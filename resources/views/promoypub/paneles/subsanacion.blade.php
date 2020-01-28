@extends('master')
@section('css')
  {!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal.css') !!}
  {!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') !!}
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
textarea {
    white-space: normal;
    text-align: justify;
    -moz-text-align-last: left; /* Firefox 12+ */
    text-align-last: left;
}

@media screen and (min-width: 768px) {
  
  #modal-id .modal-dialog  {width:900px;}

}

#myModal{
    
    position: center;
    top: 5%;
    left: 50%;
    margin-top: -0px;
    margin-left: -200px;
    padding: 0px;
}

#imprimirModal{
    position: center;
    top: 5%;
    left: 50%;
    margin-top: -0px;
    margin-left: -200px;
    padding: 0px; 
}

#frmEst{
    position: center;
    top: 50%;
    left: 50%;
    margin-top: -0px;
    margin-left: -350px;
    padding: 0px;

}

#frmEst1{
    position: center;
    top: 50%;
    left: 50%;
    margin-top: -0px;
    margin-left: -350px;
    padding: 0px;

}

div.modal{
  width:0px;
  height: 0px;
    position: center;
    top: 5%;
    left: 0%;
    margin-top: -0px;
    margin-left: -0px;
    padding: 0px;
}
</style>
@endsection
@section('contenido')
{{-- MENSAJE DE EXITO --}}
  @if(Session::has('msnExito'))
    <div class="modal fade" id="imprimirModal" tabindex="-1" role="dialog" aria-labelledby="myModalImprimir" aria-hidden="false" style="display: block;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="js-title-step">Enhorabuena! {!! Session::get('msnExito') !!}</h4>
      </div>
      <div class="modal-body">
          <div align="center">

            <label>Imprimir Solicitud</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="{{route('imprimir.solicitud',['idSolicitud'=>Crypt::encrypt(Session::get('idSolicitud'))])}}" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>
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
    <div class="alert alert-danger square fade in alert-dismissable">
      <button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
      <strong>Auchh!</strong>
      Algo ha salido mal. {!! Session::get('msnError') !!}
    </div>
  @endif
<div class="panel panel-success">
  <div class="panel-heading">
    <h3 class="panel-title">SUBSANACIÓN </h3>
    <input type="hidden" name="idSolicitud" id="idSolicitud" class="form-control" value="">
  </div>
  <div class="panel-body">
    <div class="row">
    @if($tipoTramite==1)
        <form id="form1" method="post" enctype="multipart/form-data" action="{{route('guardar.solicitudes') }}">
    @elseif($tipoTramite==2)
        <form id="form2" method="post" enctype="multipart/form-data" action="{{route('guardar.solicitudes.ext')}}">
    @endif
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="panel with-nav-tabs panel-success">
          <div class="panel-heading">

             <ul class="nav nav-tabs">
              <li class="active"><a href="#panel-dtsolicitud" data-toggle="tab">Detalle Solicitud</a></li>
              <li><a href="#panel-dtpublicidad" data-toggle="tab">Detalle Publicidad</a></li>
            </ul>
          </div>
            
          <div id="panel-collapse-1" class="collapse in">
              <div class="panel-body">
                  <div class="tab-content">
                    <div class="form-group">
                        <div class="input-group col-sm-12 col-md-12 col-lg-6">
                          <div class="input-group-addon">No. de Solicitud a Subsanar:</div>
                          <input type="text" class="form-control text-uppercase" id="solpadre" name="solpadre" value="{{$tramitespub->numeroSolicitud}}" readonly>
                        </div>
                    </div>
                      @if($tipoTramite==1)
                          @include('promoypub.paneles.detsolicitud')
                          @include('promoypub.paneles.detpublicidad')
                      @elseif($tipoTramite==2)
                          @include('promoypub.paneles.detsolicitudext')
                          @include('promoypub.paneles.detpublicidad')
                      @endif


               

              

              </div><!-- /.tab-content -->
            </div><!-- /.panel-body -->           
          </div><!-- /.collapse in -->
          
        </div><!-- /.panel .panel-success -->
      </div><!-- /.col-sm-6 -->

      <input type="hidden" name="origen" id="origen" value="4">
      <input type="hidden" name="tipoTramite" id="tipoTramite3" value="{{$origen}}">
    </form>
    </div><!-- /.row -->    
  </div>
