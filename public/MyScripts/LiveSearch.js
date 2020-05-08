$('#fecha_nacimiento').datepicker({
    language: "es",
    format: "dd/mm/yyyy",
    autoclose: true,
    endDate: new Date(),
    todayBtn: "linked",
    todayHighlight: true,
    clearBtn: true
});

$('.datepicker_laboratorio').datepicker({
    language: "es",
    format: "dd/mm/yyyy",
    startDate: "-1y",
    // startDate: getStartDate(document.getElementById('fecha_confirmacion')),
    autoclose: true,
    todayBtn: "linked",
    todayHighlight: true,
    clearBtn: true
});

$('.datepicker_establecimiento').datepicker({
    language: "es",
    format: "dd/mm/yyyy",
    autoclose: true,
    todayBtn: "linked",
    todayHighlight: true,
    clearBtn: true
});
$('.datepicker_busqueda').datepicker({
    language: "es",
    format: "dd/mm/yyyy",
    autoclose: true,
    todayBtn: "linked",
    todayHighlight: true,
    clearBtn: true
});

$('.datepicker_casocerrado').datepicker({
    language: "es",
    format: "dd/mm/yyyy",
    autoclose: true,
    todayBtn: "linked",
    todayHighlight: true,
    clearBtn: true
});

$('.datepicker_isp').datepicker({
    language: "es",
    format: "dd/mm/yyyy",
    autoclose: true,
    todayBtn: "linked",
    todayHighlight: true,
    clearBtn: true
});

function getStartDate(fecha) {
    if (fecha != null) {
        fecha = fecha.value.split(' ')[0];
        return fecha;
    }
}
function fechaChange(id) {

    if (id.includes('_s')) {
        segunda = '_s';
    } else {
        segunda = '';
    }
    f1 = document.getElementById(id);//Obtiene el id del input fecha
    // console.log(id);

    // console.log("valor f1 :"+f1);
    inicio = f1.id.split('_')[2];//Obtiene el indicador numerico presente en el id para saber en que datepicker (raíz) se realiza el cambio
    valor = f1.value.split(' ')[0];//Obtiene el valor (fecha) presente en el input

    if (valor != '') {
        //fecha = new Date(valor);// crea un nuevo objeto Date con el valor obtenido.
        //fecha = fecha.toISOString().slice(0, 10); // Transforma la fecha al formato dd/mm/yyyy
        fecha = valor;
        if (id.includes('fecha_edit')) {

            for (var i = inicio; i < 8; i++) {
                //Se pretende iterar a traves de todas las fecha presentes en el input
                //primero pregunta si es que el input donde se obtuvo la fecha inicial es distinta a la que está en interación
                //si es así, la destruye, esto con el fin de iniciarla nuevamente con la fecha de inicia no mayor al anterior.
                if (('fecha_edit_' + inicio + segunda) != ('fecha_edit_' + i + segunda)) {
                    $('#fecha_edit_' + i + segunda).datepicker('destroy');
                }
                // Se inicializan nuevamente todas los datepicker, a apartir del datepicker raíz, seteando como fecha mínima la presente en el raíz.
                $('#fecha_edit_' + i + segunda).datepicker({
                    language: "es",
                    format: "dd/mm/yyyy",
                    autoclose: true,
                    startDate: fecha,
                    todayBtn: "linked",
                    todayHighlight: true,
                    clearBtn: true
                });
            }

        } else {
            for (var i = inicio; i < 8; i++) {
                if (('fecha_add_' + inicio + segunda) != ('fecha_add_' + i + segunda)) {
                    $('#fecha_add_' + i + segunda).datepicker('destroy');
                }
                $('#fecha_add_' + i + segunda).datepicker({
                    language: "es",
                    format: "dd/mm/yyyy",
                    autoclose: true,
                    startDate: fecha,
                    todayBtn: "linked",
                    todayHighlight: true,
                    clearBtn: true
                });
            }
        }
    }

}

$('input[name ="tipo_identificacion"]').change(function (e) {

    //Valor --> 1: RUT - 2: RUT PROVISORIO - 3: PASAPORTE - 4: INDOCUMENTADO
    //Cuando se cambia el select tipo_identificacion, se obtiene el calor seleccionado y las opciones del select de codigo VIH
    var opciones = document.getElementById('tipo_codigo');
    var valor = e.currentTarget.value;
    //Se llama la funcion tipoCodigoDes que segun el valor seleccionado desactiva las opciones correspondientes
    //Ej: si el valor corresponde a RUT, solo dejará habilitadas las opciones correspondientes a pacientes chilenos.
    tipoCodigoDes(valor, opciones);

    //Segun el valor se muestra el div con el input correspondiente
    if (valor == '1') {
        $('#rut_provisorio_div').fadeOut('slow', function () {
            $('#div_pasaporte').fadeOut('slow', function () {
                $('#rut_div').fadeIn('slow', function () { });
            });
        });
    }
    if (valor == '2') {
        $('#rut_div').fadeOut('slow', function () {
            $('#div_pasaporte').fadeOut('slow', function () {
                $('#rut_provisorio_div').fadeIn('slow', function () { });
            });
        });
    }
    if (valor == '3') {
        $('#rut_div').fadeOut('slow', function () {
            $('#rut_provisorio_div').fadeOut('slow', function () {
                $('#div_pasaporte').fadeIn('slow', function () { });
            });
        });
    }
    if (valor == '4') {
        $('#rut_div').fadeOut('slow', function () {
            $('#rut_provisorio_div').fadeOut('slow', function () {
                $('#div_pasaporte').fadeOut('slow', function () { });
            });
        });
    }
});

