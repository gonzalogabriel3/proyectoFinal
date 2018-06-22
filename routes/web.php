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

Route::get('/', function () {
    return view('welcome');
});

//Pagina de inicio para administrar usuarios
Route::get('/inicioUsuario',function(){
    return view('usuario/index');
});
//Pagina de inicio para administrar colectivos
Route::get('/inicioColectivos',function(){
    return view('colectivo/index');
});
//Pagina de inicio para administrar paradas
Route::get('/inicioParadas',function(){
    return view('parada/index');
});

//Pagina de inicio para administrar tarifas
Route::get('/inicioTarifas',function(){
    return view('tarifa/index');
});

//Pagina de inicio para administrar horarios
Route::get('/inicioHorarios',function(){
    return view('horario/index');
});

Route::get('/inicioRecorridos',function(){
    return view('recorrido/index');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/usuario', 'UsuarioController');

Route::resource('/colectivo', 'ColectivoController');

Route::resource('/parada', 'ParadaController');

Route::resource('/tarifa', 'TarifaController');

Route::resource('/horario', 'HorarioController');

Route::resource('/recorrido', 'RecorridoController');
