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
	@elseif($message == 'paciente')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Paciente no Existe
		</div>
	@elseif($message == 'fechaegreso')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Fecha de egreso fuera del rango permitido (Debe ser mayor que fecha entrada y menor o igual que fecha actual).
		</div>
	@elseif($message == 'prestamin_egreso')
	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		El prestamin de egreso es incorrecto.
	</div>
	@elseif($message == 'duplicado')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Ingreso lista de espera duplicado.
		</div>
	@endif

	<!--FIN Mensajes de Guardado o Actualización de Lista de Espera-->
    <div class="row">
        <div class="col-md-12 col-lg-10 col-lg-offset-1">
			<!--Panel Formulario Crear Lista Espera-->
			<div class="panel panel-default">
                <div class="panel-heading">Detalle Lista de Espera</div>
                <div class="panel-body">
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
					    <div class="row row-padding">
							<div class="col-xs-12 col-sm-6 col-md-3">
								@if( $paciente->tipoDoc == 1 )
									<label for="paciente" class="control-label">R.U.N.</label>
									<input id="paciente" type="text" class="form-control input-sm" value="{{ $paciente->rut }}-{{ $paciente->dv }}" readonly>
								@else
									<label for="paciente" class="control-label">Número de Documento</label>
									<input id="paciente" type="text" class="form-control input-sm" value="{{ $paciente->numDoc }}" readonly>
								@endif
							</div>
						</div>
						<div class="row row-padding">
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="nombre" class="control-label">Nombre</label>
								<input id="nombre" type="text" class="form-control input-sm" name="nombre" value="{{ $paciente->nombre }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="apPaterno" class="control-label">Apellido Paterno</label>
								<input id="apPaterno" type="text" class="form-control input-sm" name="apPaterno" value="{{ $paciente->apPaterno }}"  readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="apMaterno" class="control-label">Apellido Materno</label>
								<input id="apMaterno" type="text" class="form-control input-sm" name="apMaterno" value="{{ $paciente->apMaterno }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
								<label for="fechaNacimiento" class="control-label">Fecha Nac.</label>
								<input id="fechaNacimiento" type="text" class="form-control input-sm" name="fechaNacimiento" value="{{ $fechaNacimiento }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-1">
								<label for="edad" class="control-label">Edad</label>
								<input id="edad" type="text" class="form-control input-sm" name="edad" value="{{ $edad }}" readonly>
							</div>
						</div>
						<div class="row row-padding">
							<div class="col-xs-12 col-sm-6 col-md-2">
								<label for="via" class="control-label">Tipo Calle</label>
								<input id="via" type="text" class="form-control input-sm" name="via" value="{{ $via }}" readonly>
							</div>

							<div class="col-xs-12 col-sm-6 col-md-4">
								<label for="direccion" class="control-label">Dirección</label>
								<input id="direccion" type="text" class="form-control input-sm" name="direccion" value="{{ $paciente->direccion }} {{ $paciente->numero }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
								<label for="telefono" class="control-label">Teléfono</label>
								<input id="telefono" type="text" class="form-control input-sm" name="telefono" value="{{ $paciente->telefono }}"  readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
								<label for="telefono2" class="control-label">Teléfono 2</label>
								<input id="telefono2" type="text" class="form-control input-sm" name="telefono" value="{{ $paciente->telefono2 }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">
								<label for="email" class="control-label">Correo Electrónico</label>
								<input id="email" type="text" class="form-control input-sm" name="email" value="{{ $paciente->email }}" readonly>

							</div>
						</div>
						<div class="row row-padding row-border">
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="prevision" class="control-label">Previsión</label>
								<input id="prevision" type="text" class="form-control input-sm" name="prevision" value="{{ $prevision }}"  readonly >
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="tramo_lbl" class="control-label">Tramo</label>
								<input id="tramo" type="text" class="form-control input-sm" name="tramo" value="{{ $tramo }}" readonly >
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="prais" class="control-label">Prais</label>
								@if( $paciente->prais == 1 )
									<input id="prais" type="text" class="form-control input-sm" value="Si" readonly>
								@else
									<input id="prais" type="text" class="form-control input-sm" value="No" readonly>
								@endif
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="funcionario" class="control-label">Funcionario</label>
								@if( $paciente->funcionario == 1 )
									<input id="funcionario" type="text" class="form-control input-sm" value="Si" readonly>
								@else
									<input id="funcionario" type="text" class="form-control input-sm" value="No" readonly>
								@endif
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
								<label for="tipo_ges" class="control-label">GES</label>
								<input id="tipo_ges" type="text" class="form-control input-sm" name="tipo_ges" value="{{ $tipoGes }}" readonly >
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label for="fechaingreso" class="control-label">Fecha Entrada</label>
								<div class="form-inline">
									<input id="fechaingreso" type="text" class="form-control input-sm" name="fechaingreso" placeholder="dd-mm-yyyy" maxlength="10" value="{{ $fechaingreso }}" readonly>
								</div>
							</div>
						</div>
						<div class="row row-padding">
							<div class="col-xs-12 col-sm-6 col-md-6" id="divEstablecimiento_Ori" >
								<label for="idorigen" class="control-label">Establecimiento Origen</label>
								<input id="idorigen" type="text" class="form-control input-sm" name="idorigen" value="{{ $estOrigen->name }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6" id="divEstablecimiento_Des" >
								<label for="iddestino" class="control-label">Establecimiento Destino</label>
								<input id="iddestino" type="text" class="form-control input-sm" name="iddestino" value="{{ $estDest->name }}" readonly>
							</div>
						</div>
						<div class="row row-padding">
							<!--Rut Medico-->
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="run_medico" class="control-label">R.U.N. Médico Solicitante</label>
									<input id="run_medico" type="text" class="form-control input-sm" name="run_medico" value="{{ $medico }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="fechacitado_lbl" class="control-label">Fecha Citación</label>
								<input id="fechacitacion" type="text" class="form-control input-sm" name="fechacitacion" value="{{ $fechacitacion }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label for="cie10s_id" class="control-label">Diagnóstico (CIE-10)</label>
								<input id="cie10s_id" type="text" class="form-control input-sm" name="cie10s_id" title="Ingrese Diagnóstico. Búsqueda predictiva." value="{{ $cie10 }}" readonly>
							</div>
						</div>

						<div class="row row-padding">
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label for="especialidad_ing" class="control-label">Especialidad</label>
								<input id="especialidad_ing" type="text" class="form-control input-sm" name="especialidad_ing" value="{{ $especialidad_ing->name }} " readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label for="prestamin" class="control-label">Prestamin</label>
								<input id="prestamin" type="text" class="form-control input-sm" name="prestamin"  value="{{ $listaEspera->prestamin_ing }}" readonly>
							</div>
						</div>
						<div class="row row-padding row-border">
							<div class="col-xs-12 col-sm-12 col-md-12">
								<label for="precdiag" class="control-label">Precisión Diagnóstica</label>
								<textarea class="form-control" rows="3"  id="precdiag" name="precdiag" readonly autofocus>{{ $listaEspera->precdiag }}</textarea>
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
								<label for="prestacion" class="control-label">Tipo Prestación</label>
								<input id="prestacion" type="text" class="form-control input-sm" name="prestacion"  value="{{ $prestacion->name }}" readonly>
							</div>
							@if ( $prestacion->id != 1 )
								<div class="col-xs-12 col-sm-6 col-md-4">
									<label for="prestacion" class="control-label">Tipo Procedimiento</label>
									<input id="prestacion" type="text" class="form-control input-sm" name="prestacion"  value="{{ $procedimiento_id }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4">
									<label for="prestacion" class="control-label">Procedimiento</label>
									<input id="prestacion" type="text" class="form-control input-sm" name="prestacion"  value="{{ $procedimientopm_id }}" readonly>
								</div>
							@endif
						</div>
					</div>
					<!--Información de Intervención Quirúrgica-->
					@if ( $prestacion->id == 4 || $prestacion->id == 5 )
						<div class="row row-border" id="grupo_intervencion">
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
						<div id="intervencion" class="collapse in">
							<div class="row row-padding row-border">
								<div class="col-xs-12 col-sm-6 col-md-6" id="plano_div">
									<label for="planos" class="control-label">Plano</label>
									<input id="planos" type="text" class="form-control" name="planos"  value="{{ $plano }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6" id="extremidad_div">
									<label for="extremidad" class="control-label">Extremidad</label>
									<input id="extremidad" type="text" class="form-control" name="extremidad"  value="{{ $extremidad }}" readonly>
								</div>
							</div>
						</div>
					@endif


					<!--GUARDA DATOS DE EGRESO-->
					<form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/listaesperas/actualiza') }}">
						{{ csrf_field() }}
						<!--Información Lista de Espera - Oculto -->
						<input id="id" type="hidden" name="id" value="{{ $listaEspera->id }}" hidden>
						<!--Egreso-->
						<div class="row row-border" id="grupo_egreso">
							<div class="col-xs-10 col-sm-10 col-md-11">
								<h5>Datos de Egreso</h5>
							</div>
							<div class="col-xs-1 col-sm-1 col-md-1">
								<h5>
									<a href="#" class="pull-right"  data-toggle="collapse" data-target="#egreso">
										<span class="glyphicon glyphicon-plus"></span>
									</a>
								</h5>
							</div>
						</div>
						</br>
						<div id="egreso" class="collapse in">
							<div class="row row-padding">
								<div class="col-md-4" id="plano_div">
									<label for="fechaSalida" class="control-label">Fecha</label>
									<input id="fechaSalida" type="text" class="form-control" name="fechaSalida" required placeholder="dd-mm-yyyy"  maxlength="10" value="{{ old('fechaSalida') }}" autocomplete="off" required>
								</div>
								<div class="col-md-4" id="plano_div">
									<label for="prestamin_egr" class="control-label">Prestamin Salida</label>
									<input id="prestamin_egr" type="text" class="form-control" name="prestamin_egr" value="{{ $listaEspera->prestamin_egr }}" required>
								</div>
								<div class="col-md-4" id="divEstablecimiento_Res">
									<label for="idResuelve" class="control-label">Establecimiento Resuelve</label>
									<select id="idResuelve" class="form-control" name="idResuelve" required>
										<option value="">Seleccione</option>
										@foreach($estResuelve as $tipo)
											@if($tipo->id == old('idResuelve'))
												<option value="{{ $tipo->id }}" selected>{{ $tipo->name }}</option>
											@else
												<option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
							<div class="row row-padding">
								<div class="col-md-4" id="divCausalEgreso">
									<label for="idCausalEgreso" class="control-label">Causal Egreso</label>
									<select id="idCausalEgreso" class="form-control" name="idCausalEgreso" required>
										<option value="">Seleccione</option>
										@foreach($CausalEgresos as $tipo)
											@if($tipo->id === old('idCausalEgreso'))
												<option value="{{ $tipo->id }}" selected>{{ $tipo->id }} - {{ $tipo->name }}</option>
											@else
												<option value="{{ $tipo->id }}">{{ $tipo->id }} - {{ $tipo->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
								<div class="col-md-4" id="runResol">
									<label for="run_medico2" class="control-label">R.U.N. Médico Resuelve (Opcional)</label>
									<input id="run_medico2" type="text" class="form-control" name="run_medico2" onchange="validaRut()" autofocus>
									<!--Elementos ocultos-->
									<input name="run_medico_resol" id="run_medico_resol" type="hidden">
									<input name="dv_medico_resol" id="dv_medico_resol" type="hidden">
								</div>
								<div class="col-md-4" id="resultado" hidden> <!--Campo Oculto-->
									<label for="resultado" class="control-label">Resultado</label>
									<input id="resultado" type="text" class="form-control" name="resultado" maxlength="190"  autofocus>
								</div>
							</div>
							</br>
							<div class="row row-padding">
								<div class="col-xs-1 col-sm-1 col-md-1">
									<input type="submit" name="send" id="send" value="Guardar" class="btn btn-primary"></input>
								</div>
							</div>
						</div>
					</form>
				</div>
            </div>
			<!--FIN Panel Formulario Documento-->
        </div>
    </div>
</div>

<!--Script Fecha de Salida-->
<script>
$('#fechaSalida').datepicker({
        dateFormat: "dd-mm-yy",
        firstDay: 1,
        mindate: 0,
		maxDate: 0,
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
document.getElementById('fechaSalida').addEventListener('keyup', function()
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
<!--FIN Script Fecha de Entrada-->

<!--VERIFICADOR RUT-->
<script>
function validaRut()
{
	var rut = document.getElementById("run_medico2");
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
			document.getElementById("run_medico_resol").value = RUT[0];
			document.getElementById("dv_medico_resol").value = dv;
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

<!-- SCRIPT DESHABILITA BOTON -->
<script>
$("form").submit(function(){
    $('#send').prop('disabled', true);
});	
</script>
<!-- FIN SCRIPT DESHABILITA BOTON -->

@endsection
