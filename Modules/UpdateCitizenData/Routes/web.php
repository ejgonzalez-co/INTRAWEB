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

Route::group(['prefix' => 'update-citizen-data'], function () {
    Route::get('/', function () {
        dd('This is the Encuesta actualización de datos personales module index page. Build something great!');
    });


    Route::middleware(['auth', 'verified'])->group(function () {

        Route::resource('udc-requests', 'UdcRequestController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        Route::get('get-udc-requests', 'UdcRequestController@all')->name('all');
        
        Route::post('export-udc-requests', 'UdcRequestController@export')->name('export');
        

    });

    //rutas rol de ciudadano
    Route::resource('udc-requests-citizen', 'UdcRequestCitizenController', ['only' => [
        'index', 'store'
    ]]);
    Route::get('get-udc-requests-citizen', 'UdcRequestCitizenController@all')->name('all');
  
});


