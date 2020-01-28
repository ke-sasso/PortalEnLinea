@extends('master')

@section('css')
{!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal.css') !!}
{!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') !!}
{!! Html::style('plugins/smartwizard/css/smart_wizard.min.css') !!}
{!! Html::style('plugins/smartwizard/css/smart_wizard_theme_circles.min.css') !!}
{{-- SElECTIZE JS --}}
{!! Html::style('plugins/selectize-js/dist/css/selectize.bootstrap3.css') !!}
{!! Html::style('css/nuevoregistro/preurv.css') !!}
{!! Html::style('plugins/bootstrap-fileinput/css/fileinput.css') !!}
{!! Html::style('plugins/bootstrap-fileinput/themes/explorer-fa/theme.css') !!}
{!! Html::style('plugins/magnific-popup/magnific-popup.min.css') !!}

{!! Html::style('plugins/select2/css/select2.min.css') !!}



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

.text-uppercase
{ text-transform: uppercase; }
@media screen and (min-width: 768px) {

  #modal-id .modal-dialog  { width:900px;}

}



.select2-container .select2-selection--single {
    height: 34px !important;
    border-radius: 0px !important;
}

.form-group .select2-container {
    position: relative;
    z-index: 2;
    float: left;
    width: 100%;
    margin-bottom: 0;
    display: table;
    table-layout: fixed;
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

#dlgAddPinicipio{
    width:0px;
    height: 0px;
    position: fixed;
    top: 0%;
    left: 0%;
    margin-top: 100px;
    margin-left: 200px;
    padding: 0px;

}

#dlgAddPresent{
    width:0px;
    height: 0px;
    position: center;
    top: 0%;
    left: 0%;
    margin-top: 100px;
    margin-left: 200px;
    padding: 0px;

}

#dlgAddVidaUtil{
    width:0px;
    height: 0px;
    position: center;
    top: -20%;
    left: 0%;
    margin-top: 100px;
    margin-left: 200px;
    padding: 0px;

}




</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
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
<div class="modal fade" id="imprimirModal" tabindex="-1" role="dialog" aria-labelledby="myModalImprimir" aria-hidden="false" style="display: block;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="js-title-step">{!! Session::get('msnExito') !!}</h4>
      </div>
      <div class="modal-body">
          <div align="center">
          </div>
          <br>
      </div>
      <div class="modal-footer">
        <button type="button" id="cerrar1" class="btn btn-primary btn-perspective">Cerrar</button>

      </div>
    </div>
  </div>
</div>
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
	      SOLICITUD NUEVO REGISTRO:
	  </h3>
	</div>

	<div class="panel-body">
		<div id="smartwizard">
		    <ul>
		        <li><a href="#step-1" onclick="">Paso 1<br /><small>Validaci&oacute;n Mandamiento</small></a></li>
		        <li><a href="#step-2">Paso 2<br /><small>Generales del Producto</small></a></li>
		        <li><a href="#step-3">Paso 3<br /><small>Informaci&oacute;n Solicitantes</small></a></li>
		        <li><a href="#step-4">Paso 4<br /><small>Fabricantes y Acondionador</small></a></li>
		        <li><a href="#step-5">Paso 5<br /><small>Certificado de libre venta</small></a></li>
		        <li><a href="#step-6">Paso 6<br /><small>Certificado manufactura</small></a></li>
		        <li><a href="#step-7">Paso 7<br /><small>Distribuidor Nacional</small></a></li>
		        <li><a href="#step-8">Paso 8<br /><small>Info. Material de empaque</small></a></li>
		        <li><a href="#step-9">Paso 9<br /><small>Farmacología del producto</small></a></li>
		        <li><a href="#step-10">Paso 10<br /><small>Documentaci&oacute;n</small></a></li>
		        <li><a href="#step-11">Paso 11<br /><small>Finalizaci&oacute;n de la solicitud</small></a></li>
		    </ul>
		    <div>
		        <div id="step-1" class="">
                    @include('registro.nuevoregistro.pasos.paso1.mandamiento')
		        </div>
		        <div id="step-2" class="">
		            @include('registro.nuevoregistro.pasos.paso2.gnralProducto')
		        </div>
		        <div id="step-3" class="">
                    @include('registro.nuevoregistro.pasos.paso3.mainpaso3')
		        </div>
		        <div id="step-4" class="">
		            @include('registro.nuevoregistro.pasos.paso4.fabricantes')
		        </div>
		        <div id="step-5" class="">
		            @include('registro.nuevoregistro.pasos.paso5.certificadolibreventa')
		        </div>
		        <div id="step-6" class="">
		            @include('registro.nuevoregistro.pasos.paso6.certificadobpm')
		        </div>
		        <div id="step-7" class="">
		            @include('registro.nuevoregistro.pasos.paso7.distribuidor')
		        </div>
		        <div id="step-8" class="">
		            @include('registro.nuevoregistro.pasos.paso8.materialempaque')
		        </div>
		        <div id="step-9" class="">
		            @include('registro.nuevoregistro.pasos.paso9.farmacologica')
		        </div>
		        <div id="step-10" class="">
		            @include('registro.nuevoregistro.pasos.paso10.documentos')
		        </div>
		        <div id="step-11" class="">
                    @include('registro.nuevoregistro.pasos.paso11.finalizacion')
		        </div>
		    </div>
		</div>

	</div>
