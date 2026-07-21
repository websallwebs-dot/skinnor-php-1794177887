<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $res = DB::table('media')
        ->where('model_type', 'App\Models\Seguimiento')
        ->select('model_id', DB::raw('count(*) as count'))
        ->groupBy('model_id')
        ->having('count', '>=', 4)
        ->get();
    
    foreach ($res as $r) {
        echo "Seguimiento ID: {$r->model_id}, Count: {$r->count}\n";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
