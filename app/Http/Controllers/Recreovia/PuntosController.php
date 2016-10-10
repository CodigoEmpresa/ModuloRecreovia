<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modulos\Recreovia\Punto;
use App\Modulos\Parques\Localidad;
use App\Modulos\Parques\Upz;
use App\Http\Requests\GuardarProfesor;
use Idrd\Usuarios\Repo\PersonaInterface;
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
		$elementos = Puntow::with(['zona' => function($query){
								return $query->orderBy('Id_Zona');
							}])
							->orderBy('Cod_IDRD', 'ASC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Puntos',
	        'elementos' => $elementos,
	        'documentos' => Documento::all(),
	        'paises' => Pais::all(),
	        'etnias' => Etnia::all(),
	        'zonas' => Zona::all()
		];

		$datos = [
			'seccion' => 'Puntos',
			'lista'	=> view('idrd.recreovia.lista-profesores', $lista)
		];

		return view('list', $datos);
	}

	public function buscar(Request $request, $key)
	{
		$resultados = $this->repositorio_personas->buscar($key);
		$profesores = Persona::with('zonas', 'tipoDocumento')
							->whereIn('Id_Persona', $resultados->lists('Id_Persona'))
							->get();

		return response()->json($profesores);
	}

	public function obtener(Request $request, $id)
	{
		$persona = $this->repositorio_personas->obtener($id);	
		$profesor = Persona::with('zonas', 'tipoDocumento')
						->where('Id_Persona', $persona->Id_Persona)
						->first();

		return response()->json($profesor);
	}

	public function procesar(GuardarProfesor $request)
	{
        if ($request->input('Id_Persona') == '0')
        	$profesor = $this->repositorio_personas->guardar($request->all());
        else
        	$profesor = $this->repositorio_personas->actualizar($request->all());

        $zona = Zona::with('personas')->find($request->input('Id_Zona'));
        $personas = [];
        $profesor->zonas()->detach();

        foreach ($zona->personas as $persona) 
        {
        	echo $persona->Id_Persona.' != '.$profesor->Id_Persona;

        	if ($persona->Id_Persona != $profesor->Id_Persona)
	        	$personas[$persona->Id_Persona] = [
	        		'tipo' => $persona->pivot['tipo']
	        	];
        }

        $personas[$profesor->Id_Persona] = [
        	'tipo' => $request->input('tipo')
        ];

        $zona->personas()->sync($personas);

        return response()->json(array('status' => 'ok'));
	}
}