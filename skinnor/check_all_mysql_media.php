<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $dbs = DB::select("SHOW DATABASES");
    foreach ($dbs as $db) {
        $name = $db->Database;
        if (in_array($name, ['information_schema', 'mysql', 'performance_schema', 'phpmyadmin', 'test'])) continue;
        
        config(['database.connections.temp_mysql' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'database' => $name,
            'username' => 'root',
            'password' => '',
        ]]);
        try {
            $tables = DB::connection('temp_mysql')->select("SHOW TABLES");
            foreach ($tables as $t) {
                $tableName = array_values((array)$t)[0];
                if ($tableName === 'imagenes_seguimiento') {
                    $count = DB::connection('temp_mysql')->table('imagenes_seguimiento')->count();
                    echo "Database: $name, Table: imagenes_seguimiento, Count: $count\n";
                }
                if ($tableName === 'media') {
                    $count = DB::connection('temp_mysql')->table('media')->count();
                    echo "Database: $name, Table: media, Count: $count\n";
                    if ($count > 0) {
                        $rows = DB::connection('temp_mysql')->table('media')->get();
                        foreach ($rows as $r) {
                            echo "    Media ID: {$r->id}, Model ID: {$r->model_id}, File: {$r->file_name}\n";
                        }
                    }
                }
            }
        } catch (Exception $e) { }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
