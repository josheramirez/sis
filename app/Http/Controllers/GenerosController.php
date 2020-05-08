<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use siscont\Genero;

use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Género
 * Rol: Administrador
 */
class GenerosController extends Controller
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
     * Vista: generos.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         //
        if (Auth::check()) {
            $generos = Genero::select('id','name','active')->orderBy('name')->paginate(10);
            
            return view('generos.index',compact('generos'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     * Vista: generos.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            return view('generos.create');      
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
                'name' => 'required|string|max:150|unique:generos',
            ]);
            
            if ($validator->fails()) {
                return redirect('generos/create')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $genero = new Genero;
                
                $genero->name = $request->input('name');
                $genero->active = $request->input('active');
            
                $genero->save();            
                
                return redirect('/generos')->with('message','store');
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
     * Vista: generos.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
            $genero = Genero::find($id);
            
            return view('generos.edit',compact('genero'));
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
                'name' => 'required|string|max:150|unique:generos,name,'.$id,
            ]);
            
            if ($validator->fails()) {
                return redirect('generos/'.$id.'/edit')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $genero = Genero::find($id);
                
                $genero->name = $request->input('name');
                $genero->active = $request->input('active');
            
                $genero->save();            
                
                return redirect('/generos')->with('message','update');
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
