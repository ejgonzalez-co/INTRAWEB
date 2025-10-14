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
Route::group(['prefix' => 'work-histories', 'middleware' => ['auth']], function () {

    Route::get('/', function () {
        dd('This is the Workhistories module index page. Build something great!');
    });

    Route::resource('work-histories-actives', 'WorkHistoriesActiveController', ['only' => [
        'index', 'show' , 'store', 'update', 'destroy' 
    ]]);
    Route::get('get-work-histories-actives', 'WorkHistoriesActiveController@all')->name('all');
    
    Route::post('export-work-histories-actives', 'WorkHistoriesActiveController@export')->name('export');
        

    Route::get('generate-document/{id}', 'WorkHistoriesActiveController@showDocument')->name('generate-document.showDocument');


    Route::resource('configuration-documents', 'ConfigurationDocumentsController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-configuration-documents', 'ConfigurationDocumentsController@all')->name('all');

    Route::get('get-configuration-documents-active', 'ConfigurationDocumentsController@getConfigurationDocumentsActive');

    Route::post('export-configuration-documents', 'ConfigurationDocumentsController@export')->name('export');



    Route::resource('documents', 'DocumentsController', ['only' => [
        'index', 'update', 'destroy'
    ]]);

    Route::post('documents/{wh}', ['as' => 'documents.store', 'uses' => 'DocumentsController@store']);


    Route::get('get-documents/{wh}', 'DocumentsController@all')->name('all');
    
    Route::post('export-documents', 'DocumentsController@export')->name('export');

    

    Route::resource('news', 'NewsHistoriesController', ['only' => [
        'index', 'update', 'destroy'
    ]]);

    Route::post('news/{wh}', ['as' => 'documents.store', 'uses' => 'NewsHistoriesController@store']);


    Route::get('get-news/{wh}', 'NewsHistoriesController@all')->name('all');
    
    Route::post('export-news', 'NewsHistoriesController@export')->name('export');


    Route::get('export-excel/{wh}', 'WorkHistoriesActiveController@exportFromView')->name('exportFromView');

    
    //workHistPensioners

        
    Route::resource('config-doc-pensioners', 'configDocPensionersController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-config-doc-pensioners', 'configDocPensionersController@all')->name('all');

    Route::post('export-config-doc-pensioners', 'configDocPensionersController@export')->name('export');

    Route::get('get-conf-doc-pensioner', 'configDocPensionersController@getconfigDocPensionersActive');


    Route::resource('work-hist-pensioners', 'WorkHistPensionerController', ['only' => [
        'index', 'store', 'update', 'destroy', 'show'
    ]]);
    Route::get('get-work-hist-pensioners', 'WorkHistPensionerController@all')->name('all');
    
    Route::post('export-work-hist-pensioners', 'WorkHistPensionerController@export')->name('export');

    Route::get('generate-document-pen/{id}', 'WorkHistPensionerController@showDocument')->name('generate-document-pen.showDocument');

    Route::get('export-excel-pen/{wh}', 'WorkHistPensionerController@exportFromView')->name('exportFromView');


    Route::resource('documents-pensioners', 'DocumentsPensionersController', ['only' => [
        'index', 'update', 'destroy'
    ]]);

    Route::post('documents-pensioners/{wh}', ['as' => 'documents-pensioners.store', 'uses' => 'DocumentsPensionersController@store']);

    //Route::post('documents-pensioners/{wh}', 'DocumentsPensionersController@store');

    Route::get('get-documents-pensioners/{wh}', 'DocumentsPensionersController@all')->name('all');
    
    Route::post('export-documents-pensioners', 'DocumentsPensionersController@export')->name('export');



    Route::resource('news-histories-pen', 'NewsHistoriesPenController', ['only' => [
        'index', 'update', 'destroy'
    ]]);

    Route::post('news-histories-pen/{wh}', ['as' => 'news-histories-pen.store', 'uses' => 'NewsHistoriesPenController@store']);


    Route::get('get-news-histories-pen/{wh}', 'NewsHistoriesPenController@all')->name('all');
    
    Route::post('export-news-histories-pen', 'NewsHistoriesPenController@export')->name('export');

    
    //substitutes

    Route::resource('substitutes', 'SubstituteController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-substitutes', 'SubstituteController@all')->name('all');
    
    Route::post('export-substitutes', 'SubstituteController@export')->name('export');
    

    Route::get('get-deceased', 'WorkHistPensionerController@getDeceased');
    Route::get('get-deceased-cp', 'QuotaPartsPensionerController@getDeceasedCp');


    //documents 
    /*
    Route::resource('documents-substitutes', 'DocumentsSubstituteController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-documents-substitutes', 'DocumentsSubstituteController@all')->name('all');
    
    Route::post('export-documents-substitutes', 'DocumentsSubstituteController@export')->name('export');
    
    */

    Route::resource('documents-substitutes', 'DocumentsSubstituteController', ['only' => [
        'index', 'update', 'destroy'
    ]]);

    Route::post('documents-substitutes/{wh}', ['as' => 'documents-substitutes.store', 'uses' => 'DocumentsSubstituteController@store']);

    Route::get('get-documents-substitutes/{wh}', 'DocumentsSubstituteController@all')->name('all');
    
    Route::post('export-documents-substitutes', 'DocumentsSubstituteController@export')->name('export');



    //cuotas partes

    Route::resource('quota-parts-pensioners', 'QuotaPartsPensionerController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-quota-parts-pensioners', 'QuotaPartsPensionerController@all')->name('all');
    
    Route::post('export-quota-parts-pensioners', 'QuotaPartsPensionerController@export')->name('export');
    
    

    
    Route::resource('quota-parts', 'QuotaPartsController', ['only' => [
        'index', 'update', 'destroy'
    ]]);

    Route::post('quota-parts/{wh}', ['as' => 'quota-parts.store', 'uses' => 'QuotaPartsController@store']);

    Route::get('get-quota-parts/{wh}', 'QuotaPartsController@all')->name('all');
    
    Route::post('export-quota-parts', 'QuotaPartsController@export')->name('export');


    
    Route::resource('quota-parts-doc-pensioners', 'QuotaPartsDocPensionersController', ['only' => [
        'index', 'update', 'destroy'
    ]]);

    Route::post('quota-parts-doc-pensioners/{wh}', ['as' => 'quota-parts-doc-pensioners.store', 'uses' => 'QuotaPartsDocPensionersController@store']);

    Route::get('get-quota-parts-doc-pensioners/{wh}', 'QuotaPartsDocPensionersController@all')->name('all');
    
    Route::post('export-quota-parts-doc-pensioners', 'QuotaPartsDocPensionersController@export')->name('export');
    


    Route::resource('quota-parts-news-users', 'QuotaPartsNewsUsersController', ['only' => [
        'index', 'update', 'destroy'
    ]]);

    Route::post('get-quota-parts-news-users/{wh}', ['as' => 'get-quota-parts-news-users.store', 'uses' => 'QuotaPartsNewsUsersController@store']);

    Route::get('get-quota-parts-news-users/{wh}', 'QuotaPartsNewsUsersController@all')->name('all');
    
    Route::post('export-quota-parts-news-users', 'QuotaPartsNewsUsersController@export')->name('export');



    Route::resource('family', 'FamilyController', ['only' => [
        'index', 'update', 'destroy'
    ]]);

    Route::post('family/{wh}', ['as' => 'family.store', 'uses' => 'FamilyController@store']);

    Route::get('get-family/{wh}', 'FamilyController@all')->name('all');
    
    Route::post('export-family', 'FamilyController@export')->name('export');   

    
    
    Route::resource('family-pensioners', 'FamilyPensionerController', ['only' => [
        'index', 'update', 'destroy'
    ]]);

    Route::post('family-pensioners/{wh}', ['as' => 'family.store', 'uses' => 'FamilyPensionerController@store']);

    Route::get('get-family-pensioners/{wh}', 'FamilyPensionerController@all')->name('all');
    
    Route::post('export-family-pensioners', 'FamilyPensionerController@export')->name('export');   
    
/*

    Route::resource('newsHistoriesPens', 'NewsHistoriesPenController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-newsHistoriesPens', 'NewsHistoriesPenController@all')->name('all');
    
    Route::post('export-newsHistoriesPens', 'NewsHistoriesPenController@export')->name('export');
    


    Route::resource('documents-pensioners', 'DocumentsPensionersController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-documentsPensioners', 'DocumentsPensionersController@all')->name('all');
    
    Route::post('export-documentsPensioners', 'DocumentsPensionersController@export')->name('export');
    */

    // Ruta para la gestion de workRequests
    Route::resource('work-request', 'WorkRequestController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de workRequests
    Route::get('get-work-request', 'WorkRequestController@all')->name('all');
    // Ruta para exportar los datos de workRequests
    Route::post('export-workRequests', 'WorkRequestController@export')->name('export');

    //Ruta envia hojas de vida filtradas
    Route::get('get-users-work', 'WorkRequestController@getUserWork');

    //Ruta envia hojas de vida pensionados filtradas
    Route::get('get-pensioner-work', 'WorkRequestController@getUserPensionary');

    //Ruta envia hojas de vida pensionados filtradas
    Route::post('get-cancel-request', 'WorkRequestController@cancelRequest');

    //Ruta envia hojas de vida pensionados filtradas
    Route::post('get-approbed-request', 'WorkRequestController@approbRequest');

});
