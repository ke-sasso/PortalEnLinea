<?php

namespace App\Models\Cosmeticos\Sol;

use Illuminate\Database\Eloquent\Model;

class DetalleDocumento extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.SOL.detalleDocumentos';
    protected $primaryKey='idDoc';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';


    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function documentosSol(){
        return $this->hasMany('App\Models\Cosmeticos\Sol\DocumentoSol','idDetalleDoc','idDoc');
    }
}
