<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();

$tables = $pdo->query("
    SELECT name, sql
    FROM sqlite_master
    WHERE type = 'table'
    ORDER BY name
")->fetchAll(PDO::FETCH_ASSOC);

foreach ($tables as $table) {
    echo "\n========================================\n";
    echo "TABLE: " . $table['name'] . "\n";
    echo "========================================\n";
    echo $table['sql'] . "\n";
}
