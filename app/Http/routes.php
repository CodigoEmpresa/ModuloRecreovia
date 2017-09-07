<?php
session_start();
session_set_cookie_params(5000000000, "/");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

Route::get('/personas', '\Idrd\Usuarios\Controllers\PersonaController@index');
Route::get('/personas/service/obtener/{id}', '\Idrd\Usuarios\Controllers\PersonaController@obtener');
Route::get('/personas/service/buscar/{key}', '\Idrd\Usuarios\Controllers\PersonaController@buscar');
Route::get('/personas/service/ciudad/{id_pais}', '\Idrd\Usuarios\Controllers\LocalizacionController@buscarCiudades');
Route::post('/personas/service/procesar/', '\Idrd\Usuarios\Controllers\PersonaController@procesar');

Route::get('/actividad_usuario/{identificacion?}', function ($identificacion = null) {
	return view('idrd.usuarios.persona_actividades', [
		'seccion' => 'Actividades',
		'identificacion' => $identificacion
	]);
});

Route::get('/usuario_tipo', function () { return view('persona_tipoPersona'); });
Route::get('/asignarActividad', '\Idrd\Usuarios\Controllers\AsignarActividadController@asignarActividades');
Route::get('/actividadesModulo', '\Idrd\Usuarios\Controllers\AsignarActividadController@moduloActividades');
Route::get('/actividadesPersona/{id}', '\Idrd\Usuarios\Controllers\AsignarActividadController@personaActividades');
Route::get('/tipo_modulo', '\Idrd\Usuarios\Controllers\AsignarActividadController@tipoModulo');
Route::get('/parques/service/buscar/{key}', '\Idrd\Parques\Controllers\ParqueController@buscar');
Route::post('ProcesoTipoPersona', '\Idrd\Usuarios\Controllers\AsignarActividadController@AdicionTipoPersona');
Route::get('/buscador', 'Recreovia\BuscadorController@index');
Route::post('/buscador', 'Recreovia\BuscadorController@buscar');

Route::any('/logout', 'MainController@logout');
Route::any('/', 'MainController@index');
Route::any('PersonasActividadesProceso', '\Idrd\Usuarios\Controllers\AsignarActividadController@PersonasActividadesProceso');

