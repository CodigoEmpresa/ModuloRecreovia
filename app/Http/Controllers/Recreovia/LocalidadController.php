<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Requests;
use App\Http\Requests\AgregarPersonalLocalidad;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modulos\Parques\Localidad;

class LocalidadController extends Controller {
	
	public function index()
	{
		$formulario = [
			'titulo' => 'Administrar localidades',
			'localidades' => Localidad::with(['puntos' => function($query)
									{
										$query->whereNull('deleted_at');
									}, 'recreopersonas', 'recreopersonas.persona'])
									->orderBy('Id_Localidad')
									->get(),
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Administrar localidades',
			'formulario' => view('idrd.recreovia.administracion-de-localidades', $formulario)
		];

		return view('form', $datos);
	}

	public function editar(Request $request, $id)
	{
		$formulario = [
			'titulo' => 'Administrar personal localidad',
			'localidad' => Localidad::with('puntos', 'recreopersonas')
									->find($id),
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Administrar localidades',
			'formulario' => view('idrd.recreovia.formulario-administracion-de-localidades', $formulario)
		];

		return view('form', $datos);
	}

	public function agregarPersonal(AgregarPersonalLocalidad $request)
	{
		$localidad = Localidad::with('recreopersonas')->find($request->input('id'));

		if ($localidad)
		{
			$localidad->recreopersonas()->detach($request->input('id_persona'));
			$localidad->recreopersonas()->attach($request->input('id_persona'), ['tipo' => $request->input('tipo')]);
		}

		return redirect('/localidades/administrar/'.$request->input('id'))->with(['status' => 'success']); 
	}

	public function removerPersonal(Request $request, $id, $id_persona)
	{
		$localidad = Localidad::with('recreopersonas')->find($id);

		if ($localidad)
		{
			$localidad->recreopersonas()->detach($id_persona);
		}

		return redirect('/localidades/administrar/'.$id)->with(['status' => 'success']); 
	}
}