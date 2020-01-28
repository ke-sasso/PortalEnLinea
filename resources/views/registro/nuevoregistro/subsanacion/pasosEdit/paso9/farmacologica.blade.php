<h4>
  <span class="label label-primary">
    <strong>Ingrese la información de la farmacologica del nuevo producto farmacéutico!</strong>
  </span>
</h4>
<!-- BEGIN FORM WIZARD -->

<input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud8" id="idSolicitud8" form="RegistroPreStep9">
<div class="panel with-nav-tabs panel-default ">
  <div class="panel-heading">
	<ul class="nav nav-tabs nav-info">
		  <li class="active"><a href="#farmacologia" @if(!empty($campos)) @if(in_array('farmacocinetica', $campos)) class="btn-danger" @endif @endif data-toggle="tab">Farmacocinética</a></li>
		  <li><a href="#mecanismo" @if(!empty($campos)) @if(in_array('mecanismoAccion', $campos)) class="btn-danger" @endif @endif data-toggle="tab">Mecanismo de Acción</a></li>
		  <li><a href="#indicaciones" @if(!empty($campos)) @if(in_array('indicacionesTerapeuticas', $campos)) class="btn-danger" @endif @endif data-toggle="tab">Indicaciones Terapéuticas</a></li>
		  <li><a href="#contradiciones" @if(!empty($campos)) @if(in_array('contraindicaciones', $campos)) class="btn-danger" @endif @endif data-toggle="tab">Contraindicaciones</a></li>
		  <li><a href="#dosis" @if(!empty($campos)) @if(in_array('regimenDosis', $campos)) class="btn-danger" @endif @endif data-toggle="tab">Dosis</a></li>
		  <li><a href="#efectosas" @if(!empty($campos)) @if(in_array('efectosAdversos', $campos)) class="btn-danger" @endif @endif data-toggle="tab">Efectos adversos o secundarios</a></li>
		  <li><a href="#advertencias"  @if(!empty($campos)) @if(in_array('precauciones', $campos)) class="btn-danger" @endif @endif data-toggle="tab">Advertencias</a></li>
		  <li><a href="#interacciones" @if(!empty($campos)) @if(in_array('interacciones', $campos)) class="btn-danger" @endif @endif data-toggle="tab">Principales Interacciones</a></li>
		  <li><a href="#categoria" @if(!empty($campos)) @if(in_array('categoriaTerapeutica', $campos)) class="btn-danger" @endif @endif data-toggle="tab">Categoría terapéutica</a></li>
	</ul>
  </div>
	<div id="panel-collapse-1">
		<div class="tab-content">
			<div class="tab-pane fade in active" id="farmacologia">
			   @if(!empty($campos)) @if(in_array('farmacocinetica', $campos))
			   <br>
			   <div class="row" align="center">
			   		<a class="btn btn-danger btn-perspective btn-xs vals" id="farmacocinetica-val">VER OBSERVACIÓN<i class="fa  fa-times"></i></a>
              		 <input form="RegistroPreStep9" type="hidden" class="form-control" id="farmacocinetica2" name="farmacocinetica2" value="{{Crypt::encrypt('farmacocinetica2') }}" />
			   </div>
               @endif @endif
				<textarea @if(!empty($campos)) @if(!in_array('farmacocinetica', $campos)) disabled @endif @endif form="RegistroPreStep9" class="form-control farmaco" id="farm" name="farm" placeholder="Digite la farmacología general..." >{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->farmacocinetica):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="mecanismo">
			   @if(!empty($campos)) @if(in_array('mecanismoAccion', $campos))
			   <br>
			   <div class="row" align="center">
			   		<a class="btn btn-danger btn-perspective btn-xs vals" id="mecanismoAccion-val">VER OBSERVACIÓN<i class="fa  fa-times"></i></a>
              		 <input form="RegistroPreStep9" type="hidden" class="form-control" id="mecanismoAccion2" name="mecanismoAccion2" value="{{Crypt::encrypt('mecanismoAccion2') }}" />
			   </div>
               @endif @endif
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="mecaaccion" name="mecaaccion" placeholder="Digite los mecanismos de acción..." @if(!empty($campos)) @if(!in_array('mecanismoAccion', $campos)) disabled @endif @endif>{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->mecanismoAccion):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="indicaciones">
			  @if(!empty($campos)) @if(in_array('indicacionesTerapeuticas', $campos))
			   <br>
			   <div class="row" align="center">
			   		<a class="btn btn-danger btn-perspective btn-xs vals" id="indicacionesTerapeuticas-val">VER OBSERVACIÓN<i class="fa  fa-times"></i></a>
              		 <input form="RegistroPreStep9" type="hidden" class="form-control" id="indicacionesTerapeuticas2" name="indicacionesTerapeuticas2" value="{{Crypt::encrypt('indicacionesTerapeuticas2') }}" />
			   </div>
               @endif @endif
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="indicacion" name="indicacion" placeholder="Digite las indicaciones..."
				@if(!empty($campos)) @if(!in_array('indicacionesTerapeuticas', $campos)) disabled @endif @endif>{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->indicacionesTerapeuticas):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="contradiciones">
			 @if(!empty($campos)) @if(in_array('contraindicaciones', $campos))
			   <br>
			   <div class="row" align="center">
			   		<a class="btn btn-danger btn-perspective btn-xs vals" id="contraindicaciones-val">VER OBSERVACIÓN<i class="fa  fa-times"></i></a>
              		 <input form="RegistroPreStep9" type="hidden" class="form-control" id="contraindicaciones2" name="contraindicaciones2" value="{{Crypt::encrypt('contraindicaciones2') }}" />
			   </div>
               @endif @endif
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="contrad" name="contrad" placeholder="Digite las contradiciones..."
				@if(!empty($campos)) @if(!in_array('contraindicaciones', $campos)) disabled @endif @endif>{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->contraindicaciones):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="dosis">
			    @if(!empty($campos)) @if(in_array('regimenDosis', $campos))
			   <br>
			   <div class="row" align="center">
			   		<a class="btn btn-danger btn-perspective btn-xs vals" id="regimenDosis-val">VER OBSERVACIÓN<i class="fa  fa-times"></i></a>
              		 <input form="RegistroPreStep9" type="hidden" class="form-control" id="regimenDosis2" name="regimenDosis2" value="{{Crypt::encrypt('regimenDosis2') }}" />
			   </div>
               @endif @endif
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="dos" name="dos" placeholder="Digite la dosis..." @if(!empty($campos)) @if(!in_array('regimenDosis', $campos)) disabled @endif @endif>{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->regimenDosis):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="efectosas">
				  @if(!empty($campos)) @if(in_array('efectosAdversos', $campos))
			   <br>
			   <div class="row" align="center">
			   		<a class="btn btn-danger btn-perspective btn-xs vals" id="efectosAdversos-val">VER OBSERVACIÓN<i class="fa  fa-times"></i></a>
              		 <input form="RegistroPreStep9" type="hidden" class="form-control" id="efectosAdversos2" name="efectosAdversos2" value="{{Crypt::encrypt('efectosAdversos2') }}" />
			   </div>
               @endif @endif
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="efectos" name="efectos" placeholder="Digite los efectos secundarios o adversos..." @if(!empty($campos)) @if(!in_array('efectosAdversos', $campos)) disabled @endif @endif>{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->efectosAdversos):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="advertencias">
				  @if(!empty($campos)) @if(in_array('precauciones', $campos))
			   <br>
			   <div class="row" align="center">
			   		<a class="btn btn-danger btn-perspective btn-xs vals" id="precauciones-val">VER OBSERVACIÓN<i class="fa  fa-times"></i></a>
              		 <input form="RegistroPreStep9" type="hidden" class="form-control" id="precauciones2" name="precauciones2" value="{{Crypt::encrypt('precauciones2') }}" />
			   </div>
               @endif @endif
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="adv" name="adv" placeholder="Digite las advertencias..." @if(!empty($campos)) @if(!in_array('precauciones', $campos)) disabled @endif @endif>{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->precauciones):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="interacciones">
				  @if(!empty($campos)) @if(in_array('interacciones', $campos))
			   <br>
			   <div class="row" align="center">
			   		<a class="btn btn-danger btn-perspective btn-xs vals" id="interacciones-val">VER OBSERVACIÓN<i class="fa  fa-times"></i></a>
              		 <input form="RegistroPreStep9" type="hidden" class="form-control" id="interacciones2" name="interacciones2" value="{{Crypt::encrypt('interacciones2') }}" />
			   </div>
               @endif @endif
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="interaccion" name="interaccion" placeholder="Digite las principales interacciones..."  @if(!empty($campos)) @if(!in_array('interacciones', $campos)) disabled @endif @endif>{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->interacciones):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="categoria">
				  @if(!empty($campos)) @if(in_array('categoriaTerapeutica', $campos))
			   <br>
			   <div class="row" align="center">
			   		<a class="btn btn-danger btn-perspective btn-xs vals" id="categoriaTerapeutica-val">VER OBSERVACIÓN<i class="fa  fa-times"></i></a>
              		 <input form="RegistroPreStep9" type="hidden" class="form-control" id="categoriaTerapeutica2" name="categoriaTerapeutica2" value="{{Crypt::encrypt('categoriaTerapeutica2') }}" />
			   </div>
               @endif @endif

				<div class="form-group">
                <div class="row">
                     <div class="col-sm-2 col-md-2"></div>
                    <div class="col-sm-8 col-md-8">
                         <br><br>
                        <div class="input-group ">

                          <div class="input-group-addon"><b>Grupo farmacológico (Código ATC)</b></div>
                          <select form="RegistroPreStep9"  class="form-control chosen" id="codigo-atc" name="codigoatc"  @if(!empty($campos)) @if(!in_array('categoriaTerapeutica', $campos)) disabled @endif @endif>
                          @if(!empty($solicitud->farmacologia))
		                          @if($solicitud->farmacologia->detalle->categoriaTerapeutica!='')
		                          	<option value="" >NO APLICA</option>
		                            <option selected value="{{$solicitud->farmacologia->detalle->categoriaTerapeutica}}">{{$solicitud->farmacologia->detalle->codigoAtc->codigoAtc}} {{$solicitud->farmacologia->detalle->codigoAtc->nombre}}</option>
		                          @else
		                          	<option value="" disabled selected hidden>Seleccione...</option>
		                          @endif
                          @else
                          	      <option value="" disabled selected hidden>Seleccione...</option>
                          @endif
						</select>
                        </div>
                        <br><br>
                    </div>
                    <div class="col-sm-2 col-md-2"></div>
                </div>
           	 </div>

			</div>
		</div><!-- /.tab-content -->
	</div><!-- /.collapse in -->
</div><!-- /.panel .panel-success -->
<!-- END FORM WIZARD -->

<!-- <a href="#" id="speak9" class="waves-effect waves-light btn btn-dark" disabled><i class="fa fa-play" aria-hidden="true"></i></a> -->

@if(!empty($tablas))
@if(in_array('PASO9.detalleFarmacologica', $tablas))

<div align="center">
@if($solicitud->estadoDictamen==4) <button type="button" class="btn btn-primary" id="btnStep9">Guardar Paso 9</button>  @endif
</div>

@endif
@endif

