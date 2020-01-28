@extends('master')

@section('css')
    {!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal.css') !!}
    {!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') !!}
    {!! Html::style('plugins/smartwizard/css/smart_wizard.min.css') !!}
    {!! Html::style('plugins/smartwizard/css/smart_wizard_theme_circles.min.css') !!}
    {{-- SElECTIZE JS --}}
    {!! Html::style('plugins/selectize-js/dist/css/selectize.bootstrap3.css') !!}
    {!! Html::style('plugins/bootstrap-fileinput/css/fileinput.css') !!}
    {!! Html::style('plugins/bootstrap-fileinput/css/fileinput-rtl.css') !!}
    {!! Html::style('plugins/bootstrap-fileinput/themes/explorer-fa/theme.css') !!}

    {!! Html::style('plugins/select2/css/select2.min.css') !!}



    <style type="text/css">

        body{

            overflow-x: hidden;
            overflow-y: scroll !important;
        }
        .dlgwait {
            display:    inline;
            position:   fixed;
            z-index:    1000;
            top:        0;
            left:       0;
            height:     100%;
            width:      100%;
            background: rgba( 255, 255, 255, .3 )
            url("{{ asset('/img/ajax-loader.gif') }}")
            50% 50%
            no-repeat;
        }
        /* When the body has the loading class, we turn
           the scrollbar off with overflow:hidden */
        body.loading {
            overflow: hidden;
        }

        /* Anytime the body has the loading class, our
           modal element will be visible */
        body.loading .dlgwait {
            display: block;
        }

        .text-uppercase
        { text-transform: uppercase; }


        .form-group .select2-container {
            position: relative;
            z-index: 2;
            float: left;
            width: 100%;
            margin-bottom: 0;
            display: table;
            table-layout: fixed;
        }




        #ejemploPresentacion{
            width:0px;
            height: 0px;
            position: center;
            top: 0%;
            left: 0%;
            margin-top: 100px;
            margin-left: 0px;
            padding: 0px;
        }

        #dlgAddPinicipio{
            width:0px;
            height: 0px;
            position: center;
            top: 0%;
            left: 0%;
            margin-top: -0px;
            margin-left: 300px;
            padding: 0px;

        }

        #dlgSustancias{
            width:0px;
            height: 0px;
            position: center;
            top: 0%;
            left: 0%;
            margin-top: 200px;
            margin-left: 0px;
            padding: 0px;

        }

        #dlgPresentacion{
            width:0px;
            height: 0px;
            position: center;
            top: 0%;
            left: 0%;
            margin-top: 150px;
            margin-left: 300px;
            padding: 0px;

        }

        .modal-css{
            width:0px;
            height: 0px;
            position: center;
            top: 0%;
            left: 0%;
            margin-top: -0px;
            margin-left: 300px;
            padding: 0px;

        }




    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('contenido')
    {{-- */
        $permisos = App\UserOptions::getAutUserOptions();
    /*--}}
    {{-- MENSAJE ERROR VALIDACIONES --}}
    @if($errors->any())
        <div class="alert alert-warning square fade in alert-dismissable">
            <button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
            <strong>Oops!</strong>
            Debes corregir los siguientes errores para poder continuar
            <ul class="inline-popups">
                @foreach ($errors->all() as $error)
                    <li  class="alert-link">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- MENSAJE DE EXITO --}}
    @if(Session::has('msnExito'))
        <div class="modal fade" id="imprimirModal" tabindex="-1" role="dialog" aria-labelledby="myModalImprimir" aria-hidden="false" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="js-title-step">{!! Session::get('msnExito') !!}</h4>
                    </div>
                    <div class="modal-body">
                        <div align="center">
                        </div>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="cerrar1" class="btn btn-primary btn-perspective">Cerrar</button>

                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- MENSAJE DE ERROR --}}
    @if(Session::has('msnError'))
        <div id="error" class="alert alert-danger square fade in alert-dismissable">
            <button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
            <strong>Algo ha salido mal! </strong>
            {!! Session::get('msnError') !!}
        </div>
    @endif
    @if($solicitud->estado==4 && !empty($observacionesdic))
    <div class="alert alert-warning">
        <strong>Observaciones que posee la solicitud:
            <h5>{{$observacionesdic->observacion}}</h5>
        </strong>
    </div>
    @endif


        <div class="panel panel-success">

            <div class="panel-heading" role="tab" id="headingSix">
                <h3 id="leyendTramite" class="panel-title">
                    SOLICITUD NUEVO REGISTRO:
                </h3>
            </div>

            <div class="panel-body">
                <div id="smartwizard">
                    <ul>
                        <li><a href="#step-1" onclick="">Paso 1<br /><small>Validaci&oacute;n Mandamiento</small></a></li>
                        <li><a href="#step-2">Paso 2<br /><small>Generales del Producto</small></a></li>
                        <li><a href="#step-3">Paso 3<br /><small>Informaci&oacute;n Solicitantes</small></a></li>
                        <li><a href="#step-4">Paso 4<br /><small>Fabricantes e Importadores</small></a></li>
                        <li><a href="#step-5">Paso 5<br /><small>Distribuidores Nacionales</small></a></li>
                        <li><a href="#step-6">Paso 6<br /><small>Documentaci&oacute;n</small></a></li>
                        <li><a href="#step-7">Paso 7<br /><small>Finalizaci&oacute;n de la solicitud</small></a></li>
                    </ul>
                    <div>
                        <div id="step-1" class="">
                            @include('cosmeticos.nuevoregistro.pasos.paso1.mandamiento_edit')
                            <input type="hidden" name="img_val[1]" id="img_val1" value="" />
                        </div>
                        <div id="step-2" class="">
                            @include('cosmeticos.nuevoregistro.pasos.paso2.edit.gnralProducto')
                            <input type="hidden" name="img_val[2]" id="img_val2" value="" />
                        </div>
                        <div id="step-3" class="">
                            @include('cosmeticos.nuevoregistro.pasos.paso3.main3')
                            <input type="hidden" name="img_val[3]" id="img_val3" value="" />
                        </div>
                        <div id="step-4" class="">
                            @include('cosmeticos.nuevoregistro.pasos.paso4.main4')
                            <input type="hidden" name="img_val[4]" id="img_val4" value="" />
                        </div>
                        <div id="step-5" class="">
                            @include('cosmeticos.nuevoregistro.pasos.paso5.distribuidores')
                            <input type="hidden" name="img_val[5]" id="img_val5" value="" />
                        </div>
                        <div id="step-6" class="">
                            @include('cosmeticos.nuevoregistro.pasos.paso6.documentos')
                            <input type="hidden" name="img_val[6]" id="img_val6" value="" />
                        </div>
                        <div id="step-7" class="">
                            @if(isset($solicitud))
                                @if($solicitud->estado==4)
                                    @include('cosmeticos.nuevoregistro.pasos.paso7.subsanacionfinalizacion')
                                    <input type="hidden" name="img_val[7]" id="img_val7" value="" />
                                @else
                                    @include('cosmeticos.nuevoregistro.pasos.paso7.finalizacion')
                                    <input type="hidden" name="img_val[7]" id="img_val7" value="" />
                                @endif
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </div>

    <form action="{{route('cospresolicitud.store.step1y2')}}" id="CosPreStep1y2" method="post">
    </form>
    <form action="{{route('cospresolicitud.store.step3')}}" id="CosPreStep3" method="post">
    </form>
    <form action="{{route('cospresolicitud.store.step4')}}" id="CosPreStep4" method="post">
    </form>
    <form action="{{route('cospresolicitud.store.step5')}}" id="CosPreStep5" method="post">
    </form>
    <form action="{{route('cospresolicitud.store.step6')}}" id="CosPreStep6" method="post" enctype="multipart/form-data">
    </form>
    <form action="{{route('cospresolicitud.storemain')}}" id="CosPreStep7" method="post">
    </form>

    @include('cosmeticos.nuevoregistro.pasos.paso2.modalPresentaciones')


@endsection
@section('js')
    {{-- SElECTIZE JS --}}
    {!! Html::script('plugins/selectize-js/dist/js/standalone/selectize.min.js') !!}
    {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!}
    {!! Html::script('js/html2canvas.js') !!}
    {!! Html::script('plugins/smartwizard/js/jquery.smartWizard.min.js') !!}
    {!! Html::script('plugins/bootstrap-fileinput/js/fileinput.js') !!}
    {!! Html::script('plugins/bootstrap-fileinput/js/locales/es.js') !!}
    {!! Html::script('plugins/bootstrap-fileinput/themes/explorer-fa/theme.js') !!}
    {!! Html::script('plugins/select2/js/select2.min.js') !!}
    {!! Html::script('plugins/ckeditorfull/ckeditor.js') !!}
    {!! Html::script('plugins/ckeditorfull/config.js') !!}
    {!! Html::script('plugins/1000hz-bootstrap-validator/validator.min.js') !!}
    {!! Html::script('plugins/1000hz-bootstrap-validator/validator.min.js') !!}
    {!! Html::script('plugins/1000hz-bootstrap-validator/validator.min.js') !!}
    {!! Html::script('js/html2canvas.js') !!}
    {!! Html::script('js/cosmeticos/solicitudpreregistrotools.js') !!}
    {!! Html::script('js/cosmeticos/guardarstepbystep.js') !!}
    {!! Html::script('js/cosmeticos/editsolpretools.js') !!}
    @include('cosmeticos.nuevoregistro.pasos.jsedit');
    <script type="text/javascript">
        var step;
        var config = {
            routes: [
                {
                    findtitular: "{{route('search.titular')}}",
                    valmandamiento: "{{route('cospreregistro.validarmandamiento')}}",
                    marcas: "{{route('cospreregistro.get.marcas')}}",
                    areaaplicacion: "{{route('cospreregistro.get.areasaplicacion')}}",
                    clasificaciones: "{{route('cospreregistro.get.clasificacionbyarea')}}",
                    formas: "{{route('cospreregistro.get.formasbyclasificacion')}}",
                    sustancias: "{{route('cospreregistro.get.formulasinci')}}",
                    gettitular: "{{route('get.titular')}}",
                    getprofesional: "{{route('get.profesional.bypoder')}}",
                    findfabricante: "{{route('search.fab.imp')}}",
                    finddistribuidor: "{{route('search.distribuidor.cos')}}",
                    getrequisitos: "{{route('cospreregistro.get.requisitos')}}",
                    getreconocimiento: "{{route('cospreregistro.get.reconocimientoview')}}",
                    clasificacioneshig: "{{route('cospreregistro.get.clasificacionhig')}}",
                    getgnralprod: "{{route('cospreregistro.get.gnralprodview')}}",
                    envases: "{{route('cospreregistro.get.envases')}}",
                    materiales: "{{route('cospreregistro.get.material.present')}}",
                    unidmedidas: "{{route('cospreregistro.get.unidmedida')}}",
                    formulahig: "{{route('cospreregistro.get.formulashig')}}",
                    deletefab: "{{route('cospresolicitud.delete.fabs')}}",
                    deletedist: "{{route('cospresolicitud.delete.dist')}}",
                    pdfform: "{{route('formulario.cosehig')}}"
                }
            ],
            flags:[
                {
                    mandvalidado: false,
                    profesionalvalidado: false,
                    origen:'',
                    manyest: false,
                    loadimp: false,
                    loadidst: false,
                    disttitular: false

                }
            ]
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function(){



            $( "#inlineRadio2" ).trigger( "change" );
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};

            if (performance.navigation.type == 1) {
                $('body').modalmanager('loading');

                window.location = '{{route('cospresolicitud.edit',['idSolicitud'=>Crypt::encrypt($solicitud->idSolicitud)])}}';
            }

            $('#smartwizard').smartWizard({
                selected: 0,
                theme: 'circles',
                transitionEffect:'fade',
                showStepURLhash: true,
                keyNavigation: false,
                //toolbarSettings: {toolbarPosition: 'both'}
            });


            $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
                console.log(stepNumber);
                step = stepNumber + 1;
                if(step==2){
                    dataStep2();
                    dataFillStep2();
                }
                if(step==3){

                    dataFillStep3();
                }
                if(step==4){
                    dataFillStep4();
                }
                if(step==5){
                    dataFillStep5();
                }

                if(step==6){
                    dataFillStep6();
                }
            });


            $("#smartwizard").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {

                var elmForm = $("#form-step-" + stepNumber);
                // stepDirection === 'forward' :- this condition allows to do the form validation
                // only on forward navigation, that makes easy navigation on backwards still do the validation when going next
                if(stepDirection === 'forward' && elmForm){
                    elmForm.validator('validate');
                    var elmErr = elmForm.children('.has-error');
                    if(elmErr && elmErr.length > 0){
                        // Form validation failed
                        return false;
                    }

                    if (stepNumber+1==2){
                        tipotramit=$('input:radio[name=tipoTramite]:checked').val();
                        if( ($('#marca').val() === null) )
                        {
                            alertify.alert('Mensaje de Sistema','Debe Seleccionar la Marca del Producto');
                            return false;
                        }

                        if( ($('#areaApli').val() === null) )
                        {
                            alertify.alert('Mensaje de Sistema','Debe Seleccionar el Área de Aplicación del Producto');
                            return false;
                        }
                        if( ($('#clasificacion').val() === null) )
                        {
                            alertify.alert('Mensaje de Sistema','Debe Seleccionar la Clasificación del Producto');
                            return false;
                        }
                        if( ($('#formacos').val() === null) )
                        {
                            alertify.alert('Mensaje de Sistema','Debe Seleccionar la Forma Cosmética del Producto');
                            return false;
                        }


                        if(tipotramit==2 || tipotramit==3){
                            //VALIDAMOS QUE LOS TRAMITES  COS SEA OBLIGATORIO LAS PRESENTACIONES
                            if($('#presentacion >tbody >tr').length<=0){
                            alertify.alert("Mensaje de sistema","Debe agregar al menos una presentación!");
                            return false;
                            }
                        }
                        else
                        {   $('#btnStep2').data('autosave','true');
                            $('#btnStep2').trigger('click');
                            return true;
                        }
                    }
                    else if(stepNumber+1==3){
                        if(config.flags[0].profesionalvalidado==true) {
                            $('#btnStep3').data('autosave','true');
                            $('#btnStep3').trigger('click');
                            return true;
                        }
                        else {
                            if($('#poderProf').val().length!=0){
                                alertify.alert("Mensaje de sistema","Debe validar el número de poder del profesional dando click en el botón de buscar!");
                                return false;
                            }
                            else{
                                return false;
                            }
                        }
                    }
                    else if(stepNumber+1==4){
                        var tipoTra=$('input[type=radio][name=tipoTramite]:checked').val();
                        if($('#fabricantes >tbody >tr').length<=0){
                            alertify.alert("Mensaje de sistema","Debe agregar al menos un fabricante para el producto!");
                            return false;
                        }
                        else if (tipoTra==3 || tipoTra==5){
                            if ($('#importadores >tbody >tr').length<=0) {
                                alertify.alert("Mensaje de sistema", "Debe agregar al menos un importador para el producto!");
                                return false;
                            }
                        }
                        else{
                            $('#btnStep4').data('autosave','true');
                            $('#btnStep4').trigger('click');
                            return true;
                        }
                    }
                    else if(stepNumber+1==5){
                        if(!config.flags[0].disttitular) {
                            if ($('#distribuidores >tbody >tr').length <= 0) {
                                alertify.alert("Mensaje de sistema", "Debe agregar al menos un distribuidor para el producto!");
                                return false;
                            }
                        }
                        else{
                            $('#btnStep5').data('autosave','true');
                            $('#btnStep5').trigger('click');
                            return true;
                        }
                    }
                    else if(stepNumber+1==6){
                        var files = true;
                        $.each($('input[type=file]'),function(){
                            if($(this).data('obligatorio')==1)
                                if($(this).get(0).files.length==0){
                                    files = false;
                                }
                        });

                        if(files){
                            $('#btnStep6').data('autosave','true');
                            $('#btnStep6').trigger('click');
                            return files
                        }
                        else{
                            alertify.alert("Mensaje de sistema","Debe adjuntar un documento por cada requisito, todos son obligatorios!");
                            return files;
                        }

                    }
                }
                else if (stepDirection==='backward'){
                    return true;
                }

            });
        });


        $('#guardarSoli').click(function(event) {
            alertify.confirm("Mensaje de sistema", "Esta seguro que desea procesar este trámite?", function (asc) {
                if (asc) {
                    console.log(step);
                    var formObj = $('#CosPreStep7');
                    var formURL = formObj.attr("action");
                    var formData = new FormData($("#CosPreStep7")[0]);
                    guardarPaso(formURL,formData);
                } else {
                }
            }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});
        });


        @yield('scripts-cos')


    </script>


@endsection
