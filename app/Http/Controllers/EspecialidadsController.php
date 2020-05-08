<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use siscont\Especialidad;

use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Especialidad
 * Rol: Administrador
 */
class EspecialidadsController extends Controller
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
     * Vista: especialidads.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		if (Auth::check()) {
			$especialidads = Especialidad::searchName($request->get('searchName'))
							->searchRem($request->get('searchRem'))
							->select('id','name','rem','deis','sigte','active')
							->orderBy('rem')
							->paginate(10)
							->appends('searchName',$request->get('searchName'))
							->appends('searchRem',$request->get('searchRem'));
			
			return view('especialidads.index',compact('especialidads'));
		}
		else {
			return view('auth/login');
		}
    }

    /**
     * Show the form for creating a new resource.
     * Vista: especialidads.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		if (Auth::check()) {
			return view('especialidads.create');		
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
				'rem'  => 'required|string|max:150',
				'deis'  => 'required|string|max:150',
				'sigte'  => 'required|string|max:150|unique:especialidads',
				'name' => 'required|string|max:150|unique:especialidads',
			]);
			
			if ($validator->fails()) {
				return redirect('especialidads/create')
							->withErrors($validator)
							->withInput();
			}
			else {
				$especialidad = new Especialidad;
				
				$especialidad->name = $request->input('name');
				$especialidad->rem  = $request->input('rem');
				$especialidad->deis  = $request->input('deis');
				$especialidad->sigte  = $request->input('sigte');
				$especialidad->active = $request->input('active');
			
				$especialidad->save();			
				
				return redirect('/especialidads')->with('message','store');
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
     * Vista: especialidads.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
			$especialidad = Especialidad::find($id);
			
			return view('especialidads.edit',compact('especialidad'));
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
				'rem'  => 'required|string|max:150',
				'deis'  => 'required|string|max:150',
				'sigte'  => 'required|string|max:150|unique:especialidads,sigte,'.$id,
				'name' => 'required|string|max:150|unique:especialidads,name,'.$id,
			]);
			
			if ($validator->fails()) {
				return redirect('especialidads/'.$id.'/edit')
							->withErrors($validator)
							->withInput();
			}
			else {
				$especialidad = Especialidad::find($id);
				
				$especialidad->rem = $request->input('rem');
				$especialidad->deis = $request->input('deis');
				$especialidad->sigte = $request->input('sigte');
				$especialidad->name = $request->input('name');
				$especialidad->active = $request->input('active');
			
				$especialidad->save();			
				
				return redirect('/especialidads')->with('message','update');
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
