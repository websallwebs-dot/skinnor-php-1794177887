<x-filament-panels::page>
    <div class="space-y-6">

        {{-- SECCIÓN 1: SELECTOR DE PACIENTE NATIVO FILAMENT --}}
        <x-filament::section
            icon="heroicon-o-user-circle"
            heading="Expediente Clínico del Paciente"
            description="Selecciona un paciente para cargar sus registros fotográficos, evaluar lesiones y ejecutar herramientas diagnósticas."
        >
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <div class="flex-1 w-full">
                    {{ $this->form }}
                </div>
                @if ($pacienteSeleccionado)
                    <div class="pt-6 sm:pt-0">
                        <x-filament::button
                            type="button"
                            wire:click="limpiarPaciente"
                            color="gray"
                            icon="heroicon-o-arrow-path"
                        >
                            Cambiar Paciente
                        </x-filament::button>
                    </div>
                @endif
            </div>
        </x-filament::section>

        {{-- ESTADO A: SIN PACIENTE SELECCIONADO --}}
        @if (! $pacienteSeleccionado)
            <x-filament::section>
                <div class="py-8 text-center">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-primary-50 dark:bg-primary-950/60 flex items-center justify-center text-primary-600 dark:text-primary-400 mb-4 shadow-sm">
                        <x-filament::icon icon="heroicon-o-beaker" class="w-8 h-8" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Estación de Trabajo y Diagnóstico Clínico
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-lg mx-auto">
                        Selecciona un paciente en el buscador superior para comenzar o haz clic en cualquiera de los pacientes registrados:
                    </p>

                    @php $pacientesDisponibles = $this->getPacientesList(); @endphp
                    @if (! empty($pacientesDisponibles))
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-w-4xl mx-auto mt-8 text-left">
                            @foreach ($pacientesDisponibles as $p)
                                <div
                                    wire:click="seleccionarPacienteDirecto({{ $p['id'] }})"
                                    class="p-4 rounded-xl border border-gray-200 bg-white hover:border-primary-500 hover:shadow-md transition cursor-pointer dark:border-gray-800 dark:bg-gray-800 flex items-center justify-between"
                                >
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-sm dark:bg-primary-900 dark:text-primary-300 flex-shrink-0">
                                            {{ substr($p['nombre'], 0, 1) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="font-bold text-sm text-gray-900 dark:text-white truncate">
                                                {{ $p['nombre'] }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $p['rut'] }}
                                            </p>
                                        </div>
                                    </div>
                                    <x-filament::icon icon="heroicon-o-chevron-right" class="w-5 h-5 text-gray-400 flex-shrink-0" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </x-filament::section>

        {{-- ESTADO B: CON PACIENTE SELECCIONADO --}}
        @else
            {{-- TARJETAS DE MÉTRICAS CLÍNICAS (STAT CARDS) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-filament::section>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                            <x-filament::icon icon="heroicon-o-camera" class="w-5 h-5" />
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Seguimientos</span>
                            <h4 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">{{ $totalSeguimientos }}</h4>
                        </div>
                    </div>
                </x-filament::section>

                <x-filament::section>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-950 dark:text-purple-400 flex items-center justify-center flex-shrink-0">
                            <x-filament::icon icon="heroicon-o-tag" class="w-5 h-5" />
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Lesiones</span>
                            <h4 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">{{ $totalLesiones }}</h4>
                        </div>
                    </div>
                </x-filament::section>

                <x-filament::section>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                            <x-filament::icon icon="heroicon-o-calendar" class="w-5 h-5" />
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Días Monitoreo</span>
                            <h4 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">{{ $diasMonitoreo }}</h4>
                        </div>
                    </div>
                </x-filament::section>

                <x-filament::section>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
                            <x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="w-5 h-5" />
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Consultas</span>
                            <h4 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">
                                {{ count($pacienteConsultas) }}
                                @if ($consultasPendientes > 0)
                                    <span class="text-xs font-semibold text-amber-600">({{ $consultasPendientes }} pend.)</span>
                                @endif
                            </h4>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            {{-- EXPEDIENTE Y ANTECEDENTES RÁPIDOS --}}
            <x-filament::section
                icon="heroicon-o-identification"
                :heading="'Ficha: ' . $pacienteNombre"
            >
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0 flex items-center justify-center dark:bg-gray-800 dark:border-gray-700">
                            @if ($pacienteFotoUrl)
                                <img src="{{ $pacienteFotoUrl }}" alt="{{ $pacienteNombre }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-xl font-bold text-primary-600 dark:text-primary-400">
                                    {{ substr($pacienteNombre ?? 'P', 0, 1) }}
                                </span>
                            @endif
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-base font-extrabold text-gray-900 dark:text-white">
                                    {{ $pacienteNombre }}
                                </span>
                                <x-filament::badge color="gray">
                                    RUT: {{ $pacienteRut }}
                                </x-filament::badge>
                                <x-filament::badge color="info">
                                    {{ $pacienteGenero }}
                                </x-filament::badge>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400 flex-wrap">
                                <span><strong>Edad:</strong> {{ $pacienteEdad }} ({{ $pacienteFechaNacimiento }})</span>
                                <span>&bull;</span>
                                <span><strong>Teléfono:</strong> {{ $pacienteTelefono }}</span>
                                <span>&bull;</span>
                                <span><strong>Email:</strong> {{ $pacienteEmail }}</span>
                            </div>
                        </div>
                    </div>

                    @if ($pacienteAlergias && $pacienteAlergias !== 'Sin alergias registradas.')
                        <div class="rounded-xl border border-rose-200 bg-rose-50/70 p-3 text-xs text-rose-900 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-200 max-w-sm">
                            <span class="font-bold flex items-center gap-1 text-rose-700 dark:text-rose-300">
                                <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-4 h-4" /> Alerta de Alergias:
                            </span>
                            <span class="mt-0.5 block font-medium">{{ $pacienteAlergias }}</span>
                        </div>
                    @endif
                </div>
            </x-filament::section>

            {{-- PESTAÑAS (TABS) OFICIALES --}}
            <x-filament::tabs>
                <x-filament::tabs.item
                    :active="$tabActiva === 'seguimientos'"
                    wire:click="activarHerramienta('seguimientos')"
                    icon="heroicon-o-camera"
                    :badge="(string) $totalSeguimientos"
                >
                    Seguimientos y Fotografías
                </x-filament::tabs.item>

                <x-filament::tabs.item
                    :active="$tabActiva === 'metricas'"
                    wire:click="activarHerramienta('metricas')"
                    icon="heroicon-o-chart-bar"
                >
                    Evaluación ABCDE
                </x-filament::tabs.item>

                <x-filament::tabs.item
                    :active="$tabActiva === 'ficha'"
                    wire:click="activarHerramienta('ficha')"
                    icon="heroicon-o-clipboard-document-list"
                    :badge="(string) $totalLesiones"
                >
                    Historial & Lesiones
                </x-filament::tabs.item>

                <x-filament::tabs.item
                    :active="$tabActiva === 'ia'"
                    wire:click="activarHerramienta('ia')"
                    icon="heroicon-o-cpu-chip"
                >
                    Casos Similares & IA
                </x-filament::tabs.item>
            </x-filament::tabs>

            {{-- CONTENIDO PESTAÑA 1: SEGUIMIENTOS Y TABLA NATIVA --}}
            @if ($tabActiva === 'seguimientos')
                <div class="space-y-6">
                    {{-- Formulario para subir nueva foto clínica --}}
                    <x-filament::section
                        icon="heroicon-o-arrow-up-tray"
                        heading="Incorporar Nueva Fotografía Clínica"
                        description="Adjunta una fotografía dermatoscópica o clínica tomada en box para indexarla en el expediente."
                        collapsible
                    >
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                    Lesión Asociada
                                </label>
                                <select
                                    wire:model="lesionNuevaFotoId"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50/50 p-2 text-xs text-gray-900 focus:border-primary-500 focus:bg-white dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                >
                                    <option value="">-- Primera lesión / Automática --</option>
                                    @foreach ($pacienteLesiones as $les)
                                        <option value="{{ $les['id'] }}">{{ $les['nombre'] }} ({{ $les['parte_del_cuerpo'] }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                    Nota u Observación Clínica
                                </label>
                                <input
                                    type="text"
                                    wire:model="notaNuevaFoto"
                                    placeholder="Ej: Control post-tratamiento tópico..."
                                    class="w-full rounded-xl border-gray-300 bg-gray-50/50 p-2 text-xs text-gray-900 focus:border-primary-500 focus:bg-white dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                    Fotografía
                                </label>
                                <div class="flex items-center gap-2">
                                    <input
                                        type="file"
                                        wire:model="nuevaFotoPaciente"
                                        accept="image/jpeg,image/png,image/webp"
                                        class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-gray-800 dark:file:text-primary-400 cursor-pointer"
                                    />
                                    <x-filament::button
                                        type="button"
                                        wire:click="guardarNuevaFoto"
                                        wire:loading.attr="disabled"
                                        size="sm"
                                        icon="heroicon-o-check"
                                    >
                                        Subir
                                    </x-filament::button>
                                </div>
                            </div>
                        </div>
                    </x-filament::section>

                    {{-- TABLA NATIVA FILAMENT DE SEGUIMIENTOS --}}
                    <div>
                        {{ $this->table }}
                    </div>
                </div>
            @endif

            {{-- CONTENIDO PESTAÑA 2: EVALUACIÓN ABCDE --}}
            @if ($tabActiva === 'metricas')
                <x-filament::section
                    icon="heroicon-o-chart-bar"
                    heading="Evaluación Dermatoscópica ABCDE"
                    description="Asigna el puntaje para cada criterio clásico de sospecha de melanoma."
                >
                    <form wire:submit="guardarAbcde" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- A - Asimetría --}}
                            <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 dark:border-gray-800 dark:bg-gray-800/40">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-bold text-gray-900 dark:text-white uppercase">A &bull; Asimetría</span>
                                    <x-filament::badge color="primary">{{ $abcde_asimetria }} / 5</x-filament::badge>
                                </div>
                                <div class="grid grid-cols-5 gap-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button
                                            type="button"
                                            wire:click="$set('abcde_asimetria', {{ $i }})"
                                            class="py-2.5 text-xs font-bold rounded-lg border transition {{ $abcde_asimetria === $i ? 'bg-primary-600 text-white border-primary-600 shadow' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300' }}"
                                        >
                                            {{ $i }}
                                        </button>
                                    @endfor
                                </div>
                                <p class="text-[11px] text-gray-400 mt-2">1 = Completamente simétrica &bull; 5 = Asimetría marcada</p>
                            </div>

                            {{-- B - Bordes --}}
                            <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 dark:border-gray-800 dark:bg-gray-800/40">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-bold text-gray-900 dark:text-white uppercase">B &bull; Bordes</span>
                                    <x-filament::badge color="primary">{{ $abcde_bordes }} / 5</x-filament::badge>
                                </div>
                                <div class="grid grid-cols-5 gap-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button
                                            type="button"
                                            wire:click="$set('abcde_bordes', {{ $i }})"
                                            class="py-2.5 text-xs font-bold rounded-lg border transition {{ $abcde_bordes === $i ? 'bg-primary-600 text-white border-primary-600 shadow' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300' }}"
                                        >
                                            {{ $i }}
                                        </button>
                                    @endfor
                                </div>
                                <p class="text-[11px] text-gray-400 mt-2">1 = Nítidos y regulares &bull; 5 = Desdibujados o irregulares</p>
                            </div>

                            {{-- C - Color --}}
                            <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 dark:border-gray-800 dark:bg-gray-800/40">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-bold text-gray-900 dark:text-white uppercase">C &bull; Coloración</span>
                                    <x-filament::badge color="primary">{{ $abcde_color }} / 5</x-filament::badge>
                                </div>
                                <div class="grid grid-cols-5 gap-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button
                                            type="button"
                                            wire:click="$set('abcde_color', {{ $i }})"
                                            class="py-2.5 text-xs font-bold rounded-lg border transition {{ $abcde_color === $i ? 'bg-primary-600 text-white border-primary-600 shadow' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300' }}"
                                        >
                                            {{ $i }}
                                        </button>
                                    @endfor
                                </div>
                                <p class="text-[11px] text-gray-400 mt-2">1 = Tono homogéneo &bull; 5 = Múltiples pigmentos / Policromía</p>
                            </div>

                            {{-- D - Diámetro --}}
                            <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 dark:border-gray-800 dark:bg-gray-800/40">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-bold text-gray-900 dark:text-white uppercase">D &bull; Diámetro</span>
                                    <x-filament::badge color="primary">{{ $abcde_diametro }} / 5</x-filament::badge>
                                </div>
                                <div class="grid grid-cols-5 gap-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button
                                            type="button"
                                            wire:click="$set('abcde_diametro', {{ $i }})"
                                            class="py-2.5 text-xs font-bold rounded-lg border transition {{ $abcde_diametro === $i ? 'bg-primary-600 text-white border-primary-600 shadow' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300' }}"
                                        >
                                            {{ $i }}
                                        </button>
                                    @endfor
                                </div>
                                <p class="text-[11px] text-gray-400 mt-2">1 = &lt; 4 mm &bull; 5 = &gt; 6 mm</p>
                            </div>

                            {{-- E - Evolución --}}
                            <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 dark:border-gray-800 dark:bg-gray-800/40 md:col-span-2">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-bold text-gray-900 dark:text-white uppercase">E &bull; Evolución Dinámica</span>
                                    <x-filament::badge color="primary">{{ $abcde_evolucion }} / 5</x-filament::badge>
                                </div>
                                <div class="grid grid-cols-5 gap-3 max-w-lg">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button
                                            type="button"
                                            wire:click="$set('abcde_evolucion', {{ $i }})"
                                            class="py-2.5 text-xs font-bold rounded-lg border transition {{ $abcde_evolucion === $i ? 'bg-primary-600 text-white border-primary-600 shadow' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300' }}"
                                        >
                                            {{ $i }}
                                        </button>
                                    @endfor
                                </div>
                                <p class="text-[11px] text-gray-400 mt-2">1 = Estable en el tiempo &bull; 5 = Cambios rápidos, prurito o sangrado</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">
                                Juicio Clínico & Conducta Recomendada
                            </label>
                            <textarea
                                wire:model="abcde_notas"
                                rows="3"
                                placeholder="Indicar hallazgos dermatoscópicos específicos, indicación de biopsia o fecha del próximo control..."
                                class="w-full rounded-xl border-gray-300 bg-gray-50/50 p-3 text-sm focus:border-primary-500 focus:bg-white focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            ></textarea>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                @if ($abcde_resultado)
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $abcde_resultado }} &bull; <span class="text-primary-600">{{ $abcde_riesgo }}</span>
                                    </span>
                                @endif
                            </div>
                            <x-filament::button type="submit" icon="heroicon-o-check-circle">
                                Registrar Evaluación ABCDE
                            </x-filament::button>
                        </div>
                    </form>
                </x-filament::section>
            @endif

            {{-- CONTENIDO PESTAÑA 3: FICHA CLÍNICA & ANTECEDENTES --}}
            @if ($tabActiva === 'ficha')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <x-filament::section
                        icon="heroicon-o-document-text"
                        heading="Antecedentes Clínicos del Paciente"
                    >
                        <div class="space-y-4 text-xs">
                            <div>
                                <span class="font-bold text-gray-500 uppercase tracking-wider block">Historial Médico General:</span>
                                <p class="text-sm text-gray-800 dark:text-gray-200 mt-1">{{ $pacienteHistorialMedico }}</p>
                            </div>
                            <div>
                                <span class="font-bold text-gray-500 uppercase tracking-wider block">Alergias Conocidas:</span>
                                <p class="text-sm text-gray-800 dark:text-gray-200 mt-1">{{ $pacienteAlergias }}</p>
                            </div>
                            <div>
                                <span class="font-bold text-gray-500 uppercase tracking-wider block">Medicamentos Habituales:</span>
                                <p class="text-sm text-gray-800 dark:text-gray-200 mt-1">{{ $pacienteMedicamentos }}</p>
                            </div>
                            <div class="pt-3 border-t border-gray-100 dark:border-gray-800">
                                <span class="font-bold text-gray-500 uppercase tracking-wider block">Contacto de Emergencia:</span>
                                <p class="text-sm text-gray-800 dark:text-gray-200 mt-1">
                                    {{ $pacienteContactoEmergencia }} &bull; {{ $pacienteTelefonoEmergencia }}
                                </p>
                            </div>
                        </div>
                    </x-filament::section>

                    <x-filament::section
                        icon="heroicon-o-tag"
                        :heading="'Lesiones Registradas (' . count($pacienteLesiones) . ')'"
                    >
                        @if (! empty($pacienteLesiones))
                            <div class="space-y-3">
                                @foreach ($pacienteLesiones as $lesion)
                                    <div class="p-3.5 rounded-xl border border-gray-200 bg-gray-50/50 dark:border-gray-800 dark:bg-gray-800/40 flex items-center justify-between">
                                        <div>
                                            <h4 class="font-bold text-sm text-gray-900 dark:text-white">
                                                {{ $lesion['nombre'] }}
                                            </h4>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                Ubicación: {{ $lesion['parte_del_cuerpo'] }} &bull; Registrada el: {{ $lesion['fecha_inicio'] }}
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <x-filament::badge color="primary">
                                                {{ ucfirst($lesion['estado']) }}
                                            </x-filament::badge>
                                            <span class="block text-[11px] text-gray-400 mt-1">
                                                {{ $lesion['seguimientos_count'] }} fotos
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 py-4 text-center">No hay lesiones registradas aún.</p>
                        @endif
                    </x-filament::section>
                </div>
            @endif

            {{-- CONTENIDO PESTAÑA 4: INTELIGENCIA ARTIFICIAL & CASOS SIMILARES --}}
            @if ($tabActiva === 'ia')
                <x-filament::section
                    icon="heroicon-o-cpu-chip"
                    heading="Herramientas de Inteligencia Artificial & Visión Computacional"
                    description="Correlación con el dataset de referencia dermatológica HAM10000 mediante embeddings de DermFoundation."
                >
                    <div class="py-6 text-center max-w-xl mx-auto space-y-4">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950 dark:text-indigo-400 flex items-center justify-center shadow-sm">
                            <x-filament::icon icon="heroicon-o-magnifying-glass-circle" class="w-8 h-8" />
                        </div>
                        <h4 class="text-base font-bold text-gray-900 dark:text-white">
                            Búsqueda y Clasificación Semántica de Lesiones
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Puedes comparar las imágenes clínicas del paciente contra más de 10.000 casos confirmados histopatológicamente en el dataset HAM10000.
                        </p>
                        <div class="pt-2">
                            <x-filament::button
                                tag="a"
                                href="/profesional/buscar-dataset"
                                icon="heroicon-o-arrow-top-right-on-square"
                            >
                                Abrir Explorador Completo del Dataset
                            </x-filament::button>
                        </div>
                    </div>
                </x-filament::section>
            @endif
        @endif

    </div>
</x-filament-panels::page>
