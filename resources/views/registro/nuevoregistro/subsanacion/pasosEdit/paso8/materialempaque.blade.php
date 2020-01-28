<h4>
  <span class="label label-primary">
   ¡Verifique que los datos coinciden con los que ha seleccionado en los pasos anteriores! <strong>(Paso informátivo)</strong>
  </span>
</h4>
<div id="form-step-7" role="form" data-toggle="validator">
               <input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud7" id="idSolicitud7" form="RegistroPreStep8">
              <div class="form-group">
                  <div class="row">
                      <div class="col-sm-12 col-md-8">
                          <div class="input-group ">
                            <div class="input-group-addon" for="propietarioProd"><b>Titular/Propietario del Producto:</b></div>
                            <input type="text" class="form-control" id="propietarioProd" disabled name="propietarioProd" value="" autocomplete="off" readonly required>
                          </div>
                      </div>
                      <div class="col-sm-4 col-md-4">
                          <div class="input-group">
                            <div class="input-group-addon" for="paisProp"><b>País:</b></div>
                            <input type="text" class="form-control" id="paisTitular" name="paisTitular" disabled value="" autocomplete="off" readonly>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="form-group">
                  <div class="row">
                      <div class="col-sm-12 col-md-8">
                          <div class="input-group ">
                            <div class="input-group-addon" for="labFabricante"><b>Laboratorio Fabricante:</b></div>
                            <input type="text" class="form-control" id="labFabricante" name="labFabricante" disabled value="" autocomplete="off" readonly >
                          </div>
                      </div>
                      <div class="col-sm-4 col-md-4">
                          <div class="input-group">
                            <div class="input-group-addon" for="paisProp"><b>País Fabricante:</b></div>
                            <input type="text" class="form-control" id="paisFabri" name="paisFabri" value="" disabled autocomplete="off" readonly>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="form-group">
                  <div class="row">
                      <div class="col-sm-12 col-md-12">
                          <div class="input-group ">
                            <div class="input-group-addon" for="labFabricante"><b>Laboratorio Acondicionador:</b></div>

                            <select name="listLabAcondicionador" id="listLabAcondicionador" disabled  class="form-control" form="RegistroPreStep8"></select>
                          </div>
                      </div>

                  </div>
              </div>
</div>

<!-- <a href="#" id="speak8" class="waves-effect waves-light btn btn-dark" disabled><i class="fa fa-play" aria-hidden="true"></i></a> -->


{{--
<div align="center">
    <button type="button" class="btn btn-primary" id="btnStep8">Guardar Paso 8</button>
</div> --}}
