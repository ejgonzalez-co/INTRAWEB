<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix' => 'citizen-poll'], function () {
    Route::get('/', function () {
        dd('This is the CitizenPoll module index page. Build something great!');
    });

    Route::middleware(['auth', 'verified'])->group(function () {

        Route::resource('polls', 'PollsController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        Route::get('get-polls', 'PollsController@all')->name('all');
        
        Route::post('export-polls', 'PollsController@export')->name('export');
        
    });
    
    //rutas rol de ciudadano
    Route::resource('polls-citizen', 'CitizenPollsController', ['only' => [
        'index', 'store'
    ]]);
    Route::get('get-polls-citizen', 'CitizenPollsController@all')->name('all');
    //Ruta que envia los datos del slider
    Route::get('get-slider', 'CitizenPollsController@getSlider');

    
    // Ruta para la gestion de imageManagers
    Route::resource('image-managers', 'ImageManagerController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de imageManagers
    Route::get('get-image-managers', 'ImageManagerController@all')->name('all');
    // Ruta para exportar los datos de imageManagers
    Route::post('export-imageManagers', 'ImageManagerController@export')->name('export');

    
});