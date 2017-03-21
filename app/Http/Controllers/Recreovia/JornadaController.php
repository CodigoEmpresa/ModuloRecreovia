<?php

namespace App\Http\Controllers\Recreovia;

use App\Http\Requests\GuardarJornada;
use App\Http\Controllers\Controller;
use App\Modulos\Recreovia\Jornada;
use App\Modulos\Recreovia\Punto;
use Illuminate\Http\Request;

class JornadaController extends Controller {

	protected $usuario;

	public function __construct()
	{
		if (isset($_SESSION['Usuario']))
			$this->usuario = $_SESSION['Usuario'];
	}

	public function index()
	{
		$elementos = Jornada::whereNull('deleted_at')
							->orderBy('Id_Jornada', 'ASC')
							->get();

		$lista = [
			'titulo' => 'Jornadas',
	        'elementos' => $elementos,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Jornadas',
			'lista'	=> view('idrd.recreovia.lista-jornadas', $lista)
		];

		return view('list', $datos);
	}

	public function crear()
	{
		$puntos = Punto::whereNull('deleted_at')
							->orderBy('Escenario', 'ASC')
							->get();

		$formulario = [
			'titulo' => 'Crear ó editar jornadas',
			'jornada' => null,
			'puntos' => $puntos,
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Jornadas',
			'formulario' => view('idrd.recreovia.formulario-jornadas', $formulario)
		];

		return view('form', $datos);
	}

	public function editar(Request $request, $id)
	{
		$puntos = Punto::whereNull('deleted_at')
							->orderBy('Escenario', 'ASC')
							->get();

		$formulario = [
			'titulo' => 'Crear ó editar jornadas',
			'puntos' => $puntos,
			'jornada' => Jornada::with('puntos')->find($id),
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Jornadas',
			'formulario' => view('idrd.recreovia.formulario-jornadas', $formulario)
		];

		return view('form', $datos);
	}

	public function eliminar(Request $request, $id)
	{
		$jornada = Jornada::where('Id_Jornada', $id)
						->first();

		$jornada->delete();

		return redirect('/jornadas')->with(['status' => 'success']);
	}


	public function procesar(GuardarJornada $request)
	{
		if ($request['Id_Jornada'] == 0)
			$jornada = $this->crearJornada($request);
		else
			$jornada = $this->editarJornada($request);

		$jornada->Jornada = $request['Jornada'];
		$jornada->Dias = implode(',', $request['Dias']);
		$jornada->Fecha_Evento_Inicio = $request['Fecha_Evento_Inicio'];
		$jornada->Fecha_Evento_Fin = $request['Fecha_Evento_Fin'];
		$jornada->Inicio = $request['Inicio'];
		$jornada->Fin = $request['Fin'];
		$jornada->Tipo = $request['Tipo'];
		$jornada->save();

		if (array_key_exists('puntos', $request->all()))
			$jornada->puntos()->sync($request['puntos']);

       	return redirect('/jornadas/'.$jornada->Id_Jornada.'/editar')->with(['status' => 'success']);
	}

	private function crearJornada($request)
	{
		$jornada = new Jornada;
		return $jornada;
	}

	private function editarJornada($request)
	{
		$jornada = Jornada::find($request->Id_Jornada);
		return $jornada;
	}
}
