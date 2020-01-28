<?php

namespace App\Http\Controllers\Anualidades;

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
use App\Http\Controllers\Anualidades\VariosMetodosController;
class PagosMoraController extends Controller
{

    private $endDate = "";
    private  $todayDate= "";
    private  $valorPorcentual = 0.01;

    public function __construct(){

      $fechaLimite=date('Y')."-03-31";
      $this->endDate = date("Y-m-d",strtotime($fechaLimite));
      $this->todayDate =date("Y-m-d");
      $this->url = Config::get('app.api');

    }
    public  function datosIdTipoPago($idPago)
    {

        //CONSULTAMOS EL ID_TIPO_PAGO_RECARGO
        $client = new Client();
        $res = $client->request('POST', $this->url.'mandamiento/pagosvarios/consulta',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'idPago'   =>$idPago
          ]

        ]);

      $r = json_decode($res->getBody());
      $info=$r->data[0];
      $idPagoRecargo=$info->ID_TIPO_PAGO_RECARGO;


        $client2 = new Client();
        $res2 = $client2->request('POST', $this->url.'mandamiento/pagosvarios/consulta',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'idPago'   =>$idPagoRecargo
          ]

        ]);

      $r2 = json_decode($res2->getBody());

      if($r2->status==200){
            $info2=$r2->data[0];
           return  $info2;
      }else{
         return 0;
      }


    }
    public  function comprobarFecha()
    {
      if ($this->todayDate > $this->endDate) {
        return true;
      }else{
        return false;
      }

    }

    public  function mesDiferencias(){
      $dateEnd = $this->endDate;
      $dateToday = $this->todayDate;
      $splitEndDate= explode("-", $dateEnd);
      $splitTodayDate= explode("-", $dateToday);
      $yearEnd = (int)$splitEndDate[0];
      $yearToday = (int)$splitTodayDate[0];
      $monthEnd = (int)$splitEndDate[1];
      $monthToday = (int)$splitTodayDate[1];

      $diff = (($yearToday - $yearEnd) * 12) + ($monthToday - $monthEnd);
      return (int)$diff;
    }

    public  function getValorPorcentual(){
      return $this->valorPorcentual;
    }
}
