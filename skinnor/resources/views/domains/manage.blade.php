<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Dominios — Skinnor</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-x-3">
                        <div class="bg-primary-600 p-2 rounded-lg">
                            <i class="fas fa-globe text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-gray-900">Gestión de Dominios</span>
                    </div>

                    <div class="flex items-center gap-x-4">
                        <a href="/" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition flex items-center gap-x-2">
                            <i class="fas fa-home"></i>
                            <span class="hidden sm:block">Volver al Inicio</span>
                        </a>
                        <div class="h-6 w-px bg-gray-200"></div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500 transition">
                                <i class="fas fa-sign-out-alt"></i> Salir
                            </button>
                        </form>
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

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Form Section -->
                    <div class="lg:col-span-4">
                        <section class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-base font-semibold leading-6 text-gray-900 mb-6">Nuevo Dominio</h3>
                                
                                <form method="POST" action="{{ route('domains.store') }}" class="space-y-6">
                                    @csrf

                                    <div>
                                        <label for="tenant_id" class="block text-sm font-medium leading-6 text-gray-950">Clínica</label>
                                        <div class="mt-2">
                                            <select name="tenant_id" id="tenant_id" class="block w-full rounded-lg border-0 py-2.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 bg-white" required>
                                                <option value="">Seleccionar clínica...</option>
                                                @foreach($clinicas as $clinica)
                                                    <option value="{{ $clinica->id }}" @selected(old('tenant_id') === $clinica->id)>
                                                        {{ $clinica->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('tenant_id')
                                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="domain" class="block text-sm font-medium leading-6 text-gray-950">Dominio</label>
                                        <div class="mt-2">
                                            <div class="flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-600">
                                                <span class="flex select-none items-center pl-3 text-gray-500 sm:text-sm">http://</span>
                                                <input type="text" name="domain" id="domain" class="block flex-1 border-0 bg-transparent py-2.5 pl-1 text-gray-950 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="ejemplo.skinnor.local" value="{{ old('domain') }}" required>
                                            </div>
                                        </div>
                                        @error('domain')
                                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit" class="flex w-full justify-center rounded-lg bg-primary-600 px-3 py-2.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition">
                                        Crear Dominio
                                    </button>
                                </form>
                            </div>
                        </section>

                        <!-- Local Setup Info -->
                        <div class="mt-6 rounded-xl bg-blue-50 p-6 ring-1 ring-inset ring-blue-700/10">
                            <h4 class="text-sm font-bold text-blue-900 mb-3 flex items-center gap-x-2">
                                <i class="fas fa-terminal"></i> Configuración Local
                            </h4>
                            <p class="text-xs text-blue-700 leading-relaxed mb-4">
                                Añade los dominios a tu archivo <code class="bg-white/50 px-1 font-mono">hosts</code> para que funcionen localmente:
                            </p>
                            <div class="bg-gray-950 p-3 rounded-lg font-mono text-[10px] text-blue-400 leading-tight select-all">
                                <div>127.0.0.1  clinica-test.localhost</div>
                                <div>127.0.0.1  clinica-demo.localhost</div>
                            </div>
                        </div>
                    </div>

                    <!-- List Section -->
                    <div class="lg:col-span-8">
                        <section class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 overflow-hidden">
                            <div class="border-b border-gray-200 bg-gray-50/50 px-6 py-4">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">Dominios Registrados</h3>
                            </div>
                            
                            @if($dominios->isEmpty())
                                <div class="px-6 py-20 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                        <i class="fas fa-globe text-gray-300 text-2xl"></i>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900">No hay dominios</h3>
                                    <p class="mt-1 text-sm text-gray-500">Comienza creando el primer dominio para una clínica.</p>
                                </div>
                            @else
                                <div class="divide-y divide-gray-100">
                                    @foreach($dominios->groupBy('tenant_id') as $tenantId => $tenantDomains)
                                        @php $tenant = $tenantDomains->first()->tenant; @endphp
                                        <div class="p-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="flex items-center gap-x-3">
                                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500">
                                                        <i class="fas fa-hospital"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-sm font-bold text-gray-900">{{ $tenant?->nombre ?? 'Clínica Desconocida' }}</h4>
                                                        <p class="text-xs text-gray-500">ID: {{ $tenantId }}</p>
                                                    </div>
                                                </div>
                                                <span class="inline-flex items-center rounded-full bg-primary-50 px-2.5 py-0.5 text-[10px] font-bold text-primary-700 ring-1 ring-inset ring-primary-700/10">
                                                    {{ $tenantDomains->count() }} {{ Str::plural('Dominio', $tenantDomains->count()) }}
                                                </span>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                                                @foreach($tenantDomains as $dominio)
                                                    <div class="group flex items-center justify-between rounded-lg border border-gray-200 bg-white p-3 hover:border-primary-300 hover:ring-1 hover:ring-primary-300 transition-all">
                                                        <div class="flex items-center gap-x-3 min-w-0">
                                                            <i class="fas fa-link text-gray-300 text-xs"></i>
                                                            <a href="http://{{ $dominio->domain }}/admin" target="_blank" class="text-sm font-medium text-gray-700 truncate hover:text-primary-600 transition">
                                                                {{ $dominio->domain }}
                                                            </a>
                                                        </div>
                                                        
                                                        <div class="flex items-center gap-x-2">
                                                            @if($tenantDomains->count() > 1)
                                                                <form method="POST" action="{{ route('domains.delete', $dominio) }}" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" onclick="return confirm('¿Eliminar dominio?')" class="p-1.5 text-gray-400 hover:text-red-600 rounded-md hover:bg-red-50 transition" title="Eliminar">
                                                                        <i class="fas fa-trash-alt text-xs"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            <a href="http://{{ $dominio->domain }}/admin" target="_blank" class="p-1.5 text-gray-400 hover:text-primary-600 rounded-md hover:bg-primary-50 transition" title="Visitar">
                                                                <i class="fas fa-external-link-alt text-xs"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </section>

                        <!-- Summary Cards -->
                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Clínicas</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $clinicas->count() }}</p>
                                </div>
                                <div class="bg-indigo-50 p-3 rounded-full">
                                    <i class="fas fa-hospital text-indigo-600"></i>
                                </div>
                            </div>
                            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Dominios</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $dominios->count() }}</p>
                                </div>
                                <div class="bg-blue-50 p-3 rounded-full">
                                    <i class="fas fa-globe text-blue-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