</div>



<!-- Modal Establecimientos-->
<div class="modal fade" id="frmEst1" aria-labelledby="frmEst" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-success">
                <button type="button" class="close"
                        data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="frmModalLabel">
                    SELECCIONES UNO O MÁS PRODUCTOS
                </h4>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-right" id="bs-example-navbar-collapse-1">
                <form class="navbar-form navbar-left" role="search">

                </form>
            </div><!-- /.navbar-collapse -->
            </br>
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dt-productos">
                        <thead class="the-box dark full">
                        <tr>
                            <th>N° REGISTRO</th>
                            <th>NOMBRE COMERCIAL</th>
                            <th>VIGENCIA HASTA</th>
                            <th>ULTIMA RENOVACION</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Modal Body -->
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button"  class="btn btn-primary" onclick="guardarProductosEst();" data-dismiss="modal">Guardar</button>


            </div>
        </div>
    </div>
</div>

<!-- End Modal form -->



<!-- Modal Establecimientos-->
<div class="modal fade" id="frmEst" aria-labelledby="frmEst" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-success">
                        <button type="button" class="close" 
                           data-dismiss="modal">
                               <span aria-hidden="true">&times;</span>
                               <span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="frmModalLabel">
                           SELECCIONES UNO O MÁS PRODUCTOS
                        </h4>
                    </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-right" id="bs-example-navbar-collapse-1">           
              <form class="navbar-form navbar-left" role="search">
              
              </form>
            </div><!-- /.navbar-collapse -->
            </br>                
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="table-responsive">
                          <table class="table table-hover" id="dt-productos2">
                            <thead class="the-box dark full">
                              <tr>
                                <th>N° REGISTRO</th>
                                <th>NOMBRE COMERCIAL</th>
                                <th>VIGENCIA HASTA</th>
                                <th>ULTIMA RENOVACION</th>
                                <th>TITULAR</th>
                                <th></th>
                                <th></th>
                                  <th></th>
                              </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                          </table>
                        </div>
                    </div>
                    <!-- End Modal Body -->
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button"  class="btn btn-primary" onclick="guardarProductos();" data-dismiss="modal">Guardar</button>

                                      
                    </div>
                </div>
            </div>
        </div>

  <!-- End Modal form --> 




@endsection
@section('js')

{!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!}
<script type="text/javascript">
   var estado={!!$estado!!}; 
  var dataAddProds = [];
  var data = [];
  var tramite={!!$tipoTramite!!};