$('input[name ="extranjero"]').change(function (e) {
    var valor = e.currentTarget.value;
    //En caso de que sea esxtranjero, se muestra div nacionalidad y se bloquean los radio button correspondientes
    if (valor == '1') {
        $('#div_nacionalidad').fadeIn({
            duration: 100
        });
        rut_r = document.getElementById('rut_provisorio_r');
        pas_r = document.getElementById('pasaporte_r');
        indoc_r = document.getElementById('indoc_r');

        rut_r.disabled = false;
        pas_r.disabled = false;
        indoc_r.disabled = false;
    } else {
        //En caso de que no sea esxtranjero, se esconden div nacionalidad y pasaporte y se bloquean los radio button correspondientes
        $('#div_nacionalidad').fadeOut({
            duration: 300
        });
        $('#div_pasaporte').fadeOut({
            duration: 300
        });

        rut_r = document.getElementById('rut_r');
        rutp_r = document.getElementById('rut_provisorio_r');
        pas_r = document.getElementById('pasaporte_r');
        indoc_r = document.getElementById('indoc_r');

        rut_r.checked = true;
        // rut_r.disabled = false;
        //desmarca y deshabilita los radio button
        rutp_r.checked = false;
        rutp_r.disabled = true;
        pas_r.checked = false;
        pas_r.disabled = true;
        indoc_r.checked = false;
        indoc_r.disabled = true;

        var opciones = document.getElementById('tipo_codigo');
        tipoCodigoDes(1, opciones)

        $('#rut_provisorio_div').fadeOut('slow', function () {
            $('#div_pasaporte').fadeOut('slow', function () {
                $('#rut_div').fadeIn('slow', function () { });
            });
        });
    }
});

$('input[name ="pueblo"]').change(function (e) {
    var valor = e.currentTarget.value;
    if (valor == '1') {
        //si pertenece a algun prueblo originario muestra el select de los pueblos
        $('#div_cual').fadeIn({
            duration: 100
        });

        if ($('#pueblos_originarios').val() == 10) {
            $('#div_cual_especificar').fadeIn({
                duration: 100
            });
        }
    } else {
        //en caso de que no pertenezca a pueblo originario, esconde los divs si es que están abiertos
        $('#div_cual').fadeOut({
            duration: 100
        });
        $('#div_cual_especificar').fadeOut({
            duration: 100
        });
    }
});

$('#pueblos_originarios').change(function (e) {
    if (e.currentTarget.value == 10) {
        // si es que se selecciona otro pueblo, se muestra div que contiene input para texto libre
        $('#div_cual_especificar').fadeIn({
            duration: 100
        });
    } else {
        //en caso contrario esconde los divs
        $('#div_cual_especificar').fadeOut({
            duration: 100
        });
    }
});

$('#razones_solicitud').change(function (e) {
    //cuando se cambia el selec de razones solicitud, obtiene el valor seleccionado
    var valores = $('#razones_solicitud').val();
    //en caso de que contenga o sea 19
    if (valores.includes("19")) {
        $('#razones_solicitud').val(['19']);
        //esconde los div que esten habilitados y muestra el correspondiente a otra razon
        $('#div_razon_gestantes').fadeOut('slow', function () {
            $('#div_segunda_muestra').fadeOut('slow', function () {
                $('#div_razon_otro').fadeIn('slow', function () { });
            });

        });

    } else if (valores.includes("10")) {
        //mismo caso que en el anterior
        $('#div_razon_otro').fadeOut('slow', function () {
            $('#div_segunda_muestra').fadeOut('slow', function () {
                $('#div_razon_gestantes').fadeIn('slow', function () { });
            });

        });

    } else if (valores.includes("17")) {
        //en caso de que sea 17, consulta la base de datos por los formularios
        url_base = document.getElementById('url_base').value;
        $.ajax({
            type: 'GET',
            url: url_base + '/FormulariosSolicitud/Relacionados/' + $('#codigo_vih').val(),
            success: function (data) {
                //obtiene el select de solicitudes con resultado ISP indeterminadas
                select = document.getElementById('segunda_muestra');
                var length = select.options.length;
                //Itera sobre los elementos del select y los eliminar (Si es que posee elementos/opciones)
                for (i = length - 1; i >= 0; i--) {
                    select.remove(i);
                }
                //Se itera sobre los elementos del json obtenido "data"
                for (i = 0; i < data.length; i++) {
                    //crea un objeto del tipo "option"
                    var opt = document.createElement("option");
                    //se agrega como value el id del dormulario solicitud a la opcion
                    opt.value = data[i].formulario_solicitud_id;
                    //se agrega texto (que se mostrará en el select) a la opcion
                    opt.innerHTML = data[i].codigo_vih;
                    //finalmente agregamos la opcion previamente construida al select
                    select.appendChild(opt);
                }

            }
        });


        $('#div_razon_otro').fadeOut('slow', function () {
            $('#div_razon_gestantes').fadeOut('slow', function () {
                $('#div_segunda_muestra').fadeIn('slow', function () {
                });
            });
        });

    } else {
        $('#div_razon_otro').fadeOut('slow', function () {
            $('#div_segunda_muestra').fadeOut('slow', function () {
                $('#div_razon_gestantes').fadeOut('slow', function () { });
            });

        });
    }
});


