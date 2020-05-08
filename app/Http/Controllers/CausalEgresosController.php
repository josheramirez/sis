<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use siscont\CausalEgreso;
use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Causal de Egresos
 * Rol: Administrador
 */
class CausalEgresosController extends Controller
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
     * Vista: causalEgresos.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $causalegresos = CausalEgreso::select('id','name','active')->orderBy('name')->paginate(10);

            return view('causalEgresos.index',compact('causalegresos'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     * Vista: causalEgresos.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            return view('causalEgresos.create');
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
                'name' => 'required|string|max:150|unique:causal_egresos',
            ]);

            if ($validator->fails()) {
                return redirect('causalEgresos/create')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $causalegresos = new CausalEgreso;

                $causalegresos->name = $request->input('name');
                $causalegresos->active = $request->input('active');

                $causalegresos->save();

                return redirect('/causalEgresos')->with('message','store');
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
     * Vista: causalEgresos.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
            $causalegresos = CausalEgreso::find($id);

            return view('causalEgresos.edit',compact('causalegresos'));
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
                'name' => 'required|string|max:150|unique:causal_egresos,name,'.$id,
            ]);

            if ($validator->fails()) {
                return redirect('causalEgresos/'.$id.'/edit')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $causalegresos = CausalEgreso::find($id);

                $causalegresos->name = $request->input('name');
                $causalegresos->active = $request->input('active');

                $causalegresos->save();

                return redirect('/causalEgresos')->with('message','update');
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
