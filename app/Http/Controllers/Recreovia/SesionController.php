<?php 

namespace App\Http\Controllers\Recreovia;

use App\Http\Controllers\Controller;
use App\Modulos\Recreovia\Cronograma;
use App\Modulos\Recreovia\GrupoPoblacional;
use App\Modulos\Recreovia\Recreopersona;
use App\Modulos\Recreovia\Sesion;
use App\Http\Requests\GuardarSesionGestor;
use Illuminate\Http\Request;
use Mail;

class SesionController extends Controller {
	
	public function __construct()
	{
		if (isset($_SESSION['Usuario']))
			$this->usuario = $_SESSION['Usuario'];
	}

	public function crearSesionesGestor(Request $request, $id_cronograma)
	{
		$cronograma = Cronograma::with(['punto', 'punto.profesores.persona', 'jornada', 'sesiones', 'sesiones.profesor'])
											->find($id_cronograma);
											
		$formulario = [
			'titulo' => 'Crear o editar sesiones',
			'cronograma' => $cronograma,
			'sesion' => null,
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Programación',
			'formulario' => view('idrd.recreovia.formulario-sesiones', $formulario)
		];

		return view('form', $datos);
	}

	public function editarSesionesGestor(Request $request, $id_cronograma, $id_sesion)
	{
		$cronograma = Cronograma::with(['punto', 'punto.profesores.persona', 'jornada', 'sesiones'])->find($id_cronograma);

		$sesion = Sesion::find($id_sesion);
											
		$formulario = [
			'titulo' => 'Crear o editar sesiones',
			'cronograma' => $cronograma,
			'sesion' => $sesion,
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Programación',
			'formulario' => view('idrd.recreovia.formulario-sesiones', $formulario)
		];

		return view('form', $datos);
	}

	public function editarSesionProfesor(Request $request, $id_sesion)
	{
		$sesion = Sesion::with('cronograma', 'cronograma.punto', 'cronograma.jornada', 'gruposPoblacionales', 'profesor')->find($id_sesion);
		$gruposPoblacionales = GrupoPoblacional::get();
											
		$formulario = [
			'titulo' => 'Sesion',
			'sesion' => $sesion,
			'gruposPoblacionales' => $gruposPoblacionales,
			'tipo' => 'profesor',
			'area' => session('area'),
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Sesiones profesor',
			'formulario' => view('idrd.recreovia.formulario-sesiones-detalles', $formulario)
		];

		return view('form', $datos);
	}

	public function editarSesionGestor(Request $request, $id_sesion)
	{
		$sesion = Sesion::with('cronograma', 'cronograma.punto', 'cronograma.jornada', 'gruposPoblacionales', 'profesor')->find($id_sesion);
		$gruposPoblacionales = GrupoPoblacional::get();
											
		$formulario = [
			'titulo' => 'Sesion',
			'sesion' => $sesion,
			'gruposPoblacionales' => $gruposPoblacionales,
			'tipo' => 'gestor',
			'area' => session('area'),
			'status' => session('status')
		];

		$datos = [
			'seccion' => 'Sesiones gestor',
			'formulario' => view('idrd.recreovia.formulario-sesiones-detalles', $formulario)
		];

		return view('form', $datos);
	}

	public function eliminarSesionesGestor(Request $request, $id_cronograma, $id_sesion)
	{
		$sesion = Sesion::find($id_sesion);
		$sesion->delete();

		return redirect('/gestores/'.$id_cronograma.'/sesiones')->with(['status' => 'success']);
	}

	public function procesarGestor(GuardarSesionGestor $request)
	{
		$notificar = false;

		if ($request->input('Id') == 0)
		{
			$sesion = new Sesion;
			$nuevo = true;
			$notificar = true;
		} else {
			$sesion = Sesion::find($request->input('Id'));
			if ($sesion->Id_Recreopersona != $request->input('Id_Recreopersona'))
			{
				$notificar = true;
				//si el estado de ejecucion es realizado no es necesario cambiar el estado de ejecución
				$sesion->Estado_Ejecucion = $sesion->Estado_Ejecucion != 'Realizado' ? 'Reasignado' : $sesion->Estado_Ejecucion;
			}

			$nuevo = false;
		}

		$sesion->Id_Cronograma = $request->input('Id_Cronograma');
		$sesion->Id_Recreopersona = $request->input('Id_Recreopersona') == '' ? null : $request->input('Id_Recreopersona');
		$sesion->Objetivo_General = $request->input('Objetivo_General');
		$sesion->Recursos = $request->input('Recursos');
		$sesion->Fecha = $request->input('Fecha');
		$sesion->Inicio = $request->input('Inicio');
		$sesion->Fin = $request->input('Fin');
		$sesion->Estado = !$nuevo ? $sesion->Estado : 'Pendiente';
		$sesion->save();

		if ($notificar && $sesion->profesor)
			$this->notificar($sesion, 'profesor');

		return redirect('/gestores/'.$request->input('Id_Cronograma').'/sesiones')->with(['status' => 'success']);
	}

