<?php

namespace App\Http\Controllers\Recreovia;

use App\Http\Controllers\Controller;
use App\Modulos\Parques\Localidad;
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
use League\Flysystem\Adapter\Local;

class ReporteController extends Controller {

	protected $usuario;

	public function __construct()
	{
		if (isset($_SESSION['Usuario']))
			$this->usuario = $_SESSION['Usuario'];
	}

	public function jornadas(Request $request)
	{
        $request->flash();
		$puntos = $this->cronogramasPersona($this->usuario['Recreopersona']->Id_Recreopersona);
		$cronogramas = [];

		foreach ($puntos as $punto)
        {
            foreach ($punto->cronogramas as $cronograma)
            {
                if(!in_array($cronograma['Id'], $cronogramas)) $cronogramas[] = $cronograma['Id'];
            }
        }

		if ($request->isMethod('get'))
		{
			$qb = null;
			$elementos = $qb;
		} else {
			$qb = Reporte::with(['punto', 'sesiones', 'profesores.persona', 'cronograma.gestor.persona']);
			$qb = $this->aplicarFiltro($qb, $request);

			$elementos = $qb->whereNull('deleted_at')
							->whereIn('Id_Cronograma', $cronogramas)
                            ->whereHas('punto', function($query){
                                $query->whereNull('deleted_at');
                            })
							->orderBy('Id', 'DESC')
							->get();
		}

		$lista = [
			'titulo' => 'Informes jornadas',
	        'elementos' => $elementos,
            'localidades' => Localidad::all(),
	        'puntos' => $puntos,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Informes jornadas',
			'lista'	=> view('idrd.recreovia.lista-reportes', $lista)
		];

		return view('list', $datos);
	}

	public function jornadas_profesor(Request $request)
	{
		$request->flash();
		$recreopersona = Recreopersona::with(['reportes' => function($query){
                                        $query->with('profesores', 'punto', 'cronograma.sesiones')
                                            ->whereHas('punto', function($query_punto){
                                                $query_punto->whereNull('deleted_at');
                                            })->orderBy('Id', 'DESC');
                                    }])
									->find($this->usuario['Recreopersona']->Id_Recreopersona);

		$puntos = [];

		foreach($recreopersona->reportes as $reporte)
		{
			if(!array_key_exists($reporte->punto['Id_Punto'], $puntos))
			 $puntos[$reporte->punto['Id_Punto']] = $reporte->punto;
		}

		if ($request->isMethod('get'))
		{
			$qb = null;
			$elementos = $qb;
		} else {
			$qb = Recreopersona::with(['reportes' => function($query) use ($request)
								{
								    $query->with('profesores', 'punto', 'cronograma.sesiones');

									$query = $this->aplicarFiltro($query, $request);
									$query->whereNull('Reportes.deleted_at')
                                          ->whereHas('punto', function($query_punto){
                                              $query_punto->whereNull('deleted_at');
                                          })->orderBy('Id', 'DESC');
								}])
								->find($this->usuario['Recreopersona']->Id_Recreopersona);

			$elementos = $qb->reportes;
		}

		$lista = [
			'titulo' => 'Informes jornadas',
	        'elementos' => $elementos,
			'puntos' => $puntos,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Informes jornadas',
			'lista'	=> view('idrd.recreovia.lista-reportes', $lista)
		];

		return view('list', $datos);
	}

	public function obtenerInformes(Request $request)
	{
		$request->flash();

		if ($request->isMethod('get'))
		{
			$qb = null;
			$elementos = $qb;
		} else {
			$qb = Reporte::with(['punto', 'profesores.persona', 'cronograma', 'cronograma.jornada', 'cronograma.sesiones', 'cronograma.gestor.persona'])
                            ->whereHas('cronograma', function($query)
                            {
                                $query->whereNull('deleted_at');
                            });

			$qb = $this->aplicarFiltro($qb, $request);

			$elementos = $qb->whereNull('deleted_at')
			->orderBy('Id', 'DESC')
			->get();
		}

		$lista = [
			'titulo' => 'Aprobar informes de jornadas',
	        'elementos' => $elementos,
			'puntos' => Punto::all(),
            'localidades' => Localidad::all(),
	        'status' => session('status')
		];

        if($elementos) {
            foreach($elementos as $elemento)
            {
                try
                {
                    $data = $elemento->toString();
                } catch (\Exception $e){
                    echo 'error';
                }
            }
        }

		$datos = [
			'seccion' => 'Revisar informes',
			'lista'	=> view('idrd.recreovia.lista-revisar-reportes', $lista)
		];

		return view('list', $datos);
	}

