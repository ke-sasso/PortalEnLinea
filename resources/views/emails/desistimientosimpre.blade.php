<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Solicitud de Desistimiento de trámite de pre-registro</title>
</head>
<body>
	<p>Se le notifica que el usuario <b>{{$solicitudpre->solicitante}}</b> y con NIT: <b>{{$solicitudpre->NIT_SOLICITANTE}}</b>, ha solicitado el dia <b>{{$solicitudpre->FECHA_MODIFICACION}}</b>, el desistimiento de tramite pre registro con número de PIM: <b>{{$solicitudpre->PIM}}</b></p>
	<p></p>
	<div class="panel panel-success">
                            
            <div class="panel-body">
              <div class="container-fluid the-box">
                <div class="form-group">
                  <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                   
                  </div>
                  <div class="col-xs-1 col-sm-1 col-md-3 col-lg-3">
                    
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"  >
                    <div class="input-group">
                      <div class="input-group-addon"><b>FECHA DE PRESENTACIÓN:</b></div>
                      <input type="text" class="form-control" id="fecha" name="fecha" value="{{$solicitudpre->FECHA_CREACION}}"  disabled>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                    <div class="input-group">
                      <div class="input-group-addon"><b>NOMBRE DEL INSUMO:</b></div>
                      <input type="text" class="form-control" id="nominsumo" name="nominsumo" value="{{$solicitudpre->NOMBRE_INSUMO}}"  required disabled>
                    </div>
                  </div>
                  
                </div>
                
                <div class="form-group">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="input-group">
                      <div class="input-group-addon"><b>SOLICITANTE:</b></div>
                      <input type="text" class="form-control" id="solicitante" name="solicitante" value="{{$solicitudpre->SOLICITANTE}}"  required disabled>
                    </div>
                  </div>
                    
                </div>

              <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
                    <div class="input-group">
                      <div class="input-group-addon"><b>NUM. MANDAMIENTO:</b></div>
                      <input type="text" class="form-control" id="mandamiento" name="mandamiento" value="{{$solicitudpre->NUMERO_MANDAMIENTO}}" required disabled>
                    </div>
                  </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
                    <div class="input-group">
                      <div class="input-group-addon"><b>CONTACTO:</b></div>
                      <input type="text" class="form-control" id="contacto" name="contacto" value="{{$solicitudpre->NOMBRE_CONTACTO}}" required disabled>
                    </div>
                  </div>
              </div>

              <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10" >
                    <div class="input-group">
                      <div class="input-group-addon"><b>PROFESIONAL RESPONSABLE:</b></div>
                      <input type="text" class="form-control" id="profesional" name="profesional" value="{{$solicitudpre->PROFESIONAL}}" required disabled>
                    </div>
                  </div>
              </div>
              
              </div>                              
            </div>  
          </div>
</body>
</html>