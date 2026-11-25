<?php

try {
    $mysql = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=tms_db;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "MariaDB connection: SUCCESS\n";
    echo "Database: " . $mysql->query("SELECT DATABASE()")->fetchColumn() . "\n";
    echo "Server: " . $mysql->query("SELECT VERSION()")->fetchColumn() . "\n";

    $tables = [
        'franchises', 'id_cards', 'tanods', 'tanod_ids',
        'tricycles', 'potpots', 'mtops',
        'potpot_mayors_permits', 'tricycle_mayors_permits',
        'potpot_document_submissions', 'tricycle_document_submissions',
        'kasabutan_lost_items', 'kasabutan_sellings'
    ];

    echo "\nSOURCE RECORD COUNTS:\n";

    foreach ($tables as $table) {
        $count = $mysql->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        echo str_pad($table, 38) . $count . "\n";
    }

} catch (Throwable $e) {
    echo "CONNECTION FAILED: " . $e->getMessage() . "\n";
    exit(1);
}
