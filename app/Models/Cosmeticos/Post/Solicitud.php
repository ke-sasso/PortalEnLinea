<?php

namespace App\Models\Cosmeticos\Post;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.POST.solicitudes';
    protected $primaryKey='idSolicitud';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    //protected $fillable = ['nombreTramite'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

}
