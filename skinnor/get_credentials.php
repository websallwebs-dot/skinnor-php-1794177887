<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Central database
echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║              BASE DE DATOS CENTRAL                         ║\n";
echo "╠════════════════════════════════════════════════════════════╣\n";

$centralUsers = DB::table('users')->select('email', 'name')->get();
foreach ($centralUsers as $user) {
    echo "║ 📧 " . str_pad($user->email, 50) . " ║\n";
    echo "║    Nombre: " . str_pad($user->name, 41) . " ║\n";
}

// Tenant database for clinica-test
echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║         CLÍNICA TEST - USUARIOS PROFESIONALES              ║\n";
echo "╠════════════════════════════════════════════════════════════╣\n";

try {
    $tenantPath = database_path('tenant_clinica-test.sqlite');
    if (file_exists($tenantPath)) {
        $tenantUsers = DB::connection('sqlite')->selectStatement(
            "ATTACH DATABASE '" . $tenantPath . "' AS tenant_db"
        );
        
        $professionals = DB::selectFromWriteConnection(
            DB::raw("SELECT email, name FROM tenant_db.users WHERE role LIKE '%profesional%' OR role LIKE '%professional%'")
        );
        
        if (empty($professionals)) {
            $professionals = DB::raw("SELECT email, name FROM tenant_db.users");
        }
        
        foreach ($professionals as $prof) {
            echo "║ 📧 " . str_pad($prof->email ?? 'N/A', 50) . " ║\n";
            echo "║    Nombre: " . str_pad($prof->name ?? 'N/A', 41) . " ║\n";
        }
    }
} catch (Exception $e) {
    echo "║ ⚠️  Error al conectar a tenant: " . $e->getMessage() . "  ║\n";
}

echo "║                                                            ║\n";
echo "║ 🔐 CONTRASEÑA (todos): password                            ║\n";
echo "║ 🌐 URL de login: http://localhost:8000/admin/login        ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
