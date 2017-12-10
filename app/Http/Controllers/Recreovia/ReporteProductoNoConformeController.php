<?php

namespace App\Http\Controllers\Recreovia;

use App\Modulos\Parques\Localidad;
use App\Modulos\Recreovia\GrupoPoblacional;
use App\Modulos\Recreovia\Jornada;
use App\Http\Controllers\Controller;
use App\Modulos\Recreovia\Reporte;
use App\Modulos\Recreovia\Sesion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReporteProductoNoConformeController extends Controller
{
    public function index(Request $request)
    {
        $request->flash();

        if($request->isMethod('get')) {
            $sesiones = null;
        } else {

            $qb = Sesion::with('productoNoConforme', 'profesor.persona', 'gestorSiAsume.persona');

            $qb = $this->aplicarFiltros($qb, $request);

            $elementos = $qb->where('Estado', 'Finalizado')
                ->whereNull('deleted_at')
                ->orderBy('Id', 'DESC')
                ->get();

            $sesiones = collect([]);

            foreach ($elementos as $sesion) {
                $exists = $sesiones->search(function ($item, $key) use ($sesion) {
                    return $item->Id == $sesion->Id;
                }, true);

                if (is_bool($exists)) {
                    if ($request->has('no_conformidad')) {
                        $pass = false;
                        foreach ($request->input('no_conformidad') as $no_conformidad) {
                            if ($sesion->productoNoConforme[$no_conformidad] == '0') {
                                $pass = true;
                            }
                        }

                        if ($pass) {
                            $sesiones->push($sesion);
                        }
                    } else {
                        $sesiones->push($sesion);
                    }
                }
            }
        }

        //exit();
        //dd($sesiones);

        $data = [
            'localidades' => Localidad::with('upz.puntos')->get(),
            'jornadas' => Jornada::all(),
            'sesiones' => $sesiones,
            'seccion' => 'Reporte producto no conforme'
        ];

        return view('idrd.recreovia.reporte-producto-no-conforme', $data);
    }

    private function aplicarFiltros(Builder $qb, $request) {
        if($request->has('id_jornada'))
        {
            $qb->whereHas('cronograma', function($query) use ($request) {
                $query->whereIn('Id_Jornada', $request->input('id_jornada'));
            });
        }

        if($request->has('id_localidad'))
        {
            $qb->whereHas('cronograma.punto', function($query) use ($request) {
                $query->whereIn('Id_Localidad', $request->input('id_localidad'));
            });
        }

        if($request->has('id_upz'))
        {
            $qb->whereHas('cronograma.punto', function($query) use ($request) {
                $query->whereIn('Id_Upz', $request->input('id_upz'));
            });
        }

        if($request->has('id_punto'))
        {
            $qb->whereHas('cronograma.punto', function($query) use ($request) {
                $query->whereIn('Id_Punto', $request->input('id_punto'));
            });
        }

        if($request->input('fecha_inicio') || $request->input('fecha_fin'))
        {
            if ($request->input('fecha_inicio'))
                $qb->where('Fecha', '>=', $request->input('fecha_inicio'));

            if ($request->input('fecha_fin'))
                $qb->where('Fecha', '<=', $request->input('fecha_fin'));
        }

        return $qb;
    }
}
