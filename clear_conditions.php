<?php
/**
 * clear_conditions.php
 * Clears the conditions_reservation field from all menus.
 * Delete after use.
 */
include __DIR__ . '/noyau_backend/configuration/db.php';

$stmt = $pdo->exec("UPDATE menus SET conditions_reservation = NULL WHERE conditions_reservation IS NOT NULL");
echo "<p>✅ $stmt menus mis à jour — conditions_reservation vidé.</p>";
echo "<p>Supprimez ce fichier.</p>";
?>
