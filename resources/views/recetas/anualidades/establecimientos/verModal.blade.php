
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="panel with-nav-tabs panel-success">
                                <div class="panel-heading">

                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#panel-generales" data-toggle="tab">DATOS GENERALES</a></li>
                                        <li><a href="#panel-propietarios" data-toggle="tab">PROPIETARIOS</a></li>
                                        <li><a href="#panel-regentes" data-toggle="tab">REGENTES</a></li>
                                        <li><a href="#panel-enviar-info" data-toggle="tab">ENVIAR INFORMACIÓN</a></li>
                                    </ul>
                                </div>
                                <div id="panel-collapse-1" class="collapse in">
                                    <div class="panel-body">
                                        <div class="tab-content">
                            
                                                {{-- DATOS GENERALES --}}
                                            @include('recetas.anualidades.establecimientos.infoGeneral')
 
                                                {{-- PROPIETARIOS --}}
                                            @include('recetas.anualidades.establecimientos.infoPropietarios')
                                                
                                             {{-- REGENTES --}} 
                                             @include('recetas.anualidades.establecimientos.infoRegente')

                                              {{-- ENVIAR INFORMACIÓN --}} 
                                             @include('recetas.anualidades.establecimientos.enviarInfo')


                                                 
                                               

                                        </div><!-- /.tab-content -->
                                    </div><!-- /.panel-body -->                     
                                </div><!-- /.collapse in -->
                            </div><!-- /.panel .panel-success -->
                        </div><!-- /.col-sm-6 -->
                    </div><!-- /.row -->        
                                          