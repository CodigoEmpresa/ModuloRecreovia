<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AgruparYTransferirCronogramas extends Request
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
        $rules = [
            'operacion' => 'required',
            'cronogramas' => 'required|exists_multiple:Cronogramas,Id',
        ];

        switch ($this->input('operacion'))
        {
            case 'transferir':
                $rules['codigo'] = 'required|exists:Recreopersonas,Id_Recreopersona';
                break;
            case 'agrupar':
                $rules['codigo'] = 'required|exists:Cronogramas,Id';
                break;
            default:
                $rules['codigo'] = 'required';
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'cronogramas.required' => 'Debe ingresar los cronogramas que seran agrupados',
            'cronogramas.exist_multiple' => 'Alguno de los cronogramas ingresados no existe',
            'codigo.required' => 'El código de destino es requerido',
            'codigo.exists' => 'El código de destino no existe'
        ];
    }
}
