@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
			<!--BreadCrumb-->
			<ol class="breadcrumb">
			  <li><a href="{{ URL::to('users') }}">Usuarios</a></li>
			  <li class="active">Asignar Especialidad</li>
			</ol>
			<!--FIN BreadCrumb-->
            <div class="panel panel-default">
				<div class="panel-heading">Asignar Especialidades de Usuario</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ URL::to('users/saveEspec') }}">
						{{ csrf_field() }} 
						<!--Lista de Selección Multiple-->
						<div class="form-group">
                            <label for="especUsuarios" class="col-md-4 control-label">Especialidades</label>

                            <div class="col-md-6">
								<select id="especUsuarios" name="especUsuarios[]" class="form-control" multiple size="10" required>
									@foreach($especialidads as $especialidad)
										@if($user->isEspec($especialidad->name))
											<option value="{{ $especialidad->id }}" selected>{{ $especialidad->name }}</option>
										@else
											<option value="{{ $especialidad->id }}">{{ $especialidad->name }}</option>
										@endif
									@endforeach
								</select>    
                            </div>
                        </div>
						<!--ID Usuario-->
						<input type="hidden" name="userID" id="userID" value="{{$id}}">
						
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