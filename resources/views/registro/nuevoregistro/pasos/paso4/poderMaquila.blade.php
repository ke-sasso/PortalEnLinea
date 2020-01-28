
<div class="container-fluid the-box">
        <h4>
            <span class="label label-info">
                <strong>CONTRATO DE MAQUILA</strong>
            </span>
        </h4>
        <div class="form-group form-inline">
            <label>¿Posee contrato de maquila?</label>
                     <label class="radio-inline">
                                <input type="radio" name="valContratoMaquila" id="valContratoMaquila" value="1" required form="RegistroPreStep4"> SI
                    </label>
                    <label class="radio-inline">
                                <input type="radio" name="valContratoMaquila" id="valContratoMaquila" value="0" checked required form="RegistroPreStep4"> NO
                    </label>

        </div>
        <div id="datosContratoMaquila" style="display: none;">
                 <div class="container-fluid the-box">
                 <div class="row">
                     <div class="col-sm-12 col-md-12 col-lg-12">
                        <h4>
                          <span class="label label-primary">
                            <strong>Ingrese un número de contrato de fabricación a terceros (maquila)  para <b>fabricante principal</b> </strong>
                          </span>
                        </h4>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-10 col-md-10 col-lg-10">
                                        <div class="input-group ">
                                            <div class="input-group-addon"><b>B&uacute;squeda de poder:</b></div>
                                            <select id="searchbox-poderMaquila1" name="searchbox-poderMaquila1" placeholder="Buscar por poder para fabricante principal" class="form-control"></select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </div>
                            <div class="the-box full no-border">
                                <table class="table table-hover" id="dt-poderFabPrincipal">
                                    <thead class="thead-info">
                                    <tr>
                                        <th>Número de contrato</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                    </div>
                 </div>
               </div>

               <div class="container-fluid the-box">
                 <div class="row">
                     <div class="col-sm-12 col-md-12 col-lg-12">
                        <h4>
                          <span class="label label-primary">
                            Ingrese uno o más números de contrato de fabricación a terceros (maquila)  para <strong>fabricante alterno</strong>
                          </span>
                        </h4>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-10 col-md-10 col-lg-10">
                                        <div class="input-group ">
                                            <div class="input-group-addon"><b>B&uacute;squeda de poder:</b></div>
                                              <select id="searchbox-poderMaquila2" name="searchbox-poderMaquila2" placeholder="Buscar por poder para fabricante alterno" class="form-control"></select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </div>
                            <div class="the-box full no-border">
                                <table class="table table-hover" id="dt-poderFabAlterno">
                                    <thead class="thead-info">
                                    <tr>
                                        <th>Número de contrato</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                    </div>
                 </div>
               </div>

                <div class="container-fluid the-box">
                 <div class="row">
                     <div class="col-sm-12 col-md-12 col-lg-12">
                        <h4>
                          <span class="label label-primary">
                            Ingrese uno o más números de contrato de fabricación a terceros (maquila)  para <strong>fabricante acondicionador</strong>
                          </span>
                        </h4>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-10 col-md-10 col-lg-10">
                                        <div class="input-group ">
                                            <div class="input-group-addon"><b>B&uacute;squeda de poder:</b></div>
                                            <select id="searchbox-poderMaquila3" name="searchbox-poderMaquila3" placeholder="Buscar por poder para fabricante acondicionador" class="form-control"></select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </div>
                            <div class="the-box full no-border">
                                <table class="table table-hover" id="dt-poderFabAcondicionador">
                                    <thead class="thead-info">
                                    <tr>
                                        <th>Número de contrato</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                    </div>
                 </div>
               </div>

        </div><!-- div datosContrato -->
</div>
