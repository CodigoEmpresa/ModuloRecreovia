<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class GuardarPunto extends Request
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
            'Direccion' => 'required',
            'Escenario' => 'required',
            'Cod_IDRD' => 'required',
            'Cod_Recreovia' => 'required',
            'Id_Localidad' => 'required',
            'Id_Upz' => 'required',
            'Latitud' => 'required',
            'Longitud' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'Cod_IDRD.required' => 'El campo código IDRD es requerido',
            'Cod_Recreovia.required' => 'El campo código recreovia es requerido',
            'Id_Upz.required' => 'El campo upz es requerido',
            'Id_Localidad.required' => 'El campo localidad es requerido'
        ];
    }
}
