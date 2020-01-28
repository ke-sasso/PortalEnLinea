//hide tonos y fragancias
$('#tonos').hide();
$('#fragancias').hide();

$('#detalleCoempaque').hide();

$('#btnAddTono').click(function() {
    var index = $("#tonostb > tbody > tr").length;
    var i= index +1;
    $('#tonostb tbody').append('<tr><td>'+ i +'</td><td width="80%"><input  name="tonos[]" type="text" class="form-control" placeholder="Digite el tono para el producto" form="CosPreStep1y2"/></td><td><button class="btn btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
});

$("#tonostb").on('click', '.btnEliminar', function () {
    $(this).closest('tr').remove();
});


$('#btnAddFragancia').click(function() {
    var index = $("#fraganciastb > tbody > tr").length;
    var i= index +1;
    $('#fraganciastb tbody').append('<tr><td>'+ i +'</td><td width="80%"><input  name="fragancias[]" type="text" class="form-control" placeholder="Digite la fragacancia para el producto" form="CosPreStep1y2"/></td><td><button class="btn btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
});

$("#fraganciastb").on('click', '.btnEliminar', function () {
    $(this).closest('tr').remove();
});




$('#validarMandamiento').click(function(){
    var mandamiento = $('#mandamiento').val();
    var token = $('meta[name="_token"]').attr('content');

    $.ajax({
        data:  'numMandamiento='+mandamiento,
        url:   config.routes[0].valmandamiento,
        type:  'post',
        beforeSend: function() {
            $('body').modalmanager('loading');
        },
        success:  function (r){
            $('body').modalmanager('loading');
            if(r.status == 200){
                alertify.alert("Mensaje de sistema",r.message);
                config.flags[0].mandvalidado=true;
            }
            else if (r.status == 400){
                alertify.alert("Mensaje de sistema - Error",r.message);
            }else{//Unknown
                alertify.alert("Mensaje de sistema","El número de poder no ha podido ser validado, intentelo de nuevo!");
            }
        },
        error: function(data){
            var errors = $.parseJSON(data.message);
            console.error(errors);
        }
    });
});


$('input[type=radio][name=tipoTramite]').change(function(){

    var tipoTramite= $(this).val();
    //console.log(tipoTramite);

    $('#gnralCosOHig').empty();
    $('#sustancias tbody > tr').remove();
    $("#tonostb").find("tbody tr:not(:first)").remove();
    $("#tonostb").find("tbody tr input").val('');
    $("#fraganciastb").find("tbody tr:not(:first)").remove();
    $("#fraganciastb").find("tbody tr input").val('');
    $('#tonos').hide();
    $('#fragancias').hide();


    $.ajax({
        url: config.routes[0].getgnralprod+'?tipoTramite='+tipoTramite,
        type: 'get',
        success: function (r) {

            $('#gnralCosOHig').html(r);

            if (r.status == 400) {
                console.warn(r.message);
            }
        },
        error: function (data) {
            var errors = $.parseJSON(data.message);
            console.error(errors);
        }
    });

    if(tipoTramite==3 || tipoTramite==5){
        $('#importadoresDiv').show();
        $('#radioFabNacional').hide();
        $('#reconocimiento').empty();
        $.ajax({
            url:   config.routes[0].getreconocimiento,
            type:  'get',
            success:  function (r){
                $('#reconocimiento').append(r);
                //$('#fechaVen').datepicker({format: 'dd-mm-yyyy'});
            },
            error: function(data){
                var errors = $.parseJSON(data.message);
                console.error(errors);
            }
        });
    }
    else{
        $('#radioFabNacional').show();
        if(config.flags[0].origen=='E30'){
            $('#importadoresDiv').show();
        }
        else{
            $('#importadoresDiv').hide();
        }

        $("#fabricantes tbody > tr").remove();
        $('#reconocimiento').empty();
    }

    if($('#documentos > tbody > tr').length > 0){
        $('#documentos > tbody > tr').remove();
        dataStep6();
    }





});

function dataStep2(){
    var tipotramite=$('input[type=radio][name=tipoTramite]:checked').val();


    //carga de marcas
    var fecthMarcas = function () {

        $.ajax({
            url: config.routes[0].marcas,
            type: 'get',
            success: function (r) {
                if (r.status == 200) {
                    $('#marca').append(r.data);
                }
                else if (r.status == 400) {
                    //alertify.alert("Mensaje de sistema","Se presentan fallos con el sistema ");
                    console.warn(r.message);
                } else {//Unknown
                    //alertify.alert("Mensaje de sistema",r.message);
                    console.warn("No se han podido cargar las marcas de cosmeticos");
                }
            },
            error: function (data) {
                console.error(data.responseJSON.message);
                setTimeout(function () {
                    fecthMarcas();
                }, 4000)
            }
        });
    }

    fecthMarcas();


    if(tipotramite==2 || tipotramite==3) {
        var fecthAreas =function() {
            $.ajax({
                url: config.routes[0].areaaplicacion,
                type: 'get',
                success: function (r) {
                    if (r.status == 200) {
                        $('#areaApli').append(r.data);
                    }
                    else if (r.status == 400) {
                        console.warn(r.message);
                    } else {//Unknown
                        console.warn("No se han podido cargar las areas de aplicación");
                    }
                },
                error: function (data) {
                    console.error(data.responseJSON.message);
                    setTimeout(function () {
                        fecthAreas();
                    }, 3000)
                }
            });
        }
        fecthAreas();
    }
    else if (tipotramite==4 || tipotramite==5){

        var fecthClasificacion = function () {

            $.ajax({
                url: config.routes[0].clasificacioneshig,
                type: 'get',
                success: function (r) {
                    if (r.status == 200) {
                        $('#clasificacion').append(r.data);
                    }
                    else if (r.status == 400) {
                        console.warn(r.message);
                    } else {//Unknown
                        console.warn("No se han podido cargar las clasificaciones higiénicas");
                    }
                },
                error: function (data) {
                    console.error(data.responseJSON.message);
                    setTimeout(function () {
                        fecthClasificacion();
                    }, 2000)
                }
            });
        }

        fecthClasificacion();
    }
}

$('#gnralCosOHig').on('change', '#areaApli', function() {
    var idArea = $(this).val();
    $('#clasificacion').empty();
    var fecthClasificacion = function () {
        $.ajax({
            url: config.routes[0].clasificaciones + '?idArea=' + idArea,
            type: 'GET',
            success: function (r) {
                if (r.status == 200) {
                    $('#clasificacion').append(r.data);
                }
                else if (r.status == 400) {
                    console.warn(r.message);
                    setTimeout(function () {
                        fecthClasificacion();
                    }, 2000)
                } else {//Unknown
                    console.warn("No se han podido cargar las clasificaciones");
                }
            },
            error: function (data) {
                console.error(data.responseJSON.message);
                setTimeout(function () {
                    fecthClasificacion();
                }, 2000)
            }
        });
    }

    fecthClasificacion();
});


$('#gnralCosOHig').on('change', '#clasificacion', function() {
    //hide tonos y fragancias
    $('#tonos').hide();
    $('#fragancias').hide();
    var tipotramite=$('input[type=radio][name=tipoTramite]:checked').val();
    var idClasificacion = $("#clasificacion option:selected").val();
    var textClasificacion = $("#clasificacion option:selected").text();
    var tono = $("#clasificacion option:selected").attr('data-tono');
    var fragancia = $("#clasificacion option:selected").attr('data-fragancia');

    if(tono==1){
        //alertify.alert("Mensaje de sistema","Para la clasificación " + textClasificacion + "  es necesario que agregue uno o más tonos para el producto!" );
        $('#tonos').show();
        $('input[type=text][name=tonos]').prop("required",true);

    }

    if(fragancia==1){
        //alertify.alert("Mensaje de sistema","Para la clasificación " + textClasificacion + "  es necesario que agregue una o más fragancias para el producto!" );
        $('#fragancias').show()
        $("input .fragancias").attr("required", "true");
    }
    if(tipotramite==2 || tipotramite==3) {
        $('#formacos').empty();

        var fecthFormaCos = function () {

            $.ajax({
                url: config.routes[0].formas + '?idClasificacion=' + idClasificacion,
                type: 'GET',
                success: function (r) {
                    if (r.status == 200) {

                        $('#formacos').append(r.data);
                    }
                    else if (r.status == 400) {
                        console.warn(r.message);
                        setTimeout(function () {
                            fecthFormaCos();
                        }, 2000)
                    } else {//Unknown
                        console.warn("No se han podido cargar las clasificaciones");
                    }
                },
                error: function (data) {
                    console.error(data.responseJSON.message);
                    setTimeout(function () {
                        fecthFormaCos();
                    }, 2000)
                }
            });
        }
        if (!$('#formacos').val()) {
            fecthFormaCos();
        }
    }

});

$('#btnAddSustancia').click(function(event) {
    $('#sustancia').val('');
    var tipotramite=$('input[type=radio][name=tipoTramite]:checked').val();

    $('#dlgSustancias').modal('toggle');

    if(tipotramite==2 || tipotramite==3){
        $('#sustancia').select2({

            ajax: {
                url: config.routes[0].sustancias,
                dataType: 'json',
                delay: 250,
                dropdownAutoWidth : false,
                width: '100%',
                processResults: function (data) {

                    return {
                        results:  $.map(data.data, function (item) {
                            return {
                                text: item.denominacionINCI,
                                id: item.idDenominacion+','+'('+item.numeroCAS+')'
                            }
                        })
                    };
                },
                cache: true
            }
        });
    }
    else if(tipotramite==4 || tipotramite==5){
        $('#sustancia').select2({

            ajax: {
                url: config.routes[0].formulahig,
                dataType: 'json',
                delay: 250,
                dropdownAutoWidth : false,
                width: '100%',
                processResults: function (data) {

                    return {
                        results:  $.map(data.data, function (item) {
                            return {
                                text: item.nombreSustancia,
                                id: item.idDenominacion+','+'('+item.numeroCAS+')'
                            }
                        })
                    };
                },
                cache: true
            }
        });
    }


});

$('#addFormula').click(function (event) {

    var denominacion = $('#sustancia option:selected').val().split(',');

    var idDenominacion = denominacion[0];
    var numerocas = denominacion[1].replace('(','').replace(')','');

    var denominiacionInci=$('#sustancia option:selected').text();
    var porcentaje = $('#porcentaje').val();

    var nuevaFila ='<tr><input type="hidden" name="idDenomiacion[]" value="'+idDenominacion+'"/><td>'+numerocas+'</td><td>'+denominiacionInci+'</td><td><input type="hidden" name="porcentaje[]" value="'+porcentaje+'"/> '+porcentaje+'%'+'</td><td><button class="btn btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>';
    $('#sustancias tbody').append(nuevaFila);

    $('#dlgSustancias').modal('toggle');
});

$("#sustancias").on('click', '.btnEliminar', function () {
    $(this).closest('tr').remove();
});

$('input[type=radio][name=tipoTitular]').change(function(){


    var tipotitular=$(this).val();
    $('#searchbox-titular').selectize()[0].selectize.destroy();
    $('#searchbox-titular').selectize({

        valueField: 'ID_PROPIETARIO',
        inputClass: 'form-control selectize-input',
        labelField: 'NOMBRE_PROPIETARIO',
        searchField: ['NIT','NOMBRE_PROPIETARIO'],
        maxOptions: 10,
        preload: false,
        options: [],
        create: false,
        render: {
            option: function(item, escape) {
                return '<div>' +escape(item.NIT)+' ('+ escape(item.NOMBRE_PROPIETARIO) +')</div>';
            }
        },
        load: function(query, callback) {

            $.ajax({
                url: config.routes[0].findtitular,
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query,
                    tipoTitular: tipotitular,
                    idUnidad: 'COS'
                },
                error: function() {
                    callback();
                },
                success: function(res) {

                    if(res.status == 200){
                        callback(res.data);
                    }
                    else if (res.status == 404){
                        alertify.alert("Mensaje de sistema",res.message + " Contacte con la Unidad de Jurídico para solventar su incoveniente con el titular del producto.");
                        console.warn(res.message);
                    }else{//Unknown
                        alertify.alert("Mensaje de sistema",res.message);
                        console.warn("No se han podido cargar los titulares");
                    }

                }
            });
        }
    });
});

$('#searchbox-titular').on('change', function() {
    $('#bodyPropietario').empty();
    var idPropietario=this.value;

    var tipotitular = $('input[name=tipoTitular]:checked').val();
    var unidad = 'COS';

    $.ajax({
        url:    config.routes[0].gettitular+'?nitOrPp='+idPropietario+'&tipoTitular='+tipotitular+'&unidad='+unidad,
        type:  'GET',
        beforeSend: function() {
            $('body').modalmanager('loading');
        },
        success:  function (r){
            $('body').modalmanager('loading');
            $('#bodyPropietario').html(r);
        },
        error: function(data){
            // Error...
            $('body').modalmanager('loading');
            alertify.alert("Mensaje del Sistema","No se ha podido realizar al consulta del propietario, por favor contacte al administrador del sistema!");
            var errors = $.parseJSON(data.message);
            console.log(errors);

        }
    });

});

$('#validarPoderP').click(function(event) {
    $('#bodyProfesional').empty();
    var poder=$('#poderProf').val();
    var unidad = 'COS';

    $.ajax({
        url:    config.routes[0].getprofesional+'?poder='+poder+'&unidad='+unidad,
        type:  'GET',
        beforeSend: function() {
            $('body').modalmanager('loading');
        },
        success:  function (r){
            $('body').modalmanager('loading');
            if(r.status==200){
                config.flags[0].profesionalvalidado=true;
                $('#bodyProfesional').html(r.data);
            }
            else if (r.status==400 || r.status==404){
                alertify.alert("Mensaje del Sistema",r.message);
            }
        },
        error: function(data){

            $('body').modalmanager('loading');
            alertify.alert("Mensaje del Sistema","No se ha podido realizar la consulta del profesional responsable, por favor contacte al administrador del sistema!");
            console.error(data.message);

        }
    });
});


$('input[type=radio][name=origenFab]').change(function(){

    $("#fabricantes tbody tr").remove();
    var origen=$(this).val();
    var tipo=$(this).attr('tipo');
    $('#searchbox-fabricante').selectize()[0].selectize.destroy();
    $('#searchbox-fabricante').selectize({

        valueField: 'idEstablecimiento',
        inputClass: 'form-control selectize-input',
        labelField: 'nombreComercial',
        searchField: ['nombreComercial'],
        maxOptions: 10,
        preload: true,
        options: [],
        create: false,
        render: {
            option: function(item, escape) {
                return "<div class=\"option\" data-direcion='"+ escape(item.direccion) +"'  data-pais='"+ escape(item.pais) +"'>" + escape(item.nombreComercial) +"</div>";
            },
            item: function(data, escape) {
                return "<div class=\"item\" data-direcion='"+ escape(data['direccion']) +"' data-pais='"+ escape(data['pais']) +"' >" + escape(data['nombreComercial']) + "</div>";
            }
        },
        load: function(query, callback) {

            $.ajax({
                url: config.routes[0].findfabricante,
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query,
                    origenFab: origen,
                    many : true,
                    unidad: 'COS'

                },
                error: function() {
                    callback();
                },
                success: function(res) {

                    if(res.status == 200){
                        callback(res.data);
                    }
                    else if (res.status == 404){
                        alertify.alert("Mensaje de sistema",res.message + " Contacte con la Unidad de Jurídico para solventar su incoveniente con el fabricante del producto.");
                        console.warn(res.message);
                    }else{//Unknown
                        alertify.alert("Mensaje de sistema",res.message);
                        console.warn("No se han podido cargar los fabricantes");
                    }

                },

            });
        }
    });
    config.flags[0].origen=tipo;
    if(config.flags[0].origen=='EXT'){
        $('#importadoresDiv').show();
    }
    else{
        $('#importadores tbody tr').remove();
        $('#importadoresDiv').hide();
    }

});