function dgv(T)    //digito verificador
{
    var M = 0, S = 1;
    for (; T; T = Math.floor(T / 10))
        S = (S + T % 10 * (9 - M++ % 6)) % 11;
    return S ? S - 1 : 'k';

}

$('#tipo_codigo').change(function (e) {
    //obtiene el valor de la opcion seleccionada
    var valor = e.currentTarget.value;
    //obtiene los parametros para construir el codigo VIH
    nombre = $("#nombres").val();
    apPaterno = $("#apellido_paterno").val();
    apMaterno = $("#apellido_materno").val();
    fecha_nacimiento = $("#fecha_nacimiento").val().split("/");
    rut = $("#rut").val();
    rut_ultimos = rut.slice(-3);
    digito_verifi = dgv(rut);

    //Esconde el div con los datos de la Madre/Padre en caso de que esté abierto
    $('#div_madre').fadeOut('slow', function () { });


    if (valor != '') {
        if (valor == '6' || valor == '7') {
            rut = 'sin rut';
        }
        //En caso de que el valor sea distinto de nulo, evalua si los parametros para construir el codigo VIH son vacios
        if (nombre == '' || apPaterno == '' || apMaterno == '' || fecha_nacimiento == '' || rut == '') {
            //Muestra alerta en caso de que sean vacios
            toastr.warning('Debe llenar los datos básicos del paciente para seleccionar código VIH');
        } else {
            //Construye el codigo VIH segun las reglas entregadas por perosnal HSJDD
            if (valor == '1') {
                if (apMaterno == '') {
                    apMaterno = '#';
                }
                codigo_vih = nombre[0] + apPaterno[0] + apMaterno[0] + fecha_nacimiento[0] + fecha_nacimiento[1] + fecha_nacimiento[2].slice(-2) + rut_ultimos + '-' + digito_verifi;
                $("#codigo_vih").val(codigo_vih);
            } else if (valor == '2') {
                $('#div_madre').fadeIn('slow', function () { });
                $(".madre").trigger('change');

            } else if (valor == '3') {
                $('#div_madre').fadeIn('slow', function () { });
                $(".madre").trigger('change');

            } else if (valor == '4') {
                codigo_vih = nombre[0] + apPaterno[0] + '#' + fecha_nacimiento[0] + fecha_nacimiento[1] + fecha_nacimiento[2].slice(-2) + rut_ultimos + '-' + digito_verifi;
                $("#codigo_vih").val(codigo_vih);
            } else if (valor == '5') {
                if (apMaterno == '') {
                    apMaterno = '#';
                }
                codigo_vih = nombre[0] + apPaterno[0] + apMaterno[0] + fecha_nacimiento[0] + fecha_nacimiento[1] + fecha_nacimiento[2].slice(-2) + rut_ultimos + '-' + digito_verifi;
                $("#codigo_vih").val(codigo_vih);
            } else if (valor == '6') {
                if (apMaterno == '') {
                    apMaterno = '#';
                }
                codigo_vih = nombre[0] + apPaterno[0] + apMaterno[0] + fecha_nacimiento[0] + fecha_nacimiento[1] + fecha_nacimiento[2].slice(-2) + 'ABC-D';
                $("#codigo_vih").val(codigo_vih);
            }
            else if (valor == '7') {
                codigo_vih = nombre[0] + apPaterno[0] + '#' + fecha_nacimiento[0] + fecha_nacimiento[1] + fecha_nacimiento[2].slice(-2) + 'ABC-D';
                $("#codigo_vih").val(codigo_vih);
            }
        }
    }

});



$(".d_nombres").change(function (e) {
    //setea el select de codigo VIH en "cero" o "vacio" cada vez que se cambie un valor de los datos del paciente
    $("#tipo_codigo").val('').trigger('change')
    document.getElementById("codigo_vih").value = '';
});

