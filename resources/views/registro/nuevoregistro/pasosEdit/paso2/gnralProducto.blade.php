<h4>
  <span class="label label-primary">
    <strong>Ingrese la información general del nuevo producto farmacéutico!</strong>
  </span>
</h4>
<div id="form-step-1" role="form" data-toggle="validator">
        <input type="hidden" value="{{Crypt::encrypt($solicitud->idSolicitud)}}" name="idSolicitud" id="idSolicitud" form="RegistroStep1y2">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="input-group ">
                      <div class="input-group-addon" for="nomProd"><b>Nombre del producto (como se comercializará en El Salvador):</b></div>
                      <input type="text" class="form-control" id="nom_prod" name="nom_prod" value="{{$solicitud->solicitudesDetalle->nombreComercial}}" autocomplete="off" required form="RegistroStep1y2">
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6 col-md-2">
                    <div class="input-group ">
                      <div class="input-group-addon" for="innovador"><b>Innovador:</b></div>
                      <select name="innovador" id="innovador" class="form-control" form="RegistroStep1y2">
                      	<option value="1" {{$solicitud->solicitudesDetalle->innovador==1?'selected':''}}>SI</option>
                      	<option value="0" {{$solicitud->solicitudesDetalle->innovador==0?'selected':''}}>NO</option>
                      </select>
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="input-group ">
                      <div class="input-group-addon" for="origen"><b>Procedencia:</b></div>
                      <select name="origen" id="origen" class="form-control" form="RegistroStep1y2">
                        @if($solicitud->solicitudesDetalle->origenProducto==1)
                      	<option value="1" {{$solicitud->solicitudesDetalle->origenProducto==1?'selected':''}}>Nacional</option>

                        @else
                      	<option value="2" {{$solicitud->solicitudesDetalle->origenProducto==2?'selected':''}}>Extranjero</option>
                      	<option value="3" {{$solicitud->solicitudesDetalle->origenProducto==3?'selected':''}}>Reconocimiento Extranjero</option>
                        <option value="4" {{$solicitud->solicitudesDetalle->origenProducto==4?'selected':''}}>Reconocimiento Mutuo Centroamericano</option>
                        @endif
                      </select>
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="input-group ">
                      <div class="input-group-addon" for="tipoMedicamento"><b>Tipo de medicamento:</b></div>
                        <select name="tipoMedicamento" id="tipoMedicamento" style="width:100%;" class="form-control select2-selection--single" required form="RegistroStep1y2">
                            <option value="" disabled selected hidden>Seleccione...</option>

                        </select>
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        <div id="divprocedencia">

        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <div class="input-group ">
                      <div class="input-group-addon" for="formaFarm"><b>Forma Farmaceutica:</b></div>
                      <select name="formafarm" id="formaFarm" style="width:100%;" class="form-control select2-single" required form="RegistroStep1y2">
                          <option value="" disabled selected hidden>Seleccione...</option>
                      </select>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="col-sm-6 col-md-6">
                    <div class="input-group ">
                      <div class="input-group-addon" for="viaAdmin"><b>Via de administraci&oacute;n:</b></div>
                      <select name="viaAdmin" id="viaAdmin" style="width:100%;" class="form-control select2-single" required form="RegistroStep1y2">
                          <option value="" disabled selected hidden>Seleccione...</option>
                      </select>
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
                  <div class="form-group">
            <div class="row">
                <div class="col-sm-6 col-md-6">
                <div class="input-group ">
                      <div class="input-group-addon" for="bioequi"><b>Bioequivalente:</b></div>
                      <select name="bioequi" id="bioequi" style="width:100%;" class="form-control " required form="RegistroStep1y2">
                          @if(!empty($solicitud->solicitudesDetalle->bioequivalente))
                            @if($solicitud->solicitudesDetalle->bioequivalente==0)
                              <option selected value="0">NO</option>
                            @else
                             <option selected value="1">SI</option>
                            @endif
                       @else
                               @if($solicitud->solicitudesDetalle->tipoMedicamento==9)
                                  <option selected value="1">SI</option>
                               @else
                                  <option selected value="0">NO</option>
                               @endif
                       @endif
                      </select>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>

                <div class="col-sm-6 col-md-6">
                    <div class="input-group ">
                      <div class="input-group-addon" for="modalidad"><b>Modalidad de venta:</b></div>
                      <select name="modalidad" id="modalidad" style="width:100%;" class="form-control select2-single" required form="RegistroStep1y2">
                        <option value="" disabled selected hidden>Seleccione...</option>
                      </select>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>

            </div>
        </div>
        <div class="form-group">
            <div class="row">
                   <div class="col-sm-12 col-md-12">
                    <div class="input-group ">
                        <div class="input-group-addon" for="udosis"><b>Unidad de dosis:</b></div>
                        <input type="text" class="form-control" id="udosis" name="udosis"  value="{{$solicitud->solicitudesDetalle->unidadDosis!=null?$solicitud->solicitudesDetalle->unidadDosis:''}}" autocomplete="off" required form="RegistroStep1y2">
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
          <div class="form-group">
            <div class="row">
               <div class="col-sm-12 col-md-12">
                    <div class="input-group ">
                        <div class="input-group-addon" for="formula"><b>Formula (equivalencia de sal a base, excesos, purezas, moléculas de hidratación y perdida por secado):</b></div>
                        <textarea class="form-control" id="formula" name="formula" value=""  rows="2" autocomplete="off" required form="RegistroStep1y2">{{$solicitud->solicitudesDetalle->formula!=null?$solicitud->solicitudesDetalle->formula:''}}</textarea>
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="input-group ">
                        <div class="input-group-addon" for="excipientes"><b>Excipientes:</b></div>
                        <textarea class="form-control" id="excipientes" name="excipientes"   rows="2" autocomplete="off" required form="RegistroStep1y2">{{$solicitud->solicitudesDetalle->excipientes!=null?$solicitud->solicitudesDetalle->excipientes:''}}</textarea>
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>

         @include('registro.nuevoregistro.pasosEdit.paso2.principiosactivos')

         @include('registro.nuevoregistro.pasosEdit.paso2.vidautilempaque')
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 col-md-9">
                    <div class="input-group ">
                      <div class="input-group-addon" for="condAlmacenamiento"><b>Condiciones de almacenamiento:</b></div>
                      <input type="text" class="form-control" id="condAlmacenamiento" name="condAlmacenamiento" value="{{$solicitud->solicitudesDetalle->condicionesAlmacenaje!=null?$solicitud->solicitudesDetalle->condicionesAlmacenaje:''}}" autocomplete="off" required form="RegistroStep1y2">
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
                {{--<div class="col-sm-12 col-md-3">
                    <div class="input-group ">
                      <div class="input-group-addon" for="vidaUtil"><b>Vida Util (Propuesta):</b></div>
                      <input type="number" class="form-control" id="vidaUtil" name="vidaUtil" min="0" value="{{$solicitud->solicitudesDetalle->vidaUtil!=null?$solicitud->solicitudesDetalle->vidaUtil:''}}" autocomplete="off" required form="RegistroStep1y2">
                      <div class="input-group-addon"><b>Meses</b></div>
                    </div>
                     <div class="help-block with-errors"></div>
                </div>--}}
            </div>
        </div>
        @include('registro.nuevoregistro.pasosEdit.paso2.presentaciones')

        <!-- <a href="#" id="speak2" class="waves-effect waves-light btn btn-dark"><i class="fa fa-play" aria-hidden="true"></i></a> -->
</div>


<div align="center">
    <button type="button" class="btn btn-primary" id="btnStep2" name="btnStep2">Guardar Paso 2</button>
</div>
