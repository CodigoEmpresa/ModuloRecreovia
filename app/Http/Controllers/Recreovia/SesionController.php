<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Controllers\Controller;
use App\Modulos\Recreovia\Cronograma;
use App\Modulos\Recreovia\Recreopersona;
use App\Modulos\Recreovia\Sesion;
use Illuminate\Http\Request;

class SesionController extends Controller {
	
	public function __construct()
	{
		if (isset($_SESSION['Usuario']))
			$this->usuario = $_SESSION['Usuario'];
	}

	public function sesionesGestor(Request $request, $id_cronograma)
	{
		$cronograma = Cronograma::with('punto', 'jornada', 'sesiones')
							->find($id_cronograma);

		$formulario = [
			'titulo' => 'Crear o editar sesiones',
			'cronograma' => $cronograma,
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Programación',
			'formulario' => view('idrd.recreovia.formulario-sesiones.blade.php')
		]

		return view('form', $datos);
	}

	public function sesionesProfesor(Request $request)
	{
		
	}

}