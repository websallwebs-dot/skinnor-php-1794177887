<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TenancyMiddlewareExcluder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();
        
        // Si es una ruta de Livewire, saltar el middleware de tenencia
        if (str_contains($path, 'livewire')) {
            Log::info('Saltando middleware de tenencia para ruta de Livewire', ['path' => $path]);
            
            // Desactivar temporalmente el middleware de tenancy
            $request->attributes->set('skip_tenancy', true);
        }
        
        return $next($request);
    }
}
