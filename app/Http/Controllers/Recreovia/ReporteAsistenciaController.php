<?php

namespace App\Http\Controllers\Recreovia;

use App\Modulos\Parques\Localidad;
use App\Modulos\Recreovia\Jornada;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReporteAsistenciaController extends Controller
{
    public function index() {
        $data = [
            'localidades' => Localidad::with('upz.puntos')->get(),
            'jornadas' => Jornada::all(),
            'seccion' => 'Reporte asistencia y participación'
        ];

        return view('idrd.recreovia.reporte-asistencia', $data);
    }
}
