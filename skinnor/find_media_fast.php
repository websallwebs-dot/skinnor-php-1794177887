<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$dbs = ['skinnor', 'skinnor_central', 'skinnor_landlord', 'tenant_clinica-test', 'tenant_dermatel'];
$mediaIds = [16, 17, 18, 19];

foreach ($dbs as $dbName) {
    config(['database.connections.temp_mysql' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'database' => $dbName,
        'username' => 'root',
        'password' => '',
    ]]);
    
    try {
        $found = DB::connection('temp_mysql')->table('media')
            ->whereIn('id', $mediaIds)
            ->get();
        
        if ($found->count() > 0) {
            echo "Database: $dbName\n";
            foreach ($found as $m) {
                echo "  Media ID: {$m->id}, Model ID: {$m->model_id}, Model Type: {$m->model_type}\n";
            }
        }
    } catch (Exception $e) {}
}
