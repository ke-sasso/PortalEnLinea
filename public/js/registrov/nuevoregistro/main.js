$('#empaquesecundario').hide();
$('#empaqueterciario').hide();

$('input[type=radio][name=checkempsec]').change(function () {

    var val = $('input[type=radio][name=checkempsec]:checked').val();
    var empaque1 = $('#empa1 option:selected').val();
    var material1 = $('#mat1 option:selected').val();

    if (val == 1) {
        if (empaque1 && material1 && $('#por1').val() != 0) {
            $('#empesectrue').attr('checked', true);
            $('#empesecfalse').removeAttr('checked');
            $('#empaquesecundario').show();
            $("#empa2").val('');
            $("#mat2").val('');
            $("#por2").val('');
            empaques("empa2");
            $('#mat2').val($('#empa1 option:selected').text());
        }
        else {
            alertify.alert("Mensaje del Sistema", "Debe llenar primero la parte del empaque primario!");
            $('#empesecfalse').attr('checked', true);
            $('#empesectrue').removeAttr('checked');
            $('#empesecfalse').click();
        }
    }
    else if (val == 0) {
        armarPresentacion();
        $('#empesecfalse').attr('checked', true);
        $('#empesectrue').removeAttr('checked');
        $('#empaquesecundario').hide();
        if ($('input[type=radio][name=checkempter]:checked').val() == 1) {
            $('#empeterfalse').attr('checked', true);
            $('#empetertrue').removeAttr('checked');
            $('#empaqueterciario').hide();
            $('#empeterfalse').click();
        }

    }
});

$('input[type=radio][name=checkempter]').change(function () {
    armarPresentacion();
    var val = $(this).val();
    var empaque2 = $('#empa2 option:selected').val();

    if (val == 1) {
        if (empaque2 && $('#por2').val() != 0) {
            $('#empetertrue').attr('checked', true);
            $('#empeterfalse').removeAttr('checked');
            $('#empaqueterciario').show();
            $("#empa3").val('');
            $("#mat3").val('');
            $("#por3").val('');
            empaques("empa3");
            $('#mat3').val($('#empa2 option:selected').text());
        }
        else {
            alertify.alert("Mensaje del Sistema", "Debe llenar primero la parte del empaque secundario!");
            $('#empeterfalse').attr('checked', true);
            $('#empetertrue').removeAttr('checked');
            $('#empeterfalse').click();
        }

    }
    else if (val == 0) {
        armarPresentacion();
        $('#empeterfalse').attr('checked', true);
        $('#empetertrue').removeAttr('checked');
        $('#empaqueterciario').hide();
    }
});
$('input[type=radio][name=checkespecial]').change(function () {
    var value = $('input[type=radio][name=checkespecial]:checked').val();
    var div1 = document.getElementById('pre-especial');
    if(value==1){
        div1.style.display = 'block';
    }else{
        div1.style.display = 'none';

    }
});

$('#btnAddPresentacion').click(function (event) {
    $("#empa1").val('');
    $("#por1").val('');
    $("#mat1").val('');
    $("#empa2").val('');
    $("#por2").val('');
    $("#mat2").val('');
    $("#empa3").val('');
    $("#por3").val('');
    $("#mat3").val('');
    $("#material").val('');
    $("#color").val('');
    $("#accesorios").val('');
    $("#tipoP").val(1);
    $("#presentacionespecial").val('');
    $('#empesecfalse').click();
    $('#empeterfalse').click();
    $('#textPres').val('');
    $('#nombreMaterial').val('');
    $('#nombreColor').val('');

    empaques("empa1");
    $('#mat1').select2({
        ajax: {
            url: config.routes[0].contenidos,
            dataType: 'json',
            delay: 250,
            dropdownAutoWidth: false,
            width: '100%',
            processResults: function (data) {
                if (data.status == 200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.nomContenido,
                                id: item.idContenido
                            }
                        })
                    };
                }
                else {
                    console.error(data.message);
                }
            },
        }
    });
    $('#color').select2({
        ajax: {
            url: config.routes[0].colores,
            dataType: 'json',
            delay: 250,
            dropdownAutoWidth: false,
            width: '100%',
            processResults: function (data) {
                if (data.status == 200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.NOMBRE_COLOR,
                                id: item.ID_COLOR
                            }
                        })
                    };
                }
                else {
                    console.error(data.message);
                }
            },
        }
    });
    $('#material').select2({
        ajax: {
            url: config.routes[0].materiales,
            dataType: 'json',
            delay: 250,
            dropdownAutoWidth: false,
            width: '100%',
            processResults: function (data) {
                if (data.status == 200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: myTrim(item.NOMBRE_MATERIAL),
                                id: item.ID_MATERIAL
                            }
                        })
                    };
                }
                else {
                    console.error(data.message);
                }
            },
        }
    });

    $('#dlgAddPresent').modal('toggle');
});

$('#btnAddPresentacionDt').click(function (event) {
    tipo = $('#tipoP option:selected').text();
    material = $('#material option:selected').text();
    accesorios = $('#accesorios').val();
    nomcolor = $('#color option:selected').text();

    $('#nombreMaterial').val(material);
    $('#nombreColor').val(nomcolor);
    var data = buildRequestStringData($('#presentacionRvDiv')).toString();
    var datasjson = data.replace(/"/g, '&quot;');
    var values = JSON.parse(data);
    // console.log(data);
    var index = $("#presentacion > tbody > tr").length;
    var i = index + 1;
    if (values.checkempsec == 0 && values.checkempter == 0) {
        if (values.empa1 != 'null' && values.por1.length > 0 && values.mat1 != 'null' && values.tipoP.length > 0 && values.material != 'null' && values.color != 'null') {
            $('#presentacionrv > tbody').append('<tr><input form="RegistroStep1y2" type="hidden" name="presentaciones[]" value="' + datasjson + '"><td>' + i + '</td><td>' + values.textPres + '</td><td>' + tipo + '</td><td>' + material + '</td><td>' + accesorios + '</td><td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
            $('#dlgAddPresent').modal('toggle');
        }
        else {
            console.error("Todos los campos para el empaque primario son requeridos");
        }
    }
    else if (values.checkempsec == 1 && values.checkempter == 0) {
        if (values.empa1 != 'null' && values.por1.length > 0 && values.mat1 != 'null' > 0 && values.tipoP.length > 0 && values.material != 'null' && values.color != 'null' && values.empa2.length > 0 && values.por2.length > 0) {
            $('#presentacionrv > tbody').append('<tr><input form="RegistroStep1y2" type="hidden" name="presentaciones[]" value="' + datasjson + '"><td>' + i + '</td><td>' + values.textPres + '</td><td>' + tipo + '</td><td>' + material + '</td><td>' + accesorios + '</td><td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
            $('#dlgAddPresent').modal('toggle');
        }
        else {
            console.error("Todos los campos para el empaque primario y secundario son requeridos");
        }
    }
    else if (values.checkempsec == 1 && values.checkempter == 1) {
        if (values.empa1 != 'null' && values.por1.length > 0 && values.mat1 != 'null' > 0 && values.tipoP.length > 0 && values.material != 'null' && values.color != 'null' && values.empa2.length > 0 && values.por2.length > 0 && values.empa3.length > 0 && values.por3.length > 0) {
            $('#presentacionrv > tbody').append('<tr><input form="RegistroStep1y2" type="hidden" name="presentaciones[]" value="' + datasjson + '"><td>' + i + '</td><td>' + values.textPres + '</td><td>' + tipo + '</td><td>' + material + '</td><td>' + accesorios + '</td><td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
            $('#dlgAddPresent').modal('toggle');
        }
        else {
            console.error("Todos los campos para el empaque primario, secundario y terciario son requeridos");
        }
    }


});

$("#presentacionrv").on('click', '.btnEliminar', function () {
    $(this).closest('tr').remove();
});


$('#btnAddPrincipio').click(function (event) {

    $("#materiaPrima").val('');
    $('#materiaPrima').select2({
        ajax: {
            url: config.routes[0].materiasprimas,
            dataType: 'json',
            delay: 250,
            dropdownAutoWidth: false,
            width: '100%',
            processResults: function (data) {
                if (data.status == 200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.materiaPrima,
                                id: item.idMatPrima
                            }
                        })
                    };
                }
                else {
                    console.error(data.message);
                }
            },
        }
    });
    $("#unidadmedida").val('');
    $('#unidadmedida').select2({
        ajax: {
            url: config.routes[0].unidadesmedida,
            dataType: 'json',
            delay: 250,
            dropdownAutoWidth: false,
            width: '100%',
            processResults: function (data) {
                if (data.status == 200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.unidadMedida,
                                id: item.idUnidadMed
                            }
                        })
                    };
                }
                else {
                    console.error(data.message);
                }
            },
        }
    });
    $('#concentracion').val(0);
    $('#dlgAddPinicipio').modal('toggle');
});

