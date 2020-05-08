<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use siscont\TipoGes;

use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Tipo GES
 * Rol: Administrador
 */
class TipoGesController extends Controller
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
     * Vista: tipoGes.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		if (Auth::check()) {
			$tipos = TipoGes::select('id','name','active')->orderBy('name')->paginate(10);
			
			return view('tipoGes.index',compact('tipos'));
		}
		else {
			return view('auth/login');
		}			
    }

    /**
     * Show the form for creating a new resource.
     * Vista: tipoGes.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		if (Auth::check()) {
			return view('tipoGes.create');		
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
				'name' => 'required|string|max:150|unique:tipo_ges',
			]);
			
			if ($validator->fails()) {
				return redirect('tipoGes/create')
							->withErrors($validator)
							->withInput();
			}
			else {
				$tipoGes = new tipoGes;
				
				$tipoGes->name = $request->input('name');
				$tipoGes->active = $request->input('active');
			
				$tipoGes->save();			
				
				return redirect('/tipoGes')->with('message','store');
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
     * Vista: tipoGes.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		if (Auth::check()) {
			$tipo = TipoGes::find($id);
			
			return view('tipoGes.edit',compact('tipo'));
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
				'name' => 'required|string|max:150|unique:tipo_ges,name,'.$id,
			]);
			
			if ($validator->fails()) {
				return redirect('tipoGes/'.$id.'/edit')
							->withErrors($validator)
							->withInput();
			}
			else {
				$tipo = TipoGes::find($id);
				
				$tipo->name = $request->input('name');
				$tipo->active = $request->input('active');
			
				$tipo->save();			
				
				return redirect('/tipoGes')->with('message','update');
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
