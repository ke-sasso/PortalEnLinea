<div id="dlgPresentacion" class="modal fade" role="dialog" aria-labelledby="DefaultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <b><h4 class="modal-title" align="center">Agregar Presentación</h4></b>
            </div>


            <div class="modal-body" id="presentacionDiv">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-5 col-sm-5">
                                <label>¿Posee empaque secundario?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" id="empesectrue" name="checkempsec" value="1" >&nbsp;&nbsp;Si
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" id="empesecfalse"  name="checkempsec" checked="checked" value="0" >&nbsp;&nbsp;No
                            </div>
                            <div class="col-xs-7 col-sm-7">
                                <label style="color:#babec3;display:block; margin-top:-8px;">Empaque secundario se refiere a la descripción del envase más externo
                                    <a id="ayuda" type="button" data-toggle="modal" data-target="#ejemploPresentacion" data-toggle="tooltip" data-placement="bottom" title="Ayuda para ingresar una presentación" disable="true" style="font-size:1.5em; color:#de0d0d;">
                                        <i class="fa fa-question-circle x7" style="border-radius: 2em;"></i>
                                    </a>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" >
                        <div class="row">
                            <div class="col-xs-12 col-sm-12">
                                <h4>Su presentación se lee así:</h4>
                                <b><input type="text" class="form-control pres" style="border-bottom-color: black; background-color: #d5dad8;" id="textoPres" name="textPres" readonly="true"/></b>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="emapaquesecundario">
                        <div class="row">
                            <div class="col-sm-5 col-md-5">
                                <div class="input-group ">
                                    <div class="input-group-addon" for="empsec"><b>Emapaque secundario (ES):</b></div>
                                    <select name="empsec" id="empsec" class="form-control" required onchange="armarPresentacion();">
                                    </select>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="input-group ">
                                    <div class="input-group-addon" for="matsec">DE</div>
                                    <select name="matsec" id="matsec" class="form-control" required onchange="armarPresentacion();">
                                    </select>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><b>Contenido ES:</b></span>
                                    <input type="number" class="form-control" value="" min="1" id="contsec" name="contsec" required onkeyup="armarPresentacion();" onmousedown="armarPresentacion();" onmouseup="armarPresentacion();">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="empaqueprimario">
                        <div class="row">
                            <div class="col-sm-5 col-md-5">
                                <div class="input-group ">
                                    <div class="input-group-addon" ><b>Emapaque primario (EP):</b></div>
                                    <select name="emppri" id="emppri" class="form-control" required onchange="armarPresentacion();">
                                    </select>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="input-group ">
                                    <div class="input-group-addon" >DE</div>
                                    <select name="matpri" id="matpri" class="form-control" required onchange="armarPresentacion();">
                                    </select>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><b>Contenido EP:</b></span>
                                    <input type="number" class="form-control" value="" min="1" id="contpri" name="contpri" required onkeyup="armarPresentacion();" onmousedown="armarPresentacion();" onmouseup="armarPresentacion();">
                                    <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                                    <select id="unidadmedidapri" name="unidadmedidapri" style="width:70px;" class="form-control" onchange="armarPresentacion();">
                                    </select>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="cep">
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><b>Peso Unidad (Opcional):</b></span>
                                    <input type="number" class="form-control" value="" min="1"  id="peso" name="peso" required onkeyup="armarPresentacion();"  onmousedown="armarPresentacion();" onmouseup="armarPresentacion();">
                                    <span class="input-group-addon">DE</span>
                                    <select id="medida" name="medida" style="width:120px;" class="form-control" onchange="armarPresentacion();">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12">
                                <label>Ingrese un nombre de presentación (Opcional) :</label>
                                <input type="text" class="form-control pres" name="nombrePres" id="nombrePres" onkeyup="armarPresentacion();"/>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnAgregarPresent">Agregar</button>
            </div><!-- /.modal-footer -->

        </div>
    </div>
</div>

<!--modal ayda-->
<div class="modal fade" id="ejemploPresentacion" role="dialog" aria-labelledby="DefaultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EJEMPLO DE PRESENTACIÓN</h4>
            </div>
            <div class="modal-body" align="center">
                    <img class="pre1" src="{{ url('img/cosmeticos/ejemploPre1.png') }}">
                    <img class="pre2" src="{{ url('img/cosmeticos/ejemploPre2.png') }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>