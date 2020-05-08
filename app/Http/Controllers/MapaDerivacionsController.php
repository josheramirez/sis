<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use siscont\MapaDerivacion;
use siscont\Establecimiento;
use siscont\Etario;
use siscont\Especialidad;

use DB;
use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Mapa de Derivación
 * Rol: Administrador
 */
class MapaDerivacionsController extends Controller
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
	 * Vista: mapaDerivacions.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		if (Auth::check()) {
			$mapas = DB::table('mapa_derivacions')
					->join('especialidads', 'especialidads.id','=','mapa_derivacions.especialidad_id')
					->join('etarios', 'etarios.id','=','mapa_derivacions.etario_id')
					->join('establecimientos as A', 'A.id','=','mapa_derivacions.contraref_id')
					->join('establecimientos as B', 'B.id','=','mapa_derivacions.origen_id')
					->select('mapa_derivacions.id as id',
					         'especialidads.name as especialidad',
					         'etarios.name as etario',
							 'A.name as contraref',
							 'B.name as origen',
							 'mapa_derivacions.active as active')
					->where([['mapa_derivacions.contraref_id','LIKE',$request->get('searchContraref')],
					         ['mapa_derivacions.origen_id','LIKE',$request->get('searchOrigen')]])
					->orderBy('id')
					->paginate(10)
					->appends('searchContraref',$request->get('searchContraref'))
					->appends('searchOrigen',$request->get('searchOrigen'));

			$establecimientos  = Establecimiento::where('active',1)->orderBy('name')->get();

			return view('mapaDerivacions.index',compact('mapas','establecimientos'));
		}
		else {
			return view('auth/login');
		}
    }

    /**
     * Show the form for creating a new resource.
	 * Vista: mapaDerivacions.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		if (Auth::check()) {
			$especialidads     = Especialidad::where('active',1)->orderBy('name')->get();
			$establecimientos  = Establecimiento::where('active',1)->orderBy('name')->get();
			$etarios           = Etario::where('active',1)->orderBy('name')->get();

			return view('mapaDerivacions.create',compact('especialidads','establecimientos','etarios'));
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
        //
		if (Auth::check()) {
			$cont = MapaDerivacion::where([['especialidad_id',$request->input('especialidad')],
												   ['etario_id',$request->input('etario')],
												   ['contraref_id',$request->input('contraref')],
												   ['origen_id',$request->input('origen')]
												  ])->count();

			if ($cont > 0) {
				return redirect('/mapaDerivacions/create')->with('message','error')->withInput();
			}

			$mapa = new MapaDerivacion;

			$mapa->especialidad_id = $request->input('especialidad');
			$mapa->etario_id       = $request->input('etario');
			$mapa->contraref_id    = $request->input('contraref');
			$mapa->origen_id       = $request->input('origen');
			$mapa->active          = $request->input('active');

			$mapa->save();

			return redirect('/mapaDerivacions')->with('message','store');

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
	 * Vista: mapaDerivacions.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		if (Auth::check()) {
			$mapa = MapaDerivacion::find($id);

			$especialidads     = Especialidad::where('active',1)->orderBy('name')->get();
			$establecimientos  = Establecimiento::where('active',1)
								 ->orWhere('id',$mapa->contraref_id)
			                     ->orWhere('id',$mapa->origen_id)
			                     ->orderBy('name')
								 ->get();
								 
			$etarios           = Etario::where('active',1)->orderBy('name')->get();

			return view('mapaDerivacions.edit',compact('mapa','especialidads','establecimientos','etarios'));
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
					$cont = MapaDerivacion::where([['especialidad_id',$request->input('especialidad')],
												   ['etario_id',$request->input('etario')],
												   ['contraref_id',$request->input('contraref')],
												   ['origen_id',$request->input('origen')],
												   ['id','<>',$id]
												  ])->count();

			if ($cont > 0) {
				return redirect('/mapaDerivacions/'.$id.'/edit')->with('message','error')->withInput();
			}

			$mapa = MapaDerivacion::find($id);

			$mapa->especialidad_id = $request->input('especialidad');
			$mapa->etario_id       = $request->input('etario');
			$mapa->contraref_id    = $request->input('contraref');
			$mapa->origen_id       = $request->input('origen');
			$mapa->active          = $request->input('active');

			$mapa->save();

			return redirect('/mapaDerivacions')->with('message','update');
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
