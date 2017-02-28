<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class GuardarProductoNoConforme extends Request
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
            'Requisito' => 'required',
            'Descripcion_De_La_No_Conformidad' => 'required',
            'Descripcion_De_La_Accion_Tomada' => 'required',
            'Tratamiento' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'Requisito.required' => 'El campo requisito es requerido',
            'Descripcion_De_La_No_Conformidad.required' => 'El campo descripción de la no conformidad es requerido',
            'Descripcion_De_La_Accion_Tomada.required' => 'El campo descripción de la acción tomada es requerido',
            'Tratamiento.required' => 'El campo tratamiento es requerido'
        ];
    }
}