$('#btnAddPrincipioA').click(function (event) {

    matprimaid = $('#materiaPrima').val();
    matprimatext = $("#materiaPrima option:selected").text();
    concentracion = $('#concentracion').val();
    unimedidaid = $('#unidadmedida').val();
    unidmedidatext = $('#unidadmedida option:selected').text();
    idUnidad= $('#unidadmedida').val();
    if (matprimaid && concentracion && unimedidaid) {
        nuevaFila = '<tr><input form="RegistroStep1y2" type="hidden" name="idMateriasP[]" value="' + matprimaid + '"/><input form="RegistroStep1y2" type="hidden" name="nombreUnidad[]" value="' + unidmedidatext + '"/> <input form="RegistroStep1y2" type="hidden" name="nombreMateria[]" value="' + matprimatext + '"/> <td>' + matprimatext + '</td><td><input form="RegistroStep1y2" type="hidden" name="concentracion[]" value="' + concentracion + '">' + concentracion + '</td><td><input form="RegistroStep1y2" type="hidden" name="idUnidadesM[]" value="' + idUnidad + '"/> ' + unidmedidatext + '</td><td><button class="btn btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>';
        $('#principiosactivos tbody').append(nuevaFila);
        $('#dlgAddPinicipio').modal('toggle');
    } else {
        console.warn("Tiene que seleccionar la materia prima, y la unidad de medida");
    }

});

$("#principiosactivos").on('click', '.btnEliminar', function () {
    $(this).closest('tr').remove();
});


$('#btnAddVidaUtil').click(function (event) {

    $("#empaquevida").val('');
    $('#empaquevida').select2({
          ajax: {
            url: config.routes[0].empaques,
            dataType: 'json',
            delay: 250,
            dropdownAutoWidth : false,
            width: '100%',
            processResults: function (data) {
                if(data.status==200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.nomEmp,
                                id: item.idEmp
                            }
                        })
                    };
                }
                else{
                    console.error(data.message);
                }
            },
        }
    });
    $("#materialvida").val('');
    $('#materialvida').select2({
        ajax: {
            url: config.routes[0].materiales,
            dataType: 'json',
            delay: 250,
            dropdownAutoWidth: false,
            width: '100%',
            processResults: function (data) {
                if (data.status == 200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: myTrim(item.NOMBRE_MATERIAL),
                                id: item.ID_MATERIAL
                            }
                        })
                    };
                }
                else {
                    console.error(data.message);
                }
            },
        }
    });
    $("#colorvida").val('');
    $('#colorvida').select2({
         ajax: {
            url: config.routes[0].colores,
            dataType: 'json',
            delay: 250,
            dropdownAutoWidth : false,
            width: '100%',
            processResults: function (data) {
                if(data.status==200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.NOMBRE_COLOR ,
                                id: item.ID_COLOR
                            }
                        })
                    };
                }
                else{
                    console.error(data.message);
                }
            },
        }
    });
    $('#utilvida').val(0);
    $('#observacionvida').val('');
    $('#dlgAddVidaUtil').modal('toggle');
    $("html, body").animate({ scrollTop: 0 }, 300);
});
$('#btnAddVidaUtilTable').click(function (event) {

    vidaempaqueid = $('#empaquevida').val();
    vidaempaquetext = $("#empaquevida option:selected").text();
    utilvida = $('#utilvida').val();
    vidamaterialaid = $('#materialvida').val();
    vidamterialtext = $('#materialvida option:selected').text();
    observacionutil = $('#observacionvida').val();
    idperiodo = $('#idperiodvida').val();
    idperiodotext = $('#idperiodvida option:selected').text();
    idcolorvida = $('#colorvida').val();
    nomcolorvida = $('#colorvida option:selected').text();

    if (vidaempaqueid && vidamaterialaid && utilvida && idcolorvida) {
        nuevaFila = '<tr><input form="RegistroStep1y2" type="hidden" name="idEmpaquevida[]" value="' + vidaempaqueid + '"/><input form="RegistroStep1y2" type="hidden" name="nombreEmpaquevida[]" value="' + vidaempaquetext + '"/>  <td>' + vidaempaquetext + '</td><td><input form="RegistroStep1y2" type="hidden" name="materialVidaId[]" value="' + vidamaterialaid + '"><input form="RegistroStep1y2" type="hidden" name="materialnombreVidaId[]" value="' + vidamterialtext + '">' + vidamterialtext + '</td><td><input form="RegistroStep1y2" type="hidden" name="utilvida[]" value="' + utilvida + '"/> <input form="RegistroStep1y2" type="hidden" name="idperiodo[]" value="'+idperiodo+'"/> ' + utilvida + ' ('+idperiodotext+')</td><td><input name="idcolorvida[]" type="hidden" form="RegistroStep1y2"  value="'+idcolorvida+'"/><input name="nombrecolorvida[]" type="hidden" form="RegistroStep1y2"  value="'+nomcolorvida+'"/>'+nomcolorvida+'</td><td><input form="RegistroStep1y2" type="hidden" name="observacionvida[]" value="' + observacionutil + '"/> ' + observacionutil + '</td><td><button class="btn btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>';
        $('#vidautilempaque tbody').append(nuevaFila);
        $('#dlgAddVidaUtil').modal('toggle');
    } else {
        console.warn("Tiene que seleccionar un empaque, materia, color y vida util");
    }

});

$("#vidautilempaque").on('click', '.btnEliminar', function () {
    $(this).closest('tr').remove();
});

