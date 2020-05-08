@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
			<!--BreadCrumb-->
			<ol class="breadcrumb">
			  <li><a href="{{ URL::to('tipoProcedimientos') }}">Tipos de Procedimientos</a></li>
			  <li class="active">Editar</li>
			</ol>
			<!--FIN BreadCrumb-->
			<!--Panel Formulario Editar Tipo de Procedimiento-->
            <div class="panel panel-default">
                <div class="panel-heading">Editar Tipo de Procedimiento</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('tipoProcedimientos') }}/{{$tipo->id}}">
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
                        <div class="form-group{{ $errors->has('tipoprestacion') ? ' has-error' : '' }}">
                            <label for="tipoprestacion" class="col-md-4 control-label">Tipo Prestación</label>

                            <div class="col-md-6">
                                <select id="tipoprestacion" class="form-control" name="tipoprestacion" required>
                                    <option value="">Seleccione Tipo Prestación</option>
                                    @foreach($tipoprestacions as $tipoprestacion)
                                        @if($tipoprestacion->id == $tipo->tipo_prestacion_id)
                                            <option value="{{ $tipoprestacion->id }}" selected>{{ $tipoprestacion->name }}</option>
                                        @else
                                            <option value="{{ $tipoprestacion->id }}">{{ $tipoprestacion->name }}</option>
                                        @endif
                                    @endforeach
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
@endsection