$(document).ready(function() {
  var dtpropietarios = $('#propietariotb').DataTable({
          language: {
            "url": "{{ asset('plugins/datatable/lang/es.json') }}"
           
          }
      });
//$("#myModal").modal('hide');
  exito={!! (Session::has('msnExito')!=null)?Session::has('msnExito'):0!!};

  if(exito==1){
    $("#imprimirModal").modal('show');
  }
  else{

    $("#imprimirModal").modal('hide'); 
  }

if(estado==11){
    document.getElementById("validar").style.display = "none";
    document.getElementById("num_mandamiento").readOnly = true;
}


$('#cerrar').click(function(event){
    window.location.href = '{{route("ver.solicitudes")}}';

});

$('#cerrar1').click(function(event){
    window.location.href = '{{route("ver.solicitudes")}}';

});

if(tramite==1){
  $('#guardarSoli').click(function() {
    alertify.confirm("Mensaje de sistema","Esta Seguro que la informacion es de acuerdo a su solicitud?", function (asc) {
         if (asc) {
             $('#form1').submit();      
             alertify.success("Solicitud Enviada.");

         } else {
             alertify.error("Solicitud no enviada");
         }
     }, "Default Value");
          
});
}
else if (tramite==2){
  $('#guardarSoli').click(function() {
    alertify.confirm("Mensaje de sistema","Esta Seguro que la informacion es de acuerdo a su solicitud?", function (asc) {
         if (asc) {
             $('#form2').submit();      
             alertify.success("Solicitud Enviada.");

         } else {
             alertify.error("Solicitud no enviada");
         }
     }, "Default Value");
          
}); 
}



$('#btnBuscarEstablecimiento').click(function(event) {
        idEstablecimiento=$("#establecimiento option:selected" ).val();
        console.log(idEstablecimiento);
        dataAddProds.length=0;
        var dtproductos = $('#dt-productos').DataTable({
                          processing: true,
                          serverSide: false,
                          destroy: true,
                          pageLength: 5,
                          ajax: {
                              url: "{{route('dt.row.data.productos') }}",
                              data: function (d) {
                                  d.tipoTramite= $('#tipoTramite3').val();
                                  d.idEstablecimiento=idEstablecimiento;
                              }
                          },
                          columns:[                        
                                  {data: 'idProducto'},
                                  {data: 'nombreComercial'},
                                  {data: 'vigenteHasta'},
                                  {data: 'ultimaRenovacion'},
                                  {
                                          "mData": null,
                                          "bSortable": false,
                                          "mRender": function (data,type,full) { return '<input type=checkbox value="test your look ;)" name="idproducto" data-prodname="'+data.nombreComercial+'"  data-prodid="'+data.idProducto+'" data-modalidad="'+data.nombreModalidadVenta+'" data-vigencia="'+data.vigenteHasta+'" data-reno="'+data.ultimaRenovacion+'" class="ckb-check">' }
                                  },
                                  {data: 'nombreModalidadVenta'},
                                  
                                  
                              ],
                          language: {
                              "sProcessing": '<div class=\"dlgwait\"></div>',
                              "url": "{{ asset('plugins/datatable/lang/es.json') }}"
                              
                          },
                          columnDefs: [
                            {
                            "targets": [5],
                            "visible": false
                            }
                          ],

                  });     
        
        $('#frmEst1').modal('toggle');
      
     
    });

      


$('#btnBuscarProducto').click(function(event) {

          var dtproductosext = $('#dt-productos2').DataTable({
                          processing: true,
                          serverSide: false,
                          destroy: true,
                          pageLength: 5,
                          ajax: {
                              url: "{{route('dt.row.data.productospp') }}",
                              data: function (d) {
                                  d.tipoTramite= $('#tipoTramite3').val();
                              }
                          },
                          columns:[                        
                                  {data: 'idProducto'},
                                  {data: 'nombreComercial'},
                                  {data: 'vigenteHasta'},
                                  {data: 'ultimaRenovacion'},
                                  {data: 'titular'},
                                  {
                                          "mData": null,
                                          "bSortable": false,
                                          "mRender": function (data,type,full) { return '<input type=checkbox value="test your look ;)" name="idproducto" data-prodname="'+data.nombreComercial+'"  data-prodid="'+data.idProducto+'" data-modalidad="'+data.nombreModalidadVenta+'" data-vigencia="'+data.vigenteHasta+'" data-reno="'+data.ultimaRenovacion+'" data-titular="'+data.titular+'" data-idtitular="'+data.idTitular+'" class="ckb-check">' }
                                  },
                                  {data: 'nombreModalidadVenta'},
                                  {data: 'idTitular'},
                                  
                                  
                              ],
                          language: {
                              "sProcessing": '<div class=\"dlgwait\"></div>',
                              "url": "{{ asset('plugins/datatable/lang/es.json') }}"
                              
                          },
                          columnDefs: [
                            {
                            "targets": [6,7],
                            "visible": false
                            }
                          ],

                  });     
        
            $('#frmEst').modal('toggle');  

      
     
    });
});

 
$(document).on('click','.ckb-check',function(e) {
   if (this.checked) {
       if($('#tipoTramite3').val()==1){
           dataAddProds.push([$(this).data("prodname"), $(this).data("prodid"), $(this).data("modalidad"), $(this).data("vigencia"), $(this).data("reno")]);
       }
       else {
           dataAddProds.push([$(this).data("prodname"), $(this).data("prodid"), $(this).data("modalidad"), $(this).data("vigencia"), $(this).data("reno"), $(this).data("titular"), $(this).data("idtitular")]);
       }
      } else {

      }
    data=dataAddProds;
});

