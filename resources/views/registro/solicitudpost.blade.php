@extends('master')

@section('css')
{!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal.css') !!}
{!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') !!}
<style type="text/css">

body{

    overflow-x: hidden;
    overflow-y: scroll !important;
}
.dlgwait {
          display:    inline;
          position:   fixed;
          z-index:    1000;
          top:        0;
          left:       0;
          height:     100%;
          width:      100%;
          background: rgba( 255, 255, 255, .3 ) 
                      url("{{ asset('/img/ajax-loader.gif') }}") 
                      50% 50% 
                      no-repeat;
      }
/* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden */
body.loading {
    overflow: hidden;
}

/* Anytime the body has the loading class, our
   modal element will be visible */
body.loading .dlgwait {
    display: block;
}
textarea {
    white-space: normal;
    text-align: justify;
    -moz-text-align-last: left; /* Firefox 12+ */
    text-align-last: left;
}
.text-uppercase
{ text-transform: uppercase; }
@media screen and (min-width: 768px) {
  
  #modal-id .modal-dialog  { width:900px;}

}




</style>

@endsection

@section('contenido')
{{-- */
	$permisos = App\UserOptions::getAutUserOptions();
/*--}}
{{-- MENSAJE ERROR VALIDACIONES --}}
@if($errors->any())
    <div class="alert alert-warning square fade in alert-dismissable">
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
        <strong>Oops!</strong>
        Debes corregir los siguientes errores para poder continuar      
        <ul class="inline-popups">
            @foreach ($errors->all() as $error)
                <li  class="alert-link">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
{{-- MENSAJE DE EXITO --}}
@if(Session::has('msnExito'))
 
@endif
  {{-- MENSAJE DE ERROR --}}
  @if(Session::has('msnError'))
    <div id="error" class="alert alert-danger square fade in alert-dismissable">
      <button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
      <strong>Error:</strong>
        Algo ha salido mal{!! Session::get('msnError') !!}
    </div>
  @endif



<div class="panel panel-success">
	
	<div class="panel-heading" role="tab" id="headingSix">
	  <h3 id="leyendTramite" class="panel-title">
	      DETALLE DE LA SOLICITUD #<b>{{$solicitud->ID_SOLICITUD}}</b> :
	  </h3>
	</div>

	<div class="panel-body">
		
		<div class="panel panel-success">
  		
			<div class="panel-heading" role="tab" id="headingSix">
			  <h4 class="panel-title">
			      DETALLE DEL TR&Aacute;MITE
			  </h4>
			</div>				
			<div class="panel-body">
			
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="input-group">
								<div class="input-group-addon"><b>TIPO DE TR&Aacute;MITE</b></div>
								<input type="text" class="form-control" id="tramite" name="tramite" value="{{$tramite->NOMBRE_TRAMITE}}" readonly required>
							</div>
						</div>
						<br>
						<br>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="input-group">
								<div class="input-group-addon"><b>PERSONA SOLICITANTE</b></div>
								<input type="text" class="form-control" id="tramite" name="tramite" value="{{$solicitante->nombres.' '.$solicitante->apellidos}}" readonly required>
							</div>
						</div>
						<br>
						<br>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="input-group">
								<div class="input-group-addon"><b>DUI:</b></div>
								<input type="text" class="form-control" id="tramite" name="tramite" value="{{$solicitante->numeroDocumento}}" readonly required>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="input-group">
								<div class="input-group-addon"><b>NIT:</b></div>
								<input type="text" class="form-control" id="nit" name="nit" value="{{$solicitud->NIT_SOLICITANTE}}" readonly required>
							</div>
						</div>
						<br><br>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="input-group">
								<div class="input-group-addon"><b>NÚMERO DE MANDAMIENTO:</b></div>
								<input type="text" class="form-control" id="mandamiento" name="mandamiento" value="{{$solicitud->MANDAMIENTO}}" readonly required>
							</div>
						</div>

					</div>
					
																		
			</div>	
		</div>

		<div class="panel panel-success">
  		
			<div class="panel-heading" role="tab" id="headingSix">
			  <h4 class="panel-title">
			      PRODUCTO 
			  </h4>
			</div>				
			<div class="panel-body">
				
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="input-group">
								<div class="input-group-addon"><b>NUM. REGISTRO</b></div>
								<input type="text" class="form-control" id="txtregistro" name="txtregistro" value="{{$producto->idProducto}}" readonly required>
							</div>
						</div>
						<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
							
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="tipo" >
							<div class="input-group">
								<div class="input-group-addon"><b>TIPO:</b></div>
								<input type="text" class="form-control" id="txttipo" name="txttipo" value="{{$producto->tipoProd}}"  readonly>
							</div>
						</div>
					</div>
					<br>
					<br>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="nombre" >
							<div class="input-group">
								<div class="input-group-addon"><b>NOMBRE:</b></div>
								<input type="text" class="form-control" id="txtnombreprod" name="txtnombreprod" value="{{$producto->nombreComercial}}"  required readonly>
							</div>
						</div>
						
					</div>
					<br>
					<br>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="renovacion" >
							<div class="input-group">
								<div class="input-group-addon"><b>RENOVACION</b></div>
								<input type="text" class="form-control" id="txtrenovacion" name="txtrenovacion" value="{{$producto->ultimaRenovacion}}"  required readonly>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="vigencia" >
							<div class="input-group">
								<div class="input-group-addon"><b>VIGENCIA</b></div>
								<input type="text" class="form-control" id="txtvigencia" name="txtvigencia" value="{{$producto->vigenteHasta}}" required readonly>
							</div>
						</div>	
					</div>
																			
			</div>	
		</div>				
			
		<div class="panel panel-success">
			<div class="panel-heading" role="tab" id="headingFive">
			  <h4 class="panel-title">
			     DOCUMENTOS ADJUNTOS AL TRAMITE:
			  </h4>
			</div>
			<div  class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFive">
				<div class="panel-body">
					
					<div class="form-group ">
             		
	             		@if($archivos!=null)								
							@for($i=0;$i<count($archivos);$i++)

							<div class="form-group "><a class="btn btn-info" href="{{route('download.file',['idSolicitudDoc' => Crypt::encrypt($archivos[$i]->ID_SOL_DOC)])}}" target="_blank">{{$archivos[$i]->nomDoc}}<i class="fa fa-download" aria-hidden="true"></i></a></div>

							@endfor
						@endif
	             	
               
            </div>
				</div>
			</div>
		</div>
							
	</div>
</div>
		    
@endsection
@section('js')
{!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!}

@endsection	