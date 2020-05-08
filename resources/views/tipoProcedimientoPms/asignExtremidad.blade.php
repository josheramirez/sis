@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
			<!--BreadCrumb-->
			<ol class="breadcrumb">
			  <li><a href="{{ URL::to('tipoProcedimientosPm') }}">Tipos de Procedimientos PM</a></li>
			  <li class="active">Asignar Extremidad</li>
			</ol>
			<!--FIN BreadCrumb-->
            <div class="panel panel-default">
				<div class="panel-heading">Asignar Extremidad a Procedimientos PM</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ URL::to('tipoProcedimientosPm/saveExtremidad') }}">
						{{ csrf_field() }} 
						<!--Lista de Selección Multiple-->
						<div class="form-group">
                            <label for="procExtremidads" class="col-md-4 control-label">Extremidades</label>

                            <div class="col-md-6">
								<select id="procExtremidads" name="procExtremidads[]" class="form-control" multiple size="10" required>
									@foreach($extremidads as $extremidad)
										@if($procedimientos_pms->isExtremidad($extremidad->name))
											<option value="{{ $extremidad->id }}" selected>{{ $extremidad->name }}</option>
										@else
											<option value="{{ $extremidad->id }}">{{ $extremidad->name }}</option>
										@endif
									@endforeach
								</select>    
                            </div>
                        </div>
						<!--ID Usuario-->
						<input type="hidden" name="procedimientosPmsID" id="procedimientosPmsID" value="{{$id}}">
						
						<!--Boton Submit-->
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Asignar
                                </button>
                            </div>
                        </div>
					</form>
				</div>
			</div>
        </div>
    </div>
</div>
@endsection