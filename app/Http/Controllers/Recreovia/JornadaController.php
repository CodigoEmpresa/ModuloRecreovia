<?php 

namespace App\Http\Controllers\Recreovia;

se App\Http\Requests;
use App\Http\Requests\AgregarPersonalLocalidad;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modulos\Parques\Localidad;

class JornadaController extends Controller {
	
	protected $usuario;

	public function __construct()
	{
		if (isset($_SESSION['Usuario']))
			$this->usuario = $_SESSION['Usuario'];
	}

	public function index()
	{

	}

}