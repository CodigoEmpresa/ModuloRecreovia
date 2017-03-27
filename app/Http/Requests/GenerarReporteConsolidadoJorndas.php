<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class GenerarReporteConsolidadoJorndas extends Request
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
            'Id_Jornada' => 'required',
            'Dias' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'Id_Jornada.required' => 'El campo jornada es requerido',
            'Dias.required' => 'Debe seleccionar al menos un día para generar el reporte'
        ];
    }
}
