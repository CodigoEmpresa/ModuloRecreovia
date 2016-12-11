<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Controllers\Controller;
use App\Modulos\Recreovia\Cronograma;
use App\Modulos\Recreovia\Recreopersona;
use App\Modulos\Recreovia\Sesion;
use App\Http\Requests\GuardarSesionGestor;
use Illuminate\Http\Request;

class SesionController extends Controller {
	
	public function __construct()
	{
		if (isset($_SESSION['Usuario']))
			$this->usuario = $_SESSION['Usuario'];
	}

	public function crearSesionesGestor(Request $request, $id_cronograma)
	{
		$cronograma = Cronograma::with(['punto', 'punto.profesores.persona', 'jornada', 'sesiones'])
											->find($id_cronograma);
											
		$formulario = [
			'titulo' => 'Crear o editar sesiones',
			'cronograma' => $cronograma,
			'sesion' => null,
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Programación',
			'formulario' => view('idrd.recreovia.formulario-sesiones', $formulario)
		];

		return view('form', $datos);
	}

	public function editarSesionesGestor(Request $request, $id_cronograma, $id_sesion)
	{
		$cronograma = Cronograma::with(['punto', 'punto.profesores.persona', 'jornada', 'sesiones'])
											->find($id_cronograma);

		$sesion = Sesion::find($id_sesion);
											
		$formulario = [
			'titulo' => 'Crear o editar sesiones',
			'cronograma' => $cronograma,
			'sesion' => $sesion,
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Programación',
			'formulario' => view('idrd.recreovia.formulario-sesiones', $formulario)
		];

		return view('form', $datos);
	}

	public function procesarGestor(GuardarSesionGestor $request)
	{
		if ($request->input('Id') == 0)
			$sesion = new Sesion;
		else
			$sesion = Sesion::find($request->input('Id'));

		$sesion->Id_Cronograma = $request->input('Id_Cronograma');
		$sesion->Id_Recreopersona = $request->input('Id_Recreopersona');
		$sesion->Objetivo_General = $request->input('Objetivo_General');
		$sesion->Recursos = $request->input('Recursos');
		$sesion->Fecha = $request->input('Fecha');
		$sesion->Inicio = $request->input('Inicio');
		$sesion->Fin = $request->input('Fin');
		$sesion->Estado = 'Gestor';

		$sesion->save();

		return redirect('/programacion/gestores/'.$request->input('Id_Cronograma').'/sesiones')->with(['status' => 'success']);
	}

	public function sesionesProfesor(Request $request)
	{
		
	}

}