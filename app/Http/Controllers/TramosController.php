<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use siscont\Tramo;
use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Tramos Fonasa
 * Rol: Administrador
 */
class TramosController extends Controller
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
     * Vista: tramos.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $tramos = Tramo::select('id','name','active')->orderBy('name')->paginate(10);
            
            return view('tramos.index',compact('tramos'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     * Vista: tramos.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            return view('tramos.create');        
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
                'name' => 'required|string|max:150|unique:tramos',
            ]);
            
            if ($validator->fails()) {
                return redirect('tramos/create')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $tramos = new Tramo;
                
                $tramos->name = $request->input('name');
                $tramos->active = $request->input('active');
            
                $tramos->save();          
                
                return redirect('/tramos')->with('message','store');
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
     * Vista: tramos.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
            $tramos = Tramo::find($id);
            
            return view('tramos.edit',compact('tramos'));
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
                'name'   => 'required|string|max:150|unique:tramos,name,'.$id,
            ]);
            
            if ($validator->fails()) {
                return redirect('tramos/'.$id.'/edit')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $tramos = Tramo::find($id);
                
                $tramos->name = $request->input('name');
                $tramos->active = $request->input('active');
            
                $tramos->save();          
                
                return redirect('/tramos')->with('message','update');
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
