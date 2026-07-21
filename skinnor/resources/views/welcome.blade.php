<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skinnor — Monitoreo de lesiones cutáneas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            50:  '#f0f4ff',
                            100: '#dbe4ff',
                            500: '#3b5bdb',
                            700: '#1c3a8a',
                            900: '#0f1f5c',
                        },
                        slate: {
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white text-gray-800 antialiased">

    {{-- NAVBAR --}}
    <nav class="fixed top-0 w-full bg-white/95 backdrop-blur border-b border-gray-100 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <span class="text-2xl font-bold text-navy-900">Skinnor</span>
            <div class="flex items-center gap-6">
                <a href="#como-funciona" class="text-sm text-gray-500 hover:text-navy-900 transition">Cómo funciona</a>
                <a href="#para-quien" class="text-sm text-gray-500 hover:text-navy-900 transition">Para quién</a>
                <a href="#funciones" class="text-sm text-gray-500 hover:text-navy-900 transition">Funciones</a>
                <a href="/login" class="text-sm text-navy-900 border border-navy-900 px-4 py-2 rounded-lg hover:bg-navy-900 hover:text-white transition">Iniciar sesión</a>
                <a href="/register" class="text-sm bg-navy-900 text-white px-4 py-2 rounded-lg hover:bg-navy-700 transition">Registrarse</a>
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="pt-36 pb-24 px-6 text-center">
        <div class="max-w-4xl mx-auto">
            <span class="inline-block bg-blue-50 text-blue-700 text-xs font-semibold px-4 py-1.5 rounded-full mb-6 tracking-widest uppercase">Dermatología digital</span>
            <h1 class="text-5xl font-extrabold text-navy-900 leading-tight mb-6">
                Monitorea tus lesiones cutáneas<br>con precisión clínica
            </h1>
            <p class="text-lg text-gray-500 mb-10 max-w-2xl mx-auto leading-relaxed">
                Skinnor conecta pacientes y profesionales en una plataforma inteligente para el seguimiento, análisis y reporte de lesiones de piel con inteligencia artificial.
            </p>
            <div class="flex gap-4 justify-center">
                <a href="/register" class="bg-navy-900 text-white px-8 py-3 rounded-xl text-sm font-semibold hover:bg-navy-700 transition shadow-sm">Comenzar gratis</a>
                <a href="/login" class="border border-gray-300 text-gray-600 px-8 py-3 rounded-xl text-sm font-semibold hover:border-navy-900 hover:text-navy-900 transition">Iniciar sesión</a>
            </div>
        </div>
    </section>

    {{-- CÓMO FUNCIONA --}}
    <section id="como-funciona" class="py-20 bg-gray-50 px-6">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-3xl font-bold text-navy-900 text-center mb-4">Cómo funciona</h2>
            <p class="text-gray-500 text-center mb-14 max-w-xl mx-auto">En tres simples pasos, tu historial dermatológico siempre organizado y accesible.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-14 h-14 bg-navy-900 text-white rounded-2xl flex items-center justify-center text-xl font-bold mx-auto mb-4">1</div>
                    <h3 class="font-semibold text-navy-900 mb-2">Regístrate</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Crea tu cuenta como paciente en menos de un minuto. Sin datos innecesarios.</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 bg-navy-900 text-white rounded-2xl flex items-center justify-center text-xl font-bold mx-auto mb-4">2</div>
                    <h3 class="font-semibold text-navy-900 mb-2">Registra tus lesiones</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Añade lesiones indicando ubicación, fecha y notas. Sube fotos para cada seguimiento.</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 bg-navy-900 text-white rounded-2xl flex items-center justify-center text-xl font-bold mx-auto mb-4">3</div>
                    <h3 class="font-semibold text-navy-900 mb-2">Sigue tu evolución</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Tu profesional analiza los cambios y genera reportes clínicos con IA para guiar tu tratamiento.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- PARA QUIÉN --}}
    <section id="para-quien" class="py-20 px-6">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-3xl font-bold text-navy-900 text-center mb-4">Diseñado para todos</h2>
            <p class="text-gray-500 text-center mb-14 max-w-xl mx-auto">Cada usuario tiene su propio espacio con herramientas pensadas para su rol.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="border border-gray-200 rounded-2xl p-8 hover:border-navy-900 transition">
                    <div class="text-3xl mb-4">🩹</div>
                    <h3 class="font-bold text-navy-900 text-lg mb-2">Paciente</h3>
                    <p class="text-gray-500 text-sm mb-4 leading-relaxed">Registra y da seguimiento a tus propias lesiones desde cualquier dispositivo.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>✓ Crear y ver mis lesiones</li>
                        <li>✓ Seguimientos cronológicos</li>
                        <li>✓ Subir fotos</li>
                        <li>✓ Recibir reportes del profesional</li>
                    </ul>
                </div>

                <div class="border-2 border-navy-900 rounded-2xl p-8 relative">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-navy-900 text-white text-xs px-3 py-1 rounded-full">Principal</span>
                    <div class="text-3xl mb-4">🩺</div>
                    <h3 class="font-bold text-navy-900 text-lg mb-2">Profesional</h3>
                    <p class="text-gray-500 text-sm mb-4 leading-relaxed">Supervisa a tus pacientes, analiza imágenes y genera reportes clínicos con IA.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>✓ Ver todos mis pacientes</li>
                        <li>✓ Análisis de imágenes con IA</li>
                        <li>✓ Búsqueda de similares (Pinecone)</li>
                        <li>✓ Reportes automáticos</li>
                        <li>✓ Crear datasets</li>
                    </ul>
                </div>

                <div class="border border-gray-200 rounded-2xl p-8 hover:border-navy-900 transition">
                    <div class="text-3xl mb-4">⚙️</div>
                    <h3 class="font-bold text-navy-900 text-lg mb-2">Administrador</h3>
                    <p class="text-gray-500 text-sm mb-4 leading-relaxed">Gestiona usuarios, asigna profesionales y supervisa el sistema globalmente.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>✓ Gestión de usuarios</li>
                        <li>✓ Asignar pacientes</li>
                        <li>✓ Estadísticas globales</li>
                        <li>✓ Configuración del sistema</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    {{-- FUNCIONES --}}
    <section id="funciones" class="py-20 bg-gray-50 px-6">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-3xl font-bold text-navy-900 text-center mb-4">Funciones clave</h2>
            <p class="text-gray-500 text-center mb-14 max-w-xl mx-auto">Tecnología de punta al servicio de la dermatología clínica.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-sm transition">
                    <div class="w-10 h-10 bg-navy-50 text-navy-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="font-semibold text-navy-900 mb-2">Seguimiento cronológico</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Cada lesión acumula un historial ordenado automáticamente por fecha y hora.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-sm transition">
                    <div class="w-10 h-10 bg-navy-50 text-navy-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="font-semibold text-navy-900 mb-2">Reportes con IA</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">El profesional genera reportes clínicos automáticos justificando cada evolución detectada.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-sm transition">
                    <div class="w-10 h-10 bg-navy-50 text-navy-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="font-semibold text-navy-900 mb-2">Búsqueda por similitud</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Usando Google Derm Foundation y Pinecone, encuentra imágenes similares en el dataset HAM10000.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-sm transition">
                    <div class="w-10 h-10 bg-navy-50 text-navy-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.673.337a4 4 0 01-2.506.326l-1.738-.348a2 2 0 11.785-3.922l1.738.348a6 6 0 003.76-.489l.672-.336a4 4 0 012.574-.342l2.387.477a2 2 0 002.508-2.187l-.375-2.253a6 6 0 00-3.21-4.706L15.3 4.2a2 2 0 00-2.316.325l-1.258 1.258a2 2 0 01-1.458.591H8a2 2 0 00-2 2v2a2 2 0 00.586 1.414l1.258 1.258a2 2 0 01.325 2.316l-.375 2.253a2 2 0 002.187 2.508l2.253-.375a6 6 0 014.706 3.21l1.047 1.571a2 2 0 002.316.325l1.258-1.258a2 2 0 00.325-2.316l-.375-2.253z"></path></svg>
                    </div>
                    <h3 class="font-semibold text-navy-900 mb-2">Análisis de evolución</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Algoritmos de preprocesamiento detectan cambios entre imágenes y los muestran con gráficas.</p>
                </div>

            </div>
        </div>
    </section>

    {{-- CTA FINAL --}}
    <section class="py-24 px-6 text-center">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-4xl font-bold text-navy-900 mb-4">Empieza hoy sin costo</h2>
            <p class="text-gray-500 mb-8">Regístrate como paciente y comienza a llevar el control de tu salud dermatológica.</p>
            <a href="/register" class="bg-navy-900 text-white px-10 py-4 rounded-xl text-sm font-semibold hover:bg-navy-700 transition shadow-sm">Crear cuenta gratis</a>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="border-t border-gray-100 py-8 px-6">
        <div class="max-w-6xl mx-auto flex items-center justify-between text-sm text-gray-400">
            <span class="font-semibold text-navy-900">Skinnor</span>
            <span>© {{ date('Y') }} Skinnor. Todos los derechos reservados.</span>
            <div class="flex gap-4">
                <a href="/login" class="hover:text-navy-900 transition">Iniciar sesión</a>
                <a href="/register" class="hover:text-navy-900 transition">Registro</a>
            </div>
        </div>
    </footer>

</body>
</html>
eading-relaxed">Algoritmos de preprocesamiento detectan cambios entre imágenes y los muestran con gráficas.</p>
                </div>

            </div>
        </div>
    </section>

    {{-- CTA FINAL --}}
    <section class="py-24 px-6 text-center">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-4xl font-bold text-navy-900 mb-4">Empieza hoy sin costo</h2>
            <p class="text-gray-500 mb-8">Regístrate como paciente y comienza a llevar el control de tu salud dermatológica.</p>
            <a href="/register" class="bg-navy-900 text-white px-10 py-4 rounded-xl text-sm font-semibold hover:bg-navy-700 transition shadow-sm">Crear cuenta gratis</a>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="border-t border-gray-100 py-8 px-6">
        <div class="max-w-6xl mx-auto flex items-center justify-between text-sm text-gray-400">
            <span class="font-semibold text-navy-900">Skinnor</span>
            <span>© {{ date('Y') }} Skinnor. Todos los derechos reservados.</span>
            <div class="flex gap-4">
                <a href="/login" class="hover:text-navy-900 transition">Iniciar sesión</a>
                <a href="/register" class="hover:text-navy-900 transition">Registro</a>
            </div>
        </div>
    </footer>

</body>
</html>
