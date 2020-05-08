<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use siscont\TipoSalida;
use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Tipo de Salida
 * Rol: Administrador
 */
class TipoSalidasController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
        //Controladores de usuarios
        $this->middleware('admin');
    }    
    /**
     * Display a listing of the resource.
     * Vista: tipoSalidas.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $tiposalidas = TipoSalida::select('id','name','active')->orderBy('name')->paginate(10);
            
            return view('tipoSalidas.index',compact('tiposalidas'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     * Vista: tipoSalidas.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            return view('tipoSalidas.create');       
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
                'name' => 'required|string|max:150|unique:tipo_salidas',
            ]);
            
            if ($validator->fails()) {
                return redirect('tipoSalidas/create')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $tiposalidas = new TipoSalida;
                
                $tiposalidas->name = $request->input('name');
                $tiposalidas->active = $request->input('active');
            
                $tiposalidas->save();         
                
                return redirect('/tipoSalidas')->with('message','store');
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
     * Vista: tipoSalidas.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
            $tiposalidas = TipoSalida::find($id);
            
            return view('tipoSalidas.edit',compact('tiposalidas'));
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
                'name' => 'required|string|max:150|unique:tipo_salidas,name,'.$id,
            ]);
            
            if ($validator->fails()) {
                return redirect('tipoSalidas/'.$id.'/edit')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $tiposalidas = TipoSalida::find($id);
                
                $tiposalidas->name = $request->input('name');
                $tiposalidas->active = $request->input('active');
            
                $tiposalidas->save();          
                
                return redirect('/tipoSalidas')->with('message','update');
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
