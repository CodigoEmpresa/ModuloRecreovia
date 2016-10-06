<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modulos\Recreovia\Zona as Zona;
use App\Modulos\Personas\Persona as Persona;
use App\Modulos\Personas\Documento as Documento;
use App\Modulos\Personas\Pais as Pais;
use App\Modulos\Personas\Etnia as Etnia;
use Idrd\Usuarios\Repo\PersonaInterface;
use Validator;

class ProfesoresController extends Controller {
	
	protected $repositorio_personas;

	public function __construct(PersonaInterface $repositorio_personas)
	{
		$this->repositorio_personas = $repositorio_personas;
	}

	public function index()
	{
		$perPage = config('app.page_size');
		$elementos = Persona::has('zonas')->with('zonas')
							->orderBy('Cedula', 'ASC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Profesores',
	        'elementos' => $elementos,
	        'documentos' => Documento::all(),
	        'paises' => Pais::all(),
	        'etnias' => Etnia::all(),
	        'zonas' => Zona::all()
		];

		$datos = [
			'seccion' => 'Profesores',
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

	public function procesar(Request $request)
	{
		$validator = Validator::make($request->all(),
			[
	            'Id_TipoDocumento' => 'required|min:1',
				'Cedula' => 'required|numeric',
				'Primer_Apellido' => 'required',
				'Primer_Nombre' => 'required',
				'Fecha_Nacimiento' => 'required|date',
				'Id_Etnia' => 'required|min:1',
				'Id_Pais' => 'required|min:1',
				'Id_Genero' => 'required|in:1,2',
				'Id_Zona' => 'required',
				'tipo' => 'required|in:profesor,gestor'
        	]
        );

        if ($validator->fails())
            return response()->json(array('status' => 'error', 'errors' => $validator->errors()));
        
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