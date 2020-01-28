<div class="container-fluid the-box">
    <h4>
		<span class="label label-info">
			<strong>FABRICANTES :</strong>
		</span>
    </h4>
    <div class="form-group form-inline">
        <label>Seleccione el origen del fabricante antes de buscarlo:</label><br>
        @if(isset($solicitud))
            @if($solicitud->tipoSolicitud==2 || $solicitud->tipoSolicitud==4)
                <div class="radio" id="radioFabNacional">
                    <label class="radio-inline">
                        <input type="radio" name="origenFab" id="inlineRadio1"  value="E18,E04" tipo="NAC" required form="CosPreStep4"> Nacional
                    </label>
                </div>
            @endif
        @else
            <div class="radio" id="radioFabNacional">
                <label class="radio-inline">
                    <input type="radio" name="origenFab" id="inlineRadio1"  value="E18,E04" tipo="NAC" required form="CosPreStep4"> Nacional
                </label>
            </div>
        @endif
        <div class="radio">
            <label class="radio-inline">
                <input type="radio" name="origenFab" id="inlineRadio2"  value="E36,E30" tipo="EXT" required form="CosPreStep4"> Extranjero
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="input-group ">
                    <div class="input-group-addon" for="fabricante"><b>B&uacute;squeda del fabricante:</b></div>
                    <select id="searchbox-fabricante" name="qe" placeholder="Buscar por nombre del fabricante" class="form-control" required></select>
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <br>
    </div>

    <div class="table-responsive">
        <table id="fabricantes" class="table table-hover">
            <thead>
            <tr>
                <th>NOMBRE COMERCIAL</th>
                <th>DIRECCIÓN</th>
                <th>PAÍS</th>
                <th width="10%">OPCIONES</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>








