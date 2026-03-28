<?php
header('Content-Type: application/json');
$vars = ['MYSQLHOST', 'MYSQLUSER', 'MYSQLPASSWORD', 'MYSQLDATABASE', 'MYSQLPORT'];
$out = [];
foreach ($vars as $v) {
    $val = getenv($v);
    $out[$v] = [
        'val' => $val,
        'len' => strlen($val),
        'hex' => bin2hex($val)
    ];
}
echo json_encode($out, JSON_PRETTY_PRINT);
?>
