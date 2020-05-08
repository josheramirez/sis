@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Guardado o Actualización de Planos-->
	<?php $message=Session::get('message') ?>
	@if($message == 'store')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Tipo Salida Creada Exitosamente
		</div>
	@elseif($message == 'update')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Tipo Salida Modificada Exitosamente
		</div>
	@endif
	<!--FIN Mensajes de Guardado o Actualización de Planos-->
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Tipo Salida</div>
                <div class="panel-body">
                    {{ csrf_field() }} 
					<div class="row">
						<!-- Boton Crear Nuevo Plano -->
						<div class="col-md-6">
							<a class="btn btn-sm btn-primary" href="{{ URL::to('tipoSalidas/create') }}">Crear Tipo Salida</a>
						</div>
						
					</div>
					</br>
					<!-- Lista de Planos -->		
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
								  @foreach($tiposalidas as $tiposalida)
								  <tr>
									<td>{{ $tiposalida->name }}</td>
									<td>
										@if( $tiposalida->active == 1 )
											Activo
										@else
											Inactivo
										@endif
									</td>
									<td><a href="{{ URL::to('tipoSalidas/' . $tiposalida->id . '/edit') }}">Editar</a></td>
								  </tr>
								  @endforeach
								</tbody>
							</table>
							<!--paginacion-->
							{{ $tiposalidas->links() }}
						</div>
					</div>
					<!-- FIN Lista de tipos de salidas -->			
                </div>
            </div>
        </div>
    </div>
</div>
@endsection