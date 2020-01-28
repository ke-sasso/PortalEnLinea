

<div class="tab-pane fade" id="generales">

<div class="panel panel-success">
	<div class="panel-heading" role="tab" id="headingSix">
	  <h3 id="leyendTramite" class="panel-title">
	      
	  </h3>
	</div>

	<div class="panel-body">
		<form method="POST" id='frmSolicitudPost' enctype="multipart/form-data" action="{{ route('cospostregistro.cambio.empaque') }}" class="form-horizontal">
				<div class="panel-group" id="accordion1" role="tablist" aria-multiselectable="true">
				    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<label>Seleccione el tipo de producto:</label>
							<div class="input-group">
								<input type="radio"  name="tipo" id="tipo" value="COS" checked>COMÉSTICO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="tipo" id="tipo" value="HIG" >HIGIÉNICO
							</div>
					</div>
					<br><br><br>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<label>Seleccione el tipo de t&iacute;tulo para este tr&aacute;mite:</label>
							<div class="input-group">
								<div class="input-group-addon"><b>T&Iacute;TULO:</b></div>
								<select name="perfil" id="perfil" class="form-control">
                           			<option value="" >Seleccione el titulo...</option>
                           			@foreach($perfiles as $perfil)
                           				@if($perfil->PERFIL==='PFR')
                           					<option value="PROFESIONAL">PROFESIONAL RESPONSABLE</option>
                           				@endif
                           				@if($perfil->PERFIL==='APO')
                           					<option value="APODERADO">APODERADO</option>
                           				@endif
                           				@if($perfil->PERFIL==='PROP')
                           					<option value="PROPIETARIO">PROPIETARIO</option>
                           				@endif
                           				@if($perfil->PERFIL==='REPR')
                           					<option value="REPRESENTANTE">REPRESENTANTE LEGAL</option>
                           				@endif
                           			@endforeach
                      			</select>  
							</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="elemtByTramite">
							
					</div>
					<br><br><br><br><br>
					<div class="panel panel-success">
              		
						<div class="panel-heading" role="tab" id="headingSix">
						  <h4 class="panel-title">
						      PRODUCTO:
						  </h4>
						</div>				
						<div class="panel-body">
							<div class="container-fluid the-box">
								<div class="form-group">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
										<div class="input-group">
											<div class="input-group-addon"><b>NUM. REGISTRO</b></div>
											<input type="hidden" class="form-control" id="idproducto" name="idproducto" value="">
											<input type="text" class="form-control" id="txtidproducto" name="txtidproducto" value="" readonly required>
											<span class="input-group-btn">
												<span id="btnBuscarProducto" class="btn btn-primary"><i class="fa fa-search"></i></span>
											</span>
											
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="nombre" style="display: none">
										<div class="input-group">
											<div class="input-group-addon"><b>NOMBRE:</b></div>
											<input type="hidden" class="form-control" id="nombreComercial" name="nombreComercial" value="">
											<input type="text" class="form-control" id="nomcomercial" name="nomcomercial" value=""  required readonly>
										</div>
									</div>
									
								</div>
								
								<div class="form-group">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="renovacion" style="display: none">
										<div class="input-group">
											<div class="input-group-addon"><b>RENOVACION</b></div>
											<input type="text" class="form-control" id="txtrenovacion" name="txtrenovacion" value=""  required readonly>
										</div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="vigencia" style="display: none">
										<div class="input-group">
											<div class="input-group-addon"><b>VIGENCIA</b></div>
											<input type="text" class="form-control" id="txtvigencia" name="txtvigencia" value="" required readonly>
										</div>
									</div>	
								</div>
							</div>															
						</div>	
					</div>	
        {{--  
			<div id="tragnral" class="panel panel-success">
				@include('sim.tramites.tramitegnral')
			</div>
		
			--}}
			<div id="panel-mandamiento" class="panel panel-success">
                  <div class="panel-heading">
                    <h4 class="panel-title">NUMERO DE MANDAMIENTO</h4>
                  </div>
                  <div class="panel-body">
                    <table width="100%" class="table table-stripped table-hover">
                      
                      <tr>
                      <td>
                        <div class="checkbox">
                          <label>
                          <div class="input-group col-md-10 col-lg-8" >
                            <div class="input-group-addon">MANDAMIENTO CANCELADO POR DERECHOS DE TRÁMITE</div>
                              <input type="number" class="form-control" min="0" id="num_mandamiento" name="mandamiento" value="0" required>
                          </div>
                          <div align="right">
                          <button  type="button" name="validar" id="validar" class="btn btn-primary btn-perspective">Validar</i></button>
                          </div>
                          </label>
                        </div>
                      </td>
                      </tr>
                
                    </table>
                    
                  </div>
            </div>  
			<div class="panel panel-success">
				<div class="panel-heading" role="tab" id="headingFive">
				  <h4 class="panel-title">
				      SELECCIONE LOS DOCUMENTOS A PRESENTAR EN EL TRAMITE:
				  </h4>
				</div>
				
					<div class="panel-body">
						<div class="table-responsive">
							<table width="100%" class="table table-hover table-striped" id="documentos">
								<tbody>
									
								</tbody>
							</table>
						</div>
						
					</div>
				
			</div>
				<input type="hidden" name="_token" id="token" value="{{csrf_token()}}" />
				<input type="hidden" name="idTramite" id="idTramite" value="">
				<div class="panel panel-footer text-center" id="guardar">
					
					
				</div>
			</form>
		
	</div>
</div>

<!-- Modal Producto-->
		    <div class="modal fade modal-center" id="dlgProductos"  tabindex="-1" role="dialog" >
		        <div class="modal-dialog modal-lg" >
		            <div class="modal-content">
		                <!-- Modal Header -->
		                <div class="modal-header bg-success">
		                    <button type="button" class="close" 
		                       data-dismiss="modal">
		                           <span aria-hidden="true">&times;</span>
		                           <span class="sr-only">Close</span>
		                    </button>
		                    <h4 class="modal-title" id="frmModalLabel">
		                        B&Uacute;SQUEDA DE PRODUCTOS
		                    </h4>
		                </div>
						
						</br>                
		                <!-- Modal Body -->
		                <div class="modal-body">
		                	
			                    <div class="table">
			                    	<table width="100%" class="table table-hover" id="dt-producto">
			                    		<thead class="the-box dark full">
			                    			<tr>
			                    				<th>No.REGISTRO</th>
			                    				<th>NOMBRE</th>
			                    				<th>VIGENCIA</th>
			                    				<th>RENOVACION</th>
			                    				<th>-</th>
			                    			</tr>
			                    		</thead>
			                    		<tbody>
			                    		
			                    		</tbody>
			                    	</table>
			                    </div>
			               
		                </div>
		                <!-- End Modal Body -->
		                <!-- Modal Footer -->
		                <div class="modal-footer">		                    
		                    <button type="button" class="btn btn-default"
		                            data-dismiss="modal">
		                                Cancelar
		                    </button>                
		                </div>
		            </div>
		        </div>
		    </div>

		    

	<!-- End Modal form -->	