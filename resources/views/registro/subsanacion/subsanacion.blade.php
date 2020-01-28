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


#dlgProductos{
    width:0px;
    height: 0px;  
    position: center;
    top: 0%;
    left: 0%;
    margin-top: -0px;
    margin-left: 300px;
    padding: 0px;

    }

    
#dlgExportacion{
    width:0px;
    height: 0px;  
    position: center;
    top: 0%;
    left: 0%;
    margin-top: -0px;
    margin-left: 300px;
    padding: 0px;

    }

#imprimirModal{
    width:0px;
    height: 0px; 
    position: center;
    top: 5%;
    left: 50%;
    margin-top: -0px;
    margin-left: -200px;
    padding: 0px; 
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


<div class="alert alert-warning">
	<strong>Observaciones:
 	<h5>{{$dictamen->OBSERVACIONES_USUARIO}}</h5>
 	</strong>
</div>
<div class="panel panel-success">
	
	<div class="panel-heading" role="tab" id="headingSix">
	  <h3 id="leyendTramite" class="panel-title">
	     TIPO DE TRAMITE: {{$tramite->NOMBRE_TRAMITE}} 
	  </h3>
	</div>

	<div class="panel-body">
		<form method="POST" id='frmSubsanarRV' enctype="multipart/form-data" action="{{ route('subsanacion.solicitud.post')}}" class="form-horizontal" role="form">		

			
			<div class="panel-group" id="accordion1" role="tablist" aria-multiselectable="true">

			 
				<div class="panel-body">
						
						<div class="input-group">
							<div class="row">

							@if($tramite->ID_TRAMITE==46)
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="input-group">
									<div class="input-group-addon"><b>NÚMERO DE PODER QUE HA SOLICITADO:</b></div>
									<input type="text" name="numPoder" readonly class="form-control" id="numPoder" value="{{$solicitud->ID_PODER_PROFESIONAL}}">

									 <div class="input-group-addon"><b>NÚMERO DE SOLICITUD:</b></div>
									<input type="text" name="numPoder" readonly class="form-control" id="numPoder" value="{{$solicitud->ID_SOLICITUD}}">
								</div>
							</div>

							@elseif($tramite->ID_TRAMITE==45)
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="input-group">
									<div class="input-group-addon"><b>NÚMERO DE PODER QUE HA SOLICITADO:</b></div>
									<input type="text" name="numPoder" readonly class="form-control" id="numPoder" value="{{$solicitud->ID_PODER_APODERADO}}">
									 
									 <div class="input-group-addon"><b>NÚMERO DE SOLICITUD:</b></div>
									<input type="text" name="numPoder" readonly class="form-control" id="numPoder" value="{{$solicitud->ID_SOLICITUD}}">
								</div>
							</div>
							@endif
							</div>

							
						</div>

				</div>

				<div class="panel-body">
					<div class="row">
						@if($tramite->ID_TRAMITE==45)
							<div class="col-xs-10 col-sm-10 col-md-6 col-lg-6">
								<label>Digite el número de poder que corresponde al apoderado/s :</label>
								<div class="input-group">
									<div class="input-group-addon"><b>NUEVO NÚMERO DE PODER:</b></div>
									<input type="text" name="numPoderA" class="form-control" id="numPoderA" value="">
									<span class="input-group-btn">
										<button class="btn btn-primary" id="validarPoderA" type="button">Validar! <i class="fa fa-check" aria-hidden="true"></i></button>
										</span> 
								</div>
							</div>
						@elseif($tramite->ID_TRAMITE==46)
							<div class="col-xs-10 col-sm-10 col-md-6 col-lg-6">
								<label>Digite el número de poder que corresponde al profesional :</label>
								<div class="input-group">
									<div class="input-group-addon"><b>NUEVO NÚMERO DE PODER:</b></div>
									<input type="text" name="numPoder" class="form-control" id="numPoder" value="">
									<span class="input-group-btn">
										<button class="btn btn-primary" id="validarPoder" type="button">Validar! <i class="fa fa-check" aria-hidden="true"></i></button>
										</span> 
								</div>
							</div>
						@endif

					</div>	
				</div>	
			

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<label>T&iacute;tulo para este tr&aacute;mite:</label>
					<div class="input-group">
						<div class="input-group-addon"><b>T&Iacute;TULO:</b></div>
						<select name="perfil" id="perfil" class="form-control">
                   			
                   				<option value="{{$solicitud->TITULO}}" selected readonly>{{$solicitud->TITULO}}</option>
                   			
              			</select>  
					</div>
			</div>
			<br>
			<br>
			<br>
			<br>
			<br>
			<div class="panel panel-success">
      		
				<div class="panel-heading" role="tab" id="headingSix">
				  <h4 class="panel-title">
				      PRODUCTO:
				  </h4>
				</div>				
				<div class="panel-body">
					<div class="container-fluid the-box">
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
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="nombre" >
								<div class="input-group">
									<div class="input-group-addon"><b>NOMBRE:</b></div>
									<input type="text" class="form-control" id="txtnombreprod" name="txtnombreprod" value="{{$producto->nombreComercial}}"  required readonly>
								</div>
							</div>
							
						</div>
						
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
			</div>				
		
			<div id="panel-mandamiento" class="panel panel-success">
                  <div class="panel-heading">
                    <h4 class="panel-title">NUMERO DE MANDAMIENTO</h4>
                  </div>
                  <div class="panel-body">
                    <table width="100%" class="table table-stripped table-hover">
                      
                      <tr>
                      <td>
                        <div class="checkbox">
                          <label>
                          <div class="input-group col-md-10 col-lg-8" >
                            <div class="input-group-addon">MANDAMIENTO CANCELADO POR DERECHOS DE TRÁMITE</div>
                              <input type="number" class="form-control" id="num_mandamiento" name="num_mandamiento" value="{{$solicitud->MANDAMIENTO}}" readonly required>
                          </div>
                          </label>
                        </div>
                      </td>
                      </tr>
                
                    </table>
                    
                  </div>
            </div>  

		</div>

		
		<div class="panel panel-success">
			<div class="panel-heading" role="tab" id="headingFive">
			  <h4 class="panel-title">
			      SELECCIONE LOS DOCUMENTOS A PRESENTAR EN EL TRAMITE
			  </h4>
			</div>
			<div  class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFive">
				<div class="panel-body">
					@if($archivos!=null)								
							@for($i=0;$i<count($archivos);$i++)

							<div class="form-group row"><a class="btn btn-info" href="{{route('download.file',['idSolicitudDoc' => Crypt::encrypt($archivos[$i]->ID_SOL_DOC)])}}" target="_blank">{{$archivos[$i]->nomDoc}}<i class="fa fa-download" aria-hidden="true"></i></a></div>

							@endfor
						@endif
					
				</div>
			</div>
		</div>

		<div class="panel panel-footer text-center" id="guardar">
			<input type="hidden" name="_token" id="token" value="{{csrf_token()}}" />
			<button type="button" id="guardarSoli" name"guardar" class="btn btn-primary">Confirmar</button>

			<a class="btn btn-warning" href="{!! URL::previous() !!}">Cancelar</a>
			
		</div>
		<input type="hidden" name="img_val" id="img_val" value="" />
		<input type="hidden" name="idTramite" id="idTramite" value="{{$tramite->ID_TRAMITE}}">
		<input type="hidden" name="idSolicitud" value="{{$solicitud->ID_SOLICITUD}}">
		</form>
		
	</div>
