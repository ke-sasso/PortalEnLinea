<?php

namespace App\Models\Cosmeticos\Sol;

use Illuminate\Database\Eloquent\Model;

class Fragancia extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.SOL.fragancias';
    protected $primaryKey='idFragancia';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    protected $fillable = ['idFragancia','fragancia','idUsuarioCrea'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }
}
