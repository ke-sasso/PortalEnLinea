    <div class="form-group">
     @if($idvista==1)
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <div class="input-group ">
                      <div class="input-group-addon" for="formaFarm"><b>País de origen:</b></div>
                      <select name="paisReconocimiento" id="paisReconocimiento" style="width:100%;" class="form-control" required form="RegistroStep1y2">
                          <option selected value="">Seleccione un país...</option>
                          @foreach($paises as $pa)
                            <option value="{{$pa->codigoId}}">{{$pa->nombre}}</option>
                          @endforeach
                      </select>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="col-sm-6 col-md-6">
                    <div class="input-group ">
                      <div class="input-group-addon" for="viaAdmin"><b>Número de registro:</b></div>
                      <input type="text" name="noregistrorecono" id="noregistrorecono" class="form-control" required form="RegistroStep1y2">
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
            </div>
       @else
           <div class="row">
                <div class="col-sm-6 col-md-6">
                    <div class="input-group ">
                      <div class="input-group-addon" for="formaFarm"><b>País de origen:</b></div>
                      <select name="paisReconocimiento" id="paisReconocimiento" style="width:100%;" class="form-control" required form="RegistroStep1y2">
                          @foreach($paises as $pa)
                            <option value="{{$pa->codigoId}}" @if($solicitud->idPaisReconocimiento==$pa->codigoId) selected @endif >{{$pa->nombre}}</option>
                          @endforeach
                      </select>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="col-sm-6 col-md-6">
                    <div class="input-group ">
                      <div class="input-group-addon" for="viaAdmin"><b>Número de registro:</b></div>
                      <input type="text" name="noregistrorecono" id="noregistrorecono" class="form-control" required form="RegistroStep1y2" value="{{$solicitud->numeroRegistroReconocimiento}}">
                    </div>
                     <div class="help-block with-errors"></div>
                </div>
            </div>


       @endif

  </div>