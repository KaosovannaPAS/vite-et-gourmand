<?php
if (isset($_GET['debug_api']))
    die("API_START_SUCCESS");
if (isset($_GET['v_check']))
    die("API_VERSION_200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require_once __DIR__ . '/../../models/Menu.php';

$menuModel = new Menu();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $menu = $menuModel->getById($_GET['id']);
        echo json_encode($menu ? $menu : ["error" => "Menu non trouvé"]);
    }
    else {
        if (isset($_GET['all']) && $_GET['all'] === "true") {
            echo json_encode($menuModel->getAll()); // All menus for Admin
        }
        else { // Corrected the syntax error from the provided snippet
            $filters = [
                'theme' => $_GET['theme'] ?? null,
                'regime' => $_GET['regime'] ?? null,
                'max_price' => $_GET['max_price'] ?? null,
                'min_people' => $_GET['min_people'] ?? null
            ];
            // DEBUG block removed as per the provided snippet
            echo json_encode($menuModel->getAllActive($filters)); // Active menus for Frontend
        }
    }
}
elseif ($method === 'POST') {
    // Basic Auth Check ideally here
    $data = json_decode(file_get_contents("php://input"), true);
    if ($menuModel->create($data)) {
        echo json_encode(["message" => "Menu créé avec succès"]);
    }
    else {
        http_response_code(500);
        echo json_encode(["message" => "Erreur lors de la création"]);
    }
}
elseif ($method === 'PUT') {
    if (isset($_GET['id'])) {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($menuModel->update($_GET['id'], $data)) {
            echo json_encode(["message" => "Menu mis à jour"]);
        }
        else {
            http_response_code(500);
            echo json_encode(["message" => "Erreur lors de la mise à jour"]);
        }
    }
}
elseif ($method === 'DELETE') {
    if (isset($_GET['id'])) {
        if ($menuModel->delete($_GET['id'])) {
            echo json_encode(["message" => "Menu supprimé"]);
        }
    }
}
else {
    http_response_code(405);
    echo json_encode(["message" => "Méthode non autorisée"]);
}
?>
