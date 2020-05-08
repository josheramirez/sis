<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use siscont\Nivel;

use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Nivel de Establecimiento
 * Rol: Administrador
 */
class NivelsController extends Controller
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
     * Vista: nivels.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		if (Auth::check()) {
			$nivels = Nivel::select('id','name','active')->orderBy('name')->paginate(10);
			
			return view('nivels.index',compact('nivels'));
		}
		else {
			return view('auth/login');
		}		
    }

    /**
     * Show the form for creating a new resource.
     * Vista: nivels.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		if (Auth::check()) {
			return view('nivels.create');		
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
				'name' => 'required|string|max:150|unique:nivels',
			]);
			
			if ($validator->fails()) {
				return redirect('nivels/create')
							->withErrors($validator)
							->withInput();
			}
			else {
				$nivel = new Nivel;
				
				$nivel->name = $request->input('name');
				$nivel->active = $request->input('active');
			
				$nivel->save();			
				
				return redirect('/nivels')->with('message','store');
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
     * Vista: nivels.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
			$nivel = Nivel::find($id);
			
			return view('nivels.edit',compact('nivel'));
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
				'name' => 'required|string|max:150|unique:nivels,name,'.$id,
			]);
			
			if ($validator->fails()) {
				return redirect('nivels/'.$id.'/edit')
							->withErrors($validator)
							->withInput();
			}
			else {
				$nivel = Nivel::find($id);
				
				$nivel->name = $request->input('name');
				$nivel->active = $request->input('active');
			
				$nivel->save();			
				
				return redirect('/nivels')->with('message','update');
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
