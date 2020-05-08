<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use siscont\TipoProcedimiento;
use siscont\TipoPrestacion;
use Illuminate\Support\Facades\Auth;
use DB;

/**
 * Clase Controlador Tipo Procedimiento
 * Rol: Administrador
 */
class TipoProcedimientosController extends Controller
{
    /** * Instantiate a new controller instance.
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
     * Vista: tipoProcedimientos.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            $tipos = DB::table('tipo_procedimientos')
                    ->join('tipo_prestacions', 'tipo_prestacions.id','=','tipo_procedimientos.tipo_prestacion_id')
                    ->select('tipo_procedimientos.id as id',
                             'tipo_procedimientos.name as name',
                             'tipo_prestacions.name as tipo_prestacion',
                             'tipo_procedimientos.active')
                    ->where('tipo_procedimientos.name','LIKE','%'.$request->get('searchNombre').'%')
					->orderBy('name')
					->paginate(10)
					->appends('searchNombre',$request->get('searchNombre'));

            return view('tipoProcedimientos.index',compact('tipos'));
        }
        else {
            return view('auth/login');
        }           
    }

    /**
     * Show the form for creating a new resource.
     * Vista: tipoProcedimientos.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            $tipoprestacions = TipoPrestacion::where('active',1)->orderBy('name')->get();
            return view('tipoProcedimientos.create',compact('tipoprestacions'));
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
                'name' => 'required|string|max:150|unique:tipo_procedimientos',
                'tipoprestacion' => 'required',
            ]);
            
            if ($validator->fails()) {
                return redirect('tipoProcedimientos/create')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $tipoProcedimientos = new TipoProcedimiento;
                
                $tipoProcedimientos->name = $request->input('name');
                $tipoProcedimientos->active = $request->input('active');
                $tipoProcedimientos->tipo_prestacion_id = $request->input('tipoprestacion');

                $tipoProcedimientos->save();           
                
                return redirect('/tipoProcedimientos')->with('message','store');
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
     * Vista: tipoProcedimientos.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
            $tipo = TipoProcedimiento::find($id);

            $tipoprestacions = TipoPrestacion::where('active',1)->orderBy('name')->get();
            
            return view('tipoProcedimientos.edit',compact('tipo','tipoprestacions'));
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
                'name' => 'required|string|max:150|unique:tipo_procedimientos,name,'.$id,
                'tipoprestacion' => 'required',
            ]);
            
            if ($validator->fails()) {
                return redirect('tipoProcedimientos/'.$id.'/edit')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $tipo = TipoProcedimiento::find($id);
                
                $tipo->name = $request->input('name');
                $tipo->active = $request->input('active');
                $tipo->tipo_prestacion_id = $request->input('tipoprestacion');
            
                $tipo->save();          
                
                return redirect('/tipoProcedimientos')->with('message','update');
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
