<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

function checkSqlite($path) {
    echo "Checking $path\n";
    config(['database.connections.temp_sqlite' => [
        'driver' => 'sqlite',
        'database' => $path,
    ]]);
    try {
        $tables = DB::connection('temp_sqlite')->select("SELECT name FROM sqlite_master WHERE type='table'");
        foreach ($tables as $t) {
            echo "  Table: {$t->name}\n";
            $count = DB::connection('temp_sqlite')->table($t->name)->count();
            echo "    Count: $count\n";
            if ($t->name === 'media') {
                 $rows = DB::connection('temp_sqlite')->table('media')->get();
                 foreach ($rows as $r) {
                      echo "      ID: {$r->id}, Model ID: {$r->model_id}\n";
                 }
            }
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
}

foreach (glob(database_path('*.sqlite')) as $db) {
    checkSqlite($db);
}
