<x-filament-panels::page>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                Hola, {{ auth()->user()->name ?? 'Profesional' }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Resumen de tu monitoreo dermatológico.
            </p>
        </div>

        <!-- Alerta: Registro diario pendiente -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 sm:p-6 mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
            <div class="flex-1">
                <h3 class="font-bold text-gray-900 text-lg mb-1">Registro diario pendiente</h3>
                <p class="text-gray-600 text-sm">
                    Tienes {{ $lesionesPendientes ?? 0 }} lesión{{ $lesionesPendientes != 1 ? 'es' : '' }} pendiente{{ $lesionesPendientes != 1 ? 's' : '' }} de actualización fotográfica hoy.
                </p>
            </div>
            <div class="w-full sm:w-auto shrink-0 mt-2 sm:mt-0">
                <x-filament::button
                    href="{{ route('filament.profesional.resources.seguimientos.create') }}"
                    color="gray"
                    class="w-full sm:w-auto"
                >
                    Completar registro
                </x-filament::button>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <a href="{{ route('filament.profesional.resources.seguimientos.create') }}" class="group">
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm cursor-pointer hover:border-teal-500 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg mb-1">Nuevo registro fotográfico</h3>
                            <p class="text-gray-500 text-sm">Sube una actualización visual de tu lesión.</p>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                            <x-filament::icon icon="heroicon-o-camera" class="text-gray-400" />
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{ route('filament.profesional.resources.seguimientos.create') }}" class="group">
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm cursor-pointer hover:border-teal-500 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg mb-1">Registrar nueva lesión</h3>
                            <p class="text-gray-500 text-sm">Añade una mancha o lunar a tu historial.</p>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                            <x-filament::icon icon="heroicon-o-plus-circle" class="text-gray-400" />
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
                    <p class="text-3xl font-bold text-gray-900">{{ $lesionesActivas ?? 0 }}</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500 mb-1">Días en monitoreo</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $diasMonitoreo ?? 0 }}</p>
                </div>
            </div>

            <!-- Actividad Reciente -->
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Actividad reciente</div>
                    <a href="{{ route('filament.profesional.resources.seguimientos.index') }}" class="text-sm font-medium text-teal-600 hover:underline flex items-center gap-1">
                        Ver catálogo <x-filament::icon icon="heroicon-o-arrow-right" class="w-3 h-3" />
                    </a>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="divide-y divide-gray-200">
                        @foreach ($lesionesRecientes as $lesion)
                            <div class="p-4 sm:p-5 flex items-start gap-4 hover:bg-gray-50 transition-colors cursor-pointer block">
                                <div class="flex items-start gap-4">
                                    <div class="h-16 w-16 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center shrink-0 overflow-hidden relative">
                                        @if ($lesion['paciente_nombre'])
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                                {{ substr($lesion['paciente_nombre'], 0, 1) }}
                                            </div>
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                                SIN FOTO
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <h4 class="font-bold text-gray-900 truncate text-sm">{{ $lesion['nombre'] }}</h4>
                                            <span class="text-[10px] text-gray-500 whitespace-nowrap">{{ $lesion['fecha_inicio'] }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 truncate">
                                            {{ $lesion['parte_del_cuerpo'] ?? 'Sin especificar' }}
                                        </p>
                                        <div class="mt-2 text-[10px] font-medium text-gray-500">
                                            Paciente: {{ $lesion['paciente_nombre'] }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if (empty($lesionesRecientes))
                                <div class="p-8 text-center text-gray-500">
                                    <x-filament::icon icon="heroicon-o-calendar" class="w-8 h-8 mx-auto text-gray-300 mb-3" />
                                    <p class="text-sm">No hay lesiones registradas recientemente.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-filament-panels::page>
