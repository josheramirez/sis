<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use siscont\Paciente;
use siscont\Comuna;
use siscont\Via;
use siscont\Genero;
use siscont\Prevision;
use siscont\Tramo;

use DateTime;

use Illuminate\Support\Facades\Auth;

/**
 * Clase Controlador Pacientes
 * Rol: Pacientes
 */
class PacientesController extends Controller
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
        $this->middleware('pacientes');
    }
	
	/**
     * Display a listing of the resource.
	 * Vista: pacientes.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		if (Auth::check()) {
			$pacientes = Paciente::searchNombre($request->get('searchNombre'))
						->searchRut($request->get('searchRut'))
						->searchDoc($request->get('searchDoc'))
			            ->select('id','tipoDoc','rut','dv','numDoc','nombre','apPaterno','apMaterno')
						->orderBy('apPaterno','apMaterno','Nombre')
						->paginate(10)
						->appends('searchNombre',$request->get('searchNombre'))
						->appends('searchRut',$request->get('searchRut'))
						->appends('searchDoc',$request->get('searchDoc'));
			
			return view('pacientes.index',compact('pacientes'));
		}
		else {
			return view('auth/login');
		}
    }

    /**
     * Show the form for creating a new resource.
	 * Vista: pacientes.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {  
        //
		if (Auth::check()) { 
			$comunas    = Comuna::where('active',1)->orderBy('name')->get();
			$vias    	= Via::where('active',1)->orderBy('name')->get();				
			$generos    = Genero::where('active',1)->orderBy('name')->get();
			$previsions = Prevision::where('active',1)->orderBy('name')->get();
			$tramos     = Tramo::where('active',1)->orderBy('name')->get();
			$flujo      = '1'; //creado desde el mantenedor de pacientes
			
			return view('pacientes.create',compact('comunas','vias','generos','previsions','tramos','flujo'));		
		}
		else {
			return view('auth/login');
		}		
    }
	
	/**
     * Show the form for creating a new resource.
	 * Vista: pacientes.create
     *
     * @param int flujo 2-creacion de pacientes de contrarreferencia / 3-creacion de pacientes desde lista de espera
	 * @return \Illuminate\Http\Response
     */
    public function create2($flujo)
    {  
        //
		if (Auth::check()) { 
			$comunas    = Comuna::where('active',1)->orderBy('name')->get();
			$vias    	= Via::where('active',1)->orderBy('name')->get();								
			$generos    = Genero::where('active',1)->orderBy('name')->get();
			$previsions = Prevision::where('active',1)->orderBy('name')->get();
			$tramos     = Tramo::where('active',1)->orderBy('name')->get();
			
			return view('pacientes.create',compact('comunas','vias','generos','previsions','tramos','flujo'));		
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
			//valida si rut existe
			if ($request->input('tipoDoc') == 1) {
				$validator2 = validator::make($request->all(), [
					'rut' => 'required|integer|unique:pacientes',	
				]);
				
				if ($validator2->fails()) {
					if ($request->input('flujo') == '1') {
						return redirect('pacientes/create')
								->with('message','rut')
								->withInput();
					}
					else {
						return redirect('crear/pacientes/'.$request->input('flujo'))
								->with('message','rut')
								->withInput();
					}
				} 			
			}


			//valida tramo
			if ($request->input('prevision') == 1) {
				$validator3 = validator::make($request->all(), [
					'tramo' => 'required',	
				]);
				
				if ($validator3->fails()) {
					if ($request->input('flujo') == '1') {
						return redirect('pacientes/create')
								->with('message','tramo')
								->withInput();
					}
					else {
						return redirect('crear/pacientes/'.$request->input('flujo'))
								->with('message','tramo')
								->withInput();
					}
				} 			
			}


			// valida que campos sean los correctos
			$validator = validator::make($request->all(), [
				'nombre'    => 'required|string|max:150',
				'apPaterno' => 'required|string|max:150',
				'apMaterno' => 'required|string|max:150',
				'fechaNacimiento' => 'required|date|date_format:"d-m-Y"',
				'genero'    => 'required',
				'prevision' => 'required',
				'direccion' => 'required|string|max:150',
				'numero' 	=> 'required|string|max:150',
				'comuna'    => 'required',
				'via'    	=> 'required',
				'telefono'  => 'required|string|max:150',
				'telefono2' => 'nullable|string|max:150',
				'email'     => 'nullable|string|max:150',
			]);
			
			if ($validator->fails()) {
				if ($request->input('flujo') == '1') {
					return redirect('pacientes/create')
							->withErrors($validator)
							->withInput();
				}
				else {
					return redirect('crear/pacientes/'.$request->input('flujo'))
							->withErrors($validator)
							->withInput();
				}
			}
			else {
				//formatea fechas
				$fechaNacimiento = DateTime::createFromFormat('d-m-Y', $request->input('fechaNacimiento'));				
				
				$paciente = new Paciente;
				
				$paciente->tipoDoc        = $request->input('tipoDoc');
				
				if ($request->input('tipoDoc') == 1) {
					$paciente->rut        = $request->input('rut');
					$paciente->dv         = $request->input('dv');
				}
				else {	
					$paciente->numDoc     = $request->input('numDoc');
				}	
				
				$paciente->nombre         = $request->input('nombre');
				$paciente->apPaterno      = $request->input('apPaterno');
				$paciente->apMaterno      = $request->input('apMaterno');
				$paciente->fechaNacimiento  = $fechaNacimiento;
				$paciente->genero_id      = $request->input('genero');
				$paciente->prevision_id   = $request->input('prevision');
				$paciente->tramo_id       = $request->input('tramo');
				$paciente->prais          = $request->input('prais');
				$paciente->funcionario    = $request->input('funcionario');
				$paciente->via_id     	  = $request->input('via');
				$paciente->direccion      = $request->input('direccion');
				$paciente->numero         = $request->input('numero');
				$paciente->X              = $request->input('x');
				$paciente->Y              = $request->input('y');
				$paciente->comuna_id      = $request->input('comuna');

				$paciente->telefono       = $request->input('telefono');
				$paciente->telefono2      = $request->input('telefono2');
				$paciente->email          = $request->input('email');
				$paciente->active         = $request->input('active');
			
				$paciente->save();			
				
				if ($request->input('flujo') == '1') {
					return redirect('/pacientes')->with('message','store');
				}
				elseif ($request->input('flujo') == '2') {
					return redirect('/contrarreferencias/create')->with('message','store');
				}
				elseif ($request->input('flujo') == '3') {
					return redirect('/listaesperas/create')->with('message','store');
				}
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		//
    }
	
    /**
     * Edición de Pacientes para usuarios con rol de digitador.
	 * Vista: pacientes.edit
     *
     * @param  int  $id
	 * @param  int  $flujo
     * @return \Illuminate\Http\Response
     */	
	public function editDi($id,$flujo)
    {
        if (Auth::check()) {
			$paciente = Paciente::find($id);
			$comunas    = Comuna::where('active',1)->orderBy('name')->get();
			$vias    	= Via::where('active',1)->orderBy('name')->get();								             
			$generos    = Genero::where('active',1)->orderBy('name')->get();
			$previsions = Prevision::where('active',1)->orderBy('name')->get();
			$tramos     = Tramo::where('active',1)->orderBy('name')->get();
			
			return view('pacientes.edit',compact('paciente','comunas','vias','generos','previsions','tramos','flujo'));
		}
		else {
			return view('auth/login');
		}
    }
	
	/**
     * Edición de Pacientes para usuarios con rol de superusuario.
	 * Vista: pacientes.editFull
     *
     * @param  int  $id
	 * @param  int  $flujo
     * @return \Illuminate\Http\Response
     */
    public function editSU($id,$flujo)
    {
        if (Auth::check()) {
			$paciente = Paciente::find($id);
			$comunas    = Comuna::where('active',1)->orderBy('name')->get();
			$vias    	= Via::where('active',1)->orderBy('name')->get();								             
			$generos    = Genero::where('active',1)->orderBy('name')->get();
			$previsions = Prevision::where('active',1)->orderBy('name')->get();
			$tramos     = Tramo::where('active',1)->orderBy('name')->get();
			
			return view('pacientes.editFull',compact('paciente','comunas','vias','generos','previsions','tramos','flujo'));
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
			//valida si rut existe
			$validator2 = validator::make($request->all(), [
				'rut' => 'nullable|integer|unique:pacientes,rut,'.$id,	
			]);
			if ($validator2->fails()) {
				return redirect('editar/pacientes/'.$request->input('url').'/'.$id.'/'.$request->input('flujo'))
							->with('message','rut')
							->withInput();
			} 		

			//valida tramo
			if ($request->input('prevision') == 1) {
				$validator3 = validator::make($request->all(), [
					'tramo' => 'required',	
				]);
				
				if ($validator3->fails()) {
					return redirect('editar/pacientes/'.$request->input('url').'/'.$id.'/'.$request->input('flujo'))
							->with('message','tramo')
							->withInput();
				} 			
			}	
			
			// validate
			$validator = validator::make($request->all(), [
				'nombre'    => 'required|string|max:150',
				'apPaterno' => 'required|string|max:150',
				'apMaterno' => 'required|string|max:150',
				'fechaNacimiento' => 'required|date|date_format:"d-m-Y"',
				'genero'    => 'required',
				'prevision' => 'required',
				'direccion' => 'required|string|max:150',
				'numero'    => 'required|string|max:150',
				'comuna'    => 'required',
				'via'    	=> 'required',
				'telefono'  => 'required|string|max:150',
				'telefono2' => 'nullable|string|max:150',
				'email'     => 'nullable|string|max:150',
			]);
			
			if ($validator->fails()) {
				return redirect('editar/pacientes/'.$request->input('url').'/'.$id.'/'.$request->input('flujo'))
							->withErrors($validator)
							->withInput();
			}
			else {
				//formatea fechas
				$fechaNacimiento = DateTime::createFromFormat('d-m-Y', $request->input('fechaNacimiento'));	
				
				$paciente = Paciente::find($id);
				
				$paciente->tipoDoc    = $request->input('tipoDoc');
				if ($request->input('tipoDoc') == 1) {
					$paciente->rut        = $request->input('rut');
					$paciente->dv         = $request->input('dv');
					
					$paciente->numDoc     = null;
				}
				else {	
					$paciente->numDoc     = $request->input('numDoc');
					
					$paciente->rut        = null;
					$paciente->dv         = null;
				}
				
				$paciente->nombre     = $request->input('nombre');
				$paciente->apPaterno  = $request->input('apPaterno');
				$paciente->apMaterno  = $request->input('apMaterno');
				$paciente->fechaNacimiento  = $fechaNacimiento;
				$paciente->genero_id     = $request->input('genero');
				$paciente->prevision_id  = $request->input('prevision');
				$paciente->tramo_id      = $request->input('tramo');
				$paciente->prais         = $request->input('prais');
				$paciente->funcionario   = $request->input('funcionario');
				$paciente->direccion     = $request->input('direccion');
				$paciente->numero        = $request->input('numero');
				$paciente->X             = $request->input('x');
				$paciente->Y             = $request->input('y');
				$paciente->comuna_id     = $request->input('comuna');
				$paciente->via_id     	 = $request->input('via');
				$paciente->telefono      = $request->input('telefono');
				$paciente->telefono2     = $request->input('telefono2');
				$paciente->email         = $request->input('email');
				$paciente->active  = $request->input('active');
				
				$paciente->save();			
				
				if($request->input('flujo') == 1){ //editado desde el administrador de pacientes
					return redirect('/pacientes')->with('message','update');
				}	
				elseif($request->input('flujo') == 2){ //editado desde el administrador de pantalla de creacion de lista de espera
					return redirect('/listaesperas/create')->with('message','update');
				}
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