	public function procesar(Request $request)
	{	
		$notificar = false;
		$sesion = Sesion::find($request->input('Id'));

		if ($sesion->Estado != $request->input('Estado'))
			$notificar = true;
		
		$sesion->Objetivos_Especificos = $request->input('Objetivos_Especificos');
		$sesion->Metodologia_Aplicar = $request->input('Metodologia_Aplicar');
		$sesion->Recursos = $request->input('Recursos');
		$sesion->Fase_Inicial = $request->input('Fase_Inicial');
		$sesion->Fase_Central = $request->input('Fase_Central');
		$sesion->Fase_Final = $request->input('Fase_Final');
		$sesion->Observaciones = $request->input('Observaciones');
		$sesion->Estado = $request->has('Estado') ? $request->input('Estado') : $sesion->Estado;
		
		if ($request->input('origen') == 'profesor')
		{
			switch ($sesion->Estado) 
			{
				case 'Aprobado':
				break;

				case 'Pendiente':
				case 'Diligenciado':
				case 'Rechazado':
				case 'Corregir':
					$sesion->Estado = 'Diligenciado';
				default:
				break;
			}
		}

		if ($request->input('origen') == 'gestor')
		{
			switch ($sesion->Estado) 
			{
				case 'Diligenciado':
				case 'Pendiente':
				case 'Corregir':
				break;

				case 'Aprobado':
					$sesion->Estado_Ejecucion = 'Pendiente';
				break;

				case 'Rechazado':
					$sesion->Metodologia_Aplicar = '';
					$sesion->Recursos = '';
					$sesion->Fase_Inicial = '';
					$sesion->Fase_Central = '';
					$sesion->Fase_Final = '';
				default:
				break;
			}
		}

		$sesion->save();

		if($request->input('origen') == 'profesor')
		{
			if ($notificar)
				$this->notificar($sesion, 'gestor');
			
			return redirect('/profesores/sesiones/'.$sesion['Id'].'/editar')->with(['status' => 'success', 'area' => 'Detalles']);

		} else if($request->input('origen') == 'gestor') {

			if ($notificar)
				$this->notificar($sesion, 'profesor');

			return redirect('/gestores/sesiones/'.$sesion['Id'].'/editar')->with(['status' => 'success', 'area' => 'Detalles']);
		}
	}

	public function asistencia(Request $request)
	{
		$gruposPoblacionales = GrupoPoblacional::get();
		$sesion = Sesion::find($request->input('Id'));
		$sesion->gruposPoblacionales()->detach();

		foreach ($gruposPoblacionales as $grupo) 
		{
			//participantes masculinos
			$sesion->gruposPoblacionales()->save($grupo, [
				'Genero' => 'M',
				'Grupo_Asistencia' => 'Participantes',
				'Cantidad' => $request->has('participantes-m-'.$grupo['Id']) ? $request->input('participantes-m-'.$grupo['Id']) : 0
			]);

			//participantes femeninos
			$sesion->gruposPoblacionales()->save($grupo, [
				'Genero' => 'F',
				'Grupo_Asistencia' => 'Participantes',
				'Cantidad' => $request->has('participantes-f-'.$grupo['Id']) ? $request->input('participantes-f-'.$grupo['Id']) : 0
			]);

			//asistentes masculinos
			$sesion->gruposPoblacionales()->save($grupo, [
				'Genero' => 'M',
				'Grupo_Asistencia' => 'Asistentes',
				'Cantidad' => $request->has('asistentes-m-'.$grupo['Id']) ? $request->input('asistentes-m-'.$grupo['Id']) : 0
			]);

			//asistentes femeninos
			$sesion->gruposPoblacionales()->save($grupo, [
				'Genero' => 'F',
				'Grupo_Asistencia' => 'Asistentes',
				'Cantidad' => $request->has('asistentes-f-'.$grupo['Id']) ? $request->input('asistentes-f-'.$grupo['Id']) : 0
			]);
		}

		$sesion->Estado_Ejecucion = 'Realizado';
		$sesion->save();

		if($request->input('origen') == 'profesor')
		{
			return redirect('/profesores/sesiones/'.$sesion['Id'].'/editar')->with(['status' => 'success', 'area' => 'Asistencia']);
		} else if($request->input('origen') == 'gestor') {
			return redirect('/gestores/sesiones/'.$sesion['Id'].'/editar')->with(['status' => 'success', 'area' => 'Asistencia']);
		}
	}

