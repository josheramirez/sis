<div class="collapse navbar-collapse" id="app-navbar-collapse">		
	<!-- Left Side Of Navbar -->
	<ul class="nav navbar-nav">
		<!-- Authentication Links -->
		@if (!Auth::guest())

			@if( Auth::user()->isRole('Administrador') || Auth::user()->isRole('Pacientes')   || Auth::user()->isRole('Super Usuario LE') )
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Administración <span class="caret"></span>
					</a>
					
					<ul class="dropdown-menu" role="menu">
						@if( Auth::user()->isRole('Administrador'))
							<li>
								<a href="{{ URL::to('especialidads') }}"> Especialidades </a>
							</li>
							
							<li class="divider"></li>
							
							<li>
								<a href="{{ URL::to('establecimientos') }}"> Establecimientos </a>
							</li>
							
							<li>
								<a href="{{ URL::to('comunas') }}"> Comunas </a>
							</li>

							<li>
								<a href="{{ URL::to('vias') }}"> Tipo de Vías </a>
							</li>

							<li>
								<a href="{{ URL::to('mapaDerivacions') }}"> Mapa de Derivación </a>
							</li>	
							
							<li>
								<a href="{{ URL::to('nivels') }}"> Niveles Establecimiento </a>
							</li>
							
							<li>
								<a href="{{ URL::to('tipoEstabs') }}"> Tipo de Establecimiento </a>
							</li>
							
							<li>
								<a href="{{ URL::to('servicios') }}"> Servicios de Salud </a>
							</li>
							
							<li class="divider"></li>
							
							<li>
								<a href="{{ URL::to('generos') }}"> Género </a>
							</li>
							
							<li>
								<a href="{{ URL::to('previsions') }}"> Tipos de Previsión </a>
							</li>
							
							<li>
								<a href="{{ URL::to('tramos') }}"> Tramos Fonasa </a>
							</li>
						@endif
						@if(Auth::user()->isRole('Pacientes'))
							<li>
								<a href="{{ URL::to('pacientes') }}"> Pacientes </a>
							</li>
						@endif
						@if(Auth::user()->isRole('Super Usuario LE'))
							<li>
								<a href="{{ URL::to('users') }}"> Usuarios </a>
							</li>

							<li class="divider"></li>

							<li>
								<a href="{{ URL::to('listaesperas/ingresoSigte') }}"> SIGTE - Carga Masiva </a>
							</li>
						@endif	
					</ul>
				</li>
			@endif	
			
			@if( Auth::user()->isRole('Administrador') )
				<li class="dropdown">	
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Mantenedores <span class="caret"></span>
					</a>
					
					<ul class="dropdown-menu" role="menu">
						<li>
							<a href="{{ URL::to('causalEgresos') }}"> Causal de Egreso </a>
						</li>
						
						<li>
							<a href="{{ URL::to('cie10s') }}"> Códigos CIE-10 </a>
						</li>
						
						<li>
							<a href="{{ URL::to('etarios') }}"> Grupos Etarios </a>
						</li>
						
						<li>
							<a href="{{ URL::to('extremidads') }}"> Extremidades </a>
						</li>
						
						<li>
							<a href="{{ URL::to('motivoSolicituds') }}"> Motivo de Solicitud </a>
						</li>
						
						<li>
							<a href="{{ URL::to('planos') }}"> Plano </a>
						</li>
						
						<li>
							<a href="{{ URL::to('protocolos') }}"> Protocolo </a>
						</li>
						
						<li>
							<a href="{{ URL::to('tipoEsperas') }}"> Tipo Espera </a>
						</li>
						
						<li>
							<a href="{{ URL::to('tipoGes') }}"> Tipo GES </a>
						</li>
						
						<li>
							<a href="{{ URL::to('tipoProcedimientos') }}"> Tipo Procedimientos </a>
						</li>
						
						<li>
							<a href="{{ URL::to('tipoProcedimientosPm') }}"> Tipo Procedimientos PM </a>
						</li>
						
						<li>
							<a href="{{ URL::to('tipoSalidas') }}"> Tipo Salidas </a>
						</li>
					</ul>
				</li>		
			@endif

			@if( Auth::user()->isRole('Digitador LE') || Auth::user()->isRole('Super Usuario LE'))	
			<li class="dropdown">	
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					Lista de Espera <span class="caret"></span>
				</a>
				
				<ul class="dropdown-menu" role="menu">
					<li>
						<a href="{{ URL::to('listaesperas/create') }}" aria-expanded="false">Ingreso</a>
					</li>
					<li>	
						<a href="{{ URL::to('listaesperas/filtroEgreso') }}" aria-expanded="false">Egreso</a>
					</li>
					
					@if( Auth::user()->isRole('Super Usuario LE'))	
					<li>	
						<a href="{{ URL::to('listaesperas/filtroRegistro') }}" aria-expanded="false">Registro Lista de Esperas</a>
					</li>
					@endif		
				</ul>		
			</li>
			@endif

		@endif

		<li class="dropdown">	
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Reportes <span class="caret"></span>
			</a>
			
			<ul class="dropdown-menu" role="menu">
				@if( Auth::user()->isRole('Digitador LE') || Auth::user()->isRole('Super Usuario LE'))	
					<li >	
						<a href="{{ URL::to('listaesperas/reporternle') }}" aria-expanded="false">Reporte RNLE</a>
					</li>

					<li >	
						<a href="{{ URL::to('listaesperas/reporte') }}" aria-expanded="false">Consulta Red</a>
					</li>
				@endif	
			</ul>
		</li>

		<li class="dropdown">	
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Manuales <span class="caret"></span>
			</a>
			
			<ul class="dropdown-menu" role="menu">				
				@if( Auth::user()->isRole('Digitador LE') || Auth::user()->isRole('Super Usuario LE'))	
					<li >	
						<a href="{{ asset('/manuales/listaespera.pdf') }}" aria-expanded="false" target="_blank"> Manual de Lista de Espera </a>
					</li>
					<li >	
						<a href="{{ asset('/manuales/registro_de_lista_de_espera_no_ges.pdf') }}" aria-expanded="false" target="_blank"> Manual Registro de Lista de Espera No GES</a>
					</li>
				@endif	
			</ul>
		</li>
	</ul>

	<!-- Right Side Of Navbar -->
	<ul class="nav navbar-nav navbar-right">
		<!-- Authentication Links -->
		@if (!Auth::guest())
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					{{ Auth::user()->name }} <span class="caret"></span>
				</a>

				<ul class="dropdown-menu" role="menu">
					<li>
						<a href="{{ URL::to('users/password/cambiar') }}"> Cambiar Contraseña </a>
					</li>
					
					<li>
						<a href="{{ route('logout') }}"
							onclick="event.preventDefault();
									 document.getElementById('logout-form').submit();">
							Salir
						</a>

						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form>
					</li>
				</ul>
			</li>
		@endif
	</ul>
</div>