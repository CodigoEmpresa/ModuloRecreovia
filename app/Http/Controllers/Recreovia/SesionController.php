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
		$cronograma = Cronograma::with(['punto', 'punto.profesores.persona', 'jornada', 'sesiones', 'sesiones.profesor'])
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

	public function editarSesionProfesor(Request $request, $id_sesion)
	{
		$sesion = Sesion::with('cronograma', 'cronograma.punto', 'cronograma.jornada', 'profesor')->find($id_sesion);
											
		$formulario = [
			'titulo' => 'Sesion',
			'sesion' => $sesion,
			'tipo' => 'profesor',
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Sesiones profesor',
			'formulario' => view('idrd.recreovia.formulario-sesiones-detalles', $formulario)
		];

		return view('form', $datos);
	}

	public function editarSesionGestor(Request $request, $id_sesion)
	{
		$sesion = Sesion::with('cronograma', 'cronograma.punto', 'cronograma.jornada', 'profesor')->find($id_sesion);
											
		$formulario = [
			'titulo' => 'Sesion',
			'sesion' => $sesion,
			'tipo' => 'gestor',
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Sesiones gestor',
			'formulario' => view('idrd.recreovia.formulario-sesiones-detalles', $formulario)
		];

		return view('form', $datos);
	}

	public function eliminarSesionesGestor(Request $request, $id_cronograma, $id_sesion)
	{
		$sesion = Sesion::find($id_sesion);
		$sesion->delete();

		return redirect('/gestores/'.$request->input('Id_Cronograma').'/sesiones')->with(['status' => 'success']);
	}

	public function procesarGestor(GuardarSesionGestor $request)
	{
		if ($request->input('Id') == 0)
		{
			$sesion = new Sesion;
			$nuevo = true;
		} else {
			$sesion = Sesion::find($request->input('Id'));
			$nuevo = false;
		}

		$sesion->Id_Cronograma = $request->input('Id_Cronograma');
		$sesion->Id_Recreopersona = $request->input('Id_Recreopersona');
		$sesion->Objetivo_General = $request->input('Objetivo_General');
		$sesion->Recursos = $request->input('Recursos');
		$sesion->Fecha = $request->input('Fecha');
		$sesion->Inicio = $request->input('Inicio');
		$sesion->Fin = $request->input('Fin');
		$sesion->Estado = !$nuevo ? : 'Pendiente';

		$sesion->save();

		return redirect('/gestores/'.$request->input('Id_Cronograma').'/sesiones')->with(['status' => 'success']);
	}

	public function procesarProfesor(Request $request)
	{	
		$sesion = Sesion::find($request->input('Id'));
		$sesion->Objetivos_Especificos = $request->input('Objetivos_Especificos');
		$sesion->Metodologia_Aplicar = $request->input('Metodologia_Aplicar');
		$sesion->Recursos = $request->input('Recursos');
		$sesion->Fase_Inicial = $request->input('Fase_Inicial');
		$sesion->Fase_Central = $request->input('Fase_Central');
		$sesion->Fase_Final = $request->input('Fase_Final');
		$sesion->Estado = $request->input('Estado');

		$sesion->save();

		if($request->input('origen') == 'profesor')
			return redirect('/profesores/sesiones/'.$sesion['Id'].'/editar')->with(['status' => 'success']);
		else if($request->input('origen') == 'gestor')
			return redirect('/gestores/sesiones/'.$sesion['Id'].'/editar')->with(['status' => 'success']);

	}

	public function sesionesProfesor(Request $request)
	{
		$perPage = config('app.page_size');
		$elementos = Sesion::with('cronograma', 'cronograma.punto', 'cronograma.jornada')
							->whereNull('deleted_at')
							->where('Id_Recreopersona', $this->usuario['Recreopersona']->Id_Recreopersona)
							->orderBy('Id', 'DESC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Sesiones profesor',
	        'elementos' => $elementos,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Sesiones profesor',
			'lista'	=> view('idrd.recreovia.lista-sesiones-profesor', $lista)
		];

		return view('list', $datos);
	}

	public function sesionesGestor(Request $request)
	{
		$perPage = config('app.page_size');
		$elementos = Sesion::with('cronograma', 'cronograma.punto', 'cronograma.jornada')
							->whereHas('cronograma', function($query)
							{
								$query->where('Id_Recreopersona', $this->usuario['Recreopersona']->Id_Recreopersona);
							})
							->whereNull('deleted_at')
							->orderBy('Id', 'DESC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Sesiones gestor',
	        'elementos' => $elementos,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Sesiones gestor',
			'lista'	=> view('idrd.recreovia.lista-sesiones-gestor', $lista)
		];

		return view('list', $datos);
	}

}