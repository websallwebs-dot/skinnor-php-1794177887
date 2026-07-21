<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

function checkSqlite($path) {
    $name = basename($path);
    echo "Checking SQLite file: $name\n";
    if (!file_exists($path)) {
        echo "  File does not exist.\n";
        return;
    }
    
    config(['database.connections.temp_sqlite' => [
        'driver' => 'sqlite',
        'database' => $path,
        'prefix' => '',
    ]]);
    
    try {
        $tables = DB::connection('temp_sqlite')->select("SELECT name FROM sqlite_master WHERE type='table'");
        foreach ($tables as $t) {
            if ($t->name === 'media') {
                $count = DB::connection('temp_sqlite')->table('media')->count();
                echo "  Table: media, Count: $count\n";
                $rows = DB::connection('temp_sqlite')->table('media')->get();
                foreach ($rows as $r) {
                     echo "    ID: {$r->id}, Model: {$r->model_type}, Model ID: {$r->model_id}\n";
                }
            }
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
}

checkSqlite(database_path('database.sqlite'));
checkSqlite(database_path('tenant_clinica-test.sqlite'));
checkSqlite(database_path('tenant_dermatel.sqlite'));
checkSqlite(database_path('tenant_hhhhh.sqlite'));