$(".madre").change(function (e) {
    // Cada vez que cambie un valor de los datos de la Madre/Padre obtiene el Valor seleccionado y los parametros del Madre/Padre
    valor = $('#tipo_codigo').val();
    nombre = document.getElementById('nombre_pm').value[0];
    ap1 = document.getElementById('apellido_p_pm').value[0];
    ap2 = document.getElementById('apellido_m_pm').value[0];
    fecha_nacimiento = $("#fecha_nacimiento").val().split("/");
    $rut = $("#rut").val();
    rut_ultimos = $rut.slice(-3);
    digito_verifi = dgv($rut);
    //Cuando todos los campos están llenos (todos distintos de null) construye el codigo VIH segun las reglas.
    if (nombre != null && ap1 != null && ap2 != null) {
        if (valor == '2') {
            codigo_vih = 'RN/' + nombre + ap1 + ap2 + fecha_nacimiento[0] + fecha_nacimiento[1] + fecha_nacimiento[2].slice(-2);
            $("#codigo_vih").val(codigo_vih);
        } else if (valor == '3') {
            codigo_vih = 'RN/' + nombre + ap1 + ap2 + fecha_nacimiento[0] + fecha_nacimiento[1] + fecha_nacimiento[2].slice(-2) + 'G1; ';
            codigo_vih += 'RN/' + nombre + ap1 + ap2 + fecha_nacimiento[0] + fecha_nacimiento[1] + fecha_nacimiento[2].slice(-2) + 'G2';
            $("#codigo_vih").val(codigo_vih);
        }
    }
});



function consultar(d1) {
    //obtiene el rut que se haya ingresado en el input
    var rut = $('#rut').val();
    var d = d1.split(';');
    //obtiene las direcciones correspondientes a las que consultan en la BD y Fonasa
    d[0] = d[0].replace('rutaca', rut);
    d[1] = d[1].replace('rutaca', rut);

    //si el rut no es vacio entre al if
    if (rut != '') {
        //primeramente se busca en la BD mediante ajax al paciente segun el rut que se haya ingresado
        $.ajax({
            type: 'GET',
            url: d[0],
            success: function (data) {
                //si el objeto retornado es del tipo string, quiere decir que no encontró datos (debería retornar JSON)
                if (typeof data === 'string') {
                    //segun el mensaje que esté en data se muestran 2 alertas distintas, estos mensajes se definen en el controlador
                    if (data == 'No se encuentra este paciente') {
                        toastr.info('No se encontró paciente en los registros, Buscando en Fonasa');
                    } else {
                        toastr.info('Base de datos inaccesible,  Buscando en Fonasa');
                    }
                    //luego, como no se encuentran datos en la BD se procede a buscarlos en Fonasa
                    $.ajax({
                        type: 'GET',
                        url: d[1],
                        success: function (data) {
                            //segun el mensaje que esté en data se muestran 2 alertas distintas, estos mensajes se definen en el controlador
                            if (data == "error conexion fonasa") {
                                toastr.warning('No es posible conectar con Fonasa');
                            } else if (data == "Rut no existe en la Base Datos.") {
                                toastr.warning('No existe el paciente en los registros de Fonasa');
                            } else {
                                //en caso de que no sea ninguno de los casos anteriores quiere decir que encontró al paciente
                                //y se procede a la asignacion de las datos a los inputs del formulario y se muestra la alerta
                                datos_afiliado = data['RESPUESTA_ORIGINAL']['afiliadoTO'];
                                resp_original = data['RESPUESTA_ORIGINAL'];
                                console.log(data);
                                if (datos_afiliado['tramo'] != ' ') {
                                    prevision = 'FONASA';
                                    tramo = datos_afiliado['tramo'];
                                    prais = resp_original['descprais'];
                                } else {
                                    prevision = 'ISAPRE';
                                    tramo = '';
                                    prais = resp_original['descprais'];
                                }
                                $("#prevision").val(prevision);
                                $("#tramo").val(tramo);
                                $("#prais").val(prais);
                                toastr.success('Paciente encontrado!');
                                $("#nombres").val(data['nombres']);
                                $("#apellido_paterno").val(data['apellido_paterno']);
                                $("#apellido_materno").val(data['apellido_materno']);
                                $masculino = document.getElementById("masculino");
                                $femenino = document.getElementById("femenino");
                                $trans = document.getElementById("trans");

                                $masculino.checked = false;
                                $femenino.checked = false;
                                $trans.checked = false;
                                if (data['genero'] == "M") {
                                    $masculino.checked = true;
                                } else {
                                    $femenino.checked = true;
                                }
                                // $("#edad").val(data['edad']);
                                $("#fecha_nacimiento").val(data['fecha_nacimiento'].split('-').reverse().join('/'));
                                $("#fecha_nacimiento").trigger('change');
                                $("#telefono").val(data['telefono']);
                                $("#direccion").val(data['direccion']);

                                document.getElementById("in_comuna").value = data['des_comuna'];
                                document.getElementById("tx_comuna").value = data['des_comuna'];
                                $("#comuna").select2("trigger", "select", {
                                    data: { id: data['comuna'], text: data['des_comuna'] }
                                });
                                document.getElementById("cod_comuna").value = data['comuna'];

                                $("#tipo_codigo").val('').trigger('change')
                                document.getElementById("codigo_vih").value = '';

                                document.getElementById('nombres').readOnly = true;
                                document.getElementById('apellido_paterno').readOnly = true;
                                document.getElementById('apellido_materno').readOnly = true;
                                document.getElementById('fecha_nacimiento').readOnly = true;
                                document.getElementById('edad').readOnly = true;

                            }

                        }
                    });
                } else {
                    //en caso de que no sea del tipo string, quiere decir que encontro los datos y se procede a la asignacion
                    // con los datos de la BD y se muestra la alerta correspondiente.
                    toastr.success('Paciente encontrado!');
                    edad = data['edad'];
                    comuna = data['desc_comuna'];
                    cod_comuna = data['cod_comuna'];
                    data = data[0];
                    $("#nombres").val(data['nombre']);
                    $("#apellido_paterno").val(data['apMaterno']);
                    $("#apellido_materno").val(data['apMaterno']);

                    $masculino = document.getElementById("masculino");
                    $femenino = document.getElementById("femenino");
                    $trans = document.getElementById("trans");

                    $masculino.checked = false;
                    $femenino.checked = false;
                    $trans.checked = false;
                    if (data['genero_id'] == 1) {
                        $masculino.checked = true;
                    } else {
                        $femenino.checked = true;
                    }
                    // $("#edad").val(edad);
                    $("#fecha_nacimiento").val(data['fechaNacimiento'].split('-').reverse().join('/'));
                    $("#fecha_nacimiento").trigger('change');
                    $("#telefono").val(data['telefono']);
                    $("#cod_comuna").val(data['comuna_id']);
                    $("#direccion").val(data['direccion'] + ' ' + data['numero']);
                    $("#numero_direccion").val(data['numero']);
                    $("#paciente").val(data['id']);


                    document.getElementById("in_comuna").value = comuna;
                    document.getElementById("tx_comuna").value = comuna;
                    $("#comuna").select2("trigger", "select", {
                        data: { id: cod_comuna, text: comuna }
                    });
                    document.getElementById("cod_comuna").value = cod_comuna;

                    $("#tipo_codigo").val('').trigger('change')
                    document.getElementById("codigo_vih").value = '';

                    document.getElementById('nombres').readOnly = true;
                    document.getElementById('apellido_paterno').readOnly = true;
                    document.getElementById('apellido_materno').readOnly = true;
                    document.getElementById('fecha_nacimiento').readOnly = true;
                    document.getElementById('edad').readOnly = true;

                }
            },
            error: function ($e) {
            }
        });
    }
}


