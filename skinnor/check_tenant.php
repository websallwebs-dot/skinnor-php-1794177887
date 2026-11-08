<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Models\Clinica;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

$domain = DB::table('domains')->where('domain', '127.0.0.1')->first();
echo 'Dominio: '.$domain->domain.PHP_EOL;
echo 'Tenant ID: '.$domain->tenant_id.PHP_EOL;

$tenant = Clinica::find($domain->tenant_id);
echo 'Clínica: '.$tenant->nombre.PHP_EOL;
echo 'Slug: '.$tenant->slug.PHP_EOL;

$tables = DB::connection('tenant')->getSchemaBuilder()->getTableListing();
echo 'Tablas en el tenant: '.count($tables).PHP_EOL;
foreach ($tables as $t) {
    echo '  - '.$t.PHP_EOL;
}

echo PHP_EOL.'Probando acceso a profesionales...'.PHP_EOL;
try {
    $count = DB::connection('tenant')->table('profesionales')->count();
    echo 'Profesionales: '.$count.PHP_EOL;
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage().PHP_EOL;
}

echo PHP_EOL.'Probando acceso a pacientes...'.PHP_EOL;
try {
    $count = DB::connection('tenant')->table('pacientes')->count();
    echo 'Pacientes: '.$count.PHP_EOL;
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage().PHP_EOL;
}

echo PHP_EOL.'Probando acceso a lesiones...'.PHP_EOL;
try {
    $count = DB::connection('tenant')->table('lesiones')->count();
    echo 'Lesiones: '.$count.PHP_EOL;
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage().PHP_EOL;
}

echo PHP_EOL.'Probando acceso a consultas...'.PHP_EOL;
try {
    $count = DB::connection('tenant')->table('consultas')->count();
    echo 'Consultas: '.$count.PHP_EOL;
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage().PHP_EOL;
}
