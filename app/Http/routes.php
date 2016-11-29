<?php
session_start();
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/personas', '\Idrd\Usuarios\Controllers\PersonaController@index');
Route::get('/personas/service/obtener/{id}', '\Idrd\Usuarios\Controllers\PersonaController@obtener');
Route::get('/personas/service/buscar/{key}', '\Idrd\Usuarios\Controllers\PersonaController@buscar');
Route::get('/personas/service/ciudad/{id_pais}', '\Idrd\Usuarios\Controllers\LocalizacionController@buscarCiudades');
Route::post('/personas/service/procesar/', '\Idrd\Usuarios\Controllers\PersonaController@procesar');

Route::get('/parques/service/buscar/{key}', '\Idrd\Parques\Controllers\ParqueController@buscar');

Route::any('/logout', 'MainController@logout');
Route::any('/', 'MainController@index');

//rutas con filtro de autenticación
Route::group(['middleware' => ['web']], function() 
{
	Route::get('/welcome', 'MainController@welcome');
	
	Route::get('/profesores', 'Recreovia\ProfesoresController@index');
	Route::get('/profesores/service/buscar/{key}/{strict?}', 'Recreovia\ProfesoresController@buscar');
	Route::get('/profesores/service/obtener/{id}', 'Recreovia\ProfesoresController@obtener');
	Route::get('/profesores/crear', 'Recreovia\ProfesoresController@crear');
	Route::get('/profesores/editar/{id}', 'Recreovia\ProfesoresController@editar');
	Route::get('/profesores/eliminar/{id}', 'Recreovia\ProfesoresController@eliminar');
	Route::post('/profesores/procesar', 'Recreovia\ProfesoresController@procesar');

	Route::get('/puntos', 'Recreovia\PuntosController@index');
	Route::get('/puntos/service/buscar/{key}', 'Recreovia\PuntosController@buscar');
	Route::get('/puntos/service/obtener/{id}', 'Recreovia\PuntosController@obtener');
	Route::get('/puntos/crear/', 'Recreovia\PuntosController@crear');
	Route::get('/puntos/editar/{id}', 'Recreovia\PuntosController@editar');
	Route::get('/puntos/eliminar/{id}', 'Recreovia\PuntosController@eliminar');
	Route::post('/puntos/procesar/', 'Recreovia\PuntosController@procesar');

	Route::get('/localidades/administrar', 'Recreovia\LocalidadController@index');
	Route::get('/localidades/administrar/{id}', 'Recreovia\LocalidadController@editar');
	Route::get('/localidades/{id}/personal/{id_persona}/remover', 'Recreovia\LocalidadController@removerPersonal');
	Route::post('/localidades/personal/agregar', 'Recreovia\LocalidadController@agregarPersonal');

	route::get('/jornadas', 'Recreovia\JornadaController@index');
	route::get('/jornadas/crear', 'Recreovia\JornadaController@crear');
	route::get('/jornadas/editar/{id}', 'Recreovia\JornadaController@editar');
	route::get('/jornadas/eliminar/{id}', 'Recreovia\JornadaController@eliminar');
	route::post('/jornadas/procesar', 'Recreovia\JornadaController@procesar');
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