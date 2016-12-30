<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modulos\Recreovia\Reporte;
use App\Modulos\Recreovia\Recreopersona;
use App\Modulos\Recreovia\Sesion;
use App\Modulos\Recreovia\Punto;
use App\Modulos\Recreovia\Cronograma;

class ReporteController extends Controller {
	
	protected $usuario;

	public function __construct()
	{
		if (isset($_SESSION['Usuario']))
			$this->usuario = $_SESSION['Usuario'];
	}

	public function jornadas()
	{
		$perPage = config('app.page_size');
		$elementos = Reporte::whereNull('deleted_at')
							->orderBy('Id', 'DESC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Informes jornadas',
	        'elementos' => $elementos,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Informes jornadas',
			'lista'	=> view('idrd.recreovia.lista-reportes', $lista)
		];

		return view('list', $datos);
	}

	public function crearInformeJornadas()
	{
		$recreopersona = Recreopersona::with(['puntos' => function($query)
										{
											return $query->where('tipo', 'Gestor')
														->whereNull('Puntos.deleted_at');
										}, 'puntos.cronogramas' => function($query) 
										{
											return $query->whereNull('Cronogramas.deleted_at');
										}, 'puntos.cronogramas.jornada'])->find($this->usuario['Recreopersona']->Id_Recreopersona);

		foreach ($recreopersona->puntos as $punto) 
		{
			foreach ($punto->cronogramas as &$cronograma)
			{
				$cronograma->jornada->Label = $cronograma->jornada->toString();
			}	
		}

		$formulario = [
			'titulo' => 'Crear informe',
	        'informe' => null,
	        'puntos' => $recreopersona->puntos,
	        'status' => session('status')
	    ];

	    $datos = [
			'seccion' => 'Generar informe de actividades por punto',
			'formulario' => view('idrd.recreovia.formulario-reporte-jornadas', $formulario)
		];

		return view('form', $datos);
	}
}