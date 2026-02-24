<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

if (isset($_GET['diag']) && $_GET['diag'] === 'true') {
    echo json_encode([
        'MYSQLHOST' => ['v' => getenv('MYSQLHOST'), 'h' => bin2hex(getenv('MYSQLHOST'))],
        'MYSQLUSER' => ['v' => getenv('MYSQLUSER'), 'h' => bin2hex(getenv('MYSQLUSER'))],
        'MYSQLPORT' => getenv('MYSQLPORT'),
        'MYSQLDATABASE' => getenv('MYSQLDATABASE'),
        'HAS_PWD' => !empty(getenv('MYSQLPASSWORD')),
        'VER' => 'DIAG_V4'
    ]);
    exit;
}

require_once __DIR__ . '/../../models/Menu.php';

$menuModel = new Menu();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        if (isset($_GET['id'])) {
            $menu = $menuModel->getById($_GET['id']);
            echo json_encode($menu ? $menu : ["error" => "Menu non trouvé"]);
        }
        else {
            if (isset($_GET['all']) && $_GET['all'] === "true") {
                echo json_encode($menuModel->getAll()); // All menus for Admin
            }
            else {
                $filters = [
                    'theme' => $_GET['theme'] ?? null,
                    'regime' => $_GET['regime'] ?? null,
                    'max_price' => $_GET['max_price'] ?? null,
                    'min_people' => $_GET['min_people'] ?? null
                ];
                $activeMenus = $menuModel->getAllActive($filters);
                echo json_encode($activeMenus); // Active menus for Frontend
            }
        }
    }
    catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Internal Server Error", "details" => $e->getMessage()]);
    }
}
elseif ($method === 'POST') {
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
