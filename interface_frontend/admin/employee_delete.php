<?php
session_start();
include '../../noyau_backend/configuration/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /');
    exit;
}

if (isset($_GET['id'])) {
    // Soft delete ou delete. Ici simple delete
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'employe'");
    $stmt->execute([$_GET['id']]);
}

header('Location: /interface_frontend/admin/dashboard.php');
exit;
?>
