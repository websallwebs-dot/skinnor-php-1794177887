<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
        
        {{-- COLUMNA PRINCIPAL: Seguimientos --}}
        <div class="lg:col-span-3">
            <div class="mb-6">
                <x-filament::section heading="Seleccionar Paciente">
                    {{ $this->form }}
                </x-filament::section>
            </div>

            @if(!empty($datosSeguimientos))
                <x-filament::section heading="Seguimientos">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($datosSeguimientos as $seg)
                            <div class="bg-white p-4 rounded-xl border shadow-sm hover:shadow-md transition cursor-pointer"
                                 wire:click="toggleSelectMedia({{ $seg['id'] }}, '{{ $seg['url'] }}')">
                                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden mb-3">
                                    <img src="{{ $seg['url'] }}" class="w-full h-full object-cover" alt="Foto seguimiento"/>
                                </div>
                                <p class="text-sm font-bold truncate">{{ $seg['fecha'] }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $seg['lesion'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </x-filament::section>
            @else
                <div class="p-8 text-center text-gray-500 border rounded-xl bg-gray-50">
                    Selecciona un paciente para visualizar sus seguimientos.
                </div>
            @endif
        </div>

        {{-- SIDEBAR HERRAMIENTAS --}}
        <div class="lg:col-span-1 space-y-4">
            <x-filament::section heading="Herramientas">
                <div class="space-y-2">
                    <x-filament::button wire:click="activarHerramienta('metricas')" color="gray" class="w-full justify-start">Métricas ABCD</x-filament::button>
                    <x-filament::button wire:click="activarHerramienta('ia')" color="gray" class="w-full justify-start">Predicción IA</x-filament::button>
                    <x-filament::button wire:click="activarHerramienta('similares')" color="gray" class="w-full justify-start">Casos similares</x-filament::button>
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
