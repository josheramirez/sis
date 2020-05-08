<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		<title>{{ config('app.name', 'Laravel') }}</title>
		
		<!-- Estilos -->
		<style>
		.container {
			position: relative;
		}
		.head {
			position: relative;
			height: 100px;
			width: 100%;
		}
		.image {
			position: absolute;
			left: 0px;
			width: 100px;
		}
		.image>img {
			height: 100px;
			width: auto;
		}
		.title {
			margin-left: 10px;
			font-family: Arial, Helvetica, sans-serif;
			text-align: center;
			padding-top: 30px;
		}
		.body {
			position: relative;
			margin-top: 5px;
			width: 100%;
		}
		.titulo {
			position: relative;
			background-color: #f3f3f3;
			font-family: Arial, Helvetica, sans-serif;
			font-weight: bold;
			font-size: 12px;
			padding: 5px;
		}
		.detalle {
			position: relative;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
			padding: 5px;
		}
		.left {
			position: absolute;
			margin-left: 0px;
		}
		.right {
			position: relative;
			margin-left: 350px;
		}
		.bottom {
			position: absolute;
			font-family: Arial, Helvetica, sans-serif;
			font-weight: bold;
			font-size: 12px;
			margin-left: 560px;	
    		bottom: 5;
		}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="head">
				<div class="image">
					<img src="http://10.8.64.41/sic/image/SSMOC.jpg">
				</div>
				<div class="title">
					<h4>LISTA DE ESPERA</h4>
				</div>
			</div>
			</br>
			<div class="body">
				<div class="titulo">
					PACIENTE 
				</div>
				<div class="detalle">
					<div class="detalle">
						<div class="left">
							<b>Nombre:</b> {{ $paciente->nombre . ' '.  $paciente->apPaterno . ' ' . $paciente->apMaterno }}
						</div>	
						<div class="right">
							@if( $paciente->tipoDoc == 1 )
								<b>R.U.N.:</b> {{ $paciente->rut }}-{{ $paciente->dv }}
							@else
								<b>Número de Documento:</b> {{ $paciente->numDoc }}
							@endif
						</div>	
					</div>	
					<div class="detalle">
						<div class="left">
							<b>Fecha Nacimiento:</b> {{ $fechaNacimiento }}
						</div>
						<div class="right">
							<b>Edad:</b> {{ $edad }}
						</div>
					</div>	
					<div class="detalle">
						<b>Dirección:</b> {{ $paciente->direccion . ' ' . $paciente->numero }}
					</div>
					<div class="detalle">
						<div class="left">
							<b>Teléfono:</b> {{ $paciente->telefono }}
						</div>
						<div class="right">
							<b>Teléfono Alt:</b> {{ $paciente->telefono2 }}
						</div>
					</div>
					<div class="detalle">
							<b>Correo Electrónico:</b> {{ $paciente->email }}
					</div>	
					<div class="detalle">
						<div class="left">
							<b>Previsión:</b> {{ $prevision }}
						</div>
						<div class="right">
							<b>Tramo:</b> {{ $tramo }}
						</div>
					</div>
					<div class="detalle">
						<div class="left">
							@if( $paciente->prais == 1 )
								<b>Prais:</b> Si
							@else
								<b>Prais:</b> No
							@endif
						</div>
						<div class="right">
							@if( $paciente->funcionario == 1 )
								<b>Funcionario:</b> Si 
							@else
								<b>Funcionario:</b> No
							@endif	
						</div>
					</div>
				</div>
				<div class="titulo">
					DATOS DE INGRESO
				</div>
				<div class="detalle">
					<div class="detalle">
						<div class="left">
							<b>GES:</b> {{ $tipoGes }}
						</div>
						<div class="right">
							<b>Fecha Entrada:</b> {{ $fechaentrada }}
						</div>
					</div>
					<div class="detalle">
						<div class="left">
							<b>Establecimiento Origen:</b> {{ $estOrigen->name }}
						</div>
						<div class="right">
							<b>Establecimiento Destino:</b> {{ $estDest->name }}
						</div>
					</div>
					<div class="detalle">
						<div class="left">
							<b>R.U.N. Médico Solicitante:</b> {{ $medico }}
						</div>
						<div class="right">
							<br>
						</div>
					</div>
					<div class="detalle">
						<div class="left">
							<b>Fecha Citación:</b> {{ $fechacitacion }}
						</div>
						<div class="right">
							<b>Prestamin:</b> {{ $listaEspera->prestamin_ing }}
						</div>
					</div>
					<div class="detalle">
						<b>Especialidad:</b> {{ $especialidad_ing->name }}
					</div>
					<div class="detalle">
						<b>Diagnóstico (CIE-10):</b> {{ $cie10 }}
					</div>
					<div class="detalle">
						<b>Precisión Diagnóstica:</b> {{ $listaEspera->precdiag }}
					</div>		
				</div>	
				<div class="titulo">
					PRESTACIÓN
				</div>
				<div class="detalle">
					<div class="detalle">
						<b>Tipo Prestación:</b> {{ $prestacion }}
					</div>
					<div class="detalle">
						<b>Tipo Procedimiento:</b> 
						@if( $procedimiento_pm != '' )
							{{ $procedimiento_pm }}
						@endif
					</div>
					<div class="detalle">
						<b>Procedimiento:</b> 
						@if( $procedimiento_pm != '' )
							{{ $procedimiento }}
						@endif	
					</div>
					<div class="detalle">
						<div class="left">
							<b>Plano:</b> {{ $plano }}
						</div>	
						<div class="right">
							<b>Extremidad:</b> {{ $extremidad }}
						</div>
					</div>
				</div>
				@if( $listaEspera->active == 0 )
					<div class="titulo">
						DATOS DE EGRESO
					</div>
					<div class="detalle">
						<div class="left">
							<b>Fecha Salida:</b> 
							@if( $fechaegreso != '' )
								{{ $fechaegreso }}
							@endif	
						</div>
						<div class="right">
							<b>Prestamin Salida:</b> 
							@if( $prestamin_egr != '' )
								{{ $prestamin_egr }}
							@endif	
						</div>
					</div>	
					<div class="detalle">
						<div class="left">
							<b>Establecimiento Resuelve:</b> {{ $estResuelve }}
						</div>
						<div class="right">
							<b>Causal Egreso:</b> {{ $CausalEgresos }}
						</div>
					</div>	
					<div class="detalle">
						<div class="left">
							<b>R.U.N. Médico Resuelve:</b> {{ $listaEspera->run_medico_resol }}-{{ $listaEspera->dv_medico_resol }}
						</div>
						<div class="right">
							<b>Resultado:</b> {{ $listaEspera->resultado }}
						</div>
					</div>	
				@endif 				
			</div>
			<div class="bottom">
				<b><?=date("m/d/Y h:i:s a");?></b>
			</div>	
		</div>
	</body>
</html>

