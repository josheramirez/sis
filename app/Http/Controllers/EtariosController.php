<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use siscont\Etario;

use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Grupos Etarios
 * Rol: Administrador
 */
class EtariosController extends Controller
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
     * Vista: etarios.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		if (Auth::check()) {
			$etarios = Etario::select('id','name','active')->orderBy('name')->paginate(10);
			
			return view('etarios.index',compact('etarios'));
		}
		else {
			return view('auth/login');
		}			
    }

    /**
     * Show the form for creating a new resource.
     * Vista: etarios.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		//
		if (Auth::check()) {
			return view('etarios.create');		
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
				'name' => 'required|string|max:150|unique:etarios',
				'desde' => 'required|string|max:150|unique:etarios',
				'hasta' => 'required|string|max:150|unique:etarios',
			]);
			
			if ($validator->fails()) {
				return redirect('etarios/create')
							->withErrors($validator)
							->withInput();
			}
			else {
				$etario = new Etario;
				
				$etario->name = $request->input('name');
				$etario->desde = $request->input('desde');
				$etario->hasta = $request->input('hasta');
				$etario->active = $request->input('active');
			
				$etario->save();			
				
				return redirect('/etarios')->with('message','store');
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
     * Vista: etarios.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		if (Auth::check()) {
			$etario = Etario::find($id);
			
			return view('etarios.edit',compact('etario'));
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
				'name' => 'required|string|max:150|unique:etarios,name,'.$id,
				'desde' => 'required|string|max:150|unique:etarios,desde,'.$id,
				'hasta' => 'required|string|max:150|unique:etarios,hasta,'.$id,
			]);
			
			if ($validator->fails()) {
				return redirect('etarios/'.$id.'/edit')
							->withErrors($validator)
							->withInput();
			}
			else {
				$etario = Etario::find($id);
				
				$etario->name = $request->input('name');
				$etario->desde = $request->input('desde');
				$etario->hasta = $request->input('hasta');
				$etario->active = $request->input('active');
			
				$etario->save();			
				
				return redirect('/etarios')->with('message','update');
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
