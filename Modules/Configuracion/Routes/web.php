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
Route::group(['middleware' => ['auth']], function() {
    Route::prefix('configuracion')->group(function() {

        // Ruta para la gestion de documentos-ayudas
        Route::resource('documentos-ayudas', 'DocumentosAyudaController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de documentos-ayudas
        Route::get('get-documentos-ayudas', 'DocumentosAyudaController@all')->name('all');
        // Ruta para exportar los datos de documentos-ayudas
        Route::post('export-documentos-ayudas', 'DocumentosAyudaController@export')->name('export');

        Route::get('documentos-ayudas-publico', 'DocumentosAyudaController@indexPublico')->name('all');

        // Ruta para la gestion de configuration-generals
        Route::resource('configuration-generals', 'ConfigurationGeneralController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de configuration-generals
        Route::get('get-configuration-generals', 'ConfigurationGeneralController@all')->name('all');
        // Ruta para exportar los datos de configuration-generals
        Route::post('export-configuration-generals', 'ConfigurationGeneralController@export')->name('export');

        // Ruta para la gestion de variables
        Route::resource('variables', 'VariablesController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de variables
        Route::get('get-variables', 'VariablesController@all')->name('all');
        // Ruta para exportar los datos de variables
        Route::post('export-variables', 'VariablesController@export')->name('export');


        Route::get('get-modules', 'ConfigurationGeneralController@allModules')->name('getmodules');

        Route::get('user-storage-capacity','ConfigurationGeneralController@getUserAndStorageCapacity');

        // Ruta para la gestion de versions-updates
        Route::resource('versions-updates', 'versionsUpdateController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de versions-updates
        Route::get('get-versions-updates', 'versionsUpdateController@all')->name('all');
        // Ruta para exportar los datos de versions-updates
        Route::post('export-versions-updates', 'versionsUpdateController@export')->name('export');

    });

    // Ruta para enviar correos a partir del 70% de almacenamiento ya usado
    Route::get('notification-storage', 'ConfigurationGeneralController@notifyStorageSpace');
});

