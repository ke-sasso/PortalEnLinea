<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">

    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="the-box border  text-center">
            <h4 class="more-margin-bottom"><b>FINALIZACIÓN DE LA SOLICITUD NUEVO REGISTRO<b></b></h4>

            <p align="justify">Verifique que la información ingresada en cada paso es acorde al nuevo producto farmacéutico,
                posterior al envió de su solicitud no podrá agregar más información, al dar clic en el botón
                enviar solicitud esta será recibida por la unidad de registro y visado,
                puede verificar el proceso de su solicitud en el siguiente enlace: <a href="{{route('ver.solicitudes.rv.pre')}}" target="_blank">Ver Solicitudes Pre-Registro</a>.
            </p>


            <hr>
             <input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud10" id="idSolicitud10" form="RegistroPreStep11">
            <p ><button class="btn btn-danger btn-block" id="btnStep11">ENVIAR SOLICITUD</button></p>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">

    </div>
</div>
