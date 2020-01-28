<?php

namespace App\Http\Controllers\Registro;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Cssp\SolicitudesVue;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Config;

class ProductosController extends Controller
{
    //
    private $url=null;
    
    public function __construct() { 
        $this->url = Config::get('app.api');
    }


	public function getGenerales(Request $request){

		$idProducto= $request->idProducto;
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/prod/get/generales',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idProducto' => trim($idProducto),
	    
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			$data['producto'] = $r->data[0];

			return view('registro.tabs.paneles.generales',$data);
				//return response()->json(['status' => 200,'message' => "Datos del producto", 'data' =>$r->data[0]]);
		}
		else{
			
		}
	}

	public function getPropietario(Request $request){

		$idProducto= $request->idProducto;
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/prod/get/propietario',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idProducto' => trim($idProducto),
	    
	    	]	
    	]);

		$r = json_decode($res->getBody());

		
		if($r->status==200){
			
			$data['propietario'] = $r->data[0];

			//return $data;
			return view('registro.tabs.paneles.propietarios',$data);
			//return response()->json(['status' => 200,'message' => "Datos del producto", 'data' =>$r->data[0]]);
		}
		else{
			
		}
	}

	public function getProfesional(Request $request){

		$idProducto= $request->idProducto;
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/prod/get/profesionales',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idProducto' => trim($idProducto),
	    
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
				$data['persona'] = $r->data[0]->persona;
				//dd($data);
				return view('registro.tabs.paneles.profesional',$data);
				//return response()->json(['status' => 200,'message' => "Datos del producto", 'data' =>$r->data[0]]);
		}
		else{
			
		}
	}

	public function getFabricantes(Request $request){

		$idProducto= $request->idProducto;
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/prod/get/fabricantes',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idProducto' => trim($idProducto),
	    
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			$data['fabricantes'] = $r->data[0];
				
			return view('registro.tabs.paneles.fabricantes',$data);
			//return response()->json(['status' => 200,'message' => "Datos del producto", 'data' =>$r->data[0]]);
		}
		else{
			
		}
	}

	public function getDistribuidores(Request $request){

		$idProducto= $request->idProducto;
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/prod/get/distribuidores',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idProducto' => trim($idProducto),
	    
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			$data['distribuidores']=$r->data[0];
			return view ('registro.tabs.paneles.distribuidores',$data);	
			//return response()->json(['status' => 200,'message' => "Datos del producto", 'data' =>$r->data[0]]);
		}
		else{
			
		}
	}

	
	public function getFormaFarma(Request $request){

		$idProducto= $request->idProducto;
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/prod/get/fomafarma',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idProducto' => trim($idProducto),
	    
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			$data['formasfarm'] = $r->data[0];
				
			return view('registro.tabs.paneles.forma',$data);
			//return response()->json(['status' => 200,'message' => "Datos del producto", 'data' =>$r->data[0]]);
		}
		else{
			
		}
	}

	public function getPresentaciones(Request $request){

		$idProducto= $request->idProducto;
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/prod/get/presentaciones',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idProducto' => trim($idProducto),
	    
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			$data['presentaciones'] = $r->data[0];
			//return $data;	
			return view('registro.tabs.paneles.presentacion',$data);
			//return response()->json(['status' => 200,'message' => "Datos del producto", 'data' =>$r->data[0]]);
		}
		else{
			
		}
	}

	public function getLabsAcondi(Request $request){

		$idProducto= $request->idProducto;
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/prod/get/labacondicionadores',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idProducto' => trim($idProducto),
	    
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			$data['labsacondi'] = $r->data[0];
				
			return view('registro.tabs.paneles.labacondicionadores',$data);
			//return response()->json(['status' => 200,'message' => "Datos del producto", 'data' =>$r->data[0]]);
		}
		else{
			
		}
	}

	public function getNomExpProducto(Request $request){

		$idProducto= $request->idProducto;
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/prod/get/nomexpo',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idProducto' => trim($idProducto),
	    
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			$data['nomexport'] = $r->data[0];
				
			return view('registro.tabs.paneles.nombresexp',$data);
			//return response()->json(['status' => 200,'message' => "Datos del producto", 'data' =>$r->data[0]]);
		}
		else{
			
		}
	}

	public function getPoderes(Request $request){

		$idProducto= $request->idProducto;
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/prod/get/poderprofesional',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idProducto' => trim($idProducto),
	    
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			$data['poderes']['profesional']=$r->data[0];

		}

		$res1 = $client->request('POST', $this->url.'pel/prod/get/poderapoderado',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idProducto' => trim($idProducto),
	    
	    	]	
    	]);

		$r1 = json_decode($res1->getBody());

		if($r1->status==200){
			$data['poderes']['apoderados']=$r1->data[0];

		}
		//dd($data);
		return view('registro.tabs.paneles.poderes',$data);
	}

	public function getPrincipiosAct(Request $request){

		$idProducto= $request->idProducto;
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/prod/get/principiosactivos',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idProducto' => trim($idProducto),
	    
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			$data['principiosA'] = $r->data[0];
				
			return view('registro.tabs.paneles.principios',$data);
			//return response()->json(['status' => 200,'message' => "Datos del producto", 'data' =>$r->data[0]]);
		}
		else{
			
		}
	}

	public function getFormula(Request $request){

		$idProducto= $request->idProducto;
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/prod/get/generales',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idProducto' => trim($idProducto),
	    
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			$data['producto'] = $r->data[0];

			return view('registro.tabs.paneles.formula',$data);
				//return response()->json(['status' => 200,'message' => "Datos del producto", 'data' =>$r->data[0]]);
		}
		else{
			
		}
	}

	public function getExcipiente(Request $request){

		$idProducto= $request->idProducto;
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/prod/get/generales',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idProducto' => trim($idProducto),
	    
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			$data['producto'] = $r->data[0];

			return view('registro.tabs.paneles.excipientes',$data);
				//return response()->json(['status' => 200,'message' => "Datos del producto", 'data' =>$r->data[0]]);
		}
		else{
			
		}
	}


	public function getTabsProductos(){
		return view('registro.tramites.ventexp.tramite33');
	}

	public function tramitesDeProductoRv(Request $request){
      if($request->idProducto!=null){
          $productoConTramites=SolicitudesVue::getSolicitudesEnProceso($request->idProducto);
          //dd($productoConTramites);
          //dd($productoConTramites->isEmpty());
          if($productoConTramites->isEmpty()){
             return response()->json(['status' => 200,'message' => 'Sin tramites pendientes', 'data' =>[]]);
          }
          else{
            return response()->json(['status' => 400,'message' => 'No se puede realizar el tramite, debido a que hay tramites post- registro pendientes en la Unidad de Registro y Visado con el producto.', 'data' =>$productoConTramites ]);
          }
      }
      else{
        return response()->json(['status' => 404,'message' => 'Error: Problemas en el servidor, intentelo de nuevo!', 'data' => []]);
      }
   }
}
