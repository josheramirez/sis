<?php
namespace siscont\Http\Controllers;

use DateTime;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDF;
use siscont\CausalEgreso;
use siscont\Cie10;
use siscont\Comuna;
use siscont\Especialidad;
use siscont\Establecimiento;
use siscont\Extremidad;
use siscont\ListaEspera;
use siscont\LogLep;
use siscont\Paciente;
use siscont\Plano;
use siscont\Prevision;
use siscont\TipoEspera;
use siscont\TipoPrestacion;
use siscont\TipoProcedimiento;
use siscont\TipoProcedimientosPm;
use siscont\TipoSalida;
use siscont\Tramo;
use siscont\User;
use siscont\Via;
use Session;

/**
 * Clase Controlador Listas de Esperas
 * Rol: Por Funcion
 */
class ListaEsperasController extends Controller
{
    /*******************************************************************************************/
    /*                              CREAR LISTA DE ESPERA                                      */
    /*******************************************************************************************/
    /**
     * Muestra el formulario para la creación de una nueva Lista de Espera.
     * Vista: listaEsperas.create
     * Rol: digitadorLE
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (Auth::check()) {
            //determina establecimiento de usuario conectado
            $especialidads        = Especialidad::where('active', 1)->orderBy('name')->get();
            $user                 = Auth::user()->name;
            $tipoEsperas          = TipoEspera::where('active', 1)->orderBy('name')->get();
            $id_origens           = Establecimiento::where('active', 1)->orderBy('name')->get();
            $id_destinos          = Establecimiento::where('active', 1)->orderBy('name')->get();
            $tipoPrestacions      = TipoPrestacion::where('active', 1)->orderBy('name')->get();
            $tipoProcedimientos   = TipoProcedimiento::where('active', 1)->orderBy('name')->get();
            $tipoProcedimientoPms = TipoProcedimientosPm::where('active', 1)->orderBy('name')->get();
            $establecimiento      = session('establecimiento');
            if (is_null($establecimiento)) {
                $user            = User::find(Auth::user()->id);
                $establecimiento = $user->establecimientos()->first()->id;
            }

            return view('listaEsperas.create', compact('id_origens', 'id_destinos', 'tipoEsperas', 'especialidads', 'user', 'tipoPrestacions', 'tipoProcedimientos', 'tipoProcedimientoPms','establecimiento'));
        } 
        else {
            return view('auth/login');
        }
    }

    /**
     * Almacena nueva lista de espera.
     * Rol: digitadorLE
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            /*********************************************************************************************************/    
            /*                                       VALIDACION CAMPOS OBLIGATORIOS                                  */
            /*********************************************************************************************************/    
            //valida que paciente exista
            $validator = validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect('listaesperas/create')
                    ->with('message', 'paciente')
                    ->withInput();
            }

            //valida que diagnostico cie10 existe
            $validator2 = validator::make($request->all(), [
                'idCie10' => 'required',
            ]);
            if ($validator2->fails()) {
                return redirect('listaesperas/create')
                    ->with('message', 'cie10')
                    ->withInput();
            }

            // Validador Fecha entrada no debe ser menor a 1 mes a contar de hoy
            $inicio = new DateTime('now');
            $hoy    = new DateTime('now');
            $inicio->modify('-1 Month');

            $validator3 = Validator::make($request->all(), [
                'fechaingreso' => 'required|date|date_format:"d-m-Y"|after:' . $inicio->format('d-m-Y') . '|before_or_equal:' . $hoy->format('d-m-Y'),
            ]);
            if ($validator3->fails()) {
                return redirect('listaesperas/create')
                    ->with('message', 'fecha_entrada')
                    ->withInput();
            }

            // Validador Fecha entrada no debe ser menor a fecha de nacimiento
            $pacientes_id = $request->input('id');
            $hoy          = new DateTime('now');

            $pacientes = DB::table('pacientes')
                ->where('id', '=', $pacientes_id)
                ->selectRaw('DATE_FORMAT(pacientes.fechaNacimiento, "%d-%m-%Y") as fechaNacimiento')
                ->first();
            $fecha_nac = $pacientes->fechaNacimiento;

            $validator4 = Validator::make($request->all(), [
                'fechaingreso' => 'required|date|date_format:"d-m-Y"|after:' . $fecha_nac,
            ]);
            if ($validator4->fails()) {
                return redirect('listaesperas/create')
                    ->with('message', 'fecha_nacimiento')
                    ->withInput();
            }

            //valida formato de fecha de citacion
            if ($request->input('fechacitacion') != '') {
                $validator5 = Validator::make($request->all(), [
                    'fechacitacion' => 'date|date_format:"d-m-Y"',
                ]);
                if ($validator5->fails()) {
                    return redirect('listaesperas/create')
                        ->with('message', 'fecha_citacion')
                        ->withInput();
                }
            }

            //valida campos obligatorios
            $validator6 = validator::make($request->all(), [
                'tipo_ges_id'              => 'required',
                'idorigen'                 => 'required',
                'iddestino'                => 'required',
                'especialidads_ingreso_id' => 'required',
                'precdiag'                 => 'required|max:1000',
                'tipo_prestacions_id'      => 'required',
            ]);
            if ($validator6->fails()) {
                return redirect('listaesperas/create')
                    ->withErrors($validator6)
                    ->withInput();
            }

            /*********************************************************************************************************/    
            /*                                       VALIDACION PLANO Y EXTREMIDAD                                   */
            /*********************************************************************************************************/    
            // Obtiene el tipo de prestación / 1 Consulta nueva / 3 Procedimiento / 4 Intervención Quirúrgica / 5 Intervención Quirúrgica Compleja
            $tipo_prestacions_id = $request->input('tipo_prestacions_id');
            //obtiene tipo de procedimiento PMS
            $tipo_procedimiento_pm_id = $request->input('tipo_procedimiento_pm_id');
            $tipo_procedimiento_pm = TipoProcedimientospm::find($tipo_procedimiento_pm_id);

            if($tipo_prestacions_id !=  1) { //si no es consulta nueva
                //si requiere plano
                if($tipo_procedimiento_pm ->requiere_plano == 1) {
                    $validator7 = validator::make($request->all(), [
                        'planos_id' => 'required',
                    ]);
                    if ($validator7->fails()) {
                        return redirect('listaesperas/create')
                            ->withErrors($validator7)
                            ->withInput();
                    }
                }
                //si requiere extremidad
                if($tipo_procedimiento_pm ->requiere_extremidad == 1) {
                    $validator8 = validator::make($request->all(), [
                        'extremidads_id' => 'required',
                    ]);
                    if ($validator8->fails()) {
                        return redirect('listaesperas/create')
                            ->withErrors($validator8)
                            ->withInput();
                    }     
                }
            }

            /*********************************************************************************************************/    
            /*                                  INGRESO DATOS DE LISTA DE ESPERA                                     */
            /*********************************************************************************************************/
            
            $fechaingreso  = DateTime::createFromFormat('d-m-Y', $request->input('fechaingreso'));
            $fechacitacion = DateTime::createFromFormat('d-m-Y', $request->input('fechacitacion'));

            $paciente = Paciente::find($request->input('id'));
            $tramo_id = $paciente->tramo_id;

            $listaespera = new ListaEspera;

            $listaespera->fechaingreso = $fechaingreso;
            $listaespera->precdiag     = $request->input('precdiag');
            if ($fechacitacion != '') {
                $listaespera->fechacitacion = $fechacitacion;
            }
            $listaespera->run_medico_solicita        = $request->input('run_medico_solicita');
            $listaespera->dv_medico_solicita         = $request->input('dv_medico_solicita');
            $listaespera->tipo_ges_id                = $request->input('tipo_ges_id');
            $listaespera->establecimientos_id_origen = $request->input('idorigen');

            $listaespera->establecimientos_id_destino = $request->input('iddestino');
            $listaespera->pacientes_id                = $request->input('id');
            $listaespera->cie10s_id                   = $request->input('idCie10');
            $listaespera->especialidads_ingreso_id    = $request->input('especialidads_ingreso_id');
            $esp_ing_sigte = Especialidad::find($listaespera->especialidads_ingreso_id);
            //FIXME: La cx menor solo puede ser ingresada cuando el tipo prestación es igual a 4
            if($esp_ing_sigte->sigte == '07-000' && $tipo_prestacions_id != 4)
            {
                return redirect('listaesperas/create')
                ->with('message', 'cxmenor_nv')
                ->withInput();
            }

            $listaespera->tipo_consultas_id           = 0; //valor por defecto

            $listaespera->tipo_prestacions_id = $tipo_prestacions_id;
            
            if ($tipo_prestacions_id == 1) { //Consulta nueva
                $listaespera->tipo_procedimientos_id     = null;
                $listaespera->tipo_procedimientos_pms_id = null;
                $listaespera->planos_id                  = null;
                $listaespera->extremidads_id             = null;
                
                //Agrega código Prestamin
                $prestamin = Especialidad::where('id', '=', $listaespera->especialidads_ingreso_id)->first();
                $listaespera->prestamin_ing = $prestamin->sigte;
            }
			else {
                $listaespera->tipo_procedimientos_id     = $request->input('tipo_procedimiento_id');
                $listaespera->tipo_procedimientos_pms_id = $request->input('tipo_procedimiento_pm_id');
                
                if($tipo_procedimiento_pm ->requiere_plano == 1) {
                    $listaespera->planos_id = $request->input('planos_id');
                }
                
                if($tipo_procedimiento_pm ->requiere_extremidad == 1) {
                    $listaespera->extremidads_id = $request->input('extremidads_id');
                }    
                
                //Agrega código Prestamin
                $prestamin                  = TipoProcedimientosPm::where('id', '=', $listaespera->tipo_procedimientos_pms_id)->first();
                $listaespera->prestamin_ing = $prestamin->prestamin;
            }

            $listaespera->tramos_id        = $tramo_id;
            $listaespera->users_id_ingreso = Auth::user()->id;

            //RECUPERAR ESTABLECIMIENTO DEL USUARIO EN CASO DE QUE SESION ESTE VACIA
            $establecimiento = session('establecimiento');

            if (is_null($establecimiento)) {
                $user            = User::find(Auth::user()->id);
                $establecimiento = $user->establecimientos()->first()->id;
            }

            $listaespera->nodo = $establecimiento;

            //Si lista de espera esta duplicada, retorna error, sino guarda lista de espera
            if($listaespera->Duplicado()) {
                return redirect('listaesperas/create')
                    ->with('message', 'duplicado')
                    ->withInput();
            }
            else {    
                $listaespera->save();
            }    

            /*********************************************************************************************************/    
            /*                                       INGRESO DATOS DE LOG                                            */
            /*********************************************************************************************************/
            $Log                      = new logLep;
            $Log->name                = 'lista_esperas';
            $Log->tabla_id            = $listaespera->id;
            $Log->estado              = 'Creación';
            $Log->establecimientos_id = $establecimiento;
            $Log->user_id             = Auth::user()->id;
            $Log->save();

            return redirect('listaesperas/create')->with('message', 'create');
        } 
        else {
            return view('auth/login');
        }
    }
    /*******************************************************************************************/
    /*                             FIN CREAR LISTA DE ESPERA                                   */
    /*******************************************************************************************/

    
    /*******************************************************************************************/
    /*                                         EGRESO                                          */
    /*******************************************************************************************/
    /**
     * Filtro inicial de Lista para Egreso de Listas de Espera
     * Vista: listaEsperas.filtroEgreso
     * Rol: digitadorLE
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filtroEgreso(Request $request)
    {
        if (Auth::check()) {
            Session::forget('idPaciente'); //Elimina la variable en session
            
            return view('listaEsperas.filtroEgreso');
        } 
        else {
            return view('auth/login');
        }
    }
    /**
     * Muestra Listas de Espera en estado "Abiertas para su egreso".
     * Rol: digitadorLE
     * Vista: listaEsperas.egreso
     *
     * @param  \Illuminate\Http\Request  $request     
     * @return \Illuminate\Http\Response
     */
    public function egreso(Request $request)
    {
        if (Auth::check()) {
            $listaesperas = DB::table('lista_esperas')
                ->join('pacientes', 'pacientes.id', '=', 'lista_esperas.pacientes_id')
                ->join('establecimientos as estorigen', 'estorigen.id', '=', 'lista_esperas.establecimientos_id_origen')
                ->join('establecimientos as estdestino', 'estdestino.id', '=', 'lista_esperas.establecimientos_id_destino')
                ->join('especialidads', 'especialidads.id', '=', 'lista_esperas.especialidads_ingreso_id')
				->join('tipo_prestacions', 'tipo_prestacions.id', '=', 'lista_esperas.tipo_prestacions_id')
                ->where('lista_esperas.active', '=', 1)
                ->select('lista_esperas.id as id',
                    'pacientes.tipoDoc as tipoDoc',
                    'pacientes.rut as rut',
                    'pacientes.dv as dv',
                    'pacientes.numDoc',
                    'pacientes.nombre',
                    'pacientes.apPaterno',
                    'pacientes.apMaterno',
                    'estorigen.name as establecimiento_origen',
                    'estdestino.name as establecimiento_destino',
                    'especialidads.name as especialidad',
                    'lista_esperas.tipo_ges_id as tipo_ges_id',
					'lista_esperas.prestamin_ing as prestamin',
					'tipo_prestacions.name as prestacion')
                ->selectRaw('DATE_FORMAT(lista_esperas.fechaingreso, "%d-%m-%Y") as fecha');

            // Busqueda por rut
            if ($request->get('idPaciente') != null && Session::get('idPaciente') == null) {
                $listaesperas = $listaesperas->where('pacientes.id', '=', $request->get('idPaciente'));
                Session::put('idPaciente', $request->get('idPaciente'));
            }
            elseif(Session::get('idPaciente') != null) {
                $listaesperas = $listaesperas->where('pacientes.id', '=', Session::get('idPaciente'));
            }

            $listaesperas = $listaesperas->Paginate(10)
                ->appends('idPaciente', Session::get('idPaciente'));

            return view('listaEsperas.egreso', compact('listaesperas'));
        } 
        else {
            return view('auth/login');
        }
    }

    /**
     * Muestra pantalla con Información de la Lista de Espera y Formulario de Egreso
     * Rol: digitadorLE
     * Vista: listaEsperas.detalle
     *
     * @param int $id ID de Lista de Espera
     * @return \Illuminate\Http\Response
     */
    public function detalle($id)
    {
        if (Auth::check()) {
            $listaEspera = ListaEspera::find($id);

            if ($listaEspera->active == 1) {

                //determina si ListaEsperas corresponde al establecimiento del usuario
                //Datos del Paciente
                $paciente        = Paciente::find($listaEspera->pacientes_id);
                $fechaNacimiento = new DateTime($paciente->fechaNacimiento);
                $fechaNacimiento = $fechaNacimiento->format('d-m-Y');
                //calcula edad
                $date = date('Y-m-d'); //la fecha del computador
                $diff = abs(strtotime($date) - strtotime($paciente->fechaNacimiento));
                $edad = floor($diff / (365 * 60 * 60 * 24));

                if ($paciente->via_id != null) {
                    $vias = Via::find($paciente->via_id);
                    $via  = $vias->name;
                } 
                else {
                    $via = '';
                }

                $previsions = Prevision::find($paciente->prevision_id);
                $prevision  = $previsions->name;

                if ($paciente->prevision_id == 1) {
                    $tramos = Tramo::find($paciente->tramo_id);
                    $tramo  = $tramos->name;
                } 
                else {
                    $tramo = '';
                }

                if ($listaEspera->tipo_ges_id == 1) {
                    $tipoGes = 'Si';
                } 
                else {
                    $tipoGes = 'No';
                }

                $estOrigen = Establecimiento::find($listaEspera->establecimientos_id_origen);
                $estDest   = Establecimiento::find($listaEspera->establecimientos_id_destino);

                $fechaingreso  = date("d-m-Y", strtotime($listaEspera->fechaingreso));
                if( $listaEspera->fechacitacion != null ) {
                    $fechacitacion = date("d-m-Y", strtotime($listaEspera->fechacitacion));
                }
                else {
                    $fechacitacion = '';   
                }

                $medico     = $listaEspera->run_medico_solicita . "-" . $listaEspera->dv_medico_solicita;
                $tipoEspera = TipoEspera::find($listaEspera->tipo_esperas_id);

                // Recupera Cie10, si es 0 obtiene el texto del Cie10 versión anterior LEP
                $cie10s = Cie10::find($listaEspera->cie10s_id);
                if ($listaEspera->cie10s_id == 0) {
                    if ($listaEspera->cie10_ant === null) {
                        $cie10 = $cie10s->codigo . ' - ' . $cie10s->name;
                    } 
                    else {
                        $cie10 = $listaEspera->cie10_ant;
                    }
                } 
                else {
                    $cie10 = $cie10s->codigo . ' - ' . $cie10s->name;
                }

                $especialidad_ing = Especialidad::find($listaEspera->especialidads_ingreso_id);
                $prestacion       = TipoPrestacion::find($listaEspera->tipo_prestacions_id);

                $procedimiento_id   = "";
                $procedimientopm_id = "";

                if ($listaEspera->tipo_prestacions_id != 1) {

                    if (is_null($listaEspera->tipo_procedimientos_id) == false) {
                        $procedimiento    = TipoProcedimiento::find($listaEspera->tipo_procedimientos_id);
                        $procedimiento_id = $procedimiento->name;
                    }
                    if (is_null($listaEspera->tipo_procedimientos_pms_id) == false) {
                        $procedimientopm    = TipoProcedimientosPm::find($listaEspera->tipo_procedimientos_pms_id);
                        $procedimientopm_id = $procedimientopm->name;
                    }
                }

                if ($listaEspera->planos_id != null) {
                    $planos = Plano::find($listaEspera->planos_id);
                    $plano  = $planos->name;
                } 
                else {
                    $plano = '';
                }

                if ($listaEspera->extremidads_id != null) {
                    $extremidads = Extremidad::find($listaEspera->extremidads_id);
                    $extremidad  = $extremidads->name;
                } 
                else {
                    $extremidad = '';
                }

                // Definición predeterminada del prestamin de egreso
                if($listaEspera->tipo_prestacions_id =='1') // Si el tipo de prestacion es igual a 1 el prestamin debe ser igual al codigo SIGTE de la especialidad de ingreso
                {
                    $listaEspera->prestamin_egr = $especialidad_ing->sigte;
                }
                elseif($listaEspera->tipo_prestacions_id =='3') // Si el tipo de prestacion es igual a 3 el prestamin debe ser igual al prestamin de ingreso
                {
                    $listaEspera->prestamin_egr = $listaEspera->prestamin_ing;
                }                
                else // Sino, si es diferente a 1 y 3 debe ser en blanco
                {
                    $listaEspera->prestamin_egr = "";
                }

                //Campos de Egreso
                $estResuelve   = Establecimiento::where('active', 1)->orderBy('name')->get();
                $CausalEgresos = CausalEgreso::where('active', 1)->orderBy('id')->get();

                return view('listaEsperas.detalle', compact('listaEspera', 'paciente', 'fechaNacimiento', 'edad', 'estOrigen', 'estDest', 'prevision', 'tipoGes', 'tramo', 'medico', 'tipoEspera', 'cie10', 'especialidad_ing', 'prestacion', 'plano', 'extremidad', 'estResuelve', 'TipoSalida', 'CausalEgresos', 'TipoSalida', 'procedimientopm_id', 'procedimiento_id', 'via', 'fechaingreso', 'fechacitacion'));
            } 
            else {
                if (Auth::User()->isRole('Digitador LE') || Auth::User()->isRole('Super Usuario LE')) {
                    $opc = 'le';
                    return view('home', compact('opc'));
                } 
                else {
                    return view('auth/login');
                }
            }
        } 
        else {
            return view('auth/login');
        }
    }

    /**
     * Actualiza LE con datos de Egreso
     * Rol: digitadorLE
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualiza(Request $request)
    {
        if (Auth::check()) {
            $id           = $request->input('id');
            $ListaEsperas = ListaEspera::find($id);
            if ($ListaEsperas->active == 1) {

                if ($ListaEsperas->fechaingreso != null) {
                    $fecha_inicio = DateTime::createFromFormat('Y-m-d', $ListaEsperas->fechaingreso);
                } 
                else {
                    $fecha_inicio = $ListaEsperas->created_at;
                }

                $hoy = new DateTime('now');

                $validator = Validator::make($request->all(), [
                    'fechaSalida' => 'required|date|after_or_equal:' . $fecha_inicio->format('Y-m-d') . '|before_or_equal:' . $hoy->format('Y-m-d'),
                ]);

                if ($validator->fails()) {
                    return redirect('listaesperas/' . $id . '/detalle')
                        ->with('message', 'fechaegreso')
                        ->withInput();
                }

                $fechaegreso = DateTime::createFromFormat('d-m-Y', $request->input('fechaSalida'));

                //Actualiza detalles del egreso
                $ListaEsperas->fechaegreso                  = $fechaegreso;
                $ListaEsperas->prestamin_egr                = $request->input('prestamin_egr');
                // VALIDA prestamin_egr
                // Definición predeterminada del prestamin de egreso
                if($ListaEsperas->tipo_prestacions_id =='1') // Si el tipo de prestacion es igual a 1 el prestamin debe ser igual al codigo SIGTE de la especialidad de ingreso
                {
                    $existe_especialidad =DB::table('especialidads')->WHERE('sigte',$ListaEsperas->prestamin_egr)->WHERE('active', 1)->exists();
                    if (!$existe_especialidad)
                    {
                        return redirect('listaesperas/' . $id . '/detalle')
                            ->with('message', 'prestamin_egreso')
                            ->withInput();
                    }
                }
                else // Si el tipo de prestacion es igual a un prestamin de la tabla de procedimientos_pms
                {
                    $existe_tipo_procedimientos_pms =DB::table('tipo_procedimientos_pms')
                                                    ->join('tipo_procedimientos','tipo_procedimientos_pms.tipo_procedimiento_id','=','tipo_procedimientos.id')
                                                    ->WHERE('tipo_procedimientos.tipo_prestacion_id',$ListaEsperas->tipo_prestacions_id)
                                                    ->WHERE('tipo_procedimientos.active', 1)
                                                    ->WHERE('tipo_procedimientos_pms.prestamin',$ListaEsperas->prestamin_egr)
                                                    ->WHERE('tipo_procedimientos_pms.active', 1)->exists();
                    if(!$existe_tipo_procedimientos_pms) 
                    {
                        return redirect('listaesperas/' . $id . '/detalle')
                            ->with('message', 'prestamin_egreso')
                            ->withInput();
                    }                  
                }                
                // CIERRE VALIDA prestamin_egr

                $ListaEsperas->establecimientos_id_resuelve = $request->input('idResuelve');
                $ListaEsperas->causal_egresos_id            = $request->input('idCausalEgreso');
                $ListaEsperas->run_medico_resol             = $request->input('run_medico_resol');
                $ListaEsperas->dv_medico_resol              = $request->input('dv_medico_resol');
                $ListaEsperas->resultado                    = $request->input('resultado');   
                $ListaEsperas->users_id_egreso              = Auth::user()->id;

                // Fecha de Digitación de la lista de espera
                $ListaEsperas->fecha_digitacion_egreso = date('Y-m-d H:i:s');

                // Estado de lista de Espera 1 - Abierta; 0 - Cerrada;
                $ListaEsperas->active = 0;

                $ListaEsperas->save();

                //Almacena LOG de actualización
                $Log           = new logLep;
                $Log->name     = 'lista_esperas';
                $Log->tabla_id = $ListaEsperas->id;
                $Log->estado   = 'Egreso';

                $establecimiento = session('establecimiento');
                if (is_null($establecimiento)) {
                    $user            = User::find(Auth::user()->id);
                    $establecimiento = $user->establecimientos()->first()->id;
                }
                $Log->establecimientos_id = $establecimiento;

                $Log->user_id = Auth::user()->id;
                $Log->save();

                return redirect('listaesperas/filtroEgreso')->with('message', 'actualiza');
            } 
            else {
                return redirect('listaesperas/filtroEgreso')->with('message', 'egresada');
            }

        } 
        else {
            return view('auth/login');
        }
    }
    /*******************************************************************************************/
    /*                                     FIN EGRESO                                          */
    /*******************************************************************************************/
    
    /*******************************************************************************************/
    /*                               EDICION DE LISTA DE ESPERA                                */
    /*******************************************************************************************/
    /**
     * Filtro inicial de Lista para Edición de Listas de Espera
     * Vista: listaEsperas.filtroRegistro
     * Rol: superUsuarioLE 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filtroRegistro(Request $request)
    {
        if (Auth::check()) {
            Session::forget('idPaciente'); //Elimina la variable en session
            Session::forget('estado'); //Elimina la variable en session
            
            return view('listaEsperas.filtroRegistro');
        } 
        else {
            return view('auth/login');
        }
    }
    /**
     * Lista para Edición de Listas de Espera
     * Vista: listaEsperas.registro
     * Rol: superUsuarioLE
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registro(Request $request)
    {
        if (Auth::check()) {
            $listaesperas     = DB::table('lista_esperas')
                ->join('pacientes', 'pacientes.id', '=', 'lista_esperas.pacientes_id')
                ->join('establecimientos as estorigen', 'estorigen.id', '=', 'lista_esperas.establecimientos_id_origen')
                ->join('establecimientos as estdestino', 'estdestino.id', '=', 'lista_esperas.establecimientos_id_destino')
                ->join('comunas as comuna', 'comuna.id', '=', 'pacientes.comuna_id')
                ->join('especialidads as especialidad', 'especialidad.id', '=', 'lista_esperas.especialidads_ingreso_id')
                ->join('cie10s as cie10', 'cie10.id', '=', 'lista_esperas.cie10s_id')
                ->join('tipo_prestacions as prestacion', 'prestacion.id', '=', 'lista_esperas.tipo_prestacions_id')
                ->select('lista_esperas.id as id',
                    'estorigen.servicio_id as  serv_salud',
                    'pacientes.tipoDoc as tipo_doc',
                    'pacientes.rut as run',
                    'pacientes.dv as dv',
                    'pacientes.numDoc as documento',
                    'pacientes.nombre as nombres',
                    'pacientes.apPaterno as primer_apellido',
                    'pacientes.apMaterno as segundo_apellido',
                    'prestacion.name as tipo_prest',
                    'lista_esperas.prestamin_ing as presta_min',
                    'especialidad.name as presta_est',
                    'estorigen.name as estab_orig',
                    'estdestino.name as estab_dest',
                    'lista_esperas.fechaegreso as f_salida',
                    'lista_esperas.causal_egresos_id as c_salida',
                    'lista_esperas.establecimientos_id_resuelve as e_otor_at',
                    'lista_esperas.prestamin_egr as presta_min_salida',
                    'comuna.name as comuna',
                    'cie10.name as sospecha_diag',
                    'lista_esperas.precdiag as confir_diag',
                    'lista_esperas.fechacitacion as f_citacion',
                    'lista_esperas.run_medico_solicita as run_prof_sol',
                    'lista_esperas.dv_medico_solicita as dv_prof_sol',
                    'lista_esperas.users_id_ingreso as digitador',
                    'lista_esperas.users_id_egreso as egresa',
                    'lista_esperas.tipo_ges_id as ges',
                    'lista_esperas.active as estado',
                    'lista_esperas.cie10_ant',
                    'lista_esperas.cie10s_id',
                    'lista_esperas.id_lep as id_sigte')
                ->selectRaw('DATE_FORMAT(lista_esperas.updated_at, "%d-%m-%Y") as fecha_modificacion')
                ->selectRaw('DATE_FORMAT(lista_esperas.created_at, "%d-%m-%Y") as fecha_digitacion')
                ->selectRaw('DATE_FORMAT(lista_esperas.fechaingreso, "%d-%m-%Y") as f_entrada');
            
            // Busqueda por rut
            if ($request->get('idPaciente') != null && Session::get('idPaciente') == null) {
                $listaesperas = $listaesperas->where('pacientes.id', '=', $request->get('idPaciente'));
                Session::put('idPaciente', $request->get('idPaciente'));
            }
            elseif(Session::get('idPaciente') != null) {
                $listaesperas = $listaesperas->where('pacientes.id', '=', Session::get('idPaciente'));
            }
            
            // Busqueda por estado de Lista de espera
            if ($request->get('estado') != null && Session::get('estado') == null) {
                $listaesperas = $listaesperas->where('lista_esperas.active', '=', $request->get('estado'));
                Session::put('estado', $request->get('estado'));
            }
            elseif(Session::get('estado') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.active', '=', Session::get('estado'));
            }
            
            $listaesperas = $listaesperas->paginate(10)
                ->appends('idPaciente', Session::get('idPaciente'))
                ->appends('estado', Session::get('estado'));

            return view('listaEsperas.registro', compact('listaesperas'));
        } 
        else {
            return view('auth/login');
        }
    }

    /**
     * LLama a formulario de edición de lista de espera
     * Vista: listaEsperas.editar
     * Rol: superUsuarioLE
     *
     * @param int $id ID de Lista de Espera
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        if (Auth::check()) {
            $listaEspera = ListaEspera::find($id);

            //determina establecimiento de usuario conectado
            $especialidads   = Especialidad::where('active', 1)->orderBy('name')->get();
            $tipoEsperas     = TipoEspera::where('active', 1)->orderBy('name')->get();
            $id_origens      = Establecimiento::where('active', 1)->orderBy('name')->get();
            $id_destinos     = Establecimiento::where('active', 1)->orderBy('name')->get();
            $tipoPrestacions = TipoPrestacion::where('active', 1)->orderBy('name')->get();

            //determina si ListaEsperas corresponde al establecimiento del usuario
            //Datos del Paciente
            $paciente        = Paciente::find($listaEspera->pacientes_id);
            $fechaNacimiento = new DateTime($paciente->fechaNacimiento);
            $fechaNacimiento = $fechaNacimiento->format('d-m-Y');

            //formato fecha de Ingreso
            $fechaingreso = new DateTime($listaEspera->fechaingreso);
            $fechaingreso = $fechaingreso->format('d-m-Y');

            //formato fecha de Citación
            if ($listaEspera->fechacitacion != null) {
                $fechacitacion = new DateTime($listaEspera->fechacitacion);
                $fechacitacion = $fechacitacion->format('d-m-Y');
            } 
            else {
                $fechacitacion = '';
            }

            //calcula edad
            $date = date('Y-m-d'); //la fecha del computador
            $diff = abs(strtotime($date) - strtotime($paciente->fechaNacimiento));
            $edad = floor($diff / (365 * 60 * 60 * 24));

            if ($paciente->via_id != null) {
                $vias = Via::find($paciente->via_id);
                $via  = $vias->name;
            } 
            else {
                $via = '';
            }

            $previsions = Prevision::find($paciente->prevision_id);
            $prevision  = $previsions->name;

            if ($paciente->prevision_id == 1) {
                if (is_null($paciente->tramo_id)) {
                    $tramo = '';
                } 
                else {
                    $tramos = Tramo::find($paciente->tramo_id);
                    $tramo  = $tramos->name;
                }
            } 
            else {
                $tramo = '';
            }

            $medico     = $listaEspera->run_medico_solicita . "-" . $listaEspera->dv_medico_solicita;
            $tipoEspera = TipoEspera::find($listaEspera->tipo_esperas_id);

            // Recupera Cie10, si es 0 obtiene el texto del Cie10 versión anterior LEP
            $cie10s = Cie10::find($listaEspera->cie10s_id);
            if ($listaEspera->cie10s_id == 0) {
                if ($listaEspera->cie10_ant === null) {
                    $cie10 = $cie10s->codigo . ' - ' . $cie10s->name;
                } 
                else {
                    $cie10 = $listaEspera->cie10_ant;
                }
            } 
            else {
                $cie10 = $cie10s->codigo . ' - ' . $cie10s->name;
            }

            $especialidad_ing = Especialidad::find($listaEspera->especialidads_ingreso_id);
            $prestacion       = TipoPrestacion::find($listaEspera->tipo_prestacions_id);

            if ($listaEspera->tipo_prestacions_id != 1) {

                $procedimientopm_id = $listaEspera->tipo_procedimientos_pms_id;
                $procedimiento_id   = $listaEspera->tipo_procedimientos_id;

                $procedimientos   = TipoProcedimiento::where([['tipo_prestacion_id', $listaEspera->tipo_prestacions_id], ['active', 1]])->orderBy('name')->get();
                $procedimientopms = TipoProcedimientosPm::where([['tipo_procedimiento_id', $procedimiento_id], ['active', 1]])->orderBy('name')->get();
            } 
            else {
                $procedimientos   = null;
                $procedimientopms = null;

                $procedimientopm_id = 0;
                $procedimiento_id   = 0;
            }

            //Campos de Egreso
            $estResuelve   = Establecimiento::where('active', 1)->orderBy('name')->get();
            $TipoSalida    = TipoSalida::where('active', 1)->orderBy('name')->get();
            $CausalEgresos = CausalEgreso::where('active', 1)->orderBy('id')->get();
            $TipoSalida    = TipoSalida::where('active', 1)->orderBy('name')->get();

            //formato fecha de egreso
            if ($listaEspera->fechaegreso != null) {
                $fechaegreso = new DateTime($listaEspera->fechaegreso);
                $fechaegreso = $fechaegreso->format('d-m-Y');
            } 
            else {
                $fechaegreso = '';
            }

            return view('listaEsperas.editar', compact('listaEspera', 'paciente', 'fechaNacimiento', 'edad', 'id_origens', 'id_destinos', 'medico', 'prevision', 'tipoGes', 'tramo', 'medico', 'tipoEsperas', 'cie10', 'especialidads', 'tipoPrestacions', 'procedimientopm_id', 'procedimiento_id', 'procedimientopms', 'procedimientos', 'fechaingreso', 'fechacontrol', 'fechacitacion', 'via', 'estResuelve', 'TipoSalida', 'CausalEgresos', 'TipoSalida', 'fechaegreso'));
        } 
        else {
            return view('auth/login');
        }
    }

    /**
     * LLama a formulario de edición de lista de espera
     * Vista: listaEsperas.bitacora
     * Rol: superUsuarioLE
     *
     * @param int $id ID de Lista de Espera
     * @return \Illuminate\Http\Response
     */
    public function bitacora($id)
    {
        if (Auth::check()) {
            $listaesperas = DB::table('lista_esperas')
                ->join('pacientes', 'pacientes.id', '=', 'lista_esperas.pacientes_id')
                ->join('establecimientos as estorigen', 'estorigen.id', '=', 'lista_esperas.establecimientos_id_origen')
                ->join('establecimientos as estdestino', 'estdestino.id', '=', 'lista_esperas.establecimientos_id_destino')
                ->join('comunas as comuna', 'comuna.id', '=', 'pacientes.comuna_id')
                ->join('especialidads as especialidad', 'especialidad.id', '=', 'lista_esperas.especialidads_ingreso_id')
                ->join('cie10s as cie10', 'cie10.id', '=', 'lista_esperas.cie10s_id')
                ->join('tipo_prestacions as prestacion', 'prestacion.id', '=', 'lista_esperas.tipo_prestacions_id')
                ->where('lista_esperas.id', '=', $id)
                ->select('lista_esperas.id as id',
                    'estorigen.servicio_id as  serv_salud',
                    'pacientes.tipoDoc as tipo_doc',
                    'pacientes.rut as run',
                    'pacientes.dv as dv',
                    'pacientes.numDoc as documento',
                    'pacientes.nombre as nombres',
                    'pacientes.apPaterno as primer_apellido',
                    'pacientes.apMaterno as segundo_apellido',
                    'prestacion.name as tipo_prest',
                    'lista_esperas.prestamin_ing as presta_min',
                    'especialidad.name as presta_est',
                    'estorigen.name as estab_orig',
                    'estdestino.name as estab_dest',
                    'lista_esperas.fechaegreso as f_salida',
                    'lista_esperas.causal_egresos_id as c_salida',
                    'comuna.name as comuna',
                    'cie10.name as sospecha_diag',
                    'lista_esperas.fechacitacion as f_citacion',
                    'lista_esperas.active as estado',
                    'lista_esperas.cie10_ant',
                    'lista_esperas.cie10s_id')
                ->selectRaw('DATE_FORMAT(lista_esperas.updated_at, "%d-%m-%Y") as fecha_modificacion')
                ->selectRaw('DATE_FORMAT(lista_esperas.created_at, "%d-%m-%Y") as fecha_digitacion')
                ->selectRaw('DATE_FORMAT(lista_esperas.fechaingreso, "%d-%m-%Y") as f_entrada')
                ->selectRaw('DATE_FORMAT(lista_esperas.fechaegreso, "%d-%m-%Y") as f_egreso')
                ->selectRaw('DATE_FORMAT(lista_esperas.fecha_digitacion_egreso, "%d-%m-%Y") as f_dig_egreso')
                ->orderBy('lista_esperas.created_at', 'desc')->get();

            $logs = DB::table('log_leps')
                ->join('users', 'users.id', '=', 'log_leps.user_id')
                ->join('establecimientos', 'establecimientos.id', '=', 'establecimientos_id')
                ->Where('log_leps.name', '=', 'lista_esperas')
                ->Where('tabla_id', '=', $id)
                ->select('log_leps.Estado as estado', 'establecimientos.name as establecimiento', 'users.name as usuario')
                ->selectRaw('DATE_FORMAT(log_leps.created_at, "%d-%m-%Y") as fecha')
                ->orderBy('log_leps.created_at', 'asc')->get();

            return view('listaEsperas.bitacora', compact('listaesperas', 'logs'));
        } 
        else {
            return view('auth/login');
        }
    }

    /**
     * Actualiza Lista de Espera
     * Rol: superUsuarioLE
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function actualizaingreso(Request $request)
    {
        if (Auth::check()) {
            $id = $request->input('id');

            // Valida Fecha de Entrada
            $validator = Validator::make($request->all(), [
                'fechaingreso' => 'required|date|date_format:"d-m-Y"',
            ]);
            if ($validator->fails()) {
                return redirect('listaesperas/' . $id . '/editar')
                    ->with('message', 'fecha_entrada')
                    ->withInput();
            }

            // Valida Fecha de citación
            if ($request->input('fechacitacion') != '') {
                $validator2 = Validator::make($request->all(), [
                    'fechacitacion' => 'date|date_format:"d-m-Y"',
                ]);
                if ($validator2->fails()) {
                    return redirect('listaesperas/' . $id . '/editar')
                        ->with('message', 'fecha_citacion')
                        ->withInput();
                }
            }

            //valida si diagnostico cie10 existe
            $validator3 = validator::make($request->all(), [
                'idCie10' => 'required',
            ]);
            if ($validator3->fails()) {
                return redirect('listaesperas/' . $id . '/editar')
                    ->with('message', 'cie10')
                    ->withInput();
            }

            // Valida Fecha de Egreso
            if ($request->input('estado') == 0) {
                $validator4 = Validator::make($request->all(), [
                    'fechaSalida' => 'date|date_format:"d-m-Y"',
                ]);
                if ($validator4->fails()) {
                    return redirect('listaesperas/' . $id . '/editar')
                        ->with('message', 'fecha_egreso')
                        ->withInput();
                }
            }

            $ListaEsperas = ListaEspera::find($id);

            $fechaingreso  = DateTime::createFromFormat('d-m-Y', $request->input('fechaingreso'));
            $fechacitacion = DateTime::createFromFormat('d-m-Y', $request->input('fechacitacion'));

            //Actualiza actualización de la lista de espera
            $ListaEsperas->tipo_ges_id                 = $request->input('tipo_ges_id');
            $ListaEsperas->fechaingreso                = $fechaingreso;
            $ListaEsperas->establecimientos_id_origen  = $request->input('idorigen');
            $ListaEsperas->establecimientos_id_destino = $request->input('iddestino');
            $ListaEsperas->run_medico_solicita         = $request->input('run_medico_solicita');
            $ListaEsperas->dv_medico_solicita          = $request->input('dv_medico_solicita');
            if ($fechacitacion == '') {
                $ListaEsperas->fechacitacion = null;
            } 
            else {
                $ListaEsperas->fechacitacion = $fechacitacion;
            }

            $ListaEsperas->cie10s_id = $request->input('idCie10');

            $ListaEsperas->especialidads_ingreso_id = $request->input('especialidads_ingreso_id');

            $ListaEsperas->precdiag = $request->input('precdiag');

            $tipo_prestacions_id               = $request->input('tipo_prestacions_id');
            $ListaEsperas->tipo_prestacions_id = $tipo_prestacions_id;
            // Tipo de Prestacion = 1 "Consulta Nueva "
            if ($tipo_prestacions_id == 1) {
                $ListaEsperas->tipo_procedimientos_id     = null;
                $ListaEsperas->tipo_procedimientos_pms_id = null;
                $ListaEsperas->planos_id                  = null;
                $ListaEsperas->extremidads_id             = null;

                /* cambio prestamin cuando viene nulo */
                if (is_null($request->input('prestamin'))) {
                    $prestamin                   = Especialidad::where('id', '=', $ListaEsperas->especialidads_ingreso_id)->first();
                    $ListaEsperas->prestamin_ing = $prestamin->rem;
                } 
                else {
                    $ListaEsperas->prestamin_ing = $request->input('prestamin');
                }

            }
            // Tipo de Prestacion <> 1 "Procedimientos", "Cirugias"
            else {
                $ListaEsperas->tipo_procedimientos_id     = $request->input('tipo_procedimiento_id');
                $ListaEsperas->tipo_procedimientos_pms_id = $request->input('tipo_procedimiento_pm_id');
                $ListaEsperas->planos_id                  = $request->input('planos_id');
                $ListaEsperas->extremidads_id             = $request->input('extremidads_id');

                /* cambio prestamin cuando viene nulo */
                if (is_null($request->input('prestamin'))) {
                    $prestamin                   = TipoProcedimientosPm::where('id', '=', $ListaEsperas->tipo_procedimientos_pms_id)->first();
                    $ListaEsperas->prestamin_ing = $prestamin->prestamin;
                } 
                else {
                    $ListaEsperas->prestamin_ing = $request->input('prestamin');
                }
            }

            //Actualiza datos de egreso si lista de espera en estado cerrada
            if ($ListaEsperas->active == 0) {
                $fechaegreso                                = DateTime::createFromFormat('d-m-Y', $request->input('fechaSalida'));
                $ListaEsperas->fechaegreso                  = $fechaegreso;
                $ListaEsperas->prestamin_egr                = $request->input('prestamin_egr');
                $ListaEsperas->establecimientos_id_resuelve = $request->input('idResuelve');
                $ListaEsperas->causal_egresos_id            = $request->input('idCausalEgreso');
                $ListaEsperas->run_medico_resol             = $request->input('run_medico_resol');
                $ListaEsperas->dv_medico_resol              = $request->input('dv_medico_resol');
                $ListaEsperas->resultado                    = $request->input('resultado');   
            }

            //Actualiza detalles del egreso 0 = Cerrada / 1 = Abierta
            $estado = $request->input('estado');
            if ($ListaEsperas->active == 0 && $estado == 1) {
                $ListaEsperas->active                       = $estado;
                $ListaEsperas->fechaegreso                  = null;
                $ListaEsperas->prestamin_egr                = null;
                $ListaEsperas->tipo_salidas_id              = null;
                $ListaEsperas->establecimientos_id_resuelve = null;
                $ListaEsperas->causal_egresos_id            = null;
                $ListaEsperas->users_id_egreso              = null;
                $ListaEsperas->fecha_digitacion_egreso      = null;
                $ListaEsperas->run_medico_resol             = null;
                $ListaEsperas->dv_medico_resol              = null;
                $ListaEsperas->resultado                    = null;
            } 
            else {
                $ListaEsperas->active = $estado;
            }

            $ListaEsperas->save();

            //Almacena LOG
            $Log           = new logLep;
            $Log->name     = 'lista_esperas';
            $Log->tabla_id = $ListaEsperas->id;
            $Log->estado   = 'Actualización';

            $establecimiento = session('establecimiento');
            if (is_null($establecimiento)) {
                $user            = User::find(Auth::user()->id);
                $establecimiento = $user->establecimientos()->first()->id;
            }
            $Log->establecimientos_id = $establecimiento;

            $Log->user_id = Auth::user()->id;
            $Log->save();

            return redirect('listaesperas/registro')->with('message', 'actualiza')->with('id', $id);
        } 
		else {
            return view('auth/login');
        }
    }
    /*******************************************************************************************/
    /*                              FIN EDICION DE LISTA DE ESPERA                             */
    /*******************************************************************************************/

    /*******************************************************************************************/
    /*                                       REPORTES                                          */
    /*******************************************************************************************/
    /**
     * Genera reporte RNLE según filtros definidos por el usuario
     * Vista: listaEsperas.reporternle
     * Rol: None
     *
     * @return list lista de ListaEsperas que coinciden con la busqueda
     */
    public function reporternle()
    {
        if (Auth::check()) {
            $establecimientos = Establecimiento::where('active', 1)->orderBy('name')->get();
            $comunas          = Comuna::where('active', 1)->orderBy('name')->get();
            $prestacions      = TipoPrestacion::where('active', 1)->orderBy('name')->get();
            $especialidads    = Especialidad::orderBy('name')->get();

            return view('listaEsperas.reporternle', compact('establecimientos', 'comunas', 'prestacions', 'especialidads'));
        } 
        else {
            return view('auth/login');
        }
    }

    /**
     * Genera reportes Consulta Red según filtros definidos por el usuario
     * Vista: listaEsperas.reporte
     * Rol: None
     *
     * @return list lista de ListaEsperas que coinciden con la busqueda
     */
    public function reporte()
    {
        if (Auth::check()) {
            $establecimientos = Establecimiento::where('active', 1)->orderBy('name')->get();
            $comunas          = Comuna::where('active', 1)->orderBy('name')->get();
            $prestacions      = TipoPrestacion::where('active', 1)->orderBy('name')->get();
            $especialidads    = Especialidad::orderBy('name')->get();

            return view('listaEsperas.reporte', compact('establecimientos', 'comunas', 'prestacions', 'especialidads'));
        } 
        else {
            return view('auth/login');
        }
    }

    /**
     * Resultado reporte RNLE según filtros definidos por el usuario
     * Vista: listaEsperas.resultadornle
     * Rol: None
     *
     * @param  \Illuminate\Http\Request  $request
     * @return list lista de ListaEsperas que coinciden con la busqueda
     */
    public function resultadornle(Request $request)
    {   
        if (Auth::check()) {
            $listaesperas = DB::table('lista_esperas')
                ->join('pacientes', 'pacientes.id', '=', 'lista_esperas.pacientes_id')
                ->join('establecimientos as estorigen', 'estorigen.id', '=', 'lista_esperas.establecimientos_id_origen')
                ->join('establecimientos as estdestino', 'estdestino.id', '=', 'lista_esperas.establecimientos_id_destino')
                ->leftjoin('establecimientos as estotorga', 'estotorga.id', '=', 'lista_esperas.establecimientos_id_resuelve')
                ->join('comunas as comuna', 'comuna.id', '=', 'pacientes.comuna_id')
                ->join('especialidads as especialidad', 'especialidad.id', '=', 'lista_esperas.especialidads_ingreso_id')
                ->join('cie10s as cie10', 'cie10.id', '=', 'lista_esperas.cie10s_id')
                ->join('tipo_prestacions as prestacion', 'prestacion.id', '=', 'lista_esperas.tipo_prestacions_id')
                ->join('previsions as prevision', 'prevision.id', '=', 'pacientes.prevision_id')
                ->select('lista_esperas.id as id',
                    'estorigen.servicio_id as  serv_salud',
                    'pacientes.tipoDoc as tipo_doc',
                    'pacientes.rut as run',
                    'pacientes.dv as dv',
                    'pacientes.numDoc as documento',
                    'pacientes.nombre as nombres',
                    'pacientes.apPaterno as primer_apellido',
                    'pacientes.apMaterno as segundo_apellido',
                    'pacientes.fechaNacimiento as fecha_nac',
                    'pacientes.genero_id as sexo',
                    'prevision.name as prevision',
                    'prestacion.name as tipo_prest',
                    'lista_esperas.prestamin_ing as presta_min',
                    'especialidad.name as presta_est',
                    'estorigen.name as estab_orig',
                    'estdestino.name as estab_dest',
                    'lista_esperas.causal_egresos_id as c_salida',
                    'estotorga.name as e_otor_at',
                    'lista_esperas.prestamin_egr as presta_min_salida',
                    'lista_esperas.prestamin_egr as presta_min_salida',
                    'pacientes.prais as prais',
                    'comuna.codigo as comuna',
                    'cie10.name as sospecha_diag',
                    'lista_esperas.precdiag as confir_diag',
                    'pacientes.direccion as nom_calle',
                    'pacientes.telefono as fono_fijo',
                    'pacientes.telefono2 as fono_movil',
                    'pacientes.email as email',
                    'lista_esperas.fechacitacion as f_citacion',
                    'lista_esperas.run_medico_solicita as run_prof_sol',
                    'lista_esperas.dv_medico_solicita as dv_prof_sol',
                    'lista_esperas.users_id_ingreso as digitador',
                    'lista_esperas.users_id_egreso as egresa',
                    'lista_esperas.tipo_ges_id as ges',
                    'lista_esperas.active as estado',
                    'lista_esperas.cie10_ant',
                    'lista_esperas.cie10s_id',
                    'lista_esperas.id_lep as id_sigte')
                ->selectRaw('DATE_FORMAT(lista_esperas.fechaingreso, "%d-%m-%Y") as f_entrada')
                ->selectRaw('DATE_FORMAT(lista_esperas.fechaegreso, "%d-%m-%Y") as f_salida')
                ->selectRaw('DATE_FORMAT(lista_esperas.fecha_digitacion_egreso, "%d-%m-%Y") as fecha_digitacion_egreso')
                ->selectRaw('DATE_FORMAT(lista_esperas.created_at, "%d-%m-%Y") as fecha_modificacion')
                ->whereIn('lista_esperas.active', [1, 0]);
            
            //determina orden en el caso de que se ingrese alguno de los siguientes parámetros de búsqueda
            if ( $request->get('desde') != null || 
                 $request->get('hasta') != null || 
                 $request->get('digdesde') != null || 
                 $request->get('dighasta') != null || 
                 $request->get('saldesde') != null || 
                 $request->get('salhasta') != null || 
                 $request->get('digegdesde') != null || 
                 $request->get('digeghasta') != null || 
                 $request->get('idPaciente') != null || 
                 $request->get('establecimiento') != null || 
                 $request->get('estadestino') != null || 
                 $request->get('estaresuelve') != null || 
                 $request->get('prestacion') != null ||
                 $request->get('especialidad') != null ) { 
                    $listaesperas = $listaesperas->orderBy('lista_esperas.id');
            }   
            
            // Fecha Ingreso
            if ($request->get('optfechaing') != null) {
                $opcion = $request->get('optfechaing');

                if ($opcion == 1) // IGUAL
                {
                    if ($request->get('desde') != null) {
                        $fechaini     = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 00:00:00");
                        $fechafin     = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '>=', $fechaini);
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '<=', $fechafin);}
                }
                if ($opcion == 2) // MAYOR QUE
                {
                    if ($request->get('desde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '>', $fecha);
                    }
                }
                if ($opcion == 3) // MENOR QUE
                {
                    if ($request->get('desde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '<', $fecha);
                    }
                }
                if ($opcion == 4) // ENTRE
                {
                    if ($request->get('desde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '>=', $fecha);
                    }
                    if ($request->get('hasta') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('hasta') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '<=', $fecha);
                    }
                }
            }

            // Fecha Digitación
            if ($request->get('optfechadig') != null) {
                $opcion = $request->get('optfechadig');

                if ($opcion == 1) // IGUAL
                {
                    if ($request->get('digdesde') != null) {
                        $fechaini = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 00:00:00");
                        $fechafin = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 23:59:59");

                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '>=', $fechaini);
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '<=', $fechafin);
                    }
                }
                if ($opcion == 2) // MAYOR QUE
                {
                    if ($request->get('digdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '>', $fecha);
                    }
                }
                if ($opcion == 3) // MENOR QUE
                {
                    if ($request->get('digdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '<', $fecha);
                    }
                }
                if ($opcion == 4) // ENTRE
                {
                    if ($request->get('digdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '>=', $fecha);
                    }
                    if ($request->get('dighasta') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('dighasta') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '<=', $fecha);
                    }
                }
            }

            // Fecha SALIDA
            if ($request->get('optfechasal') != null) {
                $opcion = $request->get('optfechasal');

                if ($opcion == 1) // IGUAL
                {
                    if ($request->get('saldesde') != null) {
                        $fechaini = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 00:00:00");
                        $fechafin = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 23:59:59");

                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '>=', $fechaini);
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '<=', $fechafin);

                    }
                }
                if ($opcion == 2) // MAYOR QUE
                {
                    if ($request->get('saldesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '>', $fecha);
                    }
                }
                if ($opcion == 3) // MENOR QUE
                {
                    if ($request->get('saldesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '<', $fecha);
                    }
                }
                if ($opcion == 4) // ENTRE
                {
                    if ($request->get('saldesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '>=', $fecha);
                    }
                    if ($request->get('salhasta') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('salhasta') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '<=', $fecha);
                    }
                }
            }

            // Fecha Digitación Egreso
            if ($request->get('optfechadigeg') != null) {
                $opcion = $request->get('optfechadigeg');

                if ($opcion == 1) // IGUAL
                {
                    if ($request->get('digegdesde') != null) {
                        $fechaini = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 00:00:00");
                        $fechafin = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 23:59:59");

                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '>=', $fechaini);
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '<=', $fechafin);
                    }
                }
                if ($opcion == 2) // MAYOR QUE
                {
                    if ($request->get('digegdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '>', $fecha);
                    }
                }
                if ($opcion == 3) // MENOR QUE
                {
                    if ($request->get('digegdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '<', $fecha);
                    }
                }
                if ($opcion == 4) // ENTRE
                {
                    if ($request->get('digegdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '>=', $fecha);
                    }
                    if ($request->get('digeghasta') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digeghasta') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '<=', $fecha);
                    }
                }
            }

            // filtro Paciente
            if ($request->get('idPaciente') != null) {
                $listaesperas = $listaesperas->where('pacientes_id', $request->get('idPaciente'));
            }

            // Establecimiento de origen
            if ($request->get('establecimiento') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.establecimientos_id_origen', $request->get('establecimiento'));
            }

            // Establecimiento de Destino
            if ($request->get('estadestino') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.establecimientos_id_destino', $request->get('estadestino'));
            }

            // Establecimiento que resuelve
            if ($request->get('estaresuelve') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.establecimientos_id_resuelve', $request->get('estaresuelve'));
            }

            // Filtro Comuna
            if ($request->get('comuna') != null) {
                $listaesperas = $listaesperas->where('comuna.id', $request->get('comuna'));
            }

            // Tipo Prestación
            if ($request->get('prestacion') != null) {
                $listaesperas = $listaesperas->where('prestacion.id', $request->get('prestacion'));
            }

            // filtro Especialidad
            if ($request->get('especialidad') != null) {
                $listaesperas = $listaesperas->where('especialidads_ingreso_id', $request->get('especialidad'));
            }

            // Filtro de estado
            if ($request->get('estado') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.active', $request->get('estado'));
            }

            $listaesperas = $listaesperas->paginate(10)
            // Fecha de Ingreso de la LEP
                ->appends('optfechaing', $request->get('optfechaing'))
                ->appends('desde', $request->get('desde'))
                ->appends('hasta', $request->get('hasta'))
            // Fecha de Digitación de la LEP
                ->appends('optfechadig', $request->get('optfechadig'))
                ->appends('digdesde', $request->get('digdesde'))
                ->appends('dighasta', $request->get('dighasta'))
            // Fecha de Salida de la LEP
                ->appends('optfechasal', $request->get('optfechasal'))
                ->appends('saldesde', $request->get('saldesde'))
                ->appends('salhasta', $request->get('salhasta'))
            // Fecha de Digitación Egreso
                ->appends('optfechadigeg', $request->get('optfechadigeg'))
                ->appends('digegdesde', $request->get('digegdesde'))
                ->appends('digeghasta', $request->get('digeghasta'))

                ->appends('idPaciente', $request->get('idPaciente'))
                ->appends('establecimiento', $request->get('establecimiento'))
                ->appends('estadestino', $request->get('estadestino'))
                ->appends('estaresuelve', $request->get('estaresuelve'))
                ->appends('comuna', $request->get('comuna'))
                ->appends('prestacion', $request->get('prestacion'))
                ->appends('especialidad', $request->get('especialidad'))
                ->appends('estado', $request->get('estado'));

            //parametros de consulta

            $optfechaing = $request->get('optfechaing');
            $desde       = $request->get('desde');
            $hasta       = $request->get('hasta');
            $optfechadig = $request->get('optfechadig');
            $digdesde    = $request->get('digdesde');
            $dighasta    = $request->get('dighasta');
            $optfechasal = $request->get('optfechasal');
            $saldesde    = $request->get('saldesde');
            $salhasta    = $request->get('salhasta');

            $optfechadigeg = $request->get('optfechadigeg');
            $digegdesde    = $request->get('digegdesde');
            $digeghasta    = $request->get('digeghasta');

            $idPaciente      = $request->get('idPaciente');
            $establecimiento = $request->get('establecimiento');
            $estadestino     = $request->get('estadestino');
            $estaresuelve    = $request->get('estaresuelve');
            $comuna          = $request->get('comuna');
            $prestacion      = $request->get('prestacion');
            $especialidad    = $request->get('especialidad');
            $estado          = $request->get('estado');

            return view('listaEsperas.resultadornle', compact('listaesperas', 'optfechaing', 'desde', 'hasta', 'optfechadig', 'digdesde', 'dighasta', 'optfechasal', 'saldesde', 'salhasta', 'optfechadigeg', 'digegdesde', 'digeghasta', 'idPaciente', 'establecimiento', 'estadestino','estaresuelve', 'comuna', 'prestacion', 'especialidad', 'estado'));
        } 
        else {
            return view('auth/login');
        }
    }

    /**
     * Resultado reporte Consulta Red según filtros definidos por el usuario
     * Vista: listaEsperas.resultado
     * Rol: None
     *
     * @param  \Illuminate\Http\Request  $request
     * @return list lista de ListaEsperass que coinciden con la busqueda
     */
    public function resultado(Request $request)
    {
        if (Auth::check()) {
            $listaesperas = DB::table('lista_esperas')
                ->join('pacientes', 'pacientes.id', '=', 'lista_esperas.pacientes_id')
                ->join('establecimientos as estorigen', 'estorigen.id', '=', 'lista_esperas.establecimientos_id_origen')
                ->join('establecimientos as estdestino', 'estdestino.id', '=', 'lista_esperas.establecimientos_id_destino')
                ->leftjoin('establecimientos as estotorga', 'estotorga.id', '=', 'lista_esperas.establecimientos_id_resuelve')
                ->join('comunas as comuna', 'comuna.id', '=', 'pacientes.comuna_id')
                ->join('especialidads as especialidad', 'especialidad.id', '=', 'lista_esperas.especialidads_ingreso_id')
                ->join('cie10s as cie10', 'cie10.id', '=', 'lista_esperas.cie10s_id')
                ->join('tipo_prestacions as prestacion', 'prestacion.id', '=', 'lista_esperas.tipo_prestacions_id')
                ->join('previsions as prevision', 'prevision.id', '=', 'pacientes.prevision_id')
                ->select('lista_esperas.id as id',
                    'estorigen.servicio_id as  serv_salud',
                    'pacientes.tipoDoc as tipo_doc',
                    'pacientes.rut as run',
                    'pacientes.dv as dv',
                    'pacientes.numDoc as documento',
                    'pacientes.nombre as nombres',
                    'pacientes.apPaterno as primer_apellido',
                    'pacientes.apMaterno as segundo_apellido',
                    'pacientes.fechaNacimiento as fecha_nac',
                    'pacientes.genero_id as sexo',
                    'prevision.name as prevision',
                    'prestacion.name as tipo_prest',
                    'lista_esperas.prestamin_ing as presta_min',
                    'especialidad.name as presta_est',
                    'estorigen.name as estab_orig',
                    'estdestino.name as estab_dest',
                    'lista_esperas.causal_egresos_id as c_salida',
                    'lista_esperas.establecimientos_id_resuelve as e_otor_at',
                    'lista_esperas.prestamin_egr as presta_min_salida',
                    'lista_esperas.prestamin_egr as presta_min_salida',
                    'pacientes.prais as prais',
                    'comuna.codigo as comuna',
                    'cie10.name as sospecha_diag',
                    'lista_esperas.precdiag as confir_diag',
                    'pacientes.direccion as nom_calle',
                    'pacientes.telefono as fono_fijo',
                    'pacientes.telefono2 as fono_movil',
                    'pacientes.email as email',
                    'lista_esperas.fechacitacion as f_citacion',
                    'lista_esperas.run_medico_solicita as run_prof_sol',
                    'lista_esperas.dv_medico_solicita as dv_prof_sol',
                    'lista_esperas.users_id_ingreso as digitador',
                    'lista_esperas.users_id_egreso as egresa',
                    'lista_esperas.tipo_ges_id as ges',
                    'lista_esperas.cie10_ant',
                    'lista_esperas.cie10s_id',
                    'lista_esperas.active as estado',
                    'lista_esperas.id_lep as id_sigte')
                ->selectRaw('DATE_FORMAT(lista_esperas.fechaingreso, "%d-%m-%Y") as f_entrada')
                ->selectRaw('DATE_FORMAT(lista_esperas.fechaegreso, "%d-%m-%Y") as f_salida')
                ->selectRaw('DATE_FORMAT(lista_esperas.updated_at, "%d-%m-%Y") as fecha_modificacion')
                ->whereIn('lista_esperas.active', [1, 0]);
                
            //determina orden en el caso de que se ingrese alguno de los siguientes parámetros de búsqueda
            if ( $request->get('desde') != null || 
                 $request->get('hasta') != null || 
                 $request->get('digdesde') != null || 
                 $request->get('dighasta') != null || 
                 $request->get('saldesde') != null || 
                 $request->get('salhasta') != null || 
                 $request->get('digegdesde') != null || 
                 $request->get('digeghasta') != null || 
                 $request->get('idPaciente') != null || 
                 $request->get('establecimiento') != null || 
                 $request->get('estadestino') != null || 
                 $request->get('estaresuelve') != null || 
                 $request->get('prestacion') != null ||
                 $request->get('especialidad') != null ) { 
                    $listaesperas = $listaesperas->orderBy('lista_esperas.id');
            }   

            // Fecha Ingreso
            if ($request->get('optfechaing') != null) {
                $opcion = $request->get('optfechaing');

                if ($opcion == 1) // IGUAL
                {
                    if ($request->get('desde') != null) {
                        $fechaini     = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 00:00:00");
                        $fechafin     = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '>=', $fechaini);
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '<=', $fechafin);}
                }
                if ($opcion == 2) // MAYOR QUE
                {
                    if ($request->get('desde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '>', $fecha);
                    }
                }
                if ($opcion == 3) // MENOR QUE
                {
                    if ($request->get('desde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '<', $fecha);
                    }
                }
                if ($opcion == 4) // ENTRE
                {
                    if ($request->get('desde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '>=', $fecha);
                    }
                    if ($request->get('hasta') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('hasta') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '<=', $fecha);
                    }
                }
            }

            // Fecha Digitación

            if ($request->get('optfechadig') != null) {
                $opcion = $request->get('optfechadig');

                if ($opcion == 1) // IGUAL
                {
                    if ($request->get('digdesde') != null) {
                        $fechaini = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 00:00:00");
                        $fechafin = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 23:59:59");

                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '>=', $fechaini);
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '<=', $fechafin);

                    }
                }
                if ($opcion == 2) // MAYOR QUE
                {
                    if ($request->get('digdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '>', $fecha);
                    }
                }
                if ($opcion == 3) // MENOR QUE
                {
                    if ($request->get('digdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '<', $fecha);
                    }
                }
                if ($opcion == 4) // ENTRE
                {
                    if ($request->get('digdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '>=', $fecha);
                    }
                    if ($request->get('dighasta') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('dighasta') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '<=', $fecha);
                    }
                }
            }

            // Fecha SALIDA
            if ($request->get('optfechasal') != null) {
                $opcion = $request->get('optfechasal');

                if ($opcion == 1) // IGUAL
                {
                    if ($request->get('saldesde') != null) {
                        $fechaini = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 00:00:00");
                        $fechafin = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 23:59:59");

                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '>=', $fechaini);
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '<=', $fechafin);

                    }
                }
                if ($opcion == 2) // MAYOR QUE
                {
                    if ($request->get('saldesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '>', $fecha);
                    }
                }
                if ($opcion == 3) // MENOR QUE
                {
                    if ($request->get('saldesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '<', $fecha);
                    }
                }
                if ($opcion == 4) // ENTRE
                {
                    if ($request->get('saldesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '>=', $fecha);
                    }
                    if ($request->get('salhasta') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('salhasta') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '<=', $fecha);
                    }
                }
            }

            // Fecha Digitación Egreso
            if ($request->get('optfechadigeg') != null) {
                $opcion = $request->get('optfechadigeg');

                if ($opcion == 1) // IGUAL
                {
                    if ($request->get('digegdesde') != null) {
                        $fechaini = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 00:00:00");
                        $fechafin = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 23:59:59");

                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '>=', $fechaini);
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '<=', $fechafin);
                    }
                }
                if ($opcion == 2) // MAYOR QUE
                {
                    if ($request->get('digegdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '>', $fecha);
                    }
                }
                if ($opcion == 3) // MENOR QUE
                {
                    if ($request->get('digegdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '<', $fecha);
                    }
                }
                if ($opcion == 4) // ENTRE
                {
                    if ($request->get('digegdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '>=', $fecha);
                    }
                    if ($request->get('digeghasta') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digeghasta') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '<=', $fecha);
                    }
                }
            }

            // filtro Paciente
            if ($request->get('idPaciente') != null) {
                $listaesperas = $listaesperas->where('pacientes_id', $request->get('idPaciente'));
            }

            // Establecimiento de origen
            if ($request->get('establecimiento') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.establecimientos_id_origen', $request->get('establecimiento'));
            }

            // Establecimiento de Destino
            if ($request->get('estadestino') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.establecimientos_id_destino', $request->get('estadestino'));
            }

            // Establecimiento que resuelve
            if ($request->get('estaresuelve') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.establecimientos_id_resuelve', $request->get('estaresuelve'));
            }

            // Filtro Comuna
            if ($request->get('comuna') != null) {
                $listaesperas = $listaesperas->where('comuna.id', $request->get('comuna'));
            }

            // Tipo Prestación
            if ($request->get('prestacion') != null) {
                $listaesperas = $listaesperas->where('prestacion.id', $request->get('prestacion'));
            }

            // filtro Especialidad
            if ($request->get('especialidad') != null) {
                $listaesperas = $listaesperas->where('especialidads_ingreso_id', $request->get('especialidad'));
            }

            // Filtro de estado
            if ($request->get('estado') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.active', $request->get('estado'));
            }

            $listaesperas = $listaesperas->paginate(10)
            // Fecha de Ingreso de la LEP
                ->appends('optfechaing', $request->get('optfechaing'))
                ->appends('desde', $request->get('desde'))
                ->appends('hasta', $request->get('hasta'))
            // Fecha de Digitación de la LEP
                ->appends('optfechadig', $request->get('optfechadig'))
                ->appends('digdesde', $request->get('digdesde'))
                ->appends('dighasta', $request->get('dighasta'))
            // Fecha de Salida de la LEP
                ->appends('optfechasal', $request->get('optfechasal'))
                ->appends('saldesde', $request->get('saldesde'))
                ->appends('salhasta', $request->get('salhasta'))
            // Fecha de Digitación Egreso
                ->appends('optfechadigeg', $request->get('optfechadigeg'))
                ->appends('digegdesde', $request->get('digegdesde'))
                ->appends('digeghasta', $request->get('digeghasta'))

                ->appends('idPaciente', $request->get('idPaciente'))
                ->appends('establecimiento', $request->get('establecimiento'))
                ->appends('estadestino', $request->get('estadestino'))
                ->appends('estaresuelve', $request->get('estaresuelve'))
                ->appends('comuna', $request->get('comuna'))
                ->appends('prestacion', $request->get('prestacion'))
                ->appends('especialidad', $request->get('especialidad'))
                ->appends('estado', $request->get('estado'));

            //parametros de consulta
            $optfechaing = $request->get('optfechaing');
            $desde       = $request->get('desde');
            $hasta       = $request->get('hasta');
            // Fecha de Digitación de la LEP
            $optfechadig = $request->get('optfechadig');
            $digdesde    = $request->get('digdesde');
            $dighasta    = $request->get('dighasta');
            // Fecha de Salida de la LEP
            $optfechasal = $request->get('optfechasal');
            $saldesde    = $request->get('saldesde');
            $salhasta    = $request->get('salhasta');
            // Fecha de Digitación Egreso
            $optfechadigeg = $request->get('optfechadigeg');
            $digegdesde    = $request->get('digegdesde');
            $digeghasta    = $request->get('digeghasta');

            $idPaciente      = $request->get('idPaciente');
            $establecimiento = $request->get('establecimiento');
            $estadestino     = $request->get('estadestino');
            $estaresuelve    = $request->get('estaresuelve');
            $comuna          = $request->get('comuna');
            $prestacion      = $request->get('prestacion');
            $especialidad    = $request->get('especialidad');
            $estado          = $request->get('estado');

            return view('listaEsperas.resultado', compact('listaesperas', 'optfechaing', 'desde', 'hasta', 'optfechadig', 'digdesde', 'dighasta', 'optfechasal', 'saldesde', 'salhasta', 'optfechadigeg', 'digegdesde', 'digeghasta', 'idPaciente', 'establecimiento', 'estadestino','estaresuelve', 'comuna', 'prestacion', 'especialidad', 'estado'));
        } 
        else {
            return view('auth/login');
        }
    }

    /**
     * Exporta a Archivo Excel Reporte RNLE
     * Rol: None
     *
     * @param  \Illuminate\Http\Request  $request
     * @return list lista de ListaEsperass que coinciden con la busqueda
     */
    public function excelrnle(Request $request)
    {
        //TODO: Incluir ID_SIGTE
        if (Auth::check()) {
            ini_set("memory_limit", -1);
            ini_set('max_execution_time', 300);

            $listaesperas = DB::table('lista_esperas')
                ->join('pacientes', 'pacientes.id', '=', 'lista_esperas.pacientes_id')
                ->leftjoin('users as user_digita', 'user_digita.id', '=', 'lista_esperas.users_id_ingreso')
                ->leftjoin('users as user_egresa', 'user_egresa.id', '=', 'lista_esperas.users_id_egreso')
                ->leftjoin('establecimientos as estorigen', 'estorigen.id', '=', 'lista_esperas.establecimientos_id_origen')
                ->leftjoin('establecimientos as estdestino', 'estdestino.id', '=', 'lista_esperas.establecimientos_id_destino')
                ->leftjoin('establecimientos as estotorga', 'estotorga.id', '=', 'lista_esperas.establecimientos_id_resuelve')
                ->leftjoin('comunas as comuna', 'comuna.id', '=', 'pacientes.comuna_id')
                ->leftjoin('especialidads as especialidad', 'especialidad.id', '=', 'lista_esperas.especialidads_ingreso_id')
                ->leftjoin('cie10s as cie10', 'cie10.id', '=', 'lista_esperas.cie10s_id')
                ->leftjoin('tipo_prestacions as prestacion', 'prestacion.id', '=', 'lista_esperas.tipo_prestacions_id')
                ->select('lista_esperas.id as id',
                    'estorigen.servicio_id as  serv_salud',
                    'pacientes.tipoDoc as tipo_doc',
                    'pacientes.rut as run',
                    'pacientes.dv as dv',
                    'pacientes.nombre as nombres',
                    'pacientes.apPaterno as primer_apellido',
                    'pacientes.apMaterno as segundo_apellido',
                    'pacientes.fechaNacimiento as fecha_nac',
                    'pacientes.genero_id as sexo',
                    'pacientes.prevision_id as prevision',
                    'pacientes.prevision_id as prevision',
                    'lista_esperas.tipo_prestacions_id as tipo_prest',
                    'lista_esperas.prestamin_ing as presta_min',
                    'lista_esperas.planos_id as plano',
                    'lista_esperas.extremidads_id as extremidad',
                    'especialidad.name as presta_est',
                    'estorigen.code as estab_orig',
                    'estdestino.code as estab_dest',
                    'lista_esperas.causal_egresos_id as c_salida',
                    'estotorga.code as e_otor_at',
                    'lista_esperas.prestamin_egr as presta_min_salida',
                    'lista_esperas.prestamin_egr as presta_min_salida',
                    'pacientes.prais as prais',
                    'comuna.codigo as comuna',
                    'cie10.name as sospecha_diag',
                    'comuna.rural as rural',
                    'pacientes.via_id as via',
                    'pacientes.direccion as nom_calle',
                    'pacientes.numero as numero',
                    'pacientes.telefono as fono_fijo',
                    'pacientes.telefono2 as fono_movil',
                    'pacientes.email as email',
                    'lista_esperas.run_medico_solicita as run_prof_sol',
                    'lista_esperas.dv_medico_solicita as dv_prof_sol',
                    'user_digita.name as digitador',
                    'user_egresa.name as egresa',
                    'lista_esperas.tipo_ges_id as ges',
                    'lista_esperas.active as estado',
                    'lista_esperas.cie10_ant',
                    'lista_esperas.cie10s_id',
                    'lista_esperas.run_medico_resol as run_medico_resol',
                    'lista_esperas.dv_medico_resol as dv_medico_resol',
                    'lista_esperas.resultado as resultado',
                    'lista_esperas.id_lep as id_sigte')
                ->selectRaw('COALESCE(DATE_FORMAT(lista_esperas.fechacitacion, "%d-%m-%Y"),0) as f_citacion')
                ->selectRaw('DATE_FORMAT(lista_esperas.fechaingreso, "%d-%m-%Y") as f_entrada')
                ->selectRaw('COALESCE(DATE_FORMAT(lista_esperas.fechaegreso, "%d-%m-%Y"),0) as f_salida')
                ->selectRaw('DATE_FORMAT(lista_esperas.fecha_digitacion_egreso, "%d-%m-%Y") as fecha_digitacion_egreso')
                ->selectRaw('DATE_FORMAT(lista_esperas.created_at, "%d-%m-%Y") as fecha_modificacion')
                ->whereIn('lista_esperas.active', [1, 0]);
            
            //determina orden en el caso de que se ingrese alguno de los siguientes parámetros de búsqueda
            if ( $request->get('desde') != null || 
                 $request->get('hasta') != null || 
                 $request->get('digdesde') != null || 
                 $request->get('dighasta') != null || 
                 $request->get('saldesde') != null || 
                 $request->get('salhasta') != null || 
                 $request->get('digegdesde') != null || 
                 $request->get('digeghasta') != null || 
                 $request->get('idPaciente') != null || 
                 $request->get('establecimiento') != null || 
                 $request->get('estadestino') != null || 
                 $request->get('estaresuelve') != null || 
                 $request->get('prestacion') != null ||
                 $request->get('especialidad') != null ) { 
                    $listaesperas = $listaesperas->orderBy('lista_esperas.id');
            }
            
            // Fecha Ingreso
            if ($request->get('optfechaing') != null) {
                $opcion = $request->get('optfechaing');

                if ($opcion == 1) // IGUAL
                {
                    if ($request->get('desde') != null) {
                        $fechaini     = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 00:00:00");
                        $fechafin     = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '>=', $fechaini);
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '<=', $fechafin);
                    }
                }
                if ($opcion == 2) // MAYOR QUE
                {
                    if ($request->get('desde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '>', $fecha);
                    }
                }
                if ($opcion == 3) // MENOR QUE
                {
                    if ($request->get('desde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '<', $fecha);
                    }
                }
                if ($opcion == 4) // ENTRE
                {
                    if ($request->get('desde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '>=', $fecha);
                    }
                    if ($request->get('hasta') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('hasta') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '<=', $fecha);
                    }
                }
            }

            // Fecha Digitación

            if ($request->get('optfechadig') != null) {
                $opcion = $request->get('optfechadig');

                if ($opcion == 1) // IGUAL
                {
                    if ($request->get('digdesde') != null) {
                        $fechaini = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 00:00:00");
                        $fechafin = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 23:59:59");

                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '>=', $fechaini);
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '<=', $fechafin);

                    }
                }
                if ($opcion == 2) // MAYOR QUE
                {
                    if ($request->get('digdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '>', $fecha);
                    }
                }
                if ($opcion == 3) // MENOR QUE
                {
                    if ($request->get('digdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '<', $fecha);
                    }
                }
                if ($opcion == 4) // ENTRE
                {
                    if ($request->get('digdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '>=', $fecha);
                    }
                    if ($request->get('dighasta') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('dighasta') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.created_at', '<=', $fecha);
                    }
                }
            }

            // Fecha SALIDA
            if ($request->get('optfechasal') != null) {
                $opcion = $request->get('optfechasal');

                if ($opcion == 1) // IGUAL
                {
                    if ($request->get('saldesde') != null) {
                        $fechaini = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 00:00:00");
                        $fechafin = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 23:59:59");

                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '>=', $fechaini);
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '<=', $fechafin);

                    }
                }
                if ($opcion == 2) // MAYOR QUE
                {
                    if ($request->get('saldesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '>', $fecha);
                    }
                }
                if ($opcion == 3) // MENOR QUE
                {
                    if ($request->get('saldesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '<', $fecha);
                    }
                }
                if ($opcion == 4) // ENTRE
                {
                    if ($request->get('saldesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('saldesde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '>=', $fecha);
                    }
                    if ($request->get('salhasta') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('salhasta') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fechaegreso', '<=', $fecha);
                    }
                }
            }

            // Fecha Digitación Egreso
            if ($request->get('optfechadigeg') != null) {
                $opcion = $request->get('optfechadigeg');

                if ($opcion == 1) // IGUAL
                {
                    if ($request->get('digegdesde') != null) {
                        $fechaini = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 00:00:00");
                        $fechafin = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 23:59:59");

                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '>=', $fechaini);
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '<=', $fechafin);
                    }
                }
                if ($opcion == 2) // MAYOR QUE
                {
                    if ($request->get('digegdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '>', $fecha);
                    }
                }
                if ($opcion == 3) // MENOR QUE
                {
                    if ($request->get('digegdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '<', $fecha);
                    }
                }
                if ($opcion == 4) // ENTRE
                {
                    if ($request->get('digegdesde') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digegdesde') . " 00:00:00");
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '>=', $fecha);
                    }
                    if ($request->get('digeghasta') != null) {
                        $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digeghasta') . " 23:59:59");
                        $listaesperas = $listaesperas->where('lista_esperas.fecha_digitacion_egreso', '<=', $fecha);
                    }
                }
            }

            // filtro Paciente
            if ($request->get('idPaciente') != null) {
                $listaesperas = $listaesperas->where('pacientes_id', $request->get('idPaciente'));
            }

            // Establecimiento de origen
            if ($request->get('establecimiento') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.establecimientos_id_origen', $request->get('establecimiento'));
            }

            // Establecimiento de Destino
            if ($request->get('estadestino') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.establecimientos_id_destino', $request->get('estadestino'));
            }

            // Establecimiento resuelve
            if ($request->get('estaresuelve') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.establecimientos_id_resuelve', $request->get('estaresuelve'));
            }

            // Filtro Comuna
            if ($request->get('comuna') != null) {
                $listaesperas = $listaesperas->where('comuna.id', $request->get('comuna'));
            }

            // Tipo Prestación
            if ($request->get('prestacion') != null) {
                $listaesperas = $listaesperas->where('prestacion.id', $request->get('prestacion'));
            }

            // filtro Especialidad
            if ($request->get('especialidad') != null) {
                $listaesperas = $listaesperas->where('especialidads_ingreso_id', $request->get('especialidad'));
            }

            // Filtro de estado
            if ($request->get('estado') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.active', $request->get('estado'));
            }

            $listaesperas = $listaesperas->paginate(10000);

            //Genera archivo Excel
            Excel::create('Reporte_RNLE', function ($excel) use ($listaesperas) {
                $excel->sheet('ListaEspera', function ($sheet) use ($listaesperas) {
                    //agrega nombre de columnas
                    $sheet->appendRow(array(
                        'SERV_SALUD', 
                        'RUN', 
                        'DV', 
                        'NOMBRES', 
                        'PRIMER_APELLIDO', 
                        'SEGUNDO_APELLIDO', 
                        'FECHA_NAC', 
                        'SEXO', 
                        'PREVISION',
                        'TIPO_PREST', 
                        'PRESTA_MIN', 
                        'PLANO', 
                        'EXTREMIDAD', 
                        'PRESTA_EST', 
                        'F_ENTRADA', 
                        'ESTAB_ORIG', 
                        'ESTAB_DEST', 
                        'F_SALIDA',
                        'C_SALIDA', 
                        'E_OTOR_AT', 
                        'PRESTA_MIN_SALIDA', 
                        'PRAIS', 
                        'REGION',
                        'COMUNA', 
                        'SOSPECHA_DIAG',
                        'CONFIR_DIAG', 
                        'CIUDAD', 
                        'COND_RURALIDAD', 
                        'VIA_DIRECCION', 
                        'NOM_CALLE', 
                        'NUM_DIRECCION', 
                        'RESTO_DIRECCION',
                        'FONO_FIJO', 
                        'FONO_MOVIL', 
                        'EMAIL',
                        'F_CITACION',
                        'RUN_PROF_SOL', 
                        'DV_PROF_SOL',
                        'RUN_PROF_RESOL',
                        'DV_PROF_RESOL', 
                        'ID_LOCAL',                        
                        'RESULTADO',
                        'ID_SIGTE', 
                        'DIGITADOR', 
                        'EGRESA', 
                        'FECHA_MODIFICACION', 
                        'GES'
                    ));
                    //FIXME: Datos estáticos, Region y ciudad debe ser correspondiente al establecimiento de origen (Por consultar)
                    //Datos Fijos
                    $region          = (int) 13;
                    $ciudad          = 'Santiago';
                    $resto_direccion = '';

                    foreach ($listaesperas->chunk(10) as $chunks) {
                        foreach ($chunks as $listaespera) {
                            // Digito Verificador como número cuando corresponda
                            if (is_null($listaespera->dv)) {
                                $dv = 0;
                            } 
                            else {
                                if (is_numeric($listaespera->dv)) {
                                    $dv = $listaespera->dv;
                                } 
                                else {
                                    $dv = $listaespera->dv;
                                }
                            }
                            // Nombres - apellidos en mayuscula
                            $nombres          = strtoupper($listaespera->nombres);
                            $primer_apellido  = strtoupper($listaespera->primer_apellido);
                            // Valida si existe segundo apellido, si no existe debe insertar "NO INFORMADO" en el informe
                            $segundo_apellido = strtoupper($listaespera->segundo_apellido);
                            $aux_var = str_replace(' ', '', $segundo_apellido); //Elimina espacios.
                            if ($aux_var == '' || is_null($segundo_apellido))
                            {
                                $segundo_apellido = "NO INFORMADO"; // Valor para segundo apellido sin registrar
                            }
                            //fecha de nacimiento
                            $fecha_nac = date("d-m-Y", strtotime($listaespera->fecha_nac));
                            //Sexo como número 1:Masculino ; 2:Femenino
                            $sexo = (int) $listaespera->sexo;
                            //previsión como número
                            $prevision = (int) $listaespera->prevision;
                            //Tipo Prestación
                            $tipo_prest = (int) $listaespera->tipo_prest;
                            //Fecha entrada formato DD-MM-YYYY
                            $f_entrada = date("d-m-Y", strtotime($listaespera->f_entrada));
                            // Prais debe ser 1 (si) o 2 (no)
                            if ($listaespera->prais != 1) {
                                $prais = 2;
                            } 
                            else {
                                $prais = $listaespera->prais;
                            }
                            // comuna debe ser número
                            $comuna = (int) $listaespera->comuna;

                            // Fecha Salida DD-MM-YYYY
                            if ($listaespera->f_salida == 0) {
                                $f_salida = "";
                            } 
                            else {
                                $f_salida = date("d-m-Y", strtotime($listaespera->f_salida));
                            }

                            // Rural número
                            $rural = (int) $listaespera->rural;
                            // número dirección
                            $numero = (int) $listaespera->numero;
                            //fono número
                            $fono_fijo = (int) $listaespera->fono_fijo;
                            //fono alternativo
                            $fono_movil = $listaespera->fono_movil;
                            // Fecha Citación
                            if ($listaespera->f_citacion == 0) {
                                $f_citacion = "";
                            } 
                            else {
                                $f_citacion = date("d-m-Y", strtotime($listaespera->f_citacion));
                            }

                            // Run Profesional
                            $run_prof_sol = (int) $listaespera->run_prof_sol;
                            // DV profesional
                            if (is_numeric($listaespera->dv_prof_sol)) {
                                $dv_prof_sol = (int) $listaespera->dv_prof_sol;
                            } 
                            else {
                                $dv_prof_sol = $listaespera->dv_prof_sol;
                            }

                            // ID LOCAL
                            $id_local = $listaespera->id;

                            // Fecha modificación DD-MM-YYYY -> corresponde sólo para el reporte a la Fecha de Creación
                            if ($listaespera->estado == 1) {
                                $fecha_modificacion = date("d-m-Y", strtotime($listaespera->fecha_modificacion));
                            } 
                            else {
                                $fecha_modificacion = date("d-m-Y", strtotime($listaespera->fecha_digitacion_egreso));
                            }

                            // Confirmación Diagnostica = precdiag, en Reporte RNLE debe ir vacío
                            $confir_diag = '';

                            // Via como número
                            if (is_null($listaespera->via)) {
                                $via = '';
                            } 
                            else {
                                $via = (int) $listaespera->via;
                            }

                            // Cie10 = 0 debe traer el texto de cie10 de versión anterior
                            if ($listaespera->cie10s_id == 0) {
                                $sospecha_diag = $listaespera->cie10_ant;
                            } 
                            else {
                                $sospecha_diag = $listaespera->sospecha_diag;
                            }

                            //Datos de Egreso
                            $run_prof_resol  = $listaespera->run_medico_resol;
                            $dv_prof_resol   = $listaespera->dv_medico_resol;
                            $resultado       = $listaespera->resultado;

                            //agrega datos en columna
                            $sheet->appendRow(array(
                                $listaespera->serv_salud,
                                $listaespera->run,
                                $dv,
                                $nombres,
                                $primer_apellido,
                                $segundo_apellido,
                                $fecha_nac,
                                $sexo,
                                $prevision,
                                $tipo_prest,
                                $listaespera->presta_min,
                                $listaespera->plano,
                                $listaespera->extremidad,
                                $listaespera->presta_est,
                                $f_entrada,
                                $listaespera->estab_orig,
                                $listaespera->estab_dest,
                                $f_salida,
                                $listaespera->c_salida,
                                $listaespera->e_otor_at,
                                $listaespera->presta_min_salida,                                
                                $prais,
                                $region,
                                $comuna,
                                $sospecha_diag,
                                $confir_diag,
                                $ciudad,
                                $rural,
                                $via,
                                $listaespera->nom_calle,
                                $numero,
                                $resto_direccion,
                                $fono_fijo,
                                $fono_movil,
                                $listaespera->email,
                                $f_citacion,
                                $run_prof_sol,
                                $dv_prof_sol,
                                $run_prof_resol,
                                $dv_prof_resol,
                                $id_local,
                                $resultado,
                                $listaespera->id_sigte,
                                $listaespera->digitador,
                                $listaespera->egresa,
                                $fecha_modificacion,
                                $listaespera->ges
                            ));
                        }
                    }
                });
            })->export('xls');
        } 
        else {
            return view('auth/login');
        }
    }

    /**
     * Exporta a Archivo Excel Reporte Consulta Red
     * Rol: None
     *
     * @param  \Illuminate\Http\Request  $request
     * @return list lista de ListaEsperas que coinciden con la busqueda
     */
    public function excel(Request $request)
    {

        if (Auth::check()) {

            $listaesperas = DB::table('lista_esperas')
                ->leftjoin('pacientes', 'pacientes.id', '=', 'lista_esperas.pacientes_id')
                ->leftjoin('generos as genero', 'genero.id', '=', 'pacientes.genero_id')
                ->leftjoin('users as user_digita', 'user_digita.id', '=', 'lista_esperas.users_id_ingreso')
                ->leftjoin('users as user_egresa', 'user_egresa.id', '=', 'lista_esperas.users_id_egreso')
                ->leftjoin('previsions as prevision', 'prevision.id', '=', 'pacientes.prevision_id')
                ->leftjoin('establecimientos as estorigen', 'estorigen.id', '=', 'lista_esperas.establecimientos_id_origen')
                ->leftjoin('establecimientos as estdestino', 'estdestino.id', '=', 'lista_esperas.establecimientos_id_destino')
                ->leftjoin('establecimientos as estresuelve', 'estresuelve.id', '=', 'lista_esperas.establecimientos_id_resuelve')
                ->leftjoin('comunas as comuna', 'comuna.id', '=', 'pacientes.comuna_id')
                ->leftjoin('vias as via', 'via.id', '=', 'pacientes.via_id')
                ->leftjoin('especialidads as especialidad', 'especialidad.id', '=', 'lista_esperas.especialidads_ingreso_id')
                ->leftjoin('cie10s as cie10', 'cie10.id', '=', 'lista_esperas.cie10s_id')
                ->leftjoin('tipo_prestacions as prestacion', 'prestacion.id', '=', 'lista_esperas.tipo_prestacions_id')
                ->leftjoin('causal_egresos as causal', 'causal.id', '=', 'lista_esperas.causal_egresos_id')
                ->leftjoin('extremidads as extremidad', 'extremidad.id', '=', 'lista_esperas.extremidads_id')
                ->select('lista_esperas.id as id',
                    'estorigen.servicio_id as  serv_salud',
                    'pacientes.tipoDoc as tipo_doc',
                    'pacientes.rut as run',
                    'pacientes.dv as dv',
                    'pacientes.numDoc as documento',
                    'pacientes.nombre as nombres',
                    'pacientes.apPaterno as primer_apellido',
                    'pacientes.apMaterno as segundo_apellido',
                    'pacientes.fechaNacimiento as fecha_nac',
                    'genero.name as sexo',
                    'prevision.name as prevision',
                    'prestacion.name as tipo_prest',
                    'lista_esperas.prestamin_ing as presta_min',
                    'lista_esperas.planos_id as plano',
                    'extremidad.name as extremidad',
                    'especialidad.name as presta_est',
                    'lista_esperas.fechaingreso as f_entrada',
                    'estorigen.name as estab_orig',
                    'estdestino.name as estab_dest',
                    'lista_esperas.fechaegreso as f_salida',
                    'causal.name as c_salida',
                    'estresuelve.name as e_otor_at',
                    'lista_esperas.prestamin_egr as presta_min_salida',
                    'pacientes.prais as prais',
                    'comuna.name as comuna',
                    'cie10.name as sospecha_diag',
                    'lista_esperas.precdiag as confir_diag',
                    'via.name as via',
                    'pacientes.direccion as nom_calle',
                    'pacientes.numero as numero',
                    'pacientes.telefono as fono_fijo',
                    'pacientes.telefono2 as fono_movil',
                    'pacientes.email as email',
                    'lista_esperas.fechacitacion as f_citacion',
                    'lista_esperas.run_medico_solicita as run_prof_sol',
                    'lista_esperas.dv_medico_solicita as dv_prof_sol',
                    'user_digita.name as digitador',
                    'user_egresa.name as egresa',
                    'lista_esperas.updated_at as fecha_modificacion',
                    'lista_esperas.tipo_ges_id as ges',
                    'lista_esperas.cie10_ant',
                    'lista_esperas.cie10s_id',
                    'lista_esperas.id_lep as id_sigte')
                ->whereIn('lista_esperas.active', [1, 0]);
             
            //determina orden en el caso de que se ingrese alguno de los siguientes parámetros de búsqueda
            if ( $request->get('desde') != null || 
                 $request->get('hasta') != null || 
                 $request->get('digdesde') != null || 
                 $request->get('dighasta') != null || 
                 $request->get('saldesde') != null || 
                 $request->get('salhasta') != null || 
                 $request->get('digegdesde') != null || 
                 $request->get('digeghasta') != null || 
                 $request->get('idPaciente') != null || 
                 $request->get('establecimiento') != null || 
                 $request->get('estadestino') != null || 
                 $request->get('estaresuelve') != null || 
                 $request->get('prestacion') != null ||
                 $request->get('especialidad') != null ) { 
                    $listaesperas = $listaesperas->orderBy('lista_esperas.id');
            }
            
            // Fecha Ingreso
            if ($request->get('desde') != null) {
                //formatea fechas
                $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde') . " 00:00:00");
                $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '>=', $fecha);
            }
            if ($request->get('hasta') != null) {
                //formatea fechas
                $fecha        = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('hasta') . " 23:59:59");
                $listaesperas = $listaesperas->where('lista_esperas.fechaingreso', '<=', $fecha);
            }

            // Fecha Digitación
            if ($request->get('digdesde') != null) {
                $fechadig     = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('digdesde') . " 00:00:00");
                $listaesperas = $listaesperas->where('lista_esperas.created_at', '>=', $fechadig);
            }
            if ($request->get('dighasta') != null) {
                $fechadig     = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('dighasta') . " 23:59:59");
                $listaesperas = $listaesperas->where('lista_esperas.created_at', '<=', $fechadig);
            }

            // filtro Paciente
            if ($request->get('idPaciente') != null) {
                $listaesperas = $listaesperas->where('pacientes_id', $request->get('idPaciente'));
            }

            // Establecimiento de origen
            if ($request->get('establecimiento') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.establecimientos_id_origen', $request->get('establecimiento'));
            }

            // Establecimiento de Destino
            if ($request->get('estadestino') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.establecimientos_id_destino', $request->get('estadestino'));
            }

            // Establecimiento resuelve
            if ($request->get('estaresuelve') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.establecimientos_id_resuelve', $request->get('estaresuelve'));
            }

            // Filtro Comuna
            if ($request->get('comuna') != null) {
                $listaesperas = $listaesperas->where('comuna.id', $request->get('comuna'));
            }

            // Tipo Prestación
            if ($request->get('prestacion') != null) {
                $listaesperas = $listaesperas->where('prestacion.id', $request->get('prestacion'));
            }

            // filtro Especialidad
            if ($request->get('especialidad') != null) {
                $listaesperas = $listaesperas->where('especialidads_ingreso_id', $request->get('especialidad'));
            }

            // Filtro de estado
            if ($request->get('estado') != null) {
                $listaesperas = $listaesperas->where('lista_esperas.active', $request->get('estado'));
            }
            $listaesperas = $listaesperas->paginate(1000);
            //crea array con información de consulta
            $listaesperasArray[] = ['ID', 'SERV_SALUD', 'TIPO_DOC', 'RUN', 'DV', 'DOCUMENTO', 'NOMBRES', 'PRIMER_APELLIDO', 'SEGUNDO_APELLIDO', 'FECHA_NAC', 'SEXO', 'PREVISION', 'TIPO_PREST', 'PRESTA_MIN', 'PLANO', 'EXTREMIDAD', 'PRESTA_EST', 'F_ENTRADA', 'ESTAB_ORIG', 'ESTAB_DEST', 'F_SALIDA', 'C_SALIDA', 'E_OTOR_AT', 'PRESTA_MIN_SALIDA', 'PRAIS', 'REGION', 'COMUNA', 'SOSPECHA_DIAG', 'CONFIR_DIAG', 'CIUDAD', 'COND_RURALIDAD', 'VIA_DIRECCION', 'NOM_CALLE', 'NUM_DIRECCION', 'RESTO_DIRECCION', 'FONO_FIJO', 'FONO_MOVIL', 'EMAIL', 'F_CITACION', 'RUN_PROF_SOL', 'DV_PROF_SOL', 'RUN_PROF_RESOL', 'DV_PROF_RESOL', 'ID_LOCAL', 'RESULTADO', 'ID_SIGTE','DIGITADOR', 'EGRESA', 'FECHA_MODIFICACION', 'GES'];

            foreach ($listaesperas as $listaespera) {
                //FIXME: Datos estáticos, Region y ciudad debe ser correspondiente al establecimiento de origen (Por consultar)
                $region          = 13;
                $ciudad          = 'Santiago';
                $cond_ruralidad  = 1;
                $num_direccion   = '';
                $resto_direccion = '';
                $run_prof_resol  = '';
                $dv_prof_resol   = '';
                $id_local        = '';
                $resultado       = '';

                // Cie10 = 0 debe traer el texto de cie10 de versión anterior
                if ($listaespera->cie10s_id == 0) {
                    $sospecha_diag = $listaespera->cie10_ant;
                } 
                else {
                    $sospecha_diag = $listaespera->sospecha_diag;
                }

                $listaesperasArray[] = [$listaespera->id,
                    $listaespera->serv_salud,
                    $listaespera->tipo_doc,
                    $listaespera->run,
                    $listaespera->dv,
                    $listaespera->documento,
                    $listaespera->nombres,
                    $listaespera->primer_apellido,
                    $listaespera->segundo_apellido,
                    $listaespera->fecha_nac,
                    $listaespera->sexo,
                    $listaespera->prevision,
                    $listaespera->tipo_prest,
                    $listaespera->presta_min,
                    $listaespera->plano,
                    $listaespera->extremidad,
                    $listaespera->presta_est,
                    $listaespera->f_entrada,
                    $listaespera->estab_orig,
                    $listaespera->estab_dest,
                    $listaespera->f_salida,
                    $listaespera->c_salida,
                    $listaespera->e_otor_at,
                    $listaespera->presta_min_salida,
                    $listaespera->prais,
                    $region,
                    $listaespera->comuna,
                    $sospecha_diag,
                    $listaespera->confir_diag,
                    $ciudad,
                    $cond_ruralidad,
                    $listaespera->via,
                    $listaespera->nom_calle,
                    $listaespera->numero,
                    $resto_direccion,
                    $listaespera->fono_fijo,
                    $listaespera->fono_movil,
                    $listaespera->email,
                    $listaespera->f_citacion,
                    $listaespera->run_prof_sol,
                    $listaespera->dv_prof_sol,
                    $run_prof_resol,
                    $dv_prof_resol,
                    $id_local,
                    $resultado,
                    $listaespera->id_sigte,
                    $listaespera->digitador,
                    $listaespera->egresa,
                    $listaespera->fecha_modificacion,
                    $listaespera->ges];
            }

            //Genera archivo Excel
            Excel::create('Reporte', function ($excel) use ($listaesperasArray) {
                $excel->sheet('ListaEspera', function ($sheet) use ($listaesperasArray) {
                    $sheet->fromArray($listaesperasArray, null, 'A1', true, false);
                });
            })->export('xls');
        } 
        else {
            return view('auth/login');
        }
    }
    /*******************************************************************************************/
    /*                                    FIN  REPORTES                                        */
    /*******************************************************************************************/

    /*******************************************************************************************/
    /*                             VISUALIZACIÓN DE LISTA DE ESPERA                            */
    /*******************************************************************************************/
    /**
     * Muestra pantalla con Detalle de la Lista de Espera
     * Vista: listaEsperas.visualizar
     * Rol: None
     *
     * @param int $id ID de Lista de Espera
     * @return \Illuminate\Http\Response
     */
    public function visualizar($id)
    {
        if (Auth::check()) {
            $listaEspera = ListaEspera::find($id);

            //Datos del Paciente
            $paciente        = Paciente::find($listaEspera->pacientes_id);
            $fechaNacimiento = new DateTime($paciente->fechaNacimiento);
            $fechaNacimiento = $fechaNacimiento->format('d-m-Y');

            //calcula edad
            $date = date('Y-m-d'); //la fecha del computador
            $diff = abs(strtotime($date) - strtotime($paciente->fechaNacimiento));
            $edad = floor($diff / (365 * 60 * 60 * 24));

            $previsions = Prevision::find($paciente->prevision_id);
            $prevision  = $previsions->name;

            //Si prevision es Fonasa obtiene Tramo
            if ($paciente->prevision_id == 1) {
             $tramos = Tramo::find($paciente->tramo_id);
             $tramo  = $tramos->name;
            } 
            else {
             $tramo = '';
            }

            //Tipo Ges 1 = SI
            if ($listaEspera->tipo_ges_id == 1) {
             $tipoGes = 'Si';
            } 
            else {
             $tipoGes = 'No';
            }

            //Obtiene establecimiento de origen
            $estOrigen = Establecimiento::find($listaEspera->establecimientos_id_origen);
            //Obtiene establecimiento de destino
            $estDest   = Establecimiento::find($listaEspera->establecimientos_id_destino);

            $medico     = $listaEspera->run_medico_solicita . "-" . $listaEspera->dv_medico_solicita;
            $tipoEspera = TipoEspera::find($listaEspera->tipo_esperas_id);

            // Recupera Cie10, si es 0 obtiene el texto del Cie10 versión anterior LEP
            $cie10s = Cie10::find($listaEspera->cie10s_id);
            if ($listaEspera->cie10s_id == 0) {
             if ($listaEspera->cie10_ant === null) {
                 $cie10 = $cie10s->codigo . ' - ' . $cie10s->name;
             } 
             else {
                 $cie10 = $listaEspera->cie10_ant;
             }
            } 
            else {
             $cie10 = $cie10s->codigo . ' - ' . $cie10s->name;
            }

            $especialidad_ing = Especialidad::find($listaEspera->especialidads_ingreso_id);
            $prestacion       = TipoPrestacion::find($listaEspera->tipo_prestacions_id);

            $procedimientopm_id = '';
            $procedimiento_id   = '';

            if ($listaEspera->tipo_prestacions_id != 1) {
             if (is_null($listaEspera->tipo_procedimientos_id) == false) {
                 $procedimiento    = TipoProcedimiento::find($listaEspera->tipo_procedimientos_id);
                 $procedimiento_id = $procedimiento->name;
             }
             if (is_null($listaEspera->tipo_procedimientos_pms_id) == false) {
                 $procedimientopm    = TipoProcedimientosPm::find($listaEspera->tipo_procedimientos_pms_id);
                 $procedimientopm_id = $procedimientopm->name;
             }
            }

            if ($listaEspera->planos_id != null) {
             $planos = Plano::find($listaEspera->planos_id);
             $plano  = $planos->name;
            } 
            else {
             $plano = '';
            }

            if ($listaEspera->extremidads_id != null) {
             $extremidads = Extremidad::find($listaEspera->extremidads_id);
             $extremidad  = $extremidads->name;
            } 
            else {
             $extremidad = '';
            }

            //Campos de Egreso
            if ($listaEspera->establecimientos_id_resuelve != null) {
             $estResuelves = Establecimiento::find($listaEspera->establecimientos_id_resuelve);
             $estResuelve  = $estResuelves->name;
            } 
            else {
             $estResuelve = '';
            }

            if ($listaEspera->tipo_salidas_id != null) {
             $TipoSalidas = TipoSalida::find($listaEspera->tipo_salidas_id);
             $TipoSalida  = $TipoSalidas->name;
            } 
            else {
             $TipoSalida = '';
            }

            if ($listaEspera->causal_egresos_id !== null) {
             $Causal_Egresos = CausalEgreso::find($listaEspera->causal_egresos_id);
             $CausalEgresos  = $Causal_Egresos->name;
            } 
            else {
             $CausalEgresos = '';
            }

            //fecha entrada
            $fecha_entrada = new DateTime($listaEspera->fechaingreso);
            $fecha_entrada = $fecha_entrada->format('d-m-Y');
            //fecha citación
            if($listaEspera->fechacitacion != null){             
                $fecha_citacion = new DateTime($listaEspera->fechacitacion);
                $fecha_citacion = $fecha_citacion->format('d-m-Y');
            }
            else{
                $fecha_citacion = '';    
            }     
            //fecha egreso
            if($listaEspera->fechaegreso != null){             
                $fecha_egreso = new DateTime($listaEspera->fechaegreso);
                $fecha_egreso = $fecha_egreso->format('d-m-Y');
            }
            else{
                $fecha_egreso = '';    
            }

            return view('listaEsperas.visualizar', compact('listaEspera', 'paciente', 'fechaNacimiento', 'edad', 'estOrigen', 'estDest', 'prevision', 'tipoGes', 'tramo', 'medico', 'tipoEspera', 'cie10', 'especialidad_ing', 'prestacion', 'plano', 'extremidad', 'estResuelve', 'TipoSalida', 'CausalEgresos', 'procedimientopm_id', 'procedimiento_id','fecha_entrada','fecha_citacion','fecha_egreso'));

            } 
            else {
            return view('auth/login');
        }
    }

    /**
     * Muestra la documento en formato PDF de la Lista de Espera creada.
     * Vista: listaEsperas.pdf
     * Rol: None
     *
     * @param int $id ID de Lista de Espera
     * @return \Illuminate\Http\Response
     */
    public function pdf($id)
    {
        if (Auth::check()) {
            $listaEspera = ListaEspera::find($id);

            //Datos del Paciente
            $paciente        = Paciente::find($listaEspera->pacientes_id);
            $fechaNacimiento = new DateTime($paciente->fechaNacimiento);
            $fechaNacimiento = $fechaNacimiento->format('d-m-Y');

            //calcula edad
            $date = date('Y-m-d'); //la fecha del computador
            $diff = abs(strtotime($date) - strtotime($paciente->fechaNacimiento));
            $edad = floor($diff / (365 * 60 * 60 * 24));

            $previsions = Prevision::find($paciente->prevision_id);
            $prevision  = $previsions->name;

            if ($paciente->prevision_id == 1) {
                $tramos = Tramo::find($paciente->tramo_id);
                $tramo  = $tramos->name;
            } 
            else {
                $tramo = '';
            }

            if ($listaEspera->tipo_ges_id == 1) {
                $tipoGes = 'Si';
            } 
            else {
                $tipoGes = 'No';
            }

            $estOrigen = Establecimiento::find($listaEspera->establecimientos_id_origen);
            $estDest   = Establecimiento::find($listaEspera->establecimientos_id_destino);

            $medico = $listaEspera->run_medico_solicita . "-" . $listaEspera->dv_medico_solicita;

            if (is_null($listaEspera->tipo_esperas_id)) {
                $tipoEspera = '';
            } 
            else {
                $tipoEspera = TipoEspera::find($listaEspera->tipo_esperas_id);
            }

            // Recupera Cie10, si es 0 obtiene el texto del Cie10 versión anterior LEP
            $cie10s = Cie10::find($listaEspera->cie10s_id);
            if ($listaEspera->cie10s_id == 0) {
                if ($listaEspera->cie10_ant === null) {
                    $cie10 = $cie10s->codigo . ' - ' . $cie10s->name;
                } 
                else {
                    $cie10 = $listaEspera->cie10_ant;
                }
            } 
            else {
                $cie10 = $cie10s->codigo . ' - ' . $cie10s->name;
            }

            $especialidad_ing = Especialidad::find($listaEspera->especialidads_ingreso_id);
            $prestacions      = TipoPrestacion::find($listaEspera->tipo_prestacions_id);
            $prestacion       = $prestacions->name;

            //Rescata procedimiento y Procedimiento PM
            $procedimiento_pm = '';
            $procedimiento    = '';

            if ($listaEspera->tipo_prestacions_id != 1) {
                if (is_null($listaEspera->tipo_procedimientos_pms_id) == false) {
                    $procedimiento_pms = TipoProcedimientosPm::find($listaEspera->tipo_procedimientos_pms_id);
                    $procedimiento_pm  = $procedimiento_pms->name;
                }
                if (is_null($listaEspera->tipo_procedimientos_id) == false) {
                    $procedimientos = TipoProcedimiento::find($listaEspera->tipo_procedimientos_id);
                    $procedimiento  = $procedimientos->name;
                }
            }

            if (is_null($listaEspera->planos_id)) {
                $plano = '';
            } 
            else {
                $planos = Plano::find($listaEspera->planos_id);
                $plano  = $planos->name;
            }

            if (is_null($listaEspera->extremidads_id)) {
                $extremidad = '';
            } 
            else {
                $extremidads = Extremidad::find($listaEspera->extremidads_id);
                $extremidad  = $extremidads->name;
            }

            //Campos de Egreso

            if (is_null($listaEspera->establecimientos_id_resuelve)) {
                $estResuelve = '';
            } 
            else {
                $estResuelves = Establecimiento::find($listaEspera->establecimientos_id_resuelve);
                $estResuelve  = $estResuelves->name;
            }

            if (is_null($listaEspera->tipo_salidas_id)) {
                $TipoSalida = '';
            } 
            else {
                $TipoSalidas = TipoSalida::find($listaEspera->tipo_salidas_id);
                $TipoSalida  = $TipoSalidas->name;
            }

            if (is_null($listaEspera->causal_egresos_id)) {
                $CausalEgresos = '';
            } 
            else {
                $Causal_Egresos = CausalEgreso::find($listaEspera->causal_egresos_id);
                $CausalEgresos  = $Causal_Egresos->name;
            }

            if (is_null($listaEspera->fechaegreso)) {
                $fechaegreso   = '';
                $prestamin_egr = '';
            } 
            else {
                $fechaegreso = new DateTime($listaEspera->fechaegreso);
                $fechaegreso = $fechaegreso->format('d-m-Y');

                if (is_null($listaEspera->prestamin_egr)) {
                    $prestamin_egr = '';
                } 
                else {
                    $prestamin_egr = $listaEspera->prestamin_egr;
                }
            }

            //fecha entrada
            $fechaentrada = new DateTime($listaEspera->fechaingreso);
            $fechaentrada = $fechaentrada->format('d-m-Y');
            //fecha citación
            if($listaEspera->fechacitacion != null){             
                $fechacitacion = new DateTime($listaEspera->fechacitacion);
                $fechacitacion = $fechacitacion->format('d-m-Y');
            }
            else{
                $fechacitacion = '';    
            }     

            //genera PDF
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                ->loadView('listaEsperas.pdf',
                    compact('listaEspera', 'paciente', 'fechaNacimiento', 'edad', 'estOrigen', 'estDest', 'prevision', 'tipoGes', 'tramo', 'medico', 'tipoEspera', 'cie10', 'especialidad_ing', 'prestacion', 'plano', 'extremidad', 'estResuelve', 'TipoSalida', 'CausalEgresos', 'procedimiento_pm', 'procedimiento', 'fechaegreso', 'prestamin_egr','fechaentrada','fechacitacion'));
            return $pdf->stream();
        } 
        else {
            return view('auth/login');
        }
    }
    /*******************************************************************************************/
    /*                           FIN VISUALIZACIÓN DE LISTA DE ESPERA                          */
    /*******************************************************************************************/
    /*******************************************************************************************/
    /*                             CARGA DE ID_SIGTE                                           */
    /*******************************************************************************************/
    /**
	 * Funcion que llama a formulario de ingreso de ID_SIGTE
	 * Vista: listaEsperas.carga_sigte
	 * Rol: superUsuarioLE
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function ingresoSigte(Request $request)
	{
		if (Auth::check()) {
			return view('listaEsperas.carga_sigte');
		}
		else {
			return view('auth/login');
		}	
    }
    //TODO: Ingresar la carga del excel con ID_LOCAL e ID_SIGTE
    /**
	 * Funcion que Genera Accion de Cargar ID_SIGTE
	 * Vista: resultado_cm_sigte
	 * Rol: superUsuarioLE
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function uploadSigte(Request $request)
    {	
        if (Auth::check()) 
		{	
            try
			{			
				$user = User::find(Auth::user()->id);
				$estab_user = $user->establecimientos()->first()->id;	
				//verifica que archivo de Recepcionados Acepta existe
                if ($request->hasFile('archivo')) {
                    $upload = request()->file('archivo');

                    if(!$upload->isValid()) {
                        return redirect('listaesperas/ingresoSigte')
                        ->with('message','invalid')
                        ->withInput();
                    }

                    if($upload->getClientOriginalExtension() <> 'xls') {
                        return redirect('listaesperas/ingresoSigte')
                        ->with('message','extension')
                        ->withInput();
                    }
                }
                
			    // Se guardan los errores y se generan un contador
				$errores=[]; // nombre de los archivos con error
				$cont   = 0;
				$error_count = array(
				1 => 0, 
                2 => 0,	
                3 => 0			
				);
				$mensaje = array(
                1 => "Lista de espera no existe", 
                2 =>  "ID_SIGTE duplicado",
                3 => "El ID ingresado debe ser numérico"
				);



				//recorre documentos Recepcionados por ACEPTA
                if ($request->hasFile('archivo')) 
                {
                    $upload = request()->file('archivo');
                    
                    //carga archivo excel
                    $rows = \Excel::load($upload, function($reader) {})->get();

                    
                    foreach ($rows as $key => $value) {
                        /*Valida si archivo es correcto*/ //FIXME: Agregar errores en la vista listaEsperas/carga_sigte
                        if (is_null($value->id_sisle) || is_null($value->id_sigte)) {
                            return redirect('listaesperas/ingresoSigte')
                            ->with('message','archivo')
                            ->withInput();
                        }
                        /* Define valores */
                        $id_le      =   $value->id_sisle;
                        $id_sigte   =   $value->id_sigte;

                        /*Obtiene Lista de espera y guarda id_sigte*/
                        $listaEspera    =   ListaEspera::find($id_le);
                        if ($listaEspera != null) // Verifica si se encontro la lista de espera
                        {
                            $sigte_registrado   =  ListaEspera::where('id_lep',$id_sigte)->first();
                            if ($sigte_registrado == null)
                            {
                                if(is_numeric($id_sigte)) {
                                    $listaEspera->id_lep    =  $id_sigte;
                                    $listaEspera->save();
                                    $cont   =   $cont+1;
                                } else {
                                    $arra='ID SIGTE '.$id_sigte;
                                    // Registra error : ID_SIGTE debe ser numerico
                                    $texto=$arra."::".$mensaje[3]."::3";
                                    array_push($errores, $texto);
                                    $error_count[3]=$error_count[3]+1;
                                    continue;
                                }
                            }
                            else
                            {
                                $arra='ID SIGTE '.$id_sigte;
                                // Registra error : ID_SIGTE ya registrado
                                $texto=$arra."::".$mensaje[2]."::2";
                                array_push($errores, $texto);
                                $error_count[2]=$error_count[2]+1;
                                continue;
                            }           
                        }
                        else
                        {
                            $arra='ID SISLE '.$id_le;
                            // Registra error : Lista de espera no existe
                            $texto=$arra."::".$mensaje[1]."::1";
                            array_push($errores, $texto);
                            $error_count[1]=$error_count[1]+1;
                            continue;
                        }                                               
                    }
                }
				// Envia errores y cargas concretadas de los documentos enviados.
				Session::put('errores', $errores);                
				return view('listaEsperas.resultado_cm_sigte',compact('error_count','mensaje','cont','errores'));
									
			// Fin Try  
			}
			catch(Exception $e){
                return redirect('listaesperas/ingresoSigte')
                ->with('message','archivo')
                ->withInput();
			}

		// Fin if	
		}	
        else 
        {
		  return view('auth/login');
		}		
    }
    
    /**
	 * Funcion que genera Excel de la respuesta Carga Masiva de ID_SIGTE
	 * Rol: superUsuarioLE
	 *
     * @param  \Illuminate\Http\Request  $request	 
	 * @return lista de documentos incluidos en SIC
	 */
	public function excelRespuestaCargaSIGTE(Request $request) 
	{
		if (Auth::check()) 
		{
			if (Session::has('errores'))
			{
				ini_set("memory_limit", -1);
				ini_set('max_execution_time', 300);
				$errores= Session::get('errores');//Recupera un objeto 
				//Session::forget('respuestas'); //Elimina la variable en session	
				//crea array con información de consulta
				$documentosArray[] = ['id','error'];
					
				foreach($errores as $errore)
				{						
					$errorest = explode("::", $errore);
					$nombre = $errorest[0]; //nombre de los ID que no se adjuntaron
					$error=$errorest[1]; //error de adjuntar documento
					$documentosArray[] = [ $nombre,$error];
				}

				//Genera archivo Excel
				Excel::create('Resultado_CM_'.date("d-m-Y_H:i"), function($excel) use($documentosArray) {
					$excel->sheet('Documentos', function($sheet) use($documentosArray) {
						$sheet->fromArray($documentosArray,null,'A1',true,false);
			 		});
				})->export('xls');

			}
			else
			{
				return view('auth/login');
			}
        }
		else 
		{
			return view('auth/login');
		}
			        
	}
    /*******************************************************************************************/
    /*                             FIN CARGA DE ID_SIGTE                                       */
    /*******************************************************************************************/

    /*******************************************************************************************/
    /*                               FUNICIONES AUTOCOMPLETADO AJAX                            */
    /*******************************************************************************************/
    /**
     * Autocompleta con campo de diagnosticos CIE-10
     * Rol: None
     *
     * @param  \Illuminate\Http\Request  $request
     * @return lista de pacientes que coinciden con la busqueda
     */
    public function autoComplete(Request $request)
    {
        $query = $request->get('term', '');

        $cie10s = Cie10::where([[DB::raw('LOWER(codigo)'), 'LIKE', '%' . strtolower($query) . '%'], ['active', '1']])
            ->orWhere([[DB::raw('LOWER(name)'), 'LIKE', '%' . strtolower($query) . '%'], ['active', '1']])
            ->orderBy('name')
            ->paginate(20);

        $data = array();
        foreach ($cie10s as $cie10) {

            $data[] = array('value' => $cie10->codigo . "-" . $cie10->name,
                'id'                    => $cie10->id);
        }
        if (count($data)) {
            return $data;
        } 
        else {
            return ['value' => 'Diagnóstico no encontrado', 'id' => ''];
        }

    }

	/**
	 * Genera lista dinamica de Procedimientos
     * Rol: None
	 *
	 * @param  \Illuminate\Http\Request  $request
     * @param int $prestacion_id Tipo de Prestación (Tabla tipo_prestacions)
	 * @return \Illuminate\Http\Response
	 */
    public function autoCompleteTipoProcedimiento(Request $request, $prestacion_id)
    {
        if ($request->ajax()) {

            $tipoprocedimiento = TipoProcedimiento::where('tipo_prestacion_id', '=', $prestacion_id)->orderBy('name')->get();

            return response()->json($tipoprocedimiento);
        }
    }

	/**
	 * Genera lista dinamica de Procedimientos PM
     * Rol: None
	 *
	 * @param  \Illuminate\Http\Request  $request
     * @param int $tipo_procedimiento_id Tipo de Procedimiento (Tabla tipo_procedimientos)
	 * @return \Illuminate\Http\Response
	 */
    public function autoCompleteTipoProcedimientopm(Request $request, $tipo_procedimiento_id)
    {

        if ($request->ajax()) {

            $tipoprocedimientospm = TipoProcedimientosPm::where('tipo_procedimiento_id', '=', $tipo_procedimiento_id)->orderBy('name')->get();

            return response()->json($tipoprocedimientospm);
        }
    }

	/**
	 * Devuelve codigo prestamin de Procedimiento PMS
     * Rol: None
	 *
	 * @param  \Illuminate\Http\Request  $request
     * @param int $TipoProcedimientosPm_id Tipo de Procedimiento PMS (Tabla tipo_procedimientos_pms)
	 * @return \Illuminate\Http\Response
	 */
    public function autoCompletePrestamin(Request $request, $TipoProcedimientosPm_id)
    {

        if ($request->ajax()) {
            $TipoProcedimientosPm = TipoProcedimientosPm::where('id', '=', $TipoProcedimientosPm_id)->first();
            return response()->json($TipoProcedimientosPm);
        }
    }

    /**
     * Genera lista dinamica de Especialidades
     * Rol: None
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $especialidads_ingreso_id ID Especialidad (Tabla especialidads)
     * @return \Illuminate\Http\Response
     */
    public function autoCompleteRem(Request $request, $especialidads_ingreso_id)
    {

        if ($request->ajax()) {

            $especialidad = Especialidad::where('id', '=', $especialidads_ingreso_id)->first();
            return response()->json($especialidad);
        }
    }

    /**
     * Autocompleta con campo de pacientes con RUT
     * Rol: None
     *
     * @param  \Illuminate\Http\Request  $request
     * @return list lista de pacientes que coinciden con la busqueda
     */
    public function autoCompletePaciente(Request $request)
    {
        $query = $request->get('term', '');

        $pacientes = Paciente::where([['rut', 'LIKE', '%' . $query . '%'], ['active', '1']])
            ->orWhere([['numDoc', 'LIKE', '%' . $query . '%'], ['active', '1']])
            ->orderBy('nombre')->get();

        $data = array();

        foreach ($pacientes as $paciente) {
            //formato fecha de nacimiento
            $fechaNacimiento = new DateTime($paciente->fechaNacimiento);
            $fechaNacimiento = $fechaNacimiento->format('d-m-Y');
			
			//calcula edad
            $date = date('Y-m-d'); //la fecha del computador
            $diff = abs(strtotime($date) - strtotime($paciente->fechaNacimiento));
            $edad = floor($diff / (365 * 60 * 60 * 24));
			
            if ($paciente->prevision_id == 1 && is_null($paciente->tramo_id) == false ) {
                $tramos = Tramo::find($paciente->tramo_id);
                $tramo  = $tramos->name;
            } 
            else {
                $tramo = '';
            }
            if ($paciente->via_id == null) {
                $via = '';

            } 
            else {
                $vias = Via::find($paciente->via_id);
                $via  = $vias->name;
            }

            $previsions = Prevision::find($paciente->prevision_id);
            $prevision  = $previsions->name;

            $data[] = array('value' => $paciente->numDoc . $paciente->rut . $paciente->dv . " " . $paciente->nombre . " " . $paciente->apPaterno . " " . $paciente->apMaterno,
                'id'                    => $paciente->id,
                'nombre'                => $paciente->nombre,
                'apPaterno'             => $paciente->apPaterno,
                'apMaterno'             => $paciente->apMaterno,
                'via'                   => $via,
                'direccion'             => $paciente->direccion . " " . $paciente->numero,
                'telefono'              => $paciente->telefono,
                'telefono2'             => $paciente->telefono2,
                'email'                 => $paciente->email,
                'fechaNacimiento'       => $fechaNacimiento,
                'edad'                  => $edad,
                'prevision_id'          => $paciente->prevision_id,
                'prevision'             => $prevision,
                'tramo'                 => $tramo,
                'prais'                 => $paciente->prais,
                'funcionario'           => $paciente->funcionario);

        }
        if (count($data)) {
            return $data;
        } 
        else {
            return ['value' => 'Paciente no encontrado'];
        }

    }

    /**
     * Genera lista dinamica de Extremidades
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $TipoProcedimientosPm_id Tipo de Procedimiento PMS (Tabla tipo_procedimientos_pms)
     * @return \Illuminate\Http\Response
     */
    public function autoCompleteExtremidad(Request $request, $TipoProcedimientosPm_id)
    {

        if ($request->ajax()) {
            $Extremidades = DB::table('proced_pms_extremidad')
                ->join('extremidads', 'extremidads.id', '=', 'proced_pms_extremidad.extremidad_id')
                ->where('extremidads.active','=',1)
                ->where('proced_pms_extremidad.proced_pms_id','=',$TipoProcedimientosPm_id)
                ->select('extremidads.id as id','extremidads.name as name')
                ->orderBy('extremidads.name')->get();

            return response()->json($Extremidades);
        }
    }

    /**
     * Genera lista dinamica de Plano
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $TipoProcedimientosPm_id Tipo de Procedimiento PMS (Tabla tipo_procedimientos_pms)
     * @return \Illuminate\Http\Response
     */
    public function autoCompletePlano(Request $request, $TipoProcedimientosPm_id)
    {

        if ($request->ajax()) {
            $Planos = DB::table('proced_pms_plano')
                ->join('planos', 'planos.id', '=', 'proced_pms_plano.plano_id')
                ->where('planos.active','=',1)
                ->where('proced_pms_plano.proced_pms_id','=',$TipoProcedimientosPm_id)
                ->select('planos.id as id','planos.name as name')
                ->orderBy('planos.name')->get();

            return response()->json($Planos);
        }
    }
}
