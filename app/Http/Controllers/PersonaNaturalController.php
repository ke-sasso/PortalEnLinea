<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PersonaNatural;
use App\Http\Requests;
use Config;
class PersonaNaturalController extends Controller
{
    //


    public function ActualizarDatos(Request $request){

    	$nit=Session::get('user');
    	$persona=PersonaNatural::find($nit);
    	if($persona!=null){
    		$persona->emailsContacto=$request->correo;
    		$persona
    	}

    }

}