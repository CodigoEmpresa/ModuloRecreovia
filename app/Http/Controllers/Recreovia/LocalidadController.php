<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Requests\AgregarPersonalLocalidad;
use App\Http\Controllers\Controller;
use App\Modulos\Parques\Localidad;
use App\Modulos\Recreovia\Punto;
use Illuminate\Http\Request;

class LocalidadController extends Controller {
	
	public function index()
	{
		$formulario = [
			'titulo' => 'Administrar puntos',
			'localidades' => Localidad::with(['puntos' => function($query)
									{
										$query->whereNull('deleted_at');
									}])
									->orderBy('Id_Localidad')
									->get(),
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Administrar localidades',
			'formulario' => view('idrd.recreovia.lista-localidades', $formulario)
		];

		return view('form', $datos);
	}

	public function editar(Request $request, $id_localidad, $id_punto = 0)
	{
		$formulario = [
			'titulo' => 'Administrar personal punto',
			'localidad' => Localidad::with('puntos')
									->find($id_localidad),
			'punto' => $id_punto == 0 ? null : Punto::with('recreopersonas')->find($id_punto),
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Administrar localidades',
			'formulario' => view('idrd.recreovia.formulario-personas-puntos', $formulario)
		];

		return view('form', $datos);
	}

	public function agregarPersonal(AgregarPersonalLocalidad $request)
	{
		$punto = Punto::with('recreopersonas')->find($request->input('id_punto'));

		if ($punto)
		{
			$punto->recreopersonas()->detach($request->input('id_persona'));
			$punto->recreopersonas()->attach($request->input('id_persona'), ['tipo' => $request->input('tipo')]);
		}

		return redirect('/localidades/administrar/'.$request->input('id_localidad').'/'.$request->input('id_punto'))->with(['status' => 'success']); 
	}

	public function removerPersonal(Request $request, $id_localidad, $id_punto, $id_persona)
	{
		$punto = Punto::with('recreopersonas')->find($id_punto);

		if ($punto)
		{
			$punto->recreopersonas()->detach($id_persona);
		}

		return redirect('/localidades/administrar/'.$id_localidad.'/'.$id_punto)->with(['status' => 'success']); 
	}
}