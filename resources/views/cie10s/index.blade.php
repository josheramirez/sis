@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Guardado o Actualización de Cie10-->
	<?php $message=Session::get('message') ?>
	@if($message == 'store')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Diagnóstico CIE-10 Creado Exitosamente
		</div>
	@elseif($message == 'update')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Diagnóstico CIE-10 Modificado Exitosamente
		</div>
	@endif
	<!--FIN Mensajes de Guardado o Actualización de Cie10-->
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Lista de Diagnósticos CIE-10</div>
                <div class="panel-body">
                    {{ csrf_field() }} 
					<div class="row">
						<!-- Boton Crear Nuevo Cie10 -->
						<div class="col-md-2">
							<a class="btn btn-sm btn-primary" href="{{ URL::to('cie10s/create') }}">Crear Diagnóstico CIE-10</a>
						</div>
						<!-- Formulario de Filtro por Código -->
						<div class="col-md-5">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('cie10s') }}">
								<div class="input-group">
									<input id="searchCodigo" name="searchCodigo" type="text" class="form-control input-sm" placeholder="Buscar por Código">
									<span class="input-group-btn ">
										<button class="btn btn-default btn-sm" type="submit">Ir</button>
									</span>
								</div>
							</form>
						</div>
						<!-- Formulario de Filtro por Nombre -->
						<div class="col-md-5">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('cie10s') }}">
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
					<!-- Lista de Cie10 -->		
					<div class="row">
						<div class="col-md-12">
							<table class="table table-striped">
								<thead>
								  <tr>
									<th>Código</th>
									<th>Nombre</th>
									<th>Estado</th>
									<th>Editar</th>
								  </tr>
								</thead>
								<tbody>
								  @foreach($cie10s as $cie10)
								  <tr>
									<td>{{ $cie10->codigo }}</td>
									<td>{{ $cie10->name }}</td>
									<td>
										@if( $cie10->active == 1 )
											Activo
										@else
											Inactivo
										@endif
									</td>
									<td><a href="{{ URL::to('cie10s/' . $cie10->id . '/edit') }}">Editar</a></td>
								  </tr>
								  @endforeach
								</tbody>
							</table>
							<!--paginacion-->
							{{ $cie10s->links() }}
						</div>
					</div>
					<!-- FIN Lista de Cie10 -->			
                </div>
            </div>
        </div>
    </div>
</div>
@endsection