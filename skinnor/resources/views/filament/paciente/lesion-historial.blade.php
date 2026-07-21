<div>
    @if(empty($seguimientos))
        <p style="font-size:13px;color:#9ca3af;padding:16px 0">Sin registros aún.</p>
    @else
        <div style="display:flex;flex-direction:column;gap:0">
            @foreach($seguimientos as $seg)
                <a href="{{ $seg['view_url'] }}" style="text-decoration:none;color:inherit">
                    <div style="display:grid;grid-template-columns:180px 1fr 120px 100px;gap:12px;align-items:center;padding:12px 0;border-bottom:1px solid #f3f4f6;transition:background .1s"
                         onmouseover="this.style.background='#f9fafb'"
                         onmouseout="this.style.background='transparent'">

                        <div>
                            <div style="font-size:13px;font-weight:500;color:#1f2937">{{ $seg['fecha'] }} · {{ $seg['hora'] }}</div>
                            @if($seg['registrado'] ?? false)
                                <div style="font-size:11px;color:#9ca3af">Guardado el {{ $seg['registrado'] }}</div>
                            @endif
                        </div>

                        <div style="font-size:13px;color:#6b7280">
                            {{ $seg['nota'] ?? '—' }}
                        </div>

                        <div style="font-size:12px;color:#6b7280;text-align:center">
                            @if(($seg['total_fotos'] ?? 0) > 0)
                                {{ $seg['total_fotos'] }} foto{{ $seg['total_fotos'] > 1 ? 's' : '' }}
                            @else
                                <span style="color:#d1d5db">Sin fotos</span>
                            @endif
                        </div>

                        <div>
                            <span style="font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500;
                                background: {{ ($seg['subido_por'] ?? '') === 'profesional' ? '#dcfce7' : '#dbeafe' }};
                                color: {{ ($seg['subido_por'] ?? '') === 'profesional' ? '#166534' : '#1e40af' }}">
                                {{ ($seg['subido_por'] ?? '') === 'profesional' ? 'Profesional' : 'Paciente' }}
                            </span>
                        </div>

                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
