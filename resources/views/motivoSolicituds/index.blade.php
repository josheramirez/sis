@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Guardado o Actualización de Motivo de Solicitud-->
	<?php $message=Session::get('message') ?>
	@if($message == 'store')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Motivo Solicitud Creado Exitosamente
		</div>
	@elseif($message == 'update')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Motivo Solicitud Modificado Exitosamente
		</div>
	@endif
	<!--FIN Mensajes de Guardado o Actualización de Motivo de Solicitud-->
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Lista de Motivos de Solicitud</div>
                <div class="panel-body">
                    {{ csrf_field() }} 
					<div class="row">
						<!-- Boton Crear Nuevo Motivo Solicitud -->
						<div class="col-md-6">
							<a class="btn btn-sm btn-primary" href="{{ URL::to('motivoSolicituds/create') }}">Crear Motivo de Solicitud</a>
						</div>
						
					</div>
					</br>
					<!-- Lista de Motivo de Solicitud -->		
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
								  @foreach($motivosolicituds as $motivosolicitud)
								  <tr>
									<td>{{ $motivosolicitud->name }}</td>
									<td>
										@if( $motivosolicitud->active == 1 )
											Activo
										@else
											Inactivo
										@endif
									</td>
									<td><a href="{{ URL::to('motivoSolicituds/' . $motivosolicitud->id . '/edit') }}">Editar</a></td>
								  </tr>
								  @endforeach
								</tbody>
							</table>
							<!--paginacion-->
							{{ $motivosolicituds->links() }}
						</div>
					</div>
					<!-- FIN Lista de Motivo Solicitud -->			
                </div>
            </div>
        </div>
    </div>
</div>
@endsection