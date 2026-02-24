<?php
require_once __DIR__ . '/../configuration/db.php';

class MenuV2
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAllActive($filters = [])
    {
        $sql = "SELECT * FROM menus WHERE is_active = 1";
        $params = [];

        if (!empty($filters['theme'])) {
            $sql .= " AND theme = ?";
            $params[] = $filters['theme'];
        }
        if (!empty($filters['regime'])) {
            if ($filters['regime'] === 'Classique') {
                $sql .= " AND (regime = 'Classique' OR regime IS NULL OR regime = '')";
            }
            else {
                $sql .= " AND regime LIKE ?";
                $params[] = '%' . $filters['regime'] . '%';
            }
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND prix <= ?";
            $params[] = $filters['max_price'];
        }
        if (!empty($filters['min_people'])) {
            $sql .= " AND min_personnes >= ?";
            $params[] = $filters['min_people'];
        }

        // Removed sorting by created_at due to production DB column missing
        // No sorting


        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->attachDishes($menus);
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM menus");
        $stmt->execute();
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->attachDishes($menus);
    }

    private function attachDishes($menus)
    {
        if (empty($menus))
            return [];
        $menuIds = array_column($menus, 'id');
        if (empty($menuIds))
            return $menus;
        $inQuery = implode(',', array_fill(0, count($menuIds), '?'));

        $stmt = $this->pdo->prepare("
            SELECT md.menu_id, d.type, d.nom
            FROM menu_dishes md
            JOIN dishes d ON md.dish_id = d.id
            WHERE md.menu_id IN ($inQuery)
            // No sorting

        ");
        $stmt->execute($menuIds);

        $dishesByMenu = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $mid = $row['menu_id'];
            $type = $row['type'];
            if (!isset($dishesByMenu[$mid]))
                $dishesByMenu[$mid] = [];
            if (!isset($dishesByMenu[$mid][$type])) {
                $dishesByMenu[$mid][$type] = $row['nom'];
            }
        }

        foreach ($menus as &$menu) {
            $menu['condensed_dishes'] = $dishesByMenu[$menu['id']] ?? null;
        }
        return $menus;
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM menus WHERE id = ?");
        $stmt->execute([$id]);
        $menu = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($menu) {
            $stmt = $this->pdo->prepare("
                SELECT d.*
                FROM dishes d
                JOIN menu_dishes md ON d.id = md.dish_id
                WHERE md.menu_id = ?
                ORDER BY d.id ASC
            ");
            $stmt->execute([$id]);
            $menu['allDishes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $menu;
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO menus (titre, description, prix, min_personnes, stock, theme, regime, image_url, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['titre'],
            $data['description'],
            $data['prix'],
            $data['min_personnes'] ?? 1,
            $data['stock'] ?? 0,
            $data['theme'] ?? null,
            $data['regime'] ?? 'classique',
            $data['image_url'] ?? null,
            $data['is_active'] ?? 1
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE menus SET titre=?, description=?, prix=?, min_personnes=?, stock=?, theme=?, regime=?, image_url=?, is_active=? WHERE id=?");
        return $stmt->execute([
            $data['titre'],
            $data['description'],
            $data['prix'],
            $data['min_personnes'],
            $data['stock'],
            $data['theme'],
            $data['regime'],
            $data['image_url'],
            $data['is_active'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM menus WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
