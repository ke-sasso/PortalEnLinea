<div class="container-fluid the-box">
    <h4>
		<span class="label label-info">
			<strong>TITULAR DEL PRODUCTO:</strong>
		</span>
    </h4>
    <div class="form-group form-inline">
        <label>Seleccione el tipo de titular antes de buscarlo:</label><br>
        <div class="radio">
            <label class="radio-inline">
                <input type="radio" name="tipoTitular" id="inlineRadio1" class="tipoTitular" value="1" required form="CosPreStep3" >Persona Natural
            </label>
        </div>
        <div class="radio">
            <label class="radio-inline">
                <input type="radio" name="tipoTitular" id="inlineRadio2" class="tipoTitular" value="2" required form="CosPreStep3">Persona Jurídica
            </label>
        </div>
        <div class="radio">
            <label class="radio-inline">
                <input type="radio" name="tipoTitular" id="inlineRadio3" class="tipoTitular" value="3" required form="CosPreStep3"> Extranjero
            </label>
        </div>
    </div>


        <br/>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="input-group ">
                        <div class="input-group-addon" for="titular"><b>B&uacute;squeda del Titular:</b></div>
                        <select id="searchbox-titular" name="qe" placeholder="Buscar por nit o por nombre del titular" class="form-control" required></select>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <br>
        </div>

        <div id="bodyPropietario">
            @if(isset($soldata))
                @if($soldata->titular!=null)
                    @include('cosmeticos.nuevoregistro.pasos.paso3.bodytitular', ['propietario' => $soldata->titular])
                @endif
            @endif
        </div>

</div>
