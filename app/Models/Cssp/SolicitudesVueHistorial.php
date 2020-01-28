<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;
use DB;
class SolicitudesVueHistorial extends Model
{
    // TABLA QUE SIRVE PARA LLEVAR UN SEGUIMIENTO DE LOS CAMBIOS HECHOS EN LA TABLA SOLICITUDES_VUE 
    //POR CADA SOLICITUD 
    protected $table = 'cssp.siic_solicitudes_vue_historial_creacion';
    protected $primaryKey = 'Identificador';
    public $timestamps = false;


    public static function store($IdSolicitud,$expediente,$usuario){

    	$solicitudHist = new SolicitudesVueHistorial();

    	$solicitudHist->Identificador= DB::select("SELECT MAX(Identificador) +1 as maximo from cssp.siic_solicitudes_vue_historial_creacion")[0]->maximo;
    	$solicitudHist->Id_Solicitud=$IdSolicitud;
    	$solicitudHist->No_Expediente=$expediente;
    	$solicitudHist->Id_Usuario=$usuario;
    	$solicitudHist->Tipo_Transaccion="";
    	$solicitudHist->Fecha=date('Y-m-d');
    	$solicitudHist->Hora=date('H:i:s');

    	$saved=$solicitudHist->save();

        if(!$saved){
            return view('errors.500');
        }

    }
}
