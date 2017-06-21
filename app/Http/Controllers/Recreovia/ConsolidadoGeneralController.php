<?php

namespace App\Http\Controllers\Recreovia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modulos\Recreovia\Reporte;
use App\Modulos\Recreovia\Cronograma;
use App\Modulos\Recreovia\Jornada;
use App\Modulos\Recreovia\GrupoPoblacional;
use App\Http\Requests\GenerarReporteConsolidadoJorndas;

class ConsolidadoGeneralController extends Controller {

	protected $usuario;

	public function __construct()
	{
		if (isset($_SESSION['Usuario']))
			$this->usuario = $_SESSION['Usuario'];
	}

	public function index()
	{
		$jornadas = Jornada::whereNull('deleted_at')
								->orderBy('created_at', 'desc')
								->get();

		$formulario = [
			'titulo' => 'Consolidado general jornada',
	        'jornadas' => $jornadas,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Consolidado general jornadas',
			'formulario'	=> view('idrd.recreovia.formulario-reporte-consolidado-general', $formulario)
		];

		return view('form', $datos);
	}

	public function generar(GenerarReporteConsolidadoJorndas $request)
	{

		$id_jornada = $request->input('Id_Jornada');
		$fecha = $dias = explode(',', $request->input('Dias'));
		usort($dias, function($a, $b) {
  			return strcmp($a, $b);
		});

		$query_builder = Reporte::with('cronograma', 'cronograma.jornada', 'cronograma.sesiones', 'cronograma.sesiones.gruposPoblacionales')
								->whereHas('cronograma', function($query) use ($id_jornada)
								{
									$query->where('Id_Jornada', $id_jornada);
								})
								->where('Estado', 'Finalizado');

		$query_builder->where(function($query) use ($dias)
		{
			foreach ($dias as $dia) {
				$query->orWhere('Dias', 'LIKE', '%'.$dia.'%');
			}
		});

		$reportes = $query_builder->get();
		$jornada = Jornada::find($id_jornada);
		$gruposPoblacionales = GrupoPoblacional::all();

		$totales_sesiones = [
			'Gimnasia de Mantenimiento (GM)' => [],
			'Estimulación Muscular (EM)' => [],
			'Movilidad Articular (MA)' => [],
			'Rumba Tropical Folclorica (RTF)' => [],
			'Actividad Rítmica para Niños (ARN) Rumba para Niños' => [],
			'Gimnasia Aeróbica Musicalizada (GAM)' => [],
			'Artes Marciales Musicalizadas (AMM)' => [],
			'Gimnasia Psicofísica (GPF)' => [],
			'Pilates (Pil)' => [],
			'Taller de Danzas (TD)' => [],
			'Gimnasio Saludable al Aire Libre (GSAL)' => []
		];

		foreach ($totales_sesiones as $key => &$total_sesion)
		{
			foreach ($gruposPoblacionales as $grupo)
			{
				$total_sesion[$grupo['Id']] = [
					'Nombre' => $grupo['Grupo'],
					'Participantes' => [
						'M' => 0,
						'F' => 0
					],
					'Asistentes' => [
						'M' => 0,
						'F' => 0
					],
				];
			}
		}

		foreach ($reportes as $reporte)
		{
			foreach ($reporte->cronograma->sesiones->whereIn('Fecha', $dias)->all() as $sesion)
			{
				foreach ($sesion->gruposPoblacionales as $grupo)
				{
					if ($sesion['Objetivo_General'] != '')
						$totales_sesiones[$sesion['Objetivo_General']] [$grupo['Id']] [$grupo->pivot['Grupo_Asistencia']] [$grupo->pivot['Genero']] += $grupo->pivot['Cantidad'];
				}
			}
		}

		//return view('idrd.recreovia.reporte-consolidado-profesores', ['fecha' => $fecha, 'jornada' => $jornada, 'reportes' => $reportes, 'totales_sesiones' => $totales_sesiones, 'gruposPoblacionales' => $gruposPoblacionales]);
		//return view('idrd.recreovia.reporte-consolidado-general', ['totales_sesiones' => $totales_sesiones, 'gruposPoblacionales' => $gruposPoblacionales]);

		\Excel::create('Consolidado general', function($excel) use ($fecha, $reportes, $totales_sesiones, $jornada, $gruposPoblacionales)
		{
			$excel->setTitle('Central jornada '.$jornada->toString());

			$excel->sheet('REPORTE', function($sheet) use ($fecha, $jornada, $reportes, $totales_sesiones, $gruposPoblacionales) {
		        $sheet->loadView('idrd.recreovia.reporte-consolidado-profesores', ['fecha' => $fecha, 'jornada' => $jornada, 'reportes' => $reportes, 'totales_sesiones' => $totales_sesiones, 'gruposPoblacionales' => $gruposPoblacionales]);
		    });
		    $excel->sheet('CONSGRAL', function($sheet) use ($totales_sesiones, $gruposPoblacionales) {
		        $sheet->loadView('idrd.recreovia.reporte-consolidado-general', ['totales_sesiones' => $totales_sesiones, 'gruposPoblacionales' => $gruposPoblacionales]);
		    });
    		$excel->getExcel()->setActiveSheetIndex(0);
		})->download('xlsx');
	}
}
