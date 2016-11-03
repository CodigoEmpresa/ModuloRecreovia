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
Route::any('/', 'MainController@index');
Route::any('/logout', 'MainController@logout');

//rutas con filtro de autenticación
Route::group(['middleware' => ['web']], function() 
{
	Route::get('/welcome', 'MainController@welcome');
	
	Route::get('/profesores', 'Recreovia\ProfesoresController@index');
	Route::get('/profesores/service/buscar/{key}', 'Recreovia\ProfesoresController@buscar');
	Route::get('/profesores/service/obtener/{id}', 'Recreovia\ProfesoresController@obtener');
	Route::post('/profesores/service/procesar/', 'Recreovia\ProfesoresController@procesar');

	Route::get('/puntos', 'Recreovia\PuntosController@index');
	Route::get('/puntos/service/obtener/{id}', 'Recreovia\PuntosController@obtener');
	Route::post('/puntos/service/procesar/', 'Recreovia\PuntosController@procesar');
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