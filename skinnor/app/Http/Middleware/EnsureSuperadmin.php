<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperadmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || auth()->user()->role !== 'superadmin') {
            abort(403, 'No autorizado. Solo superadmin puede acceder.');
        }

        return $next($request);
    }
}
