$('#fecha_nacimiento').datepicker({
    language: "es",
    format: "dd/mm/yyyy",
    autoclose: true,
    endDate: new Date(),
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
    
    var rut_original = $('#run').val();
    rut = rut_original.split('-');
    rut = rut[0];
    var d = d1.split(';');
    d[0] = d[0].replace('rutaca', rut);
    d[1] = d[1].replace('rutaca', rut);

    // document.getElementById("formularioPaciente").reset();
    // $('#run').val(rut_original);
        console.log("busquedas "+JSON.stringify(d))
        if (rut != '') {
            // BUSCO EN BASE DATOS MAESTRO
            console.log("buscando en "+JSON.stringify(d[0]))
            $.ajax({
                type: 'GET',
                url: d[0],
                success: function (data) {
                    if (typeof data === 'string') {
                        if (data == 'No se encuentra este paciente') {
                            // toastr.info('No se encontró paciente en los registros');
                        } else {
                            toastr.info('Base de datos inaccesible,  Buscando en Fonasa');
                        }
                        //BUSCO EN BASE DATOS FONASA

                        console.log("buscando en "+JSON.stringify(d[1]));
                        $.ajax({
                            type: 'GET',
                            url: d[1],
                            success: function (data) {
                                
                                if (data == "error conexion fonasa") {
                                    toastr.warning('No es posible conectar con Fonasa');
                                }
                                else{
                                    if (data == "Rut no existe en la Base Datos.") {
                                        toastr.warning('No existe el paciente en los registros de Fonasa');
                                    }
                                    else {

// USUARIO ENCONTRADO en FONASA

$("#rut_existe").val(true);
toastr.success('Paciente encontrado!');
datos_afiliado = data['RESPUESTA_ORIGINAL']['afiliadoTO'];
resp_original = data['RESPUESTA_ORIGINAL'];
// console.log(data);

var pacienteFonasa={}


if (datos_afiliado['tramo'] != ' ') {
    pacienteFonasa.prevision = 'FONASA';
    pacienteFonasa.tramo = datos_afiliado['tramo'];
    pacienteFonasa.prais = resp_original['descprais'];
} else {
    pacienteFonasa.prevision = 'ISAPRE';
    pacienteFonasa.tramo = '';
    pacienteFonasa.prais = resp_original['descprais'];
}

// console.log(pacienteFonasa);

document.getElementById("formularioPaciente").reset();
$('#run').val(rut_original);

$("#nombre").val(data['nombres']);
$("#apPaterno").val(data['apellido_paterno']);
$("#apMaterno").val(data['apellido_materno']);
$("#fechaNacimiento").val(data['fecha_nacimiento'].split('-').reverse().join('-'));

// console.log(data['genero']);
// var genero_val

switch(data['genero']) {
    case "M":
        genero_val="1";
        break;
    case "F":
        genero_val="2";
        break;
    default:
        genero_val="3";
} 
// console.log(genero_val);


$("#genero option[value="+genero_val+"]").prop('selected',true).change();

//  $("#prevision option[value="+data['prevision_id']+"]").attr("selected",'select').change();

switch(pacienteFonasa.prevision) {
    case "FONASA":
        document.getElementById("prevision").options[1].selected=true;
        break;
    case "ISAPRE":
        console.log("aqui")
        document.getElementById("prevision").options[2].selected=true;
        break;
    default:
        document.getElementById("prevision").options[0].selected=true;
} 

switch( pacienteFonasa.tramo) {
    case 0:
        document.getElementById("tramo").options[5].selected=true;
        break;
    case "A":
        document.getElementById("tramo").options[1].selected=true;
        break;
    case "B":
        document.getElementById("tramo").options[2].selected=true;
        break;
    case "C":
        document.getElementById("tramo").options[3].selected=true;
        break;
    case "D":
        document.getElementById("tramo").options[4].selected=true;
        break;
    default:
        document.getElementById("tramo").options[5].selected=true;
} 

// // PRAIS VARIABLE SIN PARAMETRIZACION HAY Q REVISAR
// // FUNCIONARIO VARIABLE NO ALCANZADA EN FONASA


$("#comuna option[value="+data['comuna_id']+"]").prop('selected',true);


var direccion=document.getElementById("direccion");
direccion.value=data['direccion'];

// var numero=document.getElementById("numero");
// numero.value=data['numero'];

$("#telefono").val(data['telefono']);

if(data['telefono2']!=0 && data['telefono2']!=null){
    $("#telefono2").val(data['telefono2']);
}else{ $("#telefono2").val(null);}

if(data['email']!=0 && data['email']!=null){
    $("#email").val(data['email']);
}

// $("#active option[value="+data['active']+"]").prop('selected',true);

                                        // toastr.success('Paciente encontrado FONASA!');
                                        // datos_afiliado = data['RESPUESTA_ORIGINAL']['afiliadoTO'];
                                        // resp_original = data['RESPUESTA_ORIGINAL'];
                                        // console.log(data);
                                        // if (datos_afiliado['tramo'] != ' ') {
                                        //     prevision = 'FONASA';
                                        //     tramo = datos_afiliado['tramo'];
                                        //     prais = resp_original['descprais'];
                                        // } else {
                                        //     prevision = 'ISAPRE';
                                        //     tramo = '';
                                        //     prais = resp_original['descprais'];
                                        // }


                                        // $("#prevision").val(prevision);
                                        // $("#tramo").val(tramo);
                                        // $("#prais").val(prais);
                                        // toastr.success('Paciente encontrado!');
                                        // $("#nombres").val(data['nombres']);
                                        // $("#apellido_paterno").val(data['apellido_paterno']);
                                        // $("#apellido_materno").val(data['apellido_materno']);
                                        // $masculino = document.getElementById("masculino");
                                        // $femenino = document.getElementById("femenino");
                                        // $trans = document.getElementById("trans");
        
                                        // $masculino.checked = false;
                                        // $femenino.checked = false;
                                        // $trans.checked = false;
                                        // if (data['genero'] == "M") {
                                        //     $masculino.checked = true;
                                        // } else {
                                        //     $femenino.checked = true;
                                        // }
                                        // // $("#edad").val(data['edad']);
                                        // $("#fecha_nacimiento").val(data['fecha_nacimiento'].split('-').reverse().join('/'));
                                        // $("#fecha_nacimiento").trigger('change');
                                        // $("#telefono").val(data['telefono']);
                                        // $("#direccion").val(data['direccion']);
        
                                        // document.getElementById("in_comuna").value = data['des_comuna'];
                                        // document.getElementById("tx_comuna").value = data['des_comuna'];
                                        // $("#comuna").select2("trigger", "select", {
                                        //     data: { id: data['comuna'], text: data['des_comuna'] }
                                        // });
                                        // document.getElementById("cod_comuna").value = data['comuna'];
        
                                        // $("#tipo_codigo").val('').trigger('change')
                                        // document.getElementById("codigo_vih").value = '';
        
                                        // document.getElementById('nombres').readOnly = true;
                                        // document.getElementById('apellido_paterno').readOnly = true;
                                        // document.getElementById('apellido_materno').readOnly = true;
                                        // document.getElementById('fecha_nacimiento').readOnly = true;
                                        // document.getElementById('edad').readOnly = true;
                            // }
                                    }
                               
                                }
                            
                            },
                            error: function ($e) {
                                console.log("error en peticion ajax fonasa")
                            }
                    });
                    }else {
                    // USUARIO ENCONTRADO EN BASE DATOS MAESTRO
                    console.log("encontrado en maestra "+JSON.stringify(data))
                
                    $("#rut_existe").val(true);
                    toastr.success('Paciente encontrado!');
                    edad = data['edad'];
                    comuna = data['desc_comuna'];
                    cod_comuna = data['cod_comuna'];
                    data = data[0];

                    $("#nombre").val(data['nombre']);
                    $("#apPaterno").val(data['apPaterno']);
                    $("#apMaterno").val(data['apMaterno']);
                    $("#fechaNacimiento").val(data['fechaNacimiento'].split('-').reverse().join('-'));
                    
                    switch(data['genero_id']) {
                        case 1:
                            document.getElementById("genero").options[3].selected=true;
                            break;
                        case 2:
                            document.getElementById("genero").options[2].selected=true;
                            break;
                        default:
                            document.getElementById("genero").options[4].selected=true;
                    } 

                    $("#prevision option[value="+data['prevision_id']+"]").attr("selected",'select').change();
                    // document.getElementById("prevision").options[3].selected=true;
                    
                    switch(data['tramo_id']) {
                        case 0:
                            document.getElementById("tramo").options[5].selected=true;
                            break;
                        case 1:
                            document.getElementById("tramo").options[1].selected=true;
                            break;
                        case 2:
                            document.getElementById("tramo").options[2].selected=true;
                            break;
                        case 3:
                            document.getElementById("tramo").options[3].selected=true;
                            break;
                        case 4:
                            document.getElementById("tramo").options[4].selected=true;
                            break;
                        default:
                            document.getElementById("tramo").options[5].selected=true;
                    } 
                    
                    $("#prais option[value="+data['prais']+"]").attr('selected','select').change();
                    // console.log( $('#prais').val());

                    // $("#prais option[value="+data['des_comuna']+"]").attr('selected','select').change();
                    
                    if(data['funcionario']==1){
                            console.log("aqui");
                            $("#funcionario option[value=1]").prop('selected',true);
                    }else{
                        $("#funcionario option[value=0]").prop('selected',true);
                    }
                    
                    $("#comuna option[value="+data['comuna_id']+"]").prop('selected',true);
                    $("#via option[value="+data['via_id']+"]").prop('selected',true);
                    
                    var direccion=document.getElementById("direccion");
                    direccion.value=data['direccion'];
                    
                    var numero=document.getElementById("numero");
                    numero.value=data['numero'];

                    $("#telefono").val(data['telefono']);

                    if(data['telefono']!=0 && data['telefono2']!=null){
                        $("#telefono2").val(data['telefono2']);
                    }else{ $("#telefono2").val(null);}

                    if(data['email']!=0 && data['email']!=null){
                        $("#email").val(data['email']);
                    }

                $("#active option[value="+data['active']+"]").prop('selected',true);
    
                        // document.getElementById("in_comuna").value = comuna;
                        // document.getElementById("tx_comuna").value = comuna;
                        // $("#comuna").select2("trigger", "select", {
                        //     data: { id: cod_comuna, text: comuna }
                        // });
                        // document.getElementById("cod_comuna").value = cod_comuna;
    
                        // $("#tipo_codigo").val('').trigger('change')
                        // document.getElementById("codigo_vih").value = '';
    
                        // document.getElementById('nombres').readOnly = true;
                        // document.getElementById('apellido_paterno').readOnly = true;
                        // document.getElementById('apellido_materno').readOnly = true;
                        // document.getElementById('fecha_nacimiento').readOnly = true;
                        // document.getElementById('edad').readOnly = true;
    
                    }
                },
                error: function ($e) {
                    console.log("error en peticion ajax maestro")
                }
            });
        }
    
}

function consultar2(d1) {
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


