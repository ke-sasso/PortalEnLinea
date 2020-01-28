  <ul class="inline-popups">
      <a  class="btn btn-info btn-perspective" href="#text-popup" data-effect="mfp-zoom-in">Instrucciones para subir documentos</a>
  </ul>
<h4>
  <span class="label label-primary">
    <strong>Suba un documento por cada requisito solicitado!</strong> &nbsp;
  </span>
  <a href="{{route('preregistrorv.imprimir.formulario',['idSolicitud'=>Crypt::encrypt($solicitud->idSolicitud)])}}" target="_blank" class="btn btn-info btn-xs">Imprimir Formulario<i class="fa fa-print"></i></a>
</h4>
<!-- BEGIN FORM WIZARD -->

		<input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud9" id="idSolicitud9" form="RegistroPreStep10" />
		<div id="bodyDocumentosExpEdit">


		</div><!-- /.panel .panel-success -->

								<div id="text-popup" class="white-popup mfp-with-anim mfp-hide">
						            <b>Nota: cada documento que subira de forma invidual.</b><br><br>
									1) Dar clic al boton examinar, para seleccionar el archivo. (solo formato .pdf)
									{!! Html::image('img/urv/a1.png') !!}<br><br>
									2) Luego de seleccionar el documento, dar clic al botón "Subir archivo"
									{!! Html::image('img/urv/a2.png') !!}<br><br>
									3) El documento se guardo y se habilita el botón para eliminarlo
									{!! Html::image('img/urv/a3.png') !!}
								</div>


<!-- <a href="#" id="speak10" class="waves-effect waves-light btn btn-dark" disabled><i class="fa fa-play" aria-hidden="true"></i></a> -->


<!--
<div align="center">
	<button type="button" class="btn btn-primary" id="btnStep10">Guardar Paso 10</button>
</div>-->
