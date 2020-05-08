@extends('layouts.app4')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-10 col-lg-offset-1">
			<!--Panel Formulario Crear Contrarreferencia-->
			<div class="panel panel-default">
                <div class="panel-heading">Consulta Red</div>
                <div class="panel-body">
					<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('/listaesperas/resultado') }}">

						{{ csrf_field() }}
						<div class="form-group">
							<label for="optfechaing" class="col-md-4 control-label">Fecha Entrada</label>
							<div class="col-md-2">
                                <select id="optfechaing" class="form-control" name="optfechaing">
									<option value="0">Seleccione opción</option>
									<option value="1">Exactamente igual</option>
									<option value="2">Mayor que</option>
									<option value="3">Menor que</option>
									<option value="4">Entre</option>
								</select>
                            </div>
							<div id='fec_desde' class="col-md-2">
								<input id="desde" type="text" class="form-control" name="desde" maxlength="10" placeholder="dd-mm-aaaa" disabled>
                            </div>
							<div id='fec_hasta' class="col-md-2" hidden>
								<input id="hasta" type="text" class="form-control" name="hasta" maxlength="10" placeholder="dd-mm-aaaa">
                            </div>
						</div>

						<div class="form-group">
							<label for="fecha" class="col-md-4 control-label">Fecha Digitación</label>
							<div class="col-md-2">
                                <select id="optfechadig" class="form-control" name="optfechadig">
									<option value="0">Seleccione opción</option>
									<option value="1">Exactamente igual</option>
									<option value="2">Mayor que</option>
									<option value="3">Menor que</option>
									<option value="4">Entre</option>
								</select>
                            </div>
							<div id='dig_desde' class="col-md-2">
								<input id="digdesde" type="text" class="form-control" name="digdesde" maxlength="10" placeholder="dd-mm-aaaa" disabled>
                            </div>
							<div id='dig_hasta' class="col-md-2" hidden>
								<input id="dighasta" type="text" class="form-control" name="dighasta" maxlength="10" placeholder="dd-mm-aaaa">
                            </div>
						</div>
						<!--Fecha Salida Lista Espera -->
						<div class="form-group">
							<label for="fechasalida" class="col-md-4 control-label">Fecha Salida</label>
							<div class="col-md-2">
                                <select id="optfechasal" class="form-control" name="optfechasal">
									<option value="0">Seleccione opción</option>
									<option value="1">Exactamente igual</option>
									<option value="2">Mayor que</option>
									<option value="3">Menor que</option>
									<option value="4">Entre</option>
								</select>
                            </div>
							<div id='sal_desde' class="col-md-2">
								<input id="saldesde" type="text" class="form-control" name="saldesde" maxlength="10" placeholder="dd-mm-aaaa" disabled>
                            </div>
							<div id='sal_hasta' class="col-md-2" hidden>
								<input id="salhasta" type="text" class="form-control" name="salhasta" maxlength="10" placeholder="dd-mm-aaaa">
                            </div>
						</div>

						<!--Fecha Digitación Egreso -->
						<div class="form-group">
							<label for="optfechadigeg" class="col-md-4 control-label">Fecha Digitación Egreso</label>
							<div class="col-md-2">
                                <select id="optfechadigeg" class="form-control" name="optfechadigeg">
									<option value="0">Seleccione opción</option>
									<option value="1">Exactamente igual</option>
									<option value="2">Mayor que</option>
									<option value="3">Menor que</option>
									<option value="4">Entre</option>
								</select>
                            </div>
							<div id='dig_eg_desde' class="col-md-2">
								<input id="digegdesde" type="text" class="form-control" name="digegdesde" maxlength="10" placeholder="dd-mm-aaaa" disabled>
                            </div>
							<div id='dig_eg_hasta' class="col-md-2" hidden>
								<input id="digeghasta" type="text" class="form-control" name="digeghasta" maxlength="10" placeholder="dd-mm-aaaa">
                            </div>
						</div>

						<!-- Filtro Rut de paciente-->
						<div class="form-group">
                            <label for="paciente" class="col-md-4 control-label">RUN /N° Documento Paciente</label>
                            <div class="col-md-6">
								<input id="paciente" type="text" class="form-control" name="paciente" placeholder="Sin puntos ni dígito verificador" autofocus>
                            </div>
                        </div>
						<!--Elementos ocultos-->
						<input name="idPaciente" id="idPaciente" type="hidden">

						<!--Establecimiento de Origen -->
						<div class="form-group">
                            <label for="establecimiento" class="col-md-4 control-label">Establecimiento Origen</label>
                            <div class="col-md-6">
                                <select id="establecimiento" class="form-control" name="establecimiento">
									<option value="">Seleccione</option>
									@foreach($establecimientos as $establecimiento)
										<option value="{{ $establecimiento->id }}">{{ $establecimiento->name }}</option>
									@endforeach
								</select>
                            </div>
                        </div>

						<!--Establecimiento de Destino -->
						<div class="form-group">
                            <label for="estadestino" class="col-md-4 control-label">Establecimiento Destino</label>
                            <div class="col-md-6">
                                <select id="estadestino" class="form-control" name="estadestino">
									<option value="">Seleccione</option>
									@foreach($establecimientos as $establecimiento)
										<option value="{{ $establecimiento->id }}">{{ $establecimiento->name }}</option>
									@endforeach
								</select>
                            </div>
                        </div>

						<!--Establecimiento de Resuelve -->
                        <div class="form-group">
                            <label for="estaresuelve" class="col-md-4 control-label">Establecimiento Resuelve</label>
                            <div class="col-md-6">
                                <select id="estaresuelve" class="form-control" name="estaresuelve">
									<option value="">Seleccione</option>
									@foreach($establecimientos as $establecimiento)
										<option value="{{ $establecimiento->id }}">{{ $establecimiento->name }}</option>
									@endforeach
								</select>
                            </div>
                        </div>


						<div class="form-group">
                            <label for="comuna" class="col-md-4 control-label">Comuna</label>
                            <div class="col-md-6">
                                <select id="comuna" class="form-control" name="comuna">
									<option value="">Seleccione</option>
									@foreach($comunas as $comuna)
										<option value="{{ $comuna->id }}">{{ $comuna->name }}</option>
									@endforeach
								</select>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="prestacion" class="col-md-4 control-label">Tipo Prestación</label>
                            <div class="col-md-6">
                                <select id="prestacion" class="form-control" name="prestacion">
									<option value="">Seleccione</option>
									@foreach($prestacions as $prestacion)
										<option value="{{ $prestacion->id }}">{{ $prestacion->name }}</option>
									@endforeach
								</select>
                            </div>
                        </div>

						<div class="form-group">
                            <label for="especialidad" class="col-md-4 control-label">Especialidad</label>
                            <div class="col-md-6">
                                <select id="especialidad" class="form-control" name="especialidad">
                                	<option value="">Seleccione</option>
									@foreach($especialidads as $especialidad)
										<option value="{{ $especialidad->id }}">{{ $especialidad->name }}</option>
									@endforeach
								</select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="estado" class="col-md-4 control-label">Estado Lista Espera</label>
                            <div class="col-md-6">
                                <select id="estado" class="form-control" name="estado">
									<option value="">Seleccione</option>
									<option value="1">Abierta</option>
									<option value="0">Cerrada</option>
								</select>
                            </div>
                        </div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Consultar
                                </button>
                            </div>
						</div>
					</form>
				</div>
            </div>
			<!--FIN Panel Formulario Documento-->
        </div>
    </div>
