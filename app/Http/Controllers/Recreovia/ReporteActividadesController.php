<?php

namespace App\Http\Controllers\Recreovia;


use App\Modulos\Parques\Localidad;
use App\Modulos\Recreovia\GrupoPoblacional;
use App\Modulos\Recreovia\Jornada;
use App\Http\Controllers\Controller;
use App\Modulos\Recreovia\Reporte;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReporteActividadesController extends Controller
{
    public function index(Request $request)
    {
        $request->flash();

        if($request->isMethod('get')) {
            $puntos = null;
        } else {
            $qb = Reporte::with(['cronograma', 'punto', 'sesiones' => function($query) use ($request) {
                $query->with(['gruposPoblacionales', 'cronograma' => function($cronograma) {
                    $cronograma->with('jornada', 'punto');
                }]);

                if ($request->has('sesion')) {
                    $query->whereIn('Objetivo_General', $request->input('sesion'));
                }
            }]);

            $qb = $this->aplicarFiltros($qb, $request);

            $elementos = $qb->where('Estado', 'Finalizado')
                ->whereNull('deleted_at')
                ->orderBy('Id', 'DESC')
                ->get();

            $sesiones = collect([]);
            $puntos = [];

            foreach ($elementos as $reporte)
            {
                foreach ($reporte->sesiones as $sesion)
                {
                    $exists = $sesiones->search(function($item, $key) use ($sesion)
                    {
                        return $item->Id == $sesion->Id;
                    }, true);

                    if (is_bool($exists))
                        $sesiones->push($sesion);
                }
            }

            foreach ($sesiones as $sesion)
            {

                if (!array_key_exists($sesion->cronograma->punto['Id_Punto'], $puntos))
                {
                    $puntos[$sesion->cronograma->punto['Id_Punto']] = [
                        'punto' => $sesion->cronograma->punto,
                        'jornadas' => []
                    ];
                }

                if (array_key_exists($sesion->cronograma->punto['Id_Punto'], $puntos))
                {
                    if (!array_key_exists($sesion->cronograma->jornada['Id_Jornada'], $puntos[$sesion->cronograma->punto['Id_Punto']]['jornadas']))
                    {
                        $puntos[$sesion->cronograma->punto['Id_Punto']]['jornadas'][$sesion->cronograma->jornada['Id_Jornada']] = [
                            'jornada' => $sesion->cronograma->jornada,
                            'sesiones' => [
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
                            ]
                        ];
                    }

                    if (array_key_exists($sesion->cronograma->jornada['Id_Jornada'], $puntos[$sesion->cronograma->punto['Id_Punto']]['jornadas']))
                    {
                        $puntos[$sesion->cronograma->punto['Id_Punto']]['jornadas'][$sesion->cronograma->jornada['Id_Jornada']]['sesiones'][$sesion->Objetivo_General][] = $sesion;
                    }
                }
            }
        }

        //dd($puntos);

        $data = [
            'localidades' => Localidad::with('upz.puntos')->get(),
            'jornadas' => Jornada::all(),
            'gruposPoblacionales' => GrupoPoblacional::all(),
            'seccion' => 'Reporte de actividades',
            'puntos' => $puntos
        ];

        return view('idrd.recreovia.reporte-actividades', $data);
    }

    private function aplicarFiltros(Builder $qb, $request) {
        if($request->has('sesion'))
        {
            $qb->whereHas('sesiones', function($query) use ($request) {
                $query->whereIn('Objetivo_General', $request->input('sesion'));
            });
        }

        if($request->has('id_jornada'))
        {
            $qb->whereHas('cronograma', function($query) use ($request) {
                $query->whereIn('Id_Jornada', $request->input('id_jornada'));
            });
        }

        if($request->has('id_localidad'))
        {
            $qb->whereHas('punto', function($query) use ($request) {
                $query->whereIn('Id_Localidad', $request->input('id_localidad'));
            });
        }

        if($request->has('id_upz'))
        {
            $qb->whereHas('punto', function($query) use ($request) {
                $query->whereIn('Id_Upz', $request->input('id_upz'));
            });
        }

        if($request->has('id_punto'))
        {
            $qb->whereHas('punto', function($query) use ($request) {
                $query->whereIn('Id_Punto', $request->input('id_punto'));
            });
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
