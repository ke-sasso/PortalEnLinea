<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;
use DB;
class SoliPostListaChequeo extends Model
{
    // TABLA EN LA CUAL SE GUARDA EL TRAMITE Y EL ID_ITEM QUE SE GUARDO Y SE ENTREGO POR EL USUARIO
    protected $table = 'cssp.si_urv_solicitudes_postregistro_requisitos_lista_chequeo';
    protected $primaryKey = 'ID_REQUISITO';
    const CREATED_AT = 'FECHA_CREACION';
	const UPDATED_AT = 'FECHA_MODIFICACION'; 

	public static function store($idSolicitud,$tipoTramite,$tipoDocumentos,$usuarioCreacion){

		  for($i=0;$i<count($tipoDocumentos);$i++){
		  	$listaChequeo = new SoliPostListaChequeo();
			$listaChequeo->ID_SOLICITUD=$idSolicitud;
			$listaChequeo->ID_TRAMITE=$tipoTramite;
			$listaChequeo->ID_ITEM=$tipoDocumentos[$i];
			/* SE GUARDA UNO PORQUE ES REQUERIDO Y SE ENTREGO*/
			$listaChequeo->CHECK=1;
			$listaChequeo->USUARIO_CREACION=$usuarioCreacion;
			$listaChequeo->FECHA_CREACION=date('Y-m-d H:i:s');

			$saved=$listaChequeo->save();
			if(!$saved){
            	return view('errors.500');
        	}

		}
	}

}
