@if(count($documentos)>=1 )
    @foreach($documentos as $doc)

    <tr>
        <td width="50%">{!! $doc->nombreItem!!}</td>
        <td>
            @if(isset($solicitud))
                @if($itemsDoc!=null)
                    @if(in_array($doc->idItem,$itemsDoc))
                            <a class="btn btn-info" href="{{route('cospresolicitud.showdocumento',['idSolicitud' =>Crypt::encrypt($solicitud->idSolicitud),'idItemDoc' => Crypt::encrypt($doc->idItem)])}}" target="_blank">Ver documento del requisito<i class="fa fa-download" aria-hidden="true"></i></a>
                            <button title="Eliminar Documento" data-nomdoc="{{$doc->nombreItem}}" data-id="{{$doc->idItem}}" data-obligatorio="{{$doc->obligatorio}}" class="btn btn-danger btnEliminarDoc"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            <input type="hidden" value="{{App\Models\Cosmeticos\Sol\DocumentoSol::where('idSolicitud',$solicitud->idSolicitud)->where('idItemDoc',$doc->idItem)->first()->detalleDocumento->idDoc}}" name="docGuardado[]" form="CosPreStep6">
                    @else
                            <div class="file-loading">
                                @if($doc->obligatorio==1)
                                    <input id="{{'file'.$doc->idItem}}" name="file-es[{{$doc->idItem}}]" type="file" required="true" data-obligatorio="{{$doc->obligatorio}}" form="CosPreStep6">
                                @else
                                    <input id="{{'file'.$doc->idItem}}" name="file-es[{{$doc->idItem}}]" type="file" data-obligatorio="{{$doc->obligatorio}}" form="CosPreStep6">
                                @endif
                            </div>
                    @endif

                @else
                    <div class="file-loading">
                        @if($doc->obligatorio==1)
                            <input id="{{'file'.$doc->idItem}}" name="file-es[{{$doc->idItem}}]" type="file" required="true" data-obligatorio="{{$doc->obligatorio}}" form="CosPreStep6">
                        @else
                            <input id="{{'file'.$doc->idItem}}" name="file-es[{{$doc->idItem}}]" type="file" data-obligatorio="{{$doc->obligatorio}}" form="CosPreStep6">
                        @endif
                    </div>
                @endif
            @else

                <div class="file-loading">
                    @if($doc->obligatorio==1)
                        <input id="{{'file'.$doc->idItem}}" name="file-es[{{$doc->idItem}}]" type="file" required="true" data-obligatorio="{{$doc->obligatorio}}" form="CosPreStep6">
                    @else
                        <input id="{{'file'.$doc->idItem}}" name="file-es[{{$doc->idItem}}]" type="file" data-obligatorio="{{$doc->obligatorio}}" form="CosPreStep6">
                    @endif
                </div>
            @endif
        </td>
    </tr>
    @endforeach
@endif