<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modulos\Recreovia\Recreopersona;
use Idrd\Usuarios\Repo\PersonaInterface;
use Illuminate\Http\Request;
use App\Modulos\Recreovia\Sesion;

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
		$programadas = null;
		$asignadas = null;
		if (in_array('Gestor', $_SESSION['Usuario']['Roles']))
		{
			$programadas = Sesion::with('cronograma', 'cronograma.punto', 'cronograma.jornada', 'profesor.persona', 'gruposPoblacionales')
				->whereHas('cronograma', function($query)
				{
					$query->where('Id_Recreopersona', $this->Usuario['Recreopersona']->Id_Recreopersona);
				})
				->whereNull('deleted_at')
				->whereYear('created_at', '=', date('Y'))
				->orderBy('Id', 'DESC')
				->get();
		}

		if (in_array('Profesor', $_SESSION['Usuario']['Roles'])) {
			$asignadas = Sesion::with('cronograma', 'cronograma.punto', 'cronograma.jornada', 'profesor.persona', 'gruposPoblacionales')
				->whereNull('deleted_at')
				->where('Id_Recreopersona', $this->Usuario['Recreopersona']->Id_Recreopersona)
				->whereYear('created_at', '=', date('Y'))
				->orderBy('Id', 'DESC')
				->get();
		}

		$data = [
			'programadas' => $programadas,
			'asignadas' => $asignadas,
			'recreopersona' => $this->Usuario['Recreopersona']
		];

		$data['seccion'] = '';

		return view('welcome', $data);
	}

    public function index(Request $request)
	{
		//$fake_permissions = ['5144', '1', '1', '1', '1', '1', '1', '1', '1'];
		$fake_permissions = null;

		if ($request->has('vector_modulo') || $fake_permissions)
		{	
			$vector = $request->has('vector_modulo') ? urldecode($request->input('vector_modulo')) : $fake_permissions;
			$user_array = is_array($vector) ? $vector : unserialize($vector);
			$permissions_array = $user_array;

			$permisos = [
				'editar_profesores' => intval($permissions_array[1]),
				'editar_puntos' => intval($permissions_array[2]),
				'administrar_localidades' => intval($permissions_array[3]),
				'administrar_jornadas'=> intval($permissions_array[4]),
				'programar_sesiones'=> intval($permissions_array[5]),
				'revisar_sesiones_gestor'=> intval($permissions_array[6]),
				'revisar_sesiones_profesor'=> intval($permissions_array[7]),
				'gestionar_reportes_jornadas'=> intval($permissions_array[8])
			];

			$_SESSION['Usuario'] = $user_array;
            $persona = $this->repositorio_personas->obtener($_SESSION['Usuario'][0]);

			$_SESSION['Usuario']['Recreopersona'] = [];
			$_SESSION['Usuario']['Roles'] = [];
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