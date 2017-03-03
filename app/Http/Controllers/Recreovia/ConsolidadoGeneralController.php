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
		/*
		$id_jornada = $request->input('Id_Jornada');
		$reportes = Reporte::with('cronograma', 'cronograma.jornada', 'cronograma.sesiones', 'cronograma.sesiones.gruposPoblacionales')
								->whereHas('cronograma', function($query) use ($id_jornada)
								{
									$query->where('Id_Jornada', $id_jornada);
								})
								->get();
		$jornada = Jornada::find($id_jornada);
		$gruposPoblacionales = GrupoPoblacional::all();

		return view('idrd.recreovia.reporte-consolidado-general', ['reportes' => $reportes, 'gruposPoblacionales' => $gruposPoblacionales]);*/

		
		\Excel::create('Consolidado general', function($excel) use ($request)
		{
			$id_jornada = $request->input('Id_Jornada');
			$reportes = Reporte::with('cronograma', 'cronograma.jornada', 'cronograma.sesiones', 'cronograma.sesiones.gruposPoblacionales')
									->whereHas('cronograma', function($query) use ($id_jornada)
									{
										$query->where('Id_Jornada', $id_jornada);
									})
									->get();
			$jornada = Jornada::find($id_jornada);
			$gruposPoblacionales = GrupoPoblacional::all();

			$excel->setTitle('Central jornada '.$jornada->toString());

			$excel->sheet('CONSGRAL', function($sheet) use ($reportes, $gruposPoblacionales) {
		        $sheet->loadView('idrd.recreovia.reporte-consolidado-general', ['reportes' => $reportes, 'gruposPoblacionales' => $gruposPoblacionales]);
		    });
		})->download('xlsx');
	}
}