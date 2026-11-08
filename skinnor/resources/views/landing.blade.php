<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skinnor — Teledermatología con IA</title>
    <meta name="description" content="Sube una foto de tu lesión de piel. Skinnor mide su evolución con visión artificial y te conecta con un médico desde casa.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        primary: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                            950: '#2e1065',
                        },
                    },
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-white font-sans antialiased text-gray-950 h-full">
    <div class="flex flex-col min-h-screen">

        <!-- Header -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-x-3">
                        <div class="bg-primary-600 p-2 rounded-lg">
                            <i class="fas fa-hospital text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-gray-900">Skinnor</span>
                    </div>

                    <div class="flex items-center gap-x-4">
                        @if(Auth::check())
                            <div class="flex items-center gap-x-3 px-3 py-1.5 bg-gray-50 rounded-full border border-gray-200">
                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ Auth::user()->name }}</span>
                            </div>

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-x-2 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    <i class="fas fa-sign-out-alt text-gray-400"></i>
                                    <span>Salir</span>
                                </button>
                            </form>
                        @else
                            <a href="/login" class="inline-flex items-center gap-x-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Iniciar sesión</span>
                            </a>
                            <a href="/register" class="inline-flex items-center gap-x-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                                <i class="fas fa-user-plus"></i>
                                <span>Crear cuenta</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

                <!-- Hero -->
                <section class="text-center mb-16">
                    <span class="inline-flex items-center gap-x-2 px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-xs font-semibold tracking-wide mb-4">
                        <i class="fas fa-shield-check"></i>
                        Telemedicina dermatológica
                    </span>
                    <h1 class="text-5xl font-bold tracking-tight text-gray-900 sm:text-7xl mb-6">
                        Tu piel, <span class="text-primary-600">cuidada desde casa.</span>
                    </h1>
                    <p class="text-lg leading-8 text-gray-600 max-w-2xl mx-auto mb-8">
                        Skinnor transforma la foto de una lesión de piel en un registro clínico objetivo.
                        Algoritmos matemáticos y visión artificial miden su evolución en el tiempo y te conectan
                        con un médico — sin salir de casa.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="/login" class="inline-flex items-center gap-x-2 rounded-lg bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition duration-150">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Iniciar sesión</span>
                        </a>
                        <a href="/register" class="inline-flex items-center gap-x-2 rounded-lg border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600 transition duration-150">
                            <i class="fas fa-user-plus"></i>
                            <span>Crear cuenta</span>
                        </a>
                    </div>
                </section>

                <!-- Cómo funciona -->
                <section class="mb-16">
                    <div class="text-center mb-10">
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 mb-4">Cómo funciona</h2>
                        <p class="text-gray-600">Tres pasos simples para comenzar tu seguimiento.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="text-center p-6 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-12 h-12 rounded-lg bg-primary-100 flex items-center justify-center mx-auto mb-4 text-primary-600 text-2xl">
                                <i class="fas fa-image"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Sube una foto</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Fotografía tu lesión desde casa con cualquier dispositivo. En segundos queda registrada.
                            </p>
                        </div>
                        <div class="text-center p-6 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-12 h-12 rounded-lg bg-teal-100 flex items-center justify-center mx-auto mb-4 text-teal-600 text-2xl">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Medimos su evolución</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Algoritmos y visión artificial comparan cada foto con las anteriores y detectan cambios.
                            </p>
                        </div>
                        <div class="text-center p-6 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center mx-auto mb-4 text-indigo-600 text-2xl">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tu médico la revisa</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Un especialista revisa tu registro, responde tus dudas y define los próximos pasos.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Quién puede usar -->
                <section class="mb-16">
                    <div class="text-center mb-10">
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 mb-4">Hecha para quienes cuidan y quienes se cuidan</h2>
                        <p class="text-gray-600">Una plataforma, dos formas de acceder.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <article class="p-6 rounded-xl border border-gray-200 bg-white hover:shadow-md transition">
                            <div class="flex items-center gap-x-3 mb-4">
                                <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                                    <i class="fas fa-user text-xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Para pacientes</h3>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed mb-4">
                                Crea tu seguimiento, sube fotos de tus lesiones y comunica con tu médico asignado desde casa.
                            </p>
                            <a href="/login" class="text-sm font-semibold text-primary-600 hover:text-primary-500 flex items-center gap-x-1">
                                Iniciar sesión <i class="fas fa-arrow-right"></i>
                            </a>
                        </article>
                        <article class="p-6 rounded-xl border border-gray-200 bg-white hover:shadow-md transition">
                            <div class="flex items-center gap-x-3 mb-4">
                                <div class="bg-teal-100 p-2 rounded-lg text-teal-600">
                                    <i class="fas fa-user-md text-xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Para profesionales</h3>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed mb-4">
                                Accede a tu panel profesional para revisar pacientes, lesiones y consultas asignadas.
                            </p>
                            <a href="/login" class="text-sm font-semibold text-primary-600 hover:text-primary-500 flex items-center gap-x-1">
                                Iniciar sesión <i class="fas fa-arrow-right"></i>
                            </a>
                        </article>
                    </div>
                </section>

            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="flex items-center justify-center gap-x-2 text-gray-400 mb-4">
                    <i class="fas fa-hospital"></i>
                    <span class="font-bold text-gray-900">Skinnor</span>
                </div>
                <p class="text-sm text-gray-500">© 2026 Skinnor. Todos los derechos reservados.</p>
                <div class="mt-4 flex justify-center gap-x-6">
                    <span class="text-xs text-gray-400">Laravel v13.11</span>
                    <span class="text-xs text-gray-400">Filament v5</span>
                    <span class="text-xs text-gray-400">Tailwind CSS</span>
                </div>
            </div>
        </footer>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</body>
</html>
