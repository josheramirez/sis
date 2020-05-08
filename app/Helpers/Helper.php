<?php

namespace siscont\Helpers;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use DateTime;
use Auth;


class Helper
{
    public static function getTiposCodigo()
    {
        return [
            1 => 'Típico',
            2 => 'Recién Nacidos',
            3 => 'Recién Nacidos (Gemelar)',
            4 => 'Con un apellido',
            5 => 'Con apellido compuesto'
        ];
    }
    public static function getTiposCodigoC()
    {
        return  [
            1 => 'Típico',
            2 => 'Recién Nacidos',
            3 => 'Recién Nacidos (Gemelar)',
            4 => 'Con un apellido',
            5 => 'Con apellido compuesto',
            6 => 'Sin RUN/RUT',
            7 => 'Sin RUN/RUT con un apellido'
        ];
    }


    public static function getNumeroDir($direccion)
    {
        $direccion = explode(' ', $direccion);
        $numero = end($direccion);
        if (is_numeric($numero)) {
            return $numero;
        } else {
            return '0000';
        }
    }

    public static function actualizarSiscont($datos)
    {
        $connection = mysqli_init();
        $connection->options(MYSQLI_OPT_CONNECT_TIMEOUT, 2);
        $connection = $connection->real_connect('10.8.64.41', 'nacevedo', '12345678', '');
        
        // $numero_dir = self::getNumeroDir($datos['direccion']); //Obtiene numero de direccion guardada
        // $direccion = str_replace(' ' . $numero_dir, '', $datos['direccion']); // Obtiene direccion base, sin numero
        
        $datos_siscont = DB::connection('dbMaestra')->select('select * FROM siscont.pacientes WHERE rut =' . $datos['rut'])[0]; //Obtenog los datos de paciente en siscont
        $comuna =  DB::connection('dbMaestra')->select('select * FROM siscont.comunas WHERE id =' . $datos_siscont->comuna_id)[0]; //Obtengo comuna de siscont
        $comuna_act =  DB::connection('dbMaestra')->select('select * FROM siscont.comunas WHERE id =' . $datos->comuna)[0];
        // dd( $datos,$datos_siscont,$comuna,$comuna_act);

        if (
            ($datos_siscont->email <> $datos->email) ||
            ($datos_siscont->comuna_id <> $datos->comuna) || //Compara si los datos ingresados son iguales a los de comuna
            ($datos_siscont->telefono <> $datos->telefono) || //lo mismo para el telefono
            ($datos_siscont->telefono2 <> $datos->telefono2) || //lo mismo para el telefono
            ($datos_siscont->direccion <> $datos->direccion) || //lo mismo para direccion
            ($datos_siscont->numero <> $datos->numero ) //lo mismo para para numero de direccion
        ) {
            //si alguno de los datos evaluados anteriormente cambia, se actualizan los datos en siscont
            DB::connection('dbMaestra')->select('update siscont.pacientes set comuna_id=' . $comuna_act->id .
                ', direccion="'. $datos->direccion .'", numero=' . $datos->numero .
                ', telefono="'. $datos['telefono'] .'", telefono2="'. $datos['telefono2'] .'", email="'. $datos['email'] .'" WHERE rut =' . $datos['rut']);
               
            }
            // dd( $datos,$datos_siscont,$comuna,$comuna_act);
    }

    public static function formatearFecha($fecha)
    {
        if ($fecha != '') {
            return Carbon::parse($fecha)->format('d/m/Y');
        } else {
            return '';
        }
    }

    public static function formatearFechaBD($fecha)
    {
        $fecha = str_replace('/', '-', $fecha);
        return date('Y-m-d', strtotime($fecha));
    }

    public static function calcularDv($rut)
    {
        $rut_rev = strrev($rut); // se invierte el rut
        $multiplicador = 2; // setea el multiplicador de los digitos del rut
        $suma = 0;
        for ($i = 0; $i < strlen($rut_rev); $i++) { // itera hasta el largo del string $rut_rev
            $digito = $rut_rev[$i];
            $digito = intval($digito); // transforma el digito de string a int
            if ($multiplicador > 7) { // si el multiplicador es mayor a 7 se resetea a 2
                $multiplicador = 2;
            }
            $suma += $multiplicador * $digito; // se realiza la suma correspondiente
            $multiplicador += 1; //aumenta el multiplicador en 1 (max 7)
        }
        $valor = 11 * intval($suma / 11); // trunca la division entre la suma total y 11, además se multiplica por 11
        $resto = $suma - $valor; // del valor anteior se le resta a la suma total
        $final = 11 - $resto; // finalmente, se resta a 11 el resto obtenido.

        // si el valor final es 10 , el dv será k, si es 11 será 0, si es menor a 10 será el valor que represente.
        if ($final == 10) {
            return "K";
        } elseif ($final == 11) {
            return "0";
        } else {
            return $final;
        }
    }

