<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Config;
class PubCatTramites extends Model
{
    //
    protected $table = 'dnm_publicidad_si.pub_cat_tramites';
    protected $primaryKey = 'idTipoTramite';
    const CREATED_AT = 'fechaCreacion';
	const UPDATED_AT = 'fechaModificacion';

  

	public static function getTramites(){
        $url = Config::get('app.api');
		$client = new Client();
        $res = $client->request('POST', $url.'pyp/get/tramites',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
        
            ]  
        ]);

        $r = json_decode($res->getBody());

        if($r->status == 200){
        	return $r->data;
        }
        elseif($r->status == 400){
        	return null;	
        }
	}
}
