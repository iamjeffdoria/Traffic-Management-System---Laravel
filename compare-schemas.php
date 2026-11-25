<?php

$source = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$target = new PDO(
    'sqlite:' . getenv('APPDATA') . '/laravel-dev/database/database.sqlite'
);

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

$hasDifferences = false;

foreach ($tables as $table) {
    $sourceColumns = $source->query(
        "PRAGMA table_info(\"$table\")"
    )->fetchAll(PDO::FETCH_ASSOC);

    $targetColumns = $target->query(
        "PRAGMA table_info(\"$table\")"
    )->fetchAll(PDO::FETCH_ASSOC);

    $sourceNames = array_column($sourceColumns, 'name');
    $targetNames = array_column($targetColumns, 'name');

    $missing = array_diff($sourceNames, $targetNames);
    $extra = array_diff($targetNames, $sourceNames);

    $sourceForeignKeys = $source->query(
        "PRAGMA foreign_key_list(\"$table\")"
    )->fetchAll(PDO::FETCH_ASSOC);

    $targetForeignKeys = $target->query(
        "PRAGMA foreign_key_list(\"$table\")"
    )->fetchAll(PDO::FETCH_ASSOC);

    $sourceFk = array_map(
        fn($fk) => implode('|', [
            $fk['from'], $fk['table'], $fk['to']
        ]),
        $sourceForeignKeys
    );

    $targetFk = array_map(
        fn($fk) => implode('|', [
            $fk['from'], $fk['table'], $fk['to']
        ]),
        $targetForeignKeys
    );

    sort($sourceFk);
    sort($targetFk);

    $sameForeignKeys = $sourceFk === $targetFk;

    if ($missing || $extra || !$sameForeignKeys) {
        $hasDifferences = true;
        echo "\nDIFFERENCE: $table\n";

        if ($missing) {
            echo "Missing in NativePHP: "
                . implode(', ', $missing) . "\n";
        }

        if ($extra) {
            echo "Extra in NativePHP: "
                . implode(', ', $extra) . "\n";
        }

        if (!$sameForeignKeys) {
            echo "Foreign key definitions differ.\n";
        }
    } else {
        echo "MATCH: $table\n";
    }
}

echo "\n============================\n";

echo $hasDifferences
    ? "SCHEMA DIFFERENCES FOUND\n"
    : "ALL 13 TABLE SCHEMAS MATCH\n";