    public static function calcularEdad($nacimiento)
    {
        // construye fechas nacimiento y actual del tipo DateTime
        $nacimiento = new DateTime($nacimiento);
        $actual = (new DateTime());
        // se calcula la diferencia entre ambas fechas
        $intervalo = $nacimiento->diff($actual);
        // luego retorna la diferencia de estas fechas en años
        return substr($intervalo->format('%R%y'), 1);
    }

    public static function tiempoEditar($creacion)
    {
        // construye fechas nacimiento y actual del tipo DateTime
        $creacion = new DateTime($creacion);
        $actual = (new DateTime());
        // se calcula la diferencia entre ambas fechas
        $intervalo = $creacion->diff($actual);

        // luego retorna la diferencia de estas fechas en años
        $segundos = ($intervalo->h * 3600) + ($intervalo->i * 60) + $intervalo->s;
        //dd($segundos, $actual, $creacion, $intervalo);
        if ($intervalo->d > 0) {
            return false;
        } else {
            if ($segundos < 43200) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function diffDias($fecha1, $fecha2)
    {
        // construye fechas nacimiento y actual del tipo DateTime
        // $fecha1 = new DateTime($fecha1);
        // $fecha2 = new DateTime($fecha2);
        // se calcula la diferencia entre ambas fechas
        // $intervalo = $fecha1->diff($fecha2);
        // luego retorna la diferencia de estas fechas en años
        // return $intervalo->days;
        // return 10;
    }

    public static function diffDias2($fecha1, $fecha2)
    {
        //construye fechas nacimiento y actual del tipo DateTime
        $fecha1 = new DateTime($fecha1);
        $fecha2 = new DateTime($fecha2);
        //se calcula la diferencia entre ambas fechas
        $intervalo = $fecha1->diff($fecha2);
        //luego retorna la diferencia de estas fechas en años
        return $intervalo->days;
        // return 10;
    }

    public static function getDesacString($array, $i)
    {
        if (in_array($i, $array)) {
            return '';
        } else {
            return 'disabled';
        }
    }


    public static function getDatosAlertas($request)
    {
        $array = ['data' => []];
        // inicializo las variables necesarias para la obtencion de los resultados
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'];
        $col_ord = $request->get('order')[0]['column'];
        $dir_ord = $request->get('order')[0]['dir'];
        $criterio = $request->headers->all()["criterio"][0];

        //Obtengo la cantidad total de filas segun el la busqueda que se haya ingresado, este $total le dice a datatable cuantas paginas debe mostrar en base a $length
        if ($search != "") {
            $total = $formularios = FormularioSolicitud::where('rut', 'like', '%' . $search . '%')
                ->orWhere(DB::raw("CONCAT(nombres,' ',apellido_paterno,' ',apellido_materno)"), 'like', '%' . $search . '%')
                ->orderBy('created_at', 'desc')->count();
        } else {
            //obtiene el total de formularios en el sistema
            $total = FormularioSolicitud::all()->count();
        }
        //Segun la columna seleccionada para el ordenamiento define los nombres de las columnas
        if ($col_ord == "0") {
            $col_ord = "diferencia";
        } elseif ($col_ord == "1") {
            $col_ord = "codigo_survih";
        } elseif ($col_ord == "2") {
            $col_ord = "codigo_vih";
        } elseif ($col_ord == "3") {
            $col_ord = "establecimiento_nombre";
        } elseif ($col_ord == "4") {
            $col_ord = "created_at";
        }

        // en caso de que en el input de busqueda se haya ingresado "por vencer" la consulta traera todos los formularios que tienen establecimiento origen, con sus respectivas fechas
        $datediff = 'DATEDIFF(vih_establecimiento_unidad_origens.fecha_ingreso_lugar_vinculacion, vih_establecimiento_unidad_origens.fecha_entrega_resultado_usuario)';
        $formularios = FormularioSolicitud::select(
            'vih_formularios_solicitud.codigo_survih',
            'vih_formularios_solicitud.codigo_vih',
            'vih_formularios_solicitud.formulario_solicitud_id',
            'vih_formularios_solicitud.formulario_actualizado_id',
            'vih_formularios_solicitud.fecha_nacimiento',
            'vih_formularios_solicitud.created_at',
            'vih_formularios_solicitud.establecimiento_id',
            'vih_establecimientos.tx_descripcion as establecimiento_nombre',
            DB::raw($datediff . " as diferencia")
        )
            ->join('vih_establecimientos', 'vih_formularios_solicitud.establecimiento_id', '=', 'vih_establecimientos.establecimiento_id')
            ->join('vih_establecimiento_unidad_origens', 'vih_formularios_solicitud.formulario_solicitud_id', '=', 'vih_establecimiento_unidad_origens.solicitud_id')
            ->orderBy($col_ord, $dir_ord);
        if ($criterio == "por_vencer") {
            $formularios = $formularios->whereRaw($datediff . ' >= 30 and ' . $datediff . ' <=45');
        } else {
            $formularios = $formularios->whereRaw($datediff . ' > 45');
        }
        // la consulta obtiene la cantidad de días de diferencia que hay entre las fechas correspondientes, si es que son mayores o igual a 30 días , las obtiene
        // Se ordenan segun la columna seleccionada para ordenar "$col_ord" y ascendentemente o descendentemente "$dir_ord"

        //como la busqueda es "por vencer" se obtiene el total de registros
        $total = $formularios->get()->count();
        //luego, de ese total nos saltamos las $start cantidad de filas y tomamos la cantidad $length para mostrar
        $formularios = $formularios->skip($start)->take($length)->get();

        return array(
            "draw" => $draw, //para la paginacion
            "recordsTotal" => $total, //para la paginacion
            "recordsFiltered" => $total, //para la paginacion
            "search" => $search, // si es que envía "por vencer" en vista se muestra la columna "Días en espera"
            "datos" => self::completarForm($formularios, $search)['data'] //para llenar la tabla
        );
    }

    public static function getDatosAlertas2($request)
    {
        $array = ['data' => []];
        // inicializo las variables necesarias para la obtencion de los resultados
        //dd("sdadasd");
        $draw = '';
        $search = '';
        $usuario = Auth::user();

        // en caso de que en el input de busqueda se haya ingresado "por vencer" la consulta traera todos los formularios que tienen establecimiento origen, con sus respectivas fechas
        $datediff = 'if(vih_establecimiento_unidad_origens.fecha_ingreso_lugar_vinculacion is not null ,DATEDIFF(vih_establecimiento_unidad_origens.fecha_ingreso_lugar_vinculacion, vih_establecimiento_unidad_origens.fecha_entrega_resultado_usuario), DATEDIFF(NOW(), vih_establecimiento_unidad_origens.fecha_entrega_resultado_usuario))';
        if ($usuario->ver_local == "1") {
            $formularios = FormularioSolicitud::select(
                'vih_formularios_solicitud.codigo_survih',
                'vih_formularios_solicitud.codigo_vih',
                'vih_formularios_solicitud.formulario_solicitud_id',
                'vih_formularios_solicitud.formulario_actualizado_id',
                'vih_formularios_solicitud.fecha_nacimiento',
                'vih_formularios_solicitud.estado',
                'vih_formularios_solicitud.estado_sm',
                'vih_formularios_solicitud.estado_muestra',
                'vih_formularios_solicitud.created_at',
                'vih_formularios_solicitud.establecimiento_id',
                'vih_establecimientos.tx_descripcion as establecimiento_nombre',
                DB::raw($datediff . " as diferencia")
            )
                ->join('vih_establecimientos', function ($join) use ($usuario) {
                    $join->on('vih_formularios_solicitud.establecimiento_id', '=', 'vih_establecimientos.establecimiento_id')
                        // ->where('vih_formularios_solicitud.establecimiento_id', '=', $usuario->establecimiento_id)
                        // ->where('vih_formularios_solicitud.usuario_id', '=', $usuario->id)
                        ->where('vih_formularios_solicitud.estado', 'sin_estado')
                        ->where('vih_formularios_solicitud.estado_muestra','!=', 'negativo');
                })
                ->join('vih_establecimiento_unidad_origens', function ($join) use ($usuario) {
                    $join->on('vih_formularios_solicitud.formulario_solicitud_id', '=', 'vih_establecimiento_unidad_origens.solicitud_id')
                    ->where('vih_establecimiento_unidad_origens.estado', null);
                })
                ->orderBy('diferencia', 'desc');
        } else {
            $formularios = FormularioSolicitud::select(
                'vih_formularios_solicitud.codigo_survih',
                'vih_formularios_solicitud.codigo_vih',
                'vih_formularios_solicitud.formulario_solicitud_id',
                'vih_formularios_solicitud.formulario_actualizado_id',
                'vih_formularios_solicitud.fecha_nacimiento',
                'vih_formularios_solicitud.estado',
                'vih_formularios_solicitud.estado_sm',
                'vih_formularios_solicitud.estado_muestra',
                'vih_formularios_solicitud.created_at',
                'vih_formularios_solicitud.establecimiento_id',
                'vih_establecimientos.tx_descripcion as establecimiento_nombre',
                DB::raw($datediff . " as diferencia")
            )

                ->join('vih_establecimientos', function ($join) use ($usuario) {
                    $join->on('vih_formularios_solicitud.establecimiento_id', '=', 'vih_establecimientos.establecimiento_id')
                        ->where('vih_formularios_solicitud.estado', 'sin_estado')
                        ->where('vih_formularios_solicitud.estado_muestra','!=', 'negativo');
                })
                ->join('vih_establecimiento_unidad_origens', function ($join) use ($usuario) {
                    $join->on('vih_formularios_solicitud.formulario_solicitud_id', '=', 'vih_establecimiento_unidad_origens.solicitud_id')
                    ->where('vih_establecimiento_unidad_origens.estado', null);
                })
                ->orderBy('diferencia', 'desc');
        }
        //dd($formularios->get()->toArray());
        if ($request == "por_vencer") {
            $formularios = $formularios->whereRaw($datediff . ' >= 30 and ' . $datediff . ' <45');
        } else {
            $formularios = $formularios->whereRaw($datediff . ' >= 45');
        }
        // la consulta obtiene la cantidad de días de diferencia que hay entre las fechas correspondientes, si es que son mayores o igual a 30 días , las obtiene
        // Se ordenan segun la columna seleccionada para ordenar "$col_ord" y ascendentemente o descendentemente "$dir_ord"

        //como la busqueda es "por vencer" se obtiene el total de registros
        $total = $formularios->get()->count();
        //luego, de ese total nos saltamos las $start cantidad de filas y tomamos la cantidad $length para mostrar
        $formularios = $formularios->get();

        return array(
            "draw" => $draw, //para la paginacion
            "recordsTotal" => $total, //para la paginacion
            "recordsFiltered" => $total, //para la paginacion
            "search" => $search, // si es que envía "por vencer" en vista se muestra la columna "Días en espera"
            "data" => self::completarForm($formularios, 'modal_alertas')['data'] //para llenar la tabla
        );
    }

    public static function completarForm($formularios, $search)
    {
        //$nombre_rol = Auth::user()->getNombreRol();
        $array = ['data' => []];
        $p1 = Auth::user()->getPermisos('1');
        $p2 = Auth::user()->getPermisos('2');
        $p3 = Auth::user()->getPermisos('3');
        $p4 = Auth::user()->getPermisos('4');
        $p5 = Auth::user()->getPermisos('5');
        $p6 = Auth::user()->getPermisos('6');



        //dd($perm['explorar'],$perm['eliminar']);
        foreach ($formularios as $form) {
            //dd($form);
            $diffDias = "";
            //obtiene el establecimiento correspondiente al formulario solicitud, este tiene que estar completo o tener la primera fecha (entrega resultado usuario)
            $est = $form->getFormEoCompleto();

            // si el formulario es indeterminado o positivo, se calcula la diferencia de días
            if (($est != "0" && $est->estado == null) && ($form->estado_muestra != 'negativo' && $form->estado == 'sin_estado')) {

                $diffDias = Helper::diffDias2($est->fecha_entrega_resultado_usuario, $est->fecha_ingreso_lugar_vinculacion);
            }
            $vencimiento = "";
            if ($search != "modal_alertas") {
                //según la cantidad que se haya obtenido en el calculo de dias ($diffDias) se construye la alerta en codigo html.

                if ($diffDias >= 30 && $diffDias <= 45) {
                    $vencimiento = '&nbsp;<i class="fas fa-exclamation-circle faa-flash animated fa-lg my-icon" style="color:orange" id="icono_alerta"></i>';
                } elseif ($diffDias > 45) {
                    $vencimiento = '&nbsp;<i class="fas fa-exclamation-circle faa-flash animated fa-lg my-icon" style="color:red" id="icono_alerta"></i>';
                }
            }


            // obtiene el tiempo para editar un formulario, máximo 12 horas
            $editable = Helper::tiempoEditar($form->created_at);

            //concadeno el codigo html de la alerta al codigo_survih para que se muestre en vista
            $form["codigo_survih"] =  $form["codigo_survih"].$vencimiento;

            //obtiene la fecha de la BD en formato yyyy-mm-dd hh:mm:ss y la devuelve en dd/mm/yyyy hh:mm:ss
            $fecha = explode(' ', $form->created_at);
            $form['fecha_creacion'] = Helper::formatearFecha($fecha[0]) . ' ' . $fecha[1];
            $form['procedencia'] = $form->getEstablecimiento()->tx_descripcion;

            $sm = $form->segundaMuestra();

            //para cada registro o formulario se crea el nombre completo y las razones serializadas en string
            // $razones = $form->getRazones();
            // if (in_array(19, $razones)) {
            //     $form['razones_completo'] = RazonFormularioSolicitud::where('formulario_solicitud_id', $form->formulario_solicitud_id)->where('razon_id', 19)->first()->descripcion;
            // } else {
            //     $form['razones_completo'] = $form->getRazonesIndex();
            // }

            if ($form->formulario_actualizado_id == 0 && $form->estado_muestra != "no reactivo") {
                $pdfRoute = route('formularios.solicitud.pdf', ['id' => $form->formulario_solicitud_id]);
                $editRoute = route('formularios.solicitud.edit', ['id' => $form->formulario_solicitud_id]);
                //dd($pdfRoute);
                $form['accion'] = $form['accion'] . '<div class="row"><div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="margin: auto">
                <div class="btn-group mr-2 btn-group-sm" role="group" aria-label="First group">';
                if ($p1['explorar'] == 1) {
                    $form['accion'] = $form['accion'] . '<a type="button" class="btn btn-info border border-dark" title="Documentos" href="' . $pdfRoute . '" target="_blank"><i class="far fa-file-alt"></i></a>';
                }
                if ($p1['editar'] == 1) {
                    if ($editable) {
                        $form['accion'] = $form['accion'] . ' <a type="button" href="' . $editRoute . '" class="btn btn-warning border border-dark" title="Editar"><i class="fas fa-edit"></i></a>';
                    } else {
                        $form['accion'] = $form['accion'] . ' <a type="button" href="#" class="btn btn-default border border-dark" onclick="desactivado(this)" title="Editar"><i class="fas fa-edit"></i></a>';
                    }
                }
                if ($p1['eliminar'] == 1) {
                    $form['accion'] = $form['accion'] . '<button type="button" class="btn btn-danger border border-dark ELIMINAR" title="Eliminar" onclick="deleteData(this.id)" id="' . route('formularios.solicitud.modalDelete', ['id' => $form->formulario_solicitud_id]) . '"><i class="fas fa-trash"></i></button>';
                }
                $form['accion'] = $form['accion'] . '</div></div></div>';
            } else {
                $form['accion'] = '';
            }

            $lab = "";
            $lab2 = "";
            $isp = "";
            $isp2 = "";
            $fcc = "";
            $fcc2 = "";
            $bp = "";
            $cc = "";
            $fccEstado = "";
            $fccEstado2 = "";
            $ct=0;
            $segunda=null;
            $desac = [
                'ISP' => '',
                'ISP2' => '',
                'LAB' => '',
                'EST' => '',
                'BUS' => '',
                'CC' => '',
            ];

            $sin_movimiento=0;

            if ($form->getFormISP() == 2) {
                // dd($form);
                $isp = "success";
                $sin_movimiento=1;
            } else {
                // dd($form);

                if ($form->getFormISP() == 1) {
                    $isp = "warning";
                    $sin_movimiento=1;
                } else {
                    $desac['EST'] = "disabled";
                    $desac['BUS'] = "disabled";
                    $desac['CC'] = "disabled";
                    $desac['LAB'] = "disabled";
                    $isp = "default";
                    $sin_movimiento=0;
                }
            }

            if($form['estado_muestra'] == 'indeterminado' || $form['estado_muestra'] == 'indeterminado'){
                if ($form->getFormISP2() == 2) {
                    $isp2 = "success";
                    $sin_movimiento=1;
                } else {
                    // dd($form);
                    if ($form->getFormISP2() == 1) {
                        $isp2 = "warning";
                        $sin_movimiento=1;
                    } else {
                        $isp2 = "default";
                        $sin_movimiento=0;
                    }
                }
            }

            if ($form->getFormLab() == 2) {
                $lab = "success";
                $sin_movimiento=1;
                $desac['CC'] = "";
            } else {
                if ($form->getFormLab() == 1) {
                    $lab = "warning";
                    $sin_movimiento=1;
                    $desac['CC'] = "";
                } else {
                    $lab = "default";
                    $sin_movimiento=0;
                }
                // $lab = "default";
            }

            if($form['estado_muestra'] == 'indeterminado' || $form['estado_muestra'] == 'indeterminado'){
               $segunda = SegundaMuestra::where('formulario_solicitud_id',$form['formulario_solicitud_id'])->first();
            }
           

            // CONSULTO POR EL ESTADO DE FORMULARIO ESTABLECIMIENTO
            if ($form->getFormEo() == 2) {

                $fcc = "success";
                $sin_movimiento=1;
            } else {
                if ($form->getFormEo() == 1) {
                    $fcc = "warning";
                    $sin_movimiento=1;
                } else {
                    $fcc = "default";
                    $sin_movimiento=0;
                }
            }

            // if ($form->getFormEo("segunda") == 2) {
            //     $fcc2 = "success";
            //     $sin_movimiento=1;
            // } else {
            //     if ($form->getFormEo("segunda") == 1) {
            //         $fcc2 = "warning";
            //         $sin_movimiento=1;
            //     } else {
            //         $fcc2 = "default";
            //         $sin_movimiento=0;
            //     }
            // }

            // if($form->formulario_solicitud_id=="106" ){
            //     dd($fcc,$fcc2);
            // }
            // si estado de ambos fomulario es distinto, el formulario estara incompleto
            // if($sm!=null){
            //     if($fcc!=$fcc2){
            //         $fcc= "warning";
            //     }
            // }

            // desahabilito el boton en caso que entrega al paciente este lista
            if ($form->getFormEoEstado("primera") == 1) {
                $fccEstado = "disabled";
            } else {
                $fccEstado = "";
            }
            if ($form->getFormEoEstado("segunda") == 1) {
                $fccEstado2 = "disabled";
            } else {
                $fccEstado2 = "";
            }



            if ($form->getFormBp() == 2) {
                $bp = "success";
                $sin_movimiento=1;
            } else {
                if ($form->getFormBp() == 1) {
                    $bp = "warning";
                    $sin_movimiento=1;
                } else {
                    $bp = "default";
                    $sin_movimiento=0;
                }
                // $bp = "default";
            }


            if ($form->getFormCc() == 2) {
                $cc = "success";
                $sin_movimiento=1;
            } else {
                if ($form->getFormCc() == 1) {
                    $cc = "warning";
                    $sin_movimiento=1;
                } else {
                    $cc = "default";
                    $sin_movimiento=0;
                }
                // $cc = "default";
            }



            if ($form->formulario_actualizado_id == 0 && $form->estado_muestra != "no reactivo") {
                //se definen fariables formularios desde el 1 al 6, en estas variables se almacenará el código html del boton en particular
                $f1 = $f2 = $f3 = $f4 = $f5 = $f6 = $fc = '';
                // 1 -> ISP; 2-> Laboratorio; 3-> Establecimiento; 4-> Busqueda Paciente; 5-> Caso Cerrado; 6->ISP2(segunda_muestra);
                //

                //se crea el codigo html que abrirá el contenedor de los botones
                $form['formularios'] = '<div class="row"><div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="margin: auto">
                <div class="btn-group mr-2 btn-group-sm" role="group" aria-label="First group">';

                $fc = '<button type="button" class="btn btn-info faa-flash animated border border-dark" onclick="finalizar(this.id)" id="' . route('formularios.solicitud.modalFinalizar', ['id' => $form->formulario_solicitud_id]) . '" title=""><i class="fas fa-check"></i></button>';

                //para cada caso, según correspondan los permisos, se añade el codigo html del botón a cada variable.
                if ($p2['explorar']) {
                   

                    if($sm==null){
                        $f1 = '<button type="button" class="btn btn-' . $isp . ' border border-dark ISP" onclick="viewISP(this.id)" id="' . route('VihCasosConfirmadosPositivosIsp.show', ['id' => $form->formulario_solicitud_id]) . '" title="ISP"><i class="fas fa-clinic-medical"></i></button>';
                    }else{
                        if($form->estado_muestra == 'negativo'){
                            $f1 = '<button type="button" class="btn btn-' . $isp . ' border border-dark ISP" onclick="viewISP(this.id)" id="' . route('VihCasosConfirmadosPositivosIsp.show', ['id' => $form->formulario_solicitud_id]) . '" title="ISP"><i class="fas fa-clinic-medical"></i></button>';
                        }else{
                            $f1 = '<button type="button" class="btn btn-' . $isp . ' border border-dark ISP" onclick="viewISP(this.id)" id="' . route('VihCasosConfirmadosPositivosIsp.show', ['id' => $form->formulario_solicitud_id]) . '" title="ISP"><i class="fas fa-clinic-medical"></i>*</button>';
                        }
                        
                    }
                }
                
                if ($p3['explorar']) {
                    if($sm==null){
                        $f2 = '<button type="button" class="btn btn-' . $lab . ' border border-dark LAB" onclick="viewLab(this.id)" id="' . route('laboratorio.show', ['id' => $form->formulario_solicitud_id]) . '" title="Laboratorio" '.$desac['LAB'].'><i class="fas fa-flask"></i></button>';
                    }else{
                        if($form->estado_muestra == 'negativo'){
                            $f2 = '<button type="button" class="btn btn-' . $lab . ' border border-dark LAB" onclick="viewLab(this.id)" id="' . route('laboratorio.show', ['id' => $form->formulario_solicitud_id]) . '" title="Laboratorio" '.$desac['LAB'].'><i class="fas fa-flask"></i></button>';
                        }else{
                            $f2 = '<button type="button" class="btn btn-' . $lab . ' border border-dark LAB" onclick="viewLab(this.id)" id="' . route('laboratorio.show', ['id' => $form->formulario_solicitud_id]) . '" title="Laboratorio" '.$desac['LAB'].'><i class="fas fa-flask"></i>*</button>';
                        }
                        
                    }
                }
                if ($p4['explorar']) {
                    // el icono esta bloqueado por isp y lab
                    if ($desac['EST'] == "disabled") {
                        $f3 = ' <button type="button" class="btn btn-' . $fcc . ' border border-dark ESTdisabled" onclick="viewEo(this.id)" id="' . route('VihEstablecimientoUnidadOrigen.show', ['id' => $form->formulario_solicitud_id]) . '" title="Establecimiento"'.$desac['EST'].' ><i class="far fa-hospital"></i></button>';
                    // el boton no esta bloqueado, ASIGNO EL *
                    } else {
                        // existe una segunda muestra
                        if($sm==null){
                            $f3 = ' <button type="button" class="btn btn-' . $fcc . ' border border-dark EST" onclick="viewEo(this.id)" id="' . route('VihEstablecimientoUnidadOrigen.show', ['id' => $form->formulario_solicitud_id]) . '" title="Establecimiento"'.$desac['EST'].' ><i class="far fa-hospital"></i></button>';
                        // no existe una segunda muestra
                        }else{
                            if($form->estado_muestra == 'negativo'){
                                $f3 = ' <button type="button" class="btn btn-' . $fcc . ' border border-dark EST" onclick="viewEo(this.id)" id="' . route('VihEstablecimientoUnidadOrigen.show', ['id' => $form->formulario_solicitud_id]) . '" title="Establecimiento"'.$desac['EST'].' ><i class="far fa-hospital"></i></button>';
                            }else{
                                $f3 = ' <button type="button" class="btn btn-' . $fcc . ' border border-dark EST" onclick="viewEo(this.id)" id="' . route('VihEstablecimientoUnidadOrigen.show', ['id' => $form->formulario_solicitud_id]) . '" title="Establecimiento"'.$desac['EST'].' ><i class="far fa-hospital">*</i></button>';
                            }
                            
                        }

                    }

                }
                if ($p5['explorar']) {
                    
                    if ($desac['BUS'] == "disabled") {
                        if($sm==null){
                            $f4 = '<button type="button" class="btn btn-' . $bp . ' border border-dark BUSdisabled" onclick="viewBp(this.id)" id="' . route('VihBusquedaPaciente.show', ['id' => $form->formulario_solicitud_id]) . '" title="Busqueda Pacientes" '.$desac['BUS'].'><i class="fas fa-user"></i></button>';
                        }else{
                            if($form->estado_muestra == 'negativo'){
                                $f4 = '<button type="button" class="btn btn-' . $bp . ' border border-dark BUSdisabled" onclick="viewBp(this.id)" id="' . route('VihBusquedaPaciente.show', ['id' => $form->formulario_solicitud_id]) . '" title="Busqueda Pacientes" '.$desac['BUS'].'><i class="fas fa-user"></i></button>';    
                            }else{
                                $f4 = '<button type="button" class="btn btn-' . $bp . ' border border-dark BUSdisabled" onclick="viewBp(this.id)" id="' . route('VihBusquedaPaciente.show', ['id' => $form->formulario_solicitud_id]) . '" title="Busqueda Pacientes" '.$desac['BUS'].'><i class="fas fa-user">*</i></button>';    
                            }
                                           
                        }
                    } else {
                        if($sm==null){
                            $f4 = '<button type="button" class="btn btn-' . $bp . ' border border-dark BUS" onclick="viewBp(this.id)" id="' . route('VihBusquedaPaciente.show', ['id' => $form->formulario_solicitud_id]) . '" title="Busqueda Pacientes" '.$desac['BUS'].'><i class="fas fa-user"></i></button>';
                        }else{
                            if($form->estado_muestra == 'negativo'){
                                $f4 = '<button type="button" class="btn btn-' . $bp . ' border border-dark BUS" onclick="viewBp(this.id)" id="' . route('VihBusquedaPaciente.show', ['id' => $form->formulario_solicitud_id]) . '" title="Busqueda Pacientes" '.$desac['BUS'].'><i class="fas fa-user"></i></button>';
                            }else{
                                $f4 = '<button type="button" class="btn btn-' . $bp . ' border border-dark BUS" onclick="viewBp(this.id)" id="' . route('VihBusquedaPaciente.show', ['id' => $form->formulario_solicitud_id]) . '" title="Busqueda Pacientes" '.$desac['BUS'].'><i class="fas fa-user">*</i></button>'; 
                            }
                            
                        }
                    }
                }
                if ($p6['explorar']) {
                    if ($desac['BUS'] == "disabled") {
                        $f5 = ' <button type="button" class="btn btn-' . $cc . ' border border-dark CCdisabled" onclick="viewCc(this.id)" id="' . route('VihCasoCerrado.show', ['id' => $form->formulario_solicitud_id]) . '" title="Caso Cerrado Paciente" '.$desac['CC'].'><i class="far fa-calendar-check"></i></button>';
                    } else {
                        if($form->estado_muestra == 'negativo'){
                            $f5 = ' <button type="button" class="btn btn-' . $cc . ' border border-dark CC" onclick="viewCc(this.id)" id="' . route('VihCasoCerrado.show', ['id' => $form->formulario_solicitud_id]) . '" title="Caso Cerrado Paciente" '.$desac['CC'].'><i class="far fa-calendar-check"></i></button>';
                        }else{
                            $f5 = ' <button type="button" class="btn btn-' . $cc . ' border border-dark CC" onclick="viewCc(this.id)" id="' . route('VihCasoCerrado.show', ['id' => $form->formulario_solicitud_id]) . '" title="Caso Cerrado Paciente" '.$desac['CC'].'><i class="far fa-calendar-check"></i></button>';
                        }
                        
                    }

                }

                $dias_desde_creacion = Helper::diffDias2($form->created_at, '');
                if ($form['estado_muestra'] == 'negativo') {
                    //en caso de que el estado del formulario sea negativo, se considera ISP y Lab
                    $form['formularios'] = $form['formularios'] . $f1 . $f2 . '</div></div></div>';
                } elseif (($form['estado_muestra'] == 'indeterminado' || $form['estado_muestra'] == 'positivo') && $form['estado_sm'] == 'negativo') {
                    //en caso de que el estado del formulario sea indeterminado y estado_sm negarivo (caso de segunda_muestra), se considera ISP, ISP2 y Lab
                    $form['formularios'] = $form['formularios'] . $f1 . $f6 . $f2 . '</div></div></div>';
                } elseif ($form['estado'] == 'no reactivo') {
                    //en caso de que sea no reactiva no se concadena ningun boton
                    $form['formularios'] = $form['formularios'] . '</div></div></div>';
                } elseif ($form['estado_muestra'] == 'sin_estado') {
                    $form['formularios'] = $form['formularios'] . $f1 . $f2 . $f3 . $f4 . $f5;
                    if ($dias_desde_creacion > 7 && $form['estado'] != "no reactivo" &&  $sin_movimiento==0) {

                        $form['formularios'] = $form['formularios'] . $fc;
                    }
                    //en caso de que sea no reactiva no se concadena ningun boton
                    $form['formularios'] = $form['formularios'] . '</div></div></div>';
                } else {
                    // en caso contrario a los anteriores, se consideran todos lo botones y se concadenan
                    $form['formularios'] = $form['formularios'] . $f1 . $f6 . $f2 . $f3 . $f4 . $f5 . '</div></div></div>';
                }
                //notar que en algunos casos se concadenan variables que no corresponden, esto no es problema ya que estaría concadenando vacio
                //ya que si no se cumplen las condiciones, a estas variables no se le asigna contenido.
            } else {
                $form['formularios'] = '';
            }

            //se crea el código html necesario para las acciones de cada formulario laboratorio

            //cuando finaliza de manipular los datos, estos se guardar en un array.
            array_push($array['data'], $form->toArray());
        }
        //dd(count($array['data']));
        return $array;
    }


    static function getPermisos($modulo)
    {
        $user = Auth::user();
        $permisos_modulo = $user->hasMany('App\ModuloUsuario', 'user_id')->where("modulo_id", $modulo)->where("user_id", $user->id)->first();
        if ($permisos_modulo != null) {
            return $permisos_modulo->permisos();
        } else {
            return ["super" => 0, "editar" => 0, "eliminar" => 0, "crear" => 0, "explorar" => 0];
        }

        return $permisos_modulo;
    }
}
