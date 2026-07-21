<div>
    {{-- DEBUG --}}
    <div style="color: red; font-size: 10px;">DEBUG: Seguimiento ID: {{ $seguimiento->id }} | Media count: {{ $seguimiento->getMedia('fotos_clinicas')->count() }}</div>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px">

        {{-- CARD [+] SIEMPRE PRIMERO --}}
        <label
            style="aspect-ratio:1;border-radius:10px;border:2px dashed #d1d5db;display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;gap:8px;transition:all .15s;background:#fff"
            onmouseover="this.style.borderColor='#6366f1';this.style.background='#f5f3ff'"
            onmouseout="this.style.borderColor='#d1d5db';this.style.background='#fff'"
        >
            <div style="width:40px;height:40px;border-radius:50%;background:#ede9fe;display:flex;align-items:center;justify-content:center">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#6366f1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <span style="font-size:11px;color:#6b7280;font-weight:500">Subir foto</span>
            <input
                type="file"
                accept="image/*"
                capture="environment"
                style="display:none"
                wire:model="nuevaFoto"
            />
        </label>

        {{-- FOTOS ordenadas de más nueva a más antigua --}}
        @foreach($seguimiento->getMedia('fotos_clinicas')->sortByDesc('created_at') as $media)
            @php 
                $disk = env('MEDIA_DISK','public');
                $url = ($disk === 's3')
                    ? $media->getTemporaryUrl(now()->addMinutes(60))
                    : $media->getUrl();
            @endphp
            <div
                x-data
                x-on:click="$dispatch('open-photo', {
                    url: '{{ $url }}',
                    date: '{{ $media->created_at->format('d/m/Y H:i') }}'
                })"
                style="aspect-ratio:1;border-radius:10px;overflow:hidden;border:1px solid #e5e7eb;cursor:zoom-in;position:relative"
            >
                <img src="{{ $url }}" style="width:100%;height:100%;object-fit:cover;display:block"/>
                <span style="position:absolute;bottom:6px;right:6px;background:rgba(0,0,0,.45);color:#fff;font-size:10px;padding:2px 7px;border-radius:20px">
                    {{ $media->created_at->format('d/m/Y') }}
                </span>
            </div>
        @endforeach

    </div>

    {{-- Botón confirmar --}}
    @if($nuevaFoto)
        <div style="margin-top:12px;display:flex;align-items:center;gap:10px">
            <span style="font-size:13px;color:#374151">Foto lista para guardar</span>
            <button
                wire:click="subirFoto"
                wire:loading.attr="disabled"
                style="background:#6366f1;color:#fff;border:none;border-radius:8px;padding:7px 18px;font-size:13px;cursor:pointer;font-weight:500"
            >
                <span wire:loading.remove wire:target="subirFoto">Guardar foto</span>
                <span wire:loading wire:target="subirFoto">Guardando...</span>
            </button>
        </div>
    @endif

    {{-- MODAL foto ampliada --}}
    <div
        x-data="{ open: false, url: '', date: '' }"
        x-on:open-photo.window="open = true; url = $event.detail.url; date = $event.detail.date"
        x-show="open"
        x-cloak
        style="position:fixed;top:0;left:0;right:0;bottom:0;z-index:9999;background:rgba(0,0,0,.8);display:flex;align-items:center;justify-content:center"
        x-on:click.self="open = false"
    >
        <div style="position:relative;text-align:center">
            <img :src="url" style="max-width:88vw;max-height:80vh;border-radius:12px;object-fit:contain;display:block"/>
            <p x-text="date" style="color:#fff;font-size:12px;margin-top:10px;opacity:.8"></p>
            <button
                x-on:click="open = false"
                style="position:absolute;top:-16px;right:-16px;background:#fff;border:none;border-radius:50%;width:32px;height:32px;font-size:18px;cursor:pointer;line-height:1"
            >×</button>
        </div>
    </div>
</div>
