<x-filament-panels::page>
    <div x-data>
        <div style="display:grid;grid-template-columns:1fr;gap:20px">
            
            {{-- FORMULARIO DE FILTROS --}}
            <div>
                {{ $this->form }}
                
                <div style="margin-top:20px;display:flex;gap:10px">
                    <x-filament::button wire:click="buscar" icon="heroicon-o-magnifying-glass">
                        Buscar
                    </x-filament::button>
                    <x-filament::button wire:click="limpiar" icon="heroicon-o-x-mark" color="gray">
                        Limpiar
                    </x-filament::button>
                </div>
            </div>

            {{-- RESULTADOS --}}
            @if($isSearching)
                <div style="display:flex;align-items:center;justify-content:center;padding:40px">
                    <div style="text-align:center">
                        <x-filament::icon icon="heroicon-o-arrow-path" style="width:48px;height:48px;animation:spin 1s linear infinite;margin-bottom:16px" />
                        <p style="color:var(--gray-500)">Buscando en el dataset...</p>
                    </div>
                </div>
            @elseif(!empty($resultados))
                <div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                        <h3 style="font-size:18px;font-weight:600;margin:0">
                            Resultados ({{ count($resultados) }})
                        </h3>
                    </div>
                    
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px">
                        @foreach($resultados as $resultado)
                            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;background:white;transition:transform 0.2s,box-shadow 0.2s"
                                 onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'"
                                 onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
                                <div style="aspect-ratio:1;overflow:hidden;background:#f3f4f6">
                                    <img src="{{ $resultado['image_url'] }}" 
                                         alt="{{ $resultado['diagnostico'] }}"
                                         style="width:100%;height:100%;object-fit:cover"
                                         onerror="this.src='https://via.placeholder.com/200?text=No+Image'" />
                                </div>
                                <div style="padding:12px">
                                    <div style="font-size:13px;font-weight:600;color:#111827;margin-bottom:4px;line-height:1.4">
                                        {{ $resultado['diagnostico'] }}
                                    </div>
                                    <div style="font-size:12px;color:#6b7280;margin-bottom:2px">
                                        <strong>ID:</strong> {{ $resultado['id'] }}
                                    </div>
                                    <div style="font-size:12px;color:#6b7280;margin-bottom:2px">
                                        <strong>Edad:</strong> {{ $resultado['edad_paciente'] }}
                                    </div>
                                    <div style="font-size:12px;color:#6b7280;margin-bottom:2px">
                                        <strong>Localización:</strong> {{ $resultado['localizacion'] }}
                                    </div>
                                    <div style="font-size:12px;color:#6b7280">
                                        <strong>Tipo:</strong> {{ $resultado['tipo_lesion'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($errorMessage)
                <div style="padding:20px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;color:#991b1b">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                        <x-filament::icon icon="heroicon-o-exclamation-triangle" style="width:20px;height:20px" />
                        <strong>Error</strong>
                    </div>
                    <p style="margin:0">{{ $errorMessage }}</p>
                </div>
            @else
                <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:60px 0;color:var(--gray-400,#9ca3af);gap:10px">
                    <x-filament::icon icon="heroicon-o-magnifying-glass" style="width:48px;height:48px;opacity:.4" />
                    <p style="font-size:14px">Selecciona los filtros y haz clic en "Buscar" para encontrar casos</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</x-filament-panels::page>
