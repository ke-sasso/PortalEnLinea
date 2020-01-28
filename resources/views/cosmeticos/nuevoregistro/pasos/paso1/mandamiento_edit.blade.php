<div id="form-step-0" role="form" data-toggle="validator">
    <div class="form-group">
        <div class="row">

            <div class="col-sm-12 col-md-4">
                <div class="form-group">
                    <h4>
                      <span class="label label-primary">
                        <strong>Tipo de trámite seleccionado:</strong>
                      </span>
                    </h4>
                    @if(isset($soldata))
                    <div class="radio">
                        <label class="radio-inline">
                            {{$soldata->solicitudTipo->nombreTramite}}  <input type="radio" name="tipoTramite" id="inlineRadio1"  value="{{$soldata->solicitudTipo->idTramite}}" checked  readonly form="CosPreStep1y2">
                        </label>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-6">
                <h4>
                  <span class="label label-primary">
                    <strong>Ingrese el mandamiento de pago!</strong>
                  </span>
                </h4>
                    <div class="input-group">
                        <div class="input-group-addon" for="mandamiento"><b>Mandamiento de pago:</b></div>
                        @if(isset($soldata))
                            <input type="number" class="form-control" id="mandamiento" name="mandamiento" value="{{$solicitud->idMandamiento}}" autocomplete="off" readonly form="CosPreStep1y2">
                        @endif
                    </div>
                    <div class="help-block with-errors"></div>
            </div>

        </div>
    </div>
</div>
