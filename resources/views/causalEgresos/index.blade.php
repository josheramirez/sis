@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Guardado o Actualización de causal de egreso-->
	<?php $message=Session::get('message') ?>
	@if($message == 'store')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Causal Egreso Creado Exitosamente
		</div>
	@elseif($message == 'update')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Causal Egreso Modificado Exitosamente
		</div>
	@endif
	<!--FIN Mensajes de Guardado o Actualización de causal de egreso-->
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Lista de Causales de Egreso </div>
                <div class="panel-body">
                    {{ csrf_field() }} 
					<div class="row">
						<!-- Boton Crear Nueva Servicio -->
						<div class="col-md-6">
							<a class="btn btn-sm btn-primary" href="{{ URL::to('causalEgresos/create') }}">Crear Causal de Egreso</a>
						</div>
						
					</div>
					</br>
					<!-- Lista de Causal de Egreso -->		
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
								  @foreach($causalegresos as $causalegreso)
								  <tr>
									<td>{{ $causalegreso->name }}</td>
									<td>
										@if( $causalegreso->active == 1 )
											Activo
										@else
											Inactivo
										@endif
									</td>
									<td><a href="{{ URL::to('causalEgresos/' . $causalegreso->id . '/edit') }}">Editar</a></td>
								  </tr>
								  @endforeach
								</tbody>
							</table>
							<!--paginacion-->
							{{ $causalegresos->links() }}
						</div>
					</div>
					<!-- FIN Lista de Causal Egreso -->			
                </div>
            </div>
        </div>
    </div>
</div>
@endsection