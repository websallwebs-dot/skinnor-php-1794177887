<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $dbs = DB::select("SHOW DATABASES");
    foreach ($dbs as $db) {
        $name = $db->Database;
        echo "Database: $name\n";
        if (strpos($name, 'tenant_') === 0 || $name === 'skinnor') {
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
                    if ($tableName === 'media') {
                        $count = DB::connection('temp_mysql')->table('media')->count();
                        if ($count > 0) {
                            echo "  Table: media, Count: $count in DB: $name\n";
                            $rows = DB::connection('temp_mysql')->table('media')->get();
                            foreach ($rows as $r) {
                                echo "    ID: {$r->id}, Model: {$r->model_type}, Model ID: {$r->model_id}\n";
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                // skip
            }
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
