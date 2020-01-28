{{-- */
	$permisos = App\UserOptions::getAutUserOptions();
/*--}}
<!-- BEGIN SIDEBAR LEFT -->
<div class="sidebar-left sidebar-nicescroller {{ (Session::get('cfgHideMenu',false))?'toggle':'' }}">
	<ul class="sidebar-menu">
		<li class="{{ (Request::is('inicio') || Request::is('/')) ? 'active selected' : '' }}">
			<a href="{{ route('doInicio') }}"><i class="fa fa-dashboard icon-sidebar"></i>Inicio</a>
		</li>
           @if(!empty(Session::get('perfil')))
    		@if(in_array(2,Session::get('opciones'),true))
			<li class="dropdown">
		      <a href="#pplink" class="dropdown-toggle" data-toggle="dropdown">
		        <i class="fa fa-building fa-2x icon-sidebar"></i>
		        <i class="fa fa-angle-right chevron-icon-sidebar"></i>
		         Promocion y Publicidad
		      </a>

		      <ul class="submenu">

		      @if(count(Session::get('perfil'))>1)
		      		@if(Session::get('perfil')[0]==1 && Session::get('perfil')[1]==1)
		      				<li><a style="height:40px;" href="{{route('indexpromopub')}}" title="Nueva Solicitud Establecimientos Nacionales">Nueva Solicitud Establecimientos <br/> Nacionales</a></li>
		      		@elseif(Session::get('perfil')[0]==2 && Session::get('perfil')[1]==2)
		      					<li><a style="height:40px;" href="{{route('solicitudext')}}" title="Nueva Solicitud Titulares de registro">Nueva Solicitud Titulares de <br> registro</a></li>
					@else
						@for($i=0;$i<count(Session::get('perfil'));$i++)

							@if(Session::get('perfil')[$i]==1)
								<li><a style="height:40px;" href="{{route('indexpromopub')}}" title="Nueva Solicitud Establecimientos Nacionales">Nueva Solicitud Establecimientos <br/> Nacionales</a></li>
							@elseif(Session::get('perfil')[$i]==2)
									  <li><a style="height:40px;" href="{{route('solicitudext')}}"  title="Nueva Solicitud Titulares de registro">Nueva Solicitud Titulares de <br> registro</a></li>
							@elseif(Session::get('perfil')[$i]==2)
									  <li><a style="height:40px;" href="{{route('solicitudext')}}"  title="Nueva Solicitud Titulares de registro">Nueva Solicitud Titulares de <br> registro</a></li>
							@elseif(Session::get('perfil')[$i]==1)
								<li><a style="height:40px;" href="{{route('indexpromopub')}}" title="Nueva Solicitud Establecimientos Nacionales">Nueva Solicitud Establecimientos <br/> Nacionales</a></li>
							@endif
						@endfor
					@endif
        		@elseif(Session::get('perfil')[0] == 1)
        				<li><a href="{{route('indexpromopub')}}" title="Nueva Solicitud Establecimientos Nacionales">Nueva Solicitud Establecimientos <br/> Nacionales</a></li>
        		@elseif(Session::get('perfil')[0] == 2)
						  <li><a href="{{route('solicitudext')}}" title="Nueva Solicitud Titulares de registro">Nueva Solicitud Titulares de <br> registro</a></li>
        		@endif
        		<li><a href="{{route('ver.solicitudes')}}">Ver Solicitudes</a></li>

       		 </ul>

			</li>
			@endif
			@endif


			@if(in_array(3,Session::get('opciones'),true))
	    	<li class="dropdown">
	            <a href="#rglink" class="dropdown-toggle" data-toggle="dropdown">
	            <i class="fa fa-medkit fa-2x icon-sidebar"></i>
	            <i class="fa fa-angle-right chevron-icon-sidebar"></i>
	             Registro y Visado
	          </a>
	            <ul class="submenu">
				@if(in_array(11,Session::get('opciones'),true))
				 <li><a href="{{route('get.preregistrorv.index')}}">Nuevo Registro  <i class="fa fa-plus-circle"></i></a></li>
				@endif
	          	 <li><a href="{{route('nueva.solicitud')}}">Nueva Solicitud Post <i class="fa fa-plus-circle"></i></a></li>
	             <li><a href="{{route('ver.solicitudes.rv.pre')}}">Solicitudes Nuevo-Registro <i class="fa fa-eye"></i></a></li>
	             <li><a href="{{route('ver.solicitudes.rv')}}">Solicitudes Post-Registro <i class="fa fa-eye"></i></a></li>
	             </ul>
         	</li>
	    	@endif

			@if(in_array(12,Session::get('opciones'),true))
			<li class="dropdown">
				<a href="#rglink" class="dropdown-toggle" data-toggle="dropdown">
					<i class="icon-sidebar"><img src="{{asset('img/lipstick.png')}}"></i>
					<i class="fa fa-angle-right chevron-icon-sidebar"></i>
					Cosméticos e Higiénicos
				</a>

				<ul class="submenu">
					<li><a href="{{route('get.cospreregistro.nuevasolicitud')}}">Nueva Solicitud Pre-Registro<i class="fa fa-plus-circle"></i></a></li>
					<li><a href="{{route('cospresolicitud.lista-sol')}}">Solicitudes Pre-Registro</a></li>
					@if(in_array(13,Session::get('opciones'),true))
					<li><a href="{{route('get.cospostregistro.nuevasolicitud')}}">Nueva Solicitud Post-Registro<i class="fa fa-plus-circle"></i></a></li>
					<li><a href="{{route('get.cospostregistro.solicitudes')}}">Solicitudes Post-Registro</a></li>
					@endif
				</ul>

			</li>
			@endif

	    	@if(in_array(7,Session::get('opciones'),true))
	    	<li class="dropdown">
	            <a href="#rglink" class="dropdown-toggle" data-toggle="dropdown">
	            <i class="fa fa-edit fa-2x icon-sidebar"></i>
	            <i class="fa fa-angle-right chevron-icon-sidebar"></i>
	             Recetas
	          </a>

	            <ul class="submenu">
	          	 <li><a href="{{route('index.talonario')}}">Solicitar talonarios <i class="fa fa-plus-circle"></i></a></li>
	          	  <li><a href="{{route('ver.lista.talonario')}}">Lista solicitudes<i class="fa fa-plus-circle"></i></a></li>
	          	   <li><a href="{{route('index.recetas')}}">Mis recetas<i class="fa fa-plus-circle"></i></a></li>
	          	   <li><a href="{{route('productos.list')}}">Consulta de Productos<i class="fa fa-plus-circle"></i></a></li>	<li><a href="{{route('recetas.generar.mandamiento')}}">Generar Mandamiento<i class="fa fa-plus-circle"></i></a></li>
	             </ul>

         	</li>
	    	@endif

	    	@if(in_array(4,Session::get('opciones'),true))
	    	<li class="dropdown">
	            <a href="#rglink" class="dropdown-toggle" data-toggle="dropdown">
	            <i class="fa fa-stethoscope fa-2x icon-sidebar"></i>
	            <i class="fa fa-angle-right chevron-icon-sidebar"></i>
	             Insumos Médicos
	          </a>
	            <ul class="submenu">
	              <li><a href="{{route('indexsim')}}">Nueva Solicitud  <i class="fa fa-plus-circle"></i></a></li>
	              <!-- <li><a href="{{route('ver.solicitudes.sim')}}">Ver Solicitudes  <i class="fa fa-eye"></i></a></li> -->
	              <li><a href="{{route('ver.solicitudes.pre')}}">Solicitudes Nuevo-Registro <i class="fa fa-eye"></i></a></li>
	              <li><a href="{{route('ver.solicitudes.post')}}">Solicitudes Post-Registro <i class="fa fa-eye"></i></a></li>
	            </ul>
         	</li>
	    	@endif

	    	@if(in_array(6,Session::get('opciones'),true))
	    	<li class="dropdown">
	            <a href="#rglink" class="dropdown-toggle" data-toggle="dropdown">
	            <i class="fa fa-eyedropper fa-2x icon-sidebar"></i>
	            <i class="fa fa-angle-right chevron-icon-sidebar"></i>
	             Solicitudes de Vacunas
	          </a>
	            <ul class="submenu">

	          	 <li><a href="{{route('nueva.solicitud.vacuna')}}">Nueva Solicitud  <i class="fa fa-plus-circle"></i></a></li>
	          	 <!--<li><a href="{{route('form.sin.registro')}}">Pre-Registro<i class="fa fa-plus-circle"></i></a></li>-->
	             <li><a href="{{route('ver.solicitudes.vacunas')}}">Ver Solicitudes  <i class="fa fa-eye"></i></a></li>

	             </ul>


         	</li>
	    	@endif



	    	@if(in_array(1,Session::get('opciones'),true))
	    		<li class="dropdown">
	            <a href="#rglink" class="dropdown-toggle" data-toggle="dropdown">
	            <i class="fa fa-medkit fa-2x icon-sidebar"></i>
	            <i class="fa fa-angle-right chevron-icon-sidebar"></i>
	             Registro y Visado
	          </a>
	            <ul class="submenu">

	             <li><a href="{{route('ver.solicitudes.rv.admin')}}">Ver Solicitudes  <i class="fa fa-eye"></i></a></li>

	             </ul>


         		</li>
	    	@endif

	    	@if(in_array(15,Session::get('opciones'),true))
	    	 <li class="dropdown">
		      	<a href="#helplink" class="dropdown-toggle" data-toggle="dropdown">
		        <i class="fa fa-clipboard fa-2x icon-sidebar"></i>
		        <i class="fa fa-angle-right chevron-icon-sidebar"></i>
		       Mandamientos
		      </a>
		        <ul class="submenu">
				        <li><a href="{{route('ver.pagos.varios')}}">Pago Varios</a></li>
				        <li><a href="{{route('ver.pagos.cosmeticos')}}">Pago Inscripciones Cosméticos</a></li>
				        <li><a href="{{route('ver.pagos.higienicos')}}">Pago Inscripciones Higiénicos</a></li>
				        <li><a href="{{route('ver.pagos.medico')}}">Pago Insc. Insumos Médicos</a></li>
				 </ul>

		     </li>
		     <li class="dropdown">
		      	<a href="#helplink" class="dropdown-toggle" data-toggle="dropdown">
		        <i class="fa fa-archive fa-2x icon-sidebar"></i>
		        <i class="fa fa-angle-right chevron-icon-sidebar"></i>
		       Anualidades
		      </a>
		        <ul class="submenu">
				    <li><a href="{{route('ver.anualidades.establecimientos')}}">Anualidades de establecimientos</a></li>
				    <li><a href="{{route('ver.anualidades.registro')}}">Anualidades de Registro y Visado</a></li>
				    <li><a href="{{route('ver.anualidades.cosmeticos')}}">Anualidades de Cosméticos</a></li>
				     <li><a href="{{route('ver.anualidades.insumos')}}">Anualidades de Insumos Médicos</a></li>
				 </ul>
			 </li>
		     @endif

	    	<li class="dropdown">
		      	<a href="#helplink" class="dropdown-toggle" data-toggle="dropdown">
		        <i class="fa fa-info-circle fa-2x icon-sidebar"></i>
		        <i class="fa fa-angle-right chevron-icon-sidebar"></i>
		        Ayuda
		      </a>
		        <ul class="submenu">
		        		<li><a href="http://www.medicamentos.gob.sv/videos/video-tutorial-anualidades-pel.mp4" target="_blank">Tutorial de Anualidades</a></li>
				        <li><a href="http://www.medicamentos.gob.sv/tmp/Archivos/manual-portal/Manual%20usuario%20Sistema%20en%20Linea%20PYP.pdf" download>Manual de Publiciadad</a></li>
				        <li><a href="http://www.medicamentos.gob.sv/tmp/Archivos/manual-portal/Manual-pyp.html" target="_blank">Manual En Linea Publicidad</a></li>
				        <li><a href="http://www.medicamentos.gob.sv/tmp/Archivos/manual-urv/Manual%20usuario%20Sistema%20en%20Linea%20Especialidades.pdf" download>Manual Registro y Visado</a></li>
				        <li><a href="http://www.medicamentos.gob.sv/tmp/Archivos/manual-urv/manual-09122016.html" target="_blank">En Linea Registro y Visado</a></li>
				        <li><a href="http://www.medicamentos.gob.sv/videos/dnm-urv-portal-en-linea.mp4" target="_blank">Video de Trámites Juridicos Registro y Visado</a></li>
				        <li><a href="http://intranet.medicamentos.gob.sv/archivos/videos/video-titorial-urv.mp4" target="_blank">Video Consulta de Solicitudes</a></li>
				        <li><a href="http://www.medicamentos.gob.sv/videos/erecetas.mp4" target="_blank">Video Recetas En-Linea</a></li>
				        <li><a href="https://www.medicamentos.gob.sv/tmp/Archivos/manual-portal/Guia-para-pago-de-anualidades-2019.pdf" target="_blank">Guía para pago de anualidades</a></li>
				 </ul>

		     </li>

	   		<li>
     			 <a href="{{route('cambiocontraseña')}}">
        			<i class="fa fa-key fa-2x icon-sidebar"></i>
        			Cambiar Contraseña
      			</a>
    		</li>
    		<li>
     			<a href="{{ url('/logout') }}">
        		Cerrar Sesión
      			</a>
    		</li>


	</ul>
</div><!-- /.sidebar-left -->
<!-- END SIDEBAR LEFT -->
