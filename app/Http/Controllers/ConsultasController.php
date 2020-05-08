<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use siscont\Http\Controllers\Api\FonasaApi;
use siscont\Helpers\Helper;


class ConsultasController extends Controller
{
    public function getPacienteDB($rut)
    {
        $paciente=null;
        try {
            $connection = mysqli_init();
            $connection->options(MYSQLI_OPT_CONNECT_TIMEOUT, 2);
            $connection = $connection->real_connect('10.8.64.41', 'nacevedo', '12345678', '');

            // $paciente = DB::connection('dbMaestra')->select('select * FROM siscont.pacientes WHERE rut =' . $rut);            
            $paciente = DB::connection('dbMaestra')->select('select * FROM siscont.pacientes WHERE rut =' . $rut);

            if (empty($paciente)) {
                return "No se encuentra este paciente";
            }
            else {
                $comuna = DB::connection('dbMaestra')->select('select * FROM siscont.comunas WHERE id =' . $paciente[0]->comuna_id);
                $fecha_nacimiento = Carbon::parse($paciente[0]->fechaNacimiento, 'America/Santiago');
                $hoy = Carbon::now();
                $paciente["edad"] = $hoy->diff($fecha_nacimiento)->y;
                $paciente["desc_comuna"] = $comuna[0]->name;
                $paciente["cod_comuna"] = $comuna[0]->codigo;
                $paciente["tipo_consulta"] = "DB";
                $paciente[0]->direccion = str_replace(' ' . $paciente[0]->numero, '', $paciente[0]->direccion);
                
            }
        } catch (Exception $e) {
             return $e->getMessage();
        }

         return $paciente;
    }

    public function getPacienteFon($rut)
    {

        $paciente="null";
        //se definen parametros para ingresar a base de datos siscont
        $tramos = [
            ' ' => 0,
            'A' => 1,
            'B' => 2,
            'C' => 3,
            'D' => 4,
            'X' => 'NULL'
            
        ];
        $prevision = [
            'Fonasa' => 1,
            'Isapre' => 2
        ];
        $genero = [
            'M' => 1,
            'F' => 2
        ];

        
        try {

            $fonasaApi = new FonasaApi();
            $paciente = $fonasaApi->fetchNormalized($rut, Helper::calcularDv($rut));

            // dd($paciente);

            if (gettype($paciente) == "string") {
                return $paciente;
            } else {
                $paciente["tipo_consulta"] = "FON";
            }
            
        } catch (Exception $e) {
            return $e->getMessage();
        }
        
        //en caso de que encuentre a la persona en fonasa, se inicia y se obtiene informacion de comuna desde siscont
        $connection = mysqli_init();
        $connection->options(MYSQLI_OPT_CONNECT_TIMEOUT, 2);
        $connection = $connection->real_connect('10.8.64.41', 'nacevedo', '12345678', '');
        
        
        if($paciente['comuna']==null||$paciente['comuna']==" "){
            $paciente['comuna']=0;
        }
        
        $comuna = DB::connection('dbMaestra')->select('select * FROM siscont.comunas WHERE codigo =' . $paciente['comuna']);
        //Obtiene la prevision, y si es que es fonasa tambien obtiene el tramo
        
        if($paciente['RESPUESTA_ORIGINAL']['desIsapre']==' '){
            $previ = 'Fonasa';
            $tramo = $paciente['RESPUESTA_ORIGINAL']['afiliadoTO']['tramo'];
        }else{
            $previ = 'Isapre';
            $tramo = 'X';
        }
        
        $paciente['comuna_id']= $comuna[0]->id;
        //inicializo la fecha actual para guardar como created_at y updated_at
        $now = Carbon::now('America/Santiago')->format('Y-m-d H:i:s');
        
        //si el telefono es vacio, lo define como NULL para ingresarlo en la base de datos
        if($paciente['telefono']==""){
            $paciente['telefono']="NULL";
        }
        //construye el array con los datos necesarios para ingresar un paciente a siscont
        $datos =
        ['2', 
        $paciente['rut'], 
            '"'.$paciente['dv'].'"',
            '"'.$paciente['nombres'].'"', 
            '"'.$paciente['apellido_paterno'].'"', 
            '"'.$paciente['apellido_materno'].'"', 
            'STR_TO_DATE('.'"'.$paciente['fecha_nacimiento'].'", '.'"%Y-%m-%d"'.')',
            $genero[$paciente['genero']],
            $prevision[$previ],
            $tramos[$tramo],
            '2',
            '2',
            '2',
            '"'.$paciente['direccion'].'"',
            '0',
            $comuna[0]->id,
            $paciente['telefono'],
            '1',
            '"'.$now.'"',
            '"'.$now.'"'
        ];
        
        //construye una cade string con los datos a ingresar a siscont
        $datos_str = join( ',', $datos);
        //dd($paciente,$datos_str);
        //ejecuta la consulta para ingresar paciente a siscont, utiliza $datos_str concadenado a la consulta.
        // DB::connection('dbMaestra')->select('insert into siscont.pacientes (tipoDoc,rut,dv,nombre,apPaterno,apMaterno,fechaNacimiento,genero_id,prevision_id,tramo_id,prais,funcionario,via_id,direccion,numero,comuna_id,telefono,active,created_at,updated_at) values ('.$datos_str.')');
        
        // dd($rut,$paciente);
        return $paciente;
    }
    
    
    public function getModalShow($id)
    {
        $formulario = FormularioSolicitud::where('formulario_solicitud_id', $id)->first();
        $formulario_laboratorio = FormularioLaboratorio::where('formulario_solicitud_id', $id)->first();

        //dd($formulario->getRazones());
        return View("Formularios/Modals/modalshow")->with('formulario', $formulario)
            ->with('formulario_laboratorio', $formulario_laboratorio);
    }

}
