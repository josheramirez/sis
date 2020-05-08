@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
			<!--BreadCrumb-->
			<ol class="breadcrumb">
			  <li><a href="{{ URL::to('tipoProcedimientosPm') }}">Tipos de Procedimientos PM</a></li>
			  <li class="active">Asignar Plano</li>
			</ol>
			<!--FIN BreadCrumb-->
            <div class="panel panel-default">
				<div class="panel-heading">Asignar Plano a Procedimientos PM</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ URL::to('tipoProcedimientosPm/savePlano') }}">
						{{ csrf_field() }} 
						<!--Lista de Selección Multiple-->
						<div class="form-group">
                            <label for="procPlanos" class="col-md-4 control-label">Planos</label>

                            <div class="col-md-6">
								<select id="procPlanos" name="procPlanos[]" class="form-control" multiple size="10" required>
									@foreach($planos as $plano)
										@if($procedimientos_pms->isPlano($plano->name))
											<option value="{{ $plano->id }}" selected>{{ $plano->name }}</option>
										@else
											<option value="{{ $plano->id }}">{{ $plano->name }}</option>
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