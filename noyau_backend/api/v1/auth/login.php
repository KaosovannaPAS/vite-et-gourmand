<?php
session_start();
header('Content-Type: application/json');
include __DIR__ . '/../../configuration/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Méthode non autorisée"]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data && isset($_POST['email'])) {
    $data = $_POST;
}

if (!isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Email et mot de passe requis"]);
    exit;
}

$email = $data['email'];
$password = $data['password'];

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        $redirect = '/Vite-et-gourmand/interface_frontend/pages/dashboard.html';
        if ($user['role'] === 'admin') {
            $redirect = '/Vite-et-gourmand/interface_frontend/admin/dashboard.html';
        }
        elseif ($user['role'] === 'employe') {
            $redirect = '/Vite-et-gourmand/interface_frontend/employe/dashboard.html';
        }

        echo json_encode(["success" => true, "redirect" => $redirect, "role" => $user['role']]);
    }
    else {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect."]);
    }
}
catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erreur de connexion serveur."]);
}
