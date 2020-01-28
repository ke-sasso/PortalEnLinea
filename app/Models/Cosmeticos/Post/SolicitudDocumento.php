<?php

namespace App\Models\Cosmeticos\Post;

use Illuminate\Database\Eloquent\Model;

class SolicitudDocumento extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.POST.solicitudDocumentos';
    protected $primaryKey='idSolDoc';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    //protected $fillable = ['nombreTramite'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

}
