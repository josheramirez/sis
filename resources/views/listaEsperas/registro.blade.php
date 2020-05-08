@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Guardado o Actualización de Lista de Espera-->
	<?php $message=Session::get('message') ?>
	<?php $id=Session::get('id') ?>
	@if($message == 'actualiza')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Lista Espera <?=$id?> Se Ha Actualizado Exitosamente
		</div>
	@endif
	
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Registro Listas de Esperas</div>
                <div class="panel-body">
                    {{ csrf_field() }}
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped">
									<thead>
										<tr>
											<th>ID</th>
											<th>ID SIGTE</th>
											<th>Run  /  Documento </th>
											<th>Nombre</th>
											<th>Prestación</th>
											<th>Prestamin</th>
											<th>Especialidad</th>
											<th>Fecha Creación</th>
											<th>Fecha Entrada</th>
											<th>Fecha Modificación</th>
											<th>Establecimiento Origen</th>
											<th>Establecimiento Destino</th>
											<th>Diagnóstico (CIE-10)</th>
											<th>Comuna</th>
											<th>Estado</th>
											<th>Detalle</th>
										</tr>
									</thead>
									<tbody>
										@foreach($listaesperas as $listaespera)
											<tr>
												<td>{{ $listaespera->id }}</td>
												<td>{{ $listaespera->id_sigte }}</td>
												<td>
												@if ( $listaespera->tipo_doc == 1 )
													{{ $listaespera->run }} - {{ $listaespera->dv }}
												@else
													{{ $listaespera->documento }}
												@endif		
												</td>
												<td>{{ $listaespera->nombres . ' '. $listaespera->primer_apellido . ' ' . $listaespera->segundo_apellido }}</td>
												<td>{{ $listaespera->tipo_prest }}</td>
												<td>{{ $listaespera->presta_min }}</td>
												<td>{{ $listaespera->presta_est }}</td> 
												<td>{{ $listaespera->fecha_digitacion }}</td>
												<td>{{ $listaespera->f_entrada }}</td>
												<td>{{ $listaespera->fecha_modificacion }}</td>
												<td>{{ $listaespera->estab_orig }}</td>
												<td>{{ $listaespera->estab_dest }}</td>
												<td>
												@if ( $listaespera->cie10s_id == 0 )
													{{ $listaespera->cie10_ant }} 
												@else
													{{ $listaespera->sospecha_diag }}
												@endif			
												</td> 
												<td>{{ $listaespera->comuna }}</td> 
												<td>
												@if ( $listaespera->estado == 1 )
													Abierta
												@elseif ( $listaespera->estado == 0 )
													Cerrada
												@else 
													Eliminada		
												@endif		
												</td>
												<td>
													<a href="{{ URL::to('listaesperas/' . $listaespera->id . '/editar') }}">Editar</a>
													<a href="{{ URL::to('listaesperas/' . $listaespera->id . '/bitacora') }}">Bitácora</a>
												</td>

											</tr>
									@endforeach
									</tbody>
								</table>
							</div>	
							<!--paginacion-->
							{{ $listaesperas->links() }}
						</div>
					</div>
					<!-- FIN Lista de Cie10 -->			
                </div>
            </div>
        </div>
    </div>
</div>
@endsection