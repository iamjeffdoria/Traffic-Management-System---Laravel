<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

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

$mysql = null;
$sqlite = null;

try {
    // Connect to the original MariaDB database.
    $mysql = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=tms_db;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Connect to the NativePHP SQLite database.
    $sqlite = DB::connection()->getPdo();
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sqlite->exec('PRAGMA busy_timeout = 10000');
    $sqlite->exec('PRAGMA foreign_keys = ON');

    echo "===== PRE-FLIGHT CHECK =====\n";

    // Confirm the correct SQLite database is being used.
    $databaseInfo = $sqlite->query('PRAGMA database_list')->fetchAll(PDO::FETCH_ASSOC);
    $databasePath = null;

    foreach ($databaseInfo as $database) {
        if ($database['name'] === 'main') {
            $databasePath = $database['file'];
            break;
        }
    }

    if (!$databasePath || !file_exists($databasePath)) {
        throw new RuntimeException('Could not identify the active SQLite database file.');
    }

    echo "SQLite: $databasePath\n";

    // Ensure the existing three accounts are present.
    $userCount = (int) $sqlite->query('SELECT COUNT(*) FROM users')->fetchColumn();

    if ($userCount !== 3) {
        throw new RuntimeException("Expected 3 existing users, found $userCount. Import cancelled.");
    }

    echo "Existing users: $userCount (will not be modified)\n";

    // Refuse to overwrite any existing business records.
    foreach ($tables as $table) {
        $count = (int) $sqlite->query("SELECT COUNT(*) FROM \"$table\"")->fetchColumn();

        if ($count !== 0) {
            throw new RuntimeException(
                "Destination table $table is not empty ($count records). Import cancelled."
            );
        }
    }

    echo "All 13 destination tables are empty.\n";

    // Create a consistent SQLite backup before making changes.
    $backupPath = dirname($databasePath)
        . DIRECTORY_SEPARATOR
        . 'database-before-business-import-'
        . date('Ymd-His')
        . '.sqlite';

    $sqlite->exec('VACUUM INTO ' . $sqlite->quote($backupPath));

    echo "Backup created: $backupPath\n";

    // Only these three source columns are missing in SQLite.
    $missingColumns = [
        'witness_2' => 'VARCHAR NULL',
        'witness_type_1' => "VARCHAR NOT NULL DEFAULT 'Traffic Enforcer'",
        'witness_type_2' => "VARCHAR NOT NULL DEFAULT 'Traffic Enforcer'",
    ];

    $sqliteColumns = $sqlite->query(
        'PRAGMA table_info("kasabutan_sellings")'
    )->fetchAll(PDO::FETCH_ASSOC);

    $existingColumnNames = array_column($sqliteColumns, 'name');

    foreach ($missingColumns as $column => $definition) {
        if (!in_array($column, $existingColumnNames, true)) {
            $sqlite->exec(
                "ALTER TABLE \"kasabutan_sellings\" ADD COLUMN \"$column\" $definition"
            );
            echo "Added column: kasabutan_sellings.$column\n";
        }
    }

    echo "\n===== IMPORTING RECORDS =====\n";

    $sqlite->beginTransaction();

    $totalImported = 0;

    foreach ($tables as $table) {
        // Read the source table's actual column names.
        $sourceColumns = $mysql->query(
            "SHOW COLUMNS FROM `$table`"
        )->fetchAll(PDO::FETCH_COLUMN);

        $destinationInfo = $sqlite->query(
            "PRAGMA table_info(\"$table\")"
        )->fetchAll(PDO::FETCH_ASSOC);

        $destinationColumns = array_column($destinationInfo, 'name');

        // Stop if any source column is missing from SQLite.
        $missing = array_diff($sourceColumns, $destinationColumns);

        if ($missing) {
            throw new RuntimeException(
                "$table is missing SQLite columns: " . implode(', ', $missing)
            );
        }

        $quotedColumns = array_map(
            fn($column) => "`$column`",
            $sourceColumns
        );

        $sqliteColumnsQuoted = array_map(
            fn($column) => '"' . $column . '"',
            $sourceColumns
        );

        $placeholders = implode(
            ', ',
            array_fill(0, count($sourceColumns), '?')
        );

        $insertSql = 'INSERT INTO "' . $table . '" ('
            . implode(', ', $sqliteColumnsQuoted)
            . ') VALUES (' . $placeholders . ')';

        $insert = $sqlite->prepare($insertSql);

        $select = $mysql->query(
            'SELECT ' . implode(', ', $quotedColumns)
            . " FROM `$table`"
        );

        $imported = 0;

        while ($row = $select->fetch(PDO::FETCH_NUM)) {
            $insert->execute($row);
            $imported++;
        }

        $destinationCount = (int) $sqlite->query(
            "SELECT COUNT(*) FROM \"$table\""
        )->fetchColumn();

        if ($destinationCount !== $imported) {
            throw new RuntimeException(
                "Count mismatch in $table: imported $imported, found $destinationCount."
            );
        }

        $totalImported += $imported;

        echo str_pad($table, 38)
            . " Imported: $imported\n";
    }

    // Verify foreign-key relationships before committing.
    $foreignKeyErrors = $sqlite->query(
        'PRAGMA foreign_key_check'
    )->fetchAll(PDO::FETCH_ASSOC);

    if ($foreignKeyErrors) {
        throw new RuntimeException(
            'Foreign-key validation failed: ' . json_encode($foreignKeyErrors)
        );
    }

    // Verify the existing accounts remain untouched.
    $finalUserCount = (int) $sqlite->query(
        'SELECT COUNT(*) FROM users'
    )->fetchColumn();

    if ($finalUserCount !== 3) {
        throw new RuntimeException('Existing user count changed unexpectedly.');
    }

    $sqlite->commit();

    echo "\n===== IMPORT SUCCESSFUL =====\n";
    echo "Total imported: $totalImported\n";
    echo "Existing users preserved: $finalUserCount\n";
    echo "Foreign-key check: PASSED\n";
    echo "Backup: $backupPath\n";

} catch (Throwable $e) {
    if ($sqlite && $sqlite->inTransaction()) {
        $sqlite->rollBack();
    }

    echo "\n===== IMPORT FAILED =====\n";
    echo $e->getMessage() . "\n";
    echo "SQLite transaction rolled back if it had started.\n";
    exit(1);
}
