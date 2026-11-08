<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Models\Clinica;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

echo "=== Creando tablas de tenant en base de datos SQLite ===\n\n";

// Obtener el tenant activo
$domain = DB::table('domains')->where('domain', '127.0.0.1')->first();
if (! $domain) {
    echo "❌ No se encontró dominio 127.0.0.1\n";
    exit(1);
}

$tenant = Clinica::find($domain->tenant_id);
echo 'Clínica: '.$tenant->nombre.' (ID: '.$tenant->id.")\n";
echo 'Slug: '.$tenant->slug.PHP_EOL;

$dbPath = database_path('tenant_'.$tenant->id.'.sqlite');
echo 'Base de datos: '.$dbPath.PHP_EOL;
echo 'Existe: '.(file_exists($dbPath) ? 'Sí' : 'No').PHP_EOL;
echo 'Tamaño: '.(file_exists($dbPath) ? filesize($dbPath) : 0)." bytes\n\n";

$db = DB::connection('tenant');

// Función auxiliar para crear tabla si no existe
function createTable(DB $db, string $tableName, string $sql)
{
    try {
        $tables = $db->getSchemaBuilder()->getTableListing();
        if (in_array($tableName, $tables)) {
            echo "  ⚠️  Tabla '$tableName' ya existe, saltando...\n";

            return;
        }
        $db->statement($sql);
        echo "  ✅ Tabla '$tableName' creada\n";
    } catch (Exception $e) {
        echo "  ❌ Error creando '$tableName': ".$e->getMessage()."\n";
    }
}

echo PHP_EOL."Creando tablas de negocio...\n";

try {
    DB::connection('tenant')->statement('
        CREATE TABLE IF NOT EXISTS profesionales (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            foto VARCHAR(255) NULL,
            bio TEXT NULL,
            telefono VARCHAR(255) NULL,
            especialidad VARCHAR(255) NULL,
            numero_licencia VARCHAR(255) NULL,
            rut VARCHAR(255) NULL,
            universidad VARCHAR(255) NULL,
            anos_experiencia INTEGER NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ');
    echo "✅ Tabla 'profesionales' creada\n";
} catch (Exception $e) {
    echo '❌ Error creando profesionales: '.$e->getMessage()."\n";
}

try {
    DB::connection('tenant')->statement('
        CREATE TABLE IF NOT EXISTS pacientes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            profesional_id INTEGER NULL,
            foto VARCHAR(255) NULL,
            rut VARCHAR(255) NULL,
            fecha_nacimiento DATE NULL,
            genero VARCHAR(255) NULL,
            telefono VARCHAR(255) NULL,
            nombre_contacto_emergencia VARCHAR(255) NULL,
            telefono_emergencia VARCHAR(255) NULL,
            historial_medico TEXT NULL,
            alergias TEXT NULL,
            medicamentos TEXT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (profesional_id) REFERENCES profesionales(id) ON DELETE SET NULL
        )
    ');
    echo "✅ Tabla 'pacientes' creada\n";
} catch (Exception $e) {
    echo '❌ Error creando pacientes: '.$e->getMessage()."\n";
}

try {
    DB::connection('tenant')->statement("
        CREATE TABLE IF NOT EXISTS lesiones (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            paciente_id INTEGER NOT NULL,
            nombre VARCHAR(255) NOT NULL,
            parte_del_cuerpo VARCHAR(255) NULL,
            descripcion TEXT NULL,
            fecha_inicio DATE NULL,
            estado VARCHAR(255) DEFAULT 'activa',
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE
        )
    ");
    echo "✅ Tabla 'lesiones' creada\n";
} catch (Exception $e) {
    echo '❌ Error creando lesiones: '.$e->getMessage()."\n";
}

try {
    DB::connection('tenant')->statement('
        CREATE TABLE IF NOT EXISTS consultas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            paciente_id INTEGER NOT NULL,
            profesional_id INTEGER NULL,
            mensaje TEXT NOT NULL,
            respuesta TEXT NULL,
            leida BOOLEAN DEFAULT 0,
            respondida_at TIMESTAMP NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
            FOREIGN KEY (profesional_id) REFERENCES profesionales(id) ON DELETE SET NULL
        )
    ');
    echo "✅ Tabla 'consultas' creada\n";
} catch (Exception $e) {
    echo '❌ Error creando consultas: '.$e->getMessage()."\n";
}

try {
    DB::connection('tenant')->statement("
        CREATE TABLE IF NOT EXISTS seguimientos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            lesion_id INTEGER NOT NULL,
            profesional_id INTEGER NULL,
            fecha DATE NOT NULL,
            hora TIME NULL,
            nota TEXT NULL,
            subido_por VARCHAR(255) DEFAULT 'paciente',
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (lesion_id) REFERENCES lesiones(id) ON DELETE CASCADE,
            FOREIGN KEY (profesional_id) REFERENCES profesionales(id) ON DELETE SET NULL
        )
    ");
    echo "✅ Tabla 'seguimientos' creada\n";
} catch (Exception $e) {
    echo '❌ Error creando seguimientos: '.$e->getMessage()."\n";
}

try {
    DB::connection('tenant')->statement('
        CREATE TABLE IF NOT EXISTS imagenes_seguimiento (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            seguimiento_id INTEGER NOT NULL,
            ruta VARCHAR(255) NOT NULL,
            nombre_original VARCHAR(255) NULL,
            tipo VARCHAR(255) NULL,
            tamanio INTEGER NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (seguimiento_id) REFERENCES seguimientos(id) ON DELETE CASCADE
        )
    ');
    echo "✅ Tabla 'imagenes_seguimiento' creada\n";
} catch (Exception $e) {
    echo '❌ Error creando imagenes_seguimiento: '.$e->getMessage()."\n";
}

echo PHP_EOL."Tablas en la base de datos del tenant:\n";
$tables = DB::connection('tenant')->getSchemaBuilder()->getTableListing();
foreach ($tables as $t) {
    echo '  - '.$t.PHP_EOL;
}

echo PHP_EOL."Verificando conteo de registros:\n";
try {
    $count = DB::connection('tenant')->table('profesionales')->count();
    echo '  Profesionales: '.$count.PHP_EOL;
} catch (Exception $e) {
    echo '  Error profesionales: '.$e->getMessage().PHP_EOL;
}

try {
    $count = DB::connection('tenant')->table('pacientes')->count();
    echo '  Pacientes: '.$count.PHP_EOL;
} catch (Exception $e) {
    echo '  Error pacientes: '.$e->getMessage().PHP_EOL;
}

try {
    $count = DB::connection('tenant')->table('lesiones')->count();
    echo '  Lesiones: '.$count.PHP_EOL;
} catch (Exception $e) {
    echo '  Error lesiones: '.$e->getMessage().PHP_EOL;
}

try {
    $count = DB::connection('tenant')->table('consultas')->count();
    echo '  Consultas: '.$count.PHP_EOL;
} catch (Exception $e) {
    echo '  Error consultas: '.$e->getMessage().PHP_EOL;
}

try {
    $count = DB::connection('tenant')->table('seguimientos')->count();
    echo '  Seguimientos: '.$count.PHP_EOL;
} catch (Exception $e) {
    echo '  Error seguimientos: '.$e->getMessage().PHP_EOL;
}

echo PHP_EOL."✅ ¡Tablas creadas exitosamente!\n";
