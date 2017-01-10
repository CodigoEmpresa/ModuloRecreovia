<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class GenerarInforme extends Request
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
            'Id_Punto' => 'required',
            'Id_Cronograma' => 'required',
            'Dia' => 'required'
        ];
    }


    public function messages()
    {
        return [
            'Id_Punto.required' => 'El campo punto es requerido',
            'Id_Cronograma.required' => 'El campo cronograma es requerido'
        ];
    }
}
