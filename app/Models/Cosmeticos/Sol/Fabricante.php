<?php

namespace App\Models\Cosmeticos\Sol;

use Illuminate\Database\Eloquent\Model;

class Fabricante extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.SOL.fabricantes';
    protected $primaryKey='idSolicitud';
    public $autoincrement = false;
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    protected $fillable = ['idFabricante','tipoFabricante','idUsuarioCreacion'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function solicitudes(){
        return $this->belongsTo('App\Models\Cosmeticos\Sol\Solicitud','idSolicitud','idSolicitud');
    }

    public static function deleteFab($idSolicitud,$idFab){
        Fabricante::where('idSolicitud',$idSolicitud)->where('idFabricante',$idFab)->delete();
    }
}
