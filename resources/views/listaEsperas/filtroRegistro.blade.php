@extends('layouts.app4')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-10 col-lg-offset-1">
			<!--Panel Formulario Crear Contrarreferencia-->
			<div class="panel panel-default">
                <div class="panel-heading">Registro Listas de Esperas</div>
                <div class="panel-body">
					<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('/listaesperas/registro') }}">
						{{ csrf_field() }}
						<!-- Filtro Rut de paciente-->
						<div class="form-group">
                            <label for="paciente" class="col-md-4 control-label">RUN /N° Documento Paciente</label>
                            <div class="col-md-6">
								<input id="paciente" type="text" class="form-control" name="paciente" placeholder="Sin puntos ni dígito verificador" autofocus>
                            </div>
                        </div>

						<!--Elementos ocultos-->
						<input name="idPaciente" id="idPaciente" type="hidden">

						<div class="form-group">
                            <label for="estado" class="col-md-4 control-label">Estado Lista Espera</label>
                            <div class="col-md-6">
                                <select id="estado" class="form-control" name="estado">
									<option value="">Seleccione</option>
									<option value="1">Abierta</option>
									<option value="0">Cerrada</option>
									<option value="2">Eliminada</option>
								</select>
                            </div>
                        </div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Filtrar
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
<!-- LIMPIA CAMPOS AL INICIAR CARGAR PAGINA -->
<script>
$(window).bind("pageshow", function() {
	$("#paciente").val('');
	$("#estado").val('');
});
</script>
<!-- FIN LIMPIA CAMPOS AL INICIAR CARGAR PAGINA -->
@endsection
