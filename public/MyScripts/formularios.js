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

    subf= id.split(/([0-9]+)/)[2];
    f1 = document.getElementById(id);//Obtiene el id del input fecha
    inicio = f1.id.split('_')[2];//Obtiene el indicador numerico presente en el id para saber en que datepicker (raíz) se realiza el cambio
    valor = f1.value.split(' ')[0];//Obtiene el valor (fecha) presente en el input
    if (valor != '') {
        //fecha = new Date(valor);// crea un nuevo objeto Date con el valor obtenido.
        //fecha = fecha.toISOString().slice(0, 10); // Transforma la fecha al formato dd/mm/yyyy
        fecha = valor;
        if (id.includes('fecha_edit')) {

            //para los casos en que sea ssmoc en busqueda pacientes, obtiene la ultima fecha ingresada en aps (carta certificada)
            //esta fecha se guarda en ultima
            if(id.includes('ssmoc') && !id.includes('ssmoc_s')){
                ultima = document.getElementById('ultima').value;
            }
            if(id.includes('ssmoc_s')){
               
                ultima = document.getElementById('ultima_s').value;
            }
           
            for (var i = inicio; i < 8; i++) {
                //Se pretende iterar a traves de todas las fecha presentes en el input 
                //primero pregunta si es que el input donde se obtuvo la fecha inicial es distinta a la que está en interación
                //si es así, la destruye, esto con el fin de iniciarla nuevamente con la fecha de inicia no mayor al anterior.
                if (('fecha_edit_' + inicio + subf) != ('fecha_edit_' + i + subf)) {
                    $('#fecha_edit_' + i + subf).datepicker('destroy');
                }
                // Se inicializan nuevamente todas los datepicker, a apartir del datepicker raíz, seteando como fecha mínima la presente en el raíz.
                $('#fecha_edit_' + i + subf).datepicker({
                    language: "es",
                    format: "dd/mm/yyyy",
                    autoclose: true,
                    startDate: fecha,
                    todayBtn: "linked",
                    todayHighlight: true,
                    clearBtn: true
                });

                //solo para la primera iteracion y en el caso de que sea ssmoc en gestion busqueda, reinicializa los datepicker con fecha minima igual a la
                //almacenada en ultima
                if(i==1){
                    if(id.includes('ssmoc') && !id.includes('ssmoc_s')){
                        //destruye el datepicker generado arriba, que es para los otros formularios de gestión
                        $('#fecha_edit_' + i + subf).datepicker('destroy');
                        //inicia nuevamente el datepicker con 'utlima' como fecha de inicio
                        $('#fecha_edit_' + i + subf).datepicker({
                            language: "es",
                            format: "dd/mm/yyyy",
                            autoclose: true,
                            startDate: ultima,
                            todayBtn: "linked",
                            todayHighlight: true,
                            clearBtn: true
                        });
                    }
                    if(id.includes('ssmoc_s')){
                        //destruye el datepicker generado arriba, que es para los otros formularios de gestión
                        $('#fecha_edit_' + i + subf).datepicker('destroy');
                        //inicia nuevamente el datepicker con 'utlima' como fecha de inicio
                        $('#fecha_edit_' + i + subf).datepicker({
                            language: "es",
                            format: "dd/mm/yyyy",
                            autoclose: true,
                            startDate: ultima,
                            todayBtn: "linked",
                            todayHighlight: true,
                            clearBtn: true
                        });
                    }
                }

            }

        } else {
            for (var i = inicio; i < 8; i++) {
                if (('fecha_add_' + inicio + subf) != ('fecha_add_' + i + subf)) {
                    $('#fecha_add_' + i + subf).datepicker('destroy');
                }
                $('#fecha_add_' + i + subf).datepicker({
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
    }else{
        if(id.includes('ssmoc') && !id.includes('ssmoc_s')){
            ultima = document.getElementById('ultima').value;
            //destruye el datepicker generado arriba, que es para los otros formularios de gestión
            $('#fecha_add_1' + subf).datepicker('destroy');
            //inicia nuevamente el datepicker con 'utlima' como fecha de inicio
            $('#fecha_add_1' + subf).datepicker({
                language: "es",
                format: "dd/mm/yyyy",
                autoclose: true,
                startDate: ultima,
                todayBtn: "linked",
                todayHighlight: true,
                clearBtn: true
            });
        }
        if(id.includes('ssmoc_s')){
            ultima = document.getElementById('ultima_s').value;
            //destruye el datepicker generado arriba, que es para los otros formularios de gestión
            $('#fecha_add_1' + subf).datepicker('destroy');
            //inicia nuevamente el datepicker con 'utlima' como fecha de inicio
            $('#fecha_add_1' + subf).datepicker({
                language: "es",
                format: "dd/mm/yyyy",
                autoclose: true,
                startDate: ultima,
                todayBtn: "linked",
                todayHighlight: true,
                clearBtn: true
            });
        }
    }

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

function bloquearBotonFormBusSSMOC(tipo,segunda) {

   
    if(segunda=='_s'){
        add = document.getElementById('extraCall-ingresar-tab-s').getAttribute('aria-selected');
    }else{
        add = document.getElementById('extraCall-ingresar-tab').getAttribute('aria-selected');
    }
    estado = document.getElementById('estado').value;
    //obtiene los valores para cada campo de fecha presente en el formulario
    var c1 = document.getElementById('fecha_' + tipo + '_1_ssmoc'+segunda).value;
    var c2 = document.getElementById('fecha_' + tipo + '_2_ssmoc'+segunda).value;
    var c3 = document.getElementById('fecha_' + tipo + '_3_ssmoc'+segunda).value;
    var c4 = document.getElementById('fecha_' + tipo + '_4_ssmoc'+segunda).value;
    var c5 = document.getElementById('fecha_' + tipo + '_5_ssmoc'+segunda).value;
    var c6 = document.getElementById('fecha_' + tipo + '_6_ssmoc'+segunda).value;
    //define una varible vacio para ir concadenando los valors de los radio button
    var c7='';
    //itera sobre cada radio button efectivo
    console.log('boton_cerrar_' + tipo+segunda, tipo,segunda);
    for(var i = 1; i<=5;i++){
        //define variable donde se almacenará valor del radio button
        v='';
        //construye el nombre del radio button con los parametros asignados
        nombre = 'efectivo_'+tipo+'_'+i+'_ssmoc'+segunda;
        //obtiene el radio button por nombre
        radio = document.getElementsByName(nombre);
        // como el radio button posee 2 opciones, se debe iterar sobre ellas para evaluar el valor chequeado 
        for(var j = 0; j < radio.length; j++){
            //si el valor chequeado es true, lo concadeno con C7
            if(radio[j].checked){
                v = radio[j].value;
                c7 = c7 + v;
            }
        }
    }
    //en C7 si llega a existir al menos 'si', entonces se cumple condicion de cierre de formulario
    console.log(tipo, '-'+add, estado);
    if (tipo == "add" && add == "true" && estado != 1) {
        //Para este if se evaluan 3 condiciones de cierre, que todas las fechas esten llenas, que al menos la fecha de fallecimiento esté llena
        // y que al menos un radio button efectivo sea 'si'.
        if (((c1 != '') && (c2 != '') && (c3 != '') && (c4 != '') && (c5 != '')) || (c6 != '') || (c7.includes('si'))) {
            document.getElementById('boton_cerrar_' + tipo+segunda).disabled = false;
        } else {
            document.getElementById('boton_cerrar_' + tipo+segunda).disabled = true;
        }
    }
    if (tipo == "edit" && add != "true" && estado != 1) {
        if (((c1 != '') && (c2 != '') && (c3 != '') && (c4 != '') && (c5 != '')) || (c6 != '') || (c7.includes('si'))) {
            document.getElementById('boton_cerrar_' + tipo+segunda).disabled = false;
        } else {
            document.getElementById('boton_cerrar_' + tipo+segunda).disabled = true;
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
            title: 'Formulario acualizado exitosamente!',
            showConfirmButton: false,
            timer: 3500,
            onClose: function () {
                $('.modal').modal('hide');
                datatable.ajax.reload(null, false);
            },
        });
    }

    if (data == "formulario_cerrado") {
        Swal.fire({
            type: 'success',
            title: 'Formulario cerrado exitosamente!',
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
            title: 'Primero debe cerrar la segunda muestra asociada a este formulario!',
            showConfirmButton: false,
            timer: 3500,
            onClose: function() {
                $('.modal').modal('hide');
                datatable.ajax.reload(null, false);
            },
        });
    }
    if (data == "guardado_ssmoc") {
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
    if (data == "no-permitido") {
        Swal.fire({
            type: 'error',
            title: 'Debe llenar todos los campos para cerrar formulario!',
            showConfirmButton: false,
            timer: 3500,
            onClose: function() {
                $('.modal').modal('hide');
                datatable.ajax.reload(null, false);
            },
        });
    }
    if (data == "establecimiento_cerrado") {
        Swal.fire({
            type: 'error',
            title: 'No puede crear este fomrulario ya que Gestion Establecimiento Unidad Origen se encuentra cerrado!',
            showConfirmButton: false,
            timer: 3500,
            onClose: function() {
                $('.modal').modal('hide');
                datatable.ajax.reload(null, false);
            },
        });
    }
    

    
}
