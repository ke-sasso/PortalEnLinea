<h4>
  <span class="label label-primary">
    <strong>Suba un documento por cada requisito solicitado!</strong> &nbsp;
  </span>
  <a href="{{route('preregistrorv.imprimir.formulario',['idSolicitud'=>Crypt::encrypt($solicitud->idSolicitud)])}}" target="_blank" class="btn btn-info btn-xs">Imprimir Formulario<i class="fa fa-print"></i></a>

   <a href="{{route('verlista.doc.numeracion',['idSolicitud'=>Crypt::encrypt($solicitud->idSolicitud)])}}" target="_blank" class="btn btn-info btn-xs">Imprimir paginación de documentos<i class="fa fa-print"></i></a>
</h4>
<!-- BEGIN FORM WIZARD -->

		<input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud9" id="idSolicitud9" form="RegistroPreStep10" />
		<div id="token-doc"></div>
		<div id="bodyDocumentosExpEdit">


		</div><!-- /.panel .panel-success -->

<!-- END FORM WIZARD -->

<!-- <a href="#" id="speak10" class="waves-effect waves-light btn btn-dark" disabled><i class="fa fa-play" aria-hidden="true"></i></a> -->


<!--
<div align="center">
	@if($solicitud->estadoDictamen==4)<button type="button" class="btn btn-primary" id="btnStep10">Guardar Paso 10</button>@endif
</div>-->

