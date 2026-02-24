<?php
session_start();
require_once __DIR__ . '/../../configuration/config.php';
require_once __DIR__ . '/../../models/User.php';

header('Content-Type: application/json; charset=utf-8');

// Basic auth check for admin API
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["error" => "Non autorisé"]);
    exit;
}

$userModel = new User();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // List employees
    echo json_encode($userModel->getAllEmployees());
}

elseif ($method === 'POST') {
    // Add employee
    $input = json_decode(file_get_contents('php://input'), true);
    if (!empty($input['email']) && !empty($input['password']) && !empty($input['nom']) && !empty($input['prenom'])) {
        $input['role'] = 'employe'; // Force role
        if ($userModel->findByEmail($input['email'])) {
            http_response_code(400);
            echo json_encode(["error" => "Cet email existe déjà"]);
            exit;
        }
        $success = $userModel->create($input);
        echo json_encode(["success" => $success]);
    }
    else {
        http_response_code(400);
        echo json_encode(["error" => "Données incomplètes"]);
    }
}

elseif ($method === 'DELETE') {
    // Delete employee
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if ($id === $_SESSION['user']['id']) {
            http_response_code(400);
            echo json_encode(["error" => "Vous ne pouvez pas vous supprimer vous-même"]);
            exit;
        }
        $success = $userModel->delete($id);
        echo json_encode(["success" => $success]);
    }
    else {
        http_response_code(400);
        echo json_encode(["error" => "ID manquant"]);
    }
}

else {
    http_response_code(405);
    echo json_encode(["error" => "Méthode non autorisée"]);
}
