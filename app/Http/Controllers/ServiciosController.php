<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use siscont\Servicio;

use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Servicios de Salud
 * Rol: Administrador
 */
class ServiciosController extends Controller
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
     * Vista: servicios.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		if (Auth::check()) {
			$servicios = Servicio::select('id','codigo','name','active')->orderBy('name')->paginate(10);
			
			return view('servicios.index',compact('servicios'));
		}
		else {
			return view('auth/login');
		}		
    }

    /**
     * Show the form for creating a new resource.
     * Vista: servicios.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		if (Auth::check()) {
			return view('servicios.create');		
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
				'name'   => 'required|string|max:150|unique:servicios',
				'codigo' => 'required|string|max:150|unique:servicios',
			]);
			
			if ($validator->fails()) {
				return redirect('servicios/create')
							->withErrors($validator)
							->withInput();
			}
			else {
				$servicio = new Servicio;
				
				$servicio->codigo = $request->input('codigo');
				$servicio->name = $request->input('name');
				$servicio->active = $request->input('active');
			
				$servicio->save();			
				
				return redirect('/servicios')->with('message','store');
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
     * Vista: servicios.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
			$servicio = Servicio::find($id);
			
			return view('servicios.edit',compact('servicio'));
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
				'codigo' => 'required|string|max:150|unique:servicios,codigo,'.$id,
				'name'   => 'required|string|max:150|unique:servicios,name,'.$id,
			]);
			
			if ($validator->fails()) {
				return redirect('servicios/'.$id.'/edit')
							->withErrors($validator)
							->withInput();
			}
			else {
				$servicio = Servicio::find($id);
				
				$servicio->codigo = $request->input('codigo');
				$servicio->name   = $request->input('name');
				$servicio->active = $request->input('active');
			
				$servicio->save();			
				
				return redirect('/servicios')->with('message','update');
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
