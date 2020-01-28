<?php

namespace App\Models\Cosmeticos\Sol;

use Illuminate\Database\Eloquent\Model;

class Distribuidor extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.SOL.distribuidores';
    protected $primaryKey='idPoderDistribuidor';
    public $autoincrement = false;
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    protected $fillable = ['idDistribuidor','idPoderDistribuidor','idSolicitud','idUsuarioCreacion'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }
}
