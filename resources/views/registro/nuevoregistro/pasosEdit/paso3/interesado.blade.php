<div class="container-fluid the-box">
         <h4>
            <span class="label label-info">
                <strong>Señalo para oír notificaciones:</strong>
            </span>
        </h4>
        <div class="form-group row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="input-group">

                                     <label class="radio-inline">
                                        <input type="radio" name="oirNotificaciones" id="oirNotificaciones" value="1" @if($solicitud->solicitudesDetalle->idOirNotificaciones==1) checked @endif  form="RegistroPreStep3" /> Correo electrónico
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="oirNotificaciones"  id="oirNotificaciones" value="2" @if($solicitud->solicitudesDetalle->idOirNotificaciones==2) checked @endif form="RegistroPreStep3" /> Instalaciones de la DNM
                                    </label>
                                </div>
                </div>
        </div>
</div>

<div class="container-fluid the-box">
        <h4>
            <span class="label label-info">
                <strong>TERCEROS INTERESADOS</strong>
            </span>
        </h4>
        <div class="form-group form-inline">
            <label><b>Nota:</b> Se entenderá como terceros interesados las personas que sin haber iniciado el procedimiento, tengan derechos que puedan resultar afectados por la decisión que en el mismo se adopte.</label>

            @if($solicitud->solicitudesDetalle->tercerInteresado!=null || $solicitud->solicitudesDetalle->tercerInteresado!='')
                    <br>
                   <label class="radio-inline">
                     <input type="radio" name="valTerceraPersona" id="valTerceraPersona" value="1" @if($solicitud->solicitudesDetalle->tercerInteresado==1) checked @endif required  form="RegistroPreStep3"> SI
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="valTerceraPersona" id="valTerceraPersona" value="0" @if($solicitud->solicitudesDetalle->tercerInteresado==0) checked @endif required form="RegistroPreStep3"> NO ES DE MI CONOCIMIENTO
                    </label>
            @else
                    <br>
                     <label class="radio-inline">
                                <input type="radio" name="valTerceraPersona" id="valTerceraPersona" value="1" required form="RegistroPreStep3"> SI
                    </label>
                    <label class="radio-inline">
                                <input type="radio" name="valTerceraPersona" id="valTerceraPersona" value="0"  required form="RegistroPreStep3"> NO ES DE MI CONOCIMIENTO
                    </label>
            @endif
        </div>
        <div id="datosTercerasPer" @if(!$solicitud->solicitudesDetalle->tercerInteresado) style="display: none;" @endif>
                <div class="form-group">
                    <div class="row">
                       <div class="col-sm-12 col-md-12">
                            <div class="input-group ">
                                <div class="input-group-addon" for="nominteresado"><b>Nombre de persona natural o jurídica y nombre de apoderado o representante legal (Cuando aplique):</b></div>
                                <textarea class="form-control" id="nominteresado" name="nominteresado" value=""  rows="2" form="RegistroPreStep3" autocomplete="off">{{$solicitud->tercero!=null?$solicitud->tercero->nombres:''}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>


                 <h5>
                    <span class="label label-info">
                        <strong>MEDIO PARA NOTIFICAR</strong>
                    </span>
                </h5>
                 <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="input-group">
                            <div class="input-group-addon"><b>Correo electrónico:</b></div>
                            <input type="email" class="form-control" name="correointeresado" id="correointeresado" value="{{$solicitud->tercero!=null?$solicitud->tercero->email:''}}"  form="RegistroPreStep3" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="input-group">
                            <div class="input-group-addon"><b>Domicilio:</b></div>
                            <input type="text" class="form-control" name="direccioninteresado" id="direccioninteresado" value="{{$solicitud->tercero!=null?$solicitud->tercero->direccion:''}}"  form="RegistroPreStep3" autocomplete="off">
                        </div>
                    </div>
                </div>
                 <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="input-group">
                            <div class="input-group-addon"><b>Teléfono 1:</b></div>
                            <input type="text" class="form-control phone_masking" name="tel1interesado" id="tel1interesado" value="{{$solicitud->tercero!=null?$solicitud->tercero->telefono1:''}}"  form="RegistroPreStep3" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="input-group">
                            <div class="input-group-addon"><b>Teléfono 2:</b></div>
                            <input type="text" class="form-control phone_masking" name="tel2interesado" id="tel2interesado" value="{{$solicitud->tercero!=null?$solicitud->tercero->telefono2:''}}"  form="RegistroPreStep3" autocomplete="off">
                        </div>
                    </div>
                </div>
        </div>
</div>
