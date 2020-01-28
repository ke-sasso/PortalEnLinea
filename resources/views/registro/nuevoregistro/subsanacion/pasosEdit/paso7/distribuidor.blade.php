<h4>
  <span class="label label-primary">
    <strong>Ingrese la información del distribuidor nacional del nuevo producto farmacéutico! (CUANDO APLIQUE)</strong>
  </span>
</h4>
    @if($solicitud->estadoDictamen==4)
    <div class="form-group">
        <div class="row">
            <div class="col-sm-10 col-md-10 col-lg-10">
                <div class="input-group ">
                    <div class="input-group-addon" for="distribuidor">
               @if(!empty($campos)) @if(in_array('distribuidores', $campos))
               <a class="btn btn-danger btn-perspective btn-xs vals" id="distribuidores-val"><i class="fa  fa-times"></i></a>
               @endif @endif
                    <b>B&uacute;squeda del Distribuidor:</b></div>
                    <select id="searchbox-distribuidor" name="distribuidor" placeholder="Buscar por poder o por nombre del distribuidor" class="form-control" @if(!empty($campos)) @if(!in_array('distribuidores', $campos)) disabled @endif @endif></select>
                    <!-- <span class="input-group-btn">
					    <button class="btn btn-primary" id="agregarDistribuidor" type="button">Agregar <i class="fa fa-plus" aria-hidden="true"></i></button>
				    </span> -->
                </div>
                <div id="token-distribuidores"></div>
            </div>
        </div>
        <br>
    </div>
    @endif
    <input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud6" id="idSolicitud6" form="RegistroPreStep7">
    <div class="the-box full no-border">
        <table class="table table-hover" id="dt-distribuidores">
            <caption><b><u>DISTRIBUIDORES NACIONALES</u></b></caption>
            <thead class="thead-info">
            <tr>
                <th>ID DISTRIBUIDOR</th>
                <th>NOMBRE COMERCIAL</th>
                <th>DOMICILIO</th>
                <th>FECHA VIGENCIA</th>
                <th>ESTADO</th>
                <th>CORREO ELECTRÓNICO</th>
               @if(!empty($campos)) @if(in_array('distribuidores', $campos))  <th>OPCIONES</th>@endif @endif

            </tr>
            </thead>
            <tbody>
            <tr>
              @if (!is_null($soldata->distribuidores))
                @foreach ($soldata->distribuidores as $distribuidor)
                  <tr>
                    <input type="hidden" form="RegistroPreStep7" name="dist[]" value="{{$distribuidor->idEstablecimiento}}">
                    <td>{{$distribuidor->idEstablecimiento}}</td>
                    <td>{{$distribuidor->nombreComercial}}</td>
                    <td>{{$distribuidor->direccion}}</td>
                    <td>{{$distribuidor->vigenteHasta}}</td>
                    <td>{{$distribuidor->nombreEstado}}</td>
                    <td>{{$distribuidor->emailContacto}}</td>
                     @if(!empty($campos)) @if(in_array('distribuidores', $campos))
                    <td>
                      <button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    </td>
                    @endif @endif
                  </tr>
                @endforeach
              @endif
            </tr>
            </tbody>
        </table>
    </div>
<!--
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="input-group ">
                  <div class="input-group-addon" for="nombreDistribuidor"><b>Nombre del distribuidor:</b></div>
                  <input type="text" class="form-control" id="nombreDistDistme" name="nombreDist" value="" autocomplete="off" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="input-group ">
                  <div class="input-group-addon" for="direccDist"><b>Domicilio principal:</b></div>
                  <input type="text" class="form-control" id="direccDist" name="direccDist" value="" autocomplete="off" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-4 col-md-3">
                <div class="input-group ">
                  <div class="input-group-addon" for="telefonoDist"><b>Tel&eacute;fono:</b></div>
                  <input type="text" class="form-control" id="telefonoDist" name="telefonoDist" value="" autocomplete="off" readonly>
                </div>
            </div>
            <div class="col-sm-4 col-md-3">
                <div class="input-group ">
                  <div class="input-group-addon" for="faxDist"><b>Fax:</b></div>
                  <input type="text" class="form-control" id="faxDist" name="faxDist" value="" autocomplete="off" readonly>
                </div>
            </div>
            <div class="col-sm-4 col-md-6">
                <div class="input-group ">
                  <div class="input-group-addon" for="correoDist"><b>Correo:</b></div>
                  <input type="text" class="form-control" id="correoDist" name="correoDist" value="" autocomplete="off" readonly>
                </div>
            </div>
        </div>
    </div>

    <a href="#" id="speak7" class="waves-effect waves-light btn btn-dark" disabled><i class="fa fa-play" aria-hidden="true"></i></a>
-->



@if(!empty($tablas))
@if(in_array('PASO7.distribuidores', $tablas))
<div align="center">
  @if($solicitud->estadoDictamen==4) <button type="button" class="btn btn-primary" id="btnStep7">Guardar Paso 7</button> @endif
  </div>
@endif
@endif
