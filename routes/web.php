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

Route::middleware(['auth'])->group(function () {

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

    //pagina de inicio para administrar Recorridos
    Route::get('/inicioRecorridos',function(){
        return view('recorrido/index');
    });

    //pagina de inicio para administrar Tramos
    Route::get('/inicioTramos',function(){
        return view('tramo/index');
    });

    //pagina de inicio para administrar Puntos de recarga
    Route::get('/inicioPuntosRecarga',function(){
        return view('puntosRecarga/index');
    });

    //pagina de inicio para administrar comentarios
    Route::get('/inicioComentarios',function(){
        return view('comentario/index');
    });

    //pagina de inicio para administrar Sugerencias
    Route::get('/inicioSugerencia',function(){
        return view('sugerencia/index');
    });
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/usuario', 'UsuarioController');

Route::resource('/colectivo', 'ColectivoController');

Route::resource('/parada', 'ParadaController');

Route::resource('/tarifa', 'TarifaController');

Route::resource('/horario', 'HorarioController');

Route::resource('/recorrido', 'RecorridoController');

Route::resource('/tramo', 'TramoController');

Route::resource('/punto', 'PuntoRecargaController');

Route::resource('/comentario', 'ComentarioController');

Route::resource('/sugerencia', 'SugerenciaController');


//Ruta para el mapa de ejemplo
Route::get('/mapa',function(){
    return view('ejemploMapBox');
});