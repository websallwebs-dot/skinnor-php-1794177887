<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExcludeLivewireFromTenancy
{
    public function handle(Request $request, Closure $next)
    {
        // Excluir rutas de Livewire del middleware de tenancy
        $path = $request->path();
        
        if (str_contains($path, 'livewire')) {
            Log::info('Excluyendo ruta de Livewire del middleware de tenancy', ['path' => $path]);
            return $next($request);
        }

        return $next($request);
    }
}
