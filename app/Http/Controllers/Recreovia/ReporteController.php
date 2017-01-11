<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modulos\Recreovia\Reporte;
use App\Modulos\Recreovia\Recreopersona;
use App\Modulos\Recreovia\Sesion;
use App\Modulos\Recreovia\Punto;
use App\Modulos\Recreovia\Cronograma;
use App\Modulos\Recreovia\GrupoPoblacional;
use App\Modulos\Recreovia\Novedad;
use App\Modulos\Recreovia\Servicio;
use App\Http\Requests\GenerarInforme;

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
		$elementos = Reporte::with(['profesores', 'cronograma', 'cronograma.sesiones'])
							->whereNull('deleted_at')
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
		$recreopersona = $this->cronogramasPersonas();
		$gruposPoblacionales = GrupoPoblacional::all();

		$formulario = [
			'titulo' => 'Generar informe de actividades por punto',
	        'informe' => null,
	        'puntos' => $recreopersona->puntos,
	        'sesiones' => $sesiones,
	        'gruposPoblacionales' => $gruposPoblacionales,
	        'status' => session('status')
	    ];

	    $datos = [
			'seccion' => 'Generar informe de actividades por punto',
			'formulario' => view('idrd.recreovia.formulario-reporte-jornadas', $formulario)
		];

		return view('form', $datos);
	}

	public function editarInformeJornadas(Request $request, $id)
	{
		$informe = Reporte::with('profesores', 'novedad', 'servicios')->find($id);
		$recreopersona = $this->cronogramasPersonas();
		$gruposPoblacionales = GrupoPoblacional::all();

		$sesiones = Sesion::with('gruposPoblacionales', 'profesor', 'profesor.persona')
							->where('Id_Cronograma', $informe['Id_Cronograma'])
							->where('Fecha', $informe['Dia'])
							->where('Estado', 'Aprobado')
							->get();

		$formulario = [
			'titulo' => 'Editar informe',
	        'informe' => $informe,
	        'puntos' => $recreopersona->puntos,
	        'sesiones' => $sesiones,
	        'gruposPoblacionales' => $gruposPoblacionales,
	        'status' => session('status')
	    ];

	    $datos = [
			'seccion' => 'Generar informe de actividades por punto',
			'formulario' => view('idrd.recreovia.formulario-reporte-jornadas', $formulario)
		];

		return view('form', $datos);
	}

	public function generarInformeJornadas(GenerarInforme $request)
	{
		if($request->input('Id') == '0')
			$reporte = new Reporte;
		else
			$reporte = Reporte::find($request->input('Id'));

		$reporte->Id_Punto = $request->input('Id_Punto');
		$reporte->Id_Cronograma = $request->input('Id_Cronograma');
		$reporte->Dia = $request->input('Dia');
		$reporte->Condiciones_Climaticas = null;
		$reporte->save();

		$sesiones = Sesion::with('gruposPoblacionales')
							->where('Id_Cronograma', $request->input('Id_Cronograma'))
							->where('Fecha', $request->input('Dia'))
							->where('Estado', 'Aprobado')
							->get();

		if ($sesiones)
		{
			$profesores = [];
			//sincronizar profesores
			foreach ($sesiones as $sesion) 
			{
				$profesores[$sesion['Id_Recreopersona']] = [
					'Hora_Llegada' => null,
					'Hora_Salida' => null,
					'Sesiones_Realizadas' => count($sesiones->where('Id_Recreopersona', $sesion['Id_Recreopersona'])->all()),
					'Planificacion' => '',
					'Sistema_De_Datos' => '',
					'Novedades' => ''
				];
			}
		}

		$reporte->profesores()->sync($profesores);

		return redirect('/informes/jornadas/'.$reporte->Id.'/editar')->with(['status' => 'success']);
	}

	public function eliminarInformeJornadas(Request $request, $id)
	{
		$reporte = Reporte::find($id);
		$reporte->delete();

		return redirect('/informes/jornadas')->with(['status' => 'success']);
	}

	public function actualizarInformeJornadas(Request $request)
	{
		$reporte = Reporte::with('profesores', 'novedad', 'servicios')->find($request->input('Id'));

		switch ($request->input('Area')) {
			case 'datos_generales':
				$reporte->Condiciones_Climaticas = $request->has('Condiciones_Climaticas') ? $request->input('Condiciones_Climaticas') : null;
			break;
			case 'informacion_profesores_de_actividad_fisica':
				$profesores = [];
				foreach ($reporte->profesores as $profesor) 
				{
					$profesores[$profesor['Id_Recreopersona']] = [
						'Hora_Llegada' => $request->input('Hora_Llegada_'.$profesor['Id_Recreopersona']) ? $request->input('Hora_Llegada_'.$profesor['Id_Recreopersona']) : null,
						'Hora_Salida' => $request->input('Hora_Salida_'.$profesor['Id_Recreopersona']) ? $request->input('Hora_Salida_'.$profesor['Id_Recreopersona']) : null,
						'Sesiones_Realizadas' => $profesor->pivot['Sesiones_Realizadas'],
						'Planificacion' => $request->has('Planificacion_'.$profesor['Id_Recreopersona']) ? '1' : '0',
						'Sistema_De_Datos' => $request->has('Sistema_De_Datos_'.$profesor['Id_Recreopersona']) ? '1' : '0',
						'Novedades' => $request->input('Novedades_'.$profesor['Id_Recreopersona'])
					];
				}

				$reporte->profesores()->sync($profesores);
			break;
			case 'novedades_especiales':
				//novedad
				if ($reporte->novedad)
					$novedad = $reporte->novedad;
				else
					$novedad = new Novedad;

				$novedad->Id_Reporte = $request->input('Id');
				$novedad->Cod_514_523 = $request->input('Cod_514_523');
				$novedad->Cod_514_541 = $request->input('Cod_514_541');
				$novedad->Cod_514_542 = $request->input('Cod_514_542');
				$novedad->Novedades = $request->input('Novedades');
				$novedad->save();
				
				//servicios
				$reporte->servicios()->delete();
				$servicios = $request->input('Total_Servicios');
				
				for ($i = 0; $i < $servicios; $i++)
				{
					$servicio = new Servicio;
					$servicio->Id_Reporte = $request->input('Id');
					$servicio->Cod_514_523 = $request->input('Cod_514_523_'.$i);
					$servicio->Cod_514_541 = $request->input('Cod_514_541_'.$i);
					$servicio->Cod_514_542 = $request->input('Cod_514_542_'.$i);
					$servicio->tipo = $request->input('tipo_'.$i);
					$servicio->Empresa = $request->input('Empresa_'.$i);
					$servicio->Placa_Camion = $request->input('Placa_Camion_'.$i);
					$servicio->Operarios = $request->input('Operarios_'.$i);
					$servicio->Observaciones_Generales = $request->input('Observaciones_Generales_'.$i);

					$servicio->save();
				}	
			break;
		}
		$reporte->save();

		return response()->json([true]);
	}

	private function cronogramasPersonas()
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

		return $recreopersona;
	}
}