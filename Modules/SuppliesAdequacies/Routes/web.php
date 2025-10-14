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
Route::group(['middleware' => ['auth']], function() {
    Route::group(['prefix' => 'supplies-adequacies'], function () {
        Route::get('/', function () {
            dd('This is the Suppliesadequacies module index page. Build something great!');
        });

        // Ruta para la gestion de requests
        Route::resource('requests', 'RequestController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de requests
        Route::get('get-requests', 'RequestController@all')->name('all');

        // Ruta que obtiene todos los registros de requests
        Route::get('get-working-hours', 'HolidayCalendarController@allWorkingHours');

        // Ruta que obtiene todos los registros de requests
        Route::get('export-VIG/{requestId}', 'RequestController@exportFormatVIG');

        // Ruta que obtiene todos los registros de requests
        Route::get('operators/requirements/{requirementType}', 'RequestController@getOperatorsByRequirements');

        // Ruta que obtiene todos los registros de requests
        Route::get('operators/requirements', 'RequestController@getOperators');

        // Ruta para exportar los datos de requests
        Route::post('export-requests', 'RequestController@export')->name('export');

        // Ruta de la gestion de calendario laboral
        Route::resource('holiday-calendars', 'HolidayCalendarController', ['only' => [
            'index', 'show', 'store', 'update', 'destroy'
        ]]);

        // Ruta para la gestion de knowledgeBases
        Route::resource('knowledge-bases', 'KnowledgeBaseController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        
        // Ruta que obtiene todos los registros de knowledgeBases
        Route::get('get-knowledge-bases', 'KnowledgeBaseController@all')->name('all');

        // Ruta para exportar los datos de knowledgeBases
        Route::post('export-knowledge-bases', 'KnowledgeBaseController@export')->name('export');

        // Ruta para la gestion de request-annotations
        Route::resource('annotations', 'RequestAnnotationController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene todos los registros de request-annotations
        Route::get('get-request-annotations', 'RequestAnnotationController@all')->name('all');

        Route::post('request-annotations/{ci}', ['as' => 'annotations.store', 'uses' => 'RequestAnnotationController@store']);

        Route::post('request/read/annotations/{requestId}', 'RequestController@readAnnotationByRequestId');

        // Ruta para exportar los datos de request-annotations
        Route::post('export-request-annotations', 'RequestAnnotationController@export')->name('export');
    });
});
