<h4>
  <span class="label label-primary">
    <strong>Ingrese la información de los laboratorios y acondicionador del nuevo producto farmacéutico!</strong>
  </span>
</h4>

<div id="form-step-3" role="form" data-toggle="validator">

                                <input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud3" id="idSolicitud3" form="RegistroPreStep4">
                            	<h4>
                            		<span class="label label-info">
                            			<strong>LABORATORIO FABRICANTE PRINCIPAL :</strong>
                            		</span>
                            	</h4>
                                 <div class="form-group form-inline">
                                    <div class="radio" id="nacional-fabpri">
                                        <label class="radio-inline">
                                            <input type="radio" name="origenFab" id="inlineRadio1-fab"  form="RegistroPreStep4" value="E04,E55,E57"  required> Nacional
                                        </label>
                                    </div>
                                    <div class="radio"  id="extranjero-fabpri">
                                        <label class="radio-inline">
                                            <input type="radio" name="origenFab" id="inlineRadio22-fab" form="RegistroPreStep4"  value="E30"  required> Extranjero
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="input-group ">
                                              <div class="input-group-addon" for="fabricante"><b>B&uacute;squeda del fabricante principal:</b></div>
                                              <select id="searchbox-fabricante1" name="qe" placeholder="Buscar por nombre del fabricante" class="form-control" required></select>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="input-group ">
                                              <div class="input-group-addon" for="nomProp"><b>Nombre fabricante principal:</b></div>
                                              <input type="text" class="form-control" id="nomProp" name="nomProp" value="{{!is_null($soldata->fabricantePrincipalInfo)?$soldata->fabricantePrincipalInfo->nombreComercial:''}}" autocomplete="off" readonly>
                                              <input type="hidden" form="RegistroPreStep4" name="idFabricantePri" id="idFabricantePri" value="{{!is_null($soldata->fabricantePrincipalInfo)?$soldata->fabricantePrincipalInfo->idEstablecimiento:''}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="input-group ">
                                              <div class="input-group-addon" for="direccProp"><b>Domicilio principal:</b></div>
                                              <input type="text" class="form-control" id="direccProp" name="direccProp" value="{{!is_null($soldata->fabricantePrincipalInfo)?$soldata->fabricantePrincipalInfo->direccion:''}}" autocomplete="off" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                	<div class="row">
                                        <div class="col-sm-12 col-md-12">
                                          <div class="input-group ">
                                            <div class="input-group-addon" for="paisFabP"><b>País:</b></div>
                                            <input type="text" class="form-control" id="paisFabP" name="paisFab" value="{{!is_null($soldata->fabricantePrincipalInfo)?$soldata->fabricantePrincipalInfo->pais:''}}" autocomplete="off" readonly>
                                          </div>
                                        </div>
                                </div>
                                </div>
                                <div class="form-group">
                                  <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                          <div class="input-group ">
                                            <div class="input-group-addon" for="nomaquilaFabPrincipal"><b>Número de contrato de fabricación a terceros (maquila) :</b></div>
                                            <input type="text" class="form-control" id="nomaquilaFabPrincipal" name="nomaquilaFabPrincipal" value="{{!is_null($soldata->fabricantePrincipalInfo)?$solicitud->fabricantePrincipal->noPoderMaquila:''}}" autocomplete="off" form="RegistroPreStep4">
                                          </div>
                                        </div>
                                </div>
                                </div>


                            <div class="container-fluid the-box">
                              <h4>
                                <span class="label label-info">
                                  <strong>LABORATORIO FABRICANTE ALTERNO :</strong>
                                </span>
                              </h4>
                                <div class="form-group form-inline">
                                    <label>Seleccione el origen del fabricante alterno antes de buscarlo:</label><br>
                                    <div class="radio">
                                        <label class="radio-inline">
                                            <input type="radio" name="origenFabAlterno" id="inlineRadio1"  value="E04,E55,E57" form="RegistroPreStep4" > Nacional
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="radio-inline">
                                            <input type="radio" name="origenFabAlterno" id="inlineRadio2"  value="E30"  form="RegistroPreStep4"> Extranjero
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="input-group ">
                                              <div class="input-group-addon" for="fabricante"><b>B&uacute;squeda del fabricante alterno:</b></div>
                                              <select id="searchbox-fabricante2" name="qe" placeholder="Buscar por nombre del fabricante" class="form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover" id="dt-fabricantesAlternos">
                                        <caption><b><u>FABRICANTES ALTERNOS</u></b></caption>
                                        <thead class="thead-info">
                                        <tr>
                                            <th>NOMBRE COMERCIAL</th>
                                            <th>DIRECCIÓN</th>
                                            <th>PAÍS</th>
                                            <th>No. contrato de fabricación a terceros (maquila)</th>
                                            <th>OPCIONES</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                          @if (!is_null($soldata->fabricantesAlternosInfo))
                                             @foreach ($soldata->fabricantesAlternosInfo as $alterno)
                                               @foreach($solicitud->fabricantesAlternos as $fabal)
                                                  @if($alterno->idEstablecimiento==$fabal->idFabAlterno)
                                                      <tr>
                                                        <input type="hidden" form="RegistroPreStep4" name="fabricantesAlternos[]" value="{{$alterno->idEstablecimiento}}">
                                                        <td>{{$alterno->nombreComercial}}</td>
                                                        <td>{{$alterno->direccion}}</td>
                                                        <td>{{$alterno->pais}}</td>
                                                        <td><input size="45" form="RegistroPreStep4" type="text" autocomplete="off" name="noMaquilaFabAlterno[]" value="{{$fabal->noPoderMaquila}}" /></td>
                                                        <td>
                                                          <button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                        </td>
                                                      </tr>
                                                 @endif
                                               @endforeach
                                            @endforeach
                                          @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="container-fluid the-box">
                              <h4>
                                <span class="label label-info">
                                  <strong>LABORATORIO ACONDICIONADOR :</strong>
                                </span>
                              </h4>
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                            <div class="form-group form-inline">
                                            <label>Seleccione el tipo de laboratorio acondicionador:</label><br>
                                            <div class="radio">
                                                <label class="radio-inline">
                                                    <input type="radio" name="categoriaLabAcon" id="inlineRadio1-categoriaaco"  form="RegistroPreStep4" value="1" checked> Primario
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="radio-inline">
                                                    <input type="radio" name="categoriaLabAcon" id="inlineRadio22-categoriaaco" form="RegistroPreStep4"  value="2"> Secundario
                                                </label>
                                            </div>
                                            </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                                <div class="form-group form-inline">
                                                    <label>Seleccione el origen del laboratorio acondicionador antes de buscarlo:</label><br>
                                                    <div class="radio">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="origenFabAcondicionador" form="RegistroPreStep4" id="inlineRadio1"  value="E04,E55,E57,E23" > Nacional
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="origenFabAcondicionador" form="RegistroPreStep4" id="inlineRadio2"  value="E30" > Extranjero
                                                        </label>
                                                    </div>
                                                </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="input-group ">
                                              <div class="input-group-addon" for="fabricante"><b>B&uacute;squeda del laboratorio acondicionador:</b></div>
                                              <select id="searchbox-fabricante3" name="qe" placeholder="Buscar por nombre del laboratorio acondicionador" class="form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover" id="dt-fabricantesAcondicionador">
                                        <caption><b><u>FABRICANTES ACONDICIONADORES</u></b></caption>
                                        <thead class="thead-info">
                                        <tr>
                                            <th>NOMBRE</th>
                                            <th>DIRECCIÓN</th>
                                            <th>PAÍS</th>
                                            <th>TIPO</th>
                                            <th>No. contrato de fabricación a terceros (maquila)</th>
                                            <th>OPCIONES</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (!is_null($soldata->fabricantesAcondicionadoresInfo))
                                          @foreach ($soldata->fabricantesAcondicionadoresInfo as $acondicionador=>$valAcon)
                                              @foreach($solicitud->laboratorioAcondicionador as $lab)
                                                @if($lab->idLabAcondicionador==$valAcon->idEstablecimiento)
                                                 <tr>
                                                <input type="hidden" form="RegistroPreStep4" id="laboratorioAcondicionador" name="laboratorioAcondicionador[]" value="{{$valAcon->idEstablecimiento}}">
                                                   <td>{{$valAcon->nombreComercial}}</td>
                                                   <td>{{$valAcon->direccion}}</td>
                                                   <td>{{$valAcon->pais}}</td>
                                                   <td>
                                                   @if($lab->tipo==1)
                                                  <input type="hidden" form="RegistroPreStep4" id="tipoLabAcondicionador" name="tipoLabAcondicionador[]" value="1">
                                                   PRIMARIO
                                                  @elseif($lab->tipo==2)
                                                  <input type="hidden" form="RegistroPreStep4" id="tipoLabAcondicionador" name="tipoLabAcondicionador[]" value="2">
                                                   SECUNDARIO
                                                  @else
                                                   <input type="hidden" form="RegistroPreStep4" id="tipoLabAcondicionador" name="tipoLabAcondicionador[]" value="0">
                                                  @endif
                                                   </td>
                                                  <td><input size="45" form="RegistroPreStep4" type="text" autocomplete="off" name="noMaquilaFabAcon[]" value="{{$lab->noPoderMaquila}}" /></td>
                                                   <td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                                 </tr>
                                                @endif
                                              @endforeach
                                           @endforeach
                                          @endif
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                              <div class="container-fluid the-box">
                              <h4>
                                <span class="label label-info">
                                  <strong>LABORATORIO RELACIONADO :</strong>
                                </span>
                              </h4>
                               {{-- <div class="form-group form-inline">
                                    <label>Seleccione el origen del laboratorio acondicionador antes de buscarlo:</label><br>
                                    <div class="radio">
                                        <label class="radio-inline">
                                            <input type="radio" name="origenFabAcondicionador" form="RegistroPreStep4" id="inlineRadio1"  value="E04,E55,E57,E23" > Nacional
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="radio-inline">
                                            <input type="radio" name="origenFabAcondicionador" form="RegistroPreStep4" id="inlineRadio2"  value="E30" > Extranjero
                                        </label>
                                    </div>
                                </div> --}}
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="input-group ">
                                              <div class="input-group-addon" for="fabricante"><b>B&uacute;squeda del laboratorio relacionado:</b></div>
                                              <select id="searchbox-fabricante4" name="qe" placeholder="Buscar por nombre del laboratorio relacionado" class="form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover" id="dt-fabricantesRelacionados">
                                        <caption><b><u>FABRICANTES RELACIONADOS</u></b></caption>
                                        <thead class="thead-info">
                                        <tr>
                                            <th>NOMBRE</th>
                                            <th>DIRECCIÓN</th>
                                            <th>PAÍS</th>
                                            <th>OPCIONES</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                          @if (!is_null($soldata->fabricantesRelacional))
                                          @foreach ($soldata->fabricantesRelacional as $value=>$valRela)
                                             <tr>
                                               <input type="hidden" form="RegistroPreStep4" id="laboratorioRelacionado" name="laboratorioRelacionado[]" value="{{$valRela->idEstablecimiento}}">
                                               <td>{{$valRela->nombreComercial}}</td>
                                               <td>{{$valRela->direccion}}</td>
                                               <td>{{$valRela->pais}}</td>
                                               <td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td>

                                             </tr>
                                           @endforeach
                                          @endif

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="container-fluid the-box">
                              <h4>
                                <span class="label label-info">
                                  <strong>FABRICANTE DE PRINCIPIO ACTIVO:</strong>
                                </span>
                              </h4>
                                <div class="form-group form-inline">
                                  <div class="row">
                                   <div class="col-sm-12 col-md-6">
                                     <label>Seleccione el origen del fabricante antes de buscar el principio activo:</label><br>
                                      <div class="radio">
                                          <label class="radio-inline">
                                              <input type="radio" name="origenFabPrincipio" id="inlineRadio1-fab"  form="RegistroPreStep4" value="E04,E55,E57"> Nacional
                                          </label>
                                      </div>
                                      <div class="radio">
                                          <label class="radio-inline">
                                              <input type="radio" name="origenFabPrincipio" id="inlineRadio22-fab" form="RegistroPreStep4"  value="E30"> Extranjero
                                          </label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                   <label>Seleccione el principio activo antes de buscar el fabricante:</label><br>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="input-group ">
                                               <div class="input-group-addon"><b>Principios activos</b></div>
                                              <select name="principioFabricante" id="principioFabricante" class="form-control select2-single select2-hidden-accessible" style="width:100%;" aria-hidden="true"></select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="input-group ">
                                              <div class="input-group-addon" for="fabricante"><b>B&uacute;squeda del fabricante:</b></div>
                                              <select id="searchbox-fabricante5" name="qe" placeholder="Buscar por nombre del fabricante de principio activo" class="form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover" id="dt-fabricantesPrincipioActivo">
                                        <caption><b><u>FABRICANTES DE PRINICIPIO ACTIVO</u></b></caption>
                                        <thead class="thead-info">
                                        <tr>
                                            <th>NOMBRE</th>
                                            <th>DIRECCIÓN</th>
                                            <th>PAÍS</th>
                                            <th>PRINCIPIO ACTIVO</th>
                                            <th>OPCIONES</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                          @if (!is_null($soldata->fabricantePrincipioActivo))
                                          @foreach ($soldata->fabricantePrincipioActivo as $key => $valactivo)
                                            @foreach($solicitud->fabprincipioactivo as $pri)
                                               @if($valactivo->idEstablecimiento==$pri->idFabricante)
                                               <tr>
                                                <input type="hidden" form="RegistroPreStep4" id="fabPrincipioActivo" name="fabPrincipioActivo[]" value="{{$valactivo->idEstablecimiento}}">
                                                <input type="hidden" form="RegistroPreStep4" id="nombreprincipio" name="nombreprincipio[]" value="{{$pri->nombrePrincipio}}" />
                                                <input type="hidden" form="RegistroPreStep4" id="idprincpio" name="idprincpio[]" value="{{$pri->idPrincipio}}" />
                                                <input type="hidden" form="RegistroPreStep4" id="origenfabprincipio" name="origenfabprincipio[]" value="{{$pri->procedencia}}" />
                                               <td>{{$valactivo->nombreComercial}}</td>
                                               <td>{{$valactivo->direccion}}</td>
                                               <td>{{$valactivo->pais}}</td>
                                               <td>{{$pri->nombrePrincipio}}</td>
                                               <td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td>

                                              </tr>
                                               @endif
                                            @endforeach
                                           @endforeach
                                          @endif
                                        </tbody>
                                    </table>
                                </div>

                            </div>


</div>
<!-- <a href="#" id="speak4" class="waves-effect waves-light btn btn-dark" disabled><i class="fa fa-play" aria-hidden="true"></i></a> -->



<div align="center">
    <button type="button" class="btn btn-primary" id="btnStep4">Guardar Paso 4</button>
</div>
