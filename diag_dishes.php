<?php
/**
 * diag_dishes.php - Diagnostic script to check dishes/menu_dishes tables
 * Delete after use.
 */
include __DIR__ . '/noyau_backend/configuration/db.php';

echo "<h2>Diagnostic Tables</h2>";

// Check what tables exist
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "<h3>Tables existantes :</h3><ul>";
foreach ($tables as $t)
    echo "<li>$t</li>";
echo "</ul>";

// Check dishes table
if (in_array('dishes', $tables)) {
    $count = $pdo->query("SELECT COUNT(*) FROM dishes")->fetchColumn();
    echo "<p>✅ Table <strong>dishes</strong> : $count lignes</p>";
    $sample = $pdo->query("SELECT id, nom, type, is_vegan, is_gluten_free, description, allergenes FROM dishes LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>" . print_r($sample, true) . "</pre>";
}
else {
    echo "<p>❌ Table <strong>dishes</strong> n'existe PAS</p>";
}

// Check menu_dishes table
if (in_array('menu_dishes', $tables)) {
    $count = $pdo->query("SELECT COUNT(*) FROM menu_dishes")->fetchColumn();
    echo "<p>✅ Table <strong>menu_dishes</strong> : $count lignes</p>";
    $sample = $pdo->query("SELECT * FROM menu_dishes LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>" . print_r($sample, true) . "</pre>";
}
else {
    echo "<p>❌ Table <strong>menu_dishes</strong> n'existe PAS</p>";
}

// Check menus table structure
if (in_array('menus', $tables)) {
    $cols = $pdo->query("DESCRIBE menus")->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>Structure table menus :</h3><pre>" . print_r($cols, true) . "</pre>";
    $menus = $pdo->query("SELECT id, titre, theme, regime FROM menus LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>Menus (10 premiers) :</h3><pre>" . print_r($menus, true) . "</pre>";
}
?>
