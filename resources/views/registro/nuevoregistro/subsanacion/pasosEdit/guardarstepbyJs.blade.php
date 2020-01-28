<script type="text/javascript">

$("#paso1Guardar").click(function() {
   config.flags[0].mandvalidado=true;
        alertify.alert("Mensaje de sistema","¡Mandamiento validado!");
});

$('#btnStep2').click(function(e){
    alertify.confirm("Mensaje de sistema", "¡Asegúrese de haber modificado todos los campos que le fueron observados. Una vez enviado los datos ya no podra subsanar los campos que a modificado! ¿Está seguro de realizar esta acción?", function (asc) {
                if (asc) {
                       var formObj = $('#RegistroStep1y2');
                       var formURL = formObj.attr("action");
                       var formData = new FormData($("#RegistroStep1y2")[0]);
                       guardarPaso(formURL,formData);
                }else{
                }
    }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});
});



$('#btnStep3').click(function(e){


                      var titular = $('input[name="tipoTitular"]:checked').val();
                      var nomTituInput = $('#nomTitularProduc').val();
                      var paisTituInput = $('#paisTituPri').val();

                    if(titular==1){
                            if(config.flags[0].titular==false){
                                alertify.alert("Mensaje de sistema","Debe de seleccionar un titular!");

                            }else if(config.flags[0].profesionalvalidado==false){
                           alertify.alert("Mensaje de sistema","Debe validar el número de poder del profesional responsable legal dando click en el botón de buscar!");


                            }else{
                                  alertify.confirm("Mensaje de sistema", "¡Asegúrese de haber modificado todos los campos que le fueron observados. Una vez enviado los datos ya no podra subsanar los campos que a modificado! ¿Está seguro de realizar esta acción?", function (asc) {
                                              if (asc) {
                                                     var formObj = $('#RegistroPreStep3');
                                                     var formURL = formObj.attr("action");
                                                     var formData = new FormData($("#RegistroPreStep3")[0]);
                                                     guardarPaso(formURL,formData);
                                              }else{
                                              }
                                  }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});


                            }
                    }else{
                         if(config.flags[0].titular==false){
                                alertify.alert("Mensaje de sistema","Debe de seleccionar un titular!");

                          }else if((config.flags[0].representantelegal==false) && (config.flags[0].apoderado==false)) {
                                alertify.alert("Mensaje de sistema","Debe validar el número de poder de un representante legal o de un apoderado dando click en el botón de buscar!");

                          }else if(config.flags[0].profesionalvalidado==false){
                           alertify.alert("Mensaje de sistema","Debe validar el número de poder del profesional responsable legal dando click en el botón de buscar!");
                         }else{
                            alertify.confirm("Mensaje de sistema", "¡Asegúrese de haber modificado todos los campos que le fueron observados. Una vez enviado los datos ya no podra subsanar los campos que a modificado! ¿Está seguro de realizar esta acción?", function (asc) {
                                      if (asc) {
                                               var formObj = $('#RegistroPreStep3');
                                               var formURL = formObj.attr("action");
                                               var formData = new FormData($("#RegistroPreStep3")[0]);
                                               guardarPaso(formURL,formData);
                                      }else{
                                      }
                              }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});



                         }

                    }


});

$('#btnStep4').click(function(e){

      if(config.flags[0].labprincipal==false){
         alertify.alert("Mensaje de sistema","Debe de seleccionar un fabricante principal!");

      }else{
                       alertify.confirm("Mensaje de sistema", "¡Asegúrese de haber modificado todos los campos que le fueron observados. Una vez enviado los datos ya no podra subsanar los campos que a modificado! ¿Está seguro de realizar esta acción?", function (asc) {
                                      if (asc) {
                                              var formObj = $('#RegistroPreStep4');
                                              var formURL = formObj.attr("action");
                                              var formData = new FormData($("#RegistroPreStep4")[0]);
                                              guardarPaso(formURL,formData);
                                      }else{
                                      }
                         }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});


      }

});


$('#btnStep5').click(function(e){

                          alertify.confirm("Mensaje de sistema", "¡Asegúrese de haber modificado todos los campos que le fueron observados. Una vez enviado los datos ya no podra subsanar los campos que a modificado! ¿Está seguro de realizar esta acción?", function (asc) {
                                      if (asc) {
                                                var formObj = $('#RegistroPreStep5');
                                                var formURL = formObj.attr("action");
                                                var formData = new FormData($("#RegistroPreStep5")[0]);
                                                guardarPaso(formURL,formData);
                                      }else{
                                      }
                         }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});



});
$('#btnStep6').click(function(e){

                    alertify.confirm("Mensaje de sistema", "¡Asegúrese de haber modificado todos los campos que le fueron observados. Una vez enviado los datos ya no podra subsanar los campos que a modificado! ¿Está seguro de realizar esta acción?", function (asc) {
                                      if (asc) {
                                                var formObj = $('#RegistroPreStep6');
                                                var formURL = formObj.attr("action");
                                                var formData = new FormData($("#RegistroPreStep6")[0]);
                                                guardarPaso(formURL,formData);
                                      }else{
                                      }
                      }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});



});

