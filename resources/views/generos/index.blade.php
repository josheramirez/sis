@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Guardado o Actualización de Generos-->
	<?php $message=Session::get('message') ?>
	@if($message == 'store')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Género Creado Exitosamente
		</div>
	@elseif($message == 'update')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Género Modificado Exitosamente
		</div>
	@endif
	<!--FIN Mensajes de Guardado o Actualización de Generos-->
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Lista de Géneros</div>
                <div class="panel-body">
                    {{ csrf_field() }} 
					<div class="row">
						<!-- Boton Crear Nueva Genero -->
						<div class="col-md-6">
							<a class="btn btn-sm btn-primary" href="{{ URL::to('generos/create') }}">Crear Género</a>
						</div>
						
					</div>
					</br>
					<!-- Lista de Generos -->		
					<div class="row">
						<div class="col-md-12">
							<table class="table table-striped">
								<thead>
								  <tr>
									<th>Nombre</th>
									<th>Estado</th>
									<th>Editar</th>
								  </tr>
								</thead>
								<tbody>
								  @foreach($generos as $genero)
								  <tr>
									<td>{{ $genero->name }}</td>
									<td>
										@if( $genero->active == 1 )
											Activo
										@else
											Inactivo
										@endif
									</td>
									<td><a href="{{ URL::to('generos/' . $genero->id . '/edit') }}">Editar</a></td>
								  </tr>
								  @endforeach
								</tbody>
							</table>
							<!--paginacion-->
							{{ $generos->links() }}
						</div>
					</div>
					<!-- FIN Lista de Generos -->			
                </div>
            </div>
        </div>
    </div>
</div>
@endsection