@extends('master')

@section('css')




@endsection

@section('contenido')
<div align="center"><h4>BIENVENIDO AL PORTAL DE TRAMITES EN LINEA </h4></div>

@if($alertSesion)
<div class="alert alert-warning" style="color:#242523;">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<strong>Recuerde</strong> Después de realizar sus actividades en Portal en Línea, por motivos de seguridad cierre su sesión en el sistema. Esto puede hacerlo dando clic en la opción "Cerrar Sesión
</div>
@endif
{{-- MENSAJE DE EXITO --}}
	@if(Session::has('msnExito'))
		<div class="alert alert-success square fade in alert-dismissable">
			<button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
			<strong>Enhorabuena!</strong>
			{{ Session::get('msnExito') }}
		</div>
	@endif
	{{-- MENSAJE DE ERROR --}}
	@if(Session::has('msnError'))
		<div class="alert alert-danger square fade in alert-dismissable">
			<button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
			<strong>Algo ha salido mal! </strong>{{ Session::get('msnError') }}
		</div>
	@endif
<!-- 
	<div class="row">
		<a href="../mandamientos/pagosVarios">
			<div class="col-xs-6 col-md-6">
				<div class="tiles facebook-tile text-center">
					<i class="fa fa-clipboard fa-2x icon-sidebar"></i>
					<h4>Generar mandamientos </h4>(Pagos Varios)
				</div>
			</div>
		</a>
		<a href="../anualidades/establecimientos">
			<div class="col-xs-6 col-md-6">
				<div class="tiles twitter-tile text-center">
					<i class="fa fa-archive fa-2x icon-sidebar"></i>
					<h4>Pago de Anualidad </h4>(Establecimientos)
				</div>
			</div>
		</a>


	</div><!-- /.row -->

@if(!in_array(1,Session::get('opciones'),true))

	{!!Form::open(['route' => 'actualizar.datos','method' => 'POST', 'role'=>'form', 'files' => true])!!}
	<div class="the-box">


		    <h3><strong>Actualice sus datos
		    </strong></h3>
		    <div class="row">
				    <div class="form-group col-sm-6">
				                <label>Tratamiento:</label>
								<select name="tratamiento" id="tratamiento" class="form-control">
									<option value="0">Seleccione...</option>
									@foreach($tratamientos as $tra)
		                                @if($persona->idTipoTratamiento==$tra->idTipoTratamiento)
										    <option value="{{$tra->idTipoTratamiento}}" selected>{{$tra->nombreTratamiento}}</option>
		                                @else
		                                    <option value="{{$tra->idTipoTratamiento}}">{{$tra->nombreTratamiento}}</option>
		                                @endif
									@endforeach
								</select>
				     </div>
					<div class="form-group col-sm-6">
		                <label>Nombre completo:</label>
						<input type="text" id="nombre"name="nombre" class="form-control" disabled value="{{$persona->nombres.' '.$persona->apellidos}}">
		            </div>
            </div>
            <div class="row">
		            <div class="form-group col-sm-6">
		                <label>DUI:</label>
						<input type="text" id="dui"name="dui" class="form-control" disabled value="{{$persona->numeroDocumento}}">
		            </div>
		            <div class="form-group col-sm-6">
		                <label>NIT:</label>
						<input type="text" id="nit"name="nit" class="form-control" disabled value="{{$nit}}">
		            </div>
            </div>
	 	 	<div class="form-group">
                {!! Form::label('correo', 'Correo electrónico:') !!}
                {!! Form::email('correo',$persona->emailsContacto,['id'=>'correo','class' => 'form-control', 'required'])!!}
            </div>
         @if($persona->tels!=null)
				@if(count($persona->tels)==2)
	            <div class="row">
		            <div class="form-group col-sm-4">
		                <label>Teléfono fijo:</label>
						<input type="text" id="numtel"name="numtel" value="{{$persona->tels[0]}}" class="form-control" >
		            </div>
		            <div class="form-group col-sm-4">
		                <label>Teléfono celular:</label>
						<input type="text" id="numtel1"name="numte1l" value="{{$persona->tels[1]}}" class="form-control" >
		            </div>
		            <div class="form-group col-sm-4">
		                <label>FAX:</label>
						<input type="text" id="fax"name="fax" value="{{$persona->fax}}" class="form-control" >
		            </div>
	            </div>
	             @elseif(is_array($persona->tels)==true)
	             <div class="row">

		            <div class="form-group col-sm-4">
		                <label>Teléfono Fijo:</label>
						<input type="text" id="numtel"name="numtel" value="{{$persona->tels[0]->telefono1}}" class="form-control" >
		            </div>
		            <div class="form-group col-sm-4">
		                <label>Teléfono Celular:</label>
						<input type="text" id="numtel1"name="numte1l" value="{{$persona->tels[0]->telefono2}}" class="form-control" >
		            </div>
		            <div class="form-group col-sm-4">
		                <label>FAX:</label>
						<input type="text" id="fax"name="fax" value="{{$persona->fax}}" class="form-control" >
		            </div>
	            </div>
	            @else
	            	<div class="row">

		            <div class="form-group col-sm-4">
		                <label>Teléfono fijo:</label>
						<input type="text" id="numtel"name="numtel" value="{{$persona->tels->telefono1}}" class="form-control" >
		            </div>
		            <div class="form-group col-sm-4">
		                <label>Teléfono celular:</label>
						<input type="text" id="numtel1"name="numte1l" value="{{$persona->tels->telefono2}}" class="form-control" >
		            </div>
		            <div class="form-group col-sm-4">
		                <label>FAX:</label>
						<input type="text" id="fax"name="fax" value="{{$persona->fax}}" class="form-control" >
	            	</div>
	            @endif
	      	@else
	      		 <div class="row">

		            <div class="form-group col-sm-4">
		                <label>Teléfono fijo:</label>
						<input type="text" id="numtel"name="numtel" value="" class="form-control" >
		            </div>
		            <div class="form-group col-sm-4">
		                <label>Teléfono celular:</label>
						<input type="text" id="numtel1"name="numte1l" value="" class="form-control" >
		            </div>
		            <div class="form-group col-sm-4">
		                <label>FAX:</label>
						<input type="text" id="fax"name="fax" value="{{$persona->fax}}" class="form-control" >
		            </div>
	            </div>
	    	@endif
	    	        <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="input-group">
                            <span class="badge badge-info">Señalo para oír notificaciones:</span>&nbsp;&nbsp;&nbsp;
                             <label class="radio-inline">
                                <input type="radio" name="oirNotificaciones" id="oirNotificaciones" value="1" @if($notificaciones->oirNotificaciones==1) checked @endif /> Correo electrónico
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="oirNotificaciones"  id="oirNotificaciones" value="2" @if($notificaciones->oirNotificaciones==2) checked @endif /> Instalaciones de la DNM
                            </label>
                        </div>
                    </div>
                </div>



          	<div class="from-group" align="center">
          		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				{!!Form::submit('Guardar', ['class' => 'btn btn-primary'])!!}
			</div>

	 </div>

	{!!Form::close()!!}

@endif
@endsection

@section('js')

     <script type="text/javascript">
            console.log('{{implode(",",Session::get('opciones'))}}');
     </script>

@endsection
