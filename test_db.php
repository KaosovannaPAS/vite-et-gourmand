<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain');

echo "--- DIAGNOSTICS ---\n";

$hosts = [
    'google.com' => 80,
    'sql.freedb.tech' => 3306,
    '130.162.54.212' => 3306
];

foreach ($hosts as $host => $port) {
    echo "\nTesting $host:$port\n";

    // DNS Resolution
    $ip = gethostbyname($host);
    echo "DNS Resolve: $host -> $ip\n";

    // TCP Connection
    $start = microtime(true);
    $fp = @fsockopen($host, $port, $errno, $errstr, 5);
    $end = microtime(true);
    $duration = round(($end - $start) * 1000, 2);

    if ($fp) {
        echo "TCP Connect: SUCCESS (${duration}ms)\n";
        fclose($fp);
    }
    else {
        echo "TCP Connect: FAILED ($errno - $errstr)\n";
    }
}

echo "\n--- END ---\n";
?>
