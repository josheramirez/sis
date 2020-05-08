@extends('layouts.app4')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Guardado o Actualización de Lista de Espera-->
	<?php $message=Session::get('message') ?>
	@if($message == 'create')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Lista Espera Creada Exitosamente
		</div>
	@elseif($message == 'store')
		<!-- control de cambios 007  -->
		<div class="alert alert-info fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Paciente Creado Exitosamente
		</div>
	@elseif($message == 'update')
		<!-- control de cambios 007  -->
		<div class="alert alert-info fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Paciente Editado Exitosamente
		</div>
	@elseif($message == 'paciente')
		<div class="alert alert-danger fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Paciente no existe
		</div>
	@elseif($message == 'cie10')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Diagnóstico (CIE-10) no Existe
		</div>
	@elseif($message == 'fecha_entrada')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Fecha de entrada fuera del rango permitido.
		</div>
	@elseif($message == 'fecha_nacimiento')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Fecha de entrada no debe ser menor que la fecha de nacimiento.
		</div>
	@elseif($message == 'fecha_citacion')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Fecha de citación es incorrecta.
		</div>
	@elseif($message == 'duplicado')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Ingreso lista de espera duplicado.
		</div>
	@elseif($message == 'cxmenor_nv')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			La cirugía menor solo puede ser ingresada para tipo de prestación "Intervención quirúrgica".
		</div>	
	@endif


	<!--FIN Mensajes de Guardado o Actualización de Lista de Espera-->
    <div class="row">
        <div class="col-md-12 col-lg-10 col-lg-offset-1">
			<!--Panel Formulario Crear Lista Espera-->
			<div class="panel panel-default">
                <div class="panel-heading">Ingreso Lista de Espera</div>
                <div class="panel-body">
	                <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/listaesperas/store') }}">
	                	{{ csrf_field() }}
						<!--Información del paciente-->
						<div class="row row-border">
							<div class="col-xs-10 col-sm-10 col-md-11">
								<h5>Información Paciente</h5>
							</div>
							<div class="col-xs-1 col-sm-1 col-md-1">
								<h5>
									<a href="#" class="pull-right"  data-toggle="collapse" data-target="#infoPaciente">
										<span class="glyphicon glyphicon-plus"></span>
									</a>
								</h5>
							</div>
						</div>
						<br>
						<div id="infoPaciente" class="collapse in">
							<div class="row row-padding row-border">
								<div class="col-xs-12 col-sm-6 col-md-6">
									<input id="paciente" type="text" class="form-control input-sm" name="paciente" value="{{ old('paciente') }}" placeholder="R.U.N. Paciente (Sin puntos ni dígito verificador)" required autofocus>
								</div>
								<div class="col-xs-12 col-sm-3 col-md-2 col-md-offset-2">
									<a class="btn btn-sm btn-primary pull-right" href="{{ URL::to('crear/pacientes/3') }}">Crear Paciente</a>
								</div>
								<div class="col-xs-12 col-sm-3 col-md-2">
									<a id="botonEdit" class="btn btn-sm btn-primary pull-right disabled">Editar Paciente</a>
								</div>
							</div>
							<div class="row row-padding">
								<!--Datos oculto con ID paciente-->
								<input id="id" type="hidden" class="form-control" name="id" value="{{ old('id') }}" hidden>

								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="nombre" class="control-label">Nombre</label>
									<input id="nombre" type="text" class="form-control input-sm" name="nombre" value="{{ old('nombre') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="apPaterno" class="control-label">Apellido Paterno</label>
									<input id="apPaterno" type="text" class="form-control input-sm" name="apPaterno" value="{{ old('apPaterno') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="apMaterno" class="control-label">Apellido Materno</label>
									<input id="apMaterno" type="text" class="form-control input-sm" name="apMaterno" value="{{ old('apMaterno') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-2">
									<label for="fechaNacimiento" class="control-label">Fecha Nac.</label>
									<input id="fechaNacimiento" type="text" class="form-control input-sm" name="fechaNacimiento" maxlength="10" value="{{ old('fechaNacimiento') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-1">
									<label for="edad" class="control-label">Edad</label>
									<input id="edad" type="text" class="form-control input-sm" name="edad" value="{{ old('edad') }}" readonly>
								</div>
							</div>
							<div class="row row-padding">
							    <div class="col-xs-12 col-sm-6 col-md-2">
									<label for="via" class="control-label">Tipo Calle</label>
									<input id="via" type="text" class="form-control input-sm" name="via" value="{{ old('via') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4">
									<label for="direccion" class="control-label">Dirección</label>
									<input id="direccion" type="text" class="form-control input-sm" name="direccion" value="{{ old('direccion') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-2">
									<label for="telefono" class="control-label">Teléfono</label>
									<input id="telefono" type="text" class="form-control input-sm" name="telefono" value="{{ old('telefono') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-2">
									<label for="telefono2" class="control-label">Teléfono 2</label>
									<input id="telefono2" type="text" class="form-control input-sm" name="telefono" value="{{ old('telefono2') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-2">
									<label for="email" class="control-label">Correo Electrónico</label>
									<input id="email" type="text" class="form-control input-sm" name="email" value="{{ old('email') }}" readonly>
								</div>
							</div>
							<div class="row row-padding row-border">
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="prevision" class="control-label">Previsión</label>
									<input id="prevision" type="text" class="form-control input-sm" name="prevision" value="{{ old('prevision') }}" readonly >
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="tramo_lbl" class="control-label">Tramo</label>
									<input id="tramo" type="text" class="form-control input-sm" name="tramo" value="{{ old('tramo') }}" readonly >
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="prais" class="control-label">Prais</label>
									<input id="prais" type="text" class="form-control input-sm" name="prais" value="{{ old('prais') }}" readonly >
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="funcionario" class="control-label">Funcionario</label>
									<input id="funcionario" type="text" class="form-control input-sm" name="funcionario" value="{{ old('funcionario') }}" readonly >
								</div>
							</div>
						</div>
						<!--Información Clínica-->
						<div class="row row-border">
							<div class="col-xs-10 col-sm-10 col-md-11">
								<h5>Datos de Ingreso</h5>
							</div>
							<div class="col-xs-1 col-sm-1 col-md-1">
								<h5>
									<a href="#" class="pull-right"  data-toggle="collapse" data-target="#infoingreso">
										<span class="glyphicon glyphicon-plus"></span>
									</a>
								</h5>
							</div>
						</div>
						<br>
						<div id="infoingreso" class="collapse in">
							<div class="row row-padding">
								<div class="col-xs-12 col-sm-6 col-md-6">
									<label for="tipo_ges_id" class="control-label">GES</label>
									<select id="tipo_ges_id" class="form-control" name="tipo_ges_id" required>
										<option value="">Seleccione</option>
										@if( old('tipo_ges_id') == 1)
											<option value="1" selected>Si</option>
											<option value="0">No</option>
										@elseif( old('tipo_ges_id') == 0 && old('tipo_ges_id') != null )
											<option value="1">Si</option>
											<option value="0" selected>No</option>
										@else
											<option value="1">Si</option>
											<option value="0">No</option>
										@endif
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6">
									<label for="fechaingreso" class="control-label">Fecha Entrada</label>
									<div class="form-inline">
										<input id="fechaingreso" type="text" class="form-control" name="fechaingreso" placeholder="dd-mm-yyyy"  maxlength="10" value="{{ old('fechaingreso') }}" autocomplete="off" required>
									</div>
								</div>

							</div>
							<div class="row row-padding">
								<div class="col-xs-12 col-sm-6 col-md-6" id="divEstablecimiento_Ori" >
									<label for="idorigen" class="control-label">Establecimiento Origen</label>
									<select id="idorigen" class="form-control" name="idorigen" required readonly>
										<!-- <option value="">Seleccione</option> -->
										@foreach($id_origens as $tipo)
											@if($tipo->id == $establecimiento)
												<option value="{{ $tipo->id }}" selected>{{ $tipo->name }}</option>
											@else
												<!-- <option value="{{ $tipo->id }}">{{ $tipo->name }}</option> -->
											@endif
										@endforeach
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6" id="divEstablecimiento_Des" >
									<label for="iddestino" class="control-label">Establecimiento Destino</label>
									<select id="iddestino" class="form-control" name="iddestino" required>
										<option value="">Seleccione</option>
										@foreach($id_destinos as $destino)
											@if($destino->id == old('iddestino'))
												<option value="{{ $destino->id }}" selected>{{ $destino->name }}</option>
											@else
												<option value="{{ $destino->id }}">{{ $destino->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
							<div class="row row-padding">
								<!--Rut-->
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="run_medico" class="control-label">R.U.N. Médico Solicitante</label>
										<input id="run_medico" type="text" class="form-control" name="run_medico" value="{{ old('run_medico') }}" onchange="validaRut()" required autofocus>

										@if ($errors->has('run_medico'))
											<span class="help-block">
												<strong>{{ $errors->first('run_medico') }}</strong>
											</span>
										@endif
										<!--Elementos ocultos-->
										<input name="run_medico_solicita" id="run_medico_solicita" type="hidden" value="{{ old('run_medico_solicita') }}">
										<input name="dv_medico_solicita" id="dv_medico_solicita" type="hidden" value="{{ old('dv_medico_solicita') }}">
								</div>

								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="fechacitacion" class="control-label">Fecha Citación</label>
									<div class="form-inline">
										<input id="fechacitacion" type="text" class="form-control" name="fechacitacion" placeholder="dd-mm-yyyy"  maxlength="10" value="{{ old('fechacitacion') }}" autocomplete="off">
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6">
									<label for="cie10s_id" class="control-label">Diagnóstico (CIE-10)</label>
									<input name="idCie10" id="idCie10" type="hidden" value="{{ old('idCie10') }}">
									<input id="cie10s_id" type="text" class="form-control" name="cie10s_id" title="Ingrese Diagnóstico. Búsqueda predictiva." value="{{ old('cie10s_id') }}" required autofocus>
								</div>
							</div>

							<div class="row row-padding">
								<div class="col-xs-12 col-sm-6 col-md-6">
									<label for="especialidads_ingreso_id" class="control-label">Especialidad</label>
									<select id="especialidads_ingreso_id" class="form-control" name="especialidads_ingreso_id" required autofocus>
										<option value="">Seleccione</option>
										@foreach($especialidads as $especialidad)
											@if($especialidad->id == old('especialidads_ingreso_id'))
												<option value="{{ $especialidad->id }}" selected>{{ $especialidad->name }}</option>
											@else
												<option value="{{ $especialidad->id }}">{{ $especialidad->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6">
									<label for="prestamin" class="control-label">Prestamin</label>
									<input id="prestamin" type="text" class="form-control" name="prestamin" value="{{ old('prestamin') }}" readonly>
								</div>
							</div>



							<div class="row row-padding row-border">
								<div class="col-xs-12 col-sm-12 col-md-12">
									<label for="precdiag" class="control-label">Precisión Diagnóstica</label>
									<textarea class="form-control" rows="3"  id="precdiag" name="precdiag"  maxlength="1000" required autofocus>{{ old('precdiag') }}</textarea>
								</div>
							</div>
						</div>
						<!--Información Salida-->
						<div class="row row-border">
							<div class="col-xs-10 col-sm-10 col-md-11">
								<h5>Prestación</h5>
							</div>
							<div class="col-xs-1 col-sm-1 col-md-1">
								<h5>
									<a href="#" class="pull-right"  data-toggle="collapse" data-target="#infoSalida">
										<span class="glyphicon glyphicon-plus"></span>
									</a>
								</h5>
							</div>
						</div>
						</br>
						<div id="infoSalida" class="collapse in">
							<div class="row row-padding row-border">
								<div class="col-xs-12 col-sm-6 col-md-4">
									<label for="tipo_prestacions_id" class="control-label">Tipo Prestación</label>
									<select id="tipo_prestacions_id" class="form-control" name="tipo_prestacions_id" required autofocus>
										@foreach($tipoPrestacions as $tipoPrestacion)
											@if($tipoPrestacion->id == old('tipo_prestacions_id'))
												<option value="{{ $tipoPrestacion->id }}" selected>{{ $tipoPrestacion->name }}</option>
											@else
												<option value="{{ $tipoPrestacion->id }}">{{ $tipoPrestacion->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
								<!--PROCEDIMIENTO-->
								<!--Si tipo de Prestación es Procedimiento, Intervención Quirurgica o Intervención Quirurgica Compleja-->
								@if ( old('tipo_prestacions_id') != 1 && old('tipo_prestacions_id') != null )
								<div class="col-xs-12 col-sm-6 col-md-4" id='tipo_procedimiento'>
									<label for="tipo_procedimiento_id" class="control-label">Tipo Procedimiento</label>
									<select id="tipo_procedimiento_id" class="form-control" name="tipo_procedimiento_id" required>
										<option value="">Seleccione</option>
										@if ( old('tipo_procedimiento_id') != null )
											@foreach($tipoProcedimientos as $tipoProcedimiento)
												@if($tipoProcedimiento->tipo_prestacion_id == old('tipo_prestacions_id'))
													@if($tipoProcedimiento->id == old('tipo_procedimiento_id'))
														<option value="{{ $tipoProcedimiento->id }}" selected>{{ $tipoProcedimiento->name }}</option>
													@else
														<option value="{{ $tipoProcedimiento->id }}">{{ $tipoProcedimiento->name }}</option>
													@endif
												@endif
											@endforeach
										@endif
									</select>
								</div>
								@else
								<div class="col-xs-12 col-sm-6 col-md-4" id='tipo_procedimiento' hidden>
									<label for="tipo_procedimiento_id" class="control-label">Tipo Procedimiento</label>
									<select id="tipo_procedimiento_id" class="form-control" name="tipo_procedimiento_id">
										<option value="">Seleccione</option>
									</select>
								</div>
								@endif
								<!--FIN PROCEDIMIENTO-->
								<!--PROCEDIMIENTO PMS-->
								<!--Si tipo de Prestación es Procedimiento, Intervención Quirurgica o Intervención Quirurgica Compleja-->
								@if ( old('tipo_prestacions_id') != 1 && old('tipo_prestacions_id') != null )
								<div class="col-xs-12 col-sm-6 col-md-4" id='tipo_procedimiento_pm'>
									<label for="tipo_procedimiento_pm_id" class="control-label">Procedimiento</label>
									<select id="tipo_procedimiento_pm_id" class="form-control" name="tipo_procedimiento_pm_id" required>
										<option value="">Seleccione</option>
										@if ( old('tipo_procedimiento_pm_id') != null )
											@foreach($tipoProcedimientoPms as $tipoProcedimientoPm)
												@if($tipoProcedimientoPm->tipo_procedimiento_id == old('tipo_procedimiento_id'))
													@if($tipoProcedimientoPm->id == old('tipo_procedimiento_pm_id'))
														<option value="{{ $tipoProcedimientoPm->id }}" selected>{{ $tipoProcedimientoPm->name }}</option>
													@else
														<option value="{{ $tipoProcedimientoPm->id }}">{{ $tipoProcedimientoPm->name }}</option>
													@endif
												@endif
											@endforeach
										@endif
									</select>
								</div>
								@else
								<div class="col-xs-12 col-sm-6 col-md-4" id='tipo_procedimiento_pm' hidden>
									<label for="tipo_procedimiento_pm_id" class="control-label">Procedimiento</label>
									<select id="tipo_procedimiento_pm_id" class="form-control" name="tipo_procedimiento_pm_id">
										<option value="">Seleccione</option>
									</select>
								</div>
								@endif
								<!--FIN PROCEDIMIENTO PMS-->
							</div>
						</div>
						<!--Información de Intervención Quirúrgica-->
						@if(old('tipo_prestacions_id') == 4 || old('tipo_prestacions_id') == 5)
							<div class="row row-border" id="grupo_intervencion">
						@else
							<div class="row row-border" id="grupo_intervencion" hidden>
						@endif
							<div class="col-xs-10 col-sm-10 col-md-11">
								<h5>Detalle Intervención</h5>
							</div>
							<div class="col-xs-1 col-sm-1 col-md-1">
								<h5>
									<a href="#" class="pull-right"  data-toggle="collapse" data-target="#intervencion">
										<span class="glyphicon glyphicon-plus"></span>
									</a>
								</h5>
							</div>
						</div>
						</br>
						@if(old('tipo_prestacions_id') == 4 || old('tipo_prestacions_id') == 5)
						<div id="intervencion" class="collapse in">
						@else
						<div id="intervencion" class="collapse in" hidden>
						@endif
							<div class="row row-padding row-border">
								@if(old('tipo_prestacions_id') == 4 || old('tipo_prestacions_id') == 5)
									<div class="col-xs-12 col-sm-6 col-md-6" id="plano_div">
								@else
									<div class="col-xs-12 col-sm-6 col-md-6" id="plano_div" hidden>
								@endif
									<label for="planos_id" class="control-label">Plano</label>
									<select id="planos_id" class="form-control" name="planos_id" autofocus>
										<option value="">Seleccione</option>
									</select>
								</div>
								@if(old('tipo_prestacions_id') == 4 || old('tipo_prestacions_id') == 5)
									<div class="col-xs-12 col-sm-6 col-md-6" id="extremidad_div">
								@else
									<div class="col-xs-12 col-sm-6 col-md-6" id="extremidad_div" hidden>
								@endif

									<label for="extremidads_id" class="control-label">Extremidad</label>
									<select id="extremidads_id" class="form-control" name="extremidads_id">
										<option value="">Seleccione</option>
									</select>
								</div>
							</div>
						</div>
						<br>
						<div class="row row-padding">
							<div class="col-xs-1 col-sm-1 col-md-1">
								<input type="submit" name="send" id="send" value="Guardar" class="btn btn-primary"></input>
							</div>
						</div>
	            	</form>
	            </div>
            </div>
			<!--FIN Panel Formulario Documento-->
        </div>
    </div>
</div>

<!-- SCRIPT DESHABILITA BOTON -->
<script>
$("form").submit(function(){
    $('#send').prop('disabled', true);
});	
</script>
<!-- FIN SCRIPT DESHABILITA BOTON -->

<!-- SCRIPT AUTOCOMPLETA RUT -->
<script>
$("#paciente").autocomplete(
	{
		source: function(request, response) {
			$.ajax({
				url: "{{ route('getPacienteLep') }}",
				dataType: "json",
				data: {
					term : request.term
				},

				success: function(data) {
					response(data);
				}
			});
		},
		select: function (event, ui) {
			$("#id").val(ui.item.id);
	        $("#nombre").val(ui.item.nombre);
			$("#apPaterno").val(ui.item.apPaterno);
			$("#apMaterno").val(ui.item.apMaterno);
			$("#via").val(ui.item.via);
			$("#direccion").val(ui.item.direccion);
			$("#telefono").val(ui.item.telefono);
			$("#telefono2").val(ui.item.telefono2);
			$("#email").val(ui.item.email);
			$("#fechaNacimiento").val(ui.item.fechaNacimiento);
			$("#edad").val(ui.item.edad);
			$("#prevision").val(ui.item.prevision);
			$("#tramo").val(ui.item.tramo);

            if (ui.item.prais == 1) {
                $("#prais").val('Si');
            }
            else {
            	$("#prais").val('No');
            }
            if (ui.item.funcionario == 1) {
                $("#funcionario").val('Si');
            }
            else {
            	$("#funcionario").val('No');
            }

			//activa boton de edición
			document.getElementById("botonEdit").className = "btn btn-sm btn-primary pull-right";
			@if( Auth::user()->isRole('PacientesFull') )
				var url = "{{ URL::to('editar/pacientes/su') }}/" + ui.item.id + "/2";
			@else
				var url = "{{ URL::to('editar/pacientes/di') }}/" + ui.item.id + "/2";
			@endif

			document.getElementById("botonEdit").href = url;

	    },
		minLength: 4,
	});
</script>
<!-- FIN SCRIPT AUTOCOMPLETA PACIENTE -->

<!--SCRIPT FECHA DE ENTRADA-->
<script>
$('#fechaingreso').datepicker({
        dateFormat: "dd-mm-yy",
        firstDay: 1,
		maxDate: 0,
		minDate: -30,
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
        monthNames:
            ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
            "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthNamesShort:
            ["Ene", "Feb", "Mar", "Abr", "May", "Jun",
            "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
		//focus
		onSelect: function ()
		{
			this.focus();
		}
});
//funcion que pone mascara de fecha
document.getElementById('fechaingreso').addEventListener('keyup', function()
	{
		var v = this.value;
		if (v.match(/^\d{2}$/) !== null) {
			this.value = v + '-';
		} else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
			this.value = v + '-';
		}
	}
	);
</script>
<!--FIN SCRIPT FECHA DE ENTRADA-->

<!--VERIFICADOR RUT-->
<script>
function validaRut()
{
	var rut = document.getElementById("run_medico");
	rut.setCustomValidity("");

	var rexp = new RegExp(/^([0-9])+\-([kK0-9])+$/);
	var rutValue = rut.value;

	//elimina espacios y puntos
	rutValue = rutValue.replace(/\s+/g, '');
	rutValue = rutValue.replace(/\./g, '');

	if(rutValue.match(rexp)){
		//separa texto por guion

		var RUT = rutValue.split("-");
		var elRut = RUT[0];
		var factor = 2;
		var suma = 0;
		var dv;
		for(i=(elRut.length-1); i>=0; i--){
			factor = factor > 7 ? 2 : factor;
			suma += parseInt(elRut[i])*parseInt(factor++);
		}
		dv = 11 -(suma % 11);
		if(dv == 11){
			dv = 0;
		}
		else if (dv == 10){
			dv = "k";
		}

		if(dv == RUT[1].toLowerCase()){
			document.getElementById("run_medico_solicita").value = RUT[0];
			document.getElementById("dv_medico_solicita").value = dv;
			return true;
		}
		else {
			rut.setCustomValidity("El Rut es incorrecto");
			return false;
		}
	}
	else {
		rut.setCustomValidity("Formato Rut Incorrecto");
		return false;
	}
}
</script>
<!--FIN VERIFICADOR DE RUT-->

<!--SCRIPT FECHA DE CITACION-->
<script>
$('#fechacitacion').datepicker({
        dateFormat: "dd-mm-yy",
        firstDay: 1,
		minDate: 0,
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
        monthNames:
            ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
            "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthNamesShort:
            ["Ene", "Feb", "Mar", "Abr", "May", "Jun",
            "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
		//focus
		onSelect: function ()
		{
			this.focus();
		}
});
//funcion que pone mascara de fecha
document.getElementById('fechacitacion').addEventListener('keyup', function() {
	var v = this.value;
	if (v.match(/^\d{2}$/) !== null) {
		this.value = v + '-';
	} else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
		this.value = v + '-';
	}
});
</script>
<!--FIN SCRIPT FECHA DE CITACION-->

<!--SCRIPT AUTOCOMPLETA DIAGNOSTICO CIE10 -->
<script>
	$("#cie10s_id").autocomplete(
	{
		source: function(request, response) {
			$.ajax({
				url: "{{ route('getCie10') }}",
				dataType: "json",
				data: {
					term : request.term
				},

				success: function(data) {
					response(data);
				}

			});
		},
		select: function (event, ui) {
				$("#idCie10").val(ui.item.id);
				},
		minLength: 2,
	}
	);
</script>
<!-- FIN SCRIPT AUTOCOMPLETA DIAGNOSTICO CIE10 -->

<!--SCRIPT OCULTA o MUESTRA PRESTACION -->
<script>
	document.getElementById('tipo_prestacions_id').addEventListener('change', function()
	{
	    //vaciar listas deplegables asociadas
	    $("#tipo_procedimiento_pm_id").empty();
		$("#tipo_procedimiento_pm_id").append("<option value=''>Seleccione</option>");
	    $("#planos_id").empty();
		$("#planos_id").append("<option value=''>Seleccione</option>");
	    $("#extremidads_id").empty();
		$("#extremidads_id").append("<option value=''>Seleccione</option>");

	    if (this.value == 4 || this.value == 5) { //intervencion quirurjica compleja o procedimeinto

	        document.getElementById('grupo_intervencion').hidden = false;
	        document.getElementById('intervencion').hidden = false;
	        document.getElementById('plano_div').hidden = false;
	        document.getElementById('extremidad_div').hidden = false;

			document.getElementById('tipo_procedimiento').hidden = false;
			document.getElementById("tipo_procedimiento_id").required = true;
			document.getElementById('tipo_procedimiento_pm').hidden = false;
			document.getElementById("tipo_procedimiento_pm_id").required = true;
	    }
		else {

			document.getElementById('grupo_intervencion').hidden = true;
			document.getElementById('intervencion').hidden = true;
			document.getElementById('plano_div').hidden = true;
	        document.getElementById('extremidad_div').hidden = true;

	        if (this.value == 2 || this.value == 3) //consulta repetida o intervencion quirurgica
	        {
	        	document.getElementById('tipo_procedimiento').hidden = false;
				document.getElementById("tipo_procedimiento_id").required = true;
				document.getElementById('tipo_procedimiento_pm').hidden = false;
				document.getElementById("tipo_procedimiento_pm_id").required = true;
	        }
	        else { //consulta nueva o no seleccionado

	        	document.getElementById('tipo_procedimiento').hidden = true;
				document.getElementById("tipo_procedimiento_id").required = false;
				document.getElementById('tipo_procedimiento_pm').hidden = true;
				document.getElementById("tipo_procedimiento_pm_id").required = false;
	        }
	    }
	}
	);
</script>
<!-- FIN SCRIPT OCULTA o MUESTRA PRESTACION -->

<!--SCRIPT AUTOCARGA TIPO PROCEDIMIENTO -->
<script>
    $("#tipo_prestacions_id").change(function(event){
    	//busca establecimientos asociados al usuario
   		$.get("{{ URL::to('getTipoProcedimiento') }}/"+event.target.value+"",function(response,state)
      	{
        $("#tipo_procedimiento_id").empty();
        $("#tipo_procedimiento_id").append("<option value=''>Seleccione</option>");
        for(i=0;i<response.length;i++)
        	{
	            if(response[i].active == 1)
	            {
	                $("#tipo_procedimiento_id").append("<option value='"+response[i].id+"'>"+response[i].name+"</option>");
	            }
        	}
    	});
    });
</script>
<!-- FIN SCRIPT AUTOCARGA TIPO PROCEDIMIENTO -->

<!--SCRIPT AUTOCARGA TIPO PROCEDIMIENTO PM-->
<script>
    $("#tipo_procedimiento_id").change(function(event){
    	//vaciar listas deplegables asociadas
	    $("#planos_id").empty();
		$("#planos_id").append("<option value=''>Seleccione</option>");
	    $("#extremidads_id").empty();
		$("#extremidads_id").append("<option value=''>Seleccione</option>");


    	$.get("{{ URL::to('getTipoProcedimientopm') }}/"+event.target.value+"",function(response,state)
       	{
        $("#tipo_procedimiento_pm_id").empty();
        $("#tipo_procedimiento_pm_id").append("<option value=''>Seleccione</option>");
        for(i=0;i<response.length;i++)
        	{
	            if(response[i].active == 1)
	            {
	                $("#tipo_procedimiento_pm_id").append("<option value='"+response[i].id+"'>"+response[i].name+"</option>");
	            }
        	}
    	});
    });
</script>
<!-- FIN SCRIPT AUTOCARGA TIPO PROCEDIMIENTO PM -->

<!--SCRIPT CARGA PRESTAMIN CAMBIO ESPECIALIDAD-->
<script>
	$("#especialidads_ingreso_id").change(function(event){

		$("#prestamin").val("");

		$tipo_prestacions_id = $("#tipo_prestacions_id").val();
		$tipo_procedimientopm_id = $("#tipo_procedimiento_pm_id").val();

		if ($tipo_prestacions_id == 1 ) {
			//Busca Prestamin en la tabla especialidad si es consulta nueva = 1
			$especialidads_ingreso_id = $("#especialidads_ingreso_id").val();
			$.get("{{ URL::to('getRem') }}/"+$especialidads_ingreso_id+"",function(response,state)
			{
				$("#prestamin").val(response.sigte);
				$("#planos_id").prop('required',false);
				$("#extremidads_id").prop('required',false);
			});
		}
		else if ($tipo_prestacions_id > 1 || $tipo_procedimientopm_id != '')
		{
			$.get("{{ URL::to('getPrestamin') }}/"+$tipo_procedimientopm_id+"",function(response,state)
			{
				$("#prestamin").val(response.prestamin);
			});
		}
	});
</script>
<!-- FIN SCRIPT CARGA PRESTAMIN CAMBIO ESPECIALIDAD -->

<!--SCRIPT AUTOCARGA PRESTAMIN CAMBIO PRESTACION-->
<script>
	$("#tipo_prestacions_id").change(function(event){
		$tipo_prestacions_id = $("#tipo_prestacions_id").val();
		if ($tipo_prestacions_id == 1 )
		{
			//Busca Prestamin en la tabla especialidad si es consulta nueva = 1
			$especialidads_ingreso_id = $("#especialidads_ingreso_id").val();
			$.get("{{ URL::to('getRem') }}/"+$especialidads_ingreso_id+"",function(response,state)
			{
				//$("#prestamin").empty();
				$("#prestamin").val(response.sigte);
				$("#planos_id").prop('required',false);
				$("#extremidads_id").prop('required',false);
			});
		}
		else
		{
			{
				$("#prestamin").val("");
				$("#planos_id").prop('required',false);
				$("#extremidads_id").prop('required',false);
			}
		}
	});
</script>
<!-- FIN SCRIPT AUTOCARGA PRESTAMIN CAMBIO PRESTACION-->

<!--SCRIPT AUTOCARGA PRESTAMIN CAMBIO TIPO PROCEDIMIENTO PM-->
<script>
	$("#tipo_procedimiento_pm_id").change(function(event){
		$("#prestamin").val("");

		$tipo_prestacions_id = $("#tipo_prestacions_id").val();
		$tipo_procedimientopm_id = $("#tipo_procedimiento_pm_id").val();

		if ($tipo_prestacions_id == 1 ) {
			//Busca Prestamin en la tabla especialidad si es consulta nueva = 1
			$especialidads_ingreso_id = $("#especialidads_ingreso_id").val();
			$.get("{{ URL::to('getRem') }}/"+$especialidads_ingreso_id+"",function(response,state)
			{
				//$("#prestamin").empty();
				$("#prestamin").val(response.sigte);
				$("#planos_id").prop('required',false);
				$("#extremidads_id").prop('required',false);
			});
		}
		else if ($tipo_prestacions_id > 1 || $tipo_procedimientopm_id != '')
		{
			$.get("{{ URL::to('getPrestamin') }}/"+$tipo_procedimientopm_id+"",function(response,state)
			{
				$("#prestamin").val(response.prestamin);
				if (response.requiere_plano == 1 )
				{
					$("#planos_id").prop('required',true);
					//agrega lista de planos
					$.get("{{ URL::to('getPlano') }}/"+event.target.value+"",function(response1,state)
				      	{
				        $("#planos_id").empty();
				        $("#planos_id").append("<option value=''>Seleccione</option>");
				        for(i=0;i<response1.length;i++)
				        	{
					            $("#planos_id").append("<option value='"+response1[i].id+"'>"+response1[i].name+"</option>");
				        	}
				    	});
				}
				else
				{
					$("#planos_id").prop('required',false);
					//borra lista de planos
					$("#planos_id").empty();
					$("#planos_id").append("<option value=''>No Requerido</option>");
				}
				if (response.requiere_extremidad == 1 )
				{
					$("#extremidads_id").prop('required',true);
					//agrega lista de extremidad
					$.get("{{ URL::to('getExtremidad') }}/"+event.target.value+"",function(response2,state)
				      	{
				        $("#extremidads_id").empty();
				        $("#extremidads_id").append("<option value=''>Seleccione</option>");
				        for(i=0;i<response2.length;i++)
				        	{
					        	$("#extremidads_id").append("<option value='"+response2[i].id+"'>"+response2[i].name+"</option>");
				        	}
				    	});
				}
				else
				{
					$("#extremidads_id").prop('required',false);
					//borra lista de extremidad
					$("#extremidads_id").empty();
					$("#extremidads_id").append("<option value=''>No Requerido</option>");
				}
			});
		}
	});
</script>
<!-- FIN SCRIPT AUTOCARGA PRESTAMIN CAMBIO TIPO PROCEDIMIENTO PM-->

<!-- SCRIPT QUE CARGA VALORES DE PLANOS y EXTREMIDADS-->
@if(old('tipo_prestacions_id') == 4 || old('tipo_prestacions_id') == 5)
	@if(old('tipo_procedimiento_pm_id') != null)
	<script>
	$(function() {
		$.get("{{ URL::to('getPrestamin') }}/{{old('tipo_procedimiento_pm_id')}}",function(response,state)
			{
				if (response.requiere_plano == 1 )
				{
					$("#planos_id").prop('required',true);
					//agrega lista de planos
					$.get("{{ URL::to('getPlano') }}/{{old('tipo_procedimiento_pm_id')}}",function(response1,state)
				      	{
				        $("#planos_id").empty();
				        $("#planos_id").append("<option value=''>Seleccione</option>");
				        for(i=0;i<response1.length;i++)
				        	{
					            @if(old('planos_id') != null)
						            if ( response1[i].id == {{old('planos_id')}}){
						            	$("#planos_id").append("<option value='"+response1[i].id+"' selected>"+response1[i].name+"</option>");
						            }
						            else {
						            	$("#planos_id").append("<option value='"+response1[i].id+"'>"+response1[i].name+"</option>");
						            }
					            @else
					            	$("#planos_id").append("<option value='"+response1[i].id+"'>"+response1[i].name+"</option>");
					            @endif
				        	}
				    	});
				}
				else
				{
					$("#planos_id").prop('required',false);
					//borra lista de planos
					$("#planos_id").empty();
					$("#planos_id").append("<option value=''>No Requerido</option>");
				}
				if (response.requiere_extremidad == 1 )
				{
					$("#extremidads_id").prop('required',true);
					//agrega lista de extremidad
					$.get("{{ URL::to('getExtremidad') }}/{{old('tipo_procedimiento_pm_id')}}",function(response2,state)
				      	{
				        $("#extremidads_id").empty();
				        $("#extremidads_id").append("<option value=''>Seleccione</option>");
				        for(i=0;i<response2.length;i++)
				        	{
					        	@if(old('extremidads_id') != null) 
						        	if ( response2[i].id == {{old('extremidads_id')}}){
						            	$("#extremidads_id").append("<option value='"+response2[i].id+"' selected>"+response2[i].name+"</option>");
						            }
						            else {
						            	$("#extremidads_id").append("<option value='"+response2[i].id+"'>"+response2[i].name+"</option>");
						            }
					            @else
					            	$("#extremidads_id").append("<option value='"+response2[i].id+"'>"+response2[i].name+"</option>");
					            @endif	

				        	}
				    	});
				}
				else
				{
					$("#extremidads_id").prop('required',false);
					//borra lista de extremidad
					$("#extremidads_id").empty();
					$("#extremidads_id").append("<option value=''>No Requerido</option>");
				}
			});
	});
	</script>
	@endif
@endif
<!-- FIN SCRIPT QUE CARGA VALORES DE PLANOS-->

@endsection
