<h4>
  <span class="label label-primary">
    <strong>Ingrese la información de los solicitantes del nuevo producto farmacéutico!</strong>
  </span>
</h4>
<div id="form-step-2" role="form" data-toggle="validator">
			<input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud2" id="idSolicitud2" form="RegistroPreStep3">
      @include('registro.nuevoregistro.pasosEdit.paso3.titular')
			@include('registro.nuevoregistro.pasosEdit.paso3.represetantelegal')
			@include('registro.nuevoregistro.pasosEdit.paso3.apoderado')
			@include('registro.nuevoregistro.pasosEdit.paso3.profesional')
			@include('registro.nuevoregistro.pasosEdit.paso3.interesado')
</div>

<!-- <a href="#" id="speak3" class="waves-effect waves-light btn btn-dark" disabled><i class="fa fa-play" aria-hidden="true"></i></a> -->


<div align="center">
    <button type="button" class="btn btn-primary" id="btnStep3">Guardar Paso 3</button>
</div>
