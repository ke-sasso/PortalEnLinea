<?php

namespace App\Models\Cosmeticos\Sol;

use Illuminate\Database\Eloquent\Model;

class Importador extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.SOL.importadores';
    protected $primaryKey='idSolicitud';
    public $autoincrement = false;
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    protected $fillable = ['idImportador','idUsuarioCreacion'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public static function deleteFab($idSolicitud,$idImp){
        Importador::where('idSolicitud',$idSolicitud)->where('idImportador',$idImp)->delete();
    }
}
