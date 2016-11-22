<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AgregarPersonalLocalidad extends Request
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
            'id_persona' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id_persona.required' => 'El campo persona es requerido'
        ];
    }
}
