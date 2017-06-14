<?php

namespace App\Http\Controllers\Recreovia;

use App\Http\Controllers\Controller;
use App\Modulos\Parques\Localidad;
use App\Modulos\Recreovia\Punto;
use Illuminate\Http\Request;

class BuscadorController extends Controller
{
    public function index()
    {
        $data = [
            'localidades' => Localidad::all()
        ];

        return view('idrd.buscador.index', $data);
    }

    public function buscar(Request $request)
    {
        $localidad = $request->input('Id_Localidad', "");
        $fecha = $request->input('Fecha', date('Y-m-d'));

        $qb = Punto::whereHas('sesiones', function($query) use ($fecha) {
                $query->where('Fecha', $fecha)
                    ->where('Estado', 'Aprobado');
            });

        if($localidad == "")
            $qb->where('Id_Localidad', '<>', '0');
        else
            $qb->where('Id_Localidad', $localidad);

        $puntos = $qb->get();

        $puntos->load(['cronogramas.sesiones' => function($query) use ($fecha) {
            $query->where('Fecha', $fecha)
                ->where('Estado', 'Aprobado');
            }
        ]);

        return response()->json($puntos);
    }
}