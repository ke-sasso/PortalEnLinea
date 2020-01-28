<div class="form-group">
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="input-group ">
                <div class="input-group-addon" for="marca"><b>Marca:</b></div>
                <select name="marca" id="marca" class="form-control" required   form="CosPreStep1y2">
                    <option value="" disabled selected hidden>Seleccione...</option>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="input-group ">
                <div class="input-group-addon" for="clasificacion"><b>Clasificación:</b></div>
                <select name="clasificacion" id="clasificacion" class="form-control" required  form="CosPreStep1y2">
                    <option value="" disabled selected hidden>Seleccione...</option>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="input-group ">
                <div class="input-group-addon" for="uso"><b>Uso del Producto Higiénico:</b></div>
                @if(isset($solicitud))
                    @if(!empty($solicitud->solicitudesDetalle->detallesHigienicos))
                        @if(!is_null($solicitud->solicitudesDetalle->detallesHigienicos->uso) || $solicitud->solicitudesDetalle->detallesHigienicos->uso!='')
                            <input type="text" name="uso" value="{{$solicitud->solicitudesDetalle->detallesHigienicos->uso}}" class="form-control" required  form="CosPreStep1y2">
                        @else
                            <input type="text" name="uso" value="" class="form-control" required  form="CosPreStep1y2">
                        @endif
                    @endif
                @else
                    <input type="text" name="uso" value="" class="form-control" required  form="CosPreStep1y2">
                @endif
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>
</div>