$('#searchbox-fabricante').on('change', function() {

    var selectize = $('#searchbox-fabricante').get(0).selectize;
    var idEst = selectize.getValue();
    var nomEst=selectize.getItem(idEst).text();
    var option = selectize.options[ idEst ];
    var direccion = option['direccion'];
    var pais = option['pais'];
    var exist=false;

    if($('input[name^="fabricantes"]').length > 0){
        $('input[name^="fabricantes"]').each(function() {
                if($(this).val()==idEst) exist=true;
        });
        if(exist==false) $('#fabricantes > tbody:last-child').append('<tr><input type="hidden" name="fabricantes[]" value="'+idEst+'" form="CosPreStep4"><td>'+nomEst+'</td><td>'+direccion+'</td><td>'+pais+'</td><td><button class="btn btn-sm btn-danger btnEliminar" data-idest="'+idEst+'"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
    }
    else{
        $('#fabricantes > tbody:last-child').append('<tr><input type="hidden" name="fabricantes[]" value="'+idEst+'" form="CosPreStep4"><td>'+nomEst+'</td><td>'+direccion+'</td><td>'+pais+'</td><td><button class="btn btn-sm btn-danger btnEliminar" data-idest="'+idEst+'"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
    }
});

$("#fabricantes").on('click', '.btnEliminar', function () {
    console.log($(this).data('idest'));
    eliminarFabOrImp($(this).data('idest'),'fab');
    $(this).closest('tr').remove();
});


$('#searchbox-importador').selectize({

    valueField: 'idEstablecimiento',
    inputClass: 'form-control selectize-input',
    labelField: 'nombreComercial',
    searchField: ['nombreComercial'],
    maxOptions: 10,
    preload: function () {config.flags[0].loadimp},
    options: [],
    create: false,
    render: {
        option: function(item, escape) {
            return "<div class=\"option\" data-direcion='"+ escape(item.direccion) +"'  data-pais='"+ escape(item.pais) +"'>" + escape(item.nombreComercial) +"</div>";
        },
        item: function(data, escape) {
            return "<div class=\"item\" data-direcion='"+ escape(data['direccion']) +"' data-pais='"+ escape(data['pais']) +"' >" + escape(data['nombreComercial']) + "</div>";
        }
    },
    load: function(query, callback) {

        $.ajax({
            url: config.routes[0].findfabricante,
            type: 'GET',
            dataType: 'json',
            data: {
                q: query,
                origenFab: 'E29,E01',
                many : true,
                unidad: 'COS'
            },
            error: function() {
                callback();
            },
            success: function(res) {

                if(res.status == 200){
                    callback(res.data);
                }
                else if (res.status == 404){
                    alertify.alert("Mensaje de sistema","No se han encontrado los importadores!");
                    console.warn(res.message);
                }else{//Unknown
                    alertify.alert("Mensaje de sistema",res.message);
                    console.warn("No se han podido cargar los importadores");
                }

            },

        });
    }
});

$('#searchbox-importador').on('change', function() {

    var selectize = $('#searchbox-importador').get(0).selectize;
    var idEst = selectize.getValue();
    var nomEst=selectize.getItem(idEst).text();
    var option = selectize.options[ idEst ];
    var direccion = option['direccion'];
    var pais = option['pais'];

    var exist=false;

    if($('input[name^="importadores"]').length > 0){
        $('input[name^="importadores"]').each(function() {
            if($(this).val()==idEst) exist=true;
        });
        if(exist==false) $('#importadores > tbody:last-child').append('<tr><input type="hidden" name="importadores[]" value="'+idEst+'" form="CosPreStep4"><td>'+nomEst+'</td><td>'+direccion+'</td><td>'+pais+'</td><td><button class="btn btn-sm btn-danger btnEliminar" data-idest="'+idEst+'"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
    }
    else{
        $('#importadores > tbody:last-child').append('<tr><input type="hidden" name="importadores[]" value="'+idEst+'" form="CosPreStep4"><td>'+nomEst+'</td><td>'+direccion+'</td><td>'+pais+'</td><td><button class="btn btn-sm btn-danger btnEliminar" data-idest="'+idEst+'"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
    }


});


$("#importadores").on('click', '.btnEliminar', function () {
    console.log($(this).data('idest'));
    eliminarFabOrImp($(this).data('idest'),'imp');
    $(this).closest('tr').remove();
});


var $distribuidores = $('#searchbox-distribuidor').selectize({

    valueField: 'ID_PODER',
    inputClass: 'form-control selectize-input',
    labelField:  '(ID_PODER) - NOMBRE_COMERCIAL',
    searchField: ['ID_PODER','NOMBRE_COMERCIAL'],
    maxOptions: 100,
    preload:  function () {config.flags[0].loadidst},
    options: [],
    create: false,
    render: {
        option: function(item, escape) {
            return "<div class=\"option\" data-idest='"+ escape(item.ID_DISTRIBUIDOR_MAQUILA) +"'  data-nombrecomercial='"+ escape(item.NOMBRE_COMERCIAL) +"' data-direccion='"+ escape(item.DIRECCION) +"'>"+"("+ escape(item.ID_PODER)+" ) - " + escape(item.NOMBRE_COMERCIAL) +"</div>";
        },
        item: function(data, escape) {
            return "<div class=\"item\" data-idest='"+ escape(data['ID_DISTRIBUIDOR_MAQUILA']) +"' data-nombrecomercial='"+ escape(data['NOMBRE_COMERCIAL']) +"' data-direccion='"+ escape(data['DIRECCION']) +"' >"+"(" + escape(data['ID_PODER'])+" ) - " + escape(data['NOMBRE_COMERCIAL']) + "</div>";
        }
    },
    load: function(query, callback) {

        $.ajax({
            url: config.routes[0].finddistribuidor,
            type: 'GET',
            dataType: 'json',
            data: {
                q: query,
            },
            error: function() {
                callback();
            },
            success: function(res) {

                if(res.status == 200){
                    callback(res.data);
                }
                else if (res.status == 404){
                    alertify.alert("Mensaje de sistema",res.message);
                    console.warn(res.message);
                }else{//Unknown
                    alertify.alert("Mensaje de sistema",res.message);
                    console.warn("No se han podido cargar los distribuidores");
                }

            },

        });
    }
});

$('#searchbox-distribuidor').on('change', function() {

    var selectize = $('#searchbox-distribuidor').get(0).selectize;
    var idPoder = selectize.getValue();
    var nomEst=selectize.getItem(idPoder).text();
    var option = selectize.options[ idPoder ];

    var idEst = option['ID_DISTRIBUIDOR_MAQUILA'];
    var nombrecomercial = option['NOMBRE_COMERCIAL'];
    var direccion = option['DIRECCION'];

    var exist=false;

    if($('input[name^="dist"]').length > 0){
        $('input[name^="dist"]').each(function() {
            if($(this).val().split(',')[1]==idPoder) exist=true;
        });
        if(exist==false) $('#distribuidores > tbody:last-child').append('<tr><input type="hidden" name="dist[]" value="'+idEst+','+idPoder+'" form="CosPreStep5"> <td>'+idPoder+'</td><td>'+nombrecomercial+'</td><td>'+direccion+'</td><td><button class="btn btn-sm btn-danger btnEliminar" data-dist="'+idEst+','+idPoder+'"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
    }
    else{
        $('#distribuidores > tbody:last-child').append('<tr><input type="hidden" name="dist[]" value="'+idEst+','+idPoder+'" form="CosPreStep5"> <td>'+idPoder+'</td><td>'+nombrecomercial+'</td><td>'+direccion+'</td><td><button class="btn btn-sm btn-danger btnEliminar" data-dist="'+idEst+','+idPoder+'"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
    }
    /*
    else{
        alertify.alert("Mensaje de sistema","El distribuidor no se encuentra en estado activo, no puede ser seleccionado, contactese con la Unidad Jurídica para resolver su incoveniente!");
    }*/
});


$("#distribuidores").on('click', '.btnEliminar', function () {
    console.log($(this).data('dist'));
    eliminarDist($(this).data('dist'));
    $(this).closest('tr').remove();
});



function dataStep6(){

    var tipotramite=$("input[name='tipoTramite']:checked").val();

    if($('#documentos > tbody > tr').length <= 0) {
        if (tipotramite.length = !0) {

            var fecthViewDocs = function () {
                $.ajax({
                    url: config.routes[0].getrequisitos + '?tipoTramite=' + tipotramite,
                    type: 'GET',
                    success: function (r) {

                        $('#documentos > tbody:last-child').append(r);
                        $.each($('input[type=file]'), function () {
                            $('#' + $(this).attr('id')).fileinput({
                                theme: 'fa',
                                language: 'es',
                                allowedFileExtensions: ['pdf'],
                                showUpload : false
                            });

                        });

                        if (r.status == 400) {
                            console.warn(r.message);
                        }
                    },
                    error: function (data) {
                        console.error(data.responseJSON.message);
                        setTimeout(function () {
                            fecthViewDocs();
                        }, 4000)
                    }
                });
            }
            fecthViewDocs();
        }
        else {
            console.warn("Debe seleccionar el tipo de trámite, para que se muestren los requisitos!")
        }
    }

}


$('#btnAddPresentacion').click(function(event) {
    $('#emapaquesecundario').hide();
    $('#cep').hide();
    $('#dlgPresentacion').modal('toggle');



    if($('#emppri option').size()==0 && $('#empsec option').size()==0){
        var fetchEmpPri = function () {

            $.ajax({
                url: config.routes[0].envases,
                type: 'get',
                success: function (r) {
                    if (r.status == 200) {
                        $('#emppri').append(r.data);
                        $('#empsec').append(r.data);
                    }
                    else if (r.status == 400) {
                        console.warn(r.message);
                    } else {//Unknown
                        console.warn("No se han podido cargar los envases presentacion!");
                    }
                },
                error: function (data) {
                    console.error(data.responseJSON.message);
                    setTimeout(function () {
                        fetchEmpPri();
                    }, 2000)
                }
            });
        }
        fetchEmpPri();
    }

    if($('#matpri option').size()==0 && $('#matsec option').size()==0) {
        var fecthMatPri = function () {
            $.ajax({
                url: config.routes[0].materiales,
                type: 'get',
                success: function (r) {
                    if (r.status == 200) {
                        $('#matpri').append(r.data);
                        $('#matsec').append(r.data);

                    }
                    else if (r.status == 400) {
                        console.warn(r.message);
                    } else {//Unknown
                        console.warn("No se han podido cargar los materiales de presentación!");
                    }
                },
                error: function (data) {
                    console.error(data.responseJSON.message);
                    setTimeout(function () {
                        fecthMatPri();
                    }, 2000)
                }
            });
        }
        fecthMatPri();
    }

    if($('#unidadmedidapri option').size()==0 && $('#medida option').size()==0) {

       var fetchMedidas = function () {
           $.ajax({
               url: config.routes[0].unidmedidas,
               type: 'get',
               success: function (r) {
                   if (r.status == 200) {
                       $('#unidadmedidapri').append(r.data);
                       $('#medida').append(r.data);
                   }
                   else if (r.status == 400) {
                       console.warn(r.message);
                   } else {//Unknown
                       console.warn("No se han podido cargar las unidades de medidas!");
                   }
               },
               error: function (data) {
                   console.error(data.responseJSON.message);
                   setTimeout(function () {
                       fetchMedidas();
                   }, 2000)
               }
           });
       }

       fetchMedidas();

    }

});

function armarPresentacion(){

    $('#textoPres').css('background-color','#a9f0d3');
    $('#textoPres').css('border-color','#08f59f');
    $('#textoPres').css('border','15');
    p1=$('#empsec option:checked').text();
    p2=$('#matsec option:checked').text();
    p3=$('#contsec').val();
    p4=$('#emppri option:checked').text();
    p5=$('#matpri option:checked').text();
    p6=$('#contpri').val();
    p7=$('#unidadmedidapri option:checked').text();
    nombre=$('#nombrePres').val();
    if(nombre!=""){
        nombre=" ("+nombre+" )";
    }

    p9=$('#peso').val();
    if(p9!=""){
        p8="DE "+p9 + " "+$('#medida option:checked').text();
    } else {
        p8=" ";
    }

    unidad=$('#unidadmedidapri option:checked').val();

    if(unidad!=11){
        p8=" ";
    }

    var valor= $("input[name='checkempsec']:checked").val();
    if(valor==1){  //tiene empaque secundario
        texto=p1+" DE "+p2+" X "+p3+" "+p4+" DE "+p5+" X "+p6+" "+p7+" "+p8+nombre;

    } else if (unidad==11) {
        texto=p3+" "+p4+" DE "+p5+" X "+p6+" "+p7+" "+p8+nombre;

    } else {
        texto=p3+" "+p4+" DE "+p5+" X "+p6+" "+p7+nombre;
    }

    $('#textoPres').val(texto);
}

$('input[type=radio][name=checkempsec]').change(function(){

    var val = $(this).val();

    if(val==1){
        $('#empesectrue').attr('checked', true);
        $('#empesecfalse').removeAttr('checked');
        $('#emapaquesecundario').show();

    }
    else if(val==0){
        $('#empesecfalse').attr('checked', true);
        $('#empesectrue').removeAttr('checked');
        $('#emapaquesecundario').hide();
    }
});

//Si seleccionan 'unidad' en la presentación habilito campos de producto.
$('#unidadmedidapri').change(function (){
    var unidad=$(this).val();

    if(unidad==11){  // seleccionaron unidad
        $('#cep').show();  //CEP contenido empaque primario
    }else{
        $('#cep').hide();
    }

});


$('#btnAgregarPresent').click(function(event) {

    var data= buildRequestStringData($('#presentacionDiv')).toString();

    var datasjson = data.replace(/"/g,'&quot;');
    var values=JSON.parse(data);

    var nombrePresentacion;
    var index = $("#presentacion > tbody > tr").length;
    var i= index +1;

    //solo empaque primario
    if(values.checkempsec==0){
        if(values.contpri.length > 0 && values.emppri.length > 0 && values.matpri.length > 0 && values.unidadmedidapri.length > 0){
            nombrePresentacion=$('#textoPres').val();
            $('#presentacion > tbody').append('<tr><input type="hidden" name="presentaciones[]" value="'+datasjson+'" form="CosPreStep1y2"><td>'+i+'</td><td>'+nombrePresentacion+'</td><td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
            $('#presentacionForm').trigger("reset");
            $('#dlgPresentacion').modal('toggle');
        }
        else{
            console.error("Todos los campos para el empaque primario son requeridos");
        }
    }
    // empa sec y primario
    else if(values.checkempsec==1){
        //validacion empaque secundario
        if(values.contsec.length > 0 && values.empsec.length > 0 && values.matsec.length > 0){
            //validacion de empaque primario
            if(values.contpri.length > 0 && values.emppri.length > 0 && values.matpri.length > 0 && values.unidadmedidapri.length > 0){
                nombrePresentacion=$('#textoPres').val();
                $('#presentacion > tbody').append('<tr><input type="hidden" name="presentaciones[]" value="'+datasjson+'" form="CosPreStep1y2"><td>'+i+'</td><td>'+nombrePresentacion+'</td><td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
                $('#presentacionForm').trigger("reset");
                $('#dlgPresentacion').modal('toggle');
            }
            else{
                console.error("Todos los campos para el empaque primario son requeridos");
            }
        }
        else{
            console.error("Todos los campos para el empaque secundario son requeridos");
        }
    }

});

$("#presentacion").on('click', '.btnEliminar', function () {
    $(this).closest('tr').remove();
});

function buildRequestStringData(form) {
    var select = form.find('select'),
        input = form.find('input'),
        requestString = '{';
    for (var i = 0; i < select.length; i++) {
        requestString += '"' + $(select[i]).attr('name') + '": "' +$(select[i]).val() + '",';
    }
    if (select.length > 0) {
        requestString = requestString.substring(0, requestString.length - 1)+ ',';
    }
    for (var i = 0; i < input.length; i++) {
        if ($(input[i]).attr('type') !== 'checkbox') {
            if($(input[i]).attr('type') === 'radio'){
                console.log($(input[i]).is(':checked'));
                if($(input[i]).is(':checked')){
                    requestString += '"' + $(input[i]).attr('name') + '":"' + $(input[i]).val() + '",';
                }
            }
            else {
                requestString += '"' + $(input[i]).attr('name') + '":"' + $(input[i]).val() + '",';
            }
        } else {
            if ($(input[i]).attr('checked')) {
                requestString += '"' + $(input[i]).attr('name') +'":"' + $(input[i]).val() +'",';
            }
        }
    }
    if (input.length > 0) {
        requestString = requestString.substring(0, requestString.length - 1);
    }
    requestString += '}';
    return requestString;
}


$('input[type=radio][name=coempaque]').change(function() {
    var coempaque = $(this).val();

    if(coempaque==1) {
        $('#detalleCoempaque').show();
        $('#detCoempaque').show();
        $('#detcoempaque').attr('true');
    }
    else {
        $('#detalleCoempaque').hide();
        $('#detCoempaque').show();

        $('#detcoempaque').attr('false');
    }
});

function guardarSteps(div,i){
    console.log($(div));
    html2canvas($(div), {
        onpreloaded: function(){},
        onrendered: function (canvas) {
            var base64image = canvas.toDataURL("image/png");
            console.log(base64image);
            $("#img_val["+i+"]").val(base64image);
        }
    });


}

$distribuidores[0].selectize.disable();

$('input[type=radio][name=distTitu]').change(function(){
    var distTitular=$(this).val();
    var tipoTitular=$('input[type=radio][name=tipoTitular]:checked').val();
    console.log(tipoTitular);
    console.log(parseInt(tipoTitular));


    if (distTitular == 1) {
        if (parseInt(tipoTitular) == 3) {
            alertify.alert("Mensaje de sistema","Si el titular es extranjero, este no puede ser el mismo que el distribuidor, favor seleccione un distribuidor para continuar con la solicitud!");
        }
        else {
            config.flags[0].disttitular = true;
            $("#distribuidores tbody tr ").remove();
            $distribuidores[0].selectize.disable();
            //alertify.alert("Mensaje de sistema","Al seleccionar Si, ya no sera requerido que agrege uno o más distribuidores para el producto!");
        }
    }

    if(distTitular == 0 ){
        config.flags[0].disttitular = false;
        $distribuidores[0].selectize.enable();
        //alertify.alert("Mensaje de sistema","Es obligatorio que agrege uno o más distribuidores para el producto!");
    }


});






