<?php
require_once __DIR__ . '/../configuration/db.php';

class Order
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT o.*, u.nom, u.prenom FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUserId($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data, $items)
    {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, date_livraison, heure_livraison, lieu_livraison, prix_total, statut) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['user_id'],
                $data['date_livraison'],
                $data['heure_livraison'],
                $data['lieu_livraison'],
                $data['prix_total'],
                $data['statut'] ?? 'en_attente'
            ]);

            $orderId = $this->pdo->lastInsertId();

            if (!empty($items)) {
                $stmtItem = $this->pdo->prepare("INSERT INTO order_items (order_id, menu_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
                foreach ($items as $item) {
                    $stmtItem->execute([
                        $orderId,
                        $item['menu_id'],
                        $item['quantite'],
                        $item['prix_unitaire']
                    ]);
                }
            }

            $this->pdo->commit();
            return $orderId;
        }
        catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function updateStatus($id, $statut)
    {
        $stmt = $this->pdo->prepare("UPDATE orders SET statut = ? WHERE id = ?");
        return $stmt->execute([$statut, $id]);
    }
}
?>
