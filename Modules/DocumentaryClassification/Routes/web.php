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

    Route::group(['prefix' => 'documentary-classification'], function () {
        // Route::get('/', function () {
        //     dd('This is the documentaryClassification module index page. Build something great!');
        // });

        // Ruta para la gestion de typeDocumentaries
        Route::resource('type-documentaries', 'typeDocumentariesController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de typeDocumentaries
        Route::get('get-type-documentaries', 'typeDocumentariesController@all')->name('all');
        // Ruta para exportar los datos de typeDocumentaries
        Route::post('export-typeDocumentaries', 'typeDocumentariesController@export')->name('export');
        //Ruta que obtiene los registros mediante input e typeDocuments
        Route::get('get-type-documentaries-all-request','typeDocumentariesController@all_request')->name('all-request');


        // Ruta para la gestion de seriesSubSeries
        Route::resource('series-subseries', 'seriesSubSeriesController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de seriesSubSeries
        Route::get('get-series-subseries', 'seriesSubSeriesController@all')->name('all');
        // Ruta para exportar los datos de seriesSubSeries
        Route::post('export-seriesSubSeries', 'seriesSubSeriesController@export')->name('export');
        //Ruta para obtener valores de series para subseries
        Route::get('get-series','seriesSubSeriesController@all_series')->name('all-series');
        //ruta para obtener serie mediante filtro
        Route::get('get-series-all','seriesSubSeriesController@all_series_dependencias')->name('all_series_dependencias');
        //ruta para obtener subserie mediante filtro
        Route::get('get-subseries-all','seriesSubSeriesController@all_subseries_dependencias')->name('all_subseries_dependencias');

        // Ruta para obtener las subseries dependiendo de la serie, esta ruta aplica para la clasificación documental
        Route::get('get-subseries-clasificacion','seriesSubSeriesController@get_subseries_clasificacion')->name('get-subseries-clasificacion');

        // Obtiene las subseries que están habilitadas para incluir expedientes
        Route::get('get-subseries-clasificacion-expediente','seriesSubSeriesController@get_subseries_clasificacion_expediente')->name('get-subseries-get_subseries_clasificacion_expediente');

        // Ruta para series y suberies de dependencias
        Route::get('get-inventory-documentals-serie-subserie/{id}', 'seriesSubSeriesController@getSeriesSubseriesToDependency')->name('inventoryDocumentals-serie-subserie');

        Route::get('get-inventory-documentals-serie-dependency/{id}', 'seriesSubSeriesController@getSeriesToDependency');

        // Obtiene las series que están habilitadas para incluir expedientes
        Route::get('get-inventory-documentals-serie-dependency-expediente/{id}', 'seriesSubSeriesController@getSeriesToDependencyExpediente');

        // Ruta para la gestion de inventoryDocumentals
        Route::resource('inventory-documentals', 'inventoryDocumentalController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de inventoryDocumentals
        Route::get('get-inventory-documentals', 'inventoryDocumentalController@all')->name('all');
        // Ruta para exportar los datos de inventoryDocumentals
        Route::post('export-inventoryDocumentals', 'inventoryDocumentalController@export')->name('export');
        // Ruta para exportar los datos del reporte inventario
        Route::post('export-inventory-documentals-report', 'inventoryDocumentalController@export_report')->name('export_report');
        //ruta para encontrar documentos digitales en metadata por su id de inventario
        Route::get('get-document-metadata/{id}','inventoryDocumentalController@get_documents_metadata')->name('get_documents_metadata');
        //Ruta example
        // Route::get('export-exaple','inventoryDocumentalController@export_example')->name('export_example');


        // Ruta para la gestion de dependencias
        Route::resource('dependencias', 'dependenciasController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de dependencias
        Route::get('get-dependencias', 'dependenciasController@all')->name('all');
        //Ruta par obtener dependencias con relaciones
        Route::get('get-dependencias-inventory','dependenciasController@all_dependencias_inventory')->name('all_dependencias_inventory');
        // Ruta para exportar los datos de dependencias
        Route::post('export-dependencias', 'dependenciasController@export')->name('export');
        // Ruta para exportar los datos de dependencias
        Route::get('export-dependencias-specific-all', 'dependenciasController@export_dependencia_specific')->name('eexport_dependencia_specific');
        //Ruta para gestionar series y subseries
        Route::post('dependencia-series-subseries','dependenciasController@store')->name('store');

        // Ruta para la gestion de documentarySerieSubseries
        Route::resource('documentary-series-subseries', 'documentarySerieSubseriesController', ['only' => [
            'index', 'store', 'update', 'destroy','all_specific'
        ]]);
        // Ruta que obtiene todos los registros de documentarySerieSubseries
        Route::get('get-documentarySerieSubseries', 'documentarySerieSubseriesController@all')->name('all');
        // Ruta que obtiene todos los registros de de tipos de documentos por su id
        Route::get('get-documentary-serie-subseries-all-specific','documentarySerieSubseriesController@all_specific')->name('all_specific');
        // Ruta para exportar los datos de documentarySerieSubseries
        Route::post('export-documentarySerieSubseries', 'documentarySerieSubseriesController@export')->name('export');

        // Ruta para la gestion de dependenciasSerieSubseries
        Route::resource('dependencias-serie-subseries', 'dependenciasSerieSubseriesController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de dependenciasSerieSubseries
        Route::get('get-dependenciasSerieSubseries', 'dependenciasSerieSubseriesController@all')->name('all');
        // Ruta para exportar los datos de dependenciasSerieSubseries
        Route::post('export-dependenciasSerieSubseries', 'dependenciasSerieSubseriesController@export')->name('export');
        //Ruta para optener series y subseries especificos de la dependencia
        Route::get('get-dependencias-series-subseries-request-all', 'dependenciasSerieSubseriesController@dependencias_request_all')->name('dependencias_request_all');

        Route::get('/show-pdf/{id}', 'PDFController@showPdf')->name('show-pdf');
        Route::get('/get-pdf/{id}', 'PDFController@getPdf')->name('get-pdf');
        Route::patch('/update-metadata/{id}', 'PDFController@updateMetadata')->name('update-metadata');



        Route::get('get-documents/{id}','inventoryDocumentalController@getDocuments');

        //Ruta para ir a la vista de documentos inventario documental
        Route::get('documentos-serie-subseries', 'inventoryDocumentalController@indexDocumentos')->name('indexDocumentos');

        //Obtiene todos los registros del inventario documental
        Route::get('get-documentos-inventario-documental','inventoryDocumentalController@getAllDocumentosInventario');

        // Ruta para exportar los datos de documentarySerieSubseries
        Route::post('export-second-view-typeDocumentaries', 'inventoryDocumentalController@exportSecondView')->name('exportSecondView');

        Route::post('export-second-view-inventoryDocumentals', 'inventoryDocumentalController@exportSecondView')->name('exportSecondView');

        // Ruta para la gestion de criterios-busquedas
        Route::resource('criterios-busquedas', 'criteriosBusquedaController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de criterios-busquedas
        Route::get('get-criterios-busquedas', 'criteriosBusquedaController@all')->name('all');
        // Ruta para exportar los datos de criterios-busquedas
        Route::post('export-criterios-busquedas', 'criteriosBusquedaController@export')->name('export');

        Route::post('add-serie-subserie','criteriosBusquedaController@addSerieSubserie');

        Route::post('adds-series-subseries','criteriosBusquedaController@addsSeriesSubseries');

        Route::post('save-criterios-busqueda', 'criteriosBusquedaController@store');

        Route::post('edit-criterios-busqueda', 'criteriosBusquedaController@update');

        Route::post('consult-data-index', 'criteriosBusquedaController@consultDataIndex');

    });

});
