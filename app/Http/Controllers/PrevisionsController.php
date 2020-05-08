<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use siscont\Prevision;

use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Prevision
 * Rol: Administrador
 */
class PrevisionsController extends Controller
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
     * Vista: previsions.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $previsions = Prevision::select('id','name','active')->orderBy('name')->paginate(10);
            
            return view('previsions.index',compact('previsions'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     * Vista: previsions.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            return view('previsions.create');      
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
                'name' => 'required|string|max:150|unique:previsions',
            ]);
            
            if ($validator->fails()) {
                return redirect('previsions/create')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $prevision = new Prevision;
                
                $prevision->name = $request->input('name');
                $prevision->active = $request->input('active');
            
                $prevision->save();            
                
                return redirect('/previsions')->with('message','store');
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
     * Vista: previsions.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
            $prevision = Prevision::find($id);
            
            return view('previsions.edit',compact('prevision'));
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
                'name' => 'required|string|max:150|unique:previsions,name,'.$id,
            ]);
            
            if ($validator->fails()) {
                return redirect('previsions/'.$id.'/edit')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $prevision = Prevision::find($id);
                
                $prevision->name = $request->input('name');
                $prevision->active = $request->input('active');
            
                $prevision->save();            
                
                return redirect('/previsions')->with('message','update');
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
