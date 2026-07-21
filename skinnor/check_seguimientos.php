<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

function checkSeguimientos($path) {
    echo "Checking $path\n";
    config(['database.connections.temp_sqlite' => [
        'driver' => 'sqlite',
        'database' => $path,
    ]]);
    try {
        $rows = DB::connection('temp_sqlite')->table('seguimientos')->get();
        foreach ($rows as $r) {
            echo "  Seguimiento ID: {$r->id}, Nota: {$r->nota}\n";
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
}

foreach (glob(database_path('*.sqlite')) as $db) {
    checkSeguimientos($db);
}
