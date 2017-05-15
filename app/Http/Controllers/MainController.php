<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modulos\Recreovia\Recreopersona;
use App\Modulos\Recreovia\Sesion;
use App\Util\Festivos;
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
		$programadas = null;
		$asignadas = null;
		$query = Sesion::with('cronograma');

		if (in_array('Gestor', $_SESSION['Usuario']['Roles']))
		{
			$query->whereHas('cronograma', function($query)
				{
					$query->where('Id_Recreopersona', $this->Usuario['Recreopersona']->Id_Recreopersona);
				});
		}

		if (in_array('Profesor', $_SESSION['Usuario']['Roles']))
		{
			$query->where('Id_Recreopersona', $this->Usuario['Recreopersona']->Id_Recreopersona);
		}

		$sesiones = $query->whereNull('deleted_at')
			->whereYear('Fecha', '=', date('Y'))
			->orderBy('Id', 'DESC')
			->get();

		$data = [
			'sesiones' => $sesiones,
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
				'editar_profesores' => array_key_exists(1, $permissions_array) ? intval($permissions_array[1]) : 0,
				'editar_puntos' => array_key_exists(2, $permissions_array) ? intval($permissions_array[2]) : 0,
				'administrar_localidades' => array_key_exists(3, $permissions_array) ? intval($permissions_array[3]) : 0,
				'administrar_jornadas'=> array_key_exists(4, $permissions_array) ? intval($permissions_array[4]) : 0,
				'programar_sesiones'=> array_key_exists(5, $permissions_array) ? intval($permissions_array[5]) : 0,
				'revisar_sesiones_gestor'=> array_key_exists(6, $permissions_array) ? intval($permissions_array[6]) : 0,
				'revisar_sesiones_profesor'=> array_key_exists(7, $permissions_array) ? intval($permissions_array[7]) : 0,
				'gestionar_reportes_jornadas'=> array_key_exists(8, $permissions_array) ? intval($permissions_array[8]) : 0,
				'validar_reportes_jornadas' => array_key_exists(9, $permissions_array) ? intval($permissions_array[9]) : 0,
				'exportar_consolidado_general' => array_key_exists(10, $permissions_array) ? intval($permissions_array[10]) : 0,
				'gestion_global_de_sesiones' => array_key_exists(11, $permissions_array) ? intval($permissions_array[11]) : 0,
				'buscador_de_sesiones' => array_key_exists(12, $permissions_array) ? intval($permissions_array[12]) : 0
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
