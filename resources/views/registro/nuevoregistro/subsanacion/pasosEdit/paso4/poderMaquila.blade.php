

<div class="container-fluid the-box">
        <h4>
            <span class="label label-info">
                <strong>CONTRATO DE MAQUILA</strong>
            </span>
        </h4>
        <div class="form-group form-inline">
            <label>¿Posee contrato de maquila?</label>

            @if($solicitud->solicitudesDetalle->poderMaquila)
                     <label class="radio-inline">
                     <input type="radio" name="valContratoMaquila" id="valContratoMaquila" value="1" @if($solicitud->solicitudesDetalle->poderMaquila==1) checked @endif required  form="RegistroPreStep4"> SI
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="valContratoMaquila" id="valContratoMaquila" value="0" @if($solicitud->solicitudesDetalle->poderMaquila==0) checked @endif required form="RegistroPreStep4"> NO
                    </label>
            @else
                     <label class="radio-inline">
                                <input type="radio" name="valContratoMaquila" id="valContratoMaquila" value="1" required form="RegistroPreStep4"> SI
                    </label>
                    <label class="radio-inline">
                                <input type="radio" name="valContratoMaquila" id="valContratoMaquila" value="0" checked required form="RegistroPreStep4"> NO
                    </label>
            @endif
        </div>
        <div id="datosContratoMaquila" @if(!$solicitud->solicitudesDetalle->poderMaquila) style="display: none;" @endif>
                 <div class="container-fluid the-box">
                 <div class="row">
                     <div class="col-sm-12 col-md-12 col-lg-12">
                        <h4>
                          <span class="label label-primary">
                            <strong>Ingrese un número de contrato de fabricación a terceros (maquila)  para <b>fabricante principal</b> </strong>
                          </span>@if(!empty($campos)) @if(in_array('poderFabprincipal', $campos))<a class="btn btn-danger btn-perspective btn-xs vals" id="poderFabprincipal-val"><i class="fa  fa-times"></i></a>@endif @endif
                        </h4>
                           <div id="token-poderFabprincipal"></div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-10 col-md-10 col-lg-10">
                                       @if(!empty($campos)) @if(in_array('poderFabprincipal', $campos))
                                        <div class="input-group ">
                                            <div class="input-group-addon"><b>B&uacute;squeda de poder:</b></div>
                                            <select id="searchbox-poderMaquila1" name="searchbox-poderMaquila1" placeholder="Buscar por poder para fabricante principal" class="form-control"></select>
                                        </div>
                                        @endif  @endif
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
                                        @if($solicitud->poderfabprincipal)
                                         <tr><input type="hidden" id="poderFabPrincipal" name="poderFabPrincipal" form="RegistroPreStep4" value="{{$solicitud->poderfabprincipal->idPoder}}"><td>{{$solicitud->poderfabprincipal->idPoder}}</td></tr>
                                        @endif
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
                          @if(!empty($campos))  @if(in_array('poderFabAlterno', $campos))<a class="btn btn-danger btn-perspective btn-xs vals" id="poderFabAlterno-val"><i class="fa  fa-times"></i></a>@endif @endif
                        </h4>
                         <div id="token-poderFabAlterno"></div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-10 col-md-10 col-lg-10">
                                       @if(!empty($campos)) @if(in_array('poderFabAlterno', $campos))
                                        <div class="input-group ">
                                            <div class="input-group-addon"><b>B&uacute;squeda de poder:</b></div>
                                              <select id="searchbox-poderMaquila2" name="searchbox-poderMaquila2" placeholder="Buscar por poder para fabricante alterno" class="form-control"></select>
                                        </div>
                                        @endif @endif
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
                                        @if(count($solicitud->poderfabAlterno)>0)
                                          @foreach($solicitud->poderfabAlterno as $poderalter)
                                           <tr><input type="hidden" id="poderFabAlterno" name="poderFabAlterno[]" form="RegistroPreStep4" value="{{$poderalter->idPoder}}">
                                            <td>{{$poderalter->idPoder}}</td>
                                               @if(!empty($campos)) @if(in_array('poderFabAlterno', $campos)) <td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td> @endif @endif
                                           </tr>
                                          @endforeach
                                        @endif
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
                           @if(!empty($campos)) @if(in_array('poderFabAcondicionador', $campos))<a class="btn btn-danger btn-perspective btn-xs vals" id="poderFabAcondicionador-val"><i class="fa  fa-times"></i></a>@endif @endif
                        </h4>
                         <div id="token-poderFabAcondicionador"></div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-10 col-md-10 col-lg-10">
                                       @if(!empty($campos)) @if(in_array('poderFabAcondicionador', $campos))
                                        <div class="input-group ">
                                            <div class="input-group-addon"><b>B&uacute;squeda de poder:</b></div>
                                            <select id="searchbox-poderMaquila3" name="searchbox-poderMaquila3" placeholder="Buscar por poder para fabricante acondicionador" class="form-control"></select>
                                        </div>
                                        @endif @endif
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
                                        @if(count($solicitud->poderfabAcondicionador)>0)
                                          @foreach($solicitud->poderfabAcondicionador as $poderAcondi)
                                           <tr><input type="hidden" id="poderFabAcondicionador" name="poderFabAcondicionador[]" form="RegistroPreStep4" value="{{$poderAcondi->idPoder}}">
                                            <td>{{$poderAcondi->idPoder}}</td>
                                            @if(!empty($campos)) @if(in_array('poderFabAcondicionador', $campos)) <td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td> @endif @endif
                                           </tr>
                                          @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                    </div>
                 </div>
               </div>

        </div><!-- div datosContrato -->
</div>
