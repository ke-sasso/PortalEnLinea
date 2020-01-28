 <div class="modal fade" id="frmProRecetados" tabindex="-1" role="dialog" >
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
                   El paciente posee las siguientes recetas prescritas:
                </h4>
            </div>
            <div class="modal-body">
               <div class="table-responsive">
                          <table class="table table-hover" id="dt-productosRecetados" width="100%">
                            <thead class="the-box dark full">
                              <tr>
                                <th>FECHA EMITIDA</th>
                                <th>PRODUCTO CONTROLADO</th>
                                <th>CANTIDAD PRESCRITA</th>
                                <th>ESTADO</th>
                                <th>FECHA RETIRO</th>
                              </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                          </table>
                        </div>
               </div>
                   <div align="center">  
                <h4><b>¿Desea continuar?</b></h4>
                <br>
            <a class="btn btn-sm btn-success btn-perspective" onclick="enviarDatosPost();" >&nbsp;SI&nbsp;</a>
             <button type="button" class="btn btn-sm btn-danger btn-perspective" data-dismiss="modal">NO</button>                                     
            </div>
            <br>
            </div>
            <!-- End Modal Body -->
            <!-- Modal Footer -->

          
        </div>
  </div>
