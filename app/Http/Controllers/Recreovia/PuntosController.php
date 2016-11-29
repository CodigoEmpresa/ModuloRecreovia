<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Controllers\Controller;
use App\Modulos\Recreovia\Punto;
use App\Modulos\Recreovia\Jornada;
use App\Modulos\Parques\Localidad;
use App\Modulos\Parques\Upz;
use App\Http\Requests\GuardarPunto;
use Idrd\Usuarios\Repo\PersonaInterface;
use Idrd\Parques\Repo\LocalizacionInterface;
use Validator;
use Illuminate\Http\Request;

class PuntosController extends Controller {
	
	protected $repositorio_personas;

	public function __construct(PersonaInterface $repositorio_personas)
	{
		$this->repositorio_personas = $repositorio_personas;
	}

	public function index()
	{
		$perPage = config('app.page_size');
		$elementos = Punto::with('localidad', 'upz')
							->whereNull('deleted_at')
							->orderBy('Cod_IDRD', 'ASC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Puntos',
	        'elementos' => $elementos,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Puntos',
			'lista'	=> view('idrd.recreovia.lista-puntos', $lista)
		];

		return view('list', $datos);
	}

	public function crear()
	{
		$formulario = [
			'titulo' => 'Crear ó editar puntos',
	        'punto' => null,
	        'localidades' => Localidad::all(),
	        'jornadas' => Jornada::whereNull('deleted_at')->get(),
	        'upz' => Upz::all(),
	        'status' => session('status')
	    ];

	    $datos = [
			'seccion' => 'Puntos',
			'formulario' => view('idrd.recreovia.formulario-puntos', $formulario)
		];

		return view('form', $datos);
	}

	public function editar(Request $request, $id)
	{
		$punto = Punto::with(['jornadas' => function($query)
		{
			return $query->whereNull('deleted_at');
		}])->find($id);

		$formulario = [
			'titulo' => 'Crear ó editar puntos',
	        'punto' => $punto,
	        'localidades' => Localidad::all(),
	        'jornadas' => Jornada::whereNull('deleted_at')->get(),
	        'upz' => Upz::all(),
	        'status' => session('status')
	    ];

	    $datos = [
			'seccion' => 'Puntos',
			'formulario' => view('idrd.recreovia.formulario-puntos', $formulario)
		];

		return view('form', $datos);
	}

	public function buscar(Request $request, $key)
	{
		$puntos = Punto::with('localidad', 'upz')
						->where('escenario', 'LIKE', '%'.$key.'%')
						->get();

		return response()->json($puntos);
	}

	public function obtener(Request $request, $id)
	{
		$punto = Punto::with(['jornadas' => function($query)
		{
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

		$punto->Direccion = $request->input('Direccion');
		$punto->Escenario = $request->input('Escenario');
		$punto->Cod_IDRD = $request->input('Cod_IDRD');
		$punto->Cod_Recreovia = $request->input('Cod_Recreovia');
		$punto->Id_Localidad = $request->input('Id_Localidad');
		$punto->Id_Upz = $request->input('Id_Upz');
		$punto->Latitud = $request->input('Latitud');
		$punto->Longitud = $request->input('Longitud');
		$punto->save();

		$jornadas = $request->input('Jornadas');
		$jornadas = rtrim($jornadas, ',');

		$punto->jornadas()->sync(explode(',', $jornadas));

       	return redirect('/puntos/editar/'.$punto['Id_Punto'])->with(['status' => 'success']);
	}

	public function eliminar(Request $request, $id)
	{
		$punto = Punto::where('Id_Punto', $id)
						->first();
		$punto->delete();
		return redirect('/puntos')->with(['status' => 'success']); 
	}

	/*private function sincronizarJornadas($jornadas, $punto)
	{
		$jornadas = json_decode($jornadas);
		$jornadas_ids = [];

		if(is_array($jornadas))
		{
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
				$jornada->Tipo = $j->Tipo;
				$jornada->Fecha_Evento_Inicio = $j->Fecha_Evento_Inicio;
				$jornada->Fecha_Evento_Fin = $j->Fecha_Evento_Fin;

				$jornada->save();
			}
		}
	}*/

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