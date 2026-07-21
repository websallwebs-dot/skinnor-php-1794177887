<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $tables = DB::select("SHOW TABLES");
    foreach ($tables as $t) {
        $name = array_values((array)$t)[0];
        echo "Table: $name\n";
        if ($name === 'media') {
            $count = DB::table('media')->count();
            echo "  Count: $count\n";
            $rows = DB::table('media')->get();
            foreach ($rows as $r) {
                echo "  ID: {$r->id}, Model: {$r->model_type}, Model ID: {$r->model_id}\n";
            }
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
