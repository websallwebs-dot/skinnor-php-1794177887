<x-filament-panels::page>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                Métricas ABCDE
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Evaluación dermatoscópica según la regla ABCDE para lesiones pigmentadas.
            </p>
        </div>

        <!-- Selector de paciente -->
        <div class="mb-6">
            <div class="max-w-2xl">
                {{ $this->form }}
            </div>
        </div>

        <!-- Métricas ABCDE -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

            <!-- Tarjeta A - Asimetría -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <x-filament::icon icon="heroicon-o-arrow-left-right" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg mb-1">A - Asimetría</h3>
                            <p class="text-xs text-gray-500">Simetría de la lesión</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <label class="flex items-center gap-1 cursor-pointer px-2 py-1 rounded-md text-sm
                                @if ($abcde_asimetria === $i) bg-indigo-100 text-indigo-700 border border-indigo-300
                                @else text-gray-600 hover:bg-gray-50 border border-transparent @endif">
                                            <input type="radio" name="abcde_asimetria" value="{{ $i }}"
                                                wire:model="abcde_asimetria"
                                                class="w-4 h-4 text-indigo-600"
                                            {{ $abcde_asimetria === $i ? 'checked' : '' }}>
                                            <span>{{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                                <p class="text-[10px] text-gray-400 mt-2">1 = Simétrica, 5 = Muy asimétrica</p>
                            </div>
                        </div>

                        <!-- Tarjeta B - Bordes -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                        <x-filament::icon icon="heroicon-o-arrow-left-right" class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg mb-1">B - Bordes</h3>
                                        <p class="text-xs text-gray-500">Regularidad de los bordes</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center gap-1 cursor-pointer px-2 py-1 rounded-md text-sm
                                            @if ($abcde_bordes === $i) bg-indigo-100 text-indigo-700 border border-indigo-300
                                            @else text-gray-600 hover:bg-gray-50 border border-transparent @endif">
                                            <input type="radio" name="abcde_bordes" value="{{ $i }}"
                                                wire:model="abcde_bordes"
                                                class="w-4 h-4 text-indigo-600"
                                            {{ $abcde_bordes === $i ? 'checked' : '' }}>
                                            <span>{{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                                <p class="text-[10px] text-gray-400 mt-2">1 = Bordes regulares, 5 = Bordes irregulares</p>
                            </div>

                            <!-- Tarjeta C - Color -->
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                            <x-filament::icon icon="heroicon-o-color-swatch" class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900 text-lg mb-1">C - Color</h3>
                                            <p class="text-xs text-gray-500">Variedad de colores</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="flex items-center gap-1 cursor-pointer px-2 py-1 rounded-md text-sm
                                                @if ($abcde_color === $i) bg-indigo-100 text-indigo-700 border border-indigo-300
                                                @else text-gray-600 hover:bg-gray-50 border border-transparent @endif">
                                            <input type="radio" name="abcde_color" value="{{ $i }}"
                                                wire:model="abcde_color"
                                                class="w-4 h-4 text-indigo-600"
                                            {{ $abcde_color === $i ? 'checked' : '' }}>
                                            <span>{{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                                <p class="text-[10px] text-gray-400 mt-2">1 = Un color, 5 = Múltiples colores</p>
                            </div>

                            <!-- Tarjeta D - Diámetro -->
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                            <x-filament::icon icon="heroicon-o-ruler" class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900 text-lg mb-1">D - Diámetro</h3>
                                            <p class="text-xs text-gray-500">Tamaño de la lesión</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="flex items-center gap-1 cursor-pointer px-2 py-1 rounded-md text-sm
                                                @if ($abcde_diametro === $i) bg-indigo-100 text-indigo-700 border border-indigo-300
                                                @else text-gray-600 hover:bg-gray-50 border border-transparent @endif">
                                            <input type="radio" name="abcde_diametro" value="{{ $i }}"
                                                wire:model="abcde_diametro"
                                                class="w-4 h-4 text-indigo-600"
                                            {{ $abcde_diametro === $i ? 'checked' : '' }}>
                                            <span>{{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                                <p class="text-[10px] text-gray-400 mt-2">1 = &lt; 6mm, 5 = &gt; 7mm</p>
                            </div>

                            <!-- Tarjeta E - Evolución -->
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 md:col-span-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                            <x-filament::icon icon="heroicon-o-clock" class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900 text-lg mb-1">E - Evolución</h3>
                                            <p class="text-xs text-gray-500">Cambios en el tiempo</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="flex items-center gap-1 cursor-pointer px-2 py-1 rounded-md text-sm
                                                @if ($abcde_evolucion === $i) bg-indigo-100 text-indigo-700 border border-indigo-300
                                                @else text-gray-600 hover:bg-gray-50 border border-transparent @endif">
                                            <input type="radio" name="abcde_evolucion" value="{{ $i }}"
                                                wire:model="abcde_evolucion"
                                                class="w-4 h-4 text-indigo-600"
                                            {{ $abcde_evolucion === $i ? 'checked' : '' }}>
                                            <span>{{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                                <p class="text-[10px] text-gray-400 mt-2">1 = Sin cambios, 5 = Cambios rápidos (crecimiento, cambio de color, etc.)</p>
                            </div>
                        </div>

                        <!-- Resultado y notas -->
                        <div class="mt-4">
                            <div class="flex justify-between mb-2">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Notas clínicas</label>
                            </div>
                            <textarea wire:model="abcde_notas" rows="4" class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:border-primary-500 focus:bg-white" placeholder="Observaciones clínicas..."></textarea>
                        </div>

                        <div class="mt-6">
                            <x-filament::button
                                type="submit"
                                wire:click="calcularAbcde"
                                color="indigo"
                                class="w-full"
                            >
                                Guardar Evaluación ABCDE
                            </x-filament::button>
                        </div>

                        @if ($abcde_resultado)
                            <div class="mt-4 p-4 rounded-xl border
                                @if (str_contains($abcde_riesgo, 'Muy Alto')) bg-red-50 border-red-200
                                @elseif (str_contains($abcde_riesgo, 'Alto')) bg-amber-50 border-amber-200
                                @elseif (str_contains($abcde_riesgo, 'Moderado')) bg-yellow-50 border-yellow-200
                                @else bg-emerald-50 border-emerald-200 @endif">
                                <p class="text-sm font-bold text-gray-900 mb-1">{{ $abcde_resultado }}</p>
                                <p class="text-xs font-bold
                                    @if (str_contains($abcde_riesgo, 'Muy Alto')) text-red-700
                                    @elseif (str_contains($abcde_riesgo, 'Alto')) text-amber-700
                                    @elseif (str_contains($abcde_riesgo, 'Moderado')) text-yellow-700
                                    @else text-emerald-700 @endif">
                                    Riesgo: {{ $abcde_riesgo }}
                                </p>
                                @if ($abcde_notas)
                                    <p class="text-xs text-gray-600 mt-2">{{ $abcde_notas }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
