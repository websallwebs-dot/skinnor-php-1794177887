<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

function checkConnection($name) {
    echo "Checking connection: $name\n";
    try {
        $tables = DB::connection($name)->select("SELECT name FROM sqlite_master WHERE type='table'");
        foreach ($tables as $t) {
            echo "  Table: {$t->name}\n";
            if ($t->name === 'media') {
                $count = DB::connection($name)->table('media')->count();
                echo "    Media count: $count\n";
                $rows = DB::connection($name)->table('media')->get();
                foreach ($rows as $r) {
                     echo "    ID: {$r->id}, Model: {$r->model_type}, Model ID: {$r->model_id}\n";
                }
            }
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
}

checkConnection('sqlite');
checkConnection('mysql');
