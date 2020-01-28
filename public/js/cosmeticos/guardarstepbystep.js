$('#btnStep2').click(function(e){
    var formObj = $('#CosPreStep1y2');
    var formURL = formObj.attr("action");
    var formData = new FormData($("#CosPreStep1y2")[0]);
    console.log($(this).data('autosave'));
    if($(this).data('autosave')=="true") {
        autoGuardado(formURL, formData);
        $(this).data('autosave','false');
    }
    else
        guardarPaso(formURL,formData);
});

$('#btnStep3').click(function(e){
    var formObj = $('#CosPreStep3');
    var formURL = formObj.attr("action");
    var formData = new FormData($("#CosPreStep3")[0]);
    if($(this).data('autosave')=="true") {
        autoGuardado(formURL, formData);
        $(this).data('autosave', 'false');
    }
    else
        guardarPaso(formURL,formData);
});

$('#btnStep4').click(function(e){
    var formObj = $('#CosPreStep4');
    var formURL = formObj.attr("action");
    var formData = new FormData($("#CosPreStep4")[0]);
    if($(this).data('autosave')=="true") {
        autoGuardado(formURL, formData);
        $(this).data('autosave', 'false');
    }
    else
        guardarPaso(formURL,formData);
});

$('#btnStep5').click(function(e){
    var formObj = $('#CosPreStep5');
    var formURL = formObj.attr("action");
    var formData = new FormData($("#CosPreStep5")[0]);
    if($(this).data('autosave')=="true"){
        autoGuardado(formURL,formData);
        $(this).data('autosave','false');
    }
    else
        guardarPaso(formURL,formData);
});


$('#btnStep6').click(function(e){
    var formObj = $('#CosPreStep6');
    var formURL = formObj.attr("action");
    var formData = new FormData($("#CosPreStep6")[0]);
    if($(this).data('autosave')=="true") {
        autoGuardado(formURL, formData);
        $(this).data('autosave','false');
    }
    else
        guardarPaso(formURL,formData);
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
            if(response.data) { $('#idSolicitud').val(response.data); $('#idSolicitud2').val($('#idSolicitud').val()); }
            if(response.route)  window.location.href = $('#solscospre').attr('href');
            alertify.alert("Mensaje de Sistema","<strong><p class='text-primary text-justify'>"+response.message+"</p></strong>")
        },
        error: function(response) {
            $('body').modalmanager('loading');
            if(response.status==422){
                alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ADVERTENCIA: "+ response.responseJSON.errors +"</p></strong>");
            }
            else{
                alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ADVERTENCIA: "+ response.responseJSON.errors +"</p></strong>");
            }
        }
    });
}

function autoGuardado(formURL,formData){
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
            console.info(response.message);
            if(response.data){
                $('#idSolicitud').val(response.data);
                $('#idSolicitud2').val($('#idSolicitud').val());
            }
        },
        error: function(response) {
            $('body').modalmanager('loading');
            console.info(response.responseJSON.errors);
        }
    });
}

$('#formulariopdf').click(function(e){
    window.open(config.routes[0].pdfform+'?idSolicitud='+$('#idSolicitud').val());
});