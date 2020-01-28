<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Config;
class VwVerSolicitudes extends Model
{
    //
    protected $table = 'dnm_usuarios_portal.vw_solicitudes_pub';
    protected $primaryKey = 'idSolicitud';
    public $timestamps = false;

   

    public static function VerSolicitudesByNit($nit){
        $url = Config::get('app.api');
        if($nit){
            /*
            return DB::table('dnm_usuarios_portal.vw_solicitudes_pub')
                    ->where('nitSolicitante',$nit)
                    ->orderBy('idSolicitud','desc')
                    ->get();
            */
            
            $client = new Client();
            $res = $client->request('POST', $url.'pyp/get/solicitudes',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            
                ],
                'form_params' =>[
                    'nit'  =>$nit
                ]   
            ]);

            $r = json_decode($res->getBody());

            if($r->status == 200){
                 //dd($r->data);
                $solicitudes = new Collection($r->data);
                /*
                    
                    
                    for ($i = 0; $i <count($r->data); $i++) {
                        $solicitudes->push([
                            'idSolicitud'           =>$r->data[$i]->idSolicitud,
                            'numeroSolicitud'       =>$r->data[$i]->numeroSolicitud,
                            'idEstablecimiento'     =>$r->data[$i]->idEstablecimiento,
                            'nombreComercial'       =>$r->data[$i]->nombreComercial,
                            'nombreTramite'         =>$r->data[$i]->nombreTramite,
                            'nombreEstado'          =>$r->data[$i]->nombreEstado,
                            'idEstado'              =>$r->data[$i]->idEstado,
                            'dictamenes'            =>$r->data[$i]->dictamenes,
                           
                         
                        ]);
                   }*/
                return $solicitudes;
            }
            elseif($r->status == 404){
                return null;    
            }
        }

    }
}