</div>

<form action="{{route('registropresolicitud.store.step1y2')}}" id="RegistroStep1y2" method="post">
</form>
<form action="{{route('registropresolicitud.store.step3')}}" id="RegistroPreStep3" method="post">
</form>
<form action="{{route('registropresolicitud.store.step4')}}" id="RegistroPreStep4" method="post">
</form>
<form action="{{route('registropresolicitud.store.step5')}}" id="RegistroPreStep5" method="post">
</form>
<form action="{{route('registropresolicitud.store.step6')}}" id="RegistroPreStep6" method="post">
</form>
<form action="{{route('registropresolicitud.store.step7')}}" id="RegistroPreStep7" method="post">
</form>
<form action="{{route('registropresolicitud.store.step8')}}" id="RegistroPreStep8" method="post">
</form>
<form action="{{route('registropresolicitud.store.step9')}}" id="RegistroPreStep9" method="post">
</form>
<form action="{{route('registropresolicitud.store.step10')}}" id="RegistroPreStep10" method="post" enctype="multipart/form-data">
</form>
<form action="{{route('registropresolicitud.store.step11')}}" id="RegistroPreStep11" method="post">
</form>

@endsection
@section('js')


{!! Html::script('plugins/selectize-js/dist/js/standalone/selectize.min.js') !!}
{!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!}
{!! Html::script('js/html2canvas.js') !!}
{!! Html::script('plugins/smartwizard/js/jquery.smartWizard.min.js') !!}
{!! Html::script('js/registrov/nuevoregistro/main.js') !!}
{!! Html::script('js/registrov/nuevoregistro/guardarstepbystep.js') !!}
{!! Html::script('js/registrov/nuevoregistro/validardoc.js') !!}
{!! Html::script('plugins/bootstrap-fileinput/js/fileinput.js') !!}
{!! Html::script('plugins/bootstrap-fileinput/js/locales/es.js') !!}
{!! Html::script('plugins/bootstrap-fileinput/themes/explorer-fa/theme.js') !!}
{!! Html::script('plugins/select2/js/select2.min.js') !!}
{!! Html::script('plugins/ckeditorfull/ckeditor.js') !!}
{!! Html::script('plugins/ckeditorfull/config.js') !!}
{!! Html::script('plugins/1000hz-bootstrap-validator/validator.min.js') !!}
{!! Html::script('plugins/magnific-popup/jquery.magnific-popup.min.js') !!}


