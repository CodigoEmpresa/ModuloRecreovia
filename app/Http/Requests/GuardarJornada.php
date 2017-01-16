<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class GuardarJornada extends Request
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
            'Jornada' => 'required',
            'Fecha_Evento_Inicio' => 'required_if:Jornada,clases_grupales,mega_eventos',
            'Fecha_Evento_Fin' => 'required_if:Jornada,clases_grupales,mega_eventos',
            'Inicio' => 'required',
            'Fin' => 'required',
            'Dias' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'Fecha_Evento_Inicio.required_if' => 'El campo fecha de inicio es requerido cuando jornada es igual a :value',
            'Fecha_Evento_Fin.required_if' => 'El campo fecha de finalización es requerido cuando jornada es igual a :value',
            'Inicio.required' => 'El campo hora inicio es requerido',
            'Fin.required' => 'El campo hora fin es requerido',
            'Dias.required' => 'El campo dias es requerido'
        ];
    }
}
