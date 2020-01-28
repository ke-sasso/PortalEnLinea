<h4>
  <span class="label label-primary">
    <strong>Ingrese la información general del nuevo producto!</strong>
  </span>
</h4>
<div id="form-step-1" role="form" data-toggle="validator">

    <input type="hidden" value="{{Crypt::encrypt($solicitud->idSolicitud)}}" name="idSolicitud" id="idSolicitud" form="CosPreStep1y2">

    <div class="form-group">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="input-group ">
                    <div class="input-group-addon" for="nomProd"><b>Nombre del producto (como se comercializará en El Salvador):</b></div>
                    <input type="text" class="form-control" id="nomProd" name="nomProd" value="{{$solicitud->solicitudesDetalle->nombreComercial}}" autocomplete="off" required form="CosPreStep1y2">
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div>
    </div>

    <div id="gnralCosOHig">
        @if($solicitud->tipoSolicitud==2 || $solicitud->tipoSolicitud==3)
            <!- Generales Cosmeticos -->
            @include('cosmeticos.nuevoregistro.pasos.paso2.gnralCosmeticos')
        @elseif($solicitud->tipoSolicitud==4 || $solicitud->tipoSolicitud==5)
            <!- Generales Higienicos -->
            @include('cosmeticos.nuevoregistro.pasos.paso2.gnralHigienico')
        @endif


        @if($solicitud->tipoSolicitud==3 || $solicitud->tipoSolicitud==5)
            <!- Reconocimiento -->
            @include('cosmeticos.nuevoregistro.pasos.paso2.reconocimiento',['solicitud'=>$solicitud])
        @endif
    </div>


    <div class="form-group">
        <div class="row">
            <div class="col-xs-6 col-sm-6">
                <label>¿El producto poseerá coempaque?</label>&nbsp;&nbsp;
                <input {{$solicitud->poseeCoempaque==1?'checked="checked"':''}} type="radio" name="coempaque" id="coempRadio1"  value="1" required form="CosPreStep1y2"> Si
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input {{$solicitud->poseeCoempaque==0?'checked="checked"':''}} type="radio" name="coempaque" id="coempRadio2"  value="0" required form="CosPreStep1y2"> No
            </div>
        </div>
    </div>
    @if($solicitud->poseeCoempaque==1)
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="input-group" id="detCoemp">
                        <div class="input-group-addon"><b>Nombre del producto que compone el coempaque:</b></div>
                        @if(!is_null($solicitud->descripcionCoempaque) || $solicitud->descripcionCoempaque!='')
                            <textarea class="form-control" name="detcoempaque" id="detcoempaque" form="CosPreStep1y2">
                                {{trim($solicitud->descripcionCoempaque) }}
                            </textarea>
                        @else
                            <textarea class="form-control" name="detcoempaque" id="detcoempaque" placeholder="Detalle el nombre comercial del otro producto que compone el coempaque" form="CosPreStep1y2">
                            </textarea>
                        @endif
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
    @else
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="input-group" id="detCoemp" style="display: none;">
                        <div class="input-group-addon"><b>Nombre del producto que compone el coempaque:</b></div>
                        <textarea class="form-control" name="detcoempaque" id="detcoempaque" placeholder="Detalle el nombre comercial del otro producto que compone el coempaque" form="CosPreStep1y2">

                        </textarea>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>

    @endif
    @include('cosmeticos.nuevoregistro.pasos.paso2.presentaciones')
    <div id="tonos">
        @include('cosmeticos.nuevoregistro.pasos.paso2.tonos')
    </div>

    <div id="fragancias">
        @include('cosmeticos.nuevoregistro.pasos.paso2.fragancias')
    </div>


    <div align="center">
        <button type="button" class="btn btn-primary btn-perspective" data-autosave="false" id="btnStep2" form="CosPreStep1y2">Guardar Paso 2</button>
    </div>
</div>
