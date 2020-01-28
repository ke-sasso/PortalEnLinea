<?php

namespace App\Models\Cosmeticos\Post;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.POST.documentosSol';
    protected $primaryKey='idDocumento';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    //protected $fillable = ['nombreTramite'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

}
