<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Requests\GuardarJornada;
use App\Http\Controllers\Controller;
use App\Modulos\Recreovia\Jornada;
use Illuminate\Http\Request;

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

	public function crear()
	{
		$formulario = [
			'titulo' => 'Crear ó editar jornadas',
			'jornada' => null,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Jornadas',
			'formulario' => view('idrd.recreovia.formulario-jornadas', $formulario)
		];

		return view('form', $datos);
	}

	public function editar(Request $request, $id)
	{
		$formulario = [
			'titulo' => 'Crear ó editar jornadas',
			'jornada' => Jornada::find($id),
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Jornadas',
			'formulario' => view('idrd.recreovia.formulario-jornadas', $formulario)
		];

		return view('form', $datos);
	}

	public function procesar(GuardarJornada $request)
	{
		if ($request['Id_Jornada'] == 0)
			$jornada = $this->crearJornada($request);
		else 
			$jornada = $this->editarJornada($request);

		$jornada->Jornada = $request['Jornada'];
		$jornada->Dias = implode(',', $request['Dias']);
		$jornada->Fecha_Evento_Inicio = $request['Fecha_Evento_Inicio'];
		$jornada->Fecha_Evento_Fin = $request['Fecha_Evento_Fin'];
		$jornada->Inicio = $request['Inicio'];
		$jornada->Fin = $request['Fin'];
		$jornada->Tipo = $request['Tipo'];
		$jornada->save();

       	return redirect('/jornadas/editar/'.$jornada['Id_Jornada'])->with(['status' => 'success']);
	}

	private function crearJornada($request)
	{
		$jornada = new Jornada;
		return $jornada;
	}

	private function editarJornada($request)
	{
		$jornada = Jornada::find($request->Id_Jornada);
		return $jornada;
	}
}