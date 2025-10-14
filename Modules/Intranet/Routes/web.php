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
Route::group(['prefix' => 'intranet', 'middleware' => ['auth']], function () {

    // Obtiene todos los datos de una constante dependiendo de nombre
    Route::get('get-constants/{name}', 'UtilController@getConstants');

    // Obtiene lo roles disponibles
    Route::get('/get-roles', 'UtilController@roles')->name('get-roles');

    Route::get('/', function () {
        dd('This is the Intranet module index page. Build something great!');
    });
    Route::resource('users', 'UserController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-users', 'UserController@all')->name('all');

    Route::get('get-users-dependencia/{id}', 'UserController@getFunctionariesDependencia');


    Route::get('get-users-order', 'UserController@allOrderName')->name('allOrderName');

    Route::get('get-ciudadanos', 'UserController@ciudadanos')->name('ciudadanos');

    Route::post('export-users', 'UserController@export')->name('export');

    Route::get('get-functionaries', 'UserController@getFunctionaries');

    Route::get('get-citizens-users', 'UserController@getCitizens');


    Route::resource('positions', 'PositionController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-positions', 'PositionController@all')->name('all');

    Route::post('export-positions', 'PositionController@export')->name('export');


    Route::resource('dependencies', 'DependencyController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-dependencies', 'DependencyController@all')->name('all');

    Route::post('export-dependencies', 'DependencyController@export')->name('export');


    Route::resource('headquarters', 'HeadquarterController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-headquarters', 'HeadquarterController@all')->name('all');

    Route::post('export-headquarters', 'HeadquarterController@export')->name('export');


    Route::resource('work-groups', 'WorkGroupController', ['only' => [
        'index', 'store', 'update', 'destroy', 'show'
    ]]);
    Route::get('get-work-groups', 'WorkGroupController@all')->name('all');

    Route::post('export-work-groups', 'WorkGroupController@export')->name('export');

    // Ruta para obtener la vista de grupos de trabajo para el calendario de eventos
    Route::get('work-groups-calendar', 'WorkGroupController@index')->name('work-groups-calendar.index');

    // Ruta para la gestion de tipo de eventos
    Route::resource('type-events', 'TypeEventController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de tipo de eventos
    Route::get('get-type-events', 'TypeEventController@all')->name('all');
    // Ruta que obtiene todos los registros de tipo de eventos según el estado activo = 1
    Route::get('get-type-events-condition', 'TypeEventController@allCondition')->name('allCondition');
    // Ruta para exportar los datos de tipo de eventos
    Route::post('export-type-events', 'TypeEventController@export')->name('export');

    // Ruta para la gestion de eventos
    Route::resource('events', 'EventController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de eventos
    Route::get('get-events', 'EventController@all')->name('all');
    // Ruta para exportar los datos de eventos
    Route::post('export-events', 'EventController@export')->name('export');
    // Ruta para ver el calendario de eventos
    Route::get('calendar-events', 'EventController@calendarEvents')->name('calendar-events');
    // Ruta para confirmar el evento
    Route::get('confirm-attendance-event/{event_id}/{confirmation}', 'EventController@confirmAttendanceEvent')->name('confirm-attendance-event');

    // Ruta para la gestion de categories
    Route::resource('categories', 'CategoryController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de categories
    Route::get('get-categories', 'CategoryController@all')->name('all');
    // Ruta para exportar los datos de categories
    Route::post('export-categories', 'CategoryController@export')->name('export');


    // Ruta para la gestion de tags
    Route::resource('tags', 'TagController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de tags
    Route::get('get-tags', 'TagController@all')->name('all');
    // Ruta para exportar los datos de tags
    Route::post('export-tags', 'TagController@export')->name('export');


    // Ruta para la gestion de posts
    Route::resource('posts', 'PostController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de posts
    Route::get('get-posts', 'PostController@all')->name('all');
    // Ruta para exportar los datos de posts
    Route::post('export-posts', 'PostController@export')->name('export');

    // ********* Inicio de rutas para el componente de descargas *********
    Route::resource('licenses', 'LicenseController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-licenses', 'LicenseController@all')->name('all');

    Route::post('export-licenses', 'LicenseController@export')->name('export');

    Route::resource('download-categories', 'DownloadCategorieController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-download-categories', 'DownloadCategorieController@all')->name('all');

    Route::post('export-download-categories', 'DownloadCategorieController@export')->name('export');

    Route::resource('downloads-tags', 'DownloadsTagsController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-downloads-tags', 'DownloadsTagsController@all')->name('all');

    Route::post('export-downloads-tags', 'DownloadsTagsController@export')->name('export');

    Route::resource('downloads', 'DownloadController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-downloads', 'DownloadController@all')->name('all');

    Route::post('export-downloads', 'DownloadController@export')->name('export');

    Route::post('downloads-hits', 'DownloadController@incrementHits');

    Route::resource('download-file-votes', 'DownloadFileVoteController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    Route::get('get-download-file-votes', 'DownloadFileVoteController@all')->name('all');

    Route::post('export-download-file-votes', 'DownloadFileVoteController@export')->name('export');
    // ********* Fin de rutas para el componente de descargas *********

    /*Rutas de encuestas de satisfaccion*/
    Route::resource('polls', 'PollController', ['only' => [
        'index', 'store', 'update', 'destroy', 'show'
    ]]);
    Route::put('publish-polls', 'PollController@publishPolls');
    Route::get('get-polls', 'PollController@all')->name('all');
    Route::post('export-polls', 'PollController@export')->name('export');
    Route::get('get-users-poll', 'PollController@getUsers')->name('getUsers');
    Route::get('get-work-groups-poll', 'PollController@getWorkGroup')->name('getWorkGroup');

    //Rutas de preguntas
    Route::resource('poll-questions', 'PollQuestionController', ['only' => [
        'index', 'update', 'destroy'
    ]]);
    Route::post('poll-questions/{poll}', ['as' => 'pollquestions.store', 'uses' => 'PollQuestionController@store']);
    Route::get('get-poll-questions/{poll}', 'PollQuestionController@all')->name('all');
    Route::get('get-poll-questions-order/{poll}', 'PollQuestionController@allOrder')->name('allOrder');
    Route::post('export-poll-questions', 'PollQuestionController@export')->name('export');

    //Rutas de respuestas
    Route::resource('poll-answers', 'PollAnswerController', ['only' => [
        'index', 'update', 'destroy'
    ]]);
    Route::post('poll-answers/{poll}', ['as' => 'pollanswer.store', 'uses' => 'PollAnswerController@store']);
    Route::get('get-poll-answers/{poll}', 'PollAnswerController@all')->name('all');
    Route::get('export-poll-answers/{poll}', 'PollAnswerController@export')->name('export');
    Route::get('export-poll-answers-questions/{poll}', 'PollAnswerController@exportQuestions')->name('exportQuestions');


    // Ruta para obtener la vista de grupos de trabajo para el calendario de eventos
    Route::get('work-groups-poll', 'WorkGroupController@index')->name('work-groups-poll.index');

    // Ruta para la gestion de notices
    Route::resource('notices', 'NoticeController', ['only' => [
        'index', 'store', 'update', 'destroy', 'show'
    ]]);
    Route::get('notices-all', 'NoticeController@indexAll')->name('notices-all.index');
    Route::get('get-notices-public', 'NoticeController@allPublic')->name('allPublic');
    // Ruta que obtiene las primeras 6 noticias publicadas
    Route::get('get-notices-public-dashboard', 'NoticeController@allPublicDashboard')->name('allPublicDashboard');

    // Ruta que obtiene todos los registros de notices
    Route::get('get-notices', 'NoticeController@all')->name('all');
    // Ruta para exportar los datos de notices
    Route::post('export-notices', 'NoticeController@export')->name('export');


    Route::get('notices-public', 'NoticeController@homePanel')->name('noticesPublic');

    Route::get('events-public', 'EventController@homePanel')->name('eventsPublic');

    Route::get('downloads-public', 'DownloadController@homePanel')->name('downloadsPublic');

    Route::get('polls-public', 'PollController@homePanel')->name('pollsPublic');

    Route::get('consulta-buscador', 'NoticeController@buscador')->name('consultaBuscador');

    Route::get('get-registro-consulta-notices-all', 'NoticeController@registroConsultaNotices')->name('registroConsultaNotices');
    Route::get('get-registro-consulta-events', 'NoticeController@registroConsultaEvents')->name('registroConsultaEvents');
    Route::get('get-registro-consulta-downloads', 'NoticeController@registroConsultaDownloads')->name('registroConsultaDownloads');
    Route::get('get-registro-consulta-polls', 'NoticeController@registroConsultaPolls')->name('registroConsultaPolls');

    // Ruta para la gestion de citizens
    Route::resource('citizens', 'CitizenController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de citizens
    Route::get('get-citizens', 'CitizenController@all')->name('all');
    // Ruta para exportar los datos de citizens
    Route::post('export-citizens', 'CitizenController@export')->name('export');

});
