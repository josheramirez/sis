@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <!--BreadCrumb-->
			<ol class="breadcrumb">
			  <li><a href="{{ URL::to('establecimientos') }}">Establecimientos</a></li>
			  <li class="active">Editar</li>
			</ol>
			<!--FIN BreadCrumb-->
			<!--Panel Formulario Editar Establecimiento-->
			<div class="panel panel-default">
                <div class="panel-heading">Editar Establecimiento</div>
                <div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ URL::to('establecimientos') }}/{{$establecimiento->id}}">
                        <input type="hidden" name="_method" value="PUT">
                        {{ csrf_field() }}
						<!--Campo Codigo-->
                        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <label for="codigo" class="col-md-4 control-label">Código</label>

                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control" name="code" value="{{$establecimiento->code}}" required autofocus readonly>

                                @if ($errors->has('code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						<!--Campo Nombre-->
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nombre</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{$establecimiento->name}}" required autofocus readonly>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						<!--Lista Servicio-->
						<div class="form-group{{ $errors->has('servicio') ? ' has-error' : '' }}">
                            <label for="servicio" class="col-md-4 control-label">Servicio de Salud</label>

                            <div class="col-md-6">
								<select id="servicio" class="form-control" name="servicio" required>
								  <option value="">Seleccione Servicio</option>
								  @foreach($servicios as $servicio)
										@if($servicio->id == $establecimiento->servicio_id)
											<option value="{{ $servicio->id }}" selected>{{ $servicio->name }}</option>
										@else
											<option value="{{ $servicio->id }}">{{ $servicio->name }}</option>
										@endif
								  @endforeach
								</select>
                            </div>
                        </div>						
						<!--Lista Tipo-->
						<div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }}">
                            <label for="tipo" class="col-md-4 control-label">Tipo</label>

                            <div class="col-md-6">
								<select id="tipo" class="form-control" name="tipo" required>
									<option value="">Seleccione Tipo</option>
									@foreach($tipos as $tipo)
										@if($tipo->id == $establecimiento->tipo_id)
											<option value="{{ $tipo->id }}" selected>{{ $tipo->name }}</option>
										@else
											<option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
										@endif
									@endforeach
								</select>
                            </div>
                        </div>
						<!--Lista Nivel-->
						<div class="form-group{{ $errors->has('nivel') ? ' has-error' : '' }}">
                            <label for="nivel" class="col-md-4 control-label">Nivel</label>

                            <div class="col-md-6">
								<select id="nivel" class="form-control" name="nivel" required>
								  <option value="">Seleccione Nivel</option>
								  @foreach($nivels as $nivel)
										@if($nivel->id == $establecimiento->nivel_id)
											<option value="{{ $nivel->id }}" selected>{{ $nivel->name }}</option>
										@else
											<option value="{{ $nivel->id }}">{{ $nivel->name }}</option>
										@endif
									
								  @endforeach
								</select>
                            </div>
                        </div>						
						<!--Lista Comuna-->
						<div class="form-group{{ $errors->has('comuna') ? ' has-error' : '' }}">
                            <label for="comuna" class="col-md-4 control-label">Comuna</label>

                            <div class="col-md-6">
								<select id="comuna" class="form-control" name="comuna" required>
									<option value="">Seleccione Comuna</option>
									@foreach($comunas as $comuna)
										@if($comuna->id == $establecimiento->comuna_id)
											<option value="{{ $comuna->id }}" selected>{{ $comuna->name }}</option>
										@else
											<option value="{{ $comuna->id }}">{{ $comuna->name }}</option>
										@endif
									@endforeach
								</select>
                            </div>
                        </div>
						<!--Campo Direccion-->
                        <div class="form-group{{ $errors->has('direccion') ? ' has-error' : '' }}">
                            <label for="direccion" class="col-md-4 control-label">Dirección</label>

                            <div class="col-md-6">
                                <input id="direccion" type="text" class="form-control" name="direccion" onFocus="geolocate()" value="{{$establecimiento->direccion}}" required autofocus>

                                @if ($errors->has('direccion'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('direccion') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						<!--Elementos ocultos-->
						<input name="x" id="x" type="hidden" value="{{$establecimiento->X}}">
						<input name="y" id="y" type="hidden" value="{{$establecimiento->Y}}">
						<!--Lista Activo-->
						<div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
                            <label for="active" class="col-md-4 control-label">Activo</label>

                            <div class="col-md-6">
								<select id="active" class="form-control" name="active" required>
								@if ($establecimiento->active == 1)
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
			<!--FIN Panel Formulario Crear Establecimientos-->
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

<!--Script Autocompletado-->
<script>
	var placeSearch, autocomplete;
	
	function initAutocomplete() {
		
		autocomplete = new google.maps.places.Autocomplete(
			(document.getElementById('direccion')),
			{types: ['geocode']});
		
		autocomplete.addListener('place_changed', fillInAddress);
	}
	
	function fillInAddress() {
		document.getElementById("y").value = autocomplete.getPlace().geometry.location.lat();
		document.getElementById("x").value = autocomplete.getPlace().geometry.location.lng();
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

