<?php

// Script para crear tablas directamente en la base de datos SQLite del tenant
// Sin pasar por Laravel (evita problemas de conexión)

$dbPath = 'C:\Users\munoz\Downloads\Skinnor-PHP\skinnor\database\tenant_81ebd1d4-54a1-4469-8a4c-9cd9466dd5d4.sqlite';

if (! file_exists($dbPath)) {
    echo "ERROR: El archivo de base de datos no existe: $dbPath\n";
    exit(1);
}

echo "=== Creando tablas en: $dbPath ===\n\n";

// Crear conexión SQLite
$db = new SQLite3($dbPath);

// Función para crear tabla si no existe
function createTable(SQLite3 $db, string $tableName, string $sql)
{
    try {
        // Verificar si la tabla ya existe
        $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$tableName'");
        if ($result && $result->fetchArray()) {
            echo "  ⚠️  Tabla '$tableName' ya existe, saltando...\n";

            return true;
        }

        $result = $db->exec($sql);
        if ($result === false) {
            echo "  ❌ Error creando '$tableName': ".$db->lastErrorMsg()."\n";

            return false;
        }
        echo "  ✅ Tabla '$tableName' creada\n";

        return true;
    } catch (Exception $e) {
        echo "  ❌ Error creando '$tableName': ".$e->getMessage()."\n";

        return false;
    }
}

echo "Creando tablas de negocio...\n\n";

// 1. Tabla profesionales
createTable($db, 'profesionales', '
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
        updated_at TIMESTAMP NULL
    )
');

// 2. Tabla pacientes
createTable($db, 'pacientes', '
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
        updated_at TIMESTAMP NULL
    )
');

// 3. Tabla lesiones
createTable($db, 'lesiones', "
    CREATE TABLE IF NOT EXISTS lesiones (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        paciente_id INTEGER NOT NULL,
        nombre VARCHAR(255) NOT NULL,
        parte_del_cuerpo VARCHAR(255) NULL,
        descripcion TEXT NULL,
        fecha_inicio DATE NULL,
        estado VARCHAR(255) DEFAULT 'activa',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )
");

// 4. Tabla consultas
createTable($db, 'consultas', '
    CREATE TABLE IF NOT EXISTS consultas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        paciente_id INTEGER NOT NULL,
        profesional_id INTEGER NULL,
        mensaje TEXT NOT NULL,
        respuesta TEXT NULL,
        leida BOOLEAN DEFAULT 0,
        respondida_at TIMESTAMP NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )
');

// 5. Tabla seguimientos
createTable($db, 'seguimientos', "
    CREATE TABLE IF NOT EXISTS seguimientos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        lesion_id INTEGER NOT NULL,
        profesional_id INTEGER NULL,
        fecha DATE NOT NULL,
        hora TIME NULL,
        nota TEXT NULL,
        subido_por VARCHAR(255) DEFAULT 'paciente',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )
");

// 6. Tabla imagenes_seguimiento
createTable($db, 'imagenes_seguimiento', '
    CREATE TABLE IF NOT EXISTS imagenes_seguimiento (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        seguimiento_id INTEGER NOT NULL,
        ruta VARCHAR(255) NOT NULL,
        nombre_original VARCHAR(255) NULL,
        tipo VARCHAR(255) NULL,
        tamanio INTEGER NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )
');

echo PHP_EOL."Tablas en la base de datos:\n";
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
while ($row = $result->fetchArray()) {
    echo '  - '.$row['name'].PHP_EOL;
}

echo PHP_EOL."Verificando conteo de registros:\n";
$tables = ['profesionales', 'pacientes', 'lesiones', 'consultas', 'seguimientos', 'imagenes_seguimiento'];
foreach ($tables as $table) {
    try {
        $count = $db->querySingle("SELECT COUNT(*) FROM $table");
        echo "  $table: $count registros\n";
    } catch (Exception $e) {
        echo "  $table: Error - ".$e->getMessage().PHP_EOL;
    }
}

echo PHP_EOL."✅ ¡Tablas creadas exitosamente!\n";
$db->close();