	public function sesionesProfesor(Request $request)
	{
		$perPage = config('app.page_size');
		$elementos = Sesion::with('cronograma', 'cronograma.punto', 'cronograma.jornada', 'profesor.persona')
							->whereNull('deleted_at')
							->where('Id_Recreopersona', $this->usuario['Recreopersona']->Id_Recreopersona)
							->orderBy('Id', 'DESC')
							->take(200)
							->get();

		$lista = [
			'titulo' => 'Sesiones profesor',
	        'elementos' => $elementos,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Sesiones profesor',
			'lista'	=> view('idrd.recreovia.lista-sesiones-profesor', $lista)
		];

		return view('list', $datos);
	}

	public function sesionesGestor(Request $request)
	{
		$perPage = config('app.page_size');
		$elementos = Sesion::with('cronograma', 'cronograma.punto', 'cronograma.jornada', 'profesor.persona')
							->whereHas('cronograma', function($query)
							{
								$query->where('Id_Recreopersona', $this->usuario['Recreopersona']->Id_Recreopersona);
							})
							->whereNull('deleted_at')
							->orderBy('Id', 'DESC')
							->take(200)
							->get();

		$lista = [
			'titulo' => 'Sesiones gestor',
	        'elementos' => $elementos,
	        'status' => session('status')
		];

		$datos = [
			'seccion' => 'Sesiones gestor',
			'lista'	=> view('idrd.recreovia.lista-sesiones-gestor', $lista)
		];

		return view('list', $datos);
	}

	private function notificar($sesion, $to)
	{
		try {
			$sesion = Sesion::with('cronograma', 'cronograma.gestor', 'cronograma.gestor.persona', 'cronograma.punto', 'cronograma.jornada', 'profesor', 'profesor.persona')
							->whereNull('deleted_at')
							->find($sesion['Id']);

			$profesor = $sesion->profesor;
			$gestor = $sesion->cronograma->gestor;

			switch ($to) {
				case 'gestor':
						$destinatario = $gestor;
						$notificacion = 'La sesión de '.$sesion->toString().' la cual le fue asignada al profesor '.$profesor->persona->toString().' tiene actualmente el estado: '.$sesion->Estado.'. Le recomendamos ingresar al modulo de Recreovía del sistema de información misional (SIM) para continuar el proceso.';
					break;
				case 'profesor':
						$destinatario = $profesor;
						$notificacion = 'La sesión de '.$sesion->toString().' la cual le fue asignada tiene actualmente el estado: '.$sesion->Estado.'. Le recomendamos ingresar al modulo de Recreovía del sistema de información misional (SIM) para continuar el proceso.';
					break;
				default:
					# code...
					break;
			}

			if($destinatario)
			{
				$datos = [
					'titulo' => 'Notificación',
					'destinatario' => $destinatario->persona->toFriendlyString(),
					'notificacion' => $notificacion,
					'link' => [
						'url' => 'http://www.idrd.gov.co/SIM/Presentacion/',
						'texto' => 'Ingresar al SIM'
					],
					'pie' => 'Gracias.',
				];

				Mail::send('email.notificacion', $datos, function($m) use ($destinatario)
				{
					$m->from('mails@idrd.gov.co', 'Recreovía');
					$m->to($destinatario->correo, $destinatario->persona->toFriendlyString())->subject('Notificación');
				});
			}
		} catch (Exception $e) {
			
		}
	}
}