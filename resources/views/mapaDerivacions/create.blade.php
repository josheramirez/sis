@extends('layouts.app')

@section('content')
<div class="container-fluid">
<!--Mensajes de Guardado o Actualización de Mapa de Derivación-->
	<?php $message=Session::get('message') ?>
	@if($message == 'error')
		<div class="alert alert-success alert-danger" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Mapa de Derivación Ya Existe
		</div>
	@endif
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <!--BreadCrumb-->
			<ol class="breadcrumb">
			  <li><a href="{{ URL::to('mapaDerivacions') }}">Mapa de Derivación</a></li>
			  <li class="active">Crear</li>
			</ol>
			<!--FIN BreadCrumb-->
			<!--Panel Formulario Crear Mapa de Derivación-->
			<div class="panel panel-default">
                <div class="panel-heading">Crear Mapa de Derivación</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('mapaDerivacions') }}">
                        {{ csrf_field() }}
						<!--Campo Especialidad-->
						<div class="form-group{{ $errors->has('especialidad') ? ' has-error' : '' }}">
                            <label for="especialidad" class="col-md-4 control-label">Especialidad</label>
							
                            <div class="col-md-6">
								<select id="especialidad" class="form-control" name="especialidad" required>
									<option value="">Seleccione Especialidad</option>
									@foreach($especialidads as $especialidad)
										@if( $especialidad->id == old('especialidad') )
											<option value="{{ $especialidad->id }}" selected>{{ $especialidad->name }}</option>
										@else
											<option value="{{ $especialidad->id }}">{{ $especialidad->name }}</option>
										@endif	
									@endforeach
								</select>
                            </div>
                        </div>
						<!--Campo Grupo Etario-->
						<div class="form-group{{ $errors->has('etario') ? ' has-error' : '' }}">
                            <label for="etario" class="col-md-4 control-label">Grupo Etario</label>

                            <div class="col-md-6">
								<select id="etario" class="form-control" name="etario" required>
									<option value="">Seleccione Grupo Etario</option>
									@foreach($etarios as $etario)
										@if( $etario->id == old('etario') )
											<option value="{{ $etario->id }}" selected>{{ $etario->name }}</option>
										@else
											<option value="{{ $etario->id }}">{{ $etario->name }}</option>
										@endif	
									@endforeach
								</select>
                            </div>
                        </div>						
						<!--Campo Contrarrefiere-->
						<div class="form-group{{ $errors->has('contraref') ? ' has-error' : '' }}">
                            <label for="contraref" class="col-md-4 control-label">Establecimiento que Contrarrefiere</label>

                            <div class="col-md-6">
								<select id="contraref" class="form-control" name="contraref" required>
									<option value="">Seleccione Establecimiento Contrarrefiere</option>
									@foreach($establecimientos as $establecimiento)
										@if( $establecimiento->id == old('contraref') )
											<option value="{{ $establecimiento->id }}" selected>{{ $establecimiento->name }}</option>
										@else
											<option value="{{ $establecimiento->id }}">{{ $establecimiento->name }}</option>
										@endif	
									@endforeach
								</select>
                            </div>
                        </div>	
						<!--Campo Origen-->
						<div class="form-group{{ $errors->has('origen') ? ' has-error' : '' }}">
                            <label for="origen" class="col-md-4 control-label">Establecimiento de Origen</label>

                            <div class="col-md-6">
								<select id="origen" class="form-control" name="origen" required>
									<option value="">Seleccione Establecimiento Origen</option>
									@foreach($establecimientos as $establecimiento)
										@if( $establecimiento->id == old('origen') )
											<option value="{{ $establecimiento->id }}" selected>{{ $establecimiento->name }}</option>
										@else
											<option value="{{ $establecimiento->id }}">{{ $establecimiento->name }}</option>
										@endif	
									@endforeach
								</select>
                            </div>
                        </div>						
						<!--Lista Activo-->
						<div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
                            <label for="active" class="col-md-4 control-label">Activo</label>

                            <div class="col-md-6">
								<select id="active" class="form-control" name="active" required>
									<option value="1" selected>Si</option>
									<option value="0">No</option>
								</select>
                            </div>
                        </div>
						<!--Boton Submit-->
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
			<!--FIN Panel Formulario Crear Cie10-->
        </div>
    </div>
</div>
@endsection

