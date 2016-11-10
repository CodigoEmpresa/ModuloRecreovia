<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modulos\Personas\Persona;
use App\Modulos\Personas\Documento;
use App\Modulos\Personas\Pais;
use App\Modulos\Personas\Etnia;
use App\Modulos\Parques\Localidad;
use App\Modulos\Recreovia\Recreopersona;
use App\Http\Requests\GuardarProfesor;
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
		$elementos = Persona::has('recreopersona')
							->orderBy('Cedula', 'ASC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Profesores',
	        'elementos' => $elementos,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Profesores',
			'lista'	=> view('idrd.recreovia.lista-recreopersonas', $lista)
		];

		return view('list', $datos);
	}

	public function crear()
	{
		$formulario = [
			'titulo' => 'Crear ó editar profesores y gestores',
			'persona' => null,
			'documentos' => Documento::all(),
	        'paises' => Pais::all(),
	        'etnias' => Etnia::all(),
	        'localidades' => Localidad::all(),
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Profesores',
			'formulario' => view('idrd.recreovia.formulario-recreopersonas', $formulario)
		];

		return view('form', $datos);
	}

	public function editar(Request $request, $id)
	{
		$persona = $this->repositorio_personas->obtener($id);
		$profesor = Persona::with('recreopersona', 'tipoDocumento')
						->where('Id_Persona', $persona->Id_Persona)
						->first();

		$formulario = [
			'titulo' => 'Crear ó editar profesores y gestores',
			'persona' => $profesor,
			'documentos' => Documento::all(),
	        'paises' => Pais::all(),
	        'etnias' => Etnia::all(),
	        'localidades' => Localidad::all(),
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Profesores',
			'formulario' => view('idrd.recreovia.formulario-recreopersonas', $formulario)
		];

		return view('form', $datos);
	}

	public function buscar(Request $request, $key)
	{
		$resultados = $this->repositorio_personas->buscar($key);
		$profesores = Persona::with('recreopersona', 'tipoDocumento')
							->whereIn('Id_Persona', $resultados->lists('Id_Persona'))
							->get();

		return response()->json($profesores);
	}

	public function obtener(Request $request, $id)
	{
		$persona = $this->repositorio_personas->obtener($id);
		$profesor = Persona::with('recreopersona', 'tipoDocumento')
						->where('Id_Persona', $persona->Id_Persona)
						->first();

		return response()->json($profesor);
	}

	public function eliminar(Request $request, $id)
	{
		$profesor = Persona::with('recreopersona', 'tipoDocumento')
						->where('Id_Persona', $persona->Id_Persona)
						->first();
		$profesor->recreopersona()->delete();

		return redirect('/profesores')->with(['status' => 'success']); 
	}

	public function procesar(GuardarProfesor $request)
	{
        if ($request->input('Id_Persona') == '0')
        	$persona = $this->repositorio_personas->guardar($request->all());
        else
        	$persona = $this->repositorio_personas->actualizar($request->all());

        $profesor = Persona::with('recreopersona', 'tipoDocumento')
			->where('Id_Persona', $persona->Id_Persona)
			->first();

		$recreopersona = Recreopersona::withTrashed()
								->where('Id_Persona', $persona->Id_Persona);
								->first();

		if ($profesor->recreopersona)
		{			
			$profesor->recreopersona()->update([
				'tipo' => $request->input('tipo')
			]);
		} else {
			$recreopersona = new Recreopersona([
				'tipo' => $request->input('tipo')
			]);

			$profesor->recreopersona()->save($recreopersona);
		}

        $persona[$profesor->Id_Persona] = [
        	'tipo' => $request->input('tipo'),
        	'Id_Localidad' => $request->input('Id_Localidad')
        ];

        return redirect('/profesores/editar/'.$persona['Id_Persona'])->with(['status' => 'success']);
	}
}