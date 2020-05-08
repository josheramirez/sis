<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use siscont\Cie10;

use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Cie10s
 * Rol: Administrador
 */
class Cie10sController extends Controller
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
     * Vista: cie10s.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		if (Auth::check()) {
			$cie10s = Cie10::searchName($request->get('searchName'))
					->searchCodigo($request->get('searchCodigo'))
					->select('id','name','codigo','active')
					->orderBy('codigo')
					->paginate(10)
					->appends('searchName',$request->get('searchName'))
					->appends('searchCodigo',$request->get('searchCodigo'));
			
			return view('cie10s.index',compact('cie10s'));
		}
		else {
			return view('auth/login');
		}		
    }

    /**
     * Show the form for creating a new resource.
     * Vista: cie10s.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		if (Auth::check()) {
			return view('cie10s.create');		
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
				'codigo'  => 'required|string|max:150|unique:cie10s',
				'name' => 'required|string|max:150|unique:cie10s',
			]);
			
			if ($validator->fails()) {
				return redirect('cie10s/create')
							->withErrors($validator)
							->withInput();
			}
			else {
				$cie10 = new Cie10;
				
				$cie10->name = $request->input('name');
				$cie10->codigo  = $request->input('codigo');
				$cie10->active = $request->input('active');
			
				$cie10->save();			
				
				return redirect('/cie10s')->with('message','store');
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
     * Vista: cie10s.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
			$cie10 = Cie10::find($id);
			
			return view('cie10s.edit',compact('cie10'));
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
				'codigo'  => 'required|string|max:150|unique:cie10s,codigo,'.$id,
				'name' => 'required|string|max:150|unique:cie10s,name,'.$id,
			]);
			
			if ($validator->fails()) {
				return redirect('cie10s/'.$id.'/edit')
							->withErrors($validator)
							->withInput();
			}
			else {
				$cie10 = Cie10::find($id);
				
				$cie10->codigo = $request->input('codigo');
				$cie10->name = $request->input('name');
				$cie10->active = $request->input('active');
			
				$cie10->save();			
				
				return redirect('/cie10s')->with('message','update');
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
