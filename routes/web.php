<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/login_portal/{identificador}/{token}', 'UserPortalController@loginPortal')->name('login.portal');

//CAMBIA VISTA LOGIN COMO INICIO DE SITIO
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

//RUTAS DE USUARIO LARAVEL
Auth::routes();

//RUTA DE VISTA, UNA VEZ QUE SE ESTA LOGUEADO
 Route::get('/home', 'HomeController@index')->name('home.index');

//RUTAS ADMINISTRACION DE USUARIOS
Route::resource('users','UsersController');

//RUTAS ASIGNAR ROLES
Route::get('users/asignRole/{user}', 'UsersController@asignRole');
Route::post('users/saveRole', 'UsersController@saveRole');

//RUTAS ASIGNAR ESTABLECIMIENTOS
Route::get('users/asignEstab/{user}', 'UsersController@asignEstab');
Route::post('users/saveEstab', 'UsersController@saveEstab');

//RUTAS ASIGNAR ESPECIALIDAD
Route::get('users/asignEspec/{user}', 'UsersController@asignEspec');
Route::post('users/saveEspec', 'UsersController@saveEspec');

//RUTA GENERA EXCEL DE USUARIOS
Route::get('users/reporte/excel', 'UsersController@excel');	

//RUTA PARA EL CAMBIO DE PASSWORD
Route::get('users/password/cambiar', 'PasswordUsersController@password');
Route::post('users/password/cambiar', 'PasswordUsersController@save');

//RUTAS TIPO ESTABLECIMIENTO
Route::resource('tipoEstabs','TipoEstabsController');

//RUTAS COMUNAS
Route::resource('comunas','ComunasController');

//RUTA ESTABLECIMIENTOS
Route::resource('establecimientos','EstablecimientosController');

//RUTA ESPECIALIDAD
Route::resource('especialidads','EspecialidadsController');

//RUTA CIE10
Route::resource('cie10s','Cie10sController');

//RUTAS GENEROS
Route::resource('generos','GenerosController');

//RUTAS PREVISIÓN
Route::resource('previsions','PrevisionsController');

//RUTAS PROTOCOLOS
Route::resource('protocolos','ProtocolosController');

//RUTAS SERVICIOS
Route::resource('servicios','ServiciosController');

//RUTAS NIVELES
Route::resource('nivels','NivelsController');

//RUTAS TIPO GES
Route::resource('tipoGes','TipoGesController');

//RUTAS TIPO ETARIO
Route::resource('etarios','EtariosController');

//RUTAS PACIENTES
Route::resource('pacientes','PacientesController');
//creacion de pacientes desde la pantalla de contrarreferencia y lista de espera
Route::get('crear/pacientes/{flujo}', 'PacientesController@create2')->middleware('pacientes'); 
//edicion de pacientes para usuario con rol pacientesFull
Route::get('editar/pacientes/su/{id}/{flujo}', 'PacientesController@editSU')->middleware('pacientesfull'); 
//edicion de pacientes desde la pantalla de lista de espera
Route::get('editar/pacientes/di/{id}/{flujo}', 'PacientesController@editDI')->middleware('pacientes'); 

//alerta de creacion de pacientes si no hay rol asignado
Route::get('/alertaPacientes', function () {
    return view('alertaPacientes');
});

//RUTAS MAPA DE DERIVACION
Route::resource('mapaDerivacions','MapaDerivacionsController');

//TIPO PROCEDIMIENTO
Route::resource('tipoProcedimientos','TipoProcedimientosController');

//TIPO PROCEDIMIENTOS PM
Route::resource('tipoProcedimientosPm','TipoProcedimientoPmsController');

//RUTAS ASIGNAR PLANOS
Route::get('tipoProcedimientosPm/asignPlano/{tipoProcedimientosPm}', 'TipoProcedimientoPmsController@asignPlano');
Route::post('tipoProcedimientosPm/savePlano', 'TipoProcedimientoPmsController@savePlano');

//RUTAS ASIGNAR EXTREMIDADS
Route::get('tipoProcedimientosPm/asignExtremidad/{tipoProcedimientosPm}', 'TipoProcedimientoPmsController@asignExtremidad');
Route::post('tipoProcedimientosPm/saveExtremidad', 'TipoProcedimientoPmsController@saveExtremidad');

//TIPO PLANOS
Route::resource('planos','PlanosController');

//TIPO EXTREMIDADES
Route::resource('extremidads','ExtremidadsController');

//TIPO ESPERA
Route::resource('tipoEsperas','TipoEsperasController');

//TIPO MOTIVO SOLICITUD
Route::resource('motivoSolicituds','MotivoSolicitudController');

//TIPO TIPO SALIDA
Route::resource('tipoSalidas','TipoSalidasController');

//TIPO TRAMOS
Route::resource('tramos','TramosController');

//TIPO CAUSAL EGRESO
Route::resource('causalEgresos','CausalEgresosController');

//VÍAS
Route::resource('vias','ViasController');

//RUTA LOGIN AJAX
Route::get('getEstab/{mail}','Auth\LoginController@getEstab');
Route::get('getEspec/{mail}','Auth\LoginController@getEspec');


