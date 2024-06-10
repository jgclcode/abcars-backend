<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIP
{   
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Obtén las IPs permitidas desde la variable de entorno
        $allowedIPs = explode(',', env('ALLOWED_IPS'));

        // Verifica si la IP del cliente está en la lista de IPs permitidas
        if (!in_array($request->ip(), $allowedIPs)) {
            return response('Unauthorized', 401);
        }

        return $next($request);
    }

}
