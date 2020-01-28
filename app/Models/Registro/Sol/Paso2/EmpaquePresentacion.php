<?php

namespace App\Models\Registro\Sol\Paso2;

use Illuminate\Database\Eloquent\Model;

class EmpaquePresentacion extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PASO2.empaquePresentacion';
    protected $primaryKey='idItem';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }
    protected $fillable = ['tipoPresentacion','empaquePrimario','cantidadPrimaria','contenidoPrimario','empaqueSecunadrio','cantidadSecundaria','contenidoSecundario','empaqueTerciario','cantidadTerciaria','contenidoTerciario','accesorio','idMateria','idColor','textoPresentacion','presentacionEspecial'];

    public function solicitud(){
        return $this->belongsTo('App\Models\Registro\Sol\Solicitud','idSolicitud','idSolicitud');
    }
}
