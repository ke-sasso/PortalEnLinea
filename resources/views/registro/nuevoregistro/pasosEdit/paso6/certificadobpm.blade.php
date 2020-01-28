<h4>
  <span class="label label-primary">
    <strong>Ingrese la información del certificado de buenas prácticas de manufactura del nuevo producto farmacéutico! (CUANDO APLIQUE)</strong>
  </span>
</h4>
<div class="container-fluid the-box">
    <h4>
      <span class="label label-info">
        <strong>BUENAS PRACTICAS DE MANUFACTURA DEL LABORATORIO FABRICANTE PRINCIPAL</strong>
      </span>
    </h4>
    <div class="form-group">
        <input type="hidden" value="{{Crypt::encrypt($solicitud->idSolicitud)}}" name="idSolicitud5" id="idSolicitud5" form="RegistroPreStep6">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="input-group ">
                  <div class="input-group-addon" for="certificadobpm"><b>Pa&iacute;s:</b></div>
                  <input type="text" class="form-control" id="certificadobpm" name="certificadobpm" value="{{$solicitud->bpmPrincipal!=null?$solicitud->bpmPrincipal->nombreEmisor:''}}" autocomplete="off" form="RegistroPreStep6" readonly>
                  <input type="hidden" name="idcertificadobpm" id="idcertificadobpm" value="{{$solicitud->bpmPrincipal!=null?$solicitud->bpmPrincipal->idFabricantePpal:''}}" form="RegistroPreStep6">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
    <?php
                         $fe1=''; $fe2='';
                          if($solicitud->bpmPrincipal!=null){
                              if($solicitud->bpmPrincipal->fechaEmision=='1900-01-01 00:00:00' || $solicitud->bpmPrincipal->fechaEmision==''){ $fe1=''; }else{
                                $fe1= date('d-m-Y',strtotime($solicitud->bpmPrincipal->fechaEmision));
                              }
                              if($solicitud->bpmPrincipal->fechaVencimiento=='1900-01-01 00:00:00' || $solicitud->bpmPrincipal->fechaVencimiento==''){ $fe2=''; }else{
                                $fe2= date('d-m-Y',strtotime($solicitud->bpmPrincipal->fechaVencimiento));
                              }
                          }
      ?>
        <div class="row">
            <div class="col-sm-8 col-md-4">
                <div class="input-group ">
                  <div class="input-group-addon" for="fechaEmision"><b>Fecha de emisión:</b></div>
                  <input type="text" class="form-control datepicker" id="fechaEmision"  name="fechaEmision" placeholder="dd-mm-yy" form="RegistroPreStep6" value="{{$fe1}}">
                </div>
            </div>
            <div class="col-sm-8 col-md-4">
                <div class="input-group ">
                  <div class="input-group-addon" for="fechaVencimiento"><b>Fecha de vencimiento:</b></div>
                  <input type="text" class="form-control datepicker" id="fechaVencimiento"  name="fechaVencimiento" placeholder="dd-mm-yy" form="RegistroPreStep6" value="{{$fe2}}">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid the-box">
    <h4>
      <span class="label label-info">
        <strong>BUENAS PRACTICAS DE MANUFACTURA DEL LABORATORIO FABRICANTE ALTERNO</strong>
      </span>
    </h4>

       <div class="the-box full no-border">
                                    <table class="table table-hover" id="dt-pract-labfabalterno">
                                        <thead class="thead-info">
                                        <tr>
                                            <th>Laboratorio fabricante alterno</th>
                                            <th>Pa&iacute;s</th>
                                            <th>Fecha de emisión</th>
                                            <th>Fecha de vencimiento</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
        </div>

</div>

<div class="container-fluid the-box">
    <h4>
      <span class="label label-info">
        <strong>BUENAS PRACTICAS DE MANUFACTURA DEL LABORATORIO ACONDICIONADOR</strong>
      </span>
    </h4>

       <div class="the-box full no-border">
                                    <table class="table table-hover" id="dt-pract-labacondicionador">
                                        <thead class="thead-info">
                                        <tr>
                                            <th>Fabricante acondicionador</th>
                                            <th>Pa&iacute;s</th>
                                            <th>Fecha de emisión</th>
                                            <th>Fecha de vencimiento</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
        </div>

</div>
<div class="container-fluid the-box">
    <h4>
      <span class="label label-info">
        <strong>BUENAS PRACTICAS DE MANUFACTURA DEL LABORATORIO RELACIONADO</strong>
      </span>
    </h4>

       <div class="the-box full no-border">
                                    <table class="table table-hover" id="dt-pract-labRelacionado">
                                        <thead class="thead-info">
                                        <tr>
                                            <th>Fabricante Relacionado</th>
                                            <th>Pa&iacute;s</th>
                                            <th>Fecha de emisión</th>
                                            <th>Fecha de vencimiento</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
        </div>

</div>
<div class="container-fluid the-box">
    <h4>
      <span class="label label-info">
        <strong>BUENAS PRACTICAS DE MANUFACTURA DEL LABORATORIO FABRICANTE DE PRINCIPIO ACTIVO</strong>
      </span>
    </h4>

       <div class="the-box full no-border">
                                    <table class="table table-hover" id="dt-pract-FabPrinActivo">
                                        <thead class="thead-info">
                                        <tr>
                                            <th>Fabricante Principio activo</th>
                                            <th>Pa&iacute;s</th>
                                            <th>Fecha de emisión</th>
                                            <th>Fecha de vencimiento</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
        </div>

</div>


<!-- <a href="#" id="speak6" class="waves-effect waves-light btn btn-dark" disabled><i class="fa fa-play" aria-hidden="true"></i></a> -->



<div align="center">
    <button type="button" class="btn btn-primary" id="btnStep6">Guardar Paso 6</button>
</div>