$('#speak').click(function () {
    var msg = new SpeechSynthesisUtterance();
    var voices = window.speechSynthesis.getVoices();
    console.log(voices);
    msg.voice = voices[0];
    msg.rate = 10 / 10;
    msg.pitch = 0;
    msg.text = 'Ingrese su mandamiento de pago';

    msg.onend = function (e) {
        console.log('Finished in ' + event.elapsedTime + ' seconds.');
    };
    speechSynthesis.speak(msg);
});


$('input[type=radio][name=tipoTitular]').change(function () {

    var tipotitular = $(this).val();
    $('#searchbox-titular').selectize()[0].selectize.destroy();
    $('#searchbox-titular').selectize({

        valueField: 'ID_PROPIETARIO',
        inputClass: 'form-control selectize-input',
        labelField: 'NOMBRE_PROPIETARIO',
        searchField: ['NIT', 'NOMBRE_PROPIETARIO'],
        maxOptions: 10,
        preload: false,
        options: [],
        create: false,
        render: {
            option: function (item, escape) {
                return '<div>' + escape(item.NIT) + ' (' + escape(item.NOMBRE_PROPIETARIO) + ')</div>';
            }
        },
        load: function (query, callback) {

            $.ajax({
                url: config.routes[0].findtitular,
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query,
                    tipoTitular: tipotitular,
                    idUnidad: 'URV'
                },
                error: function () {
                    callback();
                },
                success: function (res) {

                    if (res.status == 200) {
                        callback(res.data);
                    }
                    else if (res.status == 404) {
                        alertify.alert("Mensaje de sistema", res.message + " Contacte con la Unidad de Jurídico para solventar su incoveniente con el titular del producto.");
                        console.warn(res.message);
                    } else {//Unknown
                        alertify.alert("Mensaje de sistema", res.message);
                        console.warn("No se han podido cargar los titulares");
                    }

                }
            });
        }
    });
});

$('#searchbox-titular').on('change', function () {
    $('#bodyTitular').empty();
    var idPropietario = this.value;

    var tipotitular = $('input[name=tipoTitular]:checked').val();
    var unidad = 'URV';
    var form = '1';
    $.ajax({
        url: config.routes[0].gettitular + '?nitOrPp=' + idPropietario + '&tipoTitular=' + tipotitular + '&unidad=' + unidad + '&form=' + form,
        type: 'GET',
        beforeSend: function () {
            $('body').modalmanager('loading');
        },
        success: function (r) {
            $('body').modalmanager('loading');
            $('#bodyTitular').html(r);
            config.flags[0].titular = true;
        },
        error: function (data) {
            // Error...
            $('body').modalmanager('loading');
            alertify.alert("Mensaje del Sistema", "No se ha podido realizar la consulta del propietario, por favor contacte al administrador del sistema!");
            var errors = $.parseJSON(data.message);
            console.log(errors);

        }
    });

});


$('#validarPoderP').click(function (event) {
    $('#bodyProfesional').empty();
    var poder = $('#poderProf').val();
    var unidad = 'URV';

    $.ajax({
        url: config.routes[0].getprofesional + '?poder=' + poder + '&unidad=' + unidad,
        type: 'GET',
        beforeSend: function () {
            $('body').modalmanager('loading');
        },
        success: function (r) {
            $('body').modalmanager('loading');
            config.flags[0].profesionalvalidado = true;
            $('#bodyProfesional').html(r);
        },
        error: function (data) {
            // Error...
            $('body').modalmanager('loading');
            alertify.alert("Mensaje del Sistema", "No se han encontrado resultado de profesional responsable!");
            var errors = $.parseJSON(data.message);
            console.log(errors);

        }
    });
});

$('#validarPresentanteL').click(function (event) {
    $('#bodyRepresentanteLegal').empty();
    var poder1 = $('#poderRL').val();
    $.ajax({
        url: config.routes[0].getrepresentantelegal + '?poder=' + poder1,
        type: 'GET',
        beforeSend: function () {
            $('body').modalmanager('loading');
        },
        success: function (r) {
            $('body').modalmanager('loading');
            config.flags[0].representantelegal = true;
            $('#bodyRepresentanteLegal').html(r);
        },
        error: function (data) {
            // Error...
            $('body').modalmanager('loading');
            alertify.alert("Mensaje del Sistema", "No se han encontrado resultados de representante legal!");
            var errors = $.parseJSON(data.message);
            console.log(errors);

        }
    });
});

$('#validarApoderado').click(function (event) {
    $('#bodyApoderados').empty();
    var poder1 = $('#poderApo').val();
    $.ajax({
        url: config.routes[0].getapoderados + '?poder=' + poder1,
        type: 'GET',
        beforeSend: function () {
            $('body').modalmanager('loading');
        },
        success: function (r) {
            $('body').modalmanager('loading');
            config.flags[0].apoderado = true;
            $('#bodyApoderados').html(r);
        },
        error: function (data) {
            // Error...
            $('body').modalmanager('loading');
            alertify.alert("Mensaje del Sistema", "No se han encontrado resultados de apoderados!");
            var errors = $.parseJSON(data.message);
            console.log(errors);

        }
    });
});

function empaques(idElement) {
    $('#' + idElement).select2({
        ajax: {
            url: config.routes[0].empaques,
            dataType: 'json',
            delay: 250,
            dropdownAutoWidth: false,
            width: '100%',
            processResults: function (data) {
                if (data.status == 200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.nomEmp,
                                id: item.idEmp
                            }
                        })
                    };
                }
                else {
                    console.error(data.message);
                }
            },
        }
    });
}

$('input[type=radio][name=origenFabAlterno]').change(function () {

    var origen = $(this).val();
    var unidad = 'URV';
    if (origen != 'E30') {
        config.flags[0].manyest = true;
    }
    $('#searchbox-fabricante2').selectize()[0].selectize.destroy();
    $('#searchbox-fabricante2').selectize({

        valueField: 'idEstablecimiento',
        inputClass: 'form-control selectize-input',
        labelField: 'nombreComercial',
        searchField: ['nombreComercial'],
        maxOptions: 10,
        preload: true,
        options: [],
        create: false,
        render: {
            option: function (item, escape) {
                return "<div class=\"option\" data-direcion='" + escape(item.direccion) + "'  data-pais='" + escape(item.pais) + "'>" + escape(item.nombreComercial) + "</div>";
            },
            item: function (data, escape) {
                return "<div class=\"item\" data-direcion='" + escape(data['direccion']) + "' data-pais='" + escape(data['pais']) + "' >" + escape(data['nombreComercial']) + "</div>";
            }
        },
        load: function (query, callback) {

            $.ajax({
                url: config.routes[0].findfabricante,
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query,
                    origenFab: origen,
                    many: config.flags[0].manyest,
                    unidad: unidad
                },
                error: function () {
                    callback();
                },
                success: function (res) {

                    if (res.status == 200) {
                        callback(res.data);
                    }
                    else if (res.status == 404) {
                        alertify.alert("Mensaje de sistema", res.message + " Contacte con la Unidad de Jurídico para solventar su incoveniente con el fabricante del producto.");
                        console.warn(res.message);
                    } else {//Unknown
                        alertify.alert("Mensaje de sistema", res.message);
                        console.warn("No se han podido cargar los fabricantes");
                    }

                },

            });
        }
    });

});