</div>
		    
@endsection
@section('js')
{!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!}
{!! Html::script('js/html2canvas.js') !!}
{!! Html::script('js/registrov/rvJs/tramitesRv.js') !!}
<script type="text/javascript">


	
	
	$('#validarPoder').click(function(event){
	   var numPoder= $('#numPoder').val();
	   var url = "{{route('get.profesional')}}";
	   var token =$('#token').val();
	   validarPoderProfesional(numPoder,token,url);
	});

	$('#validarPoderA').click(function(event){
	   var numPoder= $('#numPoderA').val();
	   var url = "{{route('get.apoderado')}}";
	   var token =$('#token').val();
	   validarPoderApoderado(numPoder,token,url);
	});


	
 	$('#guardarSoli').click(function() {
 			
 		alertify.confirm("Mensaje de sistema","Esta seguro que desea procesar este trámite?", function (asc) {
         if (asc) {
             html2canvas(document.body).then(function(canvas) {
			    // Export the canvas to its data URI representation
			    var base64image = canvas.toDataURL("image/png");
			    $('#img_val').val(base64image);
			    $('#frmSubsanarRV').submit(); 
			});
            // $('#frmSolicitudRV').submit();      
             //alertify.success("Solicitud Enviada.");

         } else {
             //alertify.error("Solicitud no enviada");
         }
        }, "Default Value").set('labels', {ok:'SI', cancel:'NO'});

 	});
            
 function b64toBlob(b64Data, contentType, sliceSize) {
        contentType = contentType || '';
        sliceSize = sliceSize || 512;

        var byteCharacters = atob(b64Data);
        var byteArrays = [];

        for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            var slice = byteCharacters.slice(offset, offset + sliceSize);

            var byteNumbers = new Array(slice.length);
            for (var i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }

            var byteArray = new Uint8Array(byteNumbers);

            byteArrays.push(byteArray);
        }

      var blob = new Blob(byteArrays, {type: contentType});
      return blob;
}    
  
</script>
@endsection	