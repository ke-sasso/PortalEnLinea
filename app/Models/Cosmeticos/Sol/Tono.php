<?php

namespace App\Models\Cosmeticos\Sol;

use Illuminate\Database\Eloquent\Model;

class Tono extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.SOL.tonos';
    protected $primaryKey='idTono';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    protected $fillable = ['idTono','tono', 'idUsuarioCrea'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }
}