function cambiarComuna() {
    $('#div_in_comuna').fadeOut('slow', function () {
        $('#sel_comuna').fadeIn({
            duration: 100
        });
    });
}

function cambiarEstablecimiento() {
    $('#select2-establecimiento-container').text('');
    document.getElementById('cod_establecimiento').value = '';
    $('#establecimiento').val('');

    $('#div_sjdd').fadeOut('slow', function () {
        $('#div_nsjdd').fadeIn({
            duration: 100
        });
    });
}

function calcularDV(rut) {
    var i, j, suma;
    suma = 0;
    for (i = j = rut.length - 1; j >= 0; i = j += -1) {
        suma = suma + rut.charAt(i) * (2 + (rut.length - 1 - i) % 6);
    }
    suma = 11 - suma % 11;
    switch (suma) {
        case 10:
            return 'K';
        case 11:
            return 0;
        default:
            return suma;
    }
}

function formatearRut(run) {
    var cdv, dv, noFormat, olen, position, rut, withFormat;

    olen = run.length;
    noFormat = run.replace(/[^0-9kK]/g, "").toUpperCase();
    dv = 'K';
    rut = noFormat.substring(0, noFormat.length - 1);
    withFormat = '';
    while (rut.length > 3) {
        withFormat = "." + rut.substr(rut.length - 3) + withFormat;
        rut = rut.substring(0, rut.length - 3);
    }
    withFormat = rut + withFormat + "-" + dv.toUpperCase();
    if (noFormat.length > 1) {
        run = withFormat;
    } else {
        run = noFormat;
    }
    return run;
}

$("#comuna").change(function (e) {
    //cod_comuna se ocupa como "pivote" al momento de cambiar a seleccion de comuna, esto porque al momento de buscar los datos por el maestro pacientes
    //al llenar los datos de comuna, no se pudo inicializar automaricamente el select ajax en el valor determinado, por lo tanto se inicia como input
    document.getElementById("cod_comuna").value = e.target.value;
    try {
        document.getElementById("tx_comuna").value = e.target.options[e.target.options.selectedIndex].text;
    } catch (error) {
        console.log(error);
    }
});

$("#rut").change(function (e) {
    document.getElementById('nombres').readOnly = false;
    document.getElementById('apellido_paterno').readOnly = false;
    document.getElementById('apellido_materno').readOnly = false;
    document.getElementById('fecha_nacimiento').readOnly = false;
});

$("#establecimiento").change(function (e) {
    cod = e.target.value;
    des = e.target.options[e.target.options.selectedIndex].text;
    document.getElementById("cod_establecimiento").value = cod;
    document.getElementById("tx_establecimiento").value = des;
    if (cod == '570') {
        document.getElementById("in_establecimiento").value = des;
    }

    // console.log('---', e.target.options[e.target.options.selectedIndex].text);
    if (e.target.value == '570') {
        $('#div_nsjdd').fadeOut('slow', function () {
            $('#div_sjdd').fadeIn({
                duration: 100
            });
        });
    } else {
        $('#div_sjdd').fadeOut('slow', function () {
            $('#div_nsjdd').fadeIn({
                duration: 100
            });
        });

    }
});

