<?php

namespace App\Http\Controllers\Recreovia;

use App\Http\Controllers\Controller;
use App\Modulos\Recreovia\Cronograma;
use App\Modulos\Recreovia\Recreopersona;
use App\Modulos\Recreovia\Sesion;
use App\Modulos\Recreovia\Punto;
use App\Modulos\Recreovia\Jornada;
use App\Http\Requests\GuardarCronograma;
use Illuminate\Http\Request;

class ProgramacionController extends Controller {

	public function __construct()
	{
		if (isset($_SESSION['Usuario']))
			$this->usuario = $_SESSION['Usuario'];
	}

	public function index(Request $request)
	{
		$request->flash();

		$cronogramas = Cronograma::with('punto')
                                ->whereHas('punto', function($query){
                                    $query->whereNull('deleted_at');
                                })
								->where('Id_Recreopersona', $this->usuario['Recreopersona']->Id_Recreopersona)
								->get();

		if ($request->isMethod('get'))
		{
			$qb = null;
			$elementos = $qb;
		} else {
			$qb = Cronograma::with('punto', 'jornada', 'sesiones')
                ->whereHas('punto', function($query){
                    $query->whereNull('deleted_at');
                });
			$qb = $this->aplicarFiltro($qb, $request);

			$elementos = $qb->whereNull('deleted_at')
							->where('Id_Recreopersona', $this->usuario['Recreopersona']->Id_Recreopersona)
							->orderBy('created_at', 'DESC')
							->get();
		}

		$puntos = [];

		foreach($cronogramas as $cronograma)
		{
			if (!array_key_exists($cronograma->punto['Id_Punto'], $puntos))
				$puntos[$cronograma->punto['Id_Punto']] = $cronograma->punto;
		}

		$lista = [
			'titulo' => 'Programación',
	        'elementos' => $elementos,
			'puntos' => $puntos,
			'jornadas' => Jornada::all(),
			'crear' => true,
	        'status' => session('status'),
		];

		$datos = [
			'seccion' => 'Programación',
			'lista'	=> view('idrd.recreovia.lista-cronogramas', $lista)
		];

		return view('list', $datos);
	}

	public function todos(Request $request)
	{
		$request->flash();

		if ($request->isMethod('get'))
		{
			$qb = null;
			$elementos = $qb;
		} else {
			$qb = Cronograma::with('punto', 'jornada', 'gestor', 'gestor.persona', 'sesiones');
            $qb->whereHas('punto', function($query)
            {
                $query->whereNull('deleted_at');
            });
			$qb = $this->aplicarFiltro($qb, $request);

			$elementos = $qb->whereNull('deleted_at')
							->orderBy('created_at', 'DESC')
							->get();
		}

		$lista = [
			'titulo' => 'Programación',
	        'elementos' => $elementos,
			'puntos' => Punto::all(),
			'jornadas' => Jornada::all(),
			'crear' => false,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Gestion global de sesiones',
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
							}])->find($cronograma->Id_Recreopersona);

		$puntos = $this->obtenerPuntosLocalidades($recreopersona->localidades);

		if (!$puntos->search(function($item, $key) use ($cronograma) { return $item['Id_Punto'] == $cronograma['Id_Punto']; }))
		{
			$puntos = Punto::with('jornadas')->where('Id_Punto', $cronograma['Id_Punto'])->get();

			foreach($puntos as $punto)
			{
				foreach($punto->jornadas as &$jornada)
				{
					$jornada->Label = $jornada->toString();
					$jornada->Code = $jornada->getCode();
				}
			}
		}

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
			$cronograma['Id_Recreopersona'] = $this->usuario['Recreopersona']->Id_Recreopersona;
		} else {
			$cronograma = Cronograma::find($request->input('Id'));
		}

		$cronograma['Id_Punto'] = $request->input('Id_Punto');
		$cronograma['Id_Jornada'] = $request->input('Id_Jornada');
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

		return redirect('/programacion')
					->with('status', 'success');
	}


	public function disponibilidad(Request $request)
	{
		$profesores = $request->input('profesores');
		$fecha = $request->input('fecha');
		$inicio = $request->input('inicio');
		$fin = $request->input('fin');

		$sesiones = Sesion::where('Fecha', $fecha)
								->where(function($query) use ($profesores) {
									$query->whereIn('Id_Recreopersona', $profesores)
										->orWhereHas('acompanantes', function($query) use ($profesores)
										{
											$query->whereIn('Sesiones_Acompanantes.Id_Recreopersona', $profesores);
										});
								})->where(function($query) use ($inicio, $fin)
								{
									$query->whereBetween('Inicio', [$inicio, $fin])
											->orWhereBetween('Fin', [$inicio, $fin]);
								})
								->get();

		$profesores_ocupados = $sesiones->pluck('Id_Recreopersona')->toArray();
		foreach ($sesiones as $sesion)
		{
			$acompanantes = $sesion->acompanantes->pluck('Id_Recreopersona')->toArray();

			$acompanantes_de_la_lista = array_intersect($profesores, $acompanantes);
			$profesores_ocupados = array_merge($profesores_ocupados, $acompanantes_de_la_lista);
		}

		array_map('intval', $profesores_ocupados);

		$profesores_disponibles = array_diff($profesores, $profesores_ocupados);

		return response()->json($profesores_disponibles);
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
					$jornada->Code = $jornada->getCode();
				}

				$puntos->push($punto);
			}
		}

		return $puntos;
	}

	private function aplicarFiltro($qb, $request)
	{
		if($request->input('punto') && $request->input('punto') != 'Todos')
		{
			$qb->where('Id_Punto', $request->input('punto'));
		}

		if($request->input('jornada') && $request->input('jornada') != 'Todos')
		{
			$qb->where('Id_Jornada', $request->input('jornada'));
		}

		return $qb;
	}
}
