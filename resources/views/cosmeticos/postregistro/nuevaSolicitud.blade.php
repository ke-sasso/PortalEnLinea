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

::-webkit-file-upload-button {
  color:#fff;background-color:#29A0CB;border-color:#29A0CB

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

#frmEst{
    width:0px;
    height: 0px; 
    position: center;
    top: 30%;
    left: 50%;
    margin-top: -0px;
    margin-left: -200px;
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
    <div class="modal fade" id="imprimirModal" tabindex="-1" role="dialog" aria-labelledby="myModalImprimir" aria-hidden="false" style="display: block;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="js-title-step">{!! Session::get('msnExito') !!}</h4>
      </div>
      <div class="modal-body">
          <div align="center">

            @if(Session::get('SinCerificar')==1)
              <label>Imprimir comprobante de ingreso de cambio post-registro</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               <a href="{{route('comprobante.sim',['idSolicitud' => Crypt::encrypt(Session::get('idSolicitud'))])}}" target="_blank" title="Imprimir Comprobante"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a> 
            @elseif(Session::get('desestimiento')!=0)
              <label>Se ha desistido su solicitud de Pre-Registro  de insumos médicos exitosamente!</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
             <a href="{{route('desistimiento.sim',['idSolicitud' => Session::get('desestimiento'), 'idTramite' => Session::get('idTramite')])}}" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>

            @else
            <label>Imprimir Resolución de la Solicitud</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="{{route('imprimir.sim',['idSolicitud' => Crypt::encrypt(Session::get('idSolicitud')), 'idTramite' => Crypt::encrypt(Session::get('idTramite'))])}}" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>
            @endif
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
        Algo ha salido mal {!! Session::get('msnError') !!}
    </div>
  @endif


<div class="panel panel-success">
  <div class="panel-heading">
   <strong> <h3  class="panel-title"></h3></strong>
  </div>
  <div class="panel-body">
    <div role="tabpanel">
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">

            <li role="presentation" class="active">

            <a href="#panel-express" aria-controls="panel-express" role="tab" id="tabTramite" data-toggle="tab">VENTANILLA VIRTUAL EXPRÉS</a>
          </li>
          {{--  <li role="presentation" >
            <a href="#panel-enlinea" aria-controls="panel-enlinea" role="tab" id="tabTramite" data-toggle="tab">VENTANILLA VIRTUAL AUTOMATICA</a>
          </li>--}}
        <li role="presentation">
          <a href="#generales" aria-controls="tab" class="hidden" role="tab"  id="tabDetalleTramite" data-toggle="tab">DETALLE DEL TRAMITE</a>
        </li>             
      </ul>
    
      
      <!-- Tab panes -->
      <div class="tab-content">
        

        <div role="tabpanel" class="tab-pane active" id="panel-express">
        <div class="table-responsive">
            <table class="table table-hover" id="dt-tramites">
              <thead>
                <tr>
                  <th></th>
                  <th>NOMBRE DEL TRAMITE</th>
                </tr>
              </thead>
              <tbody>
              @foreach($tramites as $tra)
                  <tr>
                    <td id="idTipoTramite">
                      <a class="btn btn-primary btn-sm" data-nombre="{{$tra->nombreTramite}}" data-id="{{$tra->idTramite}}" onclick="getTabGeneral(this);">
                      <i id="tramite" class="fa fa-square-o"></i>
                      </a>
                    </td>
                    <td id="nomTramite"><b>{{$tra->nombreTramite}}</b></td>
                  </tr>
              @endforeach
              </tbody>
            </table>
          </div>
          </div> <!-- End tab panes --> 

          {{--
        <div role="tabpanel" class="tab-pane" id="panel-enlinea">
          <div class="table-responsive">
            <table class="table table-hover" id="dt-tramites">
              <thead>
                <tr>
                  <th></th>
                  <th>NOMBRE DEL TRAMITE</th>
                </tr>
              </thead>
              <tbody>
        
             
      
              </tbody>
            </table>
          </div>
        </div>--}}
  
        @include('cosmeticos.postregistro.detalletramite')
    </div>
  </div>
</div>
@endsection
@section('js')
{!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!}

<script type="text/javascript">
  var documentos = {!!$documentos!!};
  $(document).ready(function(){


  });
$('#btnBuscarProducto').click(function(event) {

            perfil=$("#perfil option:selected" ).val();

            var dtproductos = $('#dt-producto').DataTable({
                                processing: true,
                                filter:true,
                                serverSide: true,
                                destroy: true,
                                pageLength: 5,
                                ajax: {
                                  url: "{{route('cosproregistro.get.productos.prof')}}",
                                },
                                columns:[                        
                                        {data: 'idCosmetico',name:'idCosmetico'}, 
                                        {data: 'nombreComercial',name:'nombreComercial'},                
                                        {data: 'vigenciaHasta',name:'vigenciaHasta'}, 
                                        {data: 'renovacion',name:'renovacion'},
                                        {data: 'alerta',name:'alerta',ordenable:false,searchable:false}                       
                                    ],
                               language: {
                                processing: '<div class=\"dlgwait\"></div>',
                                "url": "{{ asset('plugins/datatable/lang/es.json') }}"
                                
                            },
                         "order": [[ 2, 'desc' ]]                            
            });
        $('#dlgProductos').modal('toggle');
    }); 

function getTabGeneral(elem){
    var nombre = $(elem).data('nombre');
    var idtramite = $(elem).data('id');
 
    $("#documentos tr").remove(); 
    for(j=0;j<documentos.length;j++){
              if(documentos[j].idTramite==idtramite){
                $('#documentos').append('<tr><td width="50%" colspan=""><u>'+documentos[j].nombreRequisito+'<u></td><td><input type="file" id="docs" name="files['+documentos[j].idRequisito+']" accept="application/pdf"  required></td></tr>');
              }
    }
          
    $('#idTramite').val(idtramite);
    document.getElementById("leyendTramite").innerHTML="";
    $('#leyendTramite').append('DATOS DE LA SOLICITUD: ' + nombre);
    document.getElementById("elemtByTramite").innerHTML="";
    document.getElementById("guardar").innerHTML="";
    $("#frmSolicitudPost")[0].reset();
    if(idtramite==3){
      $('#elemtByTramite').append('<br><div class="input-group"><div class="input-group-addon"><b>FRAGANCIA</b></div><textarea class="form-control" name="campo1" id="campo1" required></textarea></div><br>');
    }else if(idtramite==4){
      $('#elemtByTramite').append('<br><div class="input-group"><div class="input-group-addon"><b>TONO</b></div><textarea class="form-control" name="campo1" id="campo1" required></textarea></div><br>');
    }

    $('#tabDetalleTramite').removeClass('hidden');
    $('.nav-tabs a[href="#generales"]').tab('show');
}

 function selectProducto(id_producto, nombre_comercial, vigencia, renovacion){
                    document.getElementById('idproducto').value = id_producto;
                    document.getElementById('txtidproducto').value = id_producto;
                    document.getElementById('nomcomercial').value = nombre_comercial;
                     document.getElementById('nombreComercial').value = nombre_comercial;
                    document.getElementById("nombre").style.display = "block";
                    document.getElementById('txtvigencia').value = vigencia;
                    document.getElementById("vigencia").style.display = "block";
                    document.getElementById('txtrenovacion').value = renovacion;
                    document.getElementById("renovacion").style.display = "block";
  }


   $('#validar').click(function (event) {
                        var mandamiento = $('#num_mandamiento').val();
                        var token = $('#token').val();
                        var tipo = $('input:radio[name=tipo]:checked').val();
                        var id_tramite = $('#idTramite').val();
                        //console.log(mandamiento);
                        $.ajax({
                            data: 'numMandamiento=' + mandamiento + '&tipo=' + tipo + '&idTramite='+ id_tramite +'&_token=' + token,
                            url: "{{route('cospostregistro.validarmandamiento')}}",
                            type: 'post',
                            beforeSend: function () {
                                $('body').modalmanager('loading');
                            },
                            success: function (r) {
                                $('body').modalmanager('loading');
                                if (r.status == 200) {
                                    alertify.alert("Mensaje de sistema", r.message);
                                    $('#guardar').append('<button type="submit" id="guardarSoli" name="guardar"  onclick="enviardatos();" class="btn btn-primary">Guardar Solicitud</button>');
                                    //$('#guardar').show();
                                }
                                else if (r.status == 400) {
                                    alertify.alert("Mensaje de sistema - Error", r.message);
                                }else {//Unknown
                                    alertify.alert("Mensaje de sistema", "Error al validar el mandamiento, contacte al adminsitrador!");
                                    //console.log(r);
                                }
                            },
                            error: function (data) {
                                // Error...
                                var errors = $.parseJSON(data.responseText);
                                // console.log(errors);
                                $.each(errors, function (index, value) {
                                    $.gritter.add({
                                        title: 'Error',
                                        text: value
                                    });
                                });
                            }
                        });

   });


      function enviardatos(){
                    alertify.confirm("Mensaje de sistema", "¿Esta seguro que desea procesar este trámite?", function (asc) {
                        if (asc) {
                          
                             $( "#frmSolicitudPost" ).submit();
                        } else {
                            //alertify.error("Solicitud no enviada");
                        }
                    }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});
          }



     $('#frmSolicitudPost').submit(function(e){
      var formObj = $(this);
      var formURL = formObj.attr("action");
      var formData = new FormData(this);
      $.ajax({
      data: formData,
      url: formURL,
      type: 'post',
      mimeType:"multipart/form-data",
        contentType: false,
          cache: false,
          processData:false,
      beforeSend: function() {
        $('body').modalmanager('loading');
      },
          success:  function (response){
            $('body').modalmanager('loading');
            if(isJson(response)){
                var obj =  JSON.parse(response);
               alertify.alert("Mensaje de Sistema","<strong>"+ obj.msg +"</strong>",function(){
                location.reload();
               });
             
            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
              $('body').modalmanager('loading');
               alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: No se pudo registrar la información general!</p></strong>");
              console.log("Error en peticion AJAX!");  

          }
    });
    e.preventDefault(); //Prevent Default action. 

    });

function isJson(str) {
      try {
          JSON.parse(str);
      } catch (e) {
          return false;
      }
      return true;
  }

</script>
@endsection
