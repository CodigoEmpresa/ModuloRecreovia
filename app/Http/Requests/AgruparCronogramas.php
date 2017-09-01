<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AgruparCronogramas extends Request
{

    function __construct(){
        parent::__construct();
    }

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
            'cronogramas' => 'required|exists_multiple:Cronogramas,Id',
            'codigo' => 'required|exists:Cronogramas,Id'
        ];
    }

    public function messages()
    {
        return [
            'cronogramas.required' => 'Debe ingresar los cronogramas que seran agrupados',
            'cronogramas.exist_multiple' => 'Alguno de los cronogramas ingresados no existe',
            'codigo.required' => 'El cronograma de destino es requerido',
            'codigo.exists' => 'El cronograma de destino no existe'
        ];
    }
}
