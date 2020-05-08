@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Guardado o Actualización de Especialidades-->
	<?php $message=Session::get('message') ?>
	@if($message == 'store')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Especialidad Creada Exitosamente
		</div>
	@elseif($message == 'update')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Especialidad Modificada Exitosamente
		</div>
	@endif
	<!--FIN Mensajes de Guardado o Actualización de Especialidades-->
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Lista de Especialidades</div>
                <div class="panel-body">
                    {{ csrf_field() }} 
					<div class="row">
						<!-- Boton Crear Nueva Especialidad -->
						<div class="col-md-2">
							<a class="btn btn-sm btn-primary" href="{{ URL::to('especialidads/create') }}">Crear Especialidad</a>
						</div>
						<!-- Formulario de Filtro por REM -->
						<div class="col-md-5">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('especialidads') }}">
								<div class="input-group">
									<input id="searchRem" name="searchRem" type="text" class="form-control input-sm" placeholder="Buscar por Código REM">
									<span class="input-group-btn ">
										<button class="btn btn-default btn-sm" type="submit">Ir</button>
									</span>
								</div>
							</form>
						</div>
						<!-- Formulario de Filtro por Nombre -->
						<div class="col-md-5">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('especialidads') }}">
								<div class="input-group">
									<input id="searchName" name="searchName" type="text" class="form-control input-sm" placeholder="Buscar por Nombre">
									<span class="input-group-btn ">
										<button class="btn btn-default btn-sm" type="submit">Ir</button>
									</span>
								</div>
							</form>
						</div>
					</div>
					</br>
					<!-- Lista de Especialidades -->		
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive"> 
								<table class="table table-striped">
									<thead>
									  <tr>
										<th>REM</th>
										<th>DEIS</th>
										<th>SIGTE</th>
										<th>Descripción</th>
										<th>Estado</th>
										<th>Editar</th>
									  </tr>
									</thead>
									<tbody>
									  @foreach($especialidads as $especialidad)
									  <tr>
										<td>{{ $especialidad->rem }}</td>
										<td>{{ $especialidad->deis }}</td>
										<td>{{ $especialidad->sigte }}</td>
										<td>{{ $especialidad->name }}</td>
										<td>
											@if( $especialidad->active == 1 )
												Activo
											@else
												Inactivo
											@endif
										</td>
										<td><a href="{{ URL::to('especialidads/' . $especialidad->id . '/edit') }}">Editar</a></td>
									  </tr>
									  @endforeach
									</tbody>
								</table>
							</div>	
							<!--paginacion-->
							{{ $especialidads->links() }}
						</div>
					</div>
					<!-- FIN Lista de Especialidades -->			
                </div>
            </div>
        </div>
    </div>
</div>
@endsection