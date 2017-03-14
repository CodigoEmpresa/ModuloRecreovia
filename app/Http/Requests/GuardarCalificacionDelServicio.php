<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class GuardarCalificacionDelServicio extends Request
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
            'Puntualidad_PAF' => 'required',
            'Tiempo_De_La_Sesion' => 'required',
            'Escenario_Y_Montaje' => 'required',
            'Cumplimiento_Del_Objetivo' => 'required',
            'Variedad_Y_Creatividad' => 'required',
            'Imagen_Institucional' => 'required',
            'Divulgacion' => 'required',
            'Seguridad' => 'required',
            'Nombre' => 'required',
            'Telefono' => 'required'
        ];
    }
}
