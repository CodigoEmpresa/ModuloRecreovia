<?php

namespace App\Http\Controllers\Recreovia;

use App\Http\Controllers\Controller;
use App\Modulos\Recreovia\Punto;
use App\Modulos\Recreovia\Jornada;
use App\Modulos\Parques\Localidad;
use App\Modulos\Parques\Upz;
use App\Http\Requests\GuardarPunto;
use Idrd\Parques\Repo\LocalizacionInterface;
use Illuminate\Http\Request;

class PuntosController extends Controller {

	public function index()
	{
		$perPage = config('app.page_size');
		$elementos = Punto::with('localidad', 'jornadas', 'upz')
							->whereNull('deleted_at')
							->orderBy('Cod_IDRD', 'ASC')
							->get();

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
		$puntos = Punto::with('localidad', 'profesores', 'gestores', 'jornadas', 'upz')
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
		$punto->Contacto_Nombre = $request['Contacto_Nombre'];
		$punto->Contacto_Telefono = $request['Contacto_Telefono'];
		$punto->Contacto_Correo = $request['Contacto_Correo'];
		$punto->save();

		$jornadas = $request->input('Jornadas');
		$jornadas = rtrim($jornadas, ',');

		if (trim($jornadas) != '')
			$punto->jornadas()->sync(explode(',', $jornadas));
		else
			$punto->jornadas()->detach();

       	return redirect('/puntos/'.$punto->Id_Punto.'/editar')->with(['status' => 'success']);
	}

	public function eliminar(Request $request, $id)
	{
		$punto = Punto::where('Id_Punto', $id)
						->first();
		$punto->delete();
		return redirect('/puntos')->with(['status' => 'success']);
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
