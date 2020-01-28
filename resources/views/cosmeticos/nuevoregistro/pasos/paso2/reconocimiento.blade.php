<div class="form-group">
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="input-group ">
                <div class="input-group-addon" for="paisOrigen"><b>País Origen:</b></div>
                <select class="form-control" id="paisOrigen" name="paisOrigen" form="CosPreStep1y2">
                    <option value="" disabled selected hidden>Seleccione...</option>
                    <option value="320">GUATEMALA</option>
                    <option value="558">NICARAGUA</option>
                    <option value="340">HONDURAS</option>
                    <option value="188">COSTA RICA</option>
                    <option value="591">PANAMÁ</option>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="col-sm-6 col-md-5">
            <div class="input-group ">
                <div class="input-group-addon" for="numRegistro"><b>Núm. Registro (País de Origen):</b></div>
                @if(isset($solicitud))
                    @if(!is_null($solicitud->solicitudesDetalle->numeroRegistroExtr) || $solicitud->solicitudesDetalle->numeroRegistroExtr!='')
                        <input type="text" name="numRegistro" class="form-control" value="{{$solicitud->solicitudesDetalle->numeroRegistroExtr}}" required form="CosPreStep1y2">
                    @else
                        <input type="text" name="numRegistro" class="form-control" value="{{$solicitud->solicitudesDetalle->numeroRegistroExtr}}" required form="CosPreStep1y2">
                    @endif
                @else
                    <input type="text" name="numRegistro" class="form-control" value="" required form="CosPreStep1y2">
                @endif

            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="input-group ">
                <div class="input-group-addon" for="fechaVen"><b>Fecha de Vencimiento:</b></div>
                @if(isset($solicitud))
                    @if(!is_null($solicitud->solicitudesDetalle->fechaVencimiento) || $solicitud->solicitudesDetalle->fechaVencimiento!='')
                        <input type="text" id="fechaVen" name="fechaVen" class="form-control date_masking_g" value="{{date('d-m-Y',strtotime($solicitud->solicitudesDetalle->fechaVencimiento))}}" placeholder="dd-mm-yyyy" required form="CosPreStep1y2">
                    @else
                        <input type="text" id="fechaVen" name="fechaVen" class="form-control date_masking_g" placeholder="dd-mm-yyyy" required form="CosPreStep1y2">
                    @endif
                @else
                    <input type="text" id="fechaVen" name="fechaVen" class="form-control date_masking_g" placeholder="dd-mm-yyyy" required form="CosPreStep1y2">
                @endif

            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>
</div>

