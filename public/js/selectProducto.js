function getGenerales(idProducto,token,url1){
    var idPropietario;
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                   $('#panel-generales').html(r);

                },
            });
    //return idPropietario;
}

function getPropietarioByProd(idProducto,token,url1){
    
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                    $('#panel-propietarios').html(r);

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
}



function getProfesionalByProd(idProducto,token,url1){
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                    
                   $('#panel-profesional').html(r);
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
}

function getFabricantesByProd(idProducto,token,url1){
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                    $('#panel-fabricante').html(r);
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
}

function getDistribuidoresByProd(idProducto,token,url1){
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                    $('#panel-distribuidor').html(r);
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
}

function getFormaFarm(idProducto,token,url1){
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                    $('#panel-forma').html(r);
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
}

function getPresentacion(idProducto,token,url1){
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                    $('#panel-presentacion').html(r);
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
}

function getPrincipiosA(idProducto,token,url1){
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                    $('#panel-principios').html(r);
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
}

function getFormula(idProducto,token,url1){
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                    $('#panel-formula').html(r);
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
}

function getPoderes(idProducto,token,url1){
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                    $('#panel-poderes').html(r);
                    //console.log(r);
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
}


function getLaboratorio(idProducto,token,url1){
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                    $('#panel-laboratorios').html(r);
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
}

function getExportadores(idProducto,token,url1){
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                    $('#panel-nomexp').html(r);
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
}


function getExcipientes(idProducto,token,url1){
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                    $('#panel-excipientes').html(r);
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
}

function getFormula(idProducto,token,url1){
    var idPropietario;
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                   $('#panel-formula').html(r);
                   
                },
            });
    //return idPropietario;
}


function getFabricantesProd(idProducto,idTramite,token,url1){

    
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                    if(r.status == 200){
                        if(idTramite==61){
                            if(r.data.length<=1){
                              $('#fabricantes').append('<tr id='+r.data[0].id_fabricante+'><td>'+r.data[0].id_fabricante+'</td><td>'+r.data[0].nombre+'</td><td><h4><span class="label label-info">'+r.data[0].tipo+'</span></h4></td></tr>');

                              alertify.alert("Mensaje de sistema","Para realizar este tramite se necesitan al menos dos fabricantes en el producto.");
                                $denegado61=1;
                                if($denegado61==1){
                                  $('#guardar').hide();
                                }
                              
                            }
                            else{ 
                              $('#guardar').show();
                              for(w=0;w<r.data.length;w++){
                                if(r.data[w].tipo==='Principal'){
                                  $('#fabricantes').append('<tr id='+r.data[w].id_fabricante+'><td><input type="checkbox"  name="idFab[]" value="'+r.data[w].id_fabricante+'"></td><td>'+r.data[w].id_fabricante+'</td><td>'+r.data[w].nombre+'</td><td><h4><span class="label label-info">'+r.data[w].tipo+'</span></h4></td></tr>');
                                }
                                else{
                                  $('#fabricantes').append('<tr id='+r.data[w].id_fabricante+'><td><input type="checkbox"  name="idFab[]" value="'+r.data[w].id_fabricante+'"></td><td>'+r.data[w].id_fabricante+'</td><td>'+r.data[w].nombre+'</td><td><h4><span class="label label-warning">'+r.data[w].tipo+'</span></h4></td></tr>');
                                }
                              }
                          }
                      }

                      if(idTramite==35){

                        for(w=0;w<r.data.length;w++){
                            if(r.data[w].tipo==='Alterno'){
                              $('#fabricantes').append('<tr id='+r.data[w].id_fabricante+'><td><input type="checkbox"  name="idFab[]" value="'+r.data[w].id_fabricante+'"></td><td>'+r.data[w].id_fabricante+'</td><td>'+r.data[w].nombre+'</td><td><h4><span class="label label-info">'+r.data[w].tipo+'</span></h4></td></tr>');
                            }
                        }

                      }
                    
                    }
                    else if (r.status == 400){
                        alertify.alert("Mensaje de sistema - Error",r.message);
                    }else if(r.status == 401){
                        alertify.alert("Mensaje de sistema",r.message, function(){
                            window.location.href = r.redirect;
                        });
                    }else{//Unknown
                        alertify.alert("Mensaje de sistema - Error", "Oops!. Algo ha salido mal, contactar con el adminsitrador del sistema para poder continuar!");
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

    
}

function getTabsProds(idProducto,token,url1){
    var idPropietario;
    $.ajax({
                data:  'idProducto='+idProducto+'&_token='+token,
                url:   url1,
                type:  'post',
                success:  function (r){
                   $('#panel-formula').html(r);
                   
                },
            });
    //return idPropietario;
}