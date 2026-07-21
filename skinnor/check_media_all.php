<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $res = DB::table('media')->get();
    foreach ($res as $r) {
        echo "ID: {$r->id}, Model: {$r->model_type}, Model ID: {$r->model_id}, File: {$r->file_name}\n";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