$("#nacionalidad").change(function (e) {
    cod = e.target.value;
    des = e.target.options[e.target.options.selectedIndex].text;
    document.getElementById("cod_nacionalidad").value = cod;
    document.getElementById("tx_nacionalidad").value = des;

});

$("#procedencia_sel").change(function (e) {
    document.getElementById("cd_servicio").value = e.target.value;
});

$("#fecha_nacimiento").change(function (e) {
    fecha = e.target.value;
    fecha = fecha.split('/').reverse().join('-');
    $("#edad").val(calcularEdad(fecha));
});

$(".aMayuscula").keyup(function (e) {
    e.target.value = e.target.value.toUpperCase();
});

$("#fecha_nacimiento").change(function (e) {
    fecha = e.target.value;
    fecha = fecha.split('/').reverse().join('-');
    $("#edad").val(calcularEdad(fecha));
});

function calcularEdad(fecha) {
    var nacimiento = new Date(fecha);
    // nacimiento.setDate(nacimiento.getDate()+1);
    var actual = new Date();
    var diff = actual - nacimiento; // This is the difference in milliseconds
    var edad = Math.floor(diff / 31557600000); // Divide by 1000*60*60*24*365.25
    return edad;
}

function tipoCodigoDes(valor, opciones) {
    if (valor == 1) {
        opciones.options[1].disabled = false;
        opciones.options[2].disabled = false;
        opciones.options[3].disabled = false;
        opciones.options[4].disabled = false;
        opciones.options[5].disabled = false;
        opciones.options[6].disabled = true;
        opciones.options[7].disabled = true;
    } else {
        opciones.options[1].disabled = true;
        opciones.options[2].disabled = true;
        opciones.options[3].disabled = true;
        opciones.options[4].disabled = true;
        opciones.options[5].disabled = true;
        opciones.options[6].disabled = false;
        opciones.options[7].disabled = false;
    }
}

function setearInputCerrar(selector) {
    var bcv = $(document.activeElement)[0].value;
    if (bcv == 'editar') {
        document.getElementById(selector).value = 'no';
    } else {
        document.getElementById(selector).value = 'si';
    }
}

//La logica de los siguientes funciones para bloquear los botones cerrar formulario es bastante similar, en algunas se agregan mas condicones
// pero siguen conservando la msima logica, es por esto que solo se comenta la primera funcion (bloquearBotonFormBus)

function bloquearBotonFormBus(tipo) {
    //evalua si la pestaña add está activa, en caso de que lo esté guarda true en "add", en caso contrario
    //guarda false (con este valor se asume que la pestaña editar está activa)
    add = document.getElementById('custom-tabs-two-ingresar-tab').getAttribute('aria-selected');
    //obtiene el estado del formulario en cuestion, si es uno está cerrado, en caso contrario no lo está
    estado = document.getElementById('estado').value;
    //obtiene las inputs necesarios para evaluar las condiciones de cierre de formulario, estos se obtienen mediante los id de los inputs
    //concadenando el tipo ingresado en la funcion
    var c1 = document.getElementById('fecha_' + tipo + '_1').value;
    var c2 = document.getElementById('fecha_' + tipo + '_2').value;
    var c3 = document.getElementById('fecha_' + tipo + '_3').value;
    var c4 = document.getElementById('fecha_' + tipo + '_4').value;
    var c5 = document.getElementById('fecha_' + tipo + '_5').value;
    //en caso de que sea el formulario add (tipo) y esté activa la pestaña add, y el estado sea disitnto de 1
    if (tipo == "add" && add == "true" && estado != 1) {
        //se evaluan que los inputs no sean nulos
        if ((c1 != '') && (c2 != '') && (c3 != '') && (c4 != '') && (c5 != '')) {
            document.getElementById('boton_cerrar_' + tipo).disabled = false;
        } else {
            //en caso de que ninguno sea nulo (todos llenos), activa el boton cerrar formulario
            document.getElementById('boton_cerrar_' + tipo).disabled = true;
        }
    }
    //mismo procedimiento para el if anterior pero para el caso en que sea edit
    if (tipo == "edit" && add != "true" && estado != 1) {
        if ((c1 != '') && (c2 != '') && (c3 != '') && (c4 != '') && (c5 != '')) {
            document.getElementById('boton_cerrar_' + tipo).disabled = false;
        } else {
            document.getElementById('boton_cerrar_' + tipo).disabled = true;
        }
    }
}

