@extends('layouts.app4')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-10 col-lg-offset-1">
			<!--Panel Formulario Crear Lista Espera-->
			<div class="panel panel-default">
                <div class="panel-heading">Bitácora Lista Espera</div>
                <div class="panel-body">
						{{ csrf_field() }}
						
						<div id="lep">
							<div class="row">
								<div class="col-md-10 col-md-offset-1">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Tipo Doc.</th>
												<th>Run  /  Documento </th>
												<th>Nombre</th>
												<th>Prestación</th>
												<th>Prestamin</th>
												<th>Especialidad</th>
												<th>Fecha Ingreso</th>
												<th>Fecha Egreso</th>
												<th>Fecha Modificación</th>
												<th>Establecimiento Origen</th>
												<th>Establecimiento Destino</th>
												<th>Diagnóstico (CIE-10)</th>
												<th>Comuna</th>
												<th>Estado</th>
										  	</tr>
										</thead>
										<tbody>
											@foreach($listaesperas as $listaespera)
												<tr>
													<td>
													@if ( $listaespera->tipo_doc == 1 )
														Run
													@else
														Doc.
													@endif		
													</td>
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
													<td>{{ $listaespera->f_entrada }}</td>
													<td>{{ $listaespera->f_egreso }}</td>
													@if ( $listaespera->estado == 1 )
														<td>{{ $listaespera->fecha_digitacion }}</td>
													@elseif ( $listaespera->estado == 0 )
														<td>{{ $listaespera->f_dig_egreso }}</td>
													@else 
														<td>{{ $listaespera->fecha_modificacion }}</td>
													@endif
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
												</tr>
										  @endforeach
										</tbody>
									</table>  
								</div>
							</div>
						</div>	

						<div id="historial">
							<div class="row">
								<div class="col-md-10 col-md-offset-1">
									<table class="table table-striped">
										<thead>
									      <tr>
									        <th>Fecha</th>
									        <th>Acción</th>
									        <th>Usuario</th>
									        <th>Establecimiento</th>
									      </tr>
									    </thead>
									    <tbody>
									    	@foreach($logs as $log)
									      	<tr>
									        	<td>{{ $log->fecha }}</td>
									        	<td>{{ $log->estado }}</td>
									        	<td>{{ $log->usuario }}</td>
									        	<td>{{ $log->establecimiento }}</td>
									      	</tr>
									      	@endforeach
									    </tbody>
								  	</table>
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
    </div>
</div>



@endsection