</div>

<!-- AUTOCOMPLETA RUT -->
	<script>
		$("#paciente").autocomplete({
			source: function(request, response) {
				$.ajax({
					url: "{{ route('getPacienteLep') }}",
					dataType: "json",
					data: {
						term : request.term
					},

					success: function(data) {
						response(data);
					}
				});
			},
			select: function (event, ui) {
		        //datos pacientes
				$("#idPaciente").val(ui.item.id);
		    },
			minLength: 2,
		});
	</script>
<!-- FIN AUTOCOMPLETA RUT -->

<!-- ******* FECHA INGRESO LEP ******* -->
	<!-- OCULTA o MUESTRA FECHA INGRESO -->
	<script>
		document.getElementById('optfechaing').addEventListener('change', function()
	 	// 1 -Igual  / 2 - Mayor que /3 menor que / 3 - entre
		{
		    if (this.value == 0 || this.value == 1 || this.value == 2 || this.value == 3) {
		        document.getElementById('fec_hasta').hidden = true;
		        if (this.value == 0 ) {
		        	document.getElementById('desde').value = "";
		        	document.getElementById('hasta').value = "";
		        	document.getElementById('desde').disabled = true;
		        } else {
		        	document.getElementById('hasta').value = "";
		        	document.getElementById('desde').disabled = false;
		        }
		    }
		    else {
		    	document.getElementById('desde').disabled = false;
				document.getElementById('fec_desde').hidden = false;
				document.getElementById('fec_hasta').hidden = false;
		    }
		}
		);
	</script>
	<!-- FECHA DESDE -->
	<script>
	$('#desde').datepicker({
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
	});
	//funcion que pone mascara de fecha
	document.getElementById('desde').addEventListener('keyup', function() {
		var v = this.value;
		if (v.match(/^\d{2}$/) !== null) {
			this.value = v + '-';
		} else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
			this.value = v + '-';
		}
	});
	</script>
	<!-- FECHA HASTA -->
	<script>
		$('#hasta').datepicker({
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
		});

		//funcion que pone mascara de fecha
		document.getElementById('hasta').addEventListener('keyup', function() {
			var v = this.value;
			if (v.match(/^\d{2}$/) !== null) {
				this.value = v + '-';
			} else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
				this.value = v + '-';
			}
		});
	</script>
