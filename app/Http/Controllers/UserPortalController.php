<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;
use siscont\UsuarioPortal;
use siscont\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserPortalController extends Controller
{
	public function loginPortal($identificador, $token)
    {
        $usuario_portal = UsuarioPortal::where('rut', $identificador)->where('token_portal', $token)->first();
        
        if ($usuario_portal != null && hash_equals($usuario_portal['token_portal'], $token)) {
            // db de sic en el campo email guardan el valor rut sin verificador
            
            $nuevoRut=explode("-",$identificador)[0];
            $user = User::where('email',  $nuevoRut)->first();
            
            if ($user != null) {
                Auth::loginUsingId($user->id);
                // dd($identificador, $token, $usuario_portal, $user);
                Session::put('token', $token);
                // dd("hacia home.index");
                return redirect()->route('home.index');
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('login');
        }
	}
	public function otro()
    {
		dd("otro");
       
    }
}