$('#searchbox-fabricante2').on('change', function () {

    var selectize = $('#searchbox-fabricante2').get(0).selectize;
    var idEst = selectize.getValue();
    var nomEst = selectize.getItem(idEst).text();
    var option = selectize.options[idEst];
    var direccion = option['direccion'];
    var pais = option['pais'];

    $('#dt-fabricantesAlternos > tbody:last-child').append('<tr><input type="hidden" form="RegistroPreStep4" name="fabricantesAlternos[]" value="' + idEst + '"><td>' + nomEst + '</td><td>' + direccion + '</td><td>' + pais + '</td><td><input size="45" form="RegistroPreStep4" type="text" name="noMaquilaFabAlterno[]"/></td><td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
});

$("#dt-fabricantesAlternos").on('click', '.btnEliminar', function () {
    $(this).closest('tr').remove();
});


$('input[type=radio][name=origenFabAcondicionador]').change(function () {
    var origen = $(this).val();
    var unidad = 'URV';
    if (origen != 'E30') {
        config.flags[0].manyest = true;
    }
    $('#searchbox-fabricante3').selectize()[0].selectize.destroy();

    $('#searchbox-fabricante3').selectize({

        valueField: 'idEstablecimiento',
        inputClass: 'form-control selectize-input',
        labelField: 'nombreComercial',
        searchField: ['nombreComercial'],
        maxOptions: 10,
        preload: true,
        options: [],
        create: false,
        render: {
            option: function (item, escape) {
                return "<div class=\"option\" data-direcion='" + escape(item.direccion) + "'  data-pais='" + escape(item.pais) + "'>" + escape(item.nombreComercial) + "</div>";
            },
            item: function (data, escape) {
                return "<div class=\"item\" data-direcion='" + escape(data['direccion']) + "' data-pais='" + escape(data['pais']) + "' >" + escape(data['nombreComercial']) + "</div>";
            }
        },
        load: function (query, callback) {

            $.ajax({
                url: config.routes[0].findfabricante,
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query,
                    origenFab: origen,
                    many: config.flags[0].manyest,
                    unidad: unidad
                },
                error: function () {
                    callback();
                },
                success: function (res) {

                    if (res.status == 200) {
                        callback(res.data);
                    }
                    else if (res.status == 404) {
                        alertify.alert("Mensaje de sistema", res.message + " Contacte con la Unidad de Jurídico para solventar su incoveniente con el fabricante del producto.");
                        console.warn(res.message);
                    } else {//Unknown
                        alertify.alert("Mensaje de sistema", res.message);
                        console.warn("No se han podido cargar los fabricantes");
                    }

                },

            });
        }
    });
});


$('#searchbox-fabricante3').on('change', function () {

    var selectize = $('#searchbox-fabricante3').get(0).selectize;
    var idEst = selectize.getValue();
    var nomEst = selectize.getItem(idEst).text();
    var option = selectize.options[idEst];
    var direccion = option['direccion'];
    var pais = option['pais'];
    var categoria = $('input[type=radio][name=categoriaLabAcon]:checked').val();
    var nameCategoria='';
    if(categoria==1){
        nameCategoria='PRIMARIO';
    }else{
        nameCategoria='SECUNDARIO';
    }
    $('#dt-fabricantesAcondicionador > tbody:last-child').append('<tr><input type="hidden" form="RegistroPreStep4" id="laboratorioAcondicionador" name="laboratorioAcondicionador[]" value="' + idEst + '"><input type="hidden" form="RegistroPreStep4" id="tipoLabAcondicionador" name="tipoLabAcondicionador[]" value="' + categoria + '"><td>' + nomEst + '</td><td>' + direccion + '</td><td>' + pais + '</td><td>'+nameCategoria+'</td><td><input size="45" form="RegistroPreStep4" type="text" autocomplete="off" name="noMaquilaFabAcon[]"/></td><td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
});


$('#searchbox-fabricante4').on('change', function () {

    var selectize = $('#searchbox-fabricante4').get(0).selectize;
    var idEst = selectize.getValue();
    var nomEst = selectize.getItem(idEst).text();
    var option = selectize.options[idEst];
    var direccion = option['direccion'];
    var pais = option['pais'];

    $('#dt-fabricantesRelacionados > tbody:last-child').append('<tr><input type="hidden" form="RegistroPreStep4" id="laboratorioRelacionado" name="laboratorioRelacionado[]" value="' + idEst + '"><td>' + nomEst + '</td><td>' + direccion + '</td><td>' + pais + '</td><td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
});


$("#dt-fabricantesRelacionados").on('click', '.btnEliminar', function () {
    $(this).closest('tr').remove();
});



$('#searchbox-fabricante4').selectize({

        valueField: 'idEstablecimiento',
        inputClass: 'form-control selectize-input',
        labelField: 'nombreComercial',
        searchField: ['nombreComercial'],
        maxOptions: 10,
        preload: true,
        options: [],
        create: false,
        render: {
            option: function (item, escape) {
                return "<div class=\"option\" data-direcion='" + escape(item.direccion) + "'  data-pais='" + escape(item.pais) + "'>" + escape(item.nombreComercial) + "</div>";
            },
            item: function (data, escape) {
                return "<div class=\"item\" data-direcion='" + escape(data['direccion']) + "' data-pais='" + escape(data['pais']) + "' >" + escape(data['nombreComercial']) + "</div>";
            }
        },
        load: function (query, callback) {

            $.ajax({
                url: config.routes[0].findLaboratorioRelac,
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query,

                },
                error: function () {
                    callback();
                },
                success: function (res) {

                    if (res.status == 200) {
                        callback(res.data);
                    }
                    else if (res.status == 404) {
                        alertify.alert("Mensaje de sistema", res.message + " Contacte con la Unidad de Jurídico para solventar su incoveniente con el fabricante del producto.");
                        console.warn(res.message);
                    } else {//Unknown
                        alertify.alert("Mensaje de sistema", res.message);
                        console.warn("No se han podido cargar los laboratorios relacionados");
                    }

                },

            });
        }
    });



$("#dt-fabricantesAcondicionador").on('click', '.btnEliminar', function () {
    $(this).closest('tr').remove();
});


$('input[type=radio][name=origenFab]').change(function () {
    var origen = $(this).val();
    var unidad = 'URV';
    if (origen != 'E30') {
        config.flags[0].manyest = true;
    }
    $('#searchbox-fabricante1').selectize()[0].selectize.destroy();
    $('#searchbox-fabricante1').selectize({

        valueField: 'idEstablecimiento',
        inputClass: 'form-control selectize-input',
        labelField: 'nombreComercial',
        searchField: ['nombreComercial'],
        maxOptions: 10,
        preload: true,
        options: [],
        create: false,
        render: {
            option: function (item, escape) {
                return "<div class=\"option\" data-direcion='" + escape(item.direccion) + "'  data-pais='" + escape(item.pais) + "'>" + escape(item.nombreComercial) + "</div>";
            },
            item: function (data, escape) {
                return "<div class=\"item\" data-direcion='" + escape(data['direccion']) + "' data-pais='" + escape(data['pais']) + "' >" + escape(data['nombreComercial']) + "</div>";
            }
        },
        load: function (query, callback) {

            $.ajax({
                url: config.routes[0].findfabricante,
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query,
                    origenFab: origen,
                    many: config.flags[0].manyest,
                    unidad: unidad
                },
                error: function () {
                    callback();
                },
                success: function (res) {

                    if (res.status == 200) {
                        callback(res.data);
                    }
                    else if (res.status == 404) {
                        alertify.alert("Mensaje de sistema", res.message + " Contacte con la Unidad de Jurídico para solventar su incoveniente con el fabricante del producto.");
                        console.warn(res.message);
                    } else {//Unknown
                        alertify.alert("Mensaje de sistema", res.message);
                        console.warn("No se han podido cargar los fabricantes");
                    }

                },

            });
        }
    });

});
$('#searchbox-fabricante1').on('change', function () {

    var selectize = $('#searchbox-fabricante1').get(0).selectize;
    var idEst = selectize.getValue();
    var nomEst = selectize.getItem(idEst).text();
    var option = selectize.options[idEst];
    var direccion = option['direccion'];
    var pais = option['pais'];
    $('#idFabricantePri').val(idEst);
    $('#nomProp').val(nomEst);
    $('#direccProp').val(direccion);
    $('#paisFabP').val(pais);
    config.flags[0].labprincipal = true;
});