<!-- ******* FIN FECHA INGRESO LEP ******* -->

<!-- ******* FECHA DIGITACIÓN LEP ******* -->
	<!-- Oculta o muestra fecha de digitación entre -->
	<script>
		document.getElementById('optfechadig').addEventListener('change', function()
	 	// 1 -Igual  / 2 - Mayor que /3 menor que / 3 - entre
		{
		    if (this.value == 0 || this.value == 1 || this.value == 2 || this.value == 3) {
		        document.getElementById('dig_hasta').hidden = true;
		        if (this.value == 0 ) {
		        	document.getElementById('digdesde').value = "";
		        	document.getElementById('digdesde').disabled = true;
		        } else {
		        	document.getElementById('digdesde').disabled = false;
		        }
		    }
		    else {
		    	document.getElementById('digdesde').disabled = false;
				document.getElementById('dig_desde').hidden = false;
				document.getElementById('dig_hasta').hidden = false;
		    }
		}
		);
	</script>
	<!-- fecha desde -->
	<script>
		$('#digdesde').datepicker({
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
		});

		//funcion que pone mascara de fecha
		document.getElementById('digdesde').addEventListener('keyup', function() {
			var v = this.value;
			if (v.match(/^\d{2}$/) !== null) {
				this.value = v + '-';
			} else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
				this.value = v + '-';
			}
		});
	</script>
	<!-- fecha hasta -->
	<script>
		$('#dighasta').datepicker({
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
		});

		//funcion que pone mascara de fecha
		document.getElementById('dighasta').addEventListener('keyup', function() {
			var v = this.value;
			if (v.match(/^\d{2}$/) !== null) {
				this.value = v + '-';
			} else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
				this.value = v + '-';
			}
		});
	</script>
