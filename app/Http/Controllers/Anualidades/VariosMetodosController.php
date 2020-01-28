<?php

namespace App\Http\Controllers\Anualidades;
use App\Models\EstablecimientoPortal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mumpo\FpdfBarcode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use Auth;
use Crypt;
use DB;
use Datatables;
use Session;
use App\fpdf\PDF_Code128;
use App\Models\Cssp\Mandamientos\Mandamiento;
use Config;
use Validator;
use App\Http\Controllers\Anualidades\VariosMetodosController;
class VariosMetodosController extends Controller
{

   public static function countGeneradosEstablecimientos($idEstablecimiento){
      $cout = Mandamiento::countGenenrados($idEstablecimiento,['3765','3767','3766','3764','3811','3769','3713']);
      if($cout>0){
         return 4;//MANDAMIENTO GENERADO
      }else{
         return 5;//SIN Mandamientos
      }
      /*$client = new Client();
      $res = $client->request('POST', Config::get('app.api').'anualidades/establecimientos/generados',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'idEstab'   =>$idEstablecimiento
        ]]);
        $r = json_decode($res->getBody());
        $info=$r->data[0];
        if($info>0){
         return 4;//MANDAMIENTO GENERADO
        }else{
         return 5;//SIN Mandamientos
        }*/

    }


     public static function comprobarHojaImportador($idEstablecimiento){
        $annioActual = (int)date("Y");
        $client = new Client();
        $res = $client->request('POST', Config::get('app.api').'anualidades/hoja/importador',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'idEstab'   =>$idEstablecimiento,
                    'yearActual'=> $annioActual
            ]

        ]);

        $r = json_decode($res->getBody());
        if($r->status==200){
           $a=$r->data[0];
           $data=$a->id_enlace_imp;

        }else{
           $data="";
        }

      return $data;
    }

      public static function comprobarHojaEstablecimiento($idEstablecimiento){
       /* $client = new Client();
        $res = $client->request('POST', Config::get('app.api').'anualidades/hoja/establecimiento',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'idEstablecimiento'   =>$idEstablecimiento
            ]

        ]);

        $r = json_decode($res->getBody());*/

        $c = EstablecimientoPortal::where('idEstablecimiento',$idEstablecimiento)->count();
        if($c>0){
           $data=1;
        }else{
           $data=0;
        }

      return $data;
    }

    public static function countGeneradosCosmeticos($idProp){
         $cout = Mandamiento::countGenenrados($idProp,['3654','3656','3684','3685']);
          if($cout>0){
             return 4;//MANDAMIENTO GENERADO
          }else{
             return 5;//SIN Mandamientos
          }
        /*$client = new Client();
        $res = $client->request('POST',Config::get('app.api').'anualidades/cosmeticos/generados',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'idProp'  =>  $idProp,
                    'idCliente'  =>  $idCliente
        ]]);
        $r = json_decode($res->getBody());
        $info=$r->data[0];
        if($info>0){
         return 4;//Mandamiento GENERADO
        }else{
         return 5;//Sin Mandamientos
        }*/
    }

     /*public static function   countGeneradosInsumos($idProp,$idCliente){

        $client = new Client();
        $res = $client->request('POST',Config::get('app.api').'anualidades/insumos/generados',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'idProp'  =>  $idProp,
                    'idCliente'  =>  $idCliente
            ]

        ]);

        $r = json_decode($res->getBody());
        $info=$r->data[0];
        if($info>0){
         return 4;//Mandamiento GENERADO
        }else{
         return 5;//Sin Mandamientos
        }


    }*/

    public static function countGeneradosInsumos($idProp){
          $cout = Mandamiento::countGenenrados($idProp,['3623']);
          if($cout>0){
             return 4;//MANDAMIENTO GENERADO
          }else{
             return 5;//SIN Mandamientos
          }
        /*$client = new Client();
        $res = $client->request('POST',Config::get('app.api').'anualidades/insumos/generados',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'idProp'  =>  $idProp,
                    'idCliente'  =>  $idCliente
        ]]);
        $r = json_decode($res->getBody());
        $info=$r->data[0];
        if($info>0){
         return 4;//Mandamiento GENERADO
        }else{
         return 5;//Sin Mandamientos
        }*/

    }

     public static function countGeneradosRegistro($idProp){
          $cout = Mandamiento::countGenenrados($idProp,['3580','3802','3578','3800']);
          if($cout>0){
             return 4;//MANDAMIENTO GENERADO
          }else{
             return 5;//SIN Mandamientos
          }
        /*$client = new Client();
        $res = $client->request('POST',Config::get('app.api').'anualidades/registroVisado/generados',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'idProp'  =>  $idProp,
                    'idCliente'  =>  $idCliente
        ]]);
        $r = json_decode($res->getBody());
        $info=$r->data[0];
        if($info>0){
         return 4;//Mandamiento GENERADO
        }else{
         return 5;//Sin Mandamientos
        }*/
    }

      public function destroyHoja(Request $request){

       // dd($request->all());
      $v = Validator::make($request->all(),[
            'txtEnlace'=>'required',
            'tipo'=>'required'
            ]);

        $v->setAttributeNames([
          'txtEnlace'=>'id enlace',
        ]);
       if ($v->fails())
        {
          $msg = "<ul class='text-warning'>";
          foreach ($v->messages()->all() as $err) {
            $msg .= "<li>$err</li>";
          }
          $msg .= "</ul>";
            return $msg;
        }
        try {
              $id=Crypt::decrypt($request->txtEnlace);

             $client = new Client();
            $res = $client->request('POST',Config::get('app.api').'anualidades/eliminar/hoja',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'idEnlace'=>$id,
                    'tipo'=>$request->tipo
          ]

        ]);

        $r = json_decode($res->getBody());

        if($r->status==200){
            return response()->json(['state' => 'success']);
        }else{
           return 'PROBLEMAS EN ELIMINAR LA HOJA';
        }

      } catch(Exception $e){
          DB::rollback();
          throw $e;
          return $e->getMessage();
      }
     // return response()->json(['state' => 'success']);

  }
}
