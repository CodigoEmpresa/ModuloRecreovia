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
			'localidad' => Localidad::with('recreopersonas')
									->find($id_localidad),
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Administrar localidades',
			'formulario' => view('idrd.recreovia.formulario-personas-localidades', $formulario)
		];

		return view('form', $datos);
	}

	public function agregarPersonal(AgregarPersonalLocalidad $request)
	{
		$localidad = Localidad::with('recreopersonas')->find($request->input('id_localidad'));

		if ($localidad)
		{
			$localidad->recreopersonas()->detach($request->input('id_persona'));
			$localidad->recreopersonas()->attach($request->input('id_persona'), ['tipo' => $request->input('tipo')]);
			
			return redirect('/localidades/'.$request->input('id_localidad').'/administrar/'.$request->input('id_punto'))->with(['status' => 'success']); 
		} else {
			return redirect('/localidades/administrar/'); 
		}

	}

	public function removerPersonal(Request $request, $id_localidad, $id_persona)
	{
		$localidad = Localidad::with('recreopersonas')->find($id_localidad);

		if ($localidad)
		{
			$localidad->recreopersonas()->detach($id_persona);
		}

		return redirect('/localidades/'.$id_localidad.'/administrar/')->with(['status' => 'success']); 
	}
}