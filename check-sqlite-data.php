<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = [
    'users', 'franchises', 'id_cards', 'tanods', 'tanod_ids',
    'tricycles', 'potpots', 'mtops',
    'potpot_mayors_permits', 'tricycle_mayors_permits',
    'potpot_document_submissions', 'tricycle_document_submissions',
    'kasabutan_lost_items', 'kasabutan_sellings'
];

foreach ($tables as $table) {
    $count = DB::table($table)->count();
    $maxId = DB::table($table)->max('id');

    echo str_pad($table, 35) .
         " Records: " . str_pad($count, 6) .
         " Max ID: " . ($maxId ?? 'N/A') . PHP_EOL;
}
