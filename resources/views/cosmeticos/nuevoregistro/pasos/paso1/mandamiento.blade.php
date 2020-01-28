<div id="form-step-0" role="form" data-toggle="validator">
    <div class="form-group">
        <div class="row">

            <div class="col-sm-12 col-md-4">
                <div class="form-group">
                    <h4>
                      <span class="label label-primary">
                        <strong>Seleccione el tipo de trámite:</strong>
                      </span>
                    </h4>

                    <div class="radio">
                        <label class="radio-inline">
                            Nuevo Registro Cosmético <input type="radio" name="tipoTramite" id="inlineRadio1"  value="2" required form="CosPreStep1y2">
                        </label>
                    </div>
                    <div class="radio">
                        <label class="radio-inline">
                            Nuevo Reconomiento de Cosmético
                            <input type="radio" name="tipoTramite" id="inlineRadio2"  value="3" required form="CosPreStep1y2">
                        </label>
                    </div>
                    <div class="radio">
                        <label class="radio-inline">
                            Nuevo Registro Higiénico
                            <input type="radio" name="tipoTramite" id="inlineRadio2"  value="4" required form="CosPreStep1y2">
                        </label>
                    </div>
                    <div class="radio">
                        <label class="radio-inline">
                            <input type="radio" name="tipoTramite" id="inlineRadio2"  value="5" required form="CosPreStep1y2"> Nuevo Reconocimiento de Higiénico
                        </label>
                    </div>
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
                        <input type="number" class="form-control" id="mandamiento" name="mandamiento" value="" autocomplete="off" required form="CosPreStep1y2">
                        <span class="input-group-btn">
                            <button type="button" id="validarMandamiento" class="btn btn-primary">Validar  <i class="fa fa fa-check" aria-hidden="true"></i>
                            </button>
                        </span>
                    </div>
                    <div class="help-block with-errors"></div>
            </div>

        </div>
    </div>
</div>


