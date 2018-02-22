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
	//return view('auth/login');
	//return view('home2');
	if(Auth::guest()){
		return view('auth/login');
	}else{
		return view('home');
	}
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('/favoritos', 'ControllerFavorito');

//Busqueda

Route::get('/api/Busqueda','ControllerBusqueda@busquedaAvanzada');
Route::get('/search/medios/{id}','ControllerBusqueda@autocompleteMedios');
Route::get('/api/Busqueda2','ControllerBusqueda@busquedaAvanzada2');
Route::get('/mediosFiltrado','ControllerBusqueda@cargarMediosFiltrado');
Route::get('/api/FiltroRapido','ControllerBusqueda@filtradoRapido');

//Busqueda24

Route::get('/api/Busqueda24','ControllerBusqueda24@busqueda');
Route::get('/search/medios24/{id}','ControllerBusqueda24@autocompleteMedios');
Route::get('/api/BusquedaAv24','ControllerBusqueda24@busquedaAvanzada24');
Route::get('/mediosFiltrado24','ControllerBusqueda24@cargarMediosFiltrado');
Route::get('/api/FiltroRapido24','ControllerBusqueda24@filtradoRapido');

Route::get('/mediosAV24','ControllerBusqueda24@dataMedioAV');
Route::get('/mediosInternet24','ControllerBusqueda24@dataInternet');
Route::get('/programaAV24','ControllerBusqueda24@dataPrograma');

//Vistas

Route::get('/vistaPrensa/{id}','ControllerVistas@indexPrensa');
Route::get('/vistaTelevision/{id}','ControllerVistas@indexTv');
Route::get('/vistaRadio/{id}','ControllerVistas@indexRadio');
Route::get('/vistaInternet/{id}','ControllerVistas@indexInternet');


//Favorito 

Route::post('/buscarxPC','ControllerFavorito@buscarxPC');
Route::post('/buscarxPeriodo','ControllerFavorito@buscarxPeriodo');
Route::get('info','ControllerFavorito@store');
//Route::resource('/favoritos','ControllerFavorito');
//Route::get('temas','ControllerFavorito@mostrarTema');
//Route::get('claves/{id}','ControllerFavorito@mostrarPalabraClave');

//Herramientas

Route::get('/download','ControllerBusqueda@exportarExcel');
Route::get('/download24','ControllerBusqueda24@exportarExcel');
Route::post('/cambiarvalorpauta','ControllerBusqueda@cambiarValorPauta');
Route::post('/cambiarvalortodo','ControllerBusqueda@cambiarValorTodo');

//Importar Data de la 24

Route::get('/importarPrensa','ControllerBusqueda@importarPautasPrensa24');
Route::get('/importarTv','ControllerBusqueda@importarPautasTv24');
Route::get('/importarRadio','ControllerBusqueda@importarPautasRadio24');
Route::get('/importarInternet','ControllerBusqueda@importarPautasInternet24');

Route::get('/mediosInternet24','ControllerBusqueda@importarMediosInternet');
Route::get('/mediosAV24','ControllerBusqueda@importarMediosAudioVisuales');
Route::get('/programasAV24','ControllerBusqueda@importarProgramasAudioVisuales');
Route::get('/mediosPrensa24','ControllerBusqueda@importarMediosPrensa');
Route::get('/seccionesPrensa24','ControllerBusqueda@importarSeccionesPrensa');


Route::get('/corregirRecortes','ControllerBusqueda@corregirRecortes');