<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section heading="Selección de Paciente">
            {{ $this->form }}
        </x-filament::section>

        @if(!empty($evolucionABCD))
            <div class="grid grid-cols-1 gap-6">
                @foreach(['asimetria' => 'Asimetría (A)', 'bordes' => 'Bordes (B)', 'color' => 'Color (C)', 'diametro' => 'Diámetro (D)'] as $key => $label)
                    <x-filament::section :heading="$label" collapsible>
                        <div class="flex flex-col gap-4">
                            {{-- Tira cronológica --}}
                            <div class="flex gap-2 overflow-x-auto pb-4">
                                @foreach($evolucionABCD[$key] ?? [] as $punto)
                                    <div class="flex flex-col items-center min-w-[80px] border rounded-lg p-2 bg-gray-50">
                                        <img src="{{ $punto['img'] }}" class="w-16 h-16 rounded-md object-cover" />
                                        <span class="text-[10px] font-medium mt-1">{{ $punto['fecha'] }}</span>
                                        <span class="text-xs font-bold text-primary-600">{{ $punto['valor'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                            
                            {{-- Gráfico simple de tendencia --}}
                            <div class="h-32">
                                <canvas id="chart-{{ $key }}"></canvas>
                            </div>
                        </div>
                    </x-filament::section>
                @endforeach
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('livewire:load', function () {
                    @foreach(['asimetria', 'bordes', 'color', 'diametro'] as $key)
                        const ctx = document.getElementById('chart-{{ $key }}').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: {!! json_encode(array_column($evolucionABCD[$key] ?? [], 'fecha')) !!},
                                datasets: [{
                                    label: 'Tendencia',
                                    data: {!! json_encode(array_column($evolucionABCD[$key] ?? [], 'valor')) !!},
                                    borderColor: '#534AB7',
                                    backgroundColor: 'rgba(83, 74, 183, 0.1)',
                                    fill: true,
                                    tension: 0.2
                                }]
                            },
                            options: { 
                                responsive: true, 
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: { x: { display: false }, y: { beginAtZero: true } }
                            }
                        });
                    @endforeach
                });
            </script>
        @else
            <div class="text-center py-10 text-gray-500">
                Selecciona un paciente para ver la evolución de sus métricas.
            </div>
        @endif
    </div>
</x-filament-panels::page>