//LISTA ESPERA
 Route::group(['middleware' => ['digitadorLE']], function() {
	Route::get('listaesperas/create', 'ListaEsperasController@create');     					//Creacion de Lista de Espera
	Route::post('listaesperas/store', 'ListaEsperasController@store');							//Almacena Lista de Espera
	
	Route::get('listaesperas/filtroEgreso','ListaEsperasController@filtroEgreso'); 		        //Filtra Registros para Egreso de Listas de Espera
	Route::get('listaesperas/egreso', 'ListaEsperasController@egreso');							//Lista de LE para Egresar
	Route::get('listaesperas/{id}/detalle', 'ListaEsperasController@detalle');					//Pantalla de Egreso
	Route::post('listaesperas/actualiza', 'ListaEsperasController@actualiza');					//Almacena datos de Egreso 	
	
	Route::get('listaesperas/{id}/visualizar', 'ListaEsperasController@visualizar');            //Permite ver detalle de la lista espera 
	Route::get('listaesperas/pdf/{id}','ListaEsperasController@pdf');	         				//Permite imprimir en PDF la lista de espera
	
	Route::get('listaesperas/reporternle', 'ListaEsperasController@reporternle'); 				//Reporte red Nacional Lista Espera 
	Route::get('listaesperas/resultadornle', 'ListaEsperasController@resultadornle');			//Reporte red Nacional Lista Espera 
	Route::get('listaesperas/reporte', 'ListaEsperasController@reporte');						//Reporte Lista Espera con glosas
	Route::get('listaesperas/resultado', 'ListaEsperasController@resultado');					//Reporte Lista Espera con glosa 
	Route::get('listaesperas/excel','ListaEsperasController@excel');							//Exporta a excel reporte general 
	Route::get('listaesperas/excelrnle','ListaEsperasController@excelrnle');					//Exporta a excel exportable a RNLE		
});
//EDICION DE LISTA DE ESPERA
Route::get('listaesperas/filtroRegistro','ListaEsperasController@filtroRegistro')->middleware('superUsuarioLE'); 		//Filtra Registros para Edición de Listas de Espera
Route::get('listaesperas/registro', 'ListaEsperasController@registro')->middleware('superUsuarioLE');  					//Lista de Listas de Esperas Ingresadas 
Route::get('listaesperas/{id}/editar', 'ListaEsperasController@editar')->middleware('superUsuarioLE');	            	//Formulario editar Lista de Espera
Route::get('listaesperas/{id}/bitacora', 'ListaEsperasController@bitacora')->middleware('superUsuarioLE');	            //Formulario editar Lista de Espera
Route::post('listaesperas/actualizaingreso', 'ListaEsperasController@actualizaingreso')->middleware('superUsuarioLE');  //Acción de Editar Lista de Espera

//RUTAS DE CONSULTA AUTOCOMPLETADO
Route::get('getCie10',array('as'=>'getCie10','uses'=>'ListaEsperasController@autoComplete'));						    //RUTA AUTOCOMPLETA DIAGNOSTICO
Route::get('getPacienteLep',array('as'=>'getPacienteLep','uses'=>'ListaEsperasController@autoCompletePaciente'));	    //RUTA AUTOCOMPLETA PACIENTE LEP
Route::get('getTipoProcedimiento/{prestacion_id}','ListaEsperasController@autoCompleteTipoProcedimiento');			    //RUTA AUTOCOMPLETA PROCEDIMIENTO
Route::get('getTipoProcedimientopm/{tipo_procedimiento_id}','ListaEsperasController@autoCompleteTipoProcedimientopm');	//RUTA AUTOCOMPLETA PROCEDIMIENTO_PM
Route::get('getPlano/{TipoProcedimientosPm_id}','ListaEsperasController@autoCompletePlano');							//RUTA AUTOCOMPLETA PLANO
Route::get('getExtremidad/{TipoProcedimientosPm_id}','ListaEsperasController@autoCompleteExtremidad');					//RUTA AUTOCOMPLETA EXTREMIDAD
Route::get('getPrestamin/{TipoProcedimientosPm_id}','ListaEsperasController@autoCompletePrestamin');					//RUTA AUTOCOMPLETA PRESTAMIN
Route::get('getRem/{especialidads_ingreso_id}','ListaEsperasController@autoCompleteRem');								//RUTA AUTOCOMPLETA REM

//RUTAS REGISTRO ID_SIGTE
Route::get('listaesperas/ingresoSigte','ListaEsperasController@ingresoSigte')->middleware('superUsuarioLE');//Formulario de Ingreso de carga SIGTE
Route::post('listaesperas/uploadSigte','ListaEsperasController@uploadSigte')->middleware('superUsuarioLE');//Sube Archivo SIGTE
Route::get('excelRespuestaCargaSIGTE','ListaEsperasController@excelRespuestaCargaSIGTE')->middleware('superUsuarioLE'); // Genera Excel de la respuesta Carga Masiva de Validadores



route::get('/consultarDB/{id}', 'ConsultasController@getPacienteDB')->name('consultas.db');
route::get('/consultarFon/{id}', 'ConsultasController@getPacienteFon')->name('consultas.fon');