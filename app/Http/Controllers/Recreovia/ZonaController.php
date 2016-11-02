<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modulos\Recreovia\Zona as Zona;

class ZonaController extends Controller {
	
	public function index()
	{
		$perPage = config('app.page_size');
		
		$elementos = Zona::with('personas', 'puntos')
							->orderBy('Id_Zona', 'ASC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Configurar zonas',
	        'elementos' => $elementos
		];

		$datos = [
			'seccion' => 'Zonas',
			'lista'	=> view('idrd.recreovia.lista-zonas', $lista)
		];

		return view('list', $datos);
	}
}