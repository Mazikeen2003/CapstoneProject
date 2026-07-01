<?php

$host = '127.0.0.1';
$port = 3306;
$db = 'ProjectTrackerDB';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Tables in $db:\n";
    foreach ($tables as $t) {
        echo "- $t\n";
    }

    if (in_array('migrations', $tables)) {
        echo "\nMigrations table entries:\n";
        $m = $pdo->query('SELECT id,migration,batch FROM migrations')->fetchAll(PDO::FETCH_ASSOC);
        foreach ($m as $row) {
            echo $row['id'] . ' | ' . $row['migration'] . ' | batch:' . $row['batch'] . "\n";
        }
    } else {
        echo "\nNo migrations table present.\n";
    }
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}
