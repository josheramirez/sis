@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Guardado o Actualización de Mapa de Derivación-->
	<?php $message=Session::get('message') ?>
	@if($message == 'store')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Diagnóstico Mapa de Derivación Creado Exitosamente
		</div>
	@elseif($message == 'update')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Diagnóstico Mapa de Derivación Exitosamente
		</div>
	@endif
	<!--FIN Mensajes de Guardado o Actualización de Mapa de Derivación-->
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Lista de Mapa de Derivación</div>
                <div class="panel-body">
                    {{ csrf_field() }} 
					<div class="row">
						<!-- Boton Crear Nuevo Mapa de Derivación -->
						<div class="col-md-3">
							<a class="btn btn-sm btn-primary" href="{{ URL::to('mapaDerivacions/create') }}">Crear Mapa de Derivación</a>
						</div>
						<!-- Formulario de Filtro por Establecimientos -->
						<div class="col-md-4 col-md-offset-1">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('mapaDerivacions') }}">
								<div class="input-group">
									<select id="searchContraref" class="form-control input-sm" name="searchContraref">
										<option value="">Seleccione Establecimiento Contrarrefiere</option>
										@foreach($establecimientos as $establecimiento)
											<option value="{{ $establecimiento->id }}">{{ $establecimiento->name }}</option>
										@endforeach
									</select>
									<span class="input-group-btn ">
										<button class="btn btn-default btn-sm" type="submit">Ir</button>
									</span>
								</div>
							</form>
						</div>
						<div class="col-md-4">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('mapaDerivacions') }}">	
								<div class="input-group">
									<select id="searchOrigen" class="form-control input-sm" name="searchOrigen">
										<option value="">Seleccione Establecimiento Origen</option>
										@foreach($establecimientos as $establecimiento)
											<option value="{{ $establecimiento->id }}">{{ $establecimiento->name }}</option>
										@endforeach
									</select>
									<span class="input-group-btn ">
										<button class="btn btn-default btn-sm" type="submit">Ir</button>
									</span>
								</div>
							</form>
						</div>
					</div>
					</br>
					<!-- Lista de Mapa de Derivación -->		
					<div class="row">
						<div class="col-md-12">
							<table class="table table-striped">
								<thead>
								  <tr>
									<th>Especialidad</th>
									<th>Grupo Etario</th>
									<th>Contrarrefiere</th>
									<th>Origen APS</th>
									<th>Estado</th>
									<th>Editar</th>
								  </tr>
								</thead>
								<tbody>
									@foreach($mapas as $mapa)
									<tr>
										<td>{{ $mapa->especialidad }}</td>
										<td>{{ $mapa->etario }}</td>
										<td>{{ $mapa->contraref }}</td>
										<td>{{ $mapa->origen }}</td>
										<td>
											@if( $mapa->active == 1 )
												Activo
											@else
												Inactivo
											@endif
										</td>
										<td><a href="{{ URL::to('mapaDerivacions/' . $mapa->id . '/edit') }}">Editar</a></td>
									</tr>
									@endforeach
								</tbody>
							</table>
							<!--paginacion-->
							{{ $mapas->links() }}
						</div>
					</div>
					<!-- FIN Lista de Mapa de Derivación -->			
                </div>
            </div>
        </div>
    </div>
</div>
@endsection