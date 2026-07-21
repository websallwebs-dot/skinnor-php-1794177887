<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Clinica;
use App\Models\User;

$c = Clinica::where('id', 'clinica-test')->first();
tenancy()->initialize($c);

$u = User::where('role', 'profesional')->first();
if ($u) {
    echo "Profesional User ID: " . $u->id . "\n";
    if ($u->profesional) {
        echo "Profesional Record ID: " . $u->profesional->id . "\n";
    } else {
        echo "No Profesional Record for user " . $u->id . "\n";
    }
} else {
    echo "No Profesional User found.\n";
}
