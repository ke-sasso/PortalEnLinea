<?php

namespace App\Models\Registro\Sol;

use Illuminate\Database\Eloquent\Model;
use DB;
class DocumentoGenerales extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PRE.documentoGenerales';
    protected $primaryKey='idItem';
    public $incrementing = false;
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function tipoDocumento(){
        return $this->hasOne('App\Models\Registro\Cat\TipoDocumentoSol','idTipoDoc','idTipoDoc');
    }


}
