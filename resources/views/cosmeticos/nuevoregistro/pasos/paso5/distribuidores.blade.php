<h4>
  <span class="label label-primary">
    <strong>DISTRIBUIDORES:</strong>
  </span>
</h4>
<div id="form-step-4" role="form" data-toggle="validator">

    @if(isset($solicitud))
        <input type="hidden" value="{{Crypt::encrypt($solicitud->idSolicitud)}}" name="idSolicitud4" id="idSolicitud4" form="CosPreStep5">
    @else
        <input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud4" id="idSolicitud4" form="CosPreStep5">
    @endif

    <div class="form-group form-inline">
        <div class="radio">
            <label class="radio-inline">
                <b>¿El distribuidor es el mismo titular del producto?</b>
            </label>
        </div>
        <div class="radio">
            <label class="radio-inline">
                <input type="radio" name="distTitu" id="distTitu1"  value="1" required form="CosPreStep5">SI
            </label>
        </div>
        <div class="radio">
            <label class="radio-inline">
                <input type="radio" name="distTitu" id="distTitu2"  value="0" required form="CosPreStep5">NO
            </label>
        </div>
    </div>


    <div class="form-group">
        <div class="row">
            <div class="col-sm-10 col-md-10 col-lg-10">
                <div class="input-group ">
                    <div class="input-group-addon" for="distribuidor"><b>B&uacute;squeda del Distribuidor:</b></div>
                    <select id="searchbox-distribuidor" name="distribuidor" placeholder="Buscar por poder o  nombre del distribuidor" class="form-control"></select>
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <br>
    </div>
</div>
<div class="container-fluid the-box">
    <table class="table table-hover" id="distribuidores">
        <caption><b><u>DISTRIBUIDORES NACIONALES</u></b></caption>
        <thead class="thead-info">
        <tr>
            <th>NUM. PODER</th>
            <th>DISTRIBUIDOR</th>
            <th>DOMICILIO</th>
            <th>OPCIONES</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


<div align="center">
    <button type="button" class="btn btn-primary btn-perspective" data-autosave="false" id="btnStep5" form="CosPreStep5">Guardar Paso 5</button>
</div>