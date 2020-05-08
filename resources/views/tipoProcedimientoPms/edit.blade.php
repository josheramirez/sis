@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
			<!--BreadCrumb-->
			<ol class="breadcrumb">
				<li><a href="{{ URL::to('tipoProcedimientosPm') }}">Tipos de Procedimientos PM</a></li>
				<li class="active">Editar</li>
			</ol>
			<!--FIN BreadCrumb-->
			<!--Panel Formulario Editar Tipo de Procedimiento-->
            <div class="panel panel-default">
                <div class="panel-heading">Editar Tipo de Procedimiento PM</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('tipoProcedimientosPm') }}/{{$tipo->id}}">
                        <input type="hidden" name="_method" value="PUT">
						{{ csrf_field() }}
						<!--Campo Nombre-->
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nombre</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{$tipo->name}}" required autofocus readonly>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!--Lista Comuna-->
                        <div class="form-group{{ $errors->has('tipoprocedimiento') ? ' has-error' : '' }}">
                            <label for="tipoprocedimiento" class="col-md-4 control-label">Tipo Procedimiento</label>

                            <div class="col-md-6">
                                <select id="tipoprocedimiento" class="form-control" name="tipoprocedimiento" required>
                                    <option value="">Seleccione Tipo Procedimiento</option>
                                    @foreach($tipoprocedimiento as $tipoprocedimiento)
                                        @if($tipoprocedimiento->id == $tipo->tipo_procedimiento_id)
                                            <option value="{{ $tipoprocedimiento->id }}" selected>{{ $tipoprocedimiento->name }}</option>
                                        @else
                                            <option value="{{ $tipoprocedimiento->id }}">{{ $tipoprocedimiento->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!--Campo Prestamin-->
                        <div class="form-group{{ $errors->has('prestamin') ? ' has-error' : '' }}">
                            <label for="prestamin" class="col-md-4 control-label">Prestamin</label>

                            <div class="col-md-6">
                               <input id="prestamin" type="text" class="form-control" name="prestamin" value="{{$tipo->prestamin}}"  required pattern="^[0-9]{2}[\-][0-9]{2}[\-][0-9]{3}" title="00-00-000" placeholder="00-00-000" autofocus maxlength="9" readonly>
                            </div>
                        </div>

						<!-- Requiere Plano -->
                        <div class="form-group{{ $errors->has('requiere_plano') ? ' has-error' : '' }}">
                            <label for="requiere_plano" class="col-md-4 control-label">Requiere Plano</label>

                            <div class="col-md-6">
							   <select id="requiere_plano" class="form-control" name="requiere_plano" required>
						   		@if ($tipo->requiere_plano == 1)
								 	<option value="1">Si</option>
								 	<option value="0">No</option>
								@else
									<option value="1">Si</option>
								    <option value="0" selected>No</option>
							   @endif
							   </select>
                            </div>
                        </div>

						<!-- Requiere Extremidad -->
                        <div class="form-group{{ $errors->has('requiere_extremidad') ? ' has-error' : '' }}">
                            <label for="requiere_extremidad" class="col-md-4 control-label">Requiere Extremidad</label>

                            <div class="col-md-6">
							   <select id="requiere_extremidad" class="form-control" name="requiere_extremidad" required>
						   		@if ($tipo->requiere_extremidad == 1)
								 	<option value="1">Si</option>
								 	<option value="0">No</option>
								@else
									<option value="1">Si</option>
								    <option value="0" selected>No</option>
							   @endif
							   </select>
                            </div>
                        </div>

						<!--Lista Activo-->
						<div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
                            <label for="active" class="col-md-4 control-label">Activo</label>

                            <div class="col-md-6">
								<select id="active" class="form-control" name="active" required>
								@if ($tipo->active == 1)
									<option value="1" selected>Si</option>
									<option value="0">No</option>
								@else
									<option value="1">Si</option>
									<option value="0" selected>No</option>
								@endif
								</select>
                            </div>
                        </div>
						<!--Boton Submit-->
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Editar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
			<!--FIN Panel Formulario Editar Tipo de Procedimiento-->
        </div>
    </div>
</div>

<script>
document.getElementById('prestamin').addEventListener('keyup', function() {
    var v = this.value;
    if (v.match(/^\d{2}$/) !== null) {
        this.value = v + '-';
    } else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
        this.value = v + '-';
    }
});
</script>
@endsection
