<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sim\RequisitoDocumento;

class SolPostListaChequeo extends Model
{
    //
    protected $table = 'sim.sim_solicitudes_post_requisitos_lista_chequeo';
    protected $primaryKey = 'id_lista_chequeo';
    const CREATED_AT = 'fecha_creacion';
	const UPDATED_AT = 'fecha_modificacion';


	public static function store($idSolicitud,$tipoDocumentos,$usuarioCreacion){

		  for($i=0;$i<count($tipoDocumentos);$i++){
		  	$requi=RequisitoDocumento::find($tipoDocumentos[$i]);
		  	$listaChequeo = new SolPostListaChequeo();
			$listaChequeo->id_solicitud=$idSolicitud;
			$listaChequeo->requisitoId=$requi->requisitoId;
			$listaChequeo->tramiteDocumentoId=$requi->documentoTramiteId;
			/* SE GUARDA UNO PORQUE ES REQUERIDO Y SE ENTREGO*/
			$listaChequeo->check=1;
			$listaChequeo->usuario_creacion=$usuarioCreacion;
			$listaChequeo->fecha_creacion=date('Y-m-d H:i:s');

			$saved=$listaChequeo->save();
			if(!$saved){
            	return view('errors.500');
        	}

		}
	}
}
