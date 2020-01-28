function eliminarFabOrImp(idEst,fOrI){
    $.ajax({
        url:   config.routes[0].deletefab,
        data: 'idSolicitud3='+$('#idSolicitud3').val()+'&idFab='+idEst+'&fabOrImp='+fOrI,
        type:  'post',
        dataType    : 'json',
        beforeSend: function() {
            $('body').modalmanager('loading');
        },
        success:  function (r){
            $('body').modalmanager('loading');
            if(r.status==200){
                alertify.alert("Mensaje de Sistema","<strong><p class='text-primary text-justify'>"+r.message+"</p></strong>")
            }
            else if (r.status==400 || r.status==404){
                alertify.alert("Mensaje del Sistema","<strong><p class='text-warning text-justify'>"+r.message+"</p></strong>");
            }
        },
        error: function(data){
            $('body').modalmanager('loading');
            console.warn(data.message);
        }
    });
}

function eliminarDist(idDist){
    $.ajax({
        url:   config.routes[0].deletedist,
        data: 'idSolicitud4='+$('#idSolicitud4').val()+'&idDist='+idDist,
        type:  'post',
        dataType    : 'json',
        beforeSend: function() {
            $('body').modalmanager('loading');
        },
        success:  function (r){
            $('body').modalmanager('loading');
            if(r.status==200){
                alertify.alert("Mensaje de Sistema","<strong><p class='text-primary text-justify'>"+r.message+"</p></strong>")
            }
            else if (r.status==400 || r.status==404){
                alertify.alert("Mensaje del Sistema","<strong><p class='text-warning text-justify'>"+r.message+"</p></strong>");
            }
        },
        error: function(data){
            $('body').modalmanager('loading');
            console.warn(data.message);
        }
    });
}

$("#documentos").on('click', '.btnEliminarDoc', function () {
    var nomdoc= $(this).data('nomdoc');
    var id= $(this).data('id');
    var obligatorio= $(this).data('obligatorio');
    var td=$(this).closest('td');

    alertify.confirm("Mensaje de sistema", "Esta seguro que desea eliminar el documento del requisito "+nomdoc+"?", function (asc) {
        if (asc) {
            td.empty();
            td.append('<input id="file'+id+'" name="file-es['+id+']" type="file" required="true" data-obligatorio="'+obligatorio+'" form="CosPreStep6">');
            $('#file' + id).fileinput({
                theme: 'fa',
                language: 'es',
                allowedFileExtensions: ['pdf'],
                showUpload : false
            });
        } else {
        }
    }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});


});

