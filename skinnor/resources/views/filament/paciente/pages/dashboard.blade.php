<x-filament-panels::page>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                Hola, {{ auth()->user()->name ?? 'Paciente' }}
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Resumen de tu monitoreo dermatológico.
            </p>
        </div>

        <!-- Alerta: Registro diario pendiente -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 sm:p-6 mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
            <div class="flex-1">
                <h3 class="font-bold text-gray-900 text-lg mb-1">Registro diario pendiente</h3>
                <p class="text-gray-600 text-sm">
                    Tienes {{ $lesionesPendientes }} lesión{{ $lesionesPendientes != 1 ? 'es' : '' }} pendiente{{ $lesionesPendientes != 1 ? 's' : '' }} de actualización fotográfica hoy.
                </p>
            </div>
            <div class="w-full sm:w-auto shrink-0 mt-2 sm:mt-0">
                <a href="/paciente/seguimientos/create" class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-800 transition-colors">
                    Completar registro
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <a href="/paciente/seguimientos/create" class="group">
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:border-teal-500 hover:bg-gray-50 transition-colors cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg mb-1">Nuevo registro fotográfico</h3>
                            <p class="text-gray-500 text-sm">Sube una actualización visual de tu lesión.</p>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20l12-12m4-4h3m-11 4l4 4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
            <a href="/paciente/lesiones/create" class="group">
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:border-teal-500 hover:bg-gray-50 transition-colors cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg mb-1">Registrar nueva lesión</h3>
                            <p class="text-gray-500 text-sm">Añade una mancha o lunar a tu historial.</p>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Métricas y Actividad -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Métricas -->
            <div class="lg:col-span-1 space-y-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Métricas</div>

                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500 mb-1">Lesiones activas</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $lesionesActivas }}</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500 mb-1">Días en monitoreo</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $diasMonitoreo }}</p>
                </div>
            </div>

            <!-- Actividad Reciente -->
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Actividad reciente</div>
                    <a href="/paciente/lesiones" class="text-sm font-medium text-teal-600 hover:underline flex items-center gap-1">
                        Ver catálogo <svg class="w-3 h-3 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="divide-y divide-gray-200">
                        @foreach ($alertasRecientes as $alerta)
                            <div class="p-4 sm:p-5 flex items-start gap-4 hover:bg-gray-50 transition-colors cursor-pointer block">
                                <div class="flex items-start gap-4">
                                    <div class="h-16 w-16 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center shrink-0 overflow-hidden relative">
                                        @if (!empty($alerta['paciente']['user']['foto']))
                                            <img src="{{ $alerta['paciente']['user']['foto'] }}" alt="Thumbnail" class="w-full h-full object-cover opacity-90">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                                                SIN FOTO
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <h4 class="font-bold text-gray-900 truncate text-sm">{{ $alerta['nombre'] }}</h4>
                                            <span class="text-[10px] text-gray-500 whitespace-nowrap">{{ \Illuminate\Support\Carbon::parse($alerta['updated_at'])->format('d/m/Y, H:i') }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 truncate">
                                            {{ $alerta['descripcion'] ?? 'Sin cambios visuales detectados, la forma se mantiene estable.' }}
                                        </p>
                                        <div class="mt-2 text-[10px] font-medium text-gray-500">
                                            Registro #{{ $alerta['id'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if (empty($alertasRecientes))
                            <div class="p-8 text-center text-gray-500">
                                No hay actividad reciente.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-filament-panels::page>

