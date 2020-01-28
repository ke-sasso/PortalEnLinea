@if(isset($solicitud))
<a id="formulariopdf" class="btn btn-info  btn-rounded-lg" href="{{route('formulario.cosehig',['idSolicitud'=>Crypt::encrypt($solicitud->idSolicitud)])}}" target="_blank">
        <span class="label label-info" style="font-size: 100%">
            <strong>De click en este link para imprimir el formulario correspondiente del trámite!</strong>
            <i class="fa fa-print"></i>
        </span>
</a>
@else
    <button type="button" id="formulariopdf" class="btn btn-info  btn-rounded-lg">
        <span class="label label-info" style="font-size: 100%">
            <strong>De click en este link para imprimir el formulario correspondiente del trámite!</strong>
            <i class="fa fa-print"></i>
        </span>
    </button>
@endif

<div class="table-responsive" id="documentosDiv">


    @if(isset($solicitud))
        <input type="hidden" value="{{Crypt::encrypt($solicitud->idSolicitud)}}" name="idSolicitud5" id="idSolicitud5" form="CosPreStep6">
    @else
        <input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud5" id="idSolicitud5" form="CosPreStep6">
    @endif


    <table width="100%" class="table table-striped" id="documentos">
        <thead>
        <th>REQUISITOS:</th>
        </thead>
        <tbody>
            @if(isset($solicitud))
                @if(isset($documentos))
                    @if( $documentos!=null)
                        @include('cosmeticos.nuevoregistro.pasos.paso6.bodydocumentos',['documentos'=>$documentos])
                    @endif
                @endif
            @endif
        </tbody>
    </table>

    <div align="center">
        <button type="button" class="btn btn-primary btn-perspective" data-autosave="false" id="btnStep6" form="CosPreStep6">Guardar Paso 6</button>
    </div>
</div>