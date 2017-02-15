<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Controllers\Controller;
use App\Modulos\Recreovia\Cronograma;
use App\Modulos\Recreovia\Recreopersona;
use App\Modulos\Recreovia\Sesion;
use App\Http\Requests\GuardarCronograma;
use Illuminate\Http\Request;

class ProgramacionController extends Controller {
	
	public function __construct()
	{
		if (isset($_SESSION['Usuario']))
			$this->usuario = $_SESSION['Usuario'];
	}

	public function index()
	{
		$elementos = Cronograma::with('punto', 'jornada', 'sesiones')
							->whereNull('deleted_at')
							->where('Id_Recreopersona', $this->usuario['Recreopersona']->Id_Recreopersona)
							->orderBy('created_at', 'DESC')
							->get();

		$lista = [
			'titulo' => 'Programación',
	        'elementos' => $elementos,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Programación',
			'lista'	=> view('idrd.recreovia.lista-cronogramas', $lista)
		];
		
		return view('list', $datos);
	}

	public function crear() 
	{
		$recreopersona = Recreopersona::with(['localidades' => function($query)
										{
											return $query->where('tipo', 'Gestor');
										}, 'localidades.puntos.jornadas' => function($query) 
										{
											return $query->whereNull('Jornadas.deleted_at');
										}])->find($this->usuario['Recreopersona']->Id_Recreopersona);
		
		$puntos = $this->obtenerPuntosLocalidades($recreopersona->localidades);
		$recreopersona->puntos = $puntos;

		$formulario = [
			'titulo' => 'Crear ó editar cronograma de sesiones',
			'recreopersona' => $recreopersona,
	        'cronograma' => null,
	        'status' => session('status')
	    ];

	    $datos = [
			'seccion' => 'Programación',
			'formulario' => view('idrd.recreovia.formulario-cronograma', $formulario)
		];

		return view('form', $datos);
	}

	public function editar(Request $request, $id_cronograma)
	{
		$cronograma = Cronograma::find($id_cronograma);
		
		$recreopersona = Recreopersona::with(['localidades' => function($query)
										{
											return $query->where('tipo', 'Gestor');
										}, 'localidades.puntos.jornadas' => function($query) 
										{
											return $query->whereNull('Jornadas.deleted_at');
										}])->find($this->usuario['Recreopersona']->Id_Recreopersona);
		
		$puntos = $this->obtenerPuntosLocalidades($recreopersona->localidades);
		$recreopersona->puntos = $puntos;

		$formulario = [
			'titulo' => 'Crear ó editar cronograma de sesiones',
			'recreopersona' => $recreopersona,
	        'cronograma' => $cronograma,
	        'status' => session('status')
	    ];

	    $datos = [
			'seccion' => 'Programación',
			'formulario' => view('idrd.recreovia.formulario-cronograma', $formulario)
		];

		return view('form', $datos);
	}

	public function procesar(GuardarCronograma $request)
	{
		if ($request->input('Id') == 0)
		{
			$cronograma = new Cronograma;
		} else {
			$cronograma = Cronograma::find($request->input('Id'));
		}

		$cronograma['Id_Punto'] = $request->input('Id_Punto');
		$cronograma['Id_Jornada'] = $request->input('Id_Jornada');
		$cronograma['Id_Recreopersona'] = $this->usuario['Recreopersona']->Id_Recreopersona;
		$cronograma['Desde'] = $request->input('Desde');
		$cronograma['Hasta'] = $request->input('Hasta');
		$cronograma['recreovia'] = $request->input('recreovia');

		$cronograma->save();

		return redirect('/programacion/'.$cronograma->Id.'/editar')
					->with('status', 'success');
	}

	public function eliminar(Request $request, $id_cronograma)
	{
		$cronograma = Cronograma::find($id_cronograma);
		$cronograma->delete();

		return redirect('/programacion/gestores/')
					->with('status', 'success');
	}

	private function obtenerPuntosLocalidades($localidades)
	{
		$puntos = collect();

		foreach ($localidades as $localidad) 
		{
			foreach($localidad->puntos as $punto)
			{
				foreach($punto->jornadas as &$jornada)
				{
					$jornada->Label = $jornada->toString();
				}

				$puntos->push($punto);
			}	
		}

		return $puntos;
	}

}