<!-- ******* FIN FECHA DIGITACIÓN LEP ******* -->

<!-- ******* FECHA DIGITACIÓN EGRESO ******* -->
	<!-- Oculta o muestra fecha de digitación egreso -->
	<script>
		document.getElementById('optfechadigeg').addEventListener('change', function()
	 	// 1 -Igual  / 2 - Mayor que /3 menor que / 3 - entre
		{
		    if (this.value == 0 || this.value == 1 || this.value == 2 || this.value == 3) {
		        document.getElementById('dig_eg_hasta').hidden = true;
		        if (this.value == 0 ) {
		        	document.getElementById('digegdesde').value = "";
		        	document.getElementById('digeghasta').value = "";
		        	document.getElementById('digegdesde').disabled = true;
		        } else {
		        	document.getElementById('digegdesde').disabled = false;
		        	document.getElementById('digeghasta').value = "";
		        }
		    }
		    else {
		    	document.getElementById('digegdesde').disabled = false;
				document.getElementById('dig_eg_desde').hidden = false;
				document.getElementById('dig_eg_hasta').hidden = false;
		    }
		}
		);
	</script>
	<!-- Fecha Digitación egreso desde -->
	<script>
	$('#digegdesde').datepicker({
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
	});
	//funcion que pone mascara de fecha
	document.getElementById('digegdesde').addEventListener('keyup', function() {
		var v = this.value;
		if (v.match(/^\d{2}$/) !== null) {
			this.value = v + '-';
		} else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
			this.value = v + '-';
		}
	});
	</script>
	<!-- Fecha digitación egreso hasta -->
	<script>
		$('#digeghasta').datepicker({
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
		});
		//funcion que pone mascara de fecha
		document.getElementById('digeghasta').addEventListener('keyup', function() {
			var v = this.value;
			if (v.match(/^\d{2}$/) !== null) {
				this.value = v + '-';
			} else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
				this.value = v + '-';
			}
		});
	</script>
<!-- ******* FIN FECHA DIGITACIÓN EGRESO ******* -->

<!-- ******* FECHA DE SALIDA LEP  		 ******* -->
	<!-- Oculta o muestra fecha salida entre -->
	<script>
		document.getElementById('optfechasal').addEventListener('change', function()
	 	// 1 -Igual  / 2 - Mayor que /3 menor que / 3 - entre
		{
		    if (this.value == 0 || this.value == 1 || this.value == 2 || this.value == 3) {
		        document.getElementById('sal_hasta').hidden = true;
		        if (this.value == 0 ) {
		        	document.getElementById('saldesde').value = "";
		        	document.getElementById('salhasta').value = "";
		        	document.getElementById('saldesde').disabled = true;
		        } else {
		        	document.getElementById('salhasta').value = "";
		        	document.getElementById('saldesde').disabled = false;
		        }
		    }
		    else {
		    	document.getElementById('saldesde').disabled = false;
				document.getElementById('sal_desde').hidden = false;
				document.getElementById('sal_hasta').hidden = false;
		    }
		}
		);
	</script>
	<!-- Fecha desde -->
	<script>
		$('#saldesde').datepicker({
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
		});
		//funcion que pone mascara de fecha
		document.getElementById('saldesde').addEventListener('keyup', function() {
			var v = this.value;
			if (v.match(/^\d{2}$/) !== null) {
				this.value = v + '-';
			} else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
				this.value = v + '-';
			}
		});
	</script>
	<!-- Fecha Hasta  -->
	<script>
		$('#salhasta').datepicker({
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
		});

		//funcion que pone mascara de fecha
		document.getElementById('salhasta').addEventListener('keyup', function() {
			var v = this.value;
			if (v.match(/^\d{2}$/) !== null) {
				this.value = v + '-';
			} else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
				this.value = v + '-';
			}
		});
	</script>
<!-- ******* FIN FECHA DE SALIDA   		 ******* -->




@endsection
