@extends('master')

@section('css')
{!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal.css') !!}
{!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') !!}


<style type="text/css">

body{

    overflow-x: hidden;
    overflow-y: scroll !important;
}
.chosen-container { width: 400px !important; }

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

#dlgAddPresent{
    width:0px;
    height: 0px;
    position: center;
    top: 0%;
    left: 0%;
    margin-top: -0px;
    margin-left: 300px;
    padding: 0px;

    }

#dlgExportacion{
    width:0px;
    height: 0px;
    position: center;
    top: 0%;
    left: 0%;
    margin-top: -0px;
    margin-left: 300px;
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

td.details-control {
    background: url("{{ asset('/plugins/datatable/images/details_open.png') }}") no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url("{{ asset('/plugins/datatable/images/details_close.png') }}") no-repeat center center;
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
             <p><b>SOLICITUD INGRESADA CON EXITO</b></p>

            {{--@if(Session::get('idTramite')==44)
              <label>Esta solicitud entrara a sesión</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            @elseif(in_array(Session::get('idTramite'),[33,35,52]))
              <div class="row">
                <label>Comprobante de Ingreso del trámite.</label> &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="{{route('comprobante.ingreso.rv',['idSolicitud' => Session::get('idSolicitud'), 'idTramite' => Session::get('idTramite')])}}" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>
                <br>
                <label>Declaracion Jurada.</label> &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="{{route('declaracion.jurada',['idSolicitud' => Session::get('idSolicitud'), 'idTramite' => Session::get('idTramite')])}}" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>
              </div>
            @elseif(in_array(Session::get('idTramite'),[46,45]))
                <label>Comprobante de Ingreso del trámite.</label> &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="{{route('comprobante.ingreso.rv',['idSolicitud' => Session::get('idSolicitud'), 'idTramite' => Session::get('idTramite')])}}" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>
            @else
            <label>Imprimir Resolución de la Solicitud</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="{{route('imprimir.rv',['idSolicitud' => Session::get('idSolicitud'), 'idTramite' => Session::get('idTramite')])}}" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>
            @endif--}}
          </div>
          <br>
      </div>
      <div class="modal-footer">
        {{--<button type="button" id="cerrar1" class="btn btn-primary btn-perspective">Cerrar</button>--}}
          <button type="button" class="btn btn-primary btn-perspective" data-dismiss="modal">ACEPTAR</button>

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
<div class="alert alert-info alert-block fade in alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <strong>MENSAJE DEL SISTEMA:</strong> Estimado usuario, temporalmente se han deshabilitado algunos trámites debido a que estamos optimizando el sistema, le pedimos disculpas por el inconveniente y le solicitamos atentamente se presente a nuestras ventanillas en la DNM para realizar el trámite.
</div>
<div class="panel panel-success">
  <div class="panel-heading">
   <strong> <h3 id="leyendTipo" class="panel-title"></h3></strong>
  </div>
  <div class="panel-body">
    <div role="tabpanel">
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
          <a href="#home" aria-controls="home" role="tab" id="tabTramite" data-toggle="tab">TIPO DE TR&Aacute;MITE</a>
        </li>
        <li role="presentation">
          <a href="#generales" aria-controls="tab" class="hidden" role="tab"  id="tabDetalleTramite" data-toggle="tab">DETALLE DEL TR&Aacute;MITE</a>
        </li>
      </ul>



      <!-- Tab panes -->
      <div class="tab-content">

        <div role="tabpanel" class="tab-pane active" id="home">
          <div class="panel with-nav-tabs panel-success">
            <div class="panel-heading">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#panel-admin" data-toggle="tab">ADMINISTRATIVO</a></li>
                  <li><a href="#panel-medico" data-toggle="tab">M&Eacute;DICO</a></li>
                  <li><a href="#panel-quimico" data-toggle="tab">QU&Iacute;MICO</a></li>
                  <li><a href="#panel-juridico" data-toggle="tab">JUR&Iacute;DICO</a></li>
                </ul>
            </div>
            <div id="panel-collapse-1" class="collapse in">
            <div class="panel-body">
              <div class="tab-content">
                  <div class="tab-pane active in" id="panel-admin">
                      <div class="table-responsive">
                        <table class="table table-hover" id="dt-tramites">
                          <thead>
                            <tr>
                              <th></th>
                              <th>NOMBRE DEL TRAMITE</th>

                            </tr>
                          </thead>
                          <tbody>
                            @foreach($tramites as $tram)
                              @if($tram->tipo==="ADMIN")
                              <tr>
                                <td id="idTipoTramite">

                                  <a class="btn btn-primary btn-sm" onclick="getTabGeneral({{$tram->ID_TRAMITE}})">
                                  <i id="tramite" class="fa fa-square-o"></i>
                                  </a>
                                </td>
                                <td id="nomTramite"><b>{{$tram->NOMBRE_TRAMITE}}</b></td>
                              </tr>
                              @endif
                            @endforeach

                          </tbody>
                        </table>
                      </div>
                  </div>
                    <div class="tab-pane fade" id="panel-medico">
                      <div class="table-responsive">
                        <table class="table table-hover" id="dt-tramites">
                          <thead>
                            <tr>
                              <th></th>
                              <th>NOMBRE DEL TRAMITE</th>

                            </tr>
                          </thead>
                          <tbody>
                            @foreach($tramites as $tram)
                              @if($tram->tipo==="MEDI")
                              <tr>
                                <td id="idTipoTramite">

                                  <a class="btn btn-primary btn-sm" onclick="getTabGeneral({{$tram->ID_TRAMITE}})">
                                  <i id="tramite" class="fa fa-square-o"></i>
                                  </a>
                                </td>
                                @if($tram->ID_TRAMITE==27)
                                  <td id="nomTramite"><b>ACTUALIZACION DE INSERTO</b></td>
                                @elseif($tram->ID_TRAMITE==29)
                                  <td id="nomTramite"><b>ACTUALIZACION DE MONOGRAF&Iacute;A</b></td>
                                @else
                                  <td id="nomTramite"><b>{{$tram->NOMBRE_TRAMITE}}</b></td>
                                @endif
                              </tr>
                              @endif
                            @endforeach

                          </tbody>
                        </table>
                      </div>
                  </div>
                  <div class="tab-pane fade" id="panel-quimico">
                      <div class="table-responsive">
                        <table class="table table-hover" id="dt-tramites">
                          <thead>
                            <tr>
                              <th></th>
                              <th>NOMBRE DEL TRAMITE</th>

                            </tr>
                          </thead>
                          <tbody>
                            @foreach($tramites as $tram)
                              @if($tram->tipo==="QUIM")
                              <tr>
                                <td id="idTipoTramite">

                                  <a class="btn btn-primary btn-sm" onclick="getTabGeneral({{$tram->ID_TRAMITE}})">
                                  <i id="tramite" class="fa fa-square-o"></i>
                                  </a>
                                </td>
                                <td id="nomTramite"><b>{{$tram->NOMBRE_TRAMITE}}</b></td>
                              </tr>
                              @endif
                            @endforeach

                          </tbody>
                        </table>
                      </div>
                  </div>
                  <div class="tab-pane fade" id="panel-juridico">
                      <div class="table-responsive">
                        <table class="table table-hover" id="dt-tramites">
                          <thead>
                            <tr>
                              <th></th>
                              <th>NOMBRE DEL TRAMITE</th>

                            </tr>
                          </thead>
                          <tbody>
                            @foreach($tramites as $tram)
                              @if($tram->tipo==="JURI")
                              <tr>
                                <td id="idTipoTramite">

                                  <a class="btn btn-primary btn-sm" onclick="getTabGeneral({{$tram->ID_TRAMITE}})">
                                  <i id="tramite" class="fa fa-square-o"></i>
                                  </a>
                                </td>
                                <td id="nomTramite"><b>{{$tram->NOMBRE_TRAMITE}}</b></td>
                              </tr>
                              @endif
                            @endforeach

                          </tbody>
                        </table>
                      </div>
                  </div>
              </div>
            </div>
            </div>
          </div>

        </div>

        @include('registro.tabs.detalletramite')


      </div> <!-- End tab panes -->

    </div>
  </div>
</div>
@endsection

@section('js')
{!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!}
{!! Html::script('js/selectProducto.js') !!}
{!! Html::script('js/registrov/rvJs/tramitesRv.js') !!}
<script type="text/javascript">


  var tramites = {!!$tramites!!};
  var documentos = {!!$documentos!!};
  var tramitesDoc = {!!$tramitesDoc!!};

  var id_tramite;
  var idProducto;
  var validado=0;
  var revisado=0;
  var denegado61=0;
  var presentaciones=0;
  var presentxt='';
  var emp1val;
  var emp1txt;
  var mat1txt;
  var mat1val;

  var emp2val;
  var emp2txt;
  var mat2val;

  var emp3val;
  var emp3txt;
  var mat3val;

  var fabricantes='';

$(document).ready(function(){



  $('#guardar').hide();
  exito={!! (Session::has('msnExito')!=null)?Session::has('msnExito'):0!!};
  error={!! (Session::has('msnError')!=null)?Session::has('msnError'):0!!};

  if(error==1){
    $("#myModal").modal('hide');
    $("#imprimirModal").modal('hide');

  }

  if(exito==1){
    $("#myModal").modal('hide');
    $("#imprimirModal").modal('show');
  }
  else if(exito==0){
    $("#myModal").modal('show');
    $("#imprimirModal").modal('hide');
  }

  $('#cerrar1').click(function(event){
    window.location.href = '{{route("nueva.solicitud")}}';

});



    $('#panel-mandamiento').hide();

    $('#btnBuscarProducto').click(function(event) {

          perfil=$("#perfil option:selected" ).val();
          if(perfil==='0'){
            alertify.alert('Mensaje del Sistema','Seleccione un titulo para este trámite.');
          }
          else{
          var dtproductos = $('#dt-producto').DataTable({
                              processing: true,
                              filter:true,
                              serverSide: true,
                              destroy: true,
                              pageLength: 5,
                              ajax: {
                                url: "{{route('dt.data.prod')}}",
                                data: function (d) {
                                  d.perfil=perfil;
                                  d.idTramite=id_tramite;
                                }

                              },
                              columns:[
                                      {
                                          "className":      'details-control',
                                          "orderable":      false,
                                          "data":           null,
                                          "defaultContent": '',
                                          searchable:false
                                      },
                                      {data: 'idProducto',name:'vwprod.idProducto'},
                                      {data: 'nombreComercial',name:'vwprod.nombreComercial'},
                                      {       searchable: false,
                                              "mData": null,
                                              "bSortable": false,
                                              "mRender": function (data,type,full) {
                                                  return '<a class="btn btn-primary btn-sm" data-dismiss="modal" onclick="selectProducto(\''+data.idProducto+'\',\''+data.nombreComercial+'\',\''+data.tipoProd+'\',\''+data.vigenteHasta+'\',\''+data.ultimaRenovacion+'\');" >' + '<i class="fa fa-check-square-o"></i>' + '</a>';

                                              }
                                      }

                                  ],
                             language: {
                              processing: '<div class=\"dlgwait\"></div>',
                              "url": "{{ asset('plugins/datatable/lang/es.json') }}"

                          },
          });

          // Add event listener for opening and closing details
          $('#dt-producto tbody').on('click', 'td.details-control', function (e) {
              var $link = $(e.target);
              e.preventDefault();
              var tr = $(this).closest('tr');
              var row = dtproductos.row( tr );

              if ( row.child.isShown() ) {
                  // This row is already open - close it
                  row.child.hide();
                  tr.removeClass('shown');
              }
              else {
                  row.child(format(row.data())).show();
                  tr.addClass('shown');

              }
          });



          $('#dlgProductos').modal('toggle');
        }
        });


      $('#btnAddPresent').click(function(event) {
            $('#dlgAddPresent').modal('toggle');


      });

      $('#dlgAddPresent').on('shown.bs.modal', function (e) {

              $('#empa1').on('change', function() {
              if(this.value==0){
              }
              else{
                //alert(this.value);
                emp1txt=$("#empa1 option:selected" ).text();
                emp1val=$("#empa1 option:selected" ).val();
                if(emp1val!=0 && mat1val!=0 ){
                        if(emp1val==null &&  mat1val==null)
                          console.log('no debe de poder agregar secundaria');
                        else
                          console.log(mat1val);
                          //$('#sec2').prop("disabled", false);

                }
              }
              });

              $('#mat1').on('change', function() {
                  if(this.value==0){
                  }
                  else{
                    mat1txt=$("#mat1 option:selected" ).text();
                    mat1val=$("#mat1 option:selected" ).val();
                    if(emp1val!=0 && mat1val!=0 ){
                        if(emp1val==null && mat1val==null)
                          console.log('no debe de poder agregar secundaria');
                        else
                          if($('#por1').val())
                              $('#sec2').prop("disabled", false);
                              // console.log(emp1val);
                        }
                    }
              });


            $('#sec2').click(function() {

             if($('#sec2').is(':checked')){
                $("#empa2").removeAttr('disabled');
                $("#por2").removeAttr('disabled');
                $("#mat2").removeAttr('disabled');
                $('#mat2').val(emp1txt);
                $("#mat2").attr('readonly','true');


              }
            else{
              $("#empa2").attr('disabled','disabled');
              $("#por2").attr('disabled','disabled');
              $("#mat2").attr('disabled','disabled');
            }
            });


            $('#empa2').on('change', function() {
              if(this.value==0){
              }
              else{

                emp2txt=$("#empa2 option:selected" ).text();
                emp2val=$("#empa2 option:selected" ).val();
                mat2val=emp1val;
                $('#ter3').prop("disabled", false);
              }
            });


            $('#ter3').click(function() {

             if($('#ter3').is(':checked')){
                $("#empa3").removeAttr('disabled');
                $("#por3").removeAttr('disabled');
                $("#mat3").removeAttr('disabled');
                $('#mat3').val(emp2txt);
                $("#mat3").attr('readonly','true');
              }
            else{
              $("#empa3").attr('disabled','disabled');
              $("#por3").attr('disabled','disabled');
              $("#mat3").attr('disabled','disabled');
            }
            });

            $('#empa3').on('change', function() {
              if(this.value==0){
              }
              else{

                emp3txt=$("#empa3 option:selected" ).text();
                emp3val=$("#empa3 option:selected" ).val();
                mat3val=emp2val;
              }
            });


        });



    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    $('.datepicker').datepicker({format: 'dd-mm-yyyy',onRender: function(date) {
            return date.valueOf() >= now.valueOf() ? 'disabled' : '';
        }}).on('changeDate',function(){
        $(this).datepicker('hide');
    });

    $('#fabricantes').on('click', 'input[type="checkbox"]', function () {
        if ($(this).is(':checked')) {
            idFab = $(this).val();
            var checked = $(this);
            $('#fabricantes tbody tr td input[type="checkbox"]').each(function () {
                $(this).not(checked).prop('disabled', true);
            });
        }
        else {
            $('#fabricantes tbody tr td input[type="checkbox"]').each(function () {
                $(this).not(checked).prop('disabled', false);
            });
        }
    });

});

  /* Formatting function for row details - modify as you need */
  function format (d) {
      var url = '{{ route("fabricantes.prod", ":idProducto") }}';
      url = url.replace(':idProducto', d.idProducto);

      var table= '<table id="'+d.idProducto+'"  style="font-size:12px;" width="100%">';

      $.ajax({
          url:   url,
          type:  'get',
          async: false,
          success:  function (r){
              console.log(r);

              $('#'+d.idProducto).empty();
              $.each(r, function(i, value) {

                  table+= '<tr>'+
                      '<td><b>FABRICANTE:<b>&nbsp;&nbsp;</td>'+
                      '<td>'+value.nombre+'&nbsp;&nbsp;</td>'+
                      '<td><b>TIPO:<b>&nbsp;&nbsp;</td>'+
                      '<td>'+ value.tipo +'&nbsp;&nbsp;</td>'+
                      '<td><b>VIGENCIA:<b>&nbsp;&nbsp;</td>'+
                      '<td>'+ value.vigencia_hasta +'&nbsp;&nbsp;</td>'+
                      '<td><b>RENOVACIÓN:<b>&nbsp;&nbsp;</td>'+
                      '<td>'+ value.ULTIMO_PAGO +'&nbsp;&nbsp;</td>'+
                      '</tr>';
                  $('#'+d.idProducto).append(table);
              });

          },
          error: function(r){
              // Error...
              var errors = r.responseJSON;
              console.log(errors);
              alertify.alert("Mensaje de sistema",errors.message);
          }
      });

      table+='</table>';

      return table;

  }

$('#btnDeletePres').click(function(event){

});


$('#btnAddPresentacion').click(function(event) {
      $('#plusPresent').hide();
      $('#tramite21').append("<div id='newPresentacion'></div>");
      var por1=$("#por1" ).val();
      var por2=$("#por2" ).val();
      var por3=$("#por3" ).val();
      var accesorios = $('#accesorios').val().toUpperCase();
      console.log(accesorios);
      if(emp1val==null){
          alertify.alert('Debe seleccionar el empaque');
      }
      else{
        if(emp1val==9 || emp1val==10){
            $('#documentos').append('<tr id="47"><td width="1%"><input type="hidden"  name="tipoDocumento[]" value="47"></td><td width="49%">Distribución alveolar del empaque primario.</td><td width="50%"><input type="file" id="docs" name="files[47]" required></td></tr>');
        }
        //console.log(por1);
          var tipoP=$("#tipoP option:selected" ).text();
          var tipos=$("#tipoP option:selected" ).val();
           var idmat = $("#material option:selected" ).val() === undefined ? '0' : $("#material option:selected" ).val();
          var nom_mat=$("#material option:selected" ).text();

           var idcolor = $("#material option:selected" ).val() === undefined ? '0' : $("#color option:selected" ).val();
          var nom_color=$("#color option:selected" ).text();

          if(emp1val!=null && emp1txt!=null && por1!=null && mat1txt!=null)
            if(emp2val!=null && emp2txt!=null){
              if(emp3val!=null && emp3txt!=null){
                $('#presentacion').append('<tr><td>'+emp3txt+' X '+por3+' '+emp2txt+' X '+por2+' '+emp1txt+' X '+por1+' '+mat1txt+' '+accesorios+'</td><td>'+tipoP+'</td><td>'+nom_mat+'</td></tr>');
                $('#newPresentacion').append('<input type="hidden" id="emp1" name="emp1[]" value='+emp1val+'><input type="hidden" name="cont1[]" value='+mat1val+'><input type="hidden" name="cant1[]" value='+por1+'>');
                $('#newPresentacion').append('<input type="hidden" name="emp2[]" value='+emp2val+'><input type="hidden" name="cont2[]" value='+mat2val+'><input type="hidden" name="cant2[]" value='+por2+'>');
                $('#newPresentacion').append('<input type="hidden" name="emp3[]" value='+emp3val+'><input type="hidden" name="cont3[]" value='+mat3val+'><input type="hidden" name="cant3[]" value='+por3+'>');
                $('#newPresentacion').append('<input type="hidden" name="accesorios" value="'+ accesorios +'">');
                $('#newPresentacion').append('<input type="hidden" name="idmat" value='+idmat+'>');
                $('#newPresentacion').append('<input type="hidden" name="nom_mat" value='+nom_mat+'>');
                $('#newPresentacion').append('<input type="hidden" name="idcolor" value='+idcolor+'>');
                $('#newPresentacion').append('<input type="hidden" name="nom_color" value='+nom_color+'>');
                $('#newPresentacion').append('<input type="hidden" name="present[]" value="'+emp3txt+' X '+por3+' '+emp2txt+' X '+por2+' '+emp1txt+' X '+por1+' '+mat1txt+' '+accesorios+'"><input type="hidden" name="tipo[]" value="'+tipoP+'"><input type="hidden" name="tipos[]" value="'+tipos+'">');


              }
              else{
                  $('#presentacion').append('<tr><td>'+emp2txt+' X '+por2+' '+emp1txt+' X '+por1+' '+mat1txt+'</td><td>'+tipoP+'</td><td>'+nom_mat+'</td></tr>');
                $('#newPresentacion').append('<input type="hidden" name="emp1[]" value='+emp1val+'><input type="hidden" name="cont1[]" value='+mat1val+'><input type="hidden" name="cant1[]" value='+por1+'>');
                $('#newPresentacion').append('<input type="hidden" name="emp2[]" value='+emp2val+'><input type="hidden" name="cont2[]" value='+mat2val+'><input type="hidden" name="cant2[]" value='+por2+'>');
                $('#newPresentacion').append('<input type="hidden" name="accesorios" value="'+ accesorios +'">');
                $('#newPresentacion').append('<input type="hidden" name="idmat" value='+idmat+'>');
                $('#newPresentacion').append('<input type="hidden" name="nom_mat" value='+nom_mat+'>');
                $('#newPresentacion').append('<input type="hidden" name="idcolor" value='+idcolor+'>');
                $('#newPresentacion').append('<input type="hidden" name="nom_color" value='+nom_color+'>');
                 $('#newPresentacion').append('<input type="hidden" name="present[]" value="'+emp2txt+' X '+por2+' '+emp1txt+' X '+por1+' '+mat1txt+' '+accesorios+'"><input type="hidden" name="tipo[]" value="'+tipoP+'"><input type="hidden" name="tipos[]" value="'+tipos+'">')
              }
            }
            else {
                $('#presentacion').append('<tr><td>'+emp1txt+' X '+por1+' '+mat1txt+'</td><td>'+tipoP+'</td><td>'+nom_mat+'</td></tr>');
                $('#newPresentacion').append('<input type="hidden" name="emp1[]" value='+emp1val+'><input type="hidden" name="cont1[]" value='+mat1val+'><input type="hidden" name="cant1[]" value='+por1+'>');
                $('#newPresentacion').append('<input type="hidden" name="accesorios" value="'+ accesorios +'">');
                $('#newPresentacion').append('<input type="hidden" name="idmat" value='+idmat+'>');
                $('#newPresentacion').append('<input type="hidden" name="nom_mat" value='+nom_mat+'>');
                $('#newPresentacion').append('<input type="hidden" name="idcolor" value='+idcolor+'>');
                $('#newPresentacion').append('<input type="hidden" name="nom_color" value='+nom_color+'>');
                 $('#newPresentacion').append('<input type="hidden" name="present[]" value="'+emp1txt+' X '+por1+' '+mat1txt+' '+accesorios+'"><input type="hidden" name="tipo[]" value="'+tipoP+'"><input type="hidden" name="tipos[]" value="'+tipos+'">');
            }

         $('#empa1 option').each(function() {
          if ( $(this).val() != '0' ) {
              $(this).remove();
          }
        });

         $("#empa1").val("0").change();

         $('#mat1 option').each(function() {
          if ( $(this).val() != '0' ) {
              $(this).remove();
          }
        });
         $('#material option').each(function() {
              $(this).remove();
        });
         $('#color option').each(function() {
              $(this).remove();
        });

         $("#mat1").val("0").change();
         $("#por1").val("");
         $("#empa2").val("0").change();
         $("#mat2").val("")
         $("#por2").val("");
         $("#empa2").attr('disabled','disabled');
         $("#por2").attr('disabled','disabled');
         $("#mat2").attr('disabled','disabled');
         $("#empa3").val("0").change();
         $("#mat3").val("");
         $("#por3").val("");
         $("#accesorios").val("");
         $("#empa3").attr('disabled','disabled');
         $("#por3").attr('disabled','disabled');
         $("#mat3").attr('disabled','disabled');
         mat1val=void 0;
         mat2val=void 0;
         emp1val=void 0;
         emp2val=void 0;
        $('#sec2').attr('checked', false); // Unchecks it
        $('#ter3').attr('checked', false); // Unchecks it
     }
});

function alertaProducto(){
  alertify.alert("Mensaje de sistema","Este producto no tiene vigente su anualidad o renovacion");
}
function selectProducto(id_producto,nombre_comercial,tipo_prod,vigencia,renovacion){
    var url = '{{ route("validarProducto", ":idProducto") }}';
    url = url.replace(':idProducto', id_producto);

    if(validarFechasProductos(id_producto,url)) {

        if (id_tramite == 61) {
            var idProducto = id_producto;
            var token = $('#token').val();
            if (idProducto != undefined) {
                var table = document.getElementById("fabricantes");
                //or use :  var table = document.all.tableid;
                for (var i = table.rows.length - 1; i > 0; i--) {
                    table.deleteRow(i);
                }

                $.ajax({
                    data: 'idProducto=' + idProducto + '&_token=' + token,
                    url: "{{ route('get.fabricantes') }}",
                    type: 'post',
                    success: function (r) {
                        console.log(r);
                        if (r.status == 200) {
                            if (r.data.length <= 1) {
                                $('#fabricantes').append('<tr id=' + r.data[0].id_fabricante + '><td></td><td>' + r.data[0].id_fabricante + '</td><td>' + r.data[0].nombre + '</td><td><h4><span class="label label-info">' + r.data[0].tipo + '</span></h4></td></tr>');

                                alertify.alert("Mensaje de sistema", "Para realizar este tramite se necesitan al menos dos fabricantes en el producto.");
                                $denegado61 = 1;
                                if ($denegado61 == 1) {
                                    $('#guardar').hide();
                                }

                            }
                            else {
                                $('#guardar').show();
                                for (w = 0; w < r.data.length; w++) {
                                    if (r.data[w].tipo === 'Principal') {
                                        $('#fabricantes').append('<tr id=' + r.data[w].id_fabricante + '><td><input type="checkbox"  name="idFab[]" value="' + r.data[w].id_fabricante + '"></td><td>' + r.data[w].id_fabricante + '</td><td>' + r.data[w].nombre + '</td><td><h4><span class="label label-info">' + r.data[w].tipo + '</span></h4></td></tr>');
                                    }
                                    else {
                                        $('#fabricantes').append('<tr id=' + r.data[w].id_fabricante + '><td><input type="checkbox"  name="idFab[]" value="' + r.data[w].id_fabricante + '"></td><td>' + r.data[w].id_fabricante + '</td><td>' + r.data[w].nombre + '</td><td><h4><span class="label label-warning">' + r.data[w].tipo + '</span></h4></td></tr>');
                                    }
                                }
                            }

                        }
                        else if (r.status == 400) {
                            alertify.alert("Mensaje de sistema - Error", r.message);
                        } else if (r.status == 401) {
                            alertify.alert("Mensaje de sistema", r.message, function () {
                                window.location.href = r.redirect;
                            });
                        } else {//Unknown
                            alertify.alert("Mensaje de sistema - Error", "Oops!. Algo ha salido mal, contactar con el adminsitrador del sistema para poder continuar!");
                            console.log(r);
                        }
                    },
                    error: function (data) {
                        // Error...
                        var errors = $.parseJSON(data.responseText);
                        console.log(errors);
                        $.each(errors, function (index, value) {
                            $.gritter.add({
                                title: 'Error',
                                text: value
                            });
                        });
                    }
                });
            }
        }
        else if (id_tramite == 66 || id_tramite == 21 || id_tramite == 37 || id_tramite == 38 || id_tramite == 39 || id_tramite == 51) {
            //limpiamos los selected y la tabla en donde se presentan las presentaciones
            $('.presentacion').empty().append("<option value=''>Seleccione una presentacion</option>");
            $("#tablelotes tbody tr").remove();
            $("#dt-presentaciones tbody tr").remove();
            presentxt = '';
            var table = document.getElementById("presentacion");
            //or use :  var table = document.all.tableid;
            for (var i = table.rows.length - 2; i > 0; i--) {
                table.deleteRow(i);
            }
            $('#newPresentacion').remove();
            //document.getElementById('color').value = '';
            var idProducto = id_producto;
            var token = $('#token').val();
            if (idProducto != undefined) {


                $.ajax({
                    data: 'idProducto=' + idProducto + '&_token=' + token,
                    url: "{{ route('get.presentaciones') }}",
                    type: 'post',
                    success: function (r) {
                        if (r.status == 200) {
                            if (id_tramite == 66) {
                                console.log(r.data);
                                for (j = 0; j < r.data.length; j++) {
                                    presentxt += "<option value=" + r.data[j].ID_PRESENTACION + ">" + r.data[j].PRESENTACION_COMPLETA + " " + r.data[j].ACCESORIOS + "</option>";
                                }
                                console.log(presentxt);
                            }

                            else if (id_tramite == 21) {
                                cleanModal();
                                $('#material option').each(function () {
                                    $(this).remove();
                                });

                                if (r.data.length > 0) {
                                    $('#guardar').show();
                                    $('#plusPresent').show();
                                    for (j = 0; j < r.data.length; j++) {
                                        $('.dt-presentaciones').append('<tr><td>' + r.data[j].PRESENTACION_COMPLETA + '</td><td>' + r.data[j].ACCESORIOS + '</td></tr>');
                                    }

                                    $.ajax({
                                        data: 'idProducto=' + id_producto + '&_token=' + token,
                                        url: "{{ route('get.empaques') }}",
                                        type: 'post',
                                        success: function (r) {
                                            if (r.status == 200) {
                                                //console.log(r);

                                                for (j = 0; j < r.data.length; j++) {
                                                    /*if(r.data[j].ID_EMPAQUE==9 || r.data[j].ID_EMPAQUE==10){
                                                      $('#documentos').append('<tr id="47"><td width="1%"><input type="hidden"  name="tipoDocumento[]" value="47"></td><td width="49%">Distribución alveolar del empaque primario.</td><td width="50%"><input type="file" id="docs" name="files[47]" required></td></tr>');
                                                    }
                                                    else{
                                                      $('#47').remove();
                                                    }*/
                                                    $('#empa1').append("<option value='" + r.data[j].ID_EMPAQUE + "'>" + r.data[j].NOMBRE_EMPAQUE + "</option>");

                                                }
                                                if (r.color != null && r.color.length > 0) {
                                                    for (j = 0; j < r.color.length; j++) {
                                                        if (j == 0) {
                                                            $('#color').append("<option value='" + r.color[j].ID_COLOR + "' selected>" + r.color[j].NOMBRE_COLOR + "</option>");
                                                        }
                                                        else {
                                                            $('#color').append("<option value='" + r.color[j].ID_COLOR + "'>" + r.color[j].NOMBRE_COLOR + "</option>");
                                                        }
                                                    }
                                                }
                                                if (r.mat != null && r.mat.length > 0) {
                                                    for (j = 0; j < r.mat.length; j++) {
                                                        if (j == 0) {
                                                            $('#material').append("<option value='" + r.mat[j].ID_MATERIAL + "' selected>" + r.mat[j].NOMBRE_MATERIAL + "</option>");
                                                        }
                                                        else {
                                                            $('#material').append("<option value='" + r.mat[j].ID_MATERIAL + "'>" + r.mat[j].NOMBRE_MATERIAL + "</option>");
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    });

                                    $.ajax({
                                        data: 'idProducto=' + id_producto + '&_token=' + token,
                                        url: "{{ route('get.contenidos.prod') }}",
                                        type: 'post',
                                        success: function (r) {
                                            if (r.status == 200) {
                                                //console.log(r.data);

                                                for (j = 0; j < r.data.length; j++) {
                                                    $('#mat1').append("<option value='" + r.data[j].ID_CONTENIDO + "'>" + r.data[j].NOMBRE_CONTENIDO + "</option>");
                                                }
                                            }
                                        }
                                    });

                                    $.get('{{route('get.contenidos')}}', function (data) {
                                        //console.log(data.data);
                                        for (j = 0; j < data.data.length; j++) {

                                            $('#mat2').append("<option value='" + data.data[j].ID_CONTENIDO + "'>" + data.data[j].NOMBRE_CONTENIDO + "</option>");
                                            $('#mat3').append("<option value='" + data.data[j].ID_CONTENIDO + "'>" + data.data[j].NOMBRE_CONTENIDO + "</option>");
                                        }
                                    });

                                    $.get('{{route('get.all.empaques')}}', function (data) {
                                        //console.log(data.data);
                                        for (j = 0; j < data.data.length; j++) {
                                            $('#empa2').append("<option value='" + data.data[j].ID_EMPAQUE + "'>" + data.data[j].NOMBRE_EMPAQUE + "</option>");
                                            $('#empa3').append("<option value='" + data.data[j].ID_EMPAQUE + "'>" + data.data[j].NOMBRE_EMPAQUE + "</option>");
                                        }
                                    });
                                }
                                else {
                                    alertify.alert("Mensaje de sistema", "Para realizar este trámite se necesita que producto tenga una o más presentaciones.");
                                    $('#guardar').hide();
                                }
                            }
                            else if (id_tramite == 51) {
                                if (r.data.length == 0) {
                                    presentaciones = 0;
                                    alertify.alert("Mensaje de sistema", "Para realizar este trámite se necesita que producto tenga una o más presentaciones.");
                                }
                                else {
                                    presentaciones = r.data.length;
                                    for (j = 0; j < r.data.length; j++) {
                                        $('.dt-presentaciones').append('<tr><td><input type="checkbox" class="chkPrese" name="idPresentacion[]" value="' + r.data[j].ID_PRESENTACION + '"></td><td>' + r.data[j].PRESENTACION_COMPLETA + '</td><td>' + r.data[j].ACCESORIOS + '</td></tr>');
                                    }
                                }
                            }
                            else if (id_tramite == 37 || id_tramite == 38 || id_tramite == 39) {
                                if (r.data.length == 0) {
                                    presentaciones = 0;
                                    $('#validar').hide();
                                    alertify.alert("Mensaje de sistema", "Para realizar este trámite se necesita que producto tenga una o más presentaciones.");
                                }
                                else {
                                    presentaciones = r.data.length;

                                    for (j = 0; j < r.data.length; j++) {
                                        $('.dt-presentaciones').append('<tr><td><input type="checkbox" class="chkPrese" name="idPresentacion[]" value="' + r.data[j].ID_PRESENTACION + '"></td><td>' + r.data[j].PRESENTACION_COMPLETA + '</td><td>' + r.data[j].ACCESORIOS + '</td></tr>');
                                    }

                                    $.ajax({
                                        data: 'idProducto=' + id_producto + '&_token=' + token,
                                        url: "{{ route('get.empaques') }}",
                                        type: 'post',
                                        success: function (r) {
                                            if (r.status == 200) {
                                                //console.log(r);

                                                for (j = 0; j < r.data.length; j++) {
                                                    if (r.data[j].ID_EMPAQUE == 9 || r.data[j].ID_EMPAQUE == 10) {
                                                        if (id_tramite == 37 || id_tramite == 38) {
                                                            $('#documentos').append('<tr id="47"><td width="1%"><input type="hidden"  name="tipoDocumento[]" value="47"></td><td width="49%">Distribución alveolar del empaque primario.</td><td width="50%"><input type="file" id="docs" name="files[47]" required></td></tr>');
                                                        }
                                                    }
                                                    else {
                                                        $('#47').remove();
                                                    }

                                                }

                                            }
                                        }
                                    });
                                    $('#validar').show();
                                }
                            }

                        }
                        else if (r.status == 400) {
                            alertify.alert("Mensaje de sistema - Error", r.message);
                        } else if (r.status == 401) {
                            alertify.alert("Mensaje de sistema", r.message, function () {
                                window.location.href = r.redirect;
                            });
                        } else {//Unknown
                            alertify.alert("Mensaje de sistema - Error", "Oops!. Algo ha salido mal, contactar con el adminsitrador del sistema para poder continuar!");
                            //console.log(r);
                        }
                    },
                    error: function (data) {
                        // Error...
                        var errors = $.parseJSON(data.responseText);
                        //console.log(errors);
                        $.each(errors, function (index, value) {
                            $.gritter.add({
                                title: 'Error',
                                text: value
                            });
                        });
                    }
                });
            }

        }
        else if (id_tramite == 36) {
            var table = document.getElementById("exportprod");
            for (var i = table.rows.length - 1; i > 0; i--) {
                table.deleteRow(i);
            }

            var idProducto = id_producto;
            var token = $('#token').val();
            if (idProducto != undefined) {


                $.ajax({
                    data: 'idProducto=' + idProducto + '&_token=' + token,
                    url: "{{ route('get.export') }}",
                    type: 'post',
                    success: function (r) {
                        if (r.status == 200) {
                            //console.log(r.data);
                            for (j = 0; j < r.data.length; j++) {
                                $('#exportprod').append('<tr><td>' + r.data[j].nombre_exportacion + '</td><td>' + r.data[j].nombre_pais + '</td></tr>');

                            }

                        }
                        else if (r.status == 400) {
                            alertify.alert("Mensaje de sistema - Error", r.message);
                        } else if (r.status == 401) {
                            alertify.alert("Mensaje de sistema", r.message, function () {
                                window.location.href = r.redirect;
                            });
                        } else {//Unknown
                            alertify.alert("Mensaje de sistema - Error", "Oops!. Algo ha salido mal, contactar con el adminsitrador del sistema para poder continuar!");
                            //console.log(r);
                        }
                    },
                    error: function (data) {
                        // Error...
                        var errors = $.parseJSON(data.responseText);
                        console.log(errors);
                        $.each(errors, function (index, value) {
                            $.gritter.add({
                                title: 'Error',
                                text: value
                            });
                        });
                    }
                });
            }

        }

        else if (id_tramite == 67) {
            var table = document.getElementById("labs");
            for (var i = table.rows.length - 1; i > 0; i--) {
                table.deleteRow(i);
            }

            var idProducto = id_producto;
            var token = $('#token').val();
            if (idProducto != undefined) {


                $.ajax({
                    data: 'idProducto=' + idProducto + '&_token=' + token,
                    url: "{{ route('get.labs') }}",
                    type: 'post',
                    success: function (r) {
                        if (r.status == 200) {
                            //console.log(r.data);
                            if (r.data.length == 0) {
                                alertify.alert("Mensaje de sistema", "Para realizar este trámite se necesitan que el producto tenga al menos un laboratorio");
                            }
                            else {
                                for (q = 0; q < r.data.length; q++) {
                                    $('#labs').append('<tr id=' + r.data[q].ID_LABORATORIO + '><td><input type="checkbox" class="chkGroup" id="checkLab" name="idLabs[]" onclick="checkeado()"  value=' + r.data[q].ID_LABORATORIO + '></td><td>' + r.data[q].nombreComercial + '</td><td>' + r.data[q].nombre_pais + '</td></tr>');

                                }
                            }

                        }
                        else if (r.status == 400) {
                            alertify.alert("Mensaje de sistema - Error", r.message);
                        } else if (r.status == 401) {
                            alertify.alert("Mensaje de sistema", r.message, function () {
                                window.location.href = r.redirect;
                            });
                        } else {//Unknown
                            alertify.alert("Mensaje de sistema - Error", "Oops!. Algo ha salido mal, contactar con el adminsitrador del sistema para poder continuar!");
                            console.log(r);
                        }
                    },
                    error: function (data) {
                        // Error...
                        var errors = $.parseJSON(data.responseText);
                        console.log(errors);
                        $.each(errors, function (index, value) {
                            $.gritter.add({
                                title: 'Error',
                                text: value
                            });
                        });
                    }
                });
            }


        }
        else if (id_tramite == 57) {
            var table = document.getElementById("dt-formafarm");
            for (var i = table.rows.length - 1; i > 0; i--) {
                table.deleteRow(i);
            }
            $('#formas').empty().append("<option value='0'>Seleccione...</option>");
            var idProducto = id_producto;
            var token = $('#token').val();
            var idFamilia;
            if (idProducto != undefined) {


                $.ajax({
                    data: 'idProducto=' + idProducto + '&_token=' + token,
                    url: "{{ route('get.formas') }}",
                    type: 'post',
                    success: function (r) {
                        if (r.status == 200) {
                            //console.log(r.data);

                            for (q = 0; q < r.data.length; q++) {

                                $('#dt-formafarm').append('<tr><td><input type="hidden" name="idForma" id="idForma" value=' + r.data[q].id_forma_farmaceutica + '>' + r.data[q].id_forma_farmaceutica + '</td><td><input type="hidden" name="nomForma" id="nomForma" value="' + r.data[q].nombre_forma_farmaceutica + '">' + r.data[q].nombre_forma_farmaceutica + '</td></tr>');

                                idFamilia = r.data[q].ID_FORMA_FARMACEUTICA_FAMILIA;
                            }

                            if (idProducto = 'F068108082013') {
                                dataAjax = 'idFamilia=' + idFamilia + '&idProducto=' + idProducto + '&_token=' + token;
                            }
                            else {
                                dataAjax = 'idFamilia=' + idFamilia + '&_token=' + token;
                            }

                            $.ajax({
                                data: dataAjax,
                                url: "{{ route('get.formas.far') }}",
                                type: 'post',
                                success: function (r) {
                                    if (r.status == 200) {
                                        // console.log(r.data);
                                        for (j = 0; j < r.data.length; j++) {

                                            $('#formas').append("<option value='" + r.data[j].ID_FORMA_FARMACEUTICA + "'>" + r.data[j].NOMBRE_FORMA_FARMACEUTICA + "</option>");
                                        }
                                    }
                                }
                            });

                        }
                        else if (r.status == 400) {
                            alertify.alert("Mensaje de sistema - Error", r.message);
                        } else if (r.status == 401) {
                            alertify.alert("Mensaje de sistema", r.message, function () {
                                window.location.href = r.redirect;
                            });
                        } else {//Unknown
                            alertify.alert("Mensaje de sistema - Error", "Oops!. Algo ha salido mal, contactar con el adminsitrador del sistema para poder continuar!");
                            console.log(r);
                        }
                    },
                    error: function (data) {
                        // Error...
                        var errors = $.parseJSON(data.responseText);
                        console.log(errors);
                        $.each(errors, function (index, value) {
                            $.gritter.add({
                                title: 'Error',
                                text: value
                            });
                        });
                    }
                });
            }
        }

        else if (id_tramite == 33 || id_tramite == 35 || id_tramite == 52) {
            var texttab = '';
            $('#pprop').addClass('hide');
            $('#pprof').addClass('hide');
            $('#pfab').addClass('hide');
            $('#pdist').addClass('hide');
            $('#pform').addClass('hide');
            $('#ppre').addClass('hide');
            $('#pprin').addClass('hide');
            $('#ppre').addClass('hide');
            $('#pformu').addClass('hide');
            $('#ppo').addClass('hide');
            $('#plab').addClass('hide');
            $('#pexp').addClass('hide');
            $('#chekeado').empty();
            revisado = 0;
            $('#tabs33 a[href="#panel-generales"]').tab('show');

            var idProducto = id_producto;
            var token = $('#token').val();
            $.ajax({
                data: 'idProducto=' + id_producto + '&_token=' + token,
                url: "{{route('productos.con.tramites.rv')}}",
                type: 'post',

                success: function (r) {
                    if (r.status == 200) {
                        $('#header').show();
                        var idProducto = id_producto;
                        var token = $('#token').val();
                        var url1 = "{{ route('get.generales.prod') }}";
                        getGenerales(idProducto, token, url1);

                        $('.continue').click(function (idProducto) {
                            var token = $('#token').val();

                            if ($('#prodtab > .active').next('li').find('a').text() != 'Exportadores') {
                                texttab = $('#prodtab > .active').next('li').find('a').text();
                                console.log(texttab);
                                if (texttab === 'Propietarios') {
                                    var url2 = "{{route('get.propietario.prod')}}";
                                    getPropietarioByProd(id_producto, token, url2);
                                }
                                else if (texttab === 'Profesional') {
                                    var url3 = "{{route('get.profesional.prod')}}";
                                    getProfesionalByProd(id_producto, token, url3);
                                }
                                else if (texttab === 'Fabricante') {
                                    var url4 = "{{route('get.fabricantes.prod')}}";
                                    getFabricantesByProd(id_producto, token, url4);
                                }
                                else if (texttab === 'Importadores') {
                                    var url5 = "{{route('get.dist.prod')}}";
                                    getDistribuidoresByProd(id_producto, token, url5);
                                }
                                else if (texttab === 'Forma Farmaceuticas') {
                                    var url6 = "{{ route('get.formfarma.prod') }}";
                                    getFormaFarm(id_producto, token, url6);
                                }
                                else if (texttab === 'Presentaciones') {
                                    var url11 = "{{route('get.presentaciones.prod')}}";
                                    getPresentacion(id_producto, token, url11);
                                }
                                else if (texttab === 'Principios Activos') {
                                    var url10 = "{{route('get.principiosa.prod')}}";
                                    getPrincipiosA(id_producto, token, url10);
                                }
                                else if (texttab === 'Fómula') {
                                    var url12 = "{{route('get.formula.prod')}}";
                                    getFormula(id_producto, token, url12);
                                }
                                else if (texttab === 'Poderes') {
                                    var url9 = "{{route('get.poderes.prod')}}";
                                    getPoderes(id_producto, token, url9);
                                }
                                else if (texttab === 'Lab. Acondiconadores') {
                                    var url7 = "{{route('get.labacondi.prod')}}";
                                    getLaboratorio(id_producto, token, url7);
                                }
                                $('#prodtab > .active').next('li').removeClass('hide');
                                $('.nav-tabs > .active').next('li').find('a').trigger('click');
                            }
                            else if ($('#prodtab > .active').next('li').find('a').text() == 'Exportadores') {
                                $('#prodtab > .active').next('li').removeClass('hide');
                                var url8 = "{{route('get.nomexpo.prod')}}";
                                getExportadores(id_producto, token, url8);
                                $('.nav-tabs > .active').next('li').find('a').trigger('click');
                                $('#chekeado').empty();
                                $('#chekeado').append('<div class="form-check form-check-inline"><label class="form-check-label"><input class="form-check-input" type="checkbox" id="chekeado" name="chekeado" value="">   Estoy de acuerdo que la informacion del registro es acertada!</label></div>')
                            }
                        });


                        if (id_tramite == 35) {
                            var table = document.getElementById("fabricantes");
                            //or use :  var table = document.all.tableid;
                            for (var i = table.rows.length - 1; i > 0; i--) {
                                table.deleteRow(i);
                            }


                            var url1 = "{{ route('get.fabricantes') }}";
                            getFabricantesProd(idProducto, id_tramite, token, url1);
                            /*
                            if($('#fabricantes tbody tr').length == 0){
                              alertify.alert("Mensaje de sistema","No puede realizar este tramite con el producto seleccionado, debido a que no poseé fabricantes alternos.");
                              $('#guardar').hide();
                            }*/
                        }
                    }
                    else if (r.status == 400) {

                        $('#header').hide();
                        $('#guardar').hide();
                        alertify.alert("Mensaje de sistema", r.message);

                    } else if (r.status == 404) {
                        $('#guardar').hide();
                        alertify.alert("Mensaje de sistema", r.message, function () {
                        });
                    } else {//Unknown
                        $('#guardar').hide();
                        alertify.alert("Mensaje de sistema", "");
                        //console.log(r);
                    }
                },
            });

        }


        idProducto = document.getElementById('txtregistro');

        document.getElementById('txtregistro').value = id_producto;
        document.getElementById('txttipo').value = tipo_prod;
        document.getElementById("tipo").style.display = "block";
        document.getElementById('txtnombreprod').value = nombre_comercial;
        document.getElementById("nombre").style.display = "block";
        document.getElementById('color').value = '';
        document.getElementById('material').value = '';
    }
}

function checkeado(){
  $('input.chkGroup').click(function(){
    var $inputs=$('input.chkGroup');
    if ($(this).is(':checked')) {
      //console.log($('input.chkGroup').val());
      $inputs.not(this).prop('disabled',true);
    }
    else{
      $inputs.prop('disabled',false);
    }
  });
}

  //funcion para guardar solicitud
  $('#guardarSoli').click(function() {
   //console.log(id_tramite);

      if(!$('#txtregistro').val()) {
        alertify.alert("Mensaje de sistema","Debe seleccionar un producto para realizar el tramite");
      }
      else if(id_tramite==21){

           alertify.confirm("Mensaje de sistema","Esta seguro que desea procesar este trámite?", function (asc) {
             if (asc) {
                 $('#frmSolicitudRV').submit();
                 //alertify.success("Solicitud Enviada.");

             } else {
                 //alertify.error("Solicitud no enviada");
             }
            }, "Default Value").set('labels', {ok:'SI', cancel:'NO'});

      }
      else if(id_tramite==67){
         if (!$('input.chkGroup').is(':checked')) {
          alertify.alert("Mensaje de sistema","Debe seleccionar un laboratorio para realizar el tramite");
        }
        else{
          alertify.confirm("Mensaje de sistema","Esta seguro que desea procesar este trámite?", function (asc) {
         if (asc) {
             $('#frmSolicitudRV').submit();
             //alertify.success("Solicitud Enviada.");

         } else {
             //alertify.error("Solicitud no enviada");
         }
        }, "Default Value").set('labels', {ok:'SI', cancel:'NO'});
        }

      }
      else if(id_tramite==66){
          validado66=validarTramite66();
          console.log(validado66);
          if(validado66==false) {
              alertify.alert("Mensaje de sistema","Valide que todos los campos esten llenos (LOTE,UNIDAD,FECHA VENC. Y PRESENTACION)!");
          }
          else{
                alertify.confirm("Mensaje de sistema","Esta seguro que desea procesar este trámite?", function (asc) {
                 if (asc) {
                     $('#frmSolicitudRV').submit();
                     //alertify.success("Solicitud Enviada.");

                 } else {
                     //alertify.error("Solicitud no enviada");
                 }
                }, "Default Value").set('labels', {ok:'SI', cancel:'NO'});
          }
      }
      else if(id_tramite==36){
        var idPais=$( "#pais option:selected" ).val();
        if(!$('#nomexport').val()) {
          alertify.alert("Mensaje de sistema","Debe digitar el nombre de exportación para el producto");
        }
        else if(idPais==0){
          alertify.alert("Mensaje de sistema","Debe seleccionar un país de exportación");
        }
        else{

          alertify.confirm("Mensaje de sistema","Esta seguro que desea procesar este trámite?", function (asc) {
           if (asc) {
               $('#frmSolicitudRV').submit();
               //alertify.success("Solicitud Enviada.");

           } else {
               //alertify.error("Solicitud no enviada");
           }
          }, "Default Value").set('labels', {ok:'SI', cancel:'NO'});

      }
    }
    else if(id_tramite==37 || id_tramite==38 || id_tramite==39){
          var checkeado=false;
          $('input[type=checkbox].cheks-etiquetado').each(function () {
              var sList='';
              sList += "(" + $(this).val() + "-" + (this.checked ? "checked" : "not checked") + ")";
              if(this.checked){
                checkeado=true;
              }
          });

          if(checkeado){
              alertify.confirm("Mensaje de sistema","Esta seguro que desea procesar este trámite?", function (asc) {
                  if (asc) {
                      $('#frmSolicitudRV').submit();
                      //alertify.success("Solicitud Enviada.");

                  } else {
                      //alertify.error("Solicitud no enviada");
                  }
              }, "Default Value").set('labels', {ok:'SI', cancel:'NO'});
          }
          else{
              alertify.alert("Mensaje de sistema","Debe seleccionar al menos un tipo de informacion a cambiar en el etiquetado.");
          }

    }

    else{
          alertify.confirm("Mensaje de sistema","Esta seguro que desea procesar este trámite?", function (asc) {
         if (asc) {
             $('#frmSolicitudRV').submit();
             //alertify.success("Solicitud Enviada.");

         } else {
             //alertify.error("Solicitud no enviada");
         }
        }, "Default Value").set('labels', {ok:'SI', cancel:'NO'});
      }



  });

// funcion para validar el mandamiento
$('#validar').click(function(event){
  var mandamiento = $('#num_mandamiento').val();
  var token =$('#token').val();
  //console.log(mandamiento);
  $.ajax({
            data:'mandamiento='+mandamiento+'&_token='+token,
            url:   "{{route('verificar-mandamiento')}}",
            type:  'post',

            beforeSend: function() {
                $('body').modalmanager('loading');
            },
            success:  function (r){
                $('body').modalmanager('loading');
                if(r.status == 200){
                  alertify.alert("Mensaje de sistema",'El mandamiento es válido para usar en este trámite');
                  //$('#guardar').show();
                  validado=1;
                  //$('#guardar').show();
                  //console.log(revisado);
                  if(id_tramite==33 || id_tramite==35 || id_tramite==52 || id_tramite==46 || id_tramite==45){
                    if(revisado==1){
                      $('#guardar').show();
                    }
                  }
                  else{
                    $('#guardar').show();
                  }
                  //console.log(validado);
                  document.getElementById("num_mandamiento").readOnly = true;

                }
                else if (r.status == 400){
                    alertify.alert("Mensaje de sistema - Error",r.message);
                }else if(r.status == 401){
                    alertify.alert("Mensaje de sistema",r.message, function(){
                        window.location.href = r.redirect;
                    });
                }else{//Unknown
                    alertify.alert("Mensaje de sistema","Este mandamiento no ha sido pagado o ya ha sido utilizado");
                    //console.log(r);
                }
            },
            error: function(data){
                // Error...
                var errors = $.parseJSON(data.responseText);
               // console.log(errors);
                $.each(errors, function(index, value) {
                    $.gritter.add({
                        title: 'Error',
                        text: value
                    });
                });
            }
        });

});

$('#validarPoder').click(function(event){
   var numPoder= $('#numPoder').val();
   var url = "{{route('get.profesional')}}";
   var token =$('#token').val();
   validarPoderProfesional(numPoder,token,url);
});

$('#validarPoderA').click(function(event){
   var numPoder= $('#numPoderA').val();
   var url = "{{route('get.apoderado')}}";
   var token =$('#token').val();
   validarPoderApoderado(numPoder,token,url);
});



$(document).on('change', 'input[name="chekeado"]', function () {
            if (this.checked) {
              revisado=1;
              if(validado==1){
                $('#guardar').show();
              }
            }
            else {
              revisado=0;
              if(validado==1){
                $('#guardar').hide();
              }
            }
  });

//funcion para cada vez que se selecciona un trammite
function getTabGeneral(idTramite){

  document.getElementById('txtregistro').value ='';
  document.getElementById('txttipo').value ='';
  document.getElementById("tipo").style.display = "none";
  document.getElementById('txtnombreprod').value ='';
  document.getElementById("nombre").style.display = "none";
  document.getElementById('idTramite').value='';
  document.getElementById('nomexport').value='';
  document.getElementById("num_mandamiento").readOnly = false;

  cleanModal();
  $("#dt-presentaciones tbody tr").remove();

  var table = document.getElementById("exportprod");
  for(var i = table.rows.length - 1; i >= 0; i--)
  {
        table.deleteRow(i);
  }

  var table = document.getElementById("dt-formafarm");
        for(var i = table.rows.length - 1; i > 0; i--)
        {
          table.deleteRow(i);
        }
  $('#formas').empty().append("<option value='0'>Seleccione...</option>");

  validado=0;
  revisado=0;

  $('#num_mandamiento').val('');
  $('#validar').show();
  var mandamiento=0;
  $('#error').hide();
  $('#idArea').val('');
  $("#leyendTramite").text('');
  id_tramite=idTramite;
  //console.log(id_tramite);
  $('#tra46').hide();
  $('#tra45').hide();
  $('#tra33').hide();
  $('#tra57').hide();
  $('#tra29').hide();
  $('#tra66').hide();
  $('#tra36').hide();
   $('#tramite36').hide();
  $('#tra61').hide();
  $('#tra67').hide();
  $('#tra21').hide();
  $('#tra54').hide();
  $('#tra37').hide();
  $('#tramite21').hide();
   $('#panel-mandamiento').hide();
   $('#guardar').hide();
  $('#check4').hide();
  $('#titular').attr('checked', false); // Unchecks it
  $('#nomprod').attr('checked', false);
  $('#fabricante').attr('checked', false); // Unchecks it
  $('#condiciones').attr('checked', false);

   $('#sec2').attr('checked', false); // Unchecks it
   $('#ter3').attr('checked', false); // Unchecks it
  for(w=0;w<tramites.length;w++){
    if(tramites[w].ID_TRAMITE==idTramite){
      if(idTramite==27){
          $("#leyendTramite").text('SOLICITUD DE: ACTUALIZACION DE INSERTO');
      }
      else if(idTramite==29){
        $("#leyendTramite").text('SOLICITUD DE: ACTUALIZACION DE MONOGRAFÍA');
      }
      else{
      $("#leyendTramite").text('SOLICITUD DE: '+tramites[w].NOMBRE_TRAMITE);
      }
      if(tramites[w].tipo==='ADMIN'){
       // console.log(tramites[w].tipo);
        $("#leyendTipo").text('TRAMITE ADMINISTRATIVO');
        document.getElementById('idArea').value=1;
      }
      else if(tramites[w].tipo==='MEDI'){
        $("#leyendTipo").text('TRAMITE MÉDICO');
        document.getElementById('idArea').value=3;
      }
      else if(tramites[w].tipo==='QUIM'){
        $("#leyendTipo").text('TRAMITE QUÍMICO');
        document.getElementById('idArea').value=4;
      }
      else if(tramites[w].tipo==='JURI'){
        $("#leyendTipo").text('TRAMITE JURÍDICO');
        document.getElementById('idArea').value=2;
      }


     }

  }

  if(idTramite==66){
    $('#tra66').show();

  }
  else if(idTramite==61){
    $('#tra61').show();

  }
  else if(idTramite==36){

    $('#tra36').show();
     $('#tramite36').show();
    $.get('{{route('get.paises')}}', function(data){
        for(j=0;j<data.data.length;j++){
          $('#pais').append("<option value='"+data.data[j].idPais+"'>" + data.data[j].nombre + "</option>");
        }
        $('#pais').trigger('chosen:updated');
    });

    $(function() {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });

      });



  }

  else if (idTramite==54 || idTramite==64){
     $('#tra54').show();
  }

  else if(idTramite==21){
    $('#tra21').show();
    $('#tramite21').show();
    $('#plusPresent').hide();
    $('#empa1 option').each(function() {
          if ( $(this).val() != '0' ) {
              $(this).remove();
          }
    });
    $("#empa1").val("0").change();
    $('#mat1 option').each(function() {
          if ( $(this).val() != '0' ) {
              $(this).remove();
          }
    });
    $('#material option').each(function() {
              $(this).remove();
        });

    $('#color option').each(function() {
              $(this).remove();
        });

    $("#mat1").val("0").change();
    $("#por1").val("");
    $("#empa2").val("0").change();
    $("#mat2").val("")
    $("#por2").val("");
    $("#empa2").attr('disabled','disabled');
    $("#por2").attr('disabled','disabled');
    $("#mat2").attr('disabled','disabled');
    $("#empa3").val("0").change();
    $("#mat3").val("");
    $("#por3").val("");
    $("#accesorios").val("");
    $("#empa3").attr('disabled','disabled');
    $("#por3").attr('disabled','disabled');
    $("#mat3").attr('disabled','disabled');
    mat1val=void 0;
    mat2val=void 0;
    emp1val=void 0;
    emp2val=void 0;
   $('#sec2').attr('checked', false); // Unchecks it
   $('#ter3').attr('checked', false); // Unchecks it
   document.getElementById('color').value = '';
    document.getElementById('material').value = '';

  }
  else if (idTramite==51){
    $('#tra21').show();
  }
  else if(idTramite==37 || idTramite==38 || idTramite==39){
    $('#tra21').show();
    $('#tra37').show();
    $('#check4').show();

  }
  else if(idTramite==29 || idTramite==27){
      $('#tra29').show();
   }
  else if(idTramite==67){
    $('#tra67').show();
    $('#guardar').hide();
  }
  else if(idTramite==57){
    $('#tra57').show();

  }
  else if(idTramite==33 || idTramite==52 || idTramite==35){
    $('#tra33').show();
    if(idTramite==35){
      $("#headFab").text('SELECCIONE EL FABRICANTE ALTERNO:');
      $('#tra61').show();
    }
  }
  else if(idTramite==46 ){
    $('#tra46').show();
  }
  else if(idTramite==45 ){
    $('#tra45').show();
  }


  $("#documentos tr").remove();
  for(i=0;i<tramitesDoc.length;i++){
      if(tramitesDoc[i].ID_TRAMITE==idTramite){

            for(j=0;j<documentos.length;j++){
              if(documentos[j].ID_ITEM==tramitesDoc[i].ID_ITEM){
                    if(documentos[j].ID_ITEM==1){
                        $('#panel-mandamiento').show();
                        mandamiento=1;
                    }
                    else{
                      if(documentos[j].ID_ITEM==40){
                        $('#documentos').append('<tr id="'+documentos[j].ID_ITEM+'"><td width="1%" ><input type="hidden"  name="tipoDocumento[]" value="'+documentos[j].ID_ITEM+'"></td><td width="49%">Nuevas etiquetas originales del envase/empaque primario, secundario o sus proyectos vigentes.</td><td width="50%"><input type="file" id="docs" name="files['+documentos[j].ID_ITEM+']" required></td></tr>');
                        $('#footer').show();
                      }
                      else if(documentos[j].ID_ITEM==38){
                        $('#documentos').append('<tr id="'+documentos[j].ID_ITEM+'"><td width="1%" ><input type="hidden"  name="tipoDocumento[]" value="'+documentos[j].ID_ITEM+'"></td><td width="49%">Nuevas etiquetas originales del envase/empaque primario o sus proyectos vigentes.</td><td width="50%"><input type="file" id="docs" name="files['+documentos[j].ID_ITEM+']" required></td></tr>');
                        $('#footer').show();
                      }
                       else if(documentos[j].ID_ITEM==41){
                        $('#documentos').append('<tr id="'+documentos[j].ID_ITEM+'"><td width="1%" ><input type="hidden"  name="tipoDocumento[]" value="'+documentos[j].ID_ITEM+'"></td><td width="49%">Nuevas etiquetas originales del envase/empaque secundario  o sus proyectos vigentes.</td><td width="50%"><input type="file" id="docs" name="files['+documentos[j].ID_ITEM+']" required></td></tr>');
                        $('#footer').show();
                      }
                      else if(documentos[j].ID_ITEM==47){
                      }
                      else{
                        $('#documentos').append('<tr id="'+documentos[j].ID_ITEM+'"><td width="1%" ><input type="hidden"  name="tipoDocumento[]" value="'+documentos[j].ID_ITEM+'"></td><td width="49%">'+documentos[j].NOMBRE_ITEM+'</td><td width="50%"><input type="file" id="docs" name="files['+documentos[j].ID_ITEM+']" accept="application/pdf" required></td></tr>');
                        $('#footer').show();
                      }


                    }
              }
            }
      }
  }
  //console.log(mandamiento);
  if(mandamiento==0){
      $('#guardar').show();
  }
  document.getElementById('idTramite').value=idTramite;
  $('#tabDetalleTramite').removeClass('hidden');
  $('.nav-tabs a[href="#generales"]').tab('show');

}


  function cleanModal(){

       $('#empa1 option').each(function() {
          if ( $(this).val() != '0' ) {
              $(this).remove();
          }
        });
        $("#empa1").val("0").change();

         $('#mat1 option').each(function() {
          if ( $(this).val() != '0' ) {
              $(this).remove();
          }
        });

         $('#material option').each(function() {
              $(this).remove();
        });
         $('#color option').each(function() {
              $(this).remove();
        });

         $("#mat1").val("0").change();
         $("#por1").val("");
         $("#empa2").val("0").change();
         $("#mat2").val("")
         $("#por2").val("");
         $("#empa2").attr('disabled','disabled');
         $("#por2").attr('disabled','disabled');
         $("#mat2").attr('disabled','disabled');
         $("#empa3").val("0").change();
         $("#mat3").val("");
         $("#por3").val("");
         $("#accesorios").val("");
         $("#empa3").attr('disabled','disabled');
         $("#por3").attr('disabled','disabled');
         $("#mat3").attr('disabled','disabled');
          mat1val=void 0;
         mat2val=void 0;
         emp1val=void 0;
         emp2val=void 0;
        $('#sec2').attr('checked', false); // Unchecks it
       $('#ter3').attr('checked', false); // Unchecks it
  }

  @yield('jsTramites')

</script>
@endsection