<script type="text/javascript">
	var config = {
        routes: [
            {
              tiposmedicamentos: "{{route('get.preregistrorv.tiposmedicamentos')}}",
              formasfarmaceuticas: "{{route('get.preregistrorv.formasfarmaceuticas')}}",
              viasadministracion: "{{route('get.preregistrorv.viasadministracion')}}",
              materiasprimas: "{{route('get.preregistrorv.materiasprimas')}}",
              unidadesmedida: "{{route('get.preregistrorv.unidadesmedida')}}",
              empaques: "{{route('get.preregistrorv.empaques')}}",
              contenidos: "{{route('get.preregistrorv.contenidos')}}",
              colores: "{{route('get.preregistrorv.colorespresent')}}",
              materiales: "{{route('get.preregistrorv.materialespresent')}}",
              findtitular: "{{route('search.titular')}}",
              gettitular: "{{route('get.titular.registro')}}",
              getprofesional: "{{route('get.profesional.bypoder')}}",
              getrepresentantelegal: "{{route('get.representantelegal.bypoder')}}",
              getapoderados: "{{route('get.apoderados.bypoder')}}",
              findfabricante: "{{route('search.fab.imp')}}",
              finddistribuidor: "{{route('search.distribuidor.todos')}}",
              getExpedientesDocumentos: "{{route('get.expedientes.documentos')}}",
              valmandamiento: "{{route('registrovisado.validarmandamiento')}}",
              getcodigoatc: "{{route('get.codigosAtc')}}",
              getmodalidadventa: "{{route('get.modalidad.venta')}}",
              findLaboratorioRelac: "{{route('get.esta.relacionados')}}",
              getvistareconomutuo: "{{route('get.paisnumero.reconocimiento')}}",
              getpodermaquila: "{{route('get.poder.fab.maquila')}}",
              paso10Documentos:"{{route('registropresolicitud.store.step10')}}"
            }
        ],
        flags:[
            {
                mandvalidado: false,
                profesionalvalidado: false,
                representantelegal:false,
                apoderado:false,
                manyest: false,
                titular:false,
                labprincipal:false
            }
        ]
    };

    /*$('.farmaco').each(function(){
        CKEDITOR.replace( $(this).attr('id'),{
            language: 'es',
            removePlugins:'links,about,others,tools'
        });
    });*/


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

	$(document).ready(function(){



        $.fn.modal.Constructor.prototype.enforceFocus = function() {};

        //$('#speak').trigger('click');
         if (performance.navigation.type == 1) {
                $('body').modalmanager('loading');
                window.location = '{{route("get.preregistrorv.nuevosolicitud")}}';
         }

  		$('#smartwizard').smartWizard({
  			selected: 0,
            theme: 'circles',
            transitionEffect:'fade',
            showStepURLhash: true,
            keyNavigation: false,
            //toolbarSettings: {toolbarPosition: 'both'}
  		});

        $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
            var step =stepNumber + 1;
            if(step==2){
                dataStep2();
            }
            if(step==3){
              $('#idSolicitud2').val($('#idSolicitud').val());
            }
            if(step==4){
               var porId=document.getElementById("idSolicitud2").value;
               $('#idSolicitud3').val(porId);
            }
            if(step==5){
              dataStep5();
              var porId=document.getElementById("idSolicitud3").value;
              $('#idSolicitud4').val(porId);
            }
            if(step==6){
              dataStep6();
              var porId=document.getElementById("idSolicitud4").value;
              $('#idSolicitud5').val(porId);
            }
            if(step==7){
              var porId=document.getElementById("idSolicitud5").value;
              $('#idSolicitud6').val(porId);
            }
            if(step==8){
              dataStep8();
              var porId=document.getElementById("idSolicitud6").value;
              $('#idSolicitud7').val(porId);
            }
            if(step==9){
              dataStep9();
              var porId=document.getElementById("idSolicitud7").value;
              $('#idSolicitud8').val(porId);
            }

            if(step==10){
                 dataStep10();
                 var porId=document.getElementById("idSolicitud").value;
                 console.log(porId);
                  $('#idSolicitud9').val(porId);
                  console.log($('#idSolicitud9').val());
            }
            if(step==11){
                var porId=document.getElementById("idSolicitud").value;
               $('#idSolicitud10').val(porId);
            }
        });


        $("#smartwizard").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {

            var elmForm = $("#form-step-" + stepNumber);
            // stepDirection === 'forward' :- this condition allows to do the form validation
            // only on forward navigation, that makes easy navigation on backwards still do the validation when going next
            if(stepDirection === 'forward' && elmForm){
                elmForm.validator('validate');
                var elmErr = elmForm.children('.has-error');
                if(elmErr && elmErr.length > 0){
                    // Form validation failed
                    return false;
                }
                 if(stepNumber+1==1){
                        if(config.flags[0].mandvalidado==true) {
                            return true;
                         }else {
                            alertify.alert("Mensaje de sistema","El mandamiento no ha sido validado!");
                            return false;
                        }
                  }else if (stepNumber+1==2){
                        if($('#principiosactivos >tbody >tr').length<=0){
                           alertify.alert("Mensaje de sistema","¡Debe agregar al menos un principio activo!");
                            return false;
                        }else if($('#presentacionrv >tbody >tr').length<=0){
                          alertify.alert("Mensaje de sistema","¡Debe agregar al menos una presentación!");
                            return false;

                        }else{

                            return true;
                        }
                }else if(stepNumber+1==3){
                      var titular = $('input[name="tipoTitular"]:checked').val();
                      var nomTituInput = $('#nomTitularProduc').val();
                      var paisTituInput = $('#paisTituPri').val();

                      if(titular==1){
                            if(config.flags[0].titular==false){
                                alertify.alert("Mensaje de sistema","Debe de seleccionar un titular!");
                                    return false;
                            }else if(config.flags[0].profesionalvalidado==false){
                           alertify.alert("Mensaje de sistema","¡Debe validar el número de poder del profesional responsable legal dando click en el botón de buscar!");
                                    return false;

                            }else{
                                 $('#propietarioProd').val(nomTituInput);
                                 $('#paisTitular').val("El Salvador");

                                 return true;
                            }
                      }else{
                         if(config.flags[0].titular==false){
                                alertify.alert("Mensaje de sistema","Debe de seleccionar un titular!");
                                    return false;
                          }else if((config.flags[0].representantelegal==false) && (config.flags[0].apoderado==false)) {
                                alertify.alert("Mensaje de sistema","Debe validar el número de poder de un representante legal o de un apoderado dando click en el botón de buscar!");
                                return false;
                          }else if(config.flags[0].profesionalvalidado==false){
                           alertify.alert("Mensaje de sistema","Debe validar el número de poder del profesional responsable legal dando click en el botón de buscar!");
                                    return false;

                         }else{
                               $('#propietarioProd').val(nomTituInput);
                               $('#paisTitular').val(paisTituInput);

                              return true;
                         }

                    }




              }else if(stepNumber+1==4){
                        if(config.flags[0].labprincipal==false){
                                alertify.alert("Mensaje de sistema","Debe de seleccionar un fabricante principal!");
                                    return false;
                          }else{
                              return true;
                          }

                   /*     if($('#dt-fabricantesAlternos >tbody >tr').length<=0){
                            alertify.alert("Mensaje de sistema","¡Debe agregar al menos un fabricante alterno!");
                            return false;
                        }else if ($('#dt-fabricantesAcondicionador >tbody >tr').length<=0){
                            alertify.alert("Mensaje de sistema","¡Debe agregar al menos un fabricante acondicionador!");
                            return false;
                        }else{
                            return true;
                        }*/

                }else if(stepNumber+1==10){
                    return true;
                    /*var ban = validarDocumentos();
                    if(ban==true){
                        return true;
                    }else{
                       alertify.alert("Mensaje de sistema","¡Debe adjuntar un documento PDF en cada requisito que sea obligatorio y en aquellos que aplique de acuerdo al tipo de medicamento seleccionado en el paso 2!");
                        return false;
                    }*/

                }
            }
            else if (stepDirection==='backward'){
                return true;
            }

        });

          $(".top-navbar").toggleClass("toggle");
          $(".sidebar-left").toggleClass("toggle");
          $(".page-content").toggleClass("toggle");
          $(".icon-dinamic").toggleClass("rotate-180");

          $('.chosen-select').chosen();
          $('.chosen-select-deselect').chosen({ allow_single_deselect: true });

           $('.datepicker').datepicker({format: 'dd-mm-yyyy'});
           $('#codigo-atc').chosen({
                      'width':'100%'
            });

           $("#codigo-atc").on("click", function() {
               // alert('hay vamo');
                 var length = 0;
            if(length==0){ }
                length +=1;
                });
	});

    function eliminarDocumento(row) {
                var idSol=$('#idSolicitud9').val();
                var id= $(row).data('id');
                var deleteUrl = "{{url('registro-sol-registro/eliminar-documento')}}/"+idSol+"/"+id;


                alertify.confirm("Mensaje de sistema", "¿Está seguro que desea eliminarla este documento?", function (asc) {
                if (asc) {
                    $.ajax({
                        type: "GET",
                        url: deleteUrl,
                        success: function (data) {
                               //console.log(data);
                             alertify.success("¡Se eliminó su documento correctamente!");
                            $('#'+id).fileinput('clear');
                            $('#kv-success-'+id).hide();
                            $('.kv-upload-progress').hide();
                        },
                        error: function (data) {
                            console.log('Error:', "No se pudo eliminar la solicitud, contacte a DNM Informática!");
                        }
                    });
                } else {
                }
            }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});

        };

	@yield('scripts-nrv')


</script>
@endsection