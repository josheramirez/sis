@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Alerta Creación de Pacientes</div>

                <div class="panel-body">
                    <div class="alert alert-warning">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						No posee permisos para la creación de pacientes. Por favor comuniquese con su administrador.
					</div>	
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
