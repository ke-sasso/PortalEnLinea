<div class="container-fluid the-box">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="sustancias" class="table table-hover">
                <caption><b><u>FORMULA</u></b></caption>
                <thead>
                <tr>
                    <th>N° CAS</th>
                    <th>NOMBRE SUSTANCIA</th>
                    <th>PORCENTAJE</th>
                    <th width="10%">OPCIONES</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot id="plusPresent">
                <tr>
                    <th colspan="5" class="text-right">
                        <span class="btn btn-primary" id="btnAddSustancia"><i class="fa fa-plus"></i></span>
                    </th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="modal fade modal-css" id="dlgSustancias"  role="dialog" aria-labelledby="DefaultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 align="center" class="modal-title" id="DefaultModalLabel">Agregar Formula (Nomenclatura INSI)</h4>
            </div>
            <div class="modal-body">
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                    <h5 align="left" ><b>Realice la búsqueda por número de CAS o nombre de la Sustancia</b></h5>
                </div>
                <form class="form-inline" id="formulaForm" role="form" data-toggle="validator">

                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">Sustancia:</div>
                                    <select class="form-control select2-single select2-hidden-accessible" style="width:100%" id="sustancia"  aria-hidden="true" required>
                                    </select>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-3">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">Porcentaje %:</div>
                                    <input type="number" id="porcentaje"  required="required" class="form-control input-sm" value="0" min="0" required>
                                </div>
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="addFormula">Agregar</button>
            </div><!-- /.modal-footer -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-doalog -->
</div><!-- /#DefaultModal  -->