<?php

namespace App\Models\Cosmeticos\Post;

use Illuminate\Database\Eloquent\Model;

class Requisitos extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.POST.requisitos';
    protected $primaryKey='idRequisito';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    //protected $fillable = ['nombreTramite'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

     public function tramites()
    {
        return $this->belongsToMany('App\Models\Cosmeticos\Post\Tramite');
    }

}
