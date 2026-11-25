<?php

$tables = [
    'tricycles' => 250,
    'franchises' => 22,
    'mtops' => 20,
    'tricycle_mayors_permits' => 4,
    'id_cards' => 742,
    'tanods' => 5,
    'tanod_ids' => 5,
    'potpots' => 1000,
    'potpot_mayors_permits' => 501,
    'potpot_document_submissions' => 3,
    'tricycle_document_submissions' => 3,
    'kasabutan_lost_items' => 1,
    'kasabutan_sellings' => 1,
];

$sourcePath = __DIR__ . '/database/database.sqlite';

$targetPath = rtrim(getenv('APPDATA'), '\\/')
    . '/laravel-dev/database/database.sqlite';

$source = null;
$target = null;

try {
    echo "===== NATIVEPHP DATABASE TRANSFER =====\n";

    if (!file_exists($sourcePath) || !file_exists($targetPath)) {
        throw new RuntimeException('Source or destination database not found.');
    }

    if (realpath($sourcePath) === realpath($targetPath)) {
        throw new RuntimeException('Source and destination are identical.');
    }

    $source = new PDO('sqlite:' . $sourcePath);
    $target = new PDO('sqlite:' . $targetPath);

    $source->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $target->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $target->exec('PRAGMA busy_timeout = 10000');
    $target->exec('PRAGMA foreign_keys = ON');

    if ($source->query('PRAGMA integrity_check')->fetchColumn() !== 'ok') {
        throw new RuntimeException('Source database integrity check failed.');
    }

    if ($target->query('PRAGMA integrity_check')->fetchColumn() !== 'ok') {
        throw new RuntimeException('Destination database integrity check failed.');
    }

    // Ensure source record counts are exactly as expected.
    foreach ($tables as $table => $expected) {
        $count = (int) $source->query(
            "SELECT COUNT(*) FROM \"$table\""
        )->fetchColumn();

        if ($count !== $expected) {
            throw new RuntimeException(
                "$table source count mismatch: expected $expected, found $count."
            );
        }
    }

    // Destination business tables must be empty.
    foreach ($tables as $table => $expected) {
        $count = (int) $target->query(
            "SELECT COUNT(*) FROM \"$table\""
        )->fetchColumn();

        if ($count !== 0) {
            throw new RuntimeException(
                "$table is not empty ($count records). Transfer cancelled."
            );
        }
    }

    // Snapshot existing users so we can verify they remain unchanged.
    $getUserHash = function ($db) {
        $users = $db->query(
            'SELECT * FROM users ORDER BY id'
        )->fetchAll(PDO::FETCH_ASSOC);

        return hash('sha256', serialize($users));
    };

    $userHashBefore = $getUserHash($target);
    $userCountBefore = (int) $target->query(
        'SELECT COUNT(*) FROM users'
    )->fetchColumn();

    if ($userCountBefore !== 3) {
        throw new RuntimeException(
            "Expected 3 existing users, found $userCountBefore."
        );
    }

    echo "Pre-flight checks: PASSED\n";
    echo "Existing users: 3\n";
    echo "All 13 destination tables are empty.\n";

    // Back up the actual NativePHP database.
    $backupPath = dirname($targetPath)
        . '/database-before-transfer-'
        . date('Ymd-His')
        . '.sqlite';

    if (file_exists($backupPath)) {
        throw new RuntimeException('Backup filename already exists.');
    }

    $target->exec(
        'VACUUM INTO ' . $target->quote($backupPath)
    );

    echo "Backup created: $backupPath\n";

    // Start one transaction for all schema and data changes.
    $target->beginTransaction();

    // Add the three columns missing from NativePHP.
    $missingColumns = [
        'witness_2' => 'VARCHAR NULL',
        'witness_type_1' => "VARCHAR NOT NULL DEFAULT 'Traffic Enforcer'",
        'witness_type_2' => "VARCHAR NOT NULL DEFAULT 'Traffic Enforcer'",
    ];

    $columnInfo = $target->query(
        'PRAGMA table_info("kasabutan_sellings")'
    )->fetchAll(PDO::FETCH_ASSOC);

    $existingColumns = array_column($columnInfo, 'name');

    foreach ($missingColumns as $column => $definition) {
        if (!in_array($column, $existingColumns, true)) {
            $target->exec(
                "ALTER TABLE \"kasabutan_sellings\" "
                . "ADD COLUMN \"$column\" $definition"
            );

            echo "Added: kasabutan_sellings.$column\n";
        }
    }

    echo "\n===== COPYING RECORDS =====\n";

    $totalImported = 0;

    foreach ($tables as $table => $expected) {

        $sourceInfo = $source->query(
            "PRAGMA table_info(\"$table\")"
        )->fetchAll(PDO::FETCH_ASSOC);

        $targetInfo = $target->query(
            "PRAGMA table_info(\"$table\")"
        )->fetchAll(PDO::FETCH_ASSOC);

        $sourceColumns = array_column($sourceInfo, 'name');
        $targetColumns = array_column($targetInfo, 'name');

        $missing = array_diff($sourceColumns, $targetColumns);

        if ($missing) {
            throw new RuntimeException(
                "$table is missing destination columns: "
                . implode(', ', $missing)
            );
        }

        $quotedColumns = array_map(
            fn($column) => '"' . $column . '"',
            $sourceColumns
        );

        $columnList = implode(', ', $quotedColumns);

        $placeholders = implode(
            ', ',
            array_fill(0, count($sourceColumns), '?')
        );

        $select = $source->query(
            "SELECT $columnList FROM \"$table\""
        );

        $insert = $target->prepare(
            "INSERT INTO \"$table\" ($columnList) VALUES ($placeholders)"
        );

        $imported = 0;

        while ($row = $select->fetch(PDO::FETCH_NUM)) {
            $insert->execute($row);
            $imported++;
        }

        $destinationCount = (int) $target->query(
            "SELECT COUNT(*) FROM \"$table\""
        )->fetchColumn();

        if ($imported !== $expected || $destinationCount !== $expected) {
            throw new RuntimeException(
                "$table verification failed. "
                . "Expected $expected, imported $imported, "
                . "destination contains $destinationCount."
            );
        }

        $totalImported += $imported;

        echo str_pad($table, 38)
            . " $imported / $expected\n";
    }

    // Validate foreign key relationships.
    $foreignKeyErrors = $target->query(
        'PRAGMA foreign_key_check'
    )->fetchAll(PDO::FETCH_ASSOC);

    if ($foreignKeyErrors) {
        throw new RuntimeException(
            'Foreign key validation failed: '
            . json_encode($foreignKeyErrors)
        );
    }

    // Confirm the original users remain exactly unchanged.
    if ($getUserHash($target) !== $userHashBefore) {
        throw new RuntimeException(
            'Existing user accounts changed unexpectedly.'
        );
    }

    $target->commit();

    echo "\n====================================\n";
    echo "TRANSFER SUCCESSFUL!\n";
    echo "====================================\n";
    echo "Total records transferred: $totalImported\n";
    echo "Existing users preserved: $userCountBefore\n";
    echo "Foreign key check: PASSED\n";
    echo "Database integrity: PASSED\n";
    echo "Backup: $backupPath\n";

} catch (Throwable $e) {

    if ($target && $target->inTransaction()) {
        $target->rollBack();
    }

    echo "\n===== TRANSFER FAILED =====\n";
    echo $e->getMessage() . "\n";
    echo "Transaction rolled back if started.\n";
    echo "Your original NativePHP backup remains available if created.\n";

    exit(1);
}
