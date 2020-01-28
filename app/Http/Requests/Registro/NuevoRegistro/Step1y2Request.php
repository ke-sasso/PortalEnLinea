<?php

namespace App\Http\Requests\Registro\NuevoRegistro;

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
            'idSolicitud'        => 'required',
            'mandamiento'        => 'required',
            'nom_prod'           => 'required',
            'tipoMedicamento'    => 'required',
            'formafarm'          => 'required',
            'viaAdmin'           => 'required',
            'condAlmacenamiento' => 'required',
            'excipientes'        => 'required',
            'udosis'             => 'required',
            'modalidad'          => 'required',
            'bioequi'            => 'required',
            'formula'            => 'required',
            'idMateriasP'        => 'required|array|min:1',
            'presentaciones'     => 'required|array|min:1',
            'idEmpaquevida'      => 'required|array|min:1',
            'paisReconocimiento' => 'sometimes|required_if:origen,4',
            'noregistrorecono'   => 'sometimes|required_if:origen,4'
        ];
    }

    public function attributes()
    {
        return[
            'idSolicitud'    => 'ID Solicitud',
            'mandamiento'    =>  'Número de mandamiento',
            'nom_prod'        => 'Nombre del producto',
            'tipoMedicamento' => 'Tipo de medicamento',
            'formafarm'       => 'Forma Farmaceutica',
            'viaAdmin'        => 'Via de administración',
            'condAlmacenamiento' => 'Condiciones de almacenamiento',
            'excipientes'        => 'Excipientes',
            'udosis'             =>'Unidad de dosis',
            'idMateriasP'        => 'Principio activo',
            'presentaciones'     => 'Presentación',
            'modalidad'          => 'Modalidad de venta',
            'bioequi'            => 'Bioequivalente',
            'formula'            => 'Formula',
            'idEmpaquevida'      => 'Vida útil',
            'paisReconocimiento' => 'País de origen',
            'noregistrorecono'   => 'Número de registro'
        ];
    }

    public function messages()
    {
        return[
            'idSolicitud.required'          => 'ID Solicitud es requerido',
            'mandamiento.required'          => 'Número de Mandamiento es requerido',
            'nom_prod.required'             => 'Nombre del producto es requerido',
            'tipoMedicamento.required'      => 'Tipo de medicamento es requerido',
            'formafarm.required'            => 'Forma Farmaceutica es requerido',
            'viaAdmin.required'             => 'Via de administración es requerido',
            'condAlmacenamiento.required'           => 'Condiciones de almacenamiento es requerido',
            'excipientes.required'                   => 'Excipientes es requerido',
            'udosis.required'                        => 'Unidad de dosis es requerido',
            'idMateriasP.required'                   => 'Uno o más principios activos es requerido',
            'presentaciones.required'                => 'Una o más presentaciones es requerido',
            'modalidad.required'                     => 'Modalidad de venta es requerido',
            'bioequi.required'                       => 'Bioequivalente es requerido',
            'formula.required'                       => 'Formula es requerido',
            'idEmpaquevida.required'                 => 'Una o más vida útil de empaque',
            'paisReconocimiento.required_if'           => 'País de origen es requerido',
            'noregistrorecono.required_if'             => 'Número de registro es requerido',
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


        /*$response = new JsonResponse(
            [
                'status' => 422,
                'errors' => $msg
            ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);*/
    }
}
