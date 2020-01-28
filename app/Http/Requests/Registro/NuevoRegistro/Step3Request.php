<?php

namespace App\Http\Requests\Registro\NuevoRegistro;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;

class Step3Request extends FormRequest
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
            'idSolicitud2'     => 'required',
            'titular'          => ' required',
            'tipoTitular'      => 'required',
            'idProfesional'    => 'required',
            'nitProfesional'   => 'sometimes',
            'poderProf'         =>  'required',
            'idPresentanteLegal'=> 'sometimes',
            'poderRL'           => 'sometimes',
            'idApoderado'       => 'sometimes',
            'poderApo'          => 'sometimes',
            'nominteresado'     => 'sometimes',
            'apeinteresado'     => 'sometimes',
            'correointeresado'  => 'sometimes|email',

        ];
    }

    public function attributes()
    {
        return[
             'idSolicitud2'     => 'ID solicitud',
            'titular'          => 'Titular',
            'tipoTitular'      => 'Tipo titular',
            'idProfesional'    => 'Id profesional',
            'nitProfesional'   => 'Nit profesional',
            'poderProf'         =>  'Poder profesional',
            'idPresentanteLegal'=> 'Id presentante legal',
            'poderRL'           => 'Poder representante legal',
            'idApoderado'       => 'Id apoderado',
            'poderApo'          => 'Poder apoderado',
            'nominteresado'     => 'Nombres a terceros',
            'apeinteresado'     => 'Apellidos a terceros',
            'correointeresado'  => 'Correo a terceros',

        ];
    }

    public function messages()
    {
        return[
             'idSolicitud2.required'     => 'ID solicitud es requerido',
            'titular.required'          => ' Titular es requerido',
            'tipoTitular.required'      => 'Tipo titular es requerido',
            'idProfesional.required'    => 'Id profesional es requerido',
            'nitProfesional.sometimes'   => 'Nit profesional es requerido',
            'poderProf.required'         =>  'Poder profesional es requerido',
            'idPresentanteLegal.sometimes'=> 'Id presentante legal es requerido',
            'poderRL.sometimes'           => 'Poder representante legal es requerido',
            'idApoderado.sometimes'       => 'Id apoderado es requerido',
            'poderApo.sometimes'          => 'Poder apoderado es requerido',
            'nominteresado.sometimes'     => 'Nombres a terceros es requerido',
            'apeinteresado.sometimes'     => 'Apellido a terceros es requerido',
            'correointeresado.email'      => 'Formato de correo a terceros es incorrecto',
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
