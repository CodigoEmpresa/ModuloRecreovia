<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modulos\Recreovia\Recreopersona;
use Idrd\Usuarios\Repo\PersonaInterface;
use Illuminate\Http\Request;

class MainController extends Controller {

	protected $Usuario;
	protected $repositorio_personas;

	public function __construct(PersonaInterface $repositorio_personas)
	{
		if (isset($_SESSION['Usuario']))
			$this->Usuario = $_SESSION['Usuario'];

		$this->repositorio_personas = $repositorio_personas;
	}

	public function welcome()
	{
		$data['seccion'] = '';
		return view('welcome', $data);
	}

    public function index(Request $request)
	{
		$fake_permissions = ['71766', '1', '1', '1', '1'];

		if ($request->has('vector_modulo') || $fake_permissions)
		{	
			$vector = $request->has('vector_modulo') ? urldecode($request->input('vector_modulo')) : $fake_permissions;
			$user_array = is_array($vector) ? $vector : unserialize($vector);
			$permissions_array = $user_array;

			$permisos = [
				'editar_profesores' => intval($permissions_array[1]),
				'editar_puntos' => intval($permissions_array[2]),
				'administrar_localidades' => intval($permissions_array[3]),
				'administrar_jornadas'=> intval($permissions_array[4])
			];

			$_SESSION['Usuario'] = $user_array;
			$_SESSION['Usuario']['Recreopersona'] = [];
			$_SESSION['Usuario']['Roles'] = [];
			
			$persona = $this->repositorio_personas->obtener($_SESSION['Usuario'][0]);
			$recreopersona = Recreopersona::with('puntos')->where('Id_Persona', $persona['Id_Persona'])->first();

			if ($recreopersona)
			{
				$_SESSION['Usuario']['Recreopersona'] = $recreopersona;

				foreach ($recreopersona->puntos as $punto)
				{
					if (!in_array($punto->pivot['tipo'], $_SESSION['Usuario']['Roles']))
						$_SESSION['Usuario']['Roles'][] = $punto->pivot['tipo'];
				}
			}

			$_SESSION['Usuario']['Persona'] = $persona;
			$_SESSION['Usuario']['Permisos'] = $permisos;
			$this->Usuario = $_SESSION['Usuario'];
		} else {
			if(!isset($_SESSION['Usuario']))
				$_SESSION['Usuario'] = '';
		}

		if ($_SESSION['Usuario'] == '')
			return redirect()->away('http://www.idrd.gov.co/SIM/Presentacion/');

		return redirect('/welcome');
	}

	public function logout()
	{
		$_SESSION['Usuario'] = '';

		return redirect()->to('/');
	}
}