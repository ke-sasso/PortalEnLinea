<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use GuzzleHttp\Client;
use Config;
class Establecimientos extends Model
{
    //

    protected $table = 'dnm_establecimientos_si.est_establecimientos';
    protected $primaryKey = 'idEstablecimiento';
    public $incrementing = false;
	const CREATED_AT = 'fechaCreacion';
	const UPDATED_AT = 'fechaModificacion';

  
	public static function getEstablecimiento($nit){
		return DB::table('dnm_usuarios_portal.vwestablecimientos')
				->where('idPersonaNatural',$nit)
				->distinct()
				->get();
				
	}

	public static function getNameEstablecimientos($idEstablecimientos){
	        $url = Config::get('app.api');
			$client = new Client();
            $res = $client->request('POST', $url.'pel/e/get/byids',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            
                ],
                'form_params' =>[
                    'establecimientos'   =>$idEstablecimientos
                ]   
            ]);

            $r = json_decode($res->getBody());

            if($r->status == 200){

            	return $r->data;
            }
            else{
            	return null;
            }
	}
}
