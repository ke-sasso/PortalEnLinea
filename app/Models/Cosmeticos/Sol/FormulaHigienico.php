<?php

namespace App\Models\Cosmeticos\Sol;

use Illuminate\Database\Eloquent\Model;

class FormulaHigienico extends Model
{
    //

    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.SOL.formulaHigienico';
    protected $primaryKey='idCorrelativo';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    protected $fillable = ['idDenominacion','porcentaje','idUsuarioCreacion'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }
}