function bloquearBotonFormBusSSMOC(tipo) {
    add = document.getElementById('extraCall-ingresar-tab').getAttribute('aria-selected');
    estado = document.getElementById('estado').value;
    var c1 = document.getElementById('fecha_' + tipo + '_1_ssmoc').value;
    var c2 = document.getElementById('fecha_' + tipo + '_2_ssmoc').value;
    var c3 = document.getElementById('fecha_' + tipo + '_3_ssmoc').value;
    var c4 = document.getElementById('fecha_' + tipo + '_4_ssmoc').value;
    var c5 = document.getElementById('fecha_' + tipo + '_5_ssmoc').value;
    var c6 = document.getElementById('fecha_' + tipo + '_6_ssmoc').value;
    if (tipo == "add" && add == "true" && estado != 1) {
        if (((c1 != '') && (c2 != '') && (c3 != '') && (c4 != '') && (c5 != '')) || (c6 != '')) {
            document.getElementById('boton_cerrar_' + tipo).disabled = false;
        } else {
            document.getElementById('boton_cerrar_' + tipo).disabled = true;
        }
    }
    if (tipo == "edit" && add != "true" && estado != 1) {
        if (((c1 != '') && (c2 != '') && (c3 != '') && (c4 != '') && (c5 != '')) || (c6 != '')) {
            document.getElementById('boton_cerrar_' + tipo).disabled = false;
        } else {
            document.getElementById('boton_cerrar_' + tipo).disabled = true;
        }
    }
}

function bloquearBotonFormEst(tipo) {
    nombre_rol = document.getElementById('nombre_rol').value;
    add = document.getElementById('custom-tabs-two-ingresar-tab').getAttribute('aria-selected');
    estado = document.getElementById('estado').value;
    var lv = document.getElementById('lugar_vinculacion_' + tipo).value;
    var ef = document.getElementById('estado_formulario').value;
    var c1 = document.getElementById('fecha_' + tipo + '_1').value;
    var c2 = document.getElementById('fecha_' + tipo + '_2').value;
    var c3 = document.getElementById('fecha_' + tipo + '_3').value;

    if (ef == 'sin_estado') {
        if (lv != '570') {
            if (tipo == "add" && add == "true" && estado != 1) {
                if ((c1 != '') && (lv != '')) {
                    document.getElementById('boton_cerrar_' + tipo).disabled = false;
                } else {
                    document.getElementById('boton_cerrar_' + tipo).disabled = true;
                }
            }

            if (tipo == "edit" && add != "true" && estado != 1) {
                if ((c1 != '') && (lv != '')) {
                    document.getElementById('boton_cerrar_' + tipo).disabled = false;
                } else {
                    document.getElementById('boton_cerrar_' + tipo).disabled = true;
                }
            }
        } else {
            if (tipo == "add" && add == "true" && estado != 1) {
                if ((c1 != '') && (c2 != '') && (c3 != '')) {
                    document.getElementById('boton_cerrar_' + tipo).disabled = false;
                } else {
                    document.getElementById('boton_cerrar_' + tipo).disabled = true;
                }
            }

            if (tipo == "edit" && add != "true" && estado != 1) {
                if ((c1 != '') && (c2 != '') && (c3 != '')) {
                    document.getElementById('boton_cerrar_' + tipo).disabled = false;
                } else {
                    document.getElementById('boton_cerrar_' + tipo).disabled = true;
                }
            }
        }
    }


}

function bloquearBotonFormLab(tipo, segunda) {
    resultado_sm = '';

    //segun el tipo ingresado a la function se obtiene el resultado de la primera y segunda muestra.
    // if (tipo == 'edit') {
    //     resultado = document.getElementById('resultado_examen_edit').value;
    //     if (resultado == "INDETERMINADO") {
    //         resultado_sm = document.getElementById('resultado_examen2_edit').value;
    //     }
    // } else {
    //     resultado = document.getElementById('resultado_examen_add').value;
    //     if (resultado == "INDETERMINADO") {
    //         resultado_sm = document.getElementById('resultado_examen2_add').value;
    //     }
    // }
    if (segunda == '_s') {
        add = document.getElementById('custom-tabs-two-ingresar-tab-s').getAttribute('aria-selected');
    } else {
        add = document.getElementById('custom-tabs-two-ingresar-tab').getAttribute('aria-selected');
    }

    estado = document.getElementById('estado').value;
    var c1 = document.getElementById('fecha_' + tipo + '_1' + segunda).value;
    var c2 = document.getElementById('fecha_' + tipo + '_7' + segunda).value;
    //Si es que el resultado de la primera muestra es indeterminado, pregunta por el estado de la segunda
    if (tipo == "add" && add == "true" && estado != 1) {
        if ((c1 != '')) {
            document.getElementById('boton_cerrar_' + tipo + segunda).disabled = false;
        } else {
            document.getElementById('boton_cerrar_' + tipo + segunda).disabled = true;
        }
    }
    if (tipo == "edit" && add != "true" && estado != 1) {
        if ((c1 != '')) {
            document.getElementById('boton_cerrar_' + tipo + segunda).disabled = false;
        } else {
            document.getElementById('boton_cerrar_' + tipo + segunda).disabled = true;
        }
    }

}


