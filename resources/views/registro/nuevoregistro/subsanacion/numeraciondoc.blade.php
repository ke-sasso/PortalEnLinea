@extends('master')

@section('css')

{!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal.css') !!}
{!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') !!}
<style type="text/css">

    body {

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
      .modal {
          width:      100%;
          background: rgba( 255, 255, 255, .8 );
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

@if(Session::has('msnError'))
  <div class="alert alert-danger square fade in alert-dismissable">
    <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
    <strong>Auchh!</strong>
    Algo ha salido mal. {{ Session::get('msnError') }}
  </div>
@endif


<div class="panel panel-success">
<form id="infoGeneralReceta" method="post" action="{{route('imprimir.numereacion.doc.rv')}}" >
  <div class="panel-heading">
    <h3 class="panel-title">I. PRESENTACION DE LA DOCUMENTACIÓN</h3>
  </div>
  <div class="panel-body">
    <div class="table-responsive">
      <table style="font-size: 12px;" id="dt-nuevasolicitudes" class="table table-hover table-striped" role="group" width="100%">
        <tbody>
        @foreach ($expDoc as $exp)
          <tr>
            <td colspan="2"><center><b>{{ $exp->nomSubExpediente }}</b></center></td>
             <td colspan="2"><center><b>Número de paginas</b></center></td>
          </tr>
            @foreach($exp->docs as $doc)
                @if(in_array($doc->requisito_documento->idItem,$itemsDoc))
                <tr>
                  <td>{!!$doc->nomDocumento!!}</td>
                  <td><a class="btn btn-info" href="{{route('registrovisado.showdocumento',['idDocumento' => Crypt::encrypt($soli->documentos()->where('idItemRequisitoDoc',$doc->requisito_documento->idItem)->first()->idDoc)])}}" target="_blank">Ver documento del requisito<i class="fa fa-download" aria-hidden="true"></i></a></td>
                  <td><input type="number" required min="1" name="pag1_{{$doc->requisito_documento->idItem}}" id="pag1_{{$doc->requisito_documento->idItem}}"></td>
                   <td><input type="number"  min="1" name="pag2_{{$doc->requisito_documento->idItem}}" id="pag2_{{$doc->requisito_documento->idItem}}"></td>
                </tr>
                @endif

            @endforeach
        @endforeach
        </tbody>
        <tfoot>
        </tfoot>
      </table>
    </div>
    <div align="center">
        <input type="hidden" name="idSolicitud" id="idSolicitud" value="{{Crypt::encrypt($soli->idSolicitud)}}">
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
        <button type="submit" id="enviar" name="enviar" class="btn btn-primary">IMPRIMIR PDF<i class="fa fa-check"></i></button>
        <a title="Editar" href="{{route('get.preregistrorv.index.subsanarSolicitud',['idSolicitud'=>Crypt::encrypt($soli->idSolicitud)])}}" class="btn btn-success"> REGRESAR <i class="fa fa-mail-reply"></i></a>
    </div>
  </div>
  </form>
</div>
@endsection

@section('js')
<script type="text/javascript">


$( document ).ready(function() {


});



</script>

@endsection
