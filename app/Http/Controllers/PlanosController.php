<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use siscont\Plano;
use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Planos
 * Rol: Administrador
 */
class PlanosController extends Controller
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
     * Vista: planos.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $planos = Plano::select('id','name','active')->orderBy('name')->paginate(10);
            
            return view('planos.index',compact('planos'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     * Vista: planos.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            return view('planos.create');       
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
                'name' => 'required|string|max:150|unique:planos',
            ]);
            
            if ($validator->fails()) {
                return redirect('planos/create')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $planos = new Plano;
                
                $planos->name = $request->input('name');
                $planos->active = $request->input('active');
            
                $planos->save();         
                
                return redirect('/planos')->with('message','store');
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
     * Vista: planos.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
            $planos = Plano::find($id);
            
            return view('planos.edit',compact('planos'));
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
                'name' => 'required|string|max:150|unique:planos,name,'.$id,
            ]);
            
            if ($validator->fails()) {
                return redirect('planos/'.$id.'/edit')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $planos = Plano::find($id);
                
                $planos->name = $request->input('name');
                $planos->active = $request->input('active');
            
                $planos->save();          
                
                return redirect('/planos')->with('message','update');
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
