@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Consulta Red</div>
                <div class="panel-body">
                    {{ csrf_field() }}
					<div class="row">
						<div class="col-md-6">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('/listaesperas/excel') }}">

								<input type="hidden" name="optfechaing" id="optfechaing" value="{{$optfechaing}}">
								<input type="hidden" name="desde" id="desde" value="{{$desde}}">
								<input type="hidden" name="hasta" id="hasta" value="{{$hasta}}">

								<input type="hidden" name="optfechadig" id="optfechadig" value="{{$optfechadig}}">
								<input type="hidden" name="digdesde" id="digdesde" value="{{$digdesde}}">
								<input type="hidden" name="dighasta" id="dighasta" value="{{$dighasta}}">

								<input type="hidden" name="optfechasal" id="optfechasal" value="{{$optfechasal}}">
								<input type="hidden" name="saldesde" id="saldesde" value="{{$saldesde}}">
								<input type="hidden" name="salhasta" id="salhasta" value="{{$salhasta}}">

								<input type="hidden" name="idPaciente" id="idPaciente" value="{{$idPaciente}}">
								<input type="hidden" name="establecimiento" id="establecimiento" value="{{$establecimiento}}">
								<input type="hidden" name="estadestino" id="estadestino" value="{{$estadestino}}">
								<input type="hidden" name="estaresuelve" id="estaresuelve" value="{{$estaresuelve}}">
								<input type="hidden" name="comuna" id="comuna" value="{{$comuna}}">
								<input type="hidden" name="prestacion" id="prestacion" value="{{$prestacion}}">
								<input type="hidden" name="especialidad" id="especialidad" value="{{$especialidad}}">
								<input type="hidden" name="estado" id="estado" value="{{$estado}}">

								<button class="btn btn-sm btn-primary" type="submit">Exportar a Excel</button>
								<span class="label label-default">(Hasta 1000 registros)</span>
							</form>
						</div>
					</div>
					</br>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped">
									<thead>
										<tr>
											<th>ID</th>
											<th>ID SIGTE</th>
											<th>Tipo Documento</th>
											<th>Run  /  Documento </th>
											<th>Nombres</th>
											<th>Apellido Paterno</th>
											<th>Apellido Materno</th>
											<th>Previsión</th>
											<th>Prestación</th>
											<th>Prestamin</th>
											<th>Prestamin Salida</th>
											<th>Especialidad</th>
											<th>Fecha Entrada</th>
											<th>Fecha Salida</th>
											<th>Fecha Modificación</th>
											<th>Diagnóstico (CIE-10)</th>
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
												<td>{{ $listaespera->nombres }}</td>
												<td>{{ $listaespera->primer_apellido }}</td>
												<td>{{ $listaespera->segundo_apellido }}</td>
												<td>{{ $listaespera->prevision }}</td>
												<td>{{ $listaespera->tipo_prest }}</td>
												<td>{{ $listaespera->presta_min }}</td>
												<td>{{ $listaespera->presta_min_salida }}</td>
												<td>{{ $listaespera->presta_est }}</td>
												<td>{{ $listaespera->f_entrada }}</td>
												<td>{{ $listaespera->f_salida }}</td>
												<td>{{ $listaespera->fecha_modificacion }}</td>
												<td>
												@if ( $listaespera->cie10s_id == 0 )
													{{ $listaespera->cie10_ant }}
												@else
													{{ $listaespera->sospecha_diag }}
												@endif
												</td>
												<td>
													@if ( $listaespera->estado == 1 )
														Abierta
													@else
														Cerrada
													@endif
												</td>
												<td><a href="{{ URL::to('listaesperas/' . $listaespera->id . '/visualizar') }}">Detalle</a></td>
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
