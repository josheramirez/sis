@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row">
        <div class="col-md-12 col-lg-10 col-lg-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Detalle Lista de Espera</div>
                <div class="panel-body">
					{{ csrf_field() }}
                	<div class="row row-border row-padding">
	                	<div class="col-xs-1 col-sm-1 col-md-1">
							<a href="{{ URL::to('listaesperas/pdf/' . $listaEspera->id) }}" target="_blank" class="btn btn-primary">Imprimir</a>
						</div>
					</div>
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
							<div class="col-xs-12 col-sm-6 col-md-6">
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
							<!--Información Lista de Espera -->
							<input id="id" type="hidden" class="form-control" name="id" value="{{ $listaEspera->id }}" hidden>
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
							<div class="col-xs-1 col-sm-1 col-md-1">
								<label for="edad" class="control-label">Edad</label>
								<input id="edad" type="text" class="form-control input-sm" name="edad" value="{{ $edad }}" readonly>
							</div>
						</div>
						<div class="row row-padding">
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
								<label for="fecha_entrada" class="control-label">Fecha Entrada</label>
								<div class="form-inline">
									<input id="fechaingreso" type="text" class="form-control input-sm" name="fechaingreso" required maxlength="10" placeholder="dd-mm-yyyy" value="{{ $fecha_entrada }}" readonly>
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
							<!--Rut-->
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="run_medico" class="control-label">R.U.N. Médico Solicitante</label>
									<input id="run_medico" type="text" class="form-control input-sm" name="run_medico" value="{{ $medico }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="fechacitado_lbl" class="control-label">Fecha Citación</label>
								<div class="form-inline">
									<input id="fechacitacion" type="text" class="form-control input-sm" name="fechacitacion" value="{{ $fecha_citacion }}" readonly>
								</div>
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
								<textarea class="form-control input-sm" rows="3"  id="precdiag" name="precdiag" readonly>{{ $listaEspera->precdiag }}</textarea>
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
							@if ($prestacion->id != 1 )
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
								<input id="fechaSalida" type="text" class="form-control input-sm" name="fechaSalida" value="{{ $fecha_egreso }}" readonly>
							</div>
							<div class="col-md-4" id="plano_div">
								<label for="prestamin_egr" class="control-label">Prestamin Salida</label>
								<input id="prestamin_egr" type="text" class="form-control input-sm" name="prestamin_egr" value="{{ $listaEspera->prestamin_egr }}" readonly>
							</div>
							<div class="col-md-4" id="divEstablecimiento_Res">
								<label for="idResuelve" class="control-label">Establecimiento Resuelve</label>
								<input id="idResuelve" type="text" class="form-control input-sm" name="idResuelve" value="{{ $estResuelve }}" readonly>
							</div>
						</div>
						<div class="row row-padding">
							<div class="col-md-4" id="divCausalEgreso">
								<label for="idCausalEgreso" class="control-label">Causal Egreso</label>
								<input id="idCausalEgreso" type="text" class="form-control input-sm" name="idCausalEgreso" value="{{ $CausalEgresos }}" readonly>
							</div>
							<div class="col-md-4" id="runResol">
								<label for="run_medico2" class="control-label">R.U.N. Médico Resuelve</label>
								<input id="run_medico2" type="text" class="form-control input-sm" name="run_medico2" value="{{ $listaEspera->run_medico_resol }}-{{ $listaEspera->dv_medico_resol }}" readonly>
							</div>
							<div class="col-md-4" id="resultado">
								<label for="resultado" class="control-label">Resultado</label>
								<input id="resultado" type="text" class="form-control input-sm" name="resultado" value="{{ $listaEspera->resultado }}" readonly>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div|>
    </div>
</div>

@endsection
