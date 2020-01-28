
<div class="panel-heading">
  <h4 class="panel-title">AGREGAR NUEVAS PRESENTACIONES:</h4>
</div>
<div class="panel-body">

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="table-responsive">
				<table id="presentacion" class="table table-hover">
					<thead>
						<tr>
							<th>NOMBRE</th>
							<th>TIPO</th>
							<th>MATERIAL</th>
						</tr>
					</thead>
					<tbody>
					
					</tbody>
					<tfoot id="plusPresent">
						<tr>
							<th colspan="4" class="text-right">
								<span class="btn btn-primary" id="btnAddPresent"><i class="fa fa-plus"></i></span>
							</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		
</div>
<div id="newPresentacion"></div>

<div class="modal fade" id="dlgAddPresent" tabindex="-1" role="dialog" >
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-success">
                     
                        <h4 class="modal-title" id="frmModalLabel">
                            AGREGAR NUEVA PRESENTACIÓN COMERCIAL
                        </h4>
                    </div>
            
            </br>                
                    <!-- Modal Body -->
                    <div class="modal-body">
                      
                      <div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<div class="input-group">
									<div class="input-group-addon"><b>PRIMARIA:</b></div>
									<select name="empa1" id="empa1" data-placeholder="Escoge un empaque..." class="form-control" >
										<option value="0">Escoge un empaque</option>
	                      			</select>

								</div>
								
							</div>
							<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
								<div class="input-group">
									<div class="input-group-addon"><b>X</b></div>
									<input type="text" class="form-control" id="por1" name="por1" value=""  required>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<div class="input-group">
									<select name="mat1" id="mat1" data-placeholder="Escoge un material..." class="form-control">
										<option value="0">Escoge un...</option>
	                      			</select>
								</div>
							</div>
					   </div>

					   <div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<div class="input-group">
									<div class="input-group-addon"><b>SECUNDARIA:</b></div>
									<select name="empa2" id="empa2" data-placeholder="Escoge un empaque..." class="form-control" disabled >
	                      					<option value="0"></option>
	                      			</select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
								<div class="input-group">
									<div class="input-group-addon"><b>X</b></div>
									<input type="text" class="form-control" id="por2" name="por2" value="" disabled required>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<div class="input-group">
									<div class="input-group-addon"><b></b></div>
									<input type="text" class="form-control" id="mat2" name="mat2" value="" disabled required>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
								
								<input type="checkbox"  id="sec2" name="sec2" value="" disabled required>
								
							</div>
					   </div>
					   <div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<div class="input-group">
									<div class="input-group-addon"><b>TERCIARIA:</b></div>
									<select name="empa3" id="empa3" data-placeholder="Escoge un empaque..." class="form-control" disabled>
										<option value="0"></option>
	                      			</select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
								<div class="input-group">
									<div class="input-group-addon"><b>X</b></div>
									<input type="text" class="form-control" id="por3" name="por3" value="" disabled required>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<div class="input-group">
									<div class="input-group-addon"><b></b></div>
									<input type="text" class="form-control" id="mat3" name="mat3" value="" disabled required>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
								
								<input type="checkbox"  id="ter3" name="ter3" value="" disabled required>
								
							</div>
					   </div>

					   <div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<div class="input-group">
									<div class="input-group-addon"><b>MATERIAL:</b></div>
									<select id="material" name="material" class="form-control">
										
									</select>
								</div>
							</div>
							
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<div class="input-group">
									<div class="input-group-addon"><b>COLOR:</b></div>
									<select id="color" name="color" class="form-control">
										
									</select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
								
							</div>
							
					   </div>
					   <div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12col-lg-12">
								<div class="input-group">
								<div class="input-group-addon"><b>ACCESORIOS:</b></div>
								<textarea name="accesorios" id="accesorios" class="form-control text-uppercase"></textarea>
								</div>
							</div>
					   </div>

					   <div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<div class="input-group">
									<div class="input-group-addon"><b>TIPO:</b></div>
									<select name="tipoP" id="tipoP" class="form-control">
	                           
	                           			<option value="1" selected>MUESTRA MÉDICA</option>
	                           			<option value="2">PRESENTACIÓN COMERCIAL</option>
	                           			<option value="3">PRESENTACIÓN HOSPITALARÍA</option>
	                           			<option value="4">PRESENTACIÓN INSTITUCIONAL</option>
	                           			<option value="5">MUESTRO MÉDICA SIN VALOR COMERCIAL</option>
	                      			</select>  
								</div>
							</div>
                     
                    </div>
                    <!-- End Modal Body -->
                    <!-- Modal Footer -->
                    <div class="modal-footer"> 
                    	<button type="button" id="btnAddPresentacion" class="btn btn-primary"
                                data-dismiss="modal">
                                    Agregar
                        </button>                        
                        <button type="button" id="btnDeletePres" class="btn btn-default"
                                data-dismiss="modal">
                                    Cancelar
                        </button>                
                    </div>
                </div>
            </div>
		</div>
</div>