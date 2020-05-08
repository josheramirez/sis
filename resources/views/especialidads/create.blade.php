@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <!--BreadCrumb-->
			<ol class="breadcrumb">
			  <li><a href="{{ URL::to('especialidads') }}">Especialidades</a></li>
			  <li class="active">Crear</li>
			</ol>
			<!--FIN BreadCrumb-->
			<!--Panel Formulario Crear Especialidad-->
			<div class="panel panel-default">
                <div class="panel-heading">Crear Especialidad</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('especialidads') }}">
                        {{ csrf_field() }}
						<!--Campo REM-->
                        <div class="form-group{{ $errors->has('rem') ? ' has-error' : '' }}">
                            <label for="rem" class="col-md-4 control-label">REM</label>

                            <div class="col-md-6">
                                <input id="rem" type="text" class="form-control" name="rem" value="{{ old('rem') }}" required autofocus>

                                @if ($errors->has('rem'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('rem') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!--Campo DEIS-->
                        <div class="form-group{{ $errors->has('deis') ? ' has-error' : '' }}">
                            <label for="deis" class="col-md-4 control-label">DEIS</label>

                            <div class="col-md-6">
                                <input id="deis" type="text" class="form-control" name="deis" value="{{ old('deis') }}" required autofocus>

                                @if ($errors->has('deis'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('deis') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!--Campo SIGTE-->
                        <div class="form-group{{ $errors->has('sigte') ? ' has-error' : '' }}">
                            <label for="sigte" class="col-md-4 control-label">SIGTE</label>

                            <div class="col-md-6">
                                <input id="sigte" type="text" class="form-control" name="sigte" value="{{ old('sigte') }}" required autofocus>

                                @if ($errors->has('sigte'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('sigte') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
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
			<!--FIN Panel Formulario Crear Especialidads-->
        </div>
    </div>
</div>
@endsection

