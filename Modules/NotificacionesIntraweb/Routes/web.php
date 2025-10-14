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

Route::prefix('notificacionesintraweb')->group(function() {
    Route::get('/', 'NotificacionesMailIntrawebController@index');
});

// Ruta para la gestion de notificaciones-mail-intrawebs
Route::resource('notificaciones-mail-intrawebs', 'NotificacionesMailIntrawebController', ['only' => [
    'index', 'store', 'update', 'destroy'
]])->middleware('auth');
// Ruta que obtiene todos los registros de notificaciones-mail-intrawebs
Route::get('get-notificaciones-mail-intrawebs', 'NotificacionesMailIntrawebController@all')->name('all');
// Ruta para exportar los datos de notificaciones-mail-intrawebs
Route::post('export-notificaciones-mail-intrawebs', 'NotificacionesMailIntrawebController@export')->name('export');

Route::put('reenviar-notificación', 'NotificacionesMailIntrawebController@reenviarNotificacion');

// Ruta para la gestion de listado-correos-checkeos
Route::resource('listado-correos-checkeos', 'ListadoCorreosCheckeosController', ['only' => [
    'index', 'store', 'update', 'destroy'
]]);
// Ruta que obtiene todos los registros de listado-correos-checkeos
Route::get('get-listado-correos-checkeos', 'ListadoCorreosCheckeosController@all')->name('all');
// Ruta para exportar los datos de listado-correos-checkeos
Route::post('export-listado-correos-checkeos', 'ListadoCorreosCheckeosController@export')->name('export');

