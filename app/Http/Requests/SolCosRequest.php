<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exception\HttpResponseException;


class SolCosRequest extends FormRequest
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
            'tipoTramite'    => 'required',
            'mandamiento'    => 'required',
            'nomProd'        => 'required',
            'marca'          => 'required',
            'areaApli'       => 'sometimes',
            'clasificacion'  => 'required',
            'formacos'       => 'sometimes',
            'presentaciones' => 'required|array|min:1',
            //'idDenomiacion'  => 'required|array|min:1',
            //'porcentaje'     => 'required|array|min:1',
            'tonos'          => 'sometimes|array|min:1',
            'fragancias'     => 'sometimes|array|min:1',
            'tipoTitular'    => 'required',
            'titular'        => 'required',
            'poderProf'      => 'required',
            'idProfesional'  => 'required',
            'fabricantes'    => 'required|array|min:1',
            'importadores'   => 'required_if:tipoTramite,3|required_if:tipoTramite,5|array|min:1',
            'dist'           => 'required|array|min:1'
        ];
    }

    public function attributes()
    {
        return[
            'tipoTramite'    => 'Tipo de Trámite',
            'mandamiento'    => 'Número de Mandamiento',
            'nomProd'        => 'Nombre de Producto',
            'marca'          => 'Marca del Producto',
            'areaApli'       => 'Area de Aplicación',
            'clasificacion'  => 'Clasificación',
            'formacos'       => 'Forma Cosmética',
            'presentaciones' => 'Presentaciones',
            'idDenomiacion'  => 'Sustancia',
            'porcentaje'     => 'Porcentaje',
            'tonos'          => 'Tonos',
            'fragancias'     => 'Fragancias',
            'tipoTitular'    => 'Tipo del Titular',
            'titular'        => 'Titular del producto',
            'poderProf'      => 'Poder del Profesional',
            'idProfesional'  => 'Profesional Responsable',
            'fabricantes'    => 'Fabricantes',
            'importadores'   => 'Importadores',
            'dist'           => 'Distribuidores'
        ];
    }

    public function messages()
    {
        return[
            'tipoTramite.required'                => 'Tipo de Trámite es requerido',
            'mandamiento.required'                => 'Número de Mandamiento es requerido',
            'nomProd.required'                    => 'Nombre del Producto es requerido',
            'marca.required'                      => 'Marca del Producto es requerido',
            'areaApli.required'                   => 'Área de Aplicacion es requerido',
            'clasificacion.required'              => 'Clasificación es requerido',
            'formacos.required'                   => 'Forma Cosmética es requerido',
            'presentaciones.required'             => 'Una o más presentaciones son requeridas',
            'idDenomiacion.required'              => 'Sustancia es requerido',
            'porcentaje.required'                 => 'Porcentaje es requerido',
            'tonos.required'                      => 'Tonos es requerido',
            'fragancias.required'                 => 'Fragancias es requerido',
            'tipoTitular.required'                => 'Tipo de titular es requerido',
            'titular.required'                    => 'Titular del producto es requerido',
            'poderProf.required'                  => 'Poder del Profesional es requerido',
            'idProfesional.required'              => 'Profesional del Responsable es requerido',
            'fabricantes.required'                => 'Uno o más fabricantes son requeridos',
            'importadores.required_if'   => 'Uno o más importadores son requeridos',
            'dist'                       => 'Uno o más distribuidores son requeridos'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException($this->response(
            $this->formatErrors($validator)
        ));
    }
}
