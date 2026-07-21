<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Clinica;
use App\Models\User;

$c = Clinica::where('id', 'clinica-test')->first();
tenancy()->initialize($c);

$u = User::where('email', 'prof@skinnor.cl')->first();
if ($u) {
    echo "User found: " . $u->name . "\n";
    if ($u->profesional) {
        echo "Profesional record found: " . $u->profesional->id . "\n";
    } else {
        echo "No profesional record found.\n";
    }
} else {
    echo "User not found.\n";
}
