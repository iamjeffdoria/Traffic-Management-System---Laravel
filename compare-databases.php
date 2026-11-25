<?php

$databases = [
    'PROJECT' => __DIR__ . '/database/database.sqlite',
    'NATIVEPHP' => getenv('APPDATA') . '/laravel-dev/database/database.sqlite',
];

$tables = [
    'tricycles',
    'franchises',
    'mtops',
    'tricycle_mayors_permits',
    'id_cards',
    'tanods',
    'tanod_ids',
    'potpots',
    'potpot_mayors_permits',
    'potpot_document_submissions',
    'tricycle_document_submissions',
    'kasabutan_lost_items',
    'kasabutan_sellings',
];

foreach ($databases as $label => $path) {
    echo "\n===== $label DATABASE =====\n";
    echo "Path: $path\n";

    if (!file_exists($path)) {
        echo "DATABASE NOT FOUND!\n";
        continue;
    }

    $db = new PDO('sqlite:' . $path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    foreach ($tables as $table) {
        $exists = $db->query(
            "SELECT COUNT(*) FROM sqlite_master
             WHERE type = 'table' AND name = " . $db->quote($table)
        )->fetchColumn();

        if (!$exists) {
            echo "$table: TABLE MISSING\n";
            continue;
        }

        $count = $db->query(
            "SELECT COUNT(*) FROM \"$table\""
        )->fetchColumn();

        echo str_pad($table, 38) . $count . "\n";
    }

    echo "Users: " . $db->query(
        "SELECT COUNT(*) FROM users"
    )->fetchColumn() . "\n";

    echo "\n";
}
