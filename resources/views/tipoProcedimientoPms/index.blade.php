@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Guardado o Actualización de Tipos-->
	<?php $message=Session::get('message') ?>
	@if($message == 'store')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Tipo de Procedimiento PM Creado Exitosamente
		</div>
	@elseif($message == 'update')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Tipo de Procedimiento PM Modificada Exitosamente
		</div>
	@elseif($message == 'plano')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Planos Asignados Exitosamente
		</div>	
	@elseif($message == 'extremidad')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Extremidades Asignadas Exitosamente
		</div>	
	@endif
	<!--FIN Mensajes de Guardado o Actualización de Tipos-->
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Lista de Tipos de Procedimientos PM</div>
                <div class="panel-body">
                    {{ csrf_field() }}
					<div class="row">
						<!-- Boton Crear Nuevo Tipo -->
						<div class="col-md-4">
							<a class="btn btn-sm btn-primary" href="{{ URL::to('tipoProcedimientosPm/create') }}">Crear Tipo de Procedimiento PM</a>
						</div>
						<!-- Formulario de Filtro por Nombre -->
						<div class="col-md-4">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('tipoProcedimientosPm') }}">
								<div class="input-group">
									<input id="searchNombre" name="searchNombre" type="text" class="form-control input-sm" placeholder="Buscar por Nombre">
									<span class="input-group-btn ">
										<button class="btn btn-default btn-sm" type="submit">Ir</button>
									</span>
								</div>
							</form>
						</div>
						<!-- Formulario de Filtro por Prestamin -->
						<div class="col-md-4">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('tipoProcedimientosPm') }}">
								<div class="input-group">
									<input id="searchPrestamin" name="searchPrestamin" type="text" class="form-control input-sm" placeholder="Buscar por Código Prestamin">
									<span class="input-group-btn ">
										<button class="btn btn-default btn-sm" type="submit">Ir</button>
									</span>
								</div>
							</form>
						</div>
					</div>
					</br>
					<!-- Lista de Tipos de Prestación -->
					<div class="row">
						<div class="col-md-12">
							<table class="table table-striped">
								<thead>
								  <tr>
									<th class="col-md-3">Nombre</th>
									<th class="col-md-3">Tipo Procedimiento</th>
									<th class="col-md-2">Tipo Prestación</th>
									<th class="col-md-1">Prestamin</th>
									<th class="col-md-1">Estado</th>
									<th class="col-md-1">Editar</th>
									<th class="col-md-1">Asignar</th>
								  </tr>
								</thead>
								<tbody>
								  @foreach($tipos as $tipo)
								  <tr>
									<td>{{ $tipo->name }}</td>
									<td>{{ $tipo->tipoprocedimiento->name }}</td>
									<td>{{ $tipo->tipoprocedimiento->tipoprestacion->name }}</td>
									<td>{{ $tipo->prestamin }}</td>
									<td>
										@if( $tipo->active == 1 )
											Activo
										@else
											Inactivo
										@endif
									</td>
									<td><a href="{{ URL::to('tipoProcedimientosPm/' . $tipo->id . '/edit') }}">Editar</a></td>
									<td><a href="{{ URL::to('tipoProcedimientosPm/asignPlano/' . $tipo->id ) }}">Plano</a> | 
									    <a href="{{ URL::to('tipoProcedimientosPm/asignExtremidad/' . $tipo->id ) }}">Extremidad</a></td>
								  </tr>
								  @endforeach
								</tbody>
							</table>
							<!--paginacion-->
							{{ $tipos->links() }}
						</div>
					</div>
					<!-- FIN Lista de tipos de Prestacion -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
