 <!-- Modal CODIGOS Y MODELOS-->
<div class="modal fade" id="frmEst" aria-labelledby="frmEst" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-success">
                        <button type="button" class="close" 
                           data-dismiss="modal">
                               <span aria-hidden="true">&times;</span>
                               <span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="frmModalLabel">
                           SELECCIONES UNO O MÁS CÓDIGOS
                        </h4>
                    </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-right" id="bs-example-navbar-collapse-1">           
              <form class="navbar-form navbar-left" role="search">
              
              </form>
            </div><!-- /.navbar-collapse -->
            </br>                
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="table-responsive">
                          <table class="table table-hover" id="dt-codmods">
                            <thead class="the-box dark full">
                              <tr>
                                <th>CÓDIGO</th>
                                <th>MODELO</th>
                                <th>DESCRIPCIÓN</th>
                                <th></th>
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
                        <button type="button"  class="btn btn-primary" onclick="guardarCodigos();" data-dismiss="modal">Guardar</button>             
                    </div>
                </div>
            </div>
        </div>

  <!-- End Modal form -->