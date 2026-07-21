<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skinnor — Sistema Dermatológico</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-950 h-full">
    <div class="flex flex-col min-h-screen">
        <!-- Header / Navbar -->
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
                                @if(Auth::user()->role === 'superadmin')
                                    <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                        SUPERADMIN
                                    </span>
                                @endif
                            </div>
                            
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-x-2 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    <i class="fas fa-sign-out-alt text-gray-400"></i>
                                    <span>Salir</span>
                                </button>
                            </form>
                        @else
                            <a href="/superadmin/login" class="inline-flex items-center gap-x-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Iniciar Sesión</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <!-- Notifications -->
                @if(session('success'))
                    <div class="rounded-xl border border-green-200 bg-green-50 p-4 mb-8">
                        <div class="flex items-center gap-x-3">
                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 mb-8">
                        <div class="flex items-center gap-x-3">
                            <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Hero Section -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                        Plataforma <span class="text-primary-600">Dermatológica</span>
                    </h1>
                    <p class="mt-4 text-lg leading-8 text-gray-600 max-w-2xl mx-auto">
                        Gestión integral de clínicas, profesionales y pacientes con tecnología de vanguardia.
                    </p>
                </div>

                <!-- Bento Grid / Feature Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    <!-- Superadmin Card -->
                    <section class="flex flex-col rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5">
                        <div class="p-6">
                            <div class="flex items-center gap-x-3 mb-4">
                                <div class="bg-amber-100 p-2.5 rounded-lg">
                                    <i class="fas fa-crown text-amber-600 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Control Central</h3>
                            </div>
                            <p class="text-sm text-gray-600 mb-6">
                                Administración global de inquilinos, configuración de dominios y gestión de seguridad del sistema.
                            </p>
                            <div class="mt-auto space-y-3">
                                @if(Auth::check() && Auth::user()->role === 'superadmin')
                                    <a href="/superadmin" class="flex items-center justify-center gap-x-2 w-full rounded-lg bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 transition">
                                        <i class="fas fa-cog"></i> Panel Superadmin
                                    </a>
                                    <a href="{{ route('domains.index') }}" class="flex items-center justify-center gap-x-2 w-full rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition">
                                        <i class="fas fa-globe"></i> Gestionar Dominios
                                    </a>
                                @else
                                    <a href="/superadmin/login" class="flex items-center justify-center gap-x-2 w-full rounded-lg bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 transition">
                                        <i class="fas fa-sign-in-alt"></i> Acceder
                                    </a>
                                @endif
                            </div>
                        </div>
                    </section>

                    <!-- Clinics Card -->
                    <section class="flex flex-col rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5">
                        <div class="p-6 flex-grow">
                            <div class="flex items-center gap-x-3 mb-4">
                                <div class="bg-primary-100 p-2.5 rounded-lg">
                                    <i class="fas fa-hospital-user text-primary-600 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Mis Clínicas</h3>
                            </div>
                            
                            @if(Auth::check())
                                <div class="space-y-2 mt-4 max-h-48 overflow-y-auto pr-1 custom-scrollbar">
                                    @forelse($clinicas as $clinica)
                                        @php $dominio = $clinica->domains()->first(); @endphp
                                        @if($dominio)
                                            <a href="http://{{ $dominio->domain }}/admin" target="_blank" class="group flex items-center justify-between rounded-lg bg-gray-50 px-4 py-3 text-sm font-medium text-gray-900 hover:bg-primary-50 hover:text-primary-700 transition border border-gray-100">
                                                <span class="truncate">{{ $clinica->nombre }}</span>
                                                <i class="fas fa-chevron-right text-gray-300 group-hover:text-primary-400 transition text-xs"></i>
                                            </a>
                                        @endif
                                    @empty
                                        <div class="text-center py-6">
                                            <p class="text-sm text-gray-500">No tienes clínicas asignadas.</p>
                                        </div>
                                    @endforelse
                                </div>
                            @else
                                <div class="text-center py-10">
                                    <p class="text-sm text-gray-500 mb-4">Inicia sesión para ver tus clínicas asignadas.</p>
                                    <a href="/superadmin/login" class="text-sm font-semibold text-primary-600 hover:text-primary-500 underline decoration-2 underline-offset-4">Identificarse →</a>
                                </div>
                            @endif
                        </div>
                    </section>

                    <!-- Fast Access Card -->
                    <section class="flex flex-col rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5">
                        <div class="p-6">
                            <div class="flex items-center gap-x-3 mb-4">
                                <div class="bg-teal-100 p-2.5 rounded-lg">
                                    <i class="fas fa-bolt text-teal-600 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Acceso Rápido</h3>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Enlaces directos por subdominio:</p>
                            <div class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto pr-1 custom-scrollbar">
                                @forelse($dominios as $dominio)
                                    <a href="http://{{ $dominio->domain }}/admin" target="_blank" class="flex items-center gap-x-2 rounded-lg px-3 py-2 text-xs font-mono text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition border border-dashed border-gray-200">
                                        <i class="fas fa-link text-gray-400"></i>
                                        <span class="truncate">{{ $dominio->domain }}</span>
                                    </a>
                                @empty
                                    <p class="text-xs text-gray-500 italic py-4">No hay dominios registrados.</p>
                                @endforelse
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Clinics Data Table -->
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50/50 px-6 py-4">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">Directorio de Clínicas</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-gray-500">Clínica</th>
                                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-gray-500">Dominio Principal</th>
                                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-gray-500">Estado</th>
                                    <th class="relative px-6 py-3 text-right">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($clinicas ?: \App\Models\Clinica::where('activa', true)->get() as $clinica)
                                    @php $dominios_clinica = $clinica->domains; @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $clinica->nombre }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $clinica->id }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($dominios_clinica->isNotEmpty())
                                                <code class="text-xs font-mono bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                                    {{ $dominios_clinica->first()->domain }}
                                                </code>
                                            @else
                                                <span class="text-xs text-red-500 italic">Pendiente</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                Activa
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($dominios_clinica->isNotEmpty())
                                                <a href="http://{{ $dominios_clinica->first()->domain }}/admin" target="_blank" class="inline-flex items-center rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition">
                                                    Entrar <i class="fas fa-external-link-alt ml-1.5 opacity-50"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <i class="fas fa-inbox text-4xl text-gray-200 mb-3"></i>
                                            <p class="text-sm text-gray-500 font-medium">No se encontraron clínicas activas.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="flex items-center justify-center gap-x-2 text-gray-400 mb-4">
                    <i class="fas fa-hospital"></i>
                    <span class="font-bold text-gray-900">Skinnor</span>
                </div>
                <p class="text-sm text-gray-500">© 2026 Sistema de Gestión Dermatológica Avanzada.</p>
                <div class="mt-4 flex justify-center gap-x-6">
                    <span class="text-xs text-gray-400">Laravel v13.11</span>
                    <span class="text-xs text-gray-400">Filament v3.x</span>
                    <span class="text-xs text-gray-400">Tailwind CSS v3.x</span>
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

