@extends('layouts.app3')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default panel-login">
                <div class="panel-heading"><strong>Ingresar</strong></div>
                <div class="panel-body">
					<div class="col-md-8">	
						<form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
							{{ csrf_field() }}
							<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
								<label for="email" class="col-md-4 control-label">Nombre de Usuario</label>

								<div class="col-md-8">
									<input id="email" type="text" class="form-control" name="email" required autofocus>

									@if ($errors->has('email'))
										<span class="help-block">
											<strong>{{ $errors->first('email') }}</strong>
										</span>
									@endif
								</div>
							</div>

							<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
								<label for="password" class="col-md-4 control-label">Contraseña</label>

								<div class="col-md-8">
									<input id="password" type="password" class="form-control" name="password" required>

									@if ($errors->has('password'))
										<span class="help-block">
											<strong>{{ $errors->first('password') }}</strong>
										</span>
									@endif
								</div>
							</div>
							
							<div class="form-group{{ $errors->has('establecimiento') ? ' has-error' : '' }}">
								<label for="establecimiento" class="col-md-4 control-label">Establecimiento</label>

								<div class="col-md-8">
									<select id="establecimiento" class="form-control" name="establecimiento" required>
										<option value="">Seleccione Establecimiento</option>
									</select>

									@if ($errors->has('establecimiento'))
										<span class="help-block">
											<strong>{{ $errors->first('establecimiento') }}</strong>
										</span>
									@endif
								</div>
							</div>

							<div class="form-group{{ $errors->has('especialidad') ? ' has-error' : '' }}">
								<label for="especialidad" class="col-md-4 control-label">Especialidad</label>

								<div class="col-md-8">
									<select id="especialidad" class="form-control" name="especialidad" required>
										<option value="">Seleccione Especialidad</option>
									</select>

									@if ($errors->has('especialidad'))
										<span class="help-block">
											<strong>{{ $errors->first('especialidad') }}</strong>
										</span>
									@endif
								</div>
							</div>						
							
							<br>

							<div class="form-group">
								<div class="col-md-8 col-md-offset-4">
									<button type="submit" class="btn btn-primary">
										Acceder
									</button>
								</div>
							</div>
						</form>
					</div>
					<div class="col-md-4 logodti">	
						<img alt="" src="{{ asset('image/logoDTI.png') }}">
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
@endsection
