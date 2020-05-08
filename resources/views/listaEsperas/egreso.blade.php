@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Actualización de Lista de Espera-->
	<?php $message=Session::get('message') ?>
	@if($message == 'actualiza')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Lista de Espera Egresada Exitosamente
		</div>
	@elseif($message == 'egresada')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Lista de Espera Ya Fue Egresada
		</div>	
	@endif
	<!--FIN Mensajes de Actualización de Lista de Espera -->
    <div class="row">
        <div class="col-md-12 col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Egreso - Lista de Espera</div>
                <div class="panel-body">
                    {{ csrf_field() }}
					<div class="row">
						<div class="col-md-12">
							<table class="table table-striped">
								<thead>
								  <tr>
									<th>RUN / Nro de Documento</th>
									<th>Nombre</th>
									<th>Ap. Paterno</th>
									<th>Ap. Materno</th>
									<th>Especialidad</th>
									<th>Fecha Ingreso</th>
									<th>Establecimiento Origen</th>
									<th>Establecimiento Destino</th>
									<th>Prestamin</th>
									<th>Tipo Prestación</th>
									<th>Revisar</th>
								  </tr>
								</thead>
								<tbody>
									@foreach($listaesperas as $listaespera)
										<tr>
											<td>
											@if ( $listaespera->tipoDoc == 1 )
												{{ $listaespera->rut }} - {{ $listaespera->dv }}
											@else
												{{ $listaespera->numDoc }}
											@endif		
											</td>
											<td>{{ $listaespera->nombre }}</td>
											<td>{{ $listaespera->apPaterno }}</td>
											<td>{{ $listaespera->apMaterno }}</td>
											<td>{{ $listaespera->especialidad }}</td>
											<td>{{ $listaespera->fecha }}</td>
											<td>{{ $listaespera->establecimiento_origen }}</td>
											<td>{{ $listaespera->establecimiento_destino }}</td>
											<td>{{ $listaespera->prestamin }}</td>
											<td>{{ $listaespera->prestacion }}</td>
											<td><a href="{{ URL::to('listaesperas/' . $listaespera->id . '/detalle') }}">Egresar</a></td>
										</tr>
								  @endforeach
								</tbody>
							</table>
							<!--paginacion-->
							{{ $listaesperas->links() }}
						</div>
					</div>
					<!-- FIN Lista de Listas de Espera -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection