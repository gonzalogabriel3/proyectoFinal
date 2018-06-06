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
//Pagina de inicio para administrar usuarios
Route::get('/inicioColectivos',function(){
    return view('colectivo/index');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('usuarios', 'UsuarioController');

Route::resource('/colectivo', 'ColectivoController');