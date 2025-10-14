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
Route::prefix('pqrs')->group(function() {
    // Ruta para la gestion de p-q-r-s del ciudadano anónimo
    Route::resource('p-q-r-s-ciudadano-anonimo', 'PQRAnonimoController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de p-q-r-s del ciudadano anónimo
    Route::get('get-p-q-r-s-ciudadano-anonimo', 'PQRAnonimoController@all')->name('all');

    // Ruta que obtiene todos los registros de p-q-r-tipo-solicituds
    Route::get('get-p-q-r-anonimo-tipo-solicituds-radicacion', 'PQRTipoSolicitudController@allRadicacion')->name('allRadicacion');

    // Muestra el index que tiene el iframe del sitio anterior de PQRS para los ciudadanos anónimos
    Route::get('p-q-r-s-ciudadano-anonimo-repository', 'PQRAnonimoController@indexRepositorioCiudadanoAnonimo')->name('indexRepositorioCiudadanoAnonimo');

    // Ruta para la gestion de survey-satisfaction-pqrs
    Route::resource('survey-satisfaction-pqrs', 'SurveySatisfactionPqrController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de survey-satisfaction-pqrs
    Route::get('get-survey-satisfaction-pqrs', 'SurveySatisfactionPqrController@all')->name('all');
    // Ruta para exportar los datos de survey-satisfaction-pqrs
    Route::post('export-survey-satisfaction-pqrs', 'SurveySatisfactionPqrController@export')->name('export');

    // Ruta para guardar la respuesta del ciudadano
    Route::put('p-q-r-s-answer', 'PQRController@answerPqrs');

    
    //Ruta para leer las pqr 
    Route::get('watch-archives', 'PQRController@watchDocument');
});

Route::prefix('pqrs')->middleware(['auth'])->group(function() {
    Route::get('/', 'PQRController@index');

    // Obtiene todos los datos de una constante dependiendo de nombre
    Route::get('get-constants/{name}', 'UtilController@getConstants');

    Route::post('p-q-r-s-delete-file/{id}', 'PQRController@updateFile');

    // Ruta para la gestion de p-q-r-tipo-solicituds
    Route::resource('p-q-r-tipo-solicituds', 'PQRTipoSolicitudController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de p-q-r-tipo-solicituds
    Route::get('get-p-q-r-tipo-solicituds', 'PQRTipoSolicitudController@all')->name('all');
    // Ruta para exportar los datos de p-q-r-tipo-solicituds

    // Ruta que obtiene todos los registros de p-q-r-tipo-solicituds
    Route::get('get-p-q-r-tipo-solicituds-radicacion', 'PQRTipoSolicitudController@allRadicacion')->name('allRadicacion');

    Route::post('export-p-q-r-tipo-solicituds', 'PQRTipoSolicitudController@export')->name('export');

    // Ruta para la gestion de p-q-r-eje-tematicos
    Route::resource('p-q-r-eje-tematicos', 'PQREjeTematicoController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de p-q-r-eje-tematicos
    Route::get('get-p-q-r-eje-tematicos', 'PQREjeTematicoController@all')->name('all');

    // Ruta que obtiene todos los registros de p-q-r-eje-tematicos para el formulario de radicación de PQRS
    Route::get('get-p-q-r-eje-tematicos-radicacion', 'PQREjeTematicoController@allRadicacion')->name('allRadicacion');

    // Ruta para exportar los datos de p-q-r-eje-tematicos
    Route::post('export-p-q-r-eje-tematicos', 'PQREjeTematicoController@export')->name('export');

    // Ruta que obtiene todos los registros de p-q-r-eje-tematicos
    Route::get('get-dependencies', 'PQREjeTematicoController@obtener_dependencias')->name('obtener_dependencias');

    // Ruta para la gestion de p-q-r-s
    Route::resource('p-q-r-s', 'PQRController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de p-q-r-s
    Route::get('get-p-q-r-s', 'PQRController@all')->name('all');

    Route::get('get-only-users-pqrs', 'PQRController@getOnlyPqrs')->name('all');
    //Ruta en donde se obtiene los usuarios administradores de pqrs
    Route::get('get-admin-users-pqrs', 'PQRController@getUserAdminPqrs');
    // Ruta para exportar los datos de p-q-r-s
    Route::post('export-p-q-r-s', 'PQRController@export')->name('export');
    // Ruta para exportar los datos del historial del p-q-r-s
    Route::post('export-historial-p-q-r-s/{id}', 'PQRController@exportHistorial')->name('exportHistorial');

     // Ruta para exportar los datos de p-q-r-s
     Route::post('export-report-avanzado-p-q-r-s', 'PQRController@exportReportAvanzado');

    // Ruta para obtener la información del pqr según el id por parámetro. Se usa para las entradas recientes del dashboard
    Route::get('get-p-q-r-s-show-dashboard/{id}', 'PQRController@showFromDashboard');

    // Ruta para la gestion de p-q-r-s del ciudadano
    Route::resource('p-q-r-s-ciudadano', 'PQRController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de p-q-r-s del ciudadano
    Route::get('p-q-r-s-ciudadano', 'PQRController@indexCiudadano')->name('p-q-r-s-ciudadano');

    // Ruta para asignar el destacado o no destacado a un PQR
    Route::post('destacado-pqr/{pqr_id}/{opcion_seleccionada}', 'PQRController@destacado')->name('destacado');

    // Ruta para asignar el leido a un PQR
    Route::post('leido-pqr/{pqr_id}', 'PQRController@leidoPQR')->name('leidoPQR');

    // Ruta para compartir la pqrs
    Route::put('PQRS-share', 'PQRController@pqrShare');

    // Ruta de la gestion de calendario laboral
    Route::resource('holiday-calendars', 'HolidayCalendarController', ['only' => [
        'index', 'show', 'store', 'update', 'destroy'
    ]]);
    // Route::get('get-holiday-calendars', 'HolidayCalendarController@all')->name('all');
    // Obtiene los datos del calendario laboral
    Route::get('get-working-hours', 'HolidayCalendarController@allWorkingHours');
    // Route::post('export-holiday-calendars', 'HolidayCalendarController@export')->name('export');

    Route::get('get-tablero-consolidado', 'PQRController@obtenerTableroConsolidado');

    //Ruta para la migracion de las contingencias PQR
    Route::post('migration-modal', 'PQRController@MigratePqr');
    // Ruta para la gestion de p-q-r-anotacions
    Route::resource('p-q-r-anotacions', 'PQRAnotacionController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Reescribe la función store para guardar la anotación a partir del ID del PQR recibido por parámetro
    Route::post('p-q-r-anotacions/{ci}', ['as' => 'p-q-r-anotacions.store', 'uses' => 'PQRAnotacionController@store']);
    // Ruta que obtiene todos los registros de p-q-r-anotacions
    Route::get('get-p-q-r-anotacions/{ci}', 'PQRAnotacionController@all')->name('all');
    // Ruta para exportar los datos de p-q-r-anotacions
    Route::post('export-p-q-r-anotacions', 'PQRAnotacionController@export')->name('export');

    // Ruta para asignar el leido a una antación de PQR
    Route::post('leido-anotacion-pqr/{pqr_anotacion_id}', 'PQRAnotacionController@leidoAnotacionPQR')->name('leidoAnotacionPQR');

    // Ruta para la gestion de ciudadanos desde el módulo de la intranet
    Route::resource('citizens', '\Modules\Intranet\Http\Controllers\CitizenController', ['only' => [
        'store', 'update', 'destroy'
    ]]);
    // Ruta para el index de ciudadanos desde el módulo de la intranet
    Route::get('citizens-pqr', '\Modules\Intranet\Http\Controllers\CitizenController@index')->name('citizens-pqr.index');
    // Ruta para la gestion de ciudadanos
    Route::get('get-citizens', '\Modules\Intranet\Http\Controllers\CitizenController@all')->name('all');


    // Actualiza la fecha de vencimiento de los PQRS según el calendario de días no hábiles
    Route::post('update-fecha-vence', 'PQRController@modificarFechaVencimiento')->name('modificarFechaVencimiento');

    // Muestra el index que tiene el iframe del sitio anterior de PQRS
    Route::get('repository-pqr', 'PQRController@indexRepositorio')->name('indexRepositorio');

    Route::post('export-repository-pqr', 'PQRController@exportRepositoryPqr');

    // Muestra el index que tiene el iframe del sitio anterior de PQRS para los ciudadanos
    Route::get('p-q-r-s-ciudadano-repository', 'PQRController@indexRepositorioCiudadano')->name('indexRepositorioCiudadano');

    // Ruta que obtiene todos los registros de joomla de las tablas anterios de p-q-r-s
    Route::get('get-p-q-r-s-repository', 'PQRController@allRepositoryPQR');

    //Ruta para ejecutar traer las anotaciones de la externa
    Route::get('get-annotations/{id}','PQRController@getAnnotation');

    // Obtiene todos los datos de una constante dependiendo de nombre
    Route::post('dias-restantes/{tipo}/{plazo}/{estado}/{fechafin}', 'UtilController@diasRestantes');

    Route::get('import-data', 'UtilController@importPQR');

    Route::get('import-data-id', 'UtilController@importDataIdCorresponden');

    // Ruta para exportar el reporte avanzado por tipos de adjuntos de respuesta de PQRSD
    Route::post('export-report-avanzado-tipo-adjunto', 'PQRController@exportReporteTipoAdjunto');

    // Ruta para relacionar los adjuntos de respuesta del PQRSD finalizados
    Route::post('p-q-r-s-relacionar-adjunto-respuesta', 'PQRController@relacionarAdjuntoRespuesta');


    // Ruta que obtiene todos los registros de joomla de las tablas anterios de p-q-r-s-ciudadano
    Route::get('get-p-q-r-s-repository-ciudadano', 'PQRController@allRepositoryPQRCiudadano');



});

