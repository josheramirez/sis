<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use siscont\Via;
use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Vias (Tipo de Calle)
 * Rol: Administrador
 */
class ViasController extends Controller
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
     * Vista: vias.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $vias = Via::select('id','name','active')->orderBy('name')->paginate(10);
            
            return view('vias.index',compact('vias'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     * Vista: vias.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            return view('vias.create');        
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
                'name' => 'required|string|max:150|unique:vias',
            ]);
            
            if ($validator->fails()) {
                return redirect('vias/create')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $vias = new Via;
                
                $vias->name = $request->input('name');
                $vias->active = $request->input('active');
            
                $vias->save();          
                
                return redirect('/vias')->with('message','store');
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
     * Vista: vias.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         if (Auth::check()) {
            $vias = Via::find($id);
            return view('vias.edit',compact('vias'));
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
                'name'   => 'required|string|max:150|unique:vias,name,'.$id,
            ]);
            
            if ($validator->fails()) {
                return redirect('vias/'.$id.'/edit')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $vias = Via::find($id);
                
                $vias->name = $request->input('name');
                $vias->active = $request->input('active');
            
                $vias->save();          
                
                return redirect('/vias')->with('message','update');
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