$('#searchbox-distribuidor').selectize({

    valueField: 'idEstablecimiento',
    inputClass: 'form-control selectize-input',
    labelField: '(idEstablecimiento) - nombreComercial',
    searchField: ['idEstablecimiento', 'nombreComercial'],
    maxOptions: 100,
    preload: true,
    options: [],
    create: false,
    render: {
        option: function (item, escape) {
            return "<div class=\"option\" data-idest='" + escape(item.idEstablecimiento) + "'  data-nombrecomercial='" + escape(item.nombreComercial) + "' data-direccion='" + escape(item.direccion) + "' data-vigenteHasta='" + escape(item.vigenteHasta) + "' data-estado='" + escape(item.nombreEstado) + "' >" + "(" + escape(item.idEstablecimiento) + " ) - " + escape(item.nombreComercial) + "</div>";
        },
        item: function (data, escape) {
            return "<div class=\"item\" data-idest='" + escape(data['idEstablecimiento']) + "' data-nombrecomercial='" + escape(data['nombreComercial']) + "' data-direccion='" + escape(data['direccion']) + "' data-vigenteHasta='" + escape(data['vigenteHasta']) + "' data-estado='" + escape(data['nombreEstado']) + "' >" + "(" + escape(data['idEstablecimiento']) + " ) - " + escape(data['nombreComercial']) + "</div>";
        }
    },
    load: function (query, callback) {

        $.ajax({
            url: config.routes[0].finddistribuidor,
            type: 'GET',
            dataType: 'json',
            data: {
                q: query,
            },
            error: function () {
                callback();
            },
            success: function (res) {

                if (res.status == 200) {
                    callback(res.data);
                }
                else if (res.status == 404) {
                    alertify.alert("Mensaje de sistema", res.message + " Contacte con la Unidad de Jurídico para solventar su incoveniente con el fabricante del producto.");
                    console.warn(res.message);
                } else {//Unknown
                    alertify.alert("Mensaje de sistema", res.message);
                    console.warn("No se han podido cargar los fabricantes");
                }

            },

        });
    }
});

$('#searchbox-distribuidor').on('change', function () {

    var selectize = $('#searchbox-distribuidor').get(0).selectize;
    var idEsta = selectize.getValue();
    var nomEst = selectize.getItem(idEsta).text();
    var option = selectize.options[idEsta];
    var idEst = option['idEstablecimiento'];
    var nombrecomercial = option['nombreComercial'];
    var direccion = option['direccion'];
    var vigenteHasta = option['vigenteHasta'];
    var estado = option['nombreEstado'];
    var correo = option['emailContacto'];

    $('#dt-distribuidores > tbody:last-child').append('<tr><input type="hidden" form="RegistroPreStep7" name="dist[]" value="' + idEsta + '"> <td>' + idEsta + '</td><td>' + nombrecomercial + '</td><td>' + direccion + '</td><td>' + vigenteHasta + '</td><td>' + estado + '</td><td>'+correo+'</td><td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
    /*
    else{
        alertify.alert("Mensaje de sistema","El distribuidor no se encuentra en estado activo, no puede ser seleccionado, contactese con la Unidad Jurídica para resolver su incoveniente!");
    }*/
});


$("#dt-distribuidores").on('click', '.btnEliminar', function () {
    $(this).closest('tr').remove();
});


function armarPresentacion() {

    var texto = '';

    $('#textPres').css('background-color', '#a9f0d3');
    $('#textPres').css('border-color', '#08f59f');
    $('#textPres').css('border', '15');
    emp2 = $('input[type=radio][name=checkempsec]:checked').val();
    emp3 = $('input[type=radio][name=checkempter]:checked').val();

    empaque1 = $('#empa1 option:selected').text();
    material1 = $('#mat1 option:selected').text();
    por1 = $('#por1').val().toString();

    empaque2 = $('#empa2 option:selected').text();
    por2 = $('#por2').val().toString();

    empaque3 = $('#empa3 option:selected').text();
    por3 = $('#por3').val().toString();

    material = $('#material option:selected').text();
    color = $('#color option:selected').text();
    accesorios = $('#accesorios').val();
    if(accesorios!='')
        accesorios = '+ ' +accesorios;
    $('#mat2').val(empaque1);
    $('#mat3').val(empaque2);

    if(emp2==0 && emp3==0){
        if(empaque1!='' && material1!='')
            texto= empaque1.toUpperCase()  +  ' DE ' + material.toUpperCase() + ' ' + color.toUpperCase() + ' X ' + por1 + ' '  + material1 + ' '+ accesorios.toUpperCase();
    }
    else if(emp2==1 && emp3==0){
        texto= empaque2.toUpperCase() + ' X '+ por2 + ' ' + empaque1.toUpperCase()  +  ' DE ' + material.toUpperCase() + ' ' + color.toUpperCase() + ' X ' + por1 + ' '  + material1 + ' '+ accesorios.toUpperCase();
    }
    else if (emp2==1 && emp3==1){
        texto= empaque3.toUpperCase() + ' X '+ por3 + ' ' + empaque2.toUpperCase() + ' X ' + por2 + ' ' + empaque1.toUpperCase()  +  ' DE ' + material.toUpperCase() + ' ' + color.toUpperCase() + ' X ' + por1 + ' '  + material1 + ' '+ accesorios.toUpperCase();
    }

    $('#textPres').val(texto);

}

//AL SELECCIONAR EL TIPO DE MEDICAMENTOS MOSTRAMOS SI ES BIOEQUIVALENTE O NO
$('#tipoMedicamento').on('change', function () {

    var tipoMee = this.value;
    document.getElementById("bioequi").length=0;
    if(tipoMee==9){
       $('#bioequi').append('<option value="1" selected>SI</option>');
    }else{
       $('#bioequi').append('<option selected value="0">NO</option>');
    }

});


//AL SELECCIONAR EL INNOVADOR, CARGAMOS LOS TIPOS DE MEDICAMENTOS

$('#innovador').on('change',function (){
    var a = this.value;
    $('#tipoMedicamento').val('');
    SelectTipoMedicamento(a);
});
function SelectTipoMedicamento(variable){

   var selectInno=variable;
    $('#tipoMedicamento').select2({
        ajax: {
            theme: "classic",
            url: config.routes[0].tiposmedicamentos+'?val='+selectInno,
            dataType: 'json',
            delay: 250,
            dropdownAutoWidth: false,
            width: 'resolve',
            processResults: function (data) {
                if (data.status == 200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.nomTipo,
                                id: item.tipoMed
                            }
                        })
                    };
                }
                else {
                    console.error(data.message);
                }
            },
            cache: true
        }
    });
}
$('#origen').change(function(){
    $('#divprocedencia').empty();
    var ruta='';
    var idProcedencia=$(this).val();
    if(idProcedencia==4){
        ruta = config.routes[0].getvistareconomutuo;
        ajaxViews(ruta);
    }
});
function ajaxViews(ruta){
    $.ajax({
        type: 'GET',
        url:  ruta,
        data:{idvista:1},
        success:  function (r){
            $('#divprocedencia').html(r.data);
        },
        error: function(data){
            var errors = $.parseJSON(data.message);
            console.log(errors);
        }
    });
}