$('#vwProductos tbody tr').each(function () {

      var idProducto = $(this).find("td").eq(0).html();
      var nombre = $(this).find("td").eq(1).html();
      var modalidad = $(this).find("td").eq(2).html();
      var vigencia = $(this).find("td").eq(3).html();
     // console.log(nombre);
});

function storeTblValues()
{
    var TableData = new Array();

    $('#vwProductos tr').each(function(row, tr){
          TableData[row]={
              "idProducto" : $(tr).find('td:eq(0)').text()
              
          }    
      });
    TableData.shift();  // first row will be empty - so remove
    return TableData;
}

/*
$('#guardarSoli').click(function(event) {
      
      var TableData;
      TableData = JSON.stringify(storeTblValues());

      var tipoTramite=$('#tipoTramite').val();
      var origen=$('#origen').val();
     //var table= parseJSON(TableData); 
        var form1= $('#form1').serialize();
        var form2= $('#form2').serialize();
        var token =$('#token').val();
        var dataString = form2+'&'+form1+'&'+"pTableData=" + TableData+'&'+"tipoTramite=" + tipoTramite+'&'+"origen=" +origen+'&_token='+token;
           $.ajax({
            data:  dataString,
            url:   "{{ route('guardar.solicitudes') }}",
            type:  'post',
            dataType: "json",
            beforeSend: function() {
                $('body').modalmanager('loading');
            },
            success:  function (r){
                $('body').modalmanager('loading');
                if(r.status == 200){
                  alertify.alert("Mensaje de sistema",r.message);
                  $('#form2').submit(

                  );
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
        return false;

      
    });
*/


   function guardarProductosEst(){

       for (var i =0; i< dataAddProds.length;i++) {
           var table = document.getElementById("vwProductos");
           {
               var row = table.insertRow();
               var cell1 = row.insertCell(0);
               var cell2 = row.insertCell(1);
               var cell3 = row.insertCell(2);
               var cell4 = row.insertCell(3);
               var cell5 = row.insertCell(4);
               var cell6 = row.insertCell(5);


               cell1.innerHTML = "<input type='hidden' name='pTableData[]' value='"+dataAddProds[i][1]+"'/>" +  dataAddProds[i][1];
               cell2.innerHTML = dataAddProds[i][0];
               cell3.innerHTML = dataAddProds[i][2];
               cell4.innerHTML = dataAddProds[i][3];
               cell5.innerHTML = dataAddProds[i][4];
               cell6.innerHTML = '<button type="button" id="removeprod" class="removebutton btn btn-danger"><i class="fa fa-trash-o"></i></button>'

           }
       };

   }



   function guardarProductos(){

       for (var i =0; i< dataAddProds.length;i++) {
           var table = document.getElementById("vwProductos");
           {
               var row = table.insertRow();
               var cell1 = row.insertCell(0);
               var cell2 = row.insertCell(1);
               var cell3 = row.insertCell(2);
               var cell4 = row.insertCell(3);
               var cell5 = row.insertCell(4);
               var cell6 = row.insertCell(5);
               var cell7 = row.insertCell(6);


               cell1.innerHTML = "<input type='hidden' name='pTableData[]' value='"+dataAddProds[i][1]+"'/>" +  dataAddProds[i][1];
               cell2.innerHTML = dataAddProds[i][0];
               cell3.innerHTML = dataAddProds[i][2];
               cell4.innerHTML = dataAddProds[i][3];
               cell5.innerHTML = dataAddProds[i][4];
               cell6.innerHTML = "<input type='hidden' name='idEstablecimiento[]' value='"+dataAddProds[i][6]+"'/>" + dataAddProds[i][5];
               cell7.innerHTML = '<button type="button" id="removeprod" class="removebutton btn btn-danger"><i class="fa fa-trash-o"></i></button>'

           }
       };

   }

   $("#vwProductos").on('click', '.removebutton', function () {
       $(this).closest('tr').remove();
   });




</script>
@endsection


