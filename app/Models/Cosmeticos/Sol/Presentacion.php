<?php

namespace App\Models\Cosmeticos\Sol;

use Illuminate\Database\Eloquent\Model;

class Presentacion extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.SOL.presentaciones';
    protected $primaryKey='idPresentacion';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    protected $fillable = ['idSolicitud','idEnvasePrimario','idMaterialPrimario','contenido','idUnidad','idEnvaseSecundario','idMaterialSecundario','contenidoSecundario','idClasificacion','peso','idMedida','nombrePresentacion','textoPresentacion','idCoempaque'];
    protected $hidden = ['idPresentacion','idUsuarioCrea','fechaCreacion','idUsuarioModificacion', 'fechaModificacion'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public static function createJsonPresentacion($idPresentacion){

        //$presentacion = Presentacion::find($idPresentacion);
        //$array= array('')
    }
}
