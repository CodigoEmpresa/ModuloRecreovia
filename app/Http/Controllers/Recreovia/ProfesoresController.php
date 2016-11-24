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

	public function buscar(Request $request, $key, $strict=null)
	{
		$resultados = $this->repositorio_personas->buscar($key);
		
		if(!$strict)
		{
			$profesores = Persona::with('recreopersona', 'tipoDocumento')
								->whereIn('Id_Persona', $resultados->lists('Id_Persona'))
								->get();
		} else {
			$profesores = Persona::with('recreopersona', 'tipoDocumento')
								->whereHas('recreopersona', function($query) use ($resultados)
								{
									$query->whereIn('Id_Persona', $resultados->lists('Id_Persona'));
								})
								->get();
		}


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
		$recreopersona = Recreopersona::where('Id_Persona', $id)
						->first();

		$recreopersona->delete();

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

		$recreopersona = Recreopersona::where('Id_Persona', $persona->Id_Persona)
								->first();

		if ($recreopersona)
		{
			if($recreopersona->trashed())
				$recreopersona->restore();

			$recreopersona->correo = $request->input('correo');
			$recreopersona->contrato = $request->input('contrato');
			$recreopersona->save();
		} else {
			$recreopersona = new Recreopersona;
			$recreopersona->correo = $request->input('correo');
			$recreopersona->contrato = $request->input('contrato');

			$profesor->recreopersona()->save($recreopersona);
		}

        return redirect('/profesores/editar/'.$persona['Id_Persona'])->with(['status' => 'success']);
	}
}