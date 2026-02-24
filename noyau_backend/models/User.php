<?php
require_once __DIR__ . '/../configuration/db.php';

class User
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAllEmployees()
    {
        $stmt = $this->pdo->prepare("SELECT id, email, role, nom, prenom, telephone, adresse, created_at FROM users WHERE role IN ('employe', 'admin') ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT id, email, role, nom, prenom, telephone, adresse, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (email, password, role, nom, prenom, telephone, adresse) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        return $stmt->execute([
            $data['email'],
            $hashed_password,
            $data['role'] ?? 'user',
            $data['nom'],
            $data['prenom'],
            $data['telephone'] ?? null,
            $data['adresse'] ?? null
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
