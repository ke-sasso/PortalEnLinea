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
                      <div class="input-group-addon" for="nomProd">
                @if(!empty($campos)) @if(in_array('nombreComercial', $campos))
               <a class="btn btn-danger btn-perspective btn-xs vals" id="nombreComercial-val"><i class="fa  fa-times"></i></a>
               <input  form="RegistroStep1y2" type="hidden" class="form-control" id="nombreComercial2" name="nombreComercial2" value="{{Crypt::encrypt('nombreComercial2') }}" />
               @endif @endif
                      <b>Nombre del producto (como se comercializará en El Salvador):</b></div>
                      <input type="text" class="form-control" id="nom_prod" name="nom_prod" value="{{$solicitud->solicitudesDetalle->nombreComercial}}" autocomplete="off" required
                      @if(!empty($campos)) @if(!in_array('nombreComercial', $campos)) disabled @endif @endif form="RegistroStep1y2">
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
            </div>

        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-6 col-md-2">
                    <div class="input-group ">
                      <div class="input-group-addon" for="innovador">
                @if(!empty($campos)) @if(in_array('innovador', $campos))
               <a class="btn btn-danger btn-perspective btn-xs vals" id="innovador-val"><i class="fa  fa-times"></i></a>
               <input form="RegistroStep1y2" type="hidden" class="form-control" id="innovador2" name="innovador2" value="{{Crypt::encrypt('innovador2') }}" />
               @endif @endif
                      <b>Innovador:</b></div>
                      <select name="innovador" id="innovador" class="form-control" form="RegistroStep1y2"   @if(!empty($campos)) @if(!in_array('innovador', $campos)) disabled @endif @endif>
                      	<option value="1" {{$solicitud->solicitudesDetalle->innovador==1?'selected':''}}>SI</option>
                      	<option value="0" {{$solicitud->solicitudesDetalle->innovador==0?'selected':''}}>NO</option>
                      </select>
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="input-group ">
                      <div class="input-group-addon" for="origen">
                @if(!empty($campos)) @if(in_array('origenProducto', $campos))
               <a class="btn btn-danger btn-perspective btn-xs vals" id="origenProducto-val"><i class="fa  fa-times"></i></a>
               <input  form="RegistroStep1y2" type="hidden" class="form-control" id="origenProducto2" name="origenProducto2" value="{{Crypt::encrypt('origenProducto2') }}" />
               @endif @endif
                      <b>Procedencia:</b></div>
                      <select name="origen" id="origen" class="form-control" form="RegistroStep1y2" @if(!empty($campos)) @if(!in_array('origenProducto', $campos)) disabled @endif @endif>
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
                      <div class="input-group-addon" for="tipoMedicamento">
               @if(!empty($campos)) @if(in_array('tipoMedicamento', $campos))
               <a class="btn btn-danger btn-perspective btn-xs vals" id="tipoMedicamento-val"><i class="fa  fa-times"></i></a>
               <input form="RegistroStep1y2" type="hidden" class="form-control" id="tipoMedicamento2" name="tipoMedicamento2" value="{{Crypt::encrypt('tipoMedicamento2') }}" />
               @endif @endif
                      <b>Tipo de medicamento:</b></div>
                        <select name="tipoMedicamento" id="tipoMedicamento" style="width:100%;" class="form-control select2-selection--single" required form="RegistroStep1y2"   @if(!empty($campos)) @if(!in_array('tipoMedicamento', $campos)) disabled @endif @endif>
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
                      <div class="input-group-addon" for="formaFarm">
                @if(!empty($campos)) @if(in_array('formaFarmaceutica', $campos))
               <a class="btn btn-danger btn-perspective btn-xs vals" id="formaFarmaceutica-val"><i class="fa  fa-times"></i></a>
               <input form="RegistroStep1y2" type="hidden" class="form-control" id="formaFarmaceutica2" name="formaFarmaceutica2" value="{{Crypt::encrypt('formaFarmaceutica2') }}" />
               @endif @endif
                      <b>Forma Farmaceutica:</b></div>
                      <select name="formafarm" id="formaFarm" style="width:100%;" class="form-control select2-single" required form="RegistroStep1y2"
                       @if(!empty($campos)) @if(!in_array('formaFarmaceutica', $campos)) disabled @endif @endif>
                          <option value="" disabled selected hidden>Seleccione...</option>
                      </select>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="col-sm-6 col-md-6">
                    <div class="input-group ">
                      <div class="input-group-addon" for="viaAdmin">
                       @if(!empty($campos)) @if(in_array('viaAdmon', $campos))
                       <a class="btn btn-danger btn-perspective btn-xs vals" id="viaAdmon-val"><i class="fa  fa-times"></i></a>
                       <input form="RegistroStep1y2" type="hidden" class="form-control" id="viaAdmon2" name="viaAdmon2" value="{{Crypt::encrypt('viaAdmon2') }}" />
                      @endif @endif
                      <b>Via de administraci&oacute;n:</b></div>
                      <select name="viaAdmin" id="viaAdmin" style="width:100%;" class="form-control select2-single" required form="RegistroStep1y2"
                       @if(!empty($campos)) @if(!in_array('viaAdmon', $campos)) disabled @endif @endif>
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
                      <div class="input-group-addon" for="bioequi">
                      @if(!empty($campos)) @if(in_array('bioequivalente', $campos))
                       <a class="btn btn-danger btn-perspective btn-xs vals" id="bioequivalente-val"><i class="fa  fa-times"></i></a>
                       <input form="RegistroStep1y2" type="hidden" class="form-control" id="bioequivalente2" name="bioequivalente2" value="{{Crypt::encrypt('bioequivalente2') }}" />
                      @endif @endif
                      <b>Bioequivalente:</b></div>
                      <select name="bioequi" id="bioequi" style="width:100%;" class="form-control " required form="RegistroStep1y2"
                      @if(!empty($campos)) @if(!in_array('bioequivalente', $campos)) disabled @endif @endif>
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
                      <div class="input-group-addon" for="modalidad"><b>
                      @if(!empty($campos)) @if(in_array('modalidadVenta', $campos))
                       <a class="btn btn-danger btn-perspective btn-xs vals" id="modalidadVenta-val"><i class="fa  fa-times"></i></a>
                       <input form="RegistroStep1y2" type="hidden" class="form-control" id="modalidadVenta2" name="modalidadVenta2" value="{{Crypt::encrypt('modalidadVenta2') }}" />
                      @endif @endif
                      Modalidad de venta:</b></div>
                      <select name="modalidad" id="modalidad" style="width:100%;" class="form-control select2-single" required form="RegistroStep1y2"
                      @if(!empty($campos)) @if(!in_array('modalidadVenta', $campos)) disabled @endif @endif>
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
                        <div class="input-group-addon" for="udosis">
                       @if(!empty($campos)) @if(in_array('unidadDosis', $campos))
                       <a class="btn btn-danger btn-perspective btn-xs vals" id="unidadDosis-val"><i class="fa  fa-times"></i></a>
                       <input  form="RegistroStep1y2" type="hidden" class="form-control" id="unidadDosis2" name="unidadDosis2" value="{{Crypt::encrypt('unidadDosis2') }}" />
                      @endif @endif
                        <b>Unidad de dosis:</b></div>
                        <input type="text" class="form-control" id="udosis" name="udosis"  value="{{$solicitud->solicitudesDetalle->unidadDosis!=null?$solicitud->solicitudesDetalle->unidadDosis:''}}" autocomplete="off" required form="RegistroStep1y2" @if(!empty($campos)) @if(!in_array('unidadDosis', $campos)) disabled @endif @endif>
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
          </div>
        </div>
          <div class="form-group">
            <div class="row">
               <div class="col-sm-12 col-md-12">
                    <div class="input-group ">
                        <div class="input-group-addon" for="formula">
                        @if(!empty($campos)) @if(in_array('formula', $campos))
                       <a class="btn btn-danger btn-perspective btn-xs vals" id="formula-val"><i class="fa  fa-times"></i></a>
                       <input form="RegistroStep1y2" type="hidden" class="form-control" id="formula2" name="formula2" value="{{Crypt::encrypt('formula2') }}" />
                      @endif @endif
                        <b>Formula (equivalencia de sal a base, excesos, purezas, moléculas de hidratación y perdida por secado):</b></div>
                        <textarea class="form-control" id="formula" name="formula" value=""  rows="2" autocomplete="off" required form="RegistroStep1y2"
                         @if(!empty($campos)) @if(!in_array('formula', $campos)) disabled @endif @endif>{{$solicitud->solicitudesDetalle->formula!=null?$solicitud->solicitudesDetalle->formula:''}}</textarea>
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="input-group ">
                        <div class="input-group-addon" for="excipientes">
                      @if(!empty($campos)) @if(in_array('excipientes', $campos))
                       <a class="btn btn-danger btn-perspective btn-xs vals" id="excipientes-val"><i class="fa  fa-times"></i></a>
                       <input  form="RegistroStep1y2" type="hidden" class="form-control" id="excipientes2" name="excipientes2" value="{{Crypt::encrypt('excipientes2') }}" />
                      @endif @endif
                        <b>Excipientes:</b></div>
                        <textarea class="form-control" id="excipientes" name="excipientes"   rows="2" autocomplete="off" required form="RegistroStep1y2"
                        @if(!empty($campos)) @if(!in_array('excipientes', $campos)) disabled @endif @endif>{{$solicitud->solicitudesDetalle->excipientes!=null?$solicitud->solicitudesDetalle->excipientes:''}}</textarea>
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        @include('registro.nuevoregistro.subsanacion.pasosEdit.paso2.principiosactivos')

        @include('registro.nuevoregistro.subsanacion.pasosEdit.paso2.vidautilempaque')

        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 col-md-9">
                    <div class="input-group ">
                      <div class="input-group-addon" for="condAlmacenamiento">
                      @if(!empty($campos)) @if(in_array('condicionesAlmacenaje', $campos))
                       <a class="btn btn-danger btn-perspective btn-xs vals" id="condicionesAlmacenaje-val"><i class="fa  fa-times"></i></a>
                       <input form="RegistroStep1y2" type="hidden" class="form-control" id="condicionesAlmacenaje2" name="condicionesAlmacenaje2" value="{{Crypt::encrypt('condicionesAlmacenaje2') }}" />
                      @endif @endif
                      <b>Condiciones de almacenamiento:</b></div>
                      <input type="text" class="form-control" id="condAlmacenamiento" name="condAlmacenamiento" value="{{$solicitud->solicitudesDetalle->condicionesAlmacenaje!=null?$solicitud->solicitudesDetalle->condicionesAlmacenaje:''}}" autocomplete="off" required form="RegistroStep1y2" @if(!empty($campos)) @if(!in_array('condicionesAlmacenaje', $campos)) disabled @endif @endif>
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
                {{--<div class="col-sm-12 col-md-3">
                    <div class="input-group ">
                      <div class="input-group-addon" for="vidaUtil">
                       @if(!empty($campos)) @if(in_array('vidaUtil', $campos))
                       <a class="btn btn-danger btn-perspective btn-xs vals" id="vidaUtil-val"><i class="fa  fa-times"></i></a>
                       <input form="RegistroStep1y2" type="hidden" class="form-control" id="vidaUtil2" name="vidaUtil2" value="{{Crypt::encrypt('vidaUtil2') }}" />
                      @endif @endif
                      <b>Vida Util (Propuesta):</b></div>
                      <input type="number" class="form-control" id="vidaUtil" name="vidaUtil" min="0" value="{{$solicitud->solicitudesDetalle->vidaUtil!=null?$solicitud->solicitudesDetalle->vidaUtil:''}}" autocomplete="off" required form="RegistroStep1y2" @if(!empty($campos)) @if(!in_array('vidaUtil', $campos)) disabled @endif @endif>
                      <div class="input-group-addon"><b>Meses</b></div>
                    </div>
                     <div class="help-block with-errors"></div>
                </div>--}}
            </div>
        </div>

        @include('registro.nuevoregistro.subsanacion.pasosEdit.paso2.presentaciones')
        <!-- <a href="#" id="speak2" class="waves-effect waves-light btn btn-dark"><i class="fa fa-play" aria-hidden="true"></i></a> -->
</div>

@if(!empty($tablas))
@if(in_array('PASO2.generalesProducto', $tablas) || in_array('PASO2.empaquePresentacion', $tablas) || in_array('PASO2.principiosActivos', $tablas) || in_array('PASO2.vidautilEmpaque', $tablas))
<div align="center">
@if($solicitud->estadoDictamen==4) <button type="button" class="btn btn-primary" id="btnStep2" name="btnStep2">Guardar Paso 2</button> @endif
</div>
@endif
@endif
