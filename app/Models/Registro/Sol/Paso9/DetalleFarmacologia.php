<?php

namespace App\Models\Registro\Sol\Paso9;

use Illuminate\Database\Eloquent\Model;

class DetalleFarmacologia extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PASO9.detalleFarmacologica';
    protected $primaryKey='idItem';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }
    protected $fillable = ['farmacocinetica','mecanismoAccion','indicacionesTerapeuticas','contraindicaciones','regimenDosis','efectosAdversos','precauciones','interacciones','categoriaTerapeutica','idUsuarioCreacion'];
    public function farmacologia(){
         return $this->belongsTo('App\Models\Registro\Sol\Paso9\Farmacologia','idFichaTecnica','idFichaTecnica');
    }
     public function codigoAtc(){
        return $this->hasOne('App\Models\Registro\Cat\CodigoAtc','codigoAtc','categoriaTerapeutica');
    }
}
