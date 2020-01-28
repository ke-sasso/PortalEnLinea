<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Portal en linea">
    <meta name="keywords" content="Portal en linea">
    <meta name="author" content="Unidad de Informática">
    <link href="{{{ asset('img/favicon.ico') }}}" rel="shortcut icon">
    <title>PORTAL EN LINEA</title>

    <!-- Bootstrap -->
{!! Html::style('css/bootstrap.min.css') !!}

{!! Html::style('plugins/alertifyjs/css/alertify.min.css') !!}
{!! Html::style('plugins/alertifyjs/css/themes/default.min.css') !!}
{!! Html::style('plugins/font-awesome/css/font-awesome.min.css') !!}
{!! Html::style('css/style.css') !!}
{!! Html::style('css/style-responsive.css') !!}
{!! Html::style('css/custom.min.css') !!}

<!-- Custom Theme Style -->

    <style media="screen" type="text/css">
        body{
            background: url({{asset('img/bg.png')}}) repeat !important;
        }
        .login_content{
            border-radius: 10px;
            background: #f3f3f3 !important;

            padding: 0 5px;
            margin: 0 auto;
        }
        .text-uppercase
        { text-transform: uppercase; }

        .button{
            max-width: 140px;
            position: absolute;
            top: 50%;
            left: 50%;
            margin: auto;
            width: 50%;

            padding: 10px;


        }
    </style>
</head>
<body class="login">


    <div class="login_wrapper">


                <div class="panel panel-success">
                    <div class="panel-heading" style="text-align: center">
                        PORTAL EN LINEA - CAMBIO DE CONTRASEÑA
                    </div>
                    <div class="panel-body">
                        <div class="c3-tooltip-container" style="text-align: center">
                            @if(isset($message))
                                {!! $message !!}
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <form id="frmPasswd">
                                <div class="input-group form-group">
                                    <div class="input-group-addon">Contraseña Nueva&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                    <input type="password" class="form-control" id="pwdnew1"  name="pwdnew1">
                                    <div class="input-group-addon" ><a href="#" id="checkpwdnew1" alt=""Ver contraseña><i class="fa fa-eye-slash"></i></a></div>
                                </div>
                                <div class="input-group form-group">
                                    <div class="input-group-addon">Confirmar Contraseña</div>
                                    <input type="password" class="form-control" id="pwdnew2"  name="pwdnew2">
                                    <div class="input-group-addon"><a href="#" id="checkpwdnew2" ><i class="fa fa-eye-slash"></i></a></div>
                                </div>
                                <div id="tooltip" class="panel-footer text-center">

                                </div>
                                <div class="text-center">
                                    <p id="loading"><i class="fa fa-refresh fa-3x fa-spin"></i></p>
                                    <button type="button" id="btnsend" class="btn btn-md btn-success">Cambiar Contraseña</button>

                                </div>
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                            </form>
                        </div>
                        <div class="col-lg-2">

                        </div>
                    </div>
                </div>


        {!! Html::script('js/jquery.min.js') !!}
        {!! Html::script('js/bootstrap.min.js') !!}
        {!! Html::script('plugins/alertifyjs/alertify.min.js') !!}
    </div>

    <script type="text/javascript">
        $('#loading').hide();

        $(document).ready(function() {

            function validatePwdStrength(string) {
                return /[A-Z]+/.test(string) && /[a-z]+/.test(string) &&
                    /[\d\W]/.test(string) && /\S{7,}/.test(string)
            }

            $('#checkpwdnew1').on('mousemove',function(){
                    setTimeout(function(){
                        $('#pwdnew1').attr('type','text');
                    },100);

            }).on('mouseup mouseleave',function(){
                    setTimeout(function(){
                        $('#pwdnew1').attr('type','password');
                    },100);
            });

            $('#checkpwdnew2').on('mousemove',function(){
                setTimeout(function(){
                    $('#pwdnew2').attr('type','text');
                },100);

            }).on('mouseup mouseleave',function(){
                setTimeout(function(){
                    $('#pwdnew2').attr('type','password');
                },100);
            });

            $('#pwdnew1').keyup(function(event) {
                $(this).parent().removeClass('has-error');
                var test = validatePwdStrength($(this).val());

                var nova = $('#pwdnew1').val();

                if(!test)
                {
                    $('#tooltip').html('<p class="text-left">La nueva contraseña debe cumplir las siguientes condiciones: </p><ul><li>Debe contener por lo menos 8 caracteres</li><li>Una Letra Mayúscula</li><li>Una Letra Minúscula</li><li>Un valor numérico (0-9)</li><li>Debe ser diferente a la contraseña anterior</li></ul></p>');
                    $('#pwdnew1').parent().addClass('has-error');
                    $('#pwdnew2').attr('readonly', 'readonly');
                    return;
                }
                else
                {
                    $('checkpwdnew1').show();
                    $('#pwdnew1').parent().addClass('has-success');
                    $('#pwdnew2').removeAttr('readonly');
                    $('#tooltip').html('');
                }
            });

            $('#pwdnew2').keyup(function(event) {
                $(this).parent().removeClass('has-error');
                var nova = $('#pwdnew1').val();
                var conf = $(this).val();

                if(nova != conf)
                {
                    $('#tooltip').html('<p class="text-left">La nueva contraseña debe complir las siguientes condiciones: </p><ul><li>La confirmación de la contraseña no coincide</li></ul>');
                    $('#pwdnew2').parent().addClass('has-error');
                    return;
                }
                else
                {
                    $('checkpwdnew2').show();
                    $('#pwdnew2').parent().addClass('has-success');
                    $('#tooltip').html('');

                }
            });

            $('#btnsend').on('click',function(event) {
                event.preventDefault();
                $('#btnsend').hide();
                $('#loading').show();
                $.post('{{route('cambiocontraseapost')}}', $('#frmPasswd').serialize(), function(data, textStatus, xhr) {
                    if(data.status == 200)
                    {

                        alertify.alert(data.message,function(){
                            window.location.href = "{{route('doInicio')}}";
                        }).set('title','Confirmación de Contraseña');

                    }
                    else
                    {
                        alertify.alert(data.message).set('title','Atención!!!');
                        $('#loading').hide();
                        $('#btnsend').show();
                    }
                });
            });
        });
    </script>
</body>
</html>
