<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*Route::get('/index2', function () {
    //return view('welcome');
    return view('index2');
});*/

Route::get('/', function () {
    //return view('welcome');
    return view('auth/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('/favoritos', 'ControllerFavorito');

//Route::get('/prueba','ControllerBusqueda@busquedaAvanzada');

Route::get('/api/Busqueda','ControllerBusqueda@busquedaAvanzada');
Route::get('/search/medios','ControllerBusqueda@autocompleteMedios');

Route::get('/api/Busqueda2','ControllerBusqueda@busquedaAvanzada2');

Route::get('/vistaPrensa/{id}','ControllerVistas@indexPrensa');
Route::get('/vistaTelevisión/{id}','ControllerVistas@indexTv');
Route::get('/vistaRadio/{id}','ControllerVistas@indexRadio');
Route::get('/vistaInternet/{id}','ControllerVistas@indexInternet');
Route::get('/prueba','ControllerBusqueda@index');


//FAVORITO 

//Route::resource('/favoritos','ControllerFavorito');
Route::get('temas','ControllerFavorito@mostrarTema');
Route::get('claves/{id}','ControllerFavorito@mostrarPalabraClave');