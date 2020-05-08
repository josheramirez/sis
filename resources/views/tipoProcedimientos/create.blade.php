@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <!--BreadCrumb-->
			<ol class="breadcrumb">
			  <li><a href="{{ URL::to('tipoProcedimientos') }}">Tipos de Procedimientos</a></li>
			  <li class="active">Crear</li>
			</ol>
			<!--FIN BreadCrumb-->
			<!--Panel Formulario Crear Tipo Prestación-->
			<div class="panel panel-default">
                <div class="panel-heading">Crear Tipo de Procedimiento</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('tipoProcedimientos') }}">
                        {{ csrf_field() }}
						<!--Campo Nombre-->
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nombre</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!--Lista Tipo Prestación-->
                        <div class="form-group{{ $errors->has('tipoprestacion') ? ' has-error' : '' }}">
                            <label for="tipoprestacion" class="col-md-4 control-label">Tipo Prestación</label>

                            <div class="col-md-6">
                                <select id="tipoprestacion" class="form-control" name="tipoprestacion" required>
                                  <option value="">Seleccione Tipo Prestación</option>
                                  @foreach($tipoprestacions as $tipoprestacion)
                                    <option value="{{ $tipoprestacion->id }}">{{ $tipoprestacion->name }}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
						<!--Lista Activo-->
						<div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
                            <label for="active" class="col-md-4 control-label">Activo</label>

                            <div class="col-md-6">
								<select id="active" class="form-control" name="active" required>
								  <option value="1">Si</option>
								  <option value="0">No</option>
								</select>
                            </div>
                        </div>
						<!--Boton Submit-->
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
			<!--FIN Panel Formulario Crear Tipo de Procedimiento-->
        </div>
    </div>
</div>
@endsection

