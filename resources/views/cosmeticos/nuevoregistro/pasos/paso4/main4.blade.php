<h4>
  <span class="label label-primary">
    <strong>Ingrese la información de los fabricantes e importadores del nuevo producto!</strong>
  </span>
</h4>
<div id="form-step-3" role="form" data-toggle="validator">

    @if(isset($solicitud))
      <input type="hidden" value="{{Crypt::encrypt($solicitud->idSolicitud)}}" name="idSolicitud3" id="idSolicitud3" form="CosPreStep4">
    @else
      <input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud3" id="idSolicitud3" form="CosPreStep4">
    @endif

    @include('cosmeticos.nuevoregistro.pasos.paso4.fabricantes')

    @include('cosmeticos.nuevoregistro.pasos.paso4.importadores')

  <div align="center">
    <button type="button" class="btn btn-primary btn-perspective" data-autosave="false" id="btnStep4" form="CosPreStep4">Guardar Paso 4</button>
  </div>
</div>
