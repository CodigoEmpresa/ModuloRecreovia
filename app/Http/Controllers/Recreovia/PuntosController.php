<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modulos\Recreovia\Punto;
use App\Modulos\Recreovia\Jornada;
use App\Modulos\Parques\Localidad;
use App\Modulos\Parques\Upz;
use App\Modulos\Recreovia\Zona;
use App\Http\Requests\GuardarPunto;
use Idrd\Usuarios\Repo\PersonaInterface;
use Idrd\Parques\Repo\LocalizacionInterface;
use Validator;

class PuntosController extends Controller {
	
	protected $repositorio_personas;

	public function __construct(PersonaInterface $repositorio_personas)
	{
		$this->repositorio_personas = $repositorio_personas;
	}

	public function index()
	{
		$perPage = config('app.page_size');
		$elementos = Punto::with(['zona' => function($query){
								return $query->orderBy('Id_Zona');
							}, 'localidad', 'upz'])
							->orderBy('Cod_IDRD', 'ASC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Puntos',
	        'elementos' => $elementos,
	        'localidades' => Localidad::all(),
	        'upz' => Upz::all(),
	        'zonas' => Zona::all()
		];

		$datos = [
			'seccion' => 'Puntos',
			'lista'	=> view('idrd.recreovia.lista-puntos', $lista)
		];

		return view('list', $datos);
	}

	public function buscar(Request $request, $key)
	{
		return response()->json([true]);
	}

	public function obtener(Request $request, $id)
	{
		$punto = Punto::with(['jornadas' => function($query){
			return $query->whereNull('deleted_at');
		}])->find($id);
		return response()->json($punto);
	}

	public function procesar(GuardarPunto $request)
	{
		if ($request['Id_Punto'] == 0)
			$punto = $this->crearPunto($request);
		else 
			$punto = $this->editarPunto($request);

		$punto->Direccion = $request['Direccion'];
		$punto->Escenario = $request['Escenario'];
		$punto->Cod_IDRD = $request['Cod_IDRD'];
		$punto->Cod_Recreovia = $request['Cod_Recreovia'];
		$punto->Id_Zona = $request['Id_Zona'];
		$punto->Id_Localidad = $request['Id_Localidad'];
		$punto->Id_Upz = $request['Id_Upz'];
		$punto->save();

		$this->sincronizarJornadas($request['Jornadas'], $punto);

        return response()->json(['status' => 'ok']);
	}

	private function sincronizarJornadas($jornadas, $punto)
	{
		$jornadas = json_decode($jornadas);
		$jornadas_ids = [];

		foreach ($jornadas as $j) 
		{
			if ($j->Id_Jornada != 0)
				$jornadas_ids[] = $j->Id_Jornada;
		}
		
		$jornadas_eliminadas = Jornada::where('Id_Punto', $punto['Id_Punto'])
				->whereNotIn('Id_Jornada', $jornadas_ids)
				->get();

		foreach ($jornadas_eliminadas as $j) 
		{
			$j->delete();
		}

		foreach ($jornadas as $j) 
		{
			if ($j->Id_Jornada != 0)
			{
				$jornada = Jornada::find($j->Id_Jornada);
			} else {
				$jornada = new Jornada;
			}

			$jornada->Id_Punto = $punto['Id_Punto'];
			$jornada->Jornada = $j->Jornada;
			$jornada->Dias = $j->Dias;
			$jornada->Inicio = $j->Inicio;
			$jornada->Fin = $j->Fin;

			$jornada->save();
		}
	}

	private function crearPunto($request)
	{
		$punto = new Punto;
		return $punto;
	}

	private function editarPunto($request)
	{
		$punto = Punto::find($request->Id_Punto);
		return $punto;
	}
}