<?php

namespace siscont\Http\Middleware;

use Closure;

use Auth;

/**
 * Clase Middleware para Perfil de Edición de Pacientes (Usuario Digitador)
 */
class Pacientes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( Auth::check() && Auth::user()->isRole('Pacientes') )
        {
            return $next($request);
        }

        return redirect('alertaPacientes');
    }
}
