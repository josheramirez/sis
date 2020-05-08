<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use siscont\TipoEspera;
use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Tipo de Espera
 * Rol: Administrador
 */
class TipoEsperasController extends Controller
{
    /** * Instantiate a new controller instance. @return void */
    public function __construct()
    {
        $this->middleware('auth');
        //Controladores de usuarios
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     * Vista: tipoEsperas.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $tipos = TipoEspera::select('id','name','active')->orderBy('name')->paginate(10);
            
            return view('tipoEsperas.index',compact('tipos'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     * Vista: tipoEsperas.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            return view('tipoEsperas.create');      
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            // validate
            $validator = validator::make($request->all(), [
                'name' => 'required|string|max:150|unique:tipo_esperas',
            ]);
            
            if ($validator->fails()) {
                return redirect('tipoEsperas/create')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $tipoconsulta = new TipoEspera;
                
                $tipoconsulta->name = $request->input('name');
                $tipoconsulta->active = $request->input('active');
            
                $tipoconsulta->save();           
                
                return redirect('/tipoEsperas')->with('message','store');
            }
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * Vista: tipoEsperas.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
            $tipo = TipoEspera::find($id);
            
            return view('tipoEsperas.edit',compact('tipo'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::check()) {
            // validate
            $validator = validator::make($request->all(), [
                'name' => 'required|string|max:150|unique:tipo_esperas,name,'.$id,
            ]);
            
            if ($validator->fails()) {
                return redirect('tipoEsperas/'.$id.'/edit')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $tipo = TipoEspera::find($id);
                
                $tipo->name = $request->input('name');
                $tipo->active = $request->input('active');
            
                $tipo->save();          
                
                return redirect('/tipoEsperas')->with('message','update');
            }
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
