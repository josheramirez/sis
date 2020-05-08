@extends('layouts.app4')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Verificacion de Rut Pacientes-->
	<?php $message=Session::get('message') ?>
	@if($message == 'rut')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			R.U.N. ya se encuentra registrado
		</div>
	@elseif($message == 'tramo')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Previsión Fonasa, requiere de tramo.
		</div>	
	@endif
	<!--FIN Mensajes de Verificacion de Rut Proveedores-->
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <!--BreadCrumb-->
			<ol class="breadcrumb">
			  <li><a href="{{ URL::to('pacientes') }}">Pacientes</a></li>
			  <li class="active">Editar</li>
			</ol>
			<!--FIN BreadCrumb-->
			<!--Panel Formulario Editar Paciente-->
			<div class="panel panel-default">
                <div class="panel-heading">Editar Paciente</div>
                <div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ URL::to('pacientes') }}/{{$paciente->id}}">
                        <input type="hidden" name="_method" value="PUT">
                        {{ csrf_field() }}
						<div class="col-md-6">
							<!--Tipo de Documento-->
							<div class="form-group">
								<label for="tipoDocName" class="col-md-4 control-label">Tipo de Documento</label>
								<div class="col-md-8">
									@if ( $paciente->tipoDoc == 1 ) 
										<input id="tipoDocName" type="text" class="form-control" name="tipoDocName" value="RUN" readonly>
									@else
										<input id="tipoDocName" type="text" class="form-control" name="tipoDocName" value="Pasaporte / Otro" readonly>
									@endif	
									<input name="tipoDoc" id="rut" type="hidden" value="{{ $paciente->tipoDoc }}">
								</div>
							</div>
							
							
							<!--Rut / Pasaporte-->
							<div class="form-group">
								@if ($paciente->tipoDoc == 1)
									<label for="run" class="col-md-4 control-label">R.U.N.</label>
									<div class="col-md-8">
										<input id="run" type="text" class="form-control" name="run" value="{{$paciente->rut}}-{{$paciente->dv}}" readonly>
									</div>
									<!--Elementos ocultos-->
									<input name="rut" id="rut" type="hidden" value="{{ $paciente->rut }}">
									<input name="dv" id="dv" type="hidden" value="{{ $paciente->dv }}">
								@else
									<label for="numDoc" class="col-md-4 control-label">Pasaporte / Otro</label>
									<div class="col-md-8">
										<input id="numDoc" type="text" class="form-control" name="numDoc" value="{{ $paciente->numDoc }}" readonly>
									</div>
								@endif		
							</div>
							
							<!--Nombre-->
							<div class="form-group">
								<label for="nombre" class="col-md-4 control-label">Nombre</label>
								<div class="col-md-8">
									<input id="nombre" type="text" class="form-control" name="nombre" value="{{ $paciente->nombre }}" readonly>
								</div>
							</div>
							
							<!--Apellido Paterno-->
							<div class="form-group">
								<label for="apPaterno" class="col-md-4 control-label">Apellido Paterno</label>
								<div class="col-md-8">
									<input id="apPaterno" type="text" class="form-control" name="apPaterno" value="{{ $paciente->apPaterno }}" readonly>
								</div>
							</div>
							
							<!--Apellido Materno-->
							<div class="form-group">
								<label for="apMaterno" class="col-md-4 control-label">Apellido Materno</label>
								<div class="col-md-8">
									<input id="apMaterno" type="text" class="form-control" name="apMaterno" value="{{ $paciente->apMaterno }}" readonly>
								</div>
							</div>

							<!--Fecha Nacimiento-->
							<div class="form-group{{ $errors->has('fechaNacimiento') ? ' has-error' : '' }}">
								<label for="fechaNacimiento" class="col-md-4 control-label">Fecha de Nacimiento</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="fechaNacimiento" id="fechaNacimiento" value="{{ $paciente->fechaNacimiento }}" required placeholder="dd-mm-yyyy"  maxlength="10" autofocus>
									@if ($errors->has('fechaNacimiento'))
									<span class="help-block">
										<strong>{{ $errors->first('fechaNacimiento') }}</strong>
									</span>
									@endif
								</div>
							</div>
							
							<!--Lista Genero-->
							<div class="form-group{{ $errors->has('genero') ? ' has-error' : '' }}">
								<label for="genero" class="col-md-4 control-label">Género</label>

								<div class="col-md-8">
									<select id="genero" class="form-control" name="genero" required>
										<option value="">Seleccione Genero</option>
										@foreach($generos as $genero)
											@if($genero->id == $paciente->genero_id)
												<option value="{{ $genero->id }}" selected>{{ $genero->name }}</option>
											@else
												<option value="{{ $genero->id }}">{{ $genero->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>	

							<!--Lista Prevision-->
							<div class="form-group{{ $errors->has('prevision') ? ' has-error' : '' }}">
								<label for="prevision" class="col-md-4 control-label">Previsión</label>

								<div class="col-md-8">
									<select id="prevision" class="form-control" name="prevision" required>
										<option value="">Seleccione Previsión</option>
										@foreach($previsions as $prevision)
											@if($prevision->id == $paciente->prevision_id)
												<option value="{{ $prevision->id }}" selected>{{ $prevision->name }}</option>
											@else
												<option value="{{ $prevision->id }}">{{ $prevision->name }}</option>
											@endif	
										@endforeach
									</select>
								</div>
							</div>
							
							<!--Lista Tramo-->
							<div class="form-group{{ $errors->has('tramo') ? ' has-error' : '' }}">
								<label for="tramo" class="col-md-4 control-label">Tramo</label>

								<div class="col-md-8">
									@if($paciente->prevision_id == 1)
									<select id="tramo" class="form-control" name="tramo" required>
									@else
									<select id="tramo" class="form-control" name="tramo" disabled>	
									@endif	
										<option value="">Seleccione Tramo</option>
										@foreach($tramos as $tramo)
											@if($tramo->id == $paciente->tramo_id)
												<option value="{{ $tramo->id }}" selected>{{ $tramo->name }}</option>
											@else
												<option value="{{ $tramo->id }}">{{ $tramo->name }}</option>
											@endif	
										@endforeach
									</select>
								</div>
							</div>
						
						</div>
						
						<div class="col-md-6">
							<!--Lista Prais-->
							<div class="form-group{{ $errors->has('prais') ? ' has-error' : '' }}">
								<label for="prais" class="col-md-4 control-label">PRAIS</label>

								<div class="col-md-8">
									<select id="prais" class="form-control" name="prais" required>
										@if($paciente->prais == 2)
											<option value="1">Si</option>
											<option value="2" selected>No</option>
										@else
											<option value="1" selected>Si</option>
											<option value="2">No</option>
										@endif			
									</select>
								</div>
							</div>
							
							<!--Lista Funcionario-->
							<div class="form-group{{ $errors->has('funcionario') ? ' has-error' : '' }}">
								<label for="funcionario" class="col-md-4 control-label">Funcionario</label>

								<div class="col-md-8">
									<select id="funcionario" class="form-control" name="funcionario" required>
										@if($paciente->funcionario == 0)
											<option value="1">Si</option>
											<option value="0" selected>No</option>
										@else
											<option value="1" selected>Si</option>
											<option value="0">No</option>	
										@endif	
									</select>
								</div>
							</div>
							
							<!--Lista Comuna-->
							<div class="form-group{{ $errors->has('comuna') ? ' has-error' : '' }}">
								<label for="comuna" class="col-md-4 control-label">Comuna</label>

								<div class="col-md-8">
									<select id="comuna" class="form-control" name="comuna" required>
										<option value="">Seleccione Comuna</option>
										@foreach($comunas as $comuna)
											@if($comuna->id == $paciente->comuna_id)
												<option value="{{ $comuna->id }}" selected>{{ $comuna->name }}</option>
											@else
												<option value="{{ $comuna->id }}">{{ $comuna->name }}</option>
											@endif
											
										@endforeach
									</select>
								</div>
							</div>

							<!--Lista Tipo Vía-->
							<div class="form-group{{ $errors->has('via') ? ' has-error' : '' }}">
								<label for="via" class="col-md-4 control-label">Tipo Calle</label>

								<div class="col-md-8">
									<select id="via" class="form-control" name="via" required>
										<option value="">Seleccione tipo calle</option>
										@foreach($vias as $via)
											@if($via->id == $paciente->via_id)
												<option value="{{ $via->id }}" selected>{{ $via->name }}</option>
											@else
												<option value="{{ $via->id }}">{{ $via->name }}</option>
											@endif
											
										@endforeach
									</select>
								</div>
							</div>


							<!--Campo Direccion-->
							<div class="form-group{{ $errors->has('direccion') ? ' has-error' : '' }}">
								<label for="direccion" class="col-md-4 control-label">Dirección</label>

								<div class="col-md-6">
									<input id="direccion" type="text" class="form-control" name="direccion" value="{{ $paciente->direccion }}" onFocus="geolocate()" required autofocus>

									@if ($errors->has('direccion'))
										<span class="help-block">
											<strong>{{ $errors->first('direccion') }}</strong>
										</span>
									@endif
								</div>
								<div class="col-md-2">
									<input id="numero" type="text" class="form-control" name="numero" value="{{ $paciente->numero }}" onFocus="geolocate()" placeholder="N°" required autofocus>

									@if ($errors->has('numero'))
										<span class="help-block">
											<strong>{{ $errors->first('numero') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<!--Elementos ocultos-->
							<input name="x" id="x" type="hidden" value="{{ $paciente->X }}">
							<input name="y" id="y" type="hidden" value="{{ $paciente->Y }}">

							<!--Campo Telefono-->
							<div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
								<label for="telefono" class="col-md-4 control-label">Teléfono</label>

								<div class="col-md-8">
									<input id="telefono" type="text" class="form-control" name="telefono" value="{{ $paciente->telefono }}" required autofocus>

									@if ($errors->has('telefono'))
										<span class="help-block">
											<strong>{{ $errors->first('telefono') }}</strong>
										</span>
									@endif
								</div>
							</div>
								
							<!--Campo Telefono2-->
							<div class="form-group{{ $errors->has('telefono2') ? ' has-error' : '' }}">
								<label for="telefono2" class="col-md-4 control-label">Teléfono Alternativo</label>

								<div class="col-md-8">
									<input id="telefono2" type="text" class="form-control" name="telefono2" value="{{ $paciente->telefono2 }}" autofocus>

									@if ($errors->has('telefono2'))
										<span class="help-block">
											<strong>{{ $errors->first('telefono2') }}</strong>
										</span>
									@endif
								</div>
							</div>

							<!--Campo Email-->
							<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
								<label for="email" class="col-md-4 control-label">Correo Electrónico</label>

								<div class="col-md-8">
									<input id="email" type="email" class="form-control" name="email" value="{{ $paciente->email }}" autofocus>

									@if ($errors->has('email'))
										<span class="help-block">
											<strong>{{ $errors->first('email') }}</strong>
										</span>
									@endif
								</div>
							</div>						
							
							<!--Lista Activo-->
							<div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
								<label for="active" class="col-md-4 control-label">Activo</label>

								<div class="col-md-8">
									<select id="active" class="form-control" name="active" required>
										@if($paciente->active == 1)
											<option value="1" selected>Si</option>
											<option value="0">No</option>
										@else
											<option value="1">Si</option>
											<option value="0" selected>No</option>
										@endif		
									</select>
								</div>
							</div>
							<!--Elementos Ocultos-->
							<input name="flujo" id="flujo" type="hidden" value="{{ $flujo }}">
							<input name="url" id="url" type="hidden" value="di">
							
							<!--Boton Submit-->
							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<button type="submit" class="btn btn-primary">
										Guardar
									</button>
								</div>
							</div>
						</div>
                    </form>
                </div>
            </div>
			<!--FIN Panel Formulario Crear Paciente-->
        </div>
    </div>
</div>

<!--evita submit al presionar enter-->
<script>
	
	function stopRKey(evt) {
		var evt = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
	}

	document.onkeypress = stopRKey;
</script>

<!--Script Tramo Fonasa-->
<script>
document.getElementById('prevision').addEventListener('change', function() {
	if (this.value == 1) {
		document.getElementById('tramo').disabled = false;
		document.getElementById("tramo").required = true;
	}
	else {
		document.getElementById('tramo').disabled = true;
		document.getElementById("tramo").required = false;
		document.getElementById("tramo").value = "";
	}
});	
</script>

<!--Script Fecha de Nacimiento-->
<script>
var fecha1 = new Date('{{$paciente->fechaNacimiento}}'+'T12:00:00Z');

$('#fechaNacimiento').datepicker({
        dateFormat: "dd-mm-yy",
        firstDay: 1,
		maxDate: 0,
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
        monthNames: 
            ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
            "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthNamesShort: 
            ["Ene", "Feb", "Mar", "Abr", "May", "Jun",
            "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
		//focus
		onSelect: function ()
		{
			this.focus();
		}
}).datepicker("setDate", fecha1);

//funcion que pone mascara de fecha
document.getElementById('fechaNacimiento').addEventListener('keyup', function() {
	var v = this.value;
	if (v.match(/^\d{2}$/) !== null) {
		this.value = v + '-';
	} else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
		this.value = v + '-';
	}
});		
</script>

<!--Script Autocompletado-->
<script>
	var placeSearch, autocomplete;
	
	var componentForm = {
        street_number: 'short_name',
        route: 'long_name'
    };
	
	function initAutocomplete() { 
		
		autocomplete = new google.maps.places.Autocomplete(
			(document.getElementById('direccion')),
			{types: ['geocode']});
		
		autocomplete.addListener('place_changed', fillInAddress);
	}
	
	function fillInAddress() {
		//completa número y calle
		var place  = autocomplete.getPlace();
		var number = null;
		
		for (var i = 0; i < place.address_components.length; i++) {
			var addressType = place.address_components[i].types[0];
			if (componentForm[addressType]) {
				var val = place.address_components[i][componentForm[addressType]];
				if ( addressType == 'street_number' ){
					number = val;
				}
				if ( addressType == 'route' ){
					document.getElementById("direccion").value = val;
				}
			}
		}
		
		if ( number != null ) {
			//completa X - Y
			document.getElementById("numero").value = number;
			document.getElementById("y").value = place.geometry.location.lat();
			document.getElementById("x").value = place.geometry.location.lng();
		}
	}
	
	function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAlnRCAhCvNWeH-8P-fGEnoQJ2Hi0nZv-Y&libraries=places&callback=initAutocomplete"
         async defer></script>
@endsection