$('#btnStep7').click(function(e){

                    alertify.confirm("Mensaje de sistema", "¡Asegúrese de haber modificado todos los campos que le fueron observados. Una vez enviado los datos ya no podra subsanar los campos que a modificado! ¿Está seguro de realizar esta acción?", function (asc) {
                                      if (asc) {
                                                 var formObj = $('#RegistroPreStep7');
                                                 var formURL = formObj.attr("action");
                                                 var formData = new FormData($("#RegistroPreStep7")[0]);
                                                 guardarPaso(formURL,formData);
                                      }else{
                                      }
                      }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});



});
$('#btnStep8').click(function(e){


                     alertify.confirm("Mensaje de sistema", "¡Asegúrese de haber modificado todos los campos que le fueron observados. Una vez enviado los datos ya no podra subsanar los campos que a modificado! ¿Está seguro de realizar esta acción?", function (asc) {
                                      if (asc) {
                                          var formObj = $('#RegistroPreStep8');
                                          var formURL = formObj.attr("action");
                                          var formData = new FormData($("#RegistroPreStep8")[0]);
                                          guardarPaso(formURL,formData);
                                      }else{
                                      }
                      }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});

});
$('#btnStep9').click(function(e){
   //  alertify.alert("Mensaje de Sistema","¡Datos del paso 9 se han guardado con exito!");

                                  /*var a1 =CKEDITOR.instances.farm.getData();
                                  $('#farm').val(a1);
                                  console.log($('#farm').val());
                                  var a2 = CKEDITOR.instances.mecaaccion.getData();
                                  $('#mecaaccion').val(a2);


                                  var a3 =CKEDITOR.instances.indicacion.getData();
                                  $('#indicacion').val(a3);

                                  var a4 =CKEDITOR.instances.contrad.getData();
                                  $('#contrad').val(a4);

                                  var a5 =CKEDITOR.instances.dos.getData();
                                  $('#dos').val(a5);

                                  var a6 = CKEDITOR.instances.efectos.getData();
                                  $('#efectos').val(a6);

                                   var a7 = CKEDITOR.instances.adv.getData();
                                  $('#adv').val(a7);

                                  var a8 = CKEDITOR.instances.interaccion.getData();
                                  $('#interaccion').val(a8);*/

                                  alertify.confirm("Mensaje de sistema", "¡Asegúrese de haber modificado todos los campos que le fueron observados. Una vez enviado los datos ya no podra subsanar los campos que a modificado! ¿Está seguro de realizar esta acción?", function (asc) {
                                      if (asc) {
                                           var formObj = $('#RegistroPreStep9');
                                           var formURL = formObj.attr("action");
                                           var formData = new FormData($("#RegistroPreStep9")[0]);
                                           guardarPaso(formURL,formData);
                                      }else{
                                      }
                                   }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});



});
$('#btnStep10').click(function(e){

                                 alertify.confirm("Mensaje de sistema", "¡Asegúrese de haber modificado todos los campos que le fueron observados. Una vez enviado los datos ya no podra subsanar los campos que a modificado! ¿Está seguro de realizar esta acción?", function (asc) {
                                      if (asc) {
                                      var formObj = $('#RegistroPreStep10');
                                      var formURL = formObj.attr("action");
                                      var formData = new FormData($("#RegistroPreStep10")[0]);
                                      guardarPaso(formURL,formData);
                                      }else{
                                      }
                                   }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});
});
$('#btnStep11').click(function(e){


  alertify.confirm("Mensaje de sistema", "¡Antes de enviar solicitud! verificar que a guardado cada paso donde ingreso o selecciono información. ¿Esta seguro de envia la solicitud?", function (asc) {
              var formObj = $('#RegistroPreStep11');
              var formURL = formObj.attr("action");
              var formData = new FormData($("#RegistroPreStep11")[0]);
              guardarPaso(formURL,formData);
  }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});

});

function guardarPaso(formURL,formData){
    $.ajax({
        data: formData,
        url:  formURL,
        type: 'post',
        mimeType:"multipart/form-data",
        contentType: false,
        cache: false,
        processData:false,
        dataType    : 'json',
        beforeSend: function() {
            $('body').modalmanager('loading');
        },
        success:  function (response){
            $('body').modalmanager('loading');
            alertify.alert("Mensaje de Sistema","<strong><p class='text-primary text-justify'>"+response.message+"</p></strong>");
            if(response.paso){
              $('#idSolicitud').val(response.data);
            }
            if(response.final){
              alertify.alert("Mensaje de Sistema","<strong><p class='text-primary text-justify'>"+response.message+"</p></strong>", function(){
                 // setTimeout("redireccionarPagina()", 1000);
                 window.location = '{{route('get.preregistrorv.index')}}';
              });

            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-primary text-justify'>"+response.message+"</p></strong>");
            }
        },
        error: function(response) {
           // console.log(response);
            $('body').modalmanager('loading');
            if(response.status==422){
                alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ADVERTENCIA: "+ response.responseJSON.errors +"</p></strong>");
            }
            else{

                alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA: "+ response.responseJSON.message +"</p></strong>");
            }
        }
    });
}
function redireccionarPagina() {
  window.location = '{{route('get.preregistrorv.index')}}';
}

</script>
