<?php
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . '/../../configuration/db.php';
global $pdo;

try {
    $stmt1 = $pdo->query("DESCRIBE menus");
    $stmt2 = $pdo->query("DESCRIBE dishes");
    echo json_encode([
        "menus" => $stmt1->fetchAll(PDO::FETCH_ASSOC),
        "dishes" => $stmt2->fetchAll(PDO::FETCH_ASSOC)
    ]);
}
catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
