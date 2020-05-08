<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use siscont\MotivoSolicitud;

/**
 * Clase Controlador de Motivos de Solicitud
 * Rol: Administrador
 */
class MotivoSolicitudController extends Controller
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
     * Vista: motivoSolicituds.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $motivosolicituds = MotivoSolicitud::select('id','name','active')->orderBy('name')->paginate(10);
            
            return view('motivoSolicituds.index',compact('motivosolicituds'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     * Vista: motivoSolicituds.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (Auth::check()) {
            return view('motivoSolicituds.create');       
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
                'name' => 'required|string|max:150|unique:motivo_solicituds',
            ]);
            
            if ($validator->fails()) {
                return redirect('motivoSolicituds/create')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $motivosolicitud = new MotivoSolicitud;
                
                $motivosolicitud->name = $request->input('name');
                $motivosolicitud->active = $request->input('active');
            
                $motivosolicitud->save();         
                
                return redirect('/motivoSolicituds')->with('message','store');
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
     * Vista: motivoSolicituds.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
            $motivosolicitud = MotivoSolicitud::find($id);
            
            return view('motivoSolicituds.edit',compact('motivosolicitud'));
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
                'name' => 'required|string|max:150|unique:motivo_solicituds,name,'.$id,
            ]);
            
            if ($validator->fails()) {
                return redirect('motivoSolicituds/'.$id.'/edit')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $motivosolicitud = MotivoSolicitud::find($id);
                
                $motivosolicitud->name = $request->input('name');
                $motivosolicitud->active = $request->input('active');
            
                $motivosolicitud->save();         
                
                return redirect('/motivoSolicituds')->with('message','update');
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
