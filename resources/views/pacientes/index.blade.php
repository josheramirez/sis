@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Guardado o Actualización de Pacientes-->
	<?php $message=Session::get('message') ?>
	@if($message == 'store')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Paciente Creado Exitosamente
		</div>
	@elseif($message == 'update')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Paciente Modificado Exitosamente
		</div>
	@endif
	<!--FIN Mensajes de Guardado o Actualización de Comunas-->
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Lista de Pacientes</div>
                <div class="panel-body">
                    {{ csrf_field() }} 
					<div class="row">
						<!-- Boton Crear Nuevo Paciente -->
						<div class="col-md-3">
							<a class="btn btn-sm btn-primary" href="{{ URL::to('pacientes/create') }}">Crear Paciente</a>
						</div>	
						<!-- Formulario de Filtro por Nombre -->
						<div class="col-md-3">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('pacientes') }}">
								<div class="input-group">
									<input id="searchNombre" name="searchNombre" type="text" class="form-control input-sm" placeholder="Buscar por Nombre">
									<span class="input-group-btn ">
										<button class="btn btn-default btn-sm" type="submit">Ir</button>
									</span>
								</div>
							</form>
						</div>
						<!-- Formulario de Filtro por Rut -->
						<div class="col-md-3">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('pacientes') }}">
								<div class="input-group">
									<input id="searchRut" name="searchRut" type="text" class="form-control input-sm" placeholder="Buscar por RUN (sin puntos ni dígito verificador)">
									<span class="input-group-btn ">
										<button class="btn btn-default btn-sm" type="submit">Ir</button>
									</span>
								</div>
							</form>
						</div>
						<!-- Formulario de Filtro por Nro Documento -->
						<div class="col-md-3">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('pacientes') }}">
								<div class="input-group">
									<input id="searchDoc" name="searchDoc" type="text" class="form-control input-sm" placeholder="Buscar por Nro. Documento (Pasaporte)">
									<span class="input-group-btn ">
										<button class="btn btn-default btn-sm" type="submit">Ir</button>
									</span>
								</div>
							</form>
						</div>
					</div>
					
					</br>
					<!-- Lista de Pacientes -->		
					<div class="row">
						<div class="col-md-12">
							<table class="table table-striped">
								<thead>
								  <tr>
									<th>Rut / Nro Documento</th>
									<th>Nombre</th>
									<th>Editar</th>
								  </tr>
								</thead>
								<tbody>
								  @foreach($pacientes as $paciente)
								  <tr>
									<td>
									@if( $paciente->tipoDoc == 1 )	
										{{ $paciente->rut }}-{{ $paciente->dv }}
									@else
										{{ $paciente->numDoc }}
									@endif
									</td>									
									<td>{{ $paciente->nombre }} {{ $paciente->apPaterno }} {{ $paciente->apMaterno }}</td>
									<td>
										@if( Auth::user()->isRole('PacientesFull') )	
											<a href="{{ URL::to('editar/pacientes/su/'.$paciente->id.'/1') }}">Editar</a>
										@else
											<a href="{{ URL::to('editar/pacientes/di/'.$paciente->id.'/1') }}">Editar</a>
										@endif
									</td>
								  </tr>
								  @endforeach
								</tbody>
							</table>
							<!--paginacion-->
							{{ $pacientes->links() }}
						</div>
					</div>
					<!-- FIN Lista de Pacientes -->			
                </div>
            </div>
        </div>
    </div>
</div>
@endsection