   
 <div class="form-group form-inline">
              <h4><span class="label label-info"><strong>TITULAR DEL PRODUCTO:</strong></span>@if(!empty($campos))  @if(in_array('idTitular', $campos))<a class="btn btn-danger btn-perspective btn-xs vals" id="idTitular-val"><i class="fa  fa-times"></i></a>@endif @endif</h4>

              <div id="token-idTitular"></div>
  </div>

     @if($solicitud->estadoDictamen==4)
     <div class="form-group form-inline">
        <label>Seleccione el tipo de Titular antes de buscarlo:</label><br> 
        <label class="radio-inline">
            <input type="radio" name="tipoTitular" id="inlineRadio1" value="1" required form="RegistroPreStep3" {{($solicitud->titular!=null && $solicitud->titular->tipoTitular==1?'checked':'')}}   @if(!empty($campos)) @if(!in_array('idTitular', $campos)) disabled  @endif @endif > Persona Natural
        </label>
        <label class="radio-inline">
            <input type="radio" name="tipoTitular" id="inlineRadio2" value="2" required form="RegistroPreStep3" {{($solicitud->titular!=null && $solicitud->titular->tipoTitular==2?'checked':'')}}  @if(!empty($campos)) @if(!in_array('idTitular', $campos)) disabled  @endif @endif > Persona Jurídica
        </label>
        <label class="radio-inline">
            <input type="radio" name="tipoTitular" id="inlineRadio3" value="3" required form="RegistroPreStep3" {{($solicitud->titular!=null && $solicitud->titular->tipoTitular==3?'checked':'')}}  @if(!empty($campos)) @if(!in_array('idTitular', $campos)) disabled  @endif @endif > Extranjero
        </label>
    </div>

    <br>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="input-group ">
                  <div class="input-group-addon" for="titular"><b>B&uacute;squeda del Titular:</b></div>
                  <select id="searchbox-titular" form="RegistroPreStep3" name="qe" placeholder="Buscar por nit o por nombre del titular" class="form-control"   @if(!empty($campos)) @if(!in_array('idTitular', $campos)) disabled  @endif @endif >
                  	
                  </select>
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <br>
    </div>
    @endif

    <div id="bodyTitular">
      @if(isset($soldata))
          @if($soldata->titular!=null)
              @include('registro.nuevoregistro.subsanacion.pasosEdit.paso3.bodytitular', ['propietario' => $soldata->titular])
          @endif
      @endif
    </div>
