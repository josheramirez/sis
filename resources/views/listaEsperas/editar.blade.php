@extends('layouts.app4')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Guardado o Actualización de Lista de Espera-->
	<?php $message=Session::get('message') ?>
	@if($message == 'actualiza')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Lista Espera Actualizada Exitosamente.
		</div>
	@elseif($message == 'paciente')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Paciente no Existe.
		</div>
	@elseif($message == 'cie10')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Diagnóstico (CIE-10) no Existe
		</div>
	@elseif($message == 'duplicado')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Ingreso lista de espera duplicado.
		</div>
	@elseif($message == 'fecha_entrada')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Fecha de entrada es incorrecta.
		</div>
	@elseif($message == 'fecha_citacion')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Fecha de citación es incorrecta.
		</div>
	@elseif($message == 'fecha_egreso')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Fecha de egreso es incorrecta.
		</div>
	@endif

	<!--FIN Mensajes de Guardado o Actualización de Lista de Espera-->
    <div class="row">
        <div class="col-md-12 col-lg-10 col-lg-offset-1">
			<!--Panel Formulario Crear Lista Espera-->
			<div class="panel panel-default">
                <div class="panel-heading">Detalle Lista de Espera</div>
                <div class="panel-body">
					<div class="row row-border row-padding">
						<div class="col-xs-12 col-sm-6 col-md-1">
							<a href="{{ URL::to('listaesperas/pdf/' . $listaEspera->id) }}" target="_blank" class="btn btn-primary">Imprimir</a>
						</div>
					</div>
					<form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/listaesperas/actualizaingreso') }}">
						{{ csrf_field() }}
						<!--Información del paciente-->
						<div class="row row-border">
							<div class="col-xs-10 col-sm-10 col-md-11">
								<h5>Información Paciente</h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-1">
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
								<div class="col-xs-12 col-sm-6 col-md-4" >
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
									<input id="fechaNacimiento" type="text" class="form-control input-sm" name="fechaNacimiento" required placeholder="dd-mm-yyyy"  maxlength="10" value="{{ $fechaNacimiento }}" readonly>
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
								<div class="col-xs-12 col-sm-6 col-md-2">
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
						<!--Datos de Ingreso-->
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
								<div class="col-xs-12 col-sm-12 col-md-6">
									<label for="tipo_ges" class="control-label">GES</label>
									<select id="tipo_ges_id" class="form-control" name="tipo_ges_id" required>
										<option value="">Seleccione</option>
											@if($listaEspera->tipo_ges_id == 0)
												<option value=0 selected>No</option>
												<option value=1 >Si</option>
											@else
												<option value=0 >No</option>
												<option value=1 selected>Si</option>
											@endif
									</select>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6">
									<label for="fecha_entrada" class="control-label">Fecha Entrada</label>
									<div class="form-inline">
										<input id="fechaingreso" type="text" class="form-control" name="fechaingreso" required placeholder="dd-mm-yyyy"  maxlength="10" value="{{ $fechaingreso }}" autocomplete="off" required>
									</div>
								</div>
							</div>
							<div class="row row-padding">
								<div class="col-xs-12 col-sm-12 col-md-6" id="divEstablecimiento_Ori" >
									<label for="idorigen" class="control-label">Establecimiento Origen</label>
									<select id="idorigen" class="form-control" name="idorigen" required>
										<option value="">Seleccione</option>
										@foreach($id_origens as $tipo)
											@if($tipo->id == $listaEspera->establecimientos_id_origen)
												<option value="{{ $tipo->id }}" selected>{{ $tipo->name }}</option>
											@else
												<option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6" id="divEstablecimiento_Des" >
									<label for="iddestino" class="control-label">Establecimiento Destino</label>
									<select id="iddestino" class="form-control" name="iddestino" required>
										<option value="">Seleccione</option>
										@foreach($id_destinos as $destino)
											@if($destino->id == $listaEspera->establecimientos_id_destino)
												<option value="{{ $destino->id }}" selected>{{ $destino->name }}</option>
											@else
												<option value="{{ $destino->id }}">{{ $destino->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
							<div class="row row-padding">
								<!--Rut Medico-->
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="run_medico" class="control-label">R.U.N. Médico Solicitante</label>
									<input id="run_medico" type="text" class="form-control" name="run_medico" value="{{ $medico }}" onchange="validaRut()" required autofocus>
									@if ($errors->has('run_medico'))
										<span class="help-block">
											<strong>{{ $errors->first('run_medico') }}</strong>
										</span>
									@endif
									<!--Elementos ocultos-->
									<input name="run_medico_solicita" id="run_medico_solicita" type="hidden" value="{{ $listaEspera->run_medico_solicita }}">
									<input name="dv_medico_solicita" id="dv_medico_solicita" type="hidden" value="{{ $listaEspera->dv_medico_solicita }}">
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="fechacitado_lbl" class="control-label">Fecha Citación</label>
									<div class="form-inline">
										<input id="fechacitacion" type="text" class="form-control" name="fechacitacion" placeholder="dd-mm-yyyy"  maxlength="10" value="{{ $fechacitacion }}" autocomplete="off">
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6">
									<label for="cie10s_id" class="control-label">Diagnóstico (CIE-10)</label>
									<input name="idCie10"   id="idCie10"   type="hidden" value="{{ $listaEspera->cie10s_id }}">
									<input name="cie10s_id" id="cie10s_id" type="text" class="form-control" title="Ingrese Diagnóstico. Búsqueda predictiva." value="{{ $cie10 }}" required autofocus>
								</div>
							</div>
							<div class="row row-padding">
								<div class="col-xs-12 col-sm-12 col-md-6">
									<label for="especialidads_ingreso_id" class="control-label">Especialidad</label>
									<select id="especialidads_ingreso_id" class="form-control" name="especialidads_ingreso_id" required autofocus>
										<option value="">Seleccione</option>
										@foreach($especialidads as $especialidad)
											@if($especialidad->id == $listaEspera->especialidads_ingreso_id )
												<option value="{{ $especialidad->id }}" selected>{{ $especialidad->name }}</option>
											@else
												<option value="{{ $especialidad->id }}">{{ $especialidad->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6">
									<label for="prestamin" class="control-label">Prestamin</label>
									<input id="prestamin" type="text" class="form-control" name="prestamin" value="{{ $listaEspera->prestamin_ing }}" readonly>
								</div>
							</div>
							<div class="row row-padding row-border">
								<div class="col-xs-12 col-sm-12 col-md-12">
									<label for="precdiag" class="control-label">Precisión Diagnóstica</label>
									<textarea class="form-control" rows="3"  id="precdiag" name="precdiag" maxlength="1000" required autofocus>{{ $listaEspera->precdiag }}</textarea>
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
												@if($tipoPrestacion->id == $listaEspera->tipo_prestacions_id)
													<option value="{{ $tipoPrestacion->id }}" selected>{{ $tipoPrestacion->name }}</option>
												@else
													<option value="{{ $tipoPrestacion->id }}">{{ $tipoPrestacion->name }}</option>
												@endif
											@endforeach
										</select>
								</div>
								@if( $listaEspera->tipo_prestacions_id != 1)
									<div class="col-xs-12 col-sm-6 col-md-4" id='tipo_procedimiento'>
										<label for="tipo_procedimiento_id" class="control-label">Tipo Procedimiento</label>
										<select id="tipo_procedimiento_id" class="form-control" name="tipo_procedimiento_id" required>
											<option value="">Seleccione</option>
											@foreach($procedimientos as $procedimiento)
												@if($procedimiento->id == $procedimiento_id)
													<option value="{{ $procedimiento->id }}" selected>{{ $procedimiento->name }}</option>
												@else
													<option value="{{ $procedimiento->id }}">{{ $procedimiento->name }}</option>
												@endif
											@endforeach
										</select>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-4" id='tipo_procedimiento_pm'>
										<label for="tipo_procedimiento_pm_id" class="control-label">Procedimiento</label>
										<select id="tipo_procedimiento_pm_id" class="form-control" name="tipo_procedimiento_pm_id" required>
											<option value="">Seleccione</option>
											@foreach($procedimientopms as $procedimientopm)
												@if($procedimientopm->id == $procedimientopm_id)
													<option value="{{ $procedimientopm->id }}" selected>{{ $procedimientopm->name }}</option>
												@else
													<option value="{{ $procedimientopm->id }}">{{ $procedimientopm->name }}</option>
												@endif
											@endforeach
										</select>
									</div>
								@else
									<div class="col-xs-12 col-sm-6 col-md-4" id='tipo_procedimiento' hidden>
										<label for="tipo_procedimiento_id" class="control-label">Tipo Procedimiento</label>
										<select id="tipo_procedimiento_id" class="form-control" name="tipo_procedimiento_id">
											<option value="">Seleccione</option>
										</select>
									</div>

									<div class="col-xs-12 col-sm-6 col-md-4" id='tipo_procedimiento_pm' hidden>
										<label for="tipo_procedimiento_pm_id" class="control-label">Procedimiento</label>
										<select id="tipo_procedimiento_pm_id" class="form-control" name="tipo_procedimiento_pm_id">
											<option value="">Seleccione</option>
										</select>
									</div>
								@endif
							</div>
						</div>

						<!--Información de Intervención Quirúrgica-->
						@if($listaEspera->tipo_prestacions_id == 4 || $listaEspera->tipo_prestacions_id == 5)
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
						<br>
						@if($listaEspera->tipo_prestacions_id == 4 || $listaEspera->tipo_prestacions_id == 5)
						<div id="intervencion" class="collapse in">
						@else
						<div id="intervencion" class="collapse in" hidden>
						@endif
							<div class="row row-padding row-border">
								@if($listaEspera->tipo_prestacions_id == 4 || $listaEspera->tipo_prestacions_id == 5)
									<div class="col-xs-12 col-sm-6 col-md-6" id="plano_div">
								@else
									<div class="col-xs-12 col-sm-6 col-md-6" id="plano_div" hidden>
								@endif
									<label for="planos_id" class="control-label">Plano</label>
									<select id="planos_id" class="form-control" name="planos_id" autofocus>
										<option value="">Seleccione</option>
									</select>
								</div>
								@if($listaEspera->tipo_prestacions_id == 4 || $listaEspera->tipo_prestacions_id == 5)
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

						@if($listaEspera->active==0)
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

						<div id="egreso" class="collapse in">
							<div class="row row-padding">
								<div class="col-md-4" id="plano_div">
									<label for="fechaSalida" class="control-label">Fecha</label>
									<input id="fechaSalida" type="text" class="form-control" name="fechaSalida" required placeholder="dd-mm-yyyy"  maxlength="10" value="{{ $fechaegreso }}" required>
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
											@if($tipo->id == $listaEspera->establecimientos_id_resuelve)
												<option value="{{ $tipo->id }}" selected>{{ $tipo->name }}</option>
											@else
												<option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
							<div class="row row-border row-padding">
								<div class="col-md-4" id="divCausalEgreso">
									<label for="idCausalEgreso" class="control-label">Causal Egreso</label>
									<select id="idCausalEgreso" class="form-control" name="idCausalEgreso" required>
										<option value="">Seleccione</option>
										@foreach($CausalEgresos as $tipo)
											@if($tipo->id == $listaEspera->causal_egresos_id)
												<option value="{{ $tipo->id }}" selected>{{ $tipo->id }} - {{ $tipo->name }}</option>
											@else
												<option value="{{ $tipo->id }}">{{ $tipo->id }} - {{ $tipo->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
								<div class="col-md-4" id="runResol">
									<label for="run_medico2" class="control-label">R.U.N. Médico Resuelve</label>
									<input id="run_medico2" type="text" class="form-control" name="run_medico2" onchange="validaRut2()" value="{{ $listaEspera->run_medico_resol }}-{{ $listaEspera->dv_medico_resol }}" autofocus>
									<!--Elementos ocultos-->
									<input name="run_medico_resol" id="run_medico_resol" value="{{ $listaEspera->run_medico_resol }}" type="hidden">
									<input name="dv_medico_resol" id="dv_medico_resol" value="{{ $listaEspera->dv_medico_resol }}" type="hidden">
								</div>
								<div class="col-md-4" id="resultado">
									<label for="resultado" class="control-label">Resultado</label>
									<input id="resultado" type="text" class="form-control" name="resultado" maxlength="190" value="{{ $listaEspera->resultado }}"  autofocus>
								</div>
							</div>
						</div>


						<!-- Estado -->
						<div class="row row-border" id="grupo_estado">
							<div class="col-xs-10 col-sm-10 col-md-11">
								<h5>Estado de Lista de Espera</h5>
							</div>
							<div class="col-xs-1 col-sm-1 col-md-1">
								<h5>
									<a href="#" class="pull-right"  data-toggle="collapse" data-target="#estado">
										<span class="glyphicon glyphicon-plus"></span>
									</a>
								</h5>
							</div>
						</div>
						<br>
						<div id="estado" class="collapse in">
							<div class="row row-border row-padding row-border">
								<div class="col-md-4" id="estado_div">
									<label for="estado" class="control-label">Estado</label>
									<select id="estado" class="form-control" name="estado" required>
										@if($listaEspera->active==0)
											<option value="0" selected>Cerrada</option>
											<option value="1" >Abierta</option>
											<option value="2" >Eliminada</option>
										@elseif($listaEspera->active==1)
											<option value="0" >Cerrada</option>
											<option value="1" selected>Abierta</option>
											<option value="2" >Eliminada</option>
										@else
											<option value="0" >Cerrada</option>
											<option value="1" >Abierta</option>
											<option value="2" selected>Eliminada</option>
										@endif
									</select>
								</div>
							</div>
						</div>
						@else
						<div class="row row-border" id="grupo_estado">
							<div class="col-xs-10 col-sm-10 col-md-11">
								<h5>Estado de Lista de Espera</h5>
							</div>
							<div class="col-xs-1 col-sm-1 col-md-1">
								<h5>
									<a href="#" class="pull-right"  data-toggle="collapse" data-target="#estado">
										<span class="glyphicon glyphicon-plus"></span>
									</a>
								</h5>
							</div>
						</div>
						<br>
						<div id="estado" class="collapse in">
							<div class="row row-border row-padding">
								<div class="col-md-4" id="estado_div">
									<label for="estado" class="control-label">Estado</label>
									<select id="estado" class="form-control" name="estado" required>
										@if($listaEspera->active==1)
											<option value="1" selected>Abierta</option>
											<option value="2" >Eliminada</option>
										@else
											<option value="0" >Cerrada</option>
											<option value="1" >Abierta</option>
											<option value="2" selected>Eliminada</option>
										@endif
									</select>
								</div>
							</div>
						</div>
						@endif
						<!--fin de lista espera - egreso-->

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

<!--VERIFICADOR RUT MEDICO RESOL-->
<script>
function validaRut2()
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
<!--FIN VERIFICADOR DE RUT MEDICO RESOL-->

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
@if($listaEspera->tipo_prestacions_id == 4 || $listaEspera->tipo_prestacions_id == 5)
	@if($procedimientopm_id) != null)
	<script>
	$(function() {
		$.get("{{ URL::to('getPrestamin') }}/{{ $procedimientopm_id }}",function(response,state)
			{
				if (response.requiere_plano == 1 )
				{
					$("#planos_id").prop('required',true);
					//agrega lista de planos
					$.get("{{ URL::to('getPlano') }}/{{ $procedimientopm_id }}",function(response1,state)
				      	{
				        $("#planos_id").empty();
				        $("#planos_id").append("<option value=''>Seleccione</option>");
				        for(i=0;i<response1.length;i++)
				        	{
					            @if($listaEspera->planos_id != null)
						            if ( response1[i].id == {{ $listaEspera->planos_id }}){
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
					$.get("{{ URL::to('getExtremidad') }}/{{ $procedimientopm_id }}",function(response2,state)
				      	{
				        $("#extremidads_id").empty();
				        $("#extremidads_id").append("<option value=''>Seleccione</option>");
				        for(i=0;i<response2.length;i++)
				        	{
					        	@if($listaEspera->extremidads_id != null) 
						        	if ( response2[i].id == {{ $listaEspera->extremidads_id }}){
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