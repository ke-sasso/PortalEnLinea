<?php

namespace App\Models\Registro\Sol;

use Illuminate\Database\Eloquent\Model;
use DB;
class Solicitud extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PRE.solicitud';
    protected $primaryKey='idSolicitud';
    public $incrementing = false;
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function solicitudesDetalle(){
        return $this->hasOne('App\Models\Registro\Sol\Paso2ProductoGenerales','idSolicitud','idSolicitud');
    }

    public function titular(){
        return $this->hasOne('App\Models\Registro\Sol\Paso3Titular','idSolicitud','idSolicitud');
    }
     public function representante(){
        return $this->hasOne('App\Models\Registro\Sol\Paso3Representante','idSolicitud','idSolicitud');
    }
     public function apoderado(){
        return $this->hasOne('App\Models\Registro\Sol\Paso3Apoderado','idSolicitud','idSolicitud');
    }
     public function profesional(){
        return $this->hasOne('App\Models\Registro\Sol\Paso3Profesional','idSolicitud','idSolicitud');
    }
     public function tercero(){
        return $this->hasOne('App\Models\Registro\Sol\Paso3Interesado','idSolicitud','idSolicitud');
    }
     public function fabricantePrincipal(){
        return $this->hasOne('App\Models\Registro\Sol\Paso4FabPrincipal','idSolicitud','idSolicitud');
    }
     public function fabricantesAlternos(){
        return $this->hasMany('App\Models\Registro\Sol\Paso4FabAlternos','idSolicitud','idSolicitud');
    }
     public function laboratorioAcondicionador(){
        return $this->hasMany('App\Models\Registro\Sol\Paso4LabAcondicionador','idSolicitud','idSolicitud');
    }
      public function laboratorioRelacional(){
        return $this->hasMany('App\Models\Registro\Sol\Paso4LabRelacional','idSolicitud','idSolicitud');
    }
     public function manufactura(){
        return $this->hasOne('App\Models\Registro\Sol\Paso5CertManufactura','idSolicitud','idSolicitud');
    }

    public function bpmPrincipal(){
        return $this->hasOne('App\Models\Registro\Sol\Paso6\BpmPrincipal','idSolicitud','idSolicitud');
    }

    public function bpmAlternos(){
        return $this->hasMany('App\Models\Registro\Sol\Paso6\BpmAlterno','idSolicitud','idSolicitud');
    }

    public function bpmAcondicionadores(){
        return $this->hasMany('App\Models\Registro\Sol\Paso6\BpmAcondicionador','idSolicitud','idSolicitud');
    }
     public function bpmRelacionados(){
        return $this->hasMany('App\Models\Registro\Sol\Paso6\BpmRelacionados','idSolicitud','idSolicitud');
    }
     public function bpmFabPrinActivo(){
        return $this->hasMany('App\Models\Registro\Sol\Paso6\BmpFabPrinActivo','idSolicitud','idSolicitud');
    }

    public function distribuidores(){
        return $this->hasMany('App\Models\Registro\Sol\Paso7\Distribuidor','idSolicitud','idSolicitud');
    }

    public function materialEmpaque(){
        return $this->hasOne('App\Models\Registro\Sol\Paso8\MaterialEmpaque','idSolicitud','idSolicitud');
    }

    public function farmacologia(){
        return $this->hasOne('App\Models\Registro\Sol\Paso9\Farmacologia','idSolicitud','idSolicitud');
    }

    public function principiosActivos(){
        return $this->hasMany('App\Models\Registro\Sol\Paso2\PrincipioActivo','idSolicitud','idSolicitud');
    }
    public function empaquesPresentacion(){
        return $this->hasMany('App\Models\Registro\Sol\Paso2\EmpaquePresentacion','idSolicitud','idSolicitud');
    }

    public function documentos(){
        return $this->hasMany('App\Models\Registro\Sol\PreDocumentos','idSolicitud','idSolicitud');
    }

     public function estado(){
        return $this->hasOne('App\Models\Registro\Sol\EstadosSolicitud','idEstado','estadoDictamen');
    }

     public function documentosGenerales(){
        return $this->hasMany('App\Models\Registro\Sol\DocumentoGenerales','idSolicitud','idSolicitud');
    }

    public function vidaUtil(){
        return $this->hasMany('App\Models\Registro\Sol\Paso2\VidaUtilEmpaque','idSolicitud','idSolicitud');
    }
    public function fabprincipioactivo(){
        return $this->hasMany('App\Models\Registro\Sol\Paso4FabPrincipio','idSolicitud','idSolicitud');
    }
    public function poderfabprincipal(){
        return $this->hasOne('App\Models\Registro\Sol\Paso4PrincipalPoderMaquila','idSolicitud','idSolicitud');
    }
    public function poderfabAlterno(){
        return $this->hasMany('App\Models\Registro\Sol\Paso4AlternoPoderMaquila','idSolicitud','idSolicitud');
    }
    public function poderfabAcondicionador(){
        return $this->hasMany('App\Models\Registro\Sol\Paso4AcondicionadorPoderMaquila','idSolicitud','idSolicitud');
    }

    public function revisiones(){
        return $this->hasMany('App\Models\Registro\Sol\PreRevisiones','idSolicitud','idSolicitud');
    }

     public static function getSolicitudes($nit){
      //  Solicitud::where('nitSolicitante',$nit)->where('estadoDictamen','<>','10')->join('sol.estadosSolicitudes','idEstado','estadoDictamen')->join('PASO2.generalesProducto as B','B.idSolicitud','idSolicitud')

     return DB::connection('sqlsrv')->table('dnm_urv_si.PRE.solicitud AS T1')
        ->join('dnm_urv_si.sol.estadosSolicitudes AS T2','T1.estadoDictamen ','=','T2.idEstado')
        ->join('dnm_urv_si.PASO2.generalesProducto AS T3','T1.idSolicitud ','=','T3.idSolicitud')
        ->where('T1.nitSolicitante','=',$nit)
        ->where('T1.estadoDictamen','<>',9)
        ->select('T1.*','T2.estadoPortal','T3.nombreComercial');
    }

    public static function searchAuotoSolicitud($nit,$q){
        return DB::connection('sqlsrv')->table('dnm_urv_si.PRE.solicitud AS T1')
        ->join('dnm_urv_si.PASO2.generalesProducto AS T2','T1.idSolicitud ','=','T2.idSolicitud')
        ->where('T1.nitSolicitante','=',$nit)
        ->where('T1.estadoDictamen','<>',9)
        ->where('T2.nombreComercial','like','%'.$q.'%')
        ->select('T1.numeroSolicitud','T2.nombreComercial')->take(6)->get();
    }


}
