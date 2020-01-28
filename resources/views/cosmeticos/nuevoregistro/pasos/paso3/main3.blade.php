<h4>
  <span class="label label-primary">
    <strong>Ingrese la información de los solicitantes del nuevo producto!</strong>
  </span>
</h4>
<div id="form-step-2" role="form" data-toggle="validator">
    @if(isset($solicitud))
      <input type="hidden" value="{{Crypt::encrypt($solicitud->idSolicitud)}}" name="idSolicitud2" id="idSolicitud2" form="CosPreStep3">
    @else
      <input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud2" id="idSolicitud2" form="CosPreStep3">
    @endif


    @include('cosmeticos.nuevoregistro.pasos.paso3.titular')
    @include('cosmeticos.nuevoregistro.pasos.paso3.profesional')

  <div align="center">
    <button type="button" class="btn btn-primary btn-perspective" data-autosave="false" id="btnStep3" form="CosPreStep3">Guardar Paso 3</button>
  </div>

</div>