$('input[type=radio][name=origenFabPrincipio]').change(function () {
    var origen = $(this).val();
    var unidad = 'URV';
    if (origen != 'E30') {
        config.flags[0].manyest = true;
    }
    $('#searchbox-fabricante5').selectize()[0].selectize.destroy();
    $('#searchbox-fabricante5').selectize({

        valueField: 'idEstablecimiento',
        inputClass: 'form-control selectize-input',
        labelField: 'nombreComercial',
        searchField: ['nombreComercial'],
        maxOptions: 10,
        preload: true,
        options: [],
        create: false,
        render: {
            option: function (item, escape) {
                return "<div class=\"option\" data-direcion='" + escape(item.direccion) + "'  data-pais='" + escape(item.pais) + "'>" + escape(item.nombreComercial) + "</div>";
            },
            item: function (data, escape) {
                return "<div class=\"item\" data-direcion='" + escape(data['direccion']) + "' data-pais='" + escape(data['pais']) + "' >" + escape(data['nombreComercial']) + "</div>";
            }
        },
        load: function (query, callback) {

            $.ajax({
                url: config.routes[0].findfabricante,
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query,
                    origenFab: origen,
                    many: config.flags[0].manyest,
                    unidad: unidad
                },
                error: function () {
                    callback();
                },
                success: function (res) {

                    if (res.status == 200) {
                        callback(res.data);
                    }
                    else if (res.status == 404) {
                        alertify.alert("Mensaje de sistema", res.message + " Contacte con la Unidad de Jurídico para solventar su incoveniente con el fabricante del producto.");
                        console.warn(res.message);
                    } else {//Unknown
                        alertify.alert("Mensaje de sistema", res.message);
                        console.warn("No se han podido cargar los fabricantes");
                    }

                },

            });
        }
    });

    $('#principioFabricante').val("");
    $('#principioFabricante').select2({
        ajax: {
            url: config.routes[0].materiasprimas,
            dataType: 'json',
            delay: 250,
            dropdownAutoWidth: false,
            width: '100%',
            processResults: function (data) {
                if (data.status == 200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.materiaPrima,
                                id: item.idMatPrima
                            }
                        })
                    };
                }
                else {
                    console.error(data.message);
                }
            },
        }
    });

});
$('#searchbox-fabricante5').on('change', function () {

    var selectize = $('#searchbox-fabricante5').get(0).selectize;
    var idEst = selectize.getValue();
    var nomEst = selectize.getItem(idEst).text();
    var option = selectize.options[idEst];
    var direccion = option['direccion'];
    var pais = option['pais'];
    var principio = $('#principioFabricante').val();
    var nombrePrin = $('#principioFabricante option:selected').text();
    var tipo = $('input[type=radio][name=origenFabPrincipio]:checked').val();
    var tipoOrigen='';
    /*$('#idFabricantePri').val(idEst);
    $('#nomProp').val(nomEst);
    $('#direccProp').val(direccion);
    $('#paisFabP').val(pais);*/



    if(principio===null){
         alertify.alert("Mensaje del Sistema", "¡Debe de seleccionar un principio activo!");
    }else{
         if(tipo!='E30'){
            tipoOrigen='N';
          }else{
            tipoOrigen='E';
          }
         $('#dt-fabricantesPrincipioActivo > tbody:last-child').append('<tr><input type="hidden" form="RegistroPreStep4" id="fabPrincipioActivo" name="fabPrincipioActivo[]" value="' + idEst + '"><input type="hidden" form="RegistroPreStep4" id="nombreprincipio" name="nombreprincipio[]" value="'+nombrePrin+'" /><input type="hidden" form="RegistroPreStep4" id="idprincpio" name="idprincpio[]" value="'+principio+'" /><input type="hidden" form="RegistroPreStep4" id="origenfabprincipio" name="origenfabprincipio[]" value="'+tipoOrigen+'" /><td>' + nomEst + '</td><td>' + direccion + '</td><td>' + pais + '</td><td>'+nombrePrin+'</td><td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');

    }

});


$("#dt-fabricantesPrincipioActivo").on('click', '.btnEliminar', function () {
    $(this).closest('tr').remove();
});

$('input[type=radio][name=valTerceraPersona]').change(function(){
    var val = $(this).val();
    if (val == 1) {
        $("#datosTercerasPer").show();
    }else{
        $("#datosTercerasPer").hide();
    }
});

$('input[type=radio][name=valContratoMaquila]').change(function(){
    var val = $(this).val();
    if (val == 1) {
        $("#datosContratoMaquila").show();
    }else{
        $("#datosContratoMaquila").hide();
    }
});

$('#searchbox-poderMaquila1').selectize({

    valueField: 'ID_PODER',
    inputClass: 'form-control selectize-input',
    labelField:  'ID_PODER',
    searchField: ['ID_PODER'],
    maxOptions: 100,
    preload: true,
    options: [],
    create: false,
    render: {
        option: function(item, escape) {
            return "<div>" + escape(item.ID_PODER) +"</div>";
        },
        item: function(data, escape) {
            return "<div>" + escape(data['ID_PODER']) + "</div>";
        }
    },
    load: function(query, callback) {

        $.ajax({
            url: config.routes[0].getpodermaquila,
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
                    alertify.alert("Mensaje de sistema",res.message + "No se han encontrado resultados en su búsqueda.");
                    console.warn(res.message);
                }else{//Unknown
                    alertify.alert("Mensaje de sistema",res.message);
                    console.warn("No se han podido cargar los datos");
                }

            },

        });
    }
});



