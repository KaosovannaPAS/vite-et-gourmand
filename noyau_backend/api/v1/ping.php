<?php
header('Content-Type: application/json');
echo json_encode(['status' => 'ok', 'version' => '2.0', 'time' => date('Y-m-d H:i:s')]);
?>
