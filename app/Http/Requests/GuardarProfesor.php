<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class GuardarProfesor extends Request
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
            'Id_TipoDocumento' => 'required|min:1',
            'Cedula' => 'required|numeric|unique:db_principal.persona,Cedula,'.$this->input('Id_Persona').',Id_Persona',
            'Primer_Apellido' => 'required',
            'Primer_Nombre' => 'required',
            'Fecha_Nacimiento' => 'required|date',
            'Id_Etnia' => 'required|min:1',
            'Id_Pais' => 'required|min:1',
            'Id_Genero' => 'required|in:1,2',
            'tipo' => 'required|in:Profesor,Gestor'
        ];
    }
}
