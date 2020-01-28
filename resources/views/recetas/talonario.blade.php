
@extends('master')
{{-- CSS ESPECIFICOS --}}
@section('css')

<style type="text/css">
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
</style>
@endsection

{{-- CONTENIDO PRINCIPAL --}}
@section('contenido')
{{-- ERRORES DE VALIDACIÓN --}}
@if($errors->any())
	<div class="alert alert-warning square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
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
	<div class="alert alert-success square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		<strong>Enhorabuena!</strong>
		{{ Session::get('msnExito') }}
	</div>
@endif
{{-- MENSAJE DE ERROR --}}
@if(Session::has('msnError'))
	<div class="alert alert-danger square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		<strong>Auchh!</strong>
		Algo ha salido mal.	{{ Session::get('msnError') }}
	</div>
@endif
<div class="dlgwait"></div>
<div class="panel panel-success">
<div class="panel-heading">
    <h3 class="panel-title">NUEVA SOLICITUD TALONARIO</h3>
    <input type="hidden" name="idSolicitud" id="idSolicitud" class="form-control" value="">
  </div>
		<form id="infoGeneral" method="post" action="{{route('store.talonario')}}" >
	
				<div class="panel-body">
        
				

                <div class="form-group">
                     <div class="row">
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Médico:</b></div>
                      <input type="text" class="form-control" id="nombresSolicitante" name="nombresSolicitante" value="{{Session::get('name').' '.Session::get('lastname')}}" autocomplete="off" disabled>
                    </div>
                    </div>
                      
                    
                </div>
                </div>

                <div class="form-group">
                     <div class="row">
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Número de talonarios a solicitar:</b></div>
                      <input type="number" minlength="0" min="1" class="form-control" id="numTalonario" name="numTalonario" autocomplete="off" required="">
                    </div>
                    </div>
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Fecha:</b></div>
                      <input type="text" class="form-control" id="fecha" value="<?php echo  date("d/m/Y"); ;?>" name="fecha" autocomplete="off" disabled>
                    </div>
                    </div>
                     
                </div>
                </div>
                <div class="form-group">
                     <div class="row">
                     <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Justificación:</b></div>
                      <textarea class="form-control" rows="2" id="justificacion" name="justificacion" autocomplete="off"></textarea>
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
                              <input type="number" class="form-control" id="num_mandamiento" name="mandamiento" value="" required>
                          </div>
                          <div align="right">
                          <button  type="button" name="validar" id="validar" class="btn btn-primary btn-perspective">Validar</i></button>
                          </div>
                          </label>
                        </div>
                      </td>
                      </tr>
                
                    </table>
                    
                  <div>
            </div>

               <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                <input type="hidden" name="nit" value="{{$nit}}" />

               <div class="panel-footer text-center" id="id-guardar">
               <button type="submit" id="btnGuardar" class="btn btn-primary">GUARDAR <i class="fa fa-check"></i></button>
               </div>
              </form>
  
			</div>

    </div>




	
@endsection

{{-- JS ESPECIFICOS --}}
@section('js')


 {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!} 
{{-- Bootstrap Modal --}}

<script type="text/javascript">

$(document).ready(function(){
    $('.dlgwait').hide();
    $('#id-guardar').hide();

    $('#validar').click(function(event){
      var mandamiento = $('#num_mandamiento').val();
      var token =$('#token').val();
      var num= $('#numTalonario').val();
      //console.log(mandamiento);
      if(num!=''){
      $.ajax({
                data:'mandamiento='+mandamiento+'&numTalonario='+num+'&_token='+token,
                url:   "{{route('validar.mandamiento.recetas')}}",
                type:  'post',
               
                beforeSend: function() {
                    $('body').modalmanager('loading');
                },
                success:  function (r){
                    $('body').modalmanager('loading');
                    if(r.status == 200){
                      alertify.alert("Mensaje de sistema",'El mandamiento es válido para usar en este trámite');
                      $('#id-guardar').show();
                      document.getElementById("num_mandamiento").readOnly = true;
                      
                    }
                    else if (r.status == 400){
                        alertify.alert("Mensaje de sistema - Error",r.message);
                    }else if(r.status == 401){
                        alertify.alert("Mensaje de sistema",r.message, function(){
                            window.location.href = r.redirect;
                        });
                    }else{//Unknown
                        alertify.alert("Mensaje de sistema","Este mandamiento no ha sido pagado o ya ha sido utilizado");
                        console.log(r);
                    }
                },
                error: function(data){
                    // Error...
                    var errors = $.parseJSON(data.responseText);
                    console.log(errors);
                    $.each(errors, function(index, value) {
                        $.gritter.add({
                            title: 'Error',
                            text: value
                        });
                    });
                }
            });
      }else{
        alertify.alert("Mensaje de sistema","¡ADVERTENCIA! Debe de ingresar el número de talonarios a solicitar");
      }

    });

    $('#btnGuardar').on('click', function(event) 
    {
        $('#btnGuardar').addClass('hidden');
        $('.dlgwait').show();
    });
});


</script>
@endsection