function bloquearBotonFormCace(tipo) {
    add = document.getElementById('custom-tabs-two-ingresar-tab').getAttribute('aria-selected');
    estado = document.getElementById('estado').value;
    var c1 = document.getElementById('fecha_caso_cerrado_' + tipo).value;
    var c2 = document.getElementById('responsable_cierre_' + tipo).value;
    var c3 = document.getElementById('causales_' + tipo).value;
    if (tipo == "add" && add == "true" && estado != 1) {
        if ((c1 != '') && (c2 != '') && (c3 != '')) {
            document.getElementById('boton_cerrar_' + tipo).disabled = false;
        } else {
            document.getElementById('boton_cerrar_' + tipo).disabled = true;
        }
    }
    if (tipo == "edit" && add != "true" && estado != 1) {
        if ((c1 != '') && (c2 != '') && (c3 != '')) {
            document.getElementById('boton_cerrar_' + tipo).disabled = false;
        } else {
            document.getElementById('boton_cerrar_' + tipo).disabled = true;
        }
    }
}

function bloquearBotonFormIsp(tipo,segunda) {
    if (segunda == '_s') {
        add = document.getElementById('custom-tabs-two-ingresar-tab-s').getAttribute('aria-selected');
        estado = document.getElementById('estado_s').value;
    } else {
        estado = document.getElementById('estado').value;
        add = document.getElementById('custom-tabs-two-ingresar-tab').getAttribute('aria-selected');
    }
    
    
    var c1 = document.getElementById('aniomes_' + tipo+segunda).value;
    var c2 = document.getElementById('numeroisp_' + tipo+segunda).value;
    var c3 = document.getElementById('fechaconfirmacion_' + tipo+segunda).value;
    var id_c = 'resultado_muestra_' + tipo+segunda;
    var c4 = '';
    radios = document.querySelector('input[name="'+id_c+'"]:checked');
    if(radios!=null){
        c4= radios.value;
    }
   try{
    if (tipo == "add" && add == "true" && estado != 1) {
        //console.log('boton_cerrar_' + tipo+segunda, add, estado);
        if ((c1 != '') && (c2 != '') && (c3 != '') && (c4 != '')) {
            document.getElementById('boton_cerrar_' + tipo+segunda).disabled = false;
        } else {
            document.getElementById('boton_cerrar_' + tipo+segunda).disabled = true;
        }
    }
    if (tipo == "edit" && add != "true" && estado != 1) {
        if ((c1 != '') && (c2 != '') && (c3 != '') && (c4 != '')) {
            document.getElementById('boton_cerrar_' + tipo+segunda).disabled = false;
        } else {
            document.getElementById('boton_cerrar_' + tipo+segunda).disabled = true;
        }
    }
   }catch(error){

   }

}

function spanErrores(error) {
    errores = error.responseJSON.errors;
    //obtiene los spans donde se muestran los errores de las validaciones y se setea el texto a vacio
    spans = document.getElementsByClassName("spanclass");
    for (let item in spans) {
        spans[item].innerHTML = "";
    }
    //itera sobre los errores de las validaciones
    for (let key in errores) {
        if (errores.hasOwnProperty(key)) {
            //si el elemento ya existe solo agrega el texto de error
            if (document.getElementById(key + "_span")) {
                var newEl = document.getElementById(key + "_span");
                newEl.innerHTML = errores[key];
            } else {
                //para cada error se crea un objeto span con id y clase para identificarlas, además de que sean color rojo y agrega el texto dle error
                var newEl = document.createElement('span');
                newEl.setAttribute("id", key + "_span");
                newEl.setAttribute("class", "spanclass " + key);
                newEl.setAttribute("style", "color:red");
                newEl.innerHTML = errores[key];
                var ref = document.getElementById(key);
                insertAfter(newEl, ref);
            }
        }
    }
}

function mensajesFormularios(data) {
    if (data == "guardado") {
        Swal.fire({
            type: 'success',
            title: 'Formulario Generado Exitosamente!',
            showConfirmButton: false,
            timer: 3500,
            onClose: function () {
                $('.modal').modal('hide');
                datatable.ajax.reload(null, false);
            },
        });
    }
    if (data == "actualizado") {
        Swal.fire({
            type: 'success',
            title: 'Formulario Acualizado Exitosamente!',
            showConfirmButton: false,
            timer: 3500,
            onClose: function () {
                $('.modal').modal('hide');
                datatable.ajax.reload(null, false);
            },
        });
    }
    if (data == "estado_cerrado") {
        Swal.fire({
            type: 'error',
            title: 'No se puede completar la acción, el formulario se encuentra cerrado!',
            showConfirmButton: false,
            timer: 3500,
            onClose: function () {
                $('.modal').modal('hide');
                datatable.ajax.reload(null, false);
            },
        });
    }
    if (data == "cerrar_segunda") {
        Swal.fire({
            type: 'error',
            title: 'Primero debe cerrar la segunda muestra asociada a este formulario!',
            showConfirmButton: false,
            timer: 3500,
            onClose: function() {
                $('.modal').modal('hide');
                datatable.ajax.reload(null, false);
            },
        });
    }
    if (data == "falta segunda") {
        Swal.fire({
            type: 'error',
            title: 'Para cerrar este formulario debes tener una 2° muestra cerrada!',
            showConfirmButton: false,
            timer: 3500,
            onClose: function() {
                $('.modal').modal('hide');
                datatable.ajax.reload(null, false);
            },
        });
    }
}

