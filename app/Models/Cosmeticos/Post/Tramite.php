<?php

namespace App\Models\Cosmeticos\Post;

use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.POST.tramites';
    protected $primaryKey='idTramite';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    //protected $fillable = ['nombreTramite'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function requisitos()
    {
        return $this->belongsToMany('App\Models\Cosmeticos\Post\Requisitos');
    }
    public function tramiteRequisitos(){
        return $this->belongsToMany('App\Models\Cosmeticos\Post\Requisitos','dnm_cosmeticos_si.POST.tramiteRequisitos','idTramite','idRequisito');
    }
    public static function allActive(){
        return Tramite::where('portalEnLinea','1')->select('idTramite','nombreTramite')->get();
    }

}
