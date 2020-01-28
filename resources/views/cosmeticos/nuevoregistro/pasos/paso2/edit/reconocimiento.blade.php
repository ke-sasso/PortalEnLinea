<div class="form-group">
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="input-group ">
                <div class="input-group-addon" for="paisOrigen"><b>País Origen:</b></div>
                <select class="form-control" id="paisOrigen" name="paisOrigen">
                    <option value="" disabled selected hidden>Seleccione...</option>
                    <option value="3">GUATEMALA</option>
                    <option value="4">NICARAGUA</option>
                    <option value="5">HONDURAS</option>
                    <option value="69">COSTA RICA</option>
                    <option value="70">PANAMÁ</option>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="col-sm-6 col-md-5">
            <div class="input-group ">
                <div class="input-group-addon" for="numRegistro"><b>Núm. Registro (País de Origen):</b></div>
                <input type="text" name="numRegistro" class="form-control" required>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="input-group ">
                <div class="input-group-addon" for="fechaVen"><b>Fecha de Vencimiento:</b></div>
                <input type="text" id="fechaVen" name="fechaVen" class="form-control date_masking_g" placeholder="dd-mm-yyyy" required>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>
</div>

