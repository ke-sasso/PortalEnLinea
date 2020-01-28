<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;
use DB;
class ProdNomExportacion extends Model
{
    //

    protected $table = 'cssp.siic_productos_nombres_exportacion';
    protected $primaryKey = 'ID_PRODUCTO';
    const CREATED_AT = 'FECHA_CREACION';
	const UPDATED_AT = 'FECHA_MODIFICACION'; 

	public static function storeNomExport($idSolicitudNew,$nregistro,$pais,$nomexp,$usuariocrea){
			//dd($pais);
		for($i=0;$i<count($pais);$i++){

			if(ProdNomExportacion::where('ID_PAIS',$pais[$i])->where('ID_PRODUCTO',$nregistro)->first()!=null){

					$exp = ProdNomExportacion::where('ID_PAIS',$pais[$i])->where('ID_PRODUCTO',$nregistro)->delete();
					DB::table('cssp.SIIC_SOLICITUDES_VUE_NOMBRES_EXPORTACION')->where('ID_SOLICITUD',$idSolicitudNew)
					->where('ID_PAIS',$pais[$i])->delete();
					//dd($exp);
					$exportnom = new ProdNomExportacion();
					$exportnom->ID_PRODUCTO=$nregistro;
					$exportnom->ID_PAIS=$pais[$i];
					$exportnom->NOMBRE_EXPORTACION=$nomexp;
					$exportnom->ID_USUARIO_CREACION=$usuariocrea;
					$exportnom->FECHA_CREACION=date('Y-m-d H:i:s');
					$exportnom->save();
				
			}
			else{
					$exportnom = new ProdNomExportacion();
					$exportnom->ID_PRODUCTO=$nregistro;
					$exportnom->ID_PAIS=$pais[$i];
					$exportnom->NOMBRE_EXPORTACION=$nomexp;
					$exportnom->ID_USUARIO_CREACION=$usuariocrea;
					$exportnom->FECHA_CREACION=date('Y-m-d H:i:s');
					$exportnom->save();
				}
			
			
		}
	}
}
