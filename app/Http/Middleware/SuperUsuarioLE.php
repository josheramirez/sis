<?php

namespace siscont\Http\Middleware;

use Closure;

use Auth;

/**
 * Clase Middleware para Perfil de SuperUsuario
 */
class SuperUsuarioLE
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
        if ( Auth::check() && Auth::user()->isRole('Super Usuario LE') )
        {
            return $next($request);
        }
        return redirect('home');
    }
}
