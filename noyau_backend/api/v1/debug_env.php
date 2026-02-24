<?php
header('Content-Type: application/json');
echo json_encode([
    'MYSQLHOST' => [
        'value' => getenv('MYSQLHOST'),
        'length' => strlen(getenv('MYSQLHOST')),
        'hex' => bin2hex(getenv('MYSQLHOST'))
    ],
    'MYSQLPORT' => getenv('MYSQLPORT'),
    'MYSQLUSER' => getenv('MYSQLUSER'),
    'MYSQLDATABASE' => getenv('MYSQLDATABASE')
]);
?>