$('#searchbox-poderMaquila1').on('change', function() {
    var nFilas = $("#dt-poderFabPrincipal tbody tr").length;
    if(nFilas >0){
        $('#dt-poderFabPrincipal tbody tr').remove();
    }
    var selectize = $('#searchbox-poderMaquila1').get(0).selectize;
    var idpoder = selectize.getValue();
    $('#dt-poderFabPrincipal > tbody:last-child').append('<tr><input type="hidden" id="poderFabPrincipal" name="poderFabPrincipal" form="RegistroPreStep4" value="'+idpoder+'"><td>'+idpoder+'</td></tr>');
});
$('#searchbox-poderMaquila2').selectize({

    valueField: 'ID_PODER',
    inputClass: 'form-control selectize-input',
    labelField:  'ID_PODER',
    searchField: [,'ID_PODER'],
    maxOptions: 100,
    preload: true,
    options: [],
    create: false,
    render: {
        option: function(item, escape) {
            return "<div class=\"option\"  >" + escape(item.ID_PODER) +"</div>";
        },
        item: function(data, escape) {
            return "<div class=\"item\"  >"+ escape(data['ID_PODER']) + "</div>";
        }
    },
    load: function(query, callback) {

        $.ajax({
            url: config.routes[0].getpodermaquila,
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
                    alertify.alert("Mensaje de sistema",res.message + " No se han encontrado resultados en su búsqueda.");
                    console.warn(res.message);
                }else{//Unknown
                    alertify.alert("Mensaje de sistema",res.message);
                    console.warn("No se han podido cargar los datos");
                }

            },

        });
    }
});
$('#searchbox-poderMaquila2').on('change', function() {

    var selectizemaquila2 = $('#searchbox-poderMaquila2').get(0).selectize;
    var valueemaquila2 = selectizemaquila2.getValue();
     if(valueemaquila2!=''){
     $('#dt-poderFabAlterno > tbody:last-child').append('<tr><input type="hidden" id="poderFabAlterno" name="poderFabAlterno[]" form="RegistroPreStep4" value="'+valueemaquila2+'"><td>'+valueemaquila2+'</td><td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
      }
});
$("#dt-poderFabAlterno").on('click', '.btnEliminar', function () {
    $(this).closest('tr').remove();
});




$('#searchbox-poderMaquila3').selectize({

    valueField: 'ID_PODER',
    inputClass: 'form-control selectize-input',
    labelField:  'ID_PODER',
    searchField: ['ID_PODER'],
    maxOptions: 100,
    preload: true,
    options: [],
    create: false,
    render: {
        option: function(item, escape) {
            return "<div>" + escape(item.ID_PODER) +"</div>";
        },
        item: function(data, escape) {
            return "<div>" + escape(data['ID_PODER']) + "</div>";
        }
    },
    load: function(query, callback) {

        $.ajax({
            url: config.routes[0].getpodermaquila,
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
                    alertify.alert("Mensaje de sistema",res.message + "No se han encontrado resultados en su búsqueda.");
                    console.warn(res.message);
                }else{//Unknown
                    alertify.alert("Mensaje de sistema",res.message);
                    console.warn("No se han podido cargar los datos");
                }

            },

        });
    }
});
$('#searchbox-poderMaquila3').on('change', function() {
    var selectize = $('#searchbox-poderMaquila3').get(0).selectize;
    var idpoder3 = selectize.getValue();
    if(idpoder3!=''){
    $('#dt-poderFabAcondicionador > tbody:last-child').append('<tr><input type="hidden" id="poderFabAcondicionador" name="poderFabAcondicionador[]" form="RegistroPreStep4" value="'+idpoder3+'"><td>'+idpoder3+'</td><td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
    }
});
$("#dt-poderFabAcondicionador").on('click', '.btnEliminar', function () {
    $(this).closest('tr').remove();
});


function dataStep2() {
    //METODO PARA LISTAR LOS TIPOS DE MEDICAMENTOS, ENVIAMOS 1 PORQUE CARGAMOS EL SELECT DE INNOVADOR COMO "SI" VALUE=1
    SelectTipoMedicamento(1);

    $('#formaFarm').select2({
        ajax: {
            url: config.routes[0].formasfarmaceuticas,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                if (data.status == 200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.formaFarmaceutica,
                                id: item.idFormaF
                            }
                        })
                    };
                }
                else {
                    console.error(data.message);
                }
            },
            cache: true
        }
    });

    $('#viaAdmin').select2({
        ajax: {
            url: config.routes[0].viasadministracion,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                if (data.status == 200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.viaAdministracion,
                                id: item.idViaAdmin
                            }
                        })
                    };
                }
                else {
                    console.error(data.message);
                }
            },
            cache: true
        }
    });

       $('#modalidad').select2({
        ajax: {
            url: config.routes[0].getmodalidadventa,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                if (data.status == 200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.nomModalidad,
                                id: item.idModalidad
                            }
                        })
                    };
                }
                else {
                    console.error(data.message);
                }
            },
            cache: true
        }
    });

}

function dataStep8() {
    document.getElementById("listLabAcondicionador").length = 0;
    $("#listLabAcondicionador").append('<option value="">Lista de laboratorios acondicionador...</option>');
    $('#dt-fabricantesAcondicionador tbody tr').each(function () {

        /* Obtener todas las celdas */
        var celdas = $(this).find('td');
        var valueinput = $(this).find('input').val();

        $('#listLabAcondicionador').append('<option value="' + valueinput + '">' + $(celdas[0]).html() + ' - (' + $(celdas[2]).html() + ')' + '</option>');

    });

    /* var tasks= new Array();
     $('input[name^="laboratorioAcondicionador"]').each(function(){
           tasks.push($(this).val());
           tasks.push($('#dt-fabricantesAcondicionador').find('tr').eq(i).find('td').html());
           i=i+1;
      });
     alert(tasks);*/
}

function dataStep5() {
    var titInput = $('#nomProp').val();
    var paisPinput = $('#paisFabP').val();
    var idFabpp = $('#idFabricantePri').val();
    var ntitular = $('#nomTitularProduc').val();
    // $('#titularProductoC').val(ntitular);
    // $('#idtitularProductoC').val(idFabpp);
    $('#labFabricante').val(titInput);
    $('#paisFabri').val(paisPinput);

}