//rutas con filtro de autenticación
Route::group(['middleware' => ['web']], function()
{
	Route::get('/welcome', 'MainController@welcome');

	Route::get('/jornadas', 'Recreovia\JornadaController@index');
	Route::get('/jornadas/crear', 'Recreovia\JornadaController@crear');
	Route::get('/jornadas/{id}/editar', 'Recreovia\JornadaController@editar');
	Route::get('/jornadas/{id}/eliminar', 'Recreovia\JornadaController@eliminar');
	Route::post('/jornadas/procesar', 'Recreovia\JornadaController@procesar');

	Route::get('/puntos', 'Recreovia\PuntosController@index');
	Route::get('/puntos/service/buscar/{key}', 'Recreovia\PuntosController@buscar');
	Route::get('/puntos/service/obtener/{id}', 'Recreovia\PuntosController@obtener');
	Route::get('/puntos/crear/', 'Recreovia\PuntosController@crear');
	Route::get('/puntos/{id}/editar', 'Recreovia\PuntosController@editar');
	Route::get('/puntos/{id}/eliminar', 'Recreovia\PuntosController@eliminar');
	Route::post('/puntos/procesar/', 'Recreovia\PuntosController@procesar');

	Route::get('/localidades/administrar', 'Recreovia\LocalidadController@index');
	Route::get('/localidades/{id_localidad}/administrar/{id_punto?}', 'Recreovia\LocalidadController@editar');
	Route::get('/localidades/{id_localidad}/personal/{id_persona}/remover', 'Recreovia\LocalidadController@removerPersonal');
	Route::post('/localidades/personal/agregar', 'Recreovia\LocalidadController@agregarPersonal');

	Route::any('/programacion', 'Recreovia\ProgramacionController@index');
	Route::get('/programacion/crear', 'Recreovia\ProgramacionController@crear');
	Route::get('/programacion/{id_cronograma}/editar', 'Recreovia\ProgramacionController@editar');
	Route::get('/programacion/{id_cronograma}/eliminar', 'Recreovia\ProgramacionController@eliminar');
	Route::post('/programacion/procesar', 'Recreovia\ProgramacionController@procesar');
	Route::post('/programacion/disponibilidad', 'Recreovia\ProgramacionController@disponibilidad');
    Route::post('/programacion/buscar', 'Recreovia\ProgramacionController@buscar');

    Route::get('/programacion/ajustar', 'Recreovia\ProgramacionController@ajustar');
    Route::post('/programacion/ajustar', 'Recreovia\ProgramacionController@procesarAjuste');

	Route::any('/sesiones/administrar', 'Recreovia\ProgramacionController@todos');
	Route::any('/sesiones/buscar', 'Recreovia\SesionController@buscar');

	Route::any('/gestores/sesiones', 'Recreovia\SesionController@sesionesGestor');
	Route::get('/gestores/{id_cronograma}/sesiones', 'Recreovia\SesionController@crearSesionesGestor');
	Route::get('/gestores/{id_cronograma}/sesiones/{id_sesion}/editar', 'Recreovia\SesionController@editarSesionesGestor');
	Route::get('/gestores/{id_cronograma}/sesiones/{id_sesion}/eliminar', 'Recreovia\SesionController@eliminarSesionesGestor');
	Route::post('/gestores/sesiones/procesar', 'Recreovia\SesionController@procesarGestor');
	Route::get('/gestores/sesiones/{id_sesion}/editar', 'Recreovia\SesionController@editarSesionGestor');

	Route::get('/profesores', 'Recreovia\ProfesoresController@index');
	Route::get('/profesores/service/buscar/{key}/{strict?}', 'Recreovia\ProfesoresController@buscar');
	Route::get('/profesores/service/obtener/{id}', 'Recreovia\ProfesoresController@obtener');
	Route::get('/profesores/crear', 'Recreovia\ProfesoresController@crear');
	Route::get('/profesores/{id}/editar', 'Recreovia\ProfesoresController@editar');
	Route::get('/profesores/{id}/eliminar', 'Recreovia\ProfesoresController@eliminar');
	Route::post('/profesores/procesar', 'Recreovia\ProfesoresController@procesar');
	Route::any('/profesores/sesiones', 'Recreovia\SesionController@sesionesProfesor');
	Route::get('/profesores/sesiones/{id_sesion}/editar', 'Recreovia\SesionController@editarSesionProfesor');

	Route::post('/sesiones/actualizar_estado', 'Recreovia\SesionController@actualizarEstado');
	Route::post('/sesiones/procesar', 'Recreovia\SesionController@procesar');
	Route::post('/asistencia/procesar', 'Recreovia\SesionController@asistencia');
	Route::post('/producto_no_conforme/procesar', 'Recreovia\SesionController@productoNoConforme');
	Route::get('/producto_no_conforme/{id}/eliminar/{tipo}', 'Recreovia\SesionController@eliminarProductoNoConforme');
	Route::post('/calificacion_del_servicio/procesar', 'Recreovia\SesionController@calificacionDelServicio');

	Route::any('/informes/jornadas', 'Recreovia\ReporteController@jornadas');
	Route::any('/informes/jornadas/profesor', 'Recreovia\ReporteController@jornadas_profesor');
	Route::get('/informes/jornadas/crear', 'Recreovia\ReporteController@crearInformeJornadas');
	Route::get('/informes/jornadas/{id}/editar', 'Recreovia\ReporteController@editarInformeJornadas');
	Route::get('/informes/jornadas/{id}/eliminar', 'Recreovia\ReporteController@eliminarInformeJornadas');
	Route::get('/informes/jornadas/gestor/{id_gestor}/punto/{id_punto}/cronogramas', 'Recreovia\ReporteController@obtenerCronogramasPunto');
	Route::post('/informes/jornadas/generar', 'Recreovia\ReporteController@generarInformeJornadas');
	Route::post('/informes/jornadas/actualizar', 'Recreovia\ReporteController@actualizarInformeJornadas');
	Route::any('/informes/jornadas/revisar', 'Recreovia\ReporteController@obtenerInformes');

	Route::get('/informes/consolidado_general', 'Recreovia\ConsolidadoGeneralController@index');
	Route::post('/informes/consolidado_general', 'Recreovia\ConsolidadoGeneralController@generar');

});
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
