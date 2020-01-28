<?php

namespace App\Models\Registro\Sol\Paso2;

use Illuminate\Database\Eloquent\Model;

class PrincipioActivo extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PASO2.principiosActivos';
    protected $primaryKey='idItem';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }
    
    protected $fillable = ['idMateriaPrima','nombreMateriaPrima','concentracion','unidadMedida','idUsuarioCreacion'];
    public function solicitud(){
        return $this->belongsTo('App\Models\Registro\Sol\Solicitud','idSolicitud','idSolicitud');
    }
}
