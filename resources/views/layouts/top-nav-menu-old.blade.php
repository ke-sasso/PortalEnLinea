<!-- BEGIN TOP NAV -->
<div class="top-navbar">
	<div class="top-navbar-inner">
		
		<!-- Begin Logo brand -->
		<div class="logo-brand success-color" style="height: 62px;">
			<a>{!! Html::image('img/logo.png') !!}</a>
			</div><!-- /.logo-brand -->
			<!-- End Logo brand -->
			
			<div class="top-nav-content" style="padding-left: 0px; padding-right: 0px;">
				
				{{-- Menu superior --}}
				<nav class="navbar square navbar-default" role="navigation" >
					<div class="container-fluid">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" href="#fakelink"></a>
						</div>
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
								<li ><a href="{{ url('/') }}"><i class="fa fa-home icon-sidebar"></i>Inicio</a></li>
								
								@if(in_array(381, Session::get('PERMISOS')))
								<li class="dropdown">
									<a href="#fakelink" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs icon-sidebar"></i> Procesos <b class="caret"></b></a>
									<ul class="dropdown-menu square margin-list-rounded with-triangle">
										<li><a href="{{ url('/establecimientos/solicitudes') }}">Administrador de Solicitudes</a></li>
										
										<!--
											<li><a href="{{ url('/establecimientos/inspecciones') }}">Administrador de Solicitudes</a></li>
										-->
									</ul>
								</li>
								@endif

								<li class="dropdown">
									<a href="#fakelink" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-folder-open icon-sidebar"></i> Solicitudes <b class="caret"></b></a>
									<ul class="dropdown-menu square margin-list-rounded with-triangle">
										<li><a href="{{ url('/establecimientos/asignacion') }}">Solicitudes Asignadas</a></li>
									</ul>
								</li>
								
								@if(!((in_array(382, Session::get('PERMISOS')))  &&  (in_array(381, Session::get('PERMISOS')))))
								<li class="dropdown">
									<a href="#fakelink" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-folder-open icon-sidebar"></i> Solicitudes <b class="caret"></b></a>
									<ul class="dropdown-menu square margin-list-rounded with-triangle">
										<li><a href="{{ url('/establecimientos/asignacion') }}">Solicitudes Asignadas</a></li>
									</ul>
								</li>
								@endif
								@if(in_array(381, Session::get('PERMISOS')) ||  in_array(396, Session::get('PERMISOS')))
								<li class="dropdown">
									<a href="#fakelink" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-folder-open icon-sidebar"></i> Ficheros <b class="caret"></b></a>
									<ul class="dropdown-menu square margin-list-rounded with-triangle">
										<li><a href="{{ url('/ficheros/productos') }}">Productos</a></li>
										<li><a href="{{ url('/ficheros/establecimientos') }}">Establecimientos</a></li>
									</ul>
								</li>
								@endif
								<li class="dropdown">
									<a href="#fakelink" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cubes icon-sidebar"></i> Importaciones <b class="caret"></b></a>
									<ul class="dropdown-menu square margin-list-rounded with-triangle">
										<li><a href="{{ url('/ficheros/importaciones') }}">Listado de Importaciones</a></li>
									</ul>
								</li>
							</ul>
							
							<!-- Begin user se
							ssion nav -->
							<ul class="nav-user navbar-right">
								<li class="dropdown">
									<a href="#fakelink" class="dropdown-toggle" data-toggle="dropdown">
										Hola, <strong>{{ Auth::user()->nombresUsuario }} {{ Auth::user()->apellidosUsuario }} <b class="caret"></b></strong>
									</a>
									<ul class="dropdown-menu square primary margin-list-rounded with-triangle">
										<li><a href="{{ url('/logout') }}">Cerrar Sesión</a></li>
									</ul>
								</li>
							</ul>
							<!-- End user session nav -->
							</div><!-- /.navbar-collapse -->
							</div><!-- /.container-fluid -->
						</nav>
						<!-- End defaulr navbar -->
						</div><!-- /.top-nav-content -->
						
						</div><!-- /.top-navbar-inner -->
						</div><!-- /.top-navbar -->
						<!-- END TOP NAV -->