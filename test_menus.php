<?php
require 'c:/xampp/htdocs/Vite-et-gourmand/noyau_backend/models/Menu.php';
$m = new Menu();
try {
    $res = $m->getAllActive();
    echo "SUCCESS\n";
    print_r($res);
}
catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
