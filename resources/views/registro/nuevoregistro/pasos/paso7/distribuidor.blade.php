<h4>
  <span class="label label-primary">
    <strong>Ingrese la información del distribuidor nacional del nuevo producto farmacéutico! (CUANDO APLIQUE)</strong>
  </span>
</h4>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-10 col-md-10 col-lg-10">
                <div class="input-group ">
                    <div class="input-group-addon" for="distribuidor"><b>B&uacute;squeda del Distribuidor:</b></div>
                    <select id="searchbox-distribuidor" name="distribuidor" placeholder="Buscar por poder o por nombre del distribuidor" class="form-control"></select>
                    <!-- <span class="input-group-btn">
					    <button class="btn btn-primary" id="agregarDistribuidor" type="button">Agregar <i class="fa fa-plus" aria-hidden="true"></i></button>
				    </span> -->
                </div>
            </div>
        </div>
        <br>
    </div>

    <input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud6" id="idSolicitud6" form="RegistroPreStep7">
    <div class="the-box full no-border">
        <table class="table table-hover" id="dt-distribuidores">
            <caption><b><u>DISTRIBUIDORES NACIONALES</u></b></caption>
            <thead class="thead-info">
            <tr>
                <th>ID DISTRIBUIDOR</th>
                <th>NOMBRE COMERCIAL</th>
                <th>DOMICILIO</th>
                <th>FECHA VIGENCIA</th>
                <th>ESTADO</th>
                <th>CORREO ELECTRÓNICO</th>
                <th>OPCIONES</th>

            </tr>
            </thead>
            <tbody>
            <tr>

            </tr>
            </tbody>
        </table>
    </div>
<!--
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="input-group ">
                  <div class="input-group-addon" for="nombreDistribuidor"><b>Nombre del distribuidor:</b></div>
                  <input type="text" class="form-control" id="nombreDistDistme" name="nombreDist" value="" autocomplete="off" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="input-group ">
                  <div class="input-group-addon" for="direccDist"><b>Domicilio principal:</b></div>
                  <input type="text" class="form-control" id="direccDist" name="direccDist" value="" autocomplete="off" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-4 col-md-3">
                <div class="input-group ">
                  <div class="input-group-addon" for="telefonoDist"><b>Tel&eacute;fono:</b></div>
                  <input type="text" class="form-control" id="telefonoDist" name="telefonoDist" value="" autocomplete="off" readonly>
                </div>
            </div>
            <div class="col-sm-4 col-md-3">
                <div class="input-group ">
                  <div class="input-group-addon" for="faxDist"><b>Fax:</b></div>
                  <input type="text" class="form-control" id="faxDist" name="faxDist" value="" autocomplete="off" readonly>
                </div>
            </div>
            <div class="col-sm-4 col-md-6">
                <div class="input-group ">
                  <div class="input-group-addon" for="correoDist"><b>Correo:</b></div>
                  <input type="text" class="form-control" id="correoDist" name="correoDist" value="" autocomplete="off" readonly>
                </div>
            </div>
        </div>
    </div>

    <a href="#" id="speak7" class="waves-effect waves-light btn btn-dark" disabled><i class="fa fa-play" aria-hidden="true"></i></a>
-->




<div align="center">
      <button type="button" class="btn btn-primary" id="btnStep7">Guardar Paso 7</button>
  </div>


