<?php

namespace App\Http\Requests\Cosmeticos\NuevoRegistro;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exception\HttpResponseException;

class Step1y2Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'idSolicitud'    => 'required',
            'tipoTramite'    => 'required',
            'mandamiento'    => 'required',
            'nomProd'        => 'required',
            'marca'          => 'sometimes|required',
            'areaApli'       => 'sometimes|required',
            'clasificacion'  => 'sometimes|required',
            'formacos'       => 'sometimes|required',
            'presentaciones' => 'sometimes|required|array|min:1',
            'tonos'          => 'sometimes|array|min:1',
            'fragancias'     => 'sometimes|array|min:1',
            'coempaque'      => 'sometimes|required',
            'detcoempaque'   => 'sometimes|required_if:coempaque,1',
            'fechaVen'       => 'required_if:tipoTramite,3|required_if:tipoTramite,5|date'
        ];
    }

    public function attributes()
    {
        return[
            'idSolicitud'    => 'ID Solicitud',
            'tipoTramite'    => 'Tipo de Trámite',
            'mandamiento'    => 'Número de Mandamiento',
            'nomProd'        => 'Nombre de Producto',
            'marca'          => 'Marca del Producto',
            'areaApli'       => 'Area de Aplicación',
            'clasificacion'  => 'Clasificación',
            'formacos'       => 'Forma Cosmética',
            'presentaciones' => 'Presentaciones',
            'tonos'          => 'Tonos',
            'fragancias'     => 'Fragancias',
            'coempaque'      => 'Coempaque',
            'detcoempaque'   => 'Detalle Coempaque',
            'fechaVen'       => 'Fecha de vencimiento'

        ];
    }

    public function messages()
    {
        return[
            'idSolicitud.required'                => 'ID Solicitud',
            'tipoTramite.required'                => 'Tipo de Trámite es requerido',
            'mandamiento.required'                => 'Número de Mandamiento es requerido',
            'nomProd.required'                    => 'Nombre del Producto es requerido',
            'marca.sometimes'                     => 'Marca del Producto es requerido',
            'areaApli.sometimes'                  => 'Área de Aplicacion es requerido',
            'clasificacion.sometimes'             => 'Clasificación es requerido',
            'formacos.sometimes'                  => 'Forma Cosmética es requerido',
            'presentaciones.sometimes'            => 'Una o más presentaciones son requeridas',
            'tonos.sometimes'                     => 'Tonos es requerido',
            'fragancias.sometimes'                => 'Fragancias es requerido',
            'coempaque.sometimes'                 => 'Coempaque es requerido',
            'detcoempaque.sometimes'              => 'Detalle coempaque es requerido',
            'fechaVen.required_if'                => 'Fecha de vencimiento es requerido'

        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors()->getMessages();
        $obj = $validator->failed();
        $result = [];
        $msg = "<ul class='text-warning'>";
        foreach($obj as $input => $rules){
            $i = 0;
            foreach($rules as $rule => $ruleInfo){
                //$rule = $input.'['.strtolower($rule).']';
                $result[$i] = "<li>".$errors[$input][$i]."</li>";
                $msg .= "<li>".$errors[$input][$i]."</li>";
                $i++;
            }
        }
        $msg .= "</ul>";
        //return $result;

        $errors = $validator->errors()->getMessages();

        throw new HttpResponseException(response()->json(['errors' => $msg
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));


        $response = new JsonResponse(
            [
                'status' => 422,
                'errors' => $msg
            ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