	public function crearInformeJornadas()
	{
		$puntos = $this->cronogramasPersona($this->usuario['Recreopersona']->Id_Recreopersona);

		$gruposPoblacionales = GrupoPoblacional::all();
		$formulario = [
			'titulo' => 'Generar informe de actividades por punto',
	        'puntos' => $puntos,
	        'informe' => null,
	        'sesiones' => null,
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
	    $informe = Reporte::with('profesores', 'sesiones', 'novedad', 'servicios', 'cronograma.gestor.persona')->find($id);
		$puntos = $this->cronogramasPersona($informe->cronograma->gestor['Id_Recreopersona']);
		$gruposPoblacionales = GrupoPoblacional::all();
		$sesiones = $this->obtenerSesionesInforme($informe->sesiones->pluck('Id')->toArray());

		$formulario = [
			'titulo' => 'Editar informe',
	        'informe' => $informe,
	        'puntos' => $puntos,
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
	    $nuevo = $request->input('Id') == '0';

	    if($nuevo)
			$reporte = new Reporte;
        else
            $reporte = Reporte::find($request->input('Id'));

        $sesiones = $this->obtenerSesionesInforme(explode(',', $request->input('sesiones')));

		if($sesiones)
        {
            $profesores = [];
            $dias = [];

            foreach ($sesiones as $sesion)
            {

                if($sesion['Id_Recreopersona'])
                {
                    if($sesion['Asumida_Por_El_Gestor'])
                    {
                        $id_recreopersona = $sesion['Asumida_Por_El_Gestor'];
                    } else {
                        $id_recreopersona = $sesion['Id_Recreopersona'];
                    }

                    if (!in_array($sesion['Fecha'], $dias))
                    {
                        $dias[] = $sesion['Fecha'];
                    }

                    if (array_key_exists($id_recreopersona, $profesores))
                    {
                        $profesores[$id_recreopersona]['Sesiones_Realizadas'] += 1;
                    } else {
                        if ($nuevo)
                        {
                            $profesores[$id_recreopersona] = [
                                'Hora_Llegada' => null,
                                'Hora_Salida' => null,
                                'Sesiones_Realizadas' => 1,
                                'Planificacion' => '',
                                'Sistema_De_Datos' => '',
                                'Novedades' => ''
                            ];
                        } else {
                            $profesores[$id_recreopersona] = [
                                'Sesiones_Realizadas' => 1
                            ];
                        }
                    }
                }
            }
        }

        usort($dias, function($a, $b) {
  		    return strcmp($a, $b);
		});

        $reporte->Id_Punto = $request->input('Id_Punto');
        $reporte->Id_Cronograma = $request->input('Id_Cronograma');
        $reporte->Dias = implode(',', $dias);
        $reporte->Condiciones_Climaticas = null;
        $reporte->Estado = 'Pendiente';
        $reporte->save();

        if ($sesiones) $reporte->sesiones()->sync($sesiones->pluck('Id')->toArray());

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
				$reporte->Estado = $request->has('Estado') ? $request->input('Estado') : $reporte->Estado;
				$reporte->Observaciones = $request->input('Observaciones');
				$reporte->Observaciones_Informe = $request->input('Observaciones_Informe');
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

    public function obtenerCronogramasPunto(Request $request, $id_gestor, $id_punto)
    {
        $puntos = $this->cronogramasPersona($id_gestor);

        return response()->json($puntos->where('Id_Punto', +$id_punto)->first());
    }

    public function obtenerSesionesCronogramas(Request $request, $id_cronograma)
    {
        $cronograma = $this->sesionesCronograma($id_cronograma);

        return response()->json($cronograma);
    }

    public function actualizarEstado(Request $request)
    {
	    $reporte = Reporte::find($request->input('id'));

	    $reporte->Estado = $request->input('estado');
	    $reporte->save();

        return response()->json(true);
    }

	private function obtenerSesionesInforme($sesiones)
	{
		$sesiones = Sesion::with('gruposPoblacionales', 'productoNoConforme', 'calificacionDelServicio', 'profesor.persona', 'cronograma.gestor.persona')
							->whereIn('Id', $sesiones)
							->whereIn('Estado', ['Finalizado', 'Cancelado'])
							->get();

		return $sesiones;
	}

	private function cronogramasPersona($id_recreopersona)
	{
		$recreopersona = Recreopersona::with(['cronogramas' => function($query){
		                                        $query->with(
		                                                [
		                                                    'jornada' => function($query_jornada) {
		                                                        $query_jornada->whereNull('deleted_at');
                                                            },
                                                            'punto' => function($query_punto) {
                                                                $query_punto->whereNull('deleted_at');
                                                            }
                                                        ])->whereHas('sesiones', function($query_sesiones){
                                                                $query_sesiones->with('reportes')
                                                                    ->whereIn('Estado', ['Finalizado', 'Cancelado'])
                                                                    ->whereNull('deleted_at');
                                                        })->whereNull('deleted_at');
											}])->find($id_recreopersona);
		$puntos = collect();

		foreach ($recreopersona->cronogramas as $cronograma)
		{
            if ($cronograma->punto)
            {
                $Id_Punto = $cronograma->punto['Id_Punto'];
                $cronograma->jornada->Label = $cronograma->jornada->toString();
                $cronograma->jornada->Code = $cronograma->jornada->getCode();

                $exists = $puntos->search(function($item, $key) use ($Id_Punto)
                {
                    return $item->Id_Punto == $Id_Punto;
                }, true);

                if (is_bool($exists))
                {
                    $puntos->push($cronograma->punto);
                }
            }
		}

        foreach($puntos as &$punto)
        {
            //strval($punto['Id_Punto']) para que funcione local.
            $punto->cronogramas = array_values($recreopersona->cronogramas->where('Id_Punto', strval($punto['Id_Punto']))->toArray());
		}

		return $puntos;
	}

	private function sesionesCronograma($id_cronograma)
    {
        $cronograma = Cronograma::with(['sesiones' => function($query_sesiones){
                $query_sesiones->with('reportes', 'profesor.persona')
                    ->whereIn('Estado', ['Finalizado', 'Cancelado'])
                    ->whereNull('deleted_at');
            }])->find($id_cronograma);

        return $cronograma;
    }

	private function aplicarFiltro($qb, $request)
	{
		if ($request->input('estado') && $request->input('estado') != 'Todos')
		{
			$qb->where(function($query) use ($request)
			{
				if ($request->input('estado') == 'Pendiente')
				{
                    $query->where('Estado', $request->input('estado'))
								->orWhereNull('Estado');
				} else {
                    $query->where('Estado', $request->input('estado'));
				}
			});
		}

		if ($request->input('localidad') && $request->input('localidad') != 'Todos')
		{
            $localidad = Localidad::with('puntos')->find($request->input('localidad'));

            if ($request->input('punto') && $request->input('punto') != 'Todos')
            {
                $qb->where('Id_Punto', $request->input('punto'));
            } else {
                $qb->whereIn('Id_Punto', $localidad->puntos->pluck('Id_Punto')->toArray());
            }
        } else {
            if ($request->input('punto') && $request->input('punto') != 'Todos')
            {
                $qb->where('Id_Punto', $request->input('punto'));
            }
        }

		if($request->input('fecha_inicio') || $request->input('fecha_fin'))
		{
			$qb->whereHas('sesiones', function($query) use ($request) {
                if ($request->input('fecha_inicio'))
                    $query->where('Fecha', '>=', $request->input('fecha_inicio'));

                if ($request->input('fecha_fin'))
                    $query->where('Fecha', '<=', $request->input('fecha_fin'));
            });
		}

		return $qb;
	}
}
