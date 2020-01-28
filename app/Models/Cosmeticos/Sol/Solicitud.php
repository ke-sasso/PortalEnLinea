<?php

namespace App\Models\Cosmeticos\Sol;

use Illuminate\Database\Eloquent\Model;
use DB;

class Solicitud extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.SOL.solicitudes';
    protected $primaryKey='idSolicitud';
    public    $incrementing=false;
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function solicitudesDetalle(){
        return $this->hasOne('App\Models\Cosmeticos\Sol\SolicitudDetalle','idSolicitud','idSolicitud');
    }

    public function fabricantes(){
        return $this->hasMany('App\Models\Cosmeticos\Sol\Fabricante','idSolicitud','idSolicitud');
    }

    public function importadores(){
        return $this->hasMany('App\Models\Cosmeticos\Sol\Importador','idSolicitud','idSolicitud');
    }

    public function distribuidores(){
        return $this->hasMany('App\Models\Cosmeticos\Sol\Distribuidor','idSolicitud','idSolicitud');
    }

    public function tonos(){
        return $this->hasMany('App\Models\Cosmeticos\Sol\Tono','idSolicitud','idSolicitud');
    }

    public function fragancias(){
        return $this->hasMany('App\Models\Cosmeticos\Sol\Fragancia','idSolicitud','idSolicitud');
    }

    public function formulasCosmetico(){
        return $this->hasMany('App\Models\Cosmeticos\Sol\FormulaCosmetico','idSolicitud','idSolicitud');
    }

    public function formulasHigienicos(){
        return $this->hasMany('App\Models\Cosmeticos\Sol\FormulaHigienico','idSolicitud','idSolicitud');
    }

    public function presentaciones(){
        return $this->hasMany('App\Models\Cosmeticos\Sol\Presentacion','idSolicitud','idSolicitud');
    }
    /*
    public function detalleDocumentos(){
        return $this->hasManyThrough('App\Models\Cosmeticos\Sol\DetalleDocumento','App\Models\Cosmeticos\Sol\DocumentoSol','idSolicitud','idDoc');
    }*/

    public function detalleDocumentos(){
        return $this->belongsToMany('App\Models\Cosmeticos\Sol\DetalleDocumento','dnm_cosmeticos_si.SOL.documentosSol','idSolicitud','idDetalleDoc')->withPivot('idItemDoc');
    }


    public static function getLastObservacion($idSolicitud){


        return DB::connection('sqlsrv')->table('dnm_cosmeticos_si.DIC.dictamenResolucion as dicr')
                                ->whereIn('dicr.idDictamen',function($query) use ($idSolicitud){
                                    $query->select('idDictamen')
                                        ->from('dnm_cosmeticos_si.DIC.dictamenesPre')
                                        ->where('idSolicitud',$idSolicitud)
                                        ->orderBy('idDictamen','DESC')->limit(1);
                                })->first();


    }

}
