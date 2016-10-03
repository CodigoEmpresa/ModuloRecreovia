<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modulos\Recreovia\Zona as Zona;

class ProfesoresController extends Controller {
	
	public function index()
	{
		$perPage = config('app.page_size');

		$elementos = Persona::with('zonas')
							->orderBy('Cedula', 'ASC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Profesores',
	        'elementos' => $elementos
		];

		$datos = [
			'seccion' => 'Profesores',
			'lista'	=> view('idrd.recreovia.lista-profesores', $lista)
		];

		return view('list', $datos);
	}
}