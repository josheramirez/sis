@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <!--BreadCrumb-->
			<ol class="breadcrumb">
			  <li><a href="{{ URL::to('etarios') }}">Grupos Etarios</a></li>
			  <li class="active">Crear</li>
			</ol>
			<!--FIN BreadCrumb-->
			<!--Panel Formulario Crear Etario-->
			<div class="panel panel-default">
                <div class="panel-heading">Crear Grupo Etario</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('etarios') }}">
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
						<!--Campo Desde-->
                        <div class="form-group{{ $errors->has('desde') ? ' has-error' : '' }}">
                            <label for="desde" class="col-md-4 control-label">Edad Desde</label>

                            <div class="col-md-6">
                                <input id="desde" type="number" class="form-control" name="desde" value="{{ old('desde') }}" required autofocus>

                                @if ($errors->has('desde'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('desde') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						<!--Campo Hasta-->
                        <div class="form-group{{ $errors->has('hasta') ? ' has-error' : '' }}">
                            <label for="hasta" class="col-md-4 control-label">Edad Hasta</label>

                            <div class="col-md-6">
                                <input id="hasta" type="number" class="form-control" name="hasta" value="{{ old('hasta') }}" required autofocus>

                                @if ($errors->has('hasta'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('hasta') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						<!--Lista Activo-->
						<div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
                            <label for="active" class="col-md-4 control-label">Activo</label>

                            <div class="col-md-6">
								<select id="active" class="form-control" name="active" required>
									<option value="1" selected>Si</option>
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
			<!--FIN Panel Formulario Crear Etarios-->
        </div>
    </div>
</div>
@endsection

