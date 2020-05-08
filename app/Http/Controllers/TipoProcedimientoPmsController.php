<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use siscont\TipoProcedimientosPm;
use siscont\TipoProcedimiento;
use siscont\Plano;
use siscont\Extremidad;
use Illuminate\Support\Facades\Auth;
use DB;

/**
 * Clase Controlador Tipo Procedimiento PMS
 * Rol: Administrador
 */
class TipoProcedimientoPmsController extends Controller
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
     * Vista: tipoProcedimientoPms.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
			$tipos = TipoProcedimientosPm::with('tipoprocedimiento.tipoprestacion')
                    ->where('tipo_procedimientos_pms.name','LIKE','%'.$request->get('searchNombre').'%')
                    ->where('tipo_procedimientos_pms.prestamin','LIKE','%'.$request->get('searchPrestamin').'%')
					->orderBy('tipo_procedimientos_pms.name')
					->paginate(10)
					->appends('searchNombre',$request->get('searchNombre'))
                    ->appends('searchPrestamin',$request->get('searchPrestamin'));

            return view('tipoProcedimientoPms.index',compact('tipos'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     * Vista: tipoProcedimientoPms.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            $tipoprocedimientos = TipoProcedimiento::where('active',1)->orderBy('name')->get();
            return view('tipoProcedimientoPms.create',compact('tipoprocedimientos'));
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
                'name' => 'required|string|max:150|unique:tipo_procedimientos_pms',
                'tipoprocedimiento' => 'required',
            ]);


            if ($validator->fails()) {
                return redirect('tipoProcedimientosPm/create')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {

				$tipoProcedimientosPm = new TipoProcedimientosPm;

                $tipoProcedimientosPm->name = $request->input('name');
                $tipoProcedimientosPm->active = $request->input('active');
                $tipoProcedimientosPm->tipo_procedimiento_id = $request->input('tipoprocedimiento');
				$tipoProcedimientosPm->requiere_plano = $request->input('requiere_plano');
				$tipoProcedimientosPm->requiere_extremidad = $request->input('requiere_extremidad');
                $tipoProcedimientosPm->prestamin = $request->input('prestamin');

				$tipoProcedimientosPm->save();

                return redirect('/tipoProcedimientosPm')->with('message','store');
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
     * Vista: tipoProcedimientoPms.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
            $tipo = TipoProcedimientosPm::find($id);

            $tipoprocedimiento = TipoProcedimiento::where('active',1)->orderBy('name')->get();

            return view('tipoProcedimientoPms.edit',compact('tipo','tipoprocedimiento'));
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
                'tipoprocedimiento' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect('tipoProcedimientosPm/'.$id.'/edit')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $tipo = TipoProcedimientosPm::find($id);

                $tipo->name = $request->input('name');
                $tipo->active = $request->input('active');
                $tipo->tipo_procedimiento_id = $request->input('tipoprocedimiento');
				$tipo->requiere_plano = $request->input('requiere_plano');
				$tipo->requiere_extremidad = $request->input('requiere_extremidad');
                $tipo->prestamin = $request->input('prestamin');

                $tipo->save();

                return redirect('/tipoProcedimientosPm')->with('message','update');
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

    /**
     * Funcion que Actualiza PLANO
     * Vista: tipoProcedimientoPms.asignPlano
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function asignPlano($id) {
        if (Auth::check()) {
            $planos             = Plano::where('active',1)->orderBy('name')->get();
            $procedimientos_pms = TipoProcedimientosPm::find($id);

            return view('tipoProcedimientoPms.asignPlano',compact('procedimientos_pms','planos','id'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Funcion que Guarda Plano
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function savePlano(Request $request) {
        if (Auth::check()) {
            $id = $request->input('procedimientosPmsID');
            $procedimientos_pms = TipoProcedimientosPm::find($id);

            //Graba nuevos roles asignados
            $planos = $request->input('procPlanos');

            $procedimientos_pms->planos()->sync($planos);

            return redirect('/tipoProcedimientosPm')->with('message','plano');
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Funcion que Actualiza Extremidad
     * Vista: tipoProcedimientoPms.asignExtremidad
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function asignExtremidad($id) {
        if (Auth::check()) {
            $extremidads        = Extremidad::where('active',1)->orderBy('name')->get();
            $procedimientos_pms = TipoProcedimientosPm::find($id);

            return view('tipoProcedimientoPms.asignExtremidad',compact('procedimientos_pms','extremidads','id'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Funcion que Guarda Extremidad
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveExtremidad(Request $request) {
        if (Auth::check()) {
            $id = $request->input('procedimientosPmsID');
            $procedimientos_pms = TipoProcedimientosPm::find($id);

            //Graba nuevos roles asignados
            $extremidads = $request->input('procExtremidads');

            $procedimientos_pms->extremidads()->sync($extremidads);

            return redirect('/tipoProcedimientosPm')->with('message','extremidad');
        }
        else {
            return view('auth/login');
        }
    }
}