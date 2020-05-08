<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use siscont\Protocolo;

use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Protocolos
 * Rol: Administrador
 */
class ProtocolosController extends Controller
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
     * Vista: protocolos.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		if (Auth::check()) {
			$protocolos = Protocolo::select('id','name','active')->orderBy('name')->paginate(10);
			
			return view('protocolos.index',compact('protocolos'));
		}
		else {
			return view('auth/login');
		}		
    }

    /**
     * Show the form for creating a new resource.
     * Vista: protocolos.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		if (Auth::check()) {
			return view('protocolos.create');		
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
				'name' => 'required|string|max:150|unique:protocolos',
			]);
			
			if ($validator->fails()) {
				return redirect('protocolos/create')
							->withErrors($validator)
							->withInput();
			}
			else {
				$protocolo = new Protocolo;
				
				$protocolo->name = $request->input('name');
				$protocolo->active = $request->input('active');
			
				$protocolo->save();			
				
				return redirect('/protocolos')->with('message','store');
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
     * Vista: protocolos.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
			$protocolo = Protocolo::find($id);
			
			return view('protocolos.edit',compact('protocolo'));
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
				'name' => 'required|string|max:150|unique:protocolos,name,'.$id,
			]);
			
			if ($validator->fails()) {
				return redirect('protocolos/'.$id.'/edit')
							->withErrors($validator)
							->withInput();
			}
			else {
				$protocolo = Protocolo::find($id);
				
				$protocolo->name = $request->input('name');
				$protocolo->active = $request->input('active');
			
				$protocolo->save();			
				
				return redirect('/protocolos')->with('message','update');
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
