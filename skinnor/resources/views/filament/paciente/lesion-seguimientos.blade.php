<div>
    @if(empty($seguimientos))
        <div style="text-align:center;padding:32px 0;color:#9ca3af">
            <p style="font-size:14px">No hay seguimientos aún.</p>
            <a href="{{ $crear_url }}" style="font-size:13px;color:#6366f1;text-decoration:none;font-weight:500">+ Crear primer seguimiento</a>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px">
            @foreach($seguimientos as $seg)
                <a href="{{ $seg['view_url'] }}" style="text-decoration:none;color:inherit">
                    <div style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;transition:box-shadow .15s"
                         onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,.1)'"
                         onmouseout="this.style.boxShadow='none'">
                        <div style="aspect-ratio:1;background:#f9fafb;position:relative;overflow:hidden">
                            @if($seg['url'])
                                <img src="{{ $seg['url'] }}" style="width:100%;height:100%;object-fit:cover"/>
                            @else
                                <div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;color:#d1d5db">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:28px;height:28px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 9.75h18"/>
                                    </svg>
                                    <span style="font-size:11px">Sin foto</span>
                                </div>
                            @endif
                            @if($seg['total_fotos'] > 0)
                                <span style="position:absolute;top:6px;right:6px;background:rgba(0,0,0,.45);color:#fff;font-size:10px;padding:2px 7px;border-radius:20px">
                                    {{ $seg['total_fotos'] }} foto{{ $seg['total_fotos'] > 1 ? 's' : '' }}
                                </span>
                            @endif
                        </div>
                        <div style="padding:8px 10px">
                            <div style="font-size:12px;font-weight:500;color:#1f2937">{{ $seg['fecha'] }} · {{ $seg['hora'] }}</div>
                            @if($seg['nota'])
                                <div style="font-size:11px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $seg['nota'] }}</div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
