<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Models\Clinica;
use App\Models\Seguimiento;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

$c = Clinica::where('id', 'clinica-test')->first();
if (! $c) {
    exit("Clinica not found\n");
}
tenancy()->initialize($c);

echo 'Tenant initialized: '.$c->id."\n";

// List tables
$tables = DB::select('SHOW TABLES');
echo "Tables:\n";
print_r($tables);

// Check seguimientos
$count = Seguimiento::count();
echo 'Total seguimientos: '.$count."\n";

// Check media
$mediaCount = DB::table('media')->count();
echo 'Total media records: '.$mediaCount."\n";

if ($count > 0) {
    $seg = Seguimiento::with('media')->first();
    echo 'First seguimiento ID: '.$seg->id."\n";
    echo 'Media count for this seguimiento: '.$seg->getMedia('fotos_clinicas')->count()."\n";
}
