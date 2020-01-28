<?php

namespace App\Http\Requests\Registro\NuevoRegistro;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class Step4Request extends FormRequest
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
            'idSolicitud3'               => 'required',
            'idFabricantePri'             => 'required',
            'origenFab'                   => 'required',
            'origenFabAlterno'            => 'sometimes',
            'fabricantesAlternos'         => 'sometimes|array|min:1',
            'origenFabAcondicionador'     => 'sometimes',
            'laboratorioAcondicionador'   => 'sometimes|array|min:1',
            
        ];
    }

    public function attributes()
    {
        return[
            'idSolicitud3'                => 'Id Solicitud',
            'idFabricantePri'             => 'Fabricante principal',
            'origenFab'                   => 'Origen de fabricante principal',
            'origenFabAlterno'            => 'Origen fabricante alterno',
            'fabricantesAlternos'         => 'Fabricante alterno',
            'origenFabAcondicionador'     => 'Origen laboratorio acondicionador',
            'laboratorioAcondicionador'   => 'laboratorio acondiconador',

        ];
    }

    public function messages()
    {
        return[
            'idSolicitud3.required'                => 'Id Solicitud es requerido',
            'idFabricantePri.required'             => 'Fabricante principal es requerido',
            'origenFab.required'                   => 'Origen de fabricante principal es requerido',
            'origenFabAlterno.sometimes'            => 'Origen fabricante alterno son requeridos',
            'fabricantesAlternos.sometimes'         => 'Uno o mas fabricante alterno son requeridos',
            'origenFabAcondicionador.sometimes'     => 'Origen laboratorio acondicionador',
            'laboratorioAcondicionador.sometimes'   => 'Uno o mas laboratorio acondicionador son requeridos',
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

        /*throw new HttpResponseException(response()->json(['errors' => $msg
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));*/


        $response = new JsonResponse(
            [
                'status' => 422,
                'errors' => $msg
            ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
