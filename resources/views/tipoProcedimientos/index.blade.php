@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Guardado o Actualización de Tipos-->
	<?php $message=Session::get('message') ?>
	@if($message == 'store')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Tipo de Procedimiento creado Exitosamente
		</div>
	@elseif($message == 'update')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Tipo de Procedimiento Modificado Exitosamente
		</div>
	@endif
	<!--FIN Mensajes de Guardado o Actualización de Tipos-->
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Lista de Tipos de Procedimientos</div>
                <div class="panel-body">
                    {{ csrf_field() }} 
					<div class="row">
						<!-- Boton Crear Nuevo Tipo -->
						<div class="col-md-6">
							<a class="btn btn-sm btn-primary" href="{{ URL::to('tipoProcedimientos/create') }}">Crear Tipo de Procedimiento </a>
						</div>
						<!-- Formulario de Filtro por Código -->
						<div class="col-md-6">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('tipoProcedimientos') }}">
								<div class="input-group">
									<input id="searchNombre" name="searchNombre" type="text" class="form-control input-sm" placeholder="Buscar por Nombre">
									<span class="input-group-btn ">
										<button class="btn btn-default btn-sm" type="submit">Ir</button>
									</span>
								</div>
							</form>
						</div>
					</div>
					</br>
					<!-- Lista de Tipos de Procedimiento -->		
					<div class="row">
						<div class="col-md-12">
							<table class="table table-striped">
								<thead>
								  <tr>
									<th>Nombre</th>
									<th>Tipo Prestación</th>
									<th>Estado</th>
									<th>Editar</th>
								  </tr>
								</thead>
								<tbody>
								  @foreach($tipos as $tipo)
								  <tr>
									<td>{{ $tipo->name }}</td>
									<td>{{ $tipo->tipo_prestacion }}</td>
									<td>
										@if( $tipo->active == 1 )
											Activo
										@else
											Inactivo
										@endif
									</td>
									<td><a href="{{ URL::to('tipoProcedimientos/' . $tipo->id . '/edit') }}">Editar</a></td>
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