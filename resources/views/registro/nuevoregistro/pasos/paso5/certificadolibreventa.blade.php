<h4>
  <span class="label label-primary">
    <strong>Ingrese la información del certificado de libre venta del nuevo producto farmacéutico! (CUANDO APLIQUE)</strong>
  </span>
</h4>
<div id="form-step-4" role="form" data-toggle="validator"> 

           <input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud4" id="idSolicitud4" form="RegistroPreStep5">
          <div class="form-group">
              <div class="row">
                  <div class="col-sm-12 col-md-12">
                      <div class="input-group ">
                        <div class="input-group-addon" for="certificadolv"><b>Nombre de la autoridad emisora del certificado:</b></div>
                        <input type="text" class="form-control" id="certificadolv" name="certificadolv" value="" autocomplete="off" form="RegistroPreStep5" >
                      </div>
                  </div>
              </div>
          </div>

          <div class="form-group">
              <div class="row">
                  <div class="col-sm-12 col-md-12">
                      <div class="input-group ">
                        <div class="input-group-addon" for="nomProdPais"><b>Nombre del producto registrado en país de procedencia:</b></div>
                        <input type="text" class="form-control" id="nomProdPais" name="nomProdPais" value="" autocomplete="off" form="RegistroPreStep5">
                      </div>
                  </div>
              </div>
          </div>
          <div class="form-group">    
              <div class="row">
                  <div class="col-sm-7 col-md-7">
                      <div class="input-group ">
                        <div class="input-group-addon" for="titularProductoC"><b>Titular del producto del Certificado:</b></div>
                        <input type="text" class="form-control" id="titularProductoC" name="titularProductoC" value="" autocomplete="off" form="RegistroPreStep5">
                        <input type="hidden" class="form-control" id="idtitularProductoC" name="idtitularProductoC" form="RegistroPreStep5">
                      </div>
                  </div>
              </div>
          </div>

          <div class="form-group">
              <div class="row">
                  <div class="col-sm-8 col-md-4">
                      <div class="input-group ">
                        <div class="input-group-addon" for="fechaEmision"><b>Fecha de emisión:</b></div>
                        <input type="text" class="form-control datepicker" id="fechaEmision"  name="fechaEmision" placeholder="dd-mm-yy" form="RegistroPreStep5" autocomplete="off">
                      </div>
                  </div>
                  <div class="col-sm-8 col-md-4">
                      <div class="input-group ">
                        <div class="input-group-addon" for="fechaVencimiento"><b>Fecha de vencimiento:</b></div>
                        <input type="text" class="form-control datepicker" id="fechaVencimiento"  name="fechaVencimiento" placeholder="dd-mm-yy"  form="RegistroPreStep5" autocomplete="off">
                      </div>
                  </div>
              </div>
          </div>
</div>
<!-- <a href="#" id="speak5" class="waves-effect waves-light btn btn-dark" disabled><i class="fa fa-play" aria-hidden="true"></i></a> -->



<div align="center">
    <button type="button" class="btn btn-primary" id="btnStep5">Guardar Paso 5</button>
</div>

