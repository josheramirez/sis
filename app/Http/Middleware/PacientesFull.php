<?php

namespace siscont\Http\Middleware;

use Closure;

use Auth;

/**
 * Clase Middleware para Perfil de Edición de Pacientes (Usuario SuperUsuario)
 */
class PacientesFull
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
        if ( Auth::check() && Auth::user()->isRole('PacientesFull') )
        {
            return $next($request);
        }

        return redirect('home');
    }
}
