<script type="text/javascript">

function validarPoderProfesional(numPoder,token,url1){

    $.ajax({
                data:  'numPoder='+numPoder+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                     if(r.status == 200){
                        console.log(r.data);
                        alertify.alert("Mensaje de sistema","El número de poder ha sido validado existosamente!");
                        revisado=1;
                        if(revisado==1 && validado==1){
                          console.log(validado);
                          $('#guardar').show();
                        }
                      }
                      else if (r.status == 400){
                          alertify.alert("Mensaje de sistema - Error",r.message);
                      }else if(r.status == 401){
                          alertify.alert("Mensaje de sistema",r.message, function(){
                              window.location.href = r.redirect;
                          });
                      }else{//Unknown
                          alertify.alert("Mensaje de sistema","El número de poder no ha podido ser validado, intentelo de nuevo!");
                          //console.log(r);
                      }
                },
                error: function(data){
                    // Error...
                    var errors = $.parseJSON(data.responseText);
                    //console.log(errors);
                    $.each(errors, function(index, value) {
                        $.gritter.add({
                            title: 'Error',
                            text: value
                        });
                    });
                }
            });
}

function validarPoderApoderado(numPoder,token,url1){

    $.ajax({
                data:  'numPoder='+numPoder+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                     if(r.status == 200){
                        console.log(r.data);
                        alertify.alert("Mensaje de sistema","El número de poder ha sido validado existosamente!");
                        revisado=1;
                        if(revisado==1 && validado==1){
                          console.log(validado);
                          $('#guardar').show();
                        }
                      }
                      else if (r.status == 400){
                          alertify.alert("Mensaje de sistema - Error",r.message);
                      }else if(r.status == 401){
                          alertify.alert("Mensaje de sistema",r.message, function(){
                              window.location.href = r.redirect;
                          });
                      }else{//Unknown
                          alertify.alert("Mensaje de sistema","El número de poder no ha podido ser validado, intentelo de nuevo!");
                          //console.log(r);
                      }
                },
                error: function(data){
                    // Error...
                    var errors = $.parseJSON(data.responseText);
                    //console.log(errors);
                    $.each(errors, function(index, value) {
                        $.gritter.add({
                            title: 'Error',
                            text: value
                        });
                    });
                }
            });
}
</script>
