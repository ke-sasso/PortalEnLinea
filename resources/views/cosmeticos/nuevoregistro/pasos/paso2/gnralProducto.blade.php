<h4>
  <span class="label label-primary">
    <strong>Ingrese la información general del nuevo producto!</strong>
  </span>
</h4>
<div id="form-step-1" role="form" data-toggle="validator">

    <input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud" id="idSolicitud" form="CosPreStep1y2">
    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="input-group ">
                    <div class="input-group-addon" for="nomProd"><b>Nombre del producto (como se comercializará en El Salvador):</b></div>
                    <input type="text" class="form-control" id="nomProd" name="nomProd" value="" autocomplete="off" required form="CosPreStep1y2">
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div>
    </div>

    <div id="gnralCosOHig"></div>



    <div id="reconocimiento">
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-6 col-sm-6">
                <label>¿El producto poseerá coempaque?</label>&nbsp;&nbsp;
                <input type="radio" name="coempaque" id="inlineRadio1"  value="1" required form="CosPreStep1y2"> Si
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="coempaque" id="inlineRadio2" checked="checked" value="0" required form="CosPreStep1y2"> No
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12 col-md-12" id="detalleCoempaque">
                <div class="input-group ">
                    <div class="input-group-addon"><b>Nombre del producto que compone el coempaque:</b></div>
                    <textarea class="form-control" name="detcoempaque" id="detcoempaque" placeholder="Detalle el nombre comercial del otro producto que compone el coempaque" form="CosPreStep1y2"></textarea>
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div>
    </div>

    @include('cosmeticos.nuevoregistro.pasos.paso2.presentaciones')



    <div id="tonos" >
        @include('cosmeticos.nuevoregistro.pasos.paso2.tonos')
    </div>

    <div id="fragancias">
        @include('cosmeticos.nuevoregistro.pasos.paso2.fragancias')
    </div>

    <div align="center">
        <button type="button" class="btn btn-primary btn-perspective" data-autosave="false" id="btnStep2" form="CosPreStep1y2">Guardar Paso 2</button>
    </div>

</div>



