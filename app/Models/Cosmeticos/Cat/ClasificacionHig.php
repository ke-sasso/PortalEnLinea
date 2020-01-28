<?php
/**
 * Created by PhpStorm.
 * User: steven.mena
 * Date: 27/6/2018
 * Time: 2:46 PM
 */

namespace App\Models\Cosmeticos\Cat;

use Illuminate\Database\Eloquent\Model;

class ClasificacionHig extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.HIG.clasificacion';
    protected $primaryKey='idClasificacion';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public static function allActive(){
        return ClasificacionHig::where('estado','A')->select('idClasificacion','nombreClasificacion','poseeFragancia','poseeTono')
            ->orderBy('nombreClasificacion');
    }
}
