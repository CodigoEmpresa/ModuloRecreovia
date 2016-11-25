<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Requests;
use App\Http\Requests\AgregarPersonalLocalidad;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modulos\Parques\Jornada;

class JornadaController extends Controller {
	
	protected $usuario;

	public function __construct()
	{
		if (isset($_SESSION['Usuario']))
			$this->usuario = $_SESSION['Usuario'];
	}

	public function index()
	{
		$perPage = config('app.page_size');
		$elementos = Jornada::whereNull('deleted_at')
							->orderBy('Id_Jornada', 'ASC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Jornadas',
	        'elementos' => $elementos,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Puntos',
			'lista'	=> view('idrd.recreovia.lista-jornadas', $lista)
		];

		return view('list', $datos);
	}
}