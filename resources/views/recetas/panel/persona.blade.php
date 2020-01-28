<div class="modal fade" id="formPersona" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-success">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                   FORMULARIO NUEVO PACIENTE
                </h4>
            </div>
            <div class="modal-body">
              <form  method="POST" class="form form-vertical" role="form" id="nuevoPaciente">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="panel-body">                                                    
                       
                          <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Nombre</b></div>
          <input type="text" class="form-control" id="nombresP" name="nombresP" maxlength="50" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="input-group">
                                    <div class="input-group-addon"><b>Apellidos</b></div>
          <input type="text" class="form-control" id="apellidosP" name="apellidosP" maxlength="50" autocomplete="off" required>
                                    </div>
                             </div>

                               
                            </div>
                         
                          <br><br>
                           <div class="form-group"> 
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Sexo</b></div>
                              <select  class="form-control" id="sexo" name="sexo">
                                  <option value="MASCULINO" selected>Masculino</option>
                                  <option value="FEMENINO">Femenino</option>
                              </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Edad</b></div>
                            <input type="number" maxlength="2" min="0" class="form-control" id="edad" name="edad" value="" autocomplete="off" required>
                                </div>
                            </div>

                            </div>
   
                        <br>  <br>              
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Tipo documento:</b></div>
                               <select  class="form-control" id="tipo" name="tipo" onclick="habilitarInput();">
                                  <option value="DUI" selected>DUI</option>
                                  <option value="PASAPORTE">PASAPORTE</option>
                                  <option value="CARNETMINORIDAD">CARNET MINORIDAD</option>
                                  <option value="CARNETRESIDENTE">CARNET DE RESIDENCIA</option>
                              </select>
                                </div>
                            </div>

                             <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="N1">
                                    <div class="input-group">
                                    <div class="input-group-addon"><b># Documento DUI</b></div>
                                    <input type="text" class="form-control dui_masking" id="numDocumentoP" name="numDocumentoP" autocomplete="off">
                                    </div>
                             </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="N2" style="display: none;">
                                    <div class="input-group">
                                    <div class="input-group-addon"><b># Documento</b></div>
                                    <input type="text" class="form-control" id="numDocumento2" maxlength="25" name="numDocumento2" autocomplete="off">
                                    </div>
                              </div>                                      
                                                                                         
                    </div>
                    <br><br>
                     <div class="form-group">
                
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                             <div class="input-group">
                                    <div class="input-group-addon"><b>Domicilio</b></div>
                                     <textarea class="form-control" name="domicilio" id="domicilio" cols="2" rows="3" required></textarea>
                                </div>
                               
                        </div>                                       
                                                                                         
                    </div>
                  
                              <br><br>    <br>  <br>         
                 <div class=" text-center">
                        <button type="button" class="btn btn-success" id="guardarPaciente">Guardar</button>
                        <button type="button" class="btn btn-default"
                            data-dismiss="modal">
                                Cancelar</button>
                    </div>             
                    
                    
                </form>     
               </div>
            </div>
            <!-- End Modal Body -->
            <!-- Modal Footer -->
          
        </div>
    </div>
    </div>
   
