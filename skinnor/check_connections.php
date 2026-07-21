#!/usr/bin/env php
<?php
/**
 * Script de Verificación: FastAPI + Pinecone + Local
 * Uso: php check_connections.php
 */

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  🔍 VERIFICADOR DE CONEXIONES - Búsqueda de Similares     ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";

$checks = [];

// ============================================================
// 1. FASTAPI (Derm Foundation)
// ============================================================
echo "\n[1/4] Verificando FastAPI (Derm Foundation)...\n";

$fastapi_url = "http://localhost:8001/health";
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $fastapi_url,
    CURLOPT_TIMEOUT => 5,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FAILONERROR => false,
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($http_code === 200 && $response) {
    $data = json_decode($response, true);
    echo "   ✅ CONECTADO\n";
    echo "      URL: $fastapi_url\n";
    echo "      Status: " . ($data['status'] ?? 'unknown') . "\n";
    echo "      Modelo cargado: " . ($data['model_loaded'] ? '✓' : '✗') . "\n";
    $checks['fastapi'] = true;
} else {
    echo "   ❌ DESCONECTADO\n";
    echo "      URL: $fastapi_url\n";
    echo "      Error: " . ($error ?: "HTTP $http_code") . "\n";
    echo "      Solución: Ejecuta en otra terminal:\n";
    echo "      cd ai-service && venv\\Scripts\\activate.bat\n";
    echo "      python -m uvicorn app:app --host 0.0.0.0 --port 8001\n";
    $checks['fastapi'] = false;
}

// ============================================================
// 2. PINECONE
// ============================================================
echo "\n[2/4] Verificando Pinecone API Key...\n";

$pinecone_key = getenv('PINECONE_API_KEY') ?: shell_exec('grep PINECONE_API_KEY .env | cut -d= -f2');
$pinecone_host = getenv('PINECONE_INDEX_HOST') ?: shell_exec('grep PINECONE_INDEX_HOST .env | cut -d= -f2');

$pinecone_key = trim($pinecone_key);
$pinecone_host = trim($pinecone_host);

if (!empty($pinecone_key) && !empty($pinecone_host)) {
    echo "   ✅ CONFIGURADO\n";
    echo "      API Key: " . substr($pinecone_key, 0, 10) . "...\n";
    echo "      Index Host: $pinecone_host\n";
    
    // Intentar conectar a Pinecone (timeout corto)
    $pinecone_url = "https://{$pinecone_host}/query";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $pinecone_url,
        CURLOPT_TIMEOUT => 3,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FAILONERROR => false,
        CURLOPT_HTTPHEADER => [
            "Api-Key: $pinecone_key",
            'Content-Type: application/json',
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'vector' => array_fill(0, 10, 0.1),
            'topK' => 1,
            'includeMetadata' => true,
        ]),
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if (in_array($http_code, [200, 400, 401, 403, 503])) {
        echo "      Estado: " . (in_array($http_code, [200, 400]) ? "✓ Conectado" : "⚠ HTTP $http_code") . "\n";
        $checks['pinecone'] = in_array($http_code, [200, 400]);
    } else {
        echo "      Estado: ❌ No responde (HTTP $http_code)\n";
        $checks['pinecone'] = false;
    }
} else {
    echo "   ❌ NO CONFIGURADO\n";
    echo "      Edita .env y agrega:\n";
    echo "      PINECONE_API_KEY=tu_key_aqui\n";
    echo "      PINECONE_INDEX_HOST=tu_host_aqui\n";
    $checks['pinecone'] = false;
}

// ============================================================
// 3. DATASET LOCAL HAM10000
// ============================================================
echo "\n[3/4] Verificando Dataset Local HAM10000...\n";

$local_paths = [
    'C:\\Users\\munoz\\Downloads\\HAM10000\\HAM10000_images_part_1',
    'C:\\Users\\munoz\\Downloads\\HAM10000\\HAM10000_images_part_2',
];

$found_images = 0;
foreach ($local_paths as $path) {
    if (is_dir($path)) {
        $images = glob($path . '\\*.{jpg,png,JPG,PNG}', GLOB_BRACE);
        $found_images += count($images);
        echo "   ✅ Encontrado: $path\n";
        echo "      Imágenes: " . count($images) . "\n";
    }
}

if ($found_images === 0) {
    echo "   ⚠️  NO ENCONTRADO (Fallback a Google Cloud Storage)\n";
    echo "      Rutas esperadas:\n";
    foreach ($local_paths as $path) {
        echo "      - $path\n";
    }
    $checks['local_dataset'] = false;
} else {
    echo "   ✅ DISPONIBLE: $found_images imágenes\n";
    $checks['local_dataset'] = true;
}

// ============================================================
// 4. CONFIGURACIÓN LARAVEL
// ============================================================
echo "\n[4/4] Verificando Configuración Laravel...\n";

if (file_exists('.env')) {
    $env = file_get_contents('.env');
    
    $derm_endpoint = preg_match('/DERMFOUNDATION_ENDPOINT=(.+)/i', $env, $m) ? trim($m[1]) : null;
    $hf_token = preg_match('/HF_TOKEN=(.+)/i', $env, $m) ? !empty(trim($m[1])) : false;
    
    echo "   ✅ .env ENCONTRADO\n";
    echo "      Derm Endpoint: " . ($derm_endpoint ?: '❌ NO CONFIGURADO') . "\n";
    echo "      HF Token: " . ($hf_token ? '✓ Configurado' : '⚠️ No configurado') . "\n";
    
    $checks['laravel_config'] = !empty($derm_endpoint);
} else {
    echo "   ❌ .env NO ENCONTRADO\n";
    $checks['laravel_config'] = false;
}

// ============================================================
// RESUMEN
// ============================================================
echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                        RESUMEN                             ║\n";
echo "╠════════════════════════════════════════════════════════════╣\n";

$total = count($checks);
$passed = count(array_filter($checks));

foreach ($checks as $name => $status) {
    $icon = $status ? '✅' : '❌';
    echo "║ $icon $name\n";
}

echo "╠════════════════════════════════════════════════════════════╣\n";
echo "║ $passed/$total verificaciones pasadas                          ║\n";

if ($passed === $total) {
    echo "║ ✅ SISTEMA LISTO PARA BÚSQUEDAS                         ║\n";
} else {
    echo "║ ⚠️  Faltan $" . ($total - $passed) . " configuraciones                             ║\n";
}

echo "╚════════════════════════════════════════════════════════════╝\n\n";

// ============================================================
// COMANDOS DE AYUDA
// ============================================================
if (!$checks['fastapi']) {
    echo "🔧 Para iniciar FastAPI:\n";
    echo "   cd C:\\Users\\munoz\\Downloads\\Skinnor-PHP\\skinnor\\ai-service\n";
    echo "   venv\\Scripts\\activate.bat\n";
    echo "   python -m uvicorn app:app --host 0.0.0.0 --port 8001 --reload\n\n";
}

if (!$checks['laravel_config']) {
    echo "🔧 Para configurar .env:\n";
    echo "   Edita C:\\Users\\munoz\\Downloads\\Skinnor-PHP\\skinnor\\.env\n";
    echo "   Busca DERMFOUNDATION_ENDPOINT\n";
    echo "   Verifica: DERMFOUNDATION_ENDPOINT=http://localhost:8001/embed\n\n";
}

exit($passed === $total ? 0 : 1);
