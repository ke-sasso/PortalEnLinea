<?php

namespace App\Http\Middleware;

use Closure;
use Request;

class VerifyAccessKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Obtenemos el api-key que el usuario envia
        $key = Request::header('tk');
        // Si coincide con el valor almacenado en la aplicacion
        // la aplicacion se sigue ejecutando
        if (isset($key) == '$2y$10$d7W6n5WX/0JJnxBtnIJrY.n5LfugsV/zM4VOXZ2M98E4AjkkCzapC') {
            return $next($request);
        } else {
            // Si falla devolvemos el mensaje de error
            return response()->json(['status' => 401, 'message' => 'unauthorized' ], 401);
        }
    }
}