function dataStep6() {

    var noPrincipal = $('#nomProp').val();
    var id2 = $('#idFabricantePri').val();
    $('#certificadobpm').val();
    $('#idcertificadobpm').val(id2);

    var paisprinfab = $('#paisFabP').val();
    $('#certificadobpm').val(paisprinfab);

    $('#dt-pract-labfabalterno tbody tr').remove();
    $('#dt-pract-labacondicionador tbody tr').remove();
     $('#dt-pract-labRelacionado tbody tr').remove();
     $('#dt-pract-FabPrinActivo tbody tr').remove();

    $('#dt-fabricantesAlternos tbody tr').each(function () {
        var celdas = $(this).find('td');
        var valueinput = $(this).find('input').val();
        var  pais1 = $(celdas[2]).html();
        $('#dt-pract-labfabalterno tbody').append('<tr><input form="RegistroPreStep6" type="hidden" name="practLabAlternos[]" value="' + valueinput + '"><td>' + $(celdas[0]).html() + '</td><td><input class="form-control" name="emisorAlterno-' + valueinput + '" value="'+pais1+'" type="hidden" form="RegistroPreStep6" />'+pais1+'</td><td><input form="RegistroPreStep6" type="date" class="form-control datepicker" id="fechaEmision-' + valueinput + '"  name="fechaEmision-' + valueinput + '" placeholder="dd-mm-yy"></td><td><input form="RegistroPreStep6" type="date" class="form-control datepicker" id="fechaVencimiento-' + valueinput + '"  name="fechaVencimiento-' + valueinput + '" placeholder="dd-mm-yyyy"></td><tr>');

    });
    $('#dt-fabricantesAcondicionador tbody tr').each(function () {
        var celdas = $(this).find('td');
        var valueinput = $(this).find('input').val();
        var pais2 = $(celdas[2]).html();
        $('#dt-pract-labacondicionador tbody').append('<tr><input form="RegistroPreStep6" type="hidden" name="practLabAcondi[]" value="' + valueinput + '"><td>' + $(celdas[0]).html() + '</td><td><input class="form-control" name="emisorAcondicionador-' + valueinput + '" value="'+pais2+'" type="hidden" form="RegistroPreStep6" />'+pais2+'</td><td><input form="RegistroPreStep6" type="date" class="form-control datepicker" id="fechaEmision-' + valueinput + '"  name="fechaEmision-' + valueinput + '" placeholder="dd-mm-yy"></td><td><input form="RegistroPreStep6" type="date" class="form-control datepicker" id="fechaVencimiento-' + valueinput + '"  name="fechaVencimiento-' + valueinput + '" placeholder="dd-mm-yyyy"></td><tr>');

    });
        $('#dt-fabricantesRelacionados tbody tr').each(function(){
            var celdas = $(this).find('td');
            var valueinput = $(this).find('input').val();
            var pais3 = $(celdas[2]).html();
         $('#dt-pract-labRelacionado tbody').append('<tr><input form="RegistroPreStep6" type="hidden" name="practLabRelacionado[]" value="'+valueinput+'"><td>'+$(celdas[0]).html()+'</td><td><input class="form-control" name="emisorRelacionado-'+valueinput+'"  value="'+pais3+'" type="hidden" form="RegistroPreStep6" />'+pais3+'</td><td><input form="RegistroPreStep6" type="date" class="form-control datepicker" id="fechaEmisionRel-'+valueinput+'"  name="fechaEmisionRel-'+valueinput+'" placeholder="dd-mm-yy" ></td><td><input form="RegistroPreStep6" type="date" class="form-control datepicker" id="fechaVencimientoRel-'+valueinput+'"  name="fechaVencimientoRel-'+valueinput+'" placeholder="dd-mm-yyyy"></td><tr>');

     });
      $('#dt-fabricantesPrincipioActivo tbody tr').each(function(){
            var celdas = $(this).find('td');
            var valueinput = $(this).find('input').val();
            var pais4=$(celdas[2]).html();
           $('#dt-pract-FabPrinActivo tbody').append('<tr><input form="RegistroPreStep6" type="hidden" name="practLabPrinActivo[]" value="'+valueinput+'"><td>'+$(celdas[0]).html()+'</td><td><input class="form-control" name="emisorPrinActivo-'+valueinput+'"  value="'+pais4+'" type="hidden" form="RegistroPreStep6" />'+pais4+'</td><td><input form="RegistroPreStep6" type="date" class="form-control datepicker" id="fechaEmisionAct-'+valueinput+'"  name="fechaEmisionAct-'+valueinput+'" placeholder="dd-mm-yy" ></td><td><input form="RegistroPreStep6" type="date" class="form-control datepicker" id="fechaVencimientoAct-'+valueinput+'"  name="fechaVencimientoAct-'+valueinput+'" placeholder="dd-mm-yyyy"></td><tr>');

     });



}

function dataStep9() {
    $.ajax({
        url: config.routes[0].getcodigoatc,
        type: 'get',
        success: function (r) {
            $('#codigo-atc').append(r.data);
            $("#codigo-atc").trigger("chosen:updated");
        },
        error: function (data) {
            console.error(data.message);
        }
    });

}

function dataStep10() {

    if ($('#documentos > tbody > tr').length <= 0) {

        $.ajax({
            url: config.routes[0].getExpedientesDocumentos,
            type: 'GET',
            beforeSend: function () {
                //  $('body').modalmanager('loading');
            },
            success: function (r) {
                // $('body').modalmanager('loading');
                $('#bodyDocumentosExp').html(r);
                var id=$('#idSolicitud9').val();
                var urldoc=config.routes[0].paso10Documentos;

                    /*$('#' + $(this).attr('id')).fileinput({
                        theme: 'fa',
                        language: 'es',
                        allowedFileExtensions: ['pdf'],
                        showUpload: false
                    });*/
                      $.each($('input[type=file]'), function () {
                            /*$('#' + $(this).attr('id')).fileinput({
                                uploadAsync:true, //realizará una llamada al servidor por cada fichero enviado.
                                uploadUrl: urldoc,
                                uploadExtraData:{idSolicitud9:$('#idSolicitud9').val()},
                                showPreview: false,
                                showRemove: false,
                                language: 'es',
                                allowedFileExtensions: ['pdf'],
                                }).on('filebatchpreupload', function(event, data, id, index) {
                                        //console.log("kv-success-"+$(this).attr('id'));
                                        $('#kv-success-' + $(this).attr('id')).html('<button name="eliminarDocAjax" id="eliminarDocAjax" title="Eliminar Documento" onclick="eliminarDocumento(this);" data-id="'+$(this).attr('id')+'" class="btn btn-danger eliminarDocAjax"><i class="fa fa-trash" aria-hidden="true"></i>ELIMINAR DOCUMENTO</button>').show();

                                });

                            });*/
                            $('#' + $(this).attr('id')).fileinput({
                                uploadAsync:true, //realizará una llamada al servidor por cada fichero enviado.
                                uploadUrl: urldoc,
                                uploadExtraData:{idSolicitud9:$('#idSolicitud9').val()},
                                showPreview: false,
                                showRemove: false,
                                language: 'es',
                                allowedFileExtensions: ['pdf'],
                                progressClass: "hide",
                                }).on('fileuploaded', function(event, data, id, index) {

                                        $('#kv-success-' + $(this).attr('id')).html('<button name="eliminarDocAjax" id="eliminarDocAjax" title="Eliminar Documento" onclick="eliminarDocumento(this);" data-id="'+$(this).attr('id')+'" class="btn btn-danger eliminarDocAjax"><i class="fa fa-trash" aria-hidden="true"></i>ELIMINAR DOCUMENTO</button> <span id="spanfs-'+$(this).attr('id')+'" class="label label-succes">Se subió el archivo correctamente</span>').show();
                                        $('#kv-error-' + $(this).attr('id')).empty();
                                        $('#kv-error-' + $(this).attr('id')).hide();

                                }).on('fileuploaderror', function(event, data, msg) {
                                    //$('.'+pgbarid).remove();
                                    $('#kv-error-' + $(this).attr('id')).html('<span id="spanf-'+$(this).attr('id')+'" class="label label-danger">Problemas en subir archivo</span>').show();
                                    $('.kv-upload-progress').hide();
                                    //console.log('File uploaded', data.previewId, data.index, data.fileId, msg);
                                });
                         });

            },
            error: function (data) {
                // Error...
                $('body').modalmanager('loading');
                alertify.alert("Mensaje del Sistema", "No se han encontrado documentos para mostrar!");
                var errors = $.parseJSON(data.message);
                console.log(errors);

            }
        });

    }


}






