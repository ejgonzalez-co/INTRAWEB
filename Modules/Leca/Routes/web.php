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

Route::group(['prefix' => 'leca'], function () {

    Route::middleware(['auth', 'verified'])->group(function () {

        Route::get('/get-roles', 'UtilController@roles')->name('get-roles');
        Route::get('/', function () {
            dd('This is the Leca module index page. Build something great!');
        });

        // Ruta para la gestion de customers
        Route::resource('customers', 'CustomersController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        //Ruta que obtiene los puntos de muestra
        Route::get('get-points', 'CustomersController@getPoints');
        // Ruta que obtiene todos los registros de customers
        Route::get('get-customers', 'CustomersController@all')->name('all');
        // Ruta para exportar los datos de customers
        Route::post('export-customers', 'CustomersController@export')->name('export');
        // Obtiene todos los datos de una constante dependiendo de nombre
        Route::get('get-constants', 'CustomersController@getConstants');
        //Ruta para actualizar los estados de los clientes
        Route::put('change-status', 'CustomersController@changeStatusCustomers');

        // Ruta para la gestion de samplePoints
        Route::resource('sample-points', 'SamplePointsController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de samplePoints
        Route::get('get-sample-points', 'SamplePointsController@all')->name('all');
        // Ruta para exportar los datos de samplePoints
        Route::post('export-sample-points', 'SamplePointsController@export')->name('export');

        // Ruta para la gestion de officials
        Route::resource('officials', 'OfficialsController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de officials
        Route::get('get-officials', 'OfficialsController@all')->name('all');
        // Ruta para exportar los datos de officials
        Route::post('export-officials', 'OfficialsController@export')->name('export');
        //Ruta para inactivar al funcionario
        Route::put('state-officials-inactive', 'OfficialsController@inactivateOfficial');
        //Ruta para activar al funcionario
        Route::put('state-officials-activate', 'OfficialsController@activateOfficial');
        //Ruta para Habilitar recepcionista
        Route::put('state-receptionist-activate', 'OfficialsController@enableReceptionist');
        //Ruta para inhabilitar el recepcionista
        Route::put('state-receptionist-disable', 'OfficialsController@disableReceptionist');

        // Ruta para la gestion de monthlyRoutines
        Route::resource('monthly-routines', 'MonthlyRoutinesController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de monthlyRoutines
        Route::get('get-monthly-routines', 'MonthlyRoutinesController@all')->name('all');
        // Ruta para exportar los datos de monthlyRoutines
        Route::post('export-monthly-routines', 'MonthlyRoutinesController@export')->name('export');

        // Ruta para la gestion de weeklyRoutines
        Route::resource('weekly-routines', 'WeeklyRoutinesController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de weeklyRoutines
        Route::get('get-weekly-routines', 'WeeklyRoutinesController@all')->name('all');
        // Ruta para exportar los datos de weeklyRoutines
        Route::post('export-weekly-routines', 'WeeklyRoutinesController@export')->name('export');
        //Ruta para obtener los funcionarios de la rutina mensual
        Route::get('get-oficcials-montly-routines/{id}', 'WeeklyRoutinesController@getOficcialsMontlyRoutines');
        //Ruta que obtiene el personal de apoyo
        Route::get('get-officials-replacement', 'WeeklyRoutinesController@getOficcialsReplacement');

        // Ruta para la gestion de samplingSchedules
        Route::resource('sampling-schedules', 'SamplingScheduleController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de samplingSchedules
        Route::get('get-sampling-schedules', 'SamplingScheduleController@all')->name('all');
        // Ruta para exportar los datos de samplingSchedules
        Route::post('export-samplingSchedules', 'SamplingScheduleController@export')->name('export');
        //Ruta que obtiene los puntos de muestra para la programacion de toma de muestra
        Route::get('get-points-sampling', 'SamplingScheduleController@getPointSampling');
        //Ruta que obtiene los funcionarios que pertenecen al leca
        Route::get('get-officials-sampling', 'SamplingScheduleController@getOficcialsSampling');

        // Ruta para la gestion de los usuarios tecnicos de la mesa de ayuda tic
        Route::resource('users-leca', 'UserController', ['only' => [
            'index', 'show', 'store', 'update', 'destroy',
        ]]);
        // Obtiene los usuarios tecnicos de la mesa de ayuda tic
        Route::get('get-users', 'UserController@all')->name('all');
        // Ruta para exportar los usuarios tecnicos de la mesa de ayuda tic
        Route::post('export-users', 'UserController@export')->name('export');

        Route::resource('positions', 'PositionController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        Route::get('get-positions', 'PositionController@all')->name('all');

        Route::post('export-positions', 'PositionController@export')->name('export');

        Route::resource('dependencies', 'DependencyController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        Route::get('get-dependencies', 'DependencyController@all')->name('all');

        Route::post('export-dependencies', 'DependencyController@export')->name('export');

        // Ruta para la gestion de monthlyRoutinesHasUsers
        Route::resource('monthly-routines-has-users', 'MonthlyRoutinesHasUsersController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de monthlyRoutinesHasUsers
        Route::get('get-monthly-routines-has-users', 'MonthlyRoutinesHasUsersController@all')->name('all');
        // Ruta para exportar los datos de monthlyRoutinesHasUsers
        Route::post('export-monthlyRoutinesHasUsers', 'MonthlyRoutinesOfficialsController@export')->name('export');

        // Ruta para la gestion de monthlyRoutinesOfficials
        Route::resource('monthly-routines-officials', 'MonthlyRoutinesOfficialsController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de monthlyRoutinesOfficials
        Route::get('get-monthly-routines-officials', 'MonthlyRoutinesOfficialsController@all')->name('all');
        // Ruta para exportar los datos de monthlyRoutinesOfficials
        Route::post('get-monthly-routines-officials', 'MonthlyRoutinesOfficialsController@export')->name('export');
        //Obtiene los funcionarios para el autocomplete
        Route::get('get-officials-monthly', 'MonthlyRoutinesOfficialsController@getOficcials');
        //Obtiene todos los ensayos
        Route::get('get-list-trials', 'MonthlyRoutinesOfficialsController@getListTrials');

        // Ruta para la gestion de startSamplings
        Route::resource('start-samplings', 'StartSamplingController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        //Obtiene los registros para el pdf
        Route::get('get-format-pdf/{id}', 'StartSamplingController@exporta');
        // Ruta que obtiene todos los registros de startSamplings
        Route::get('get-start-samplings', 'StartSamplingController@all')->name('all');
        // Ruta para exportar los datos de startSamplings
        Route::post('export-startSamplings', 'StartSamplingController@export')->name('export');
        //Ruta para ingresar los datos de la iformacion final de la muestra
        Route::put('information-finish', 'StartSamplingController@informationFinish');
        //Ruta para la migracion de inicio de toma
        Route::post('migration-modal', 'StartSamplingController@MigrateExcel');

        // Ruta para la gestion de Toma-de-muestra
        Route::resource('sample-takings', 'SampleTakingController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de Toma-de-muestra
        Route::get('get-sample-takings', 'SampleTakingController@all')->name('all');
        // Ruta para exportar los datos de Toma-de-muestra
        Route::post('export-Toma-de-muestra', 'SampleTakingController@export')->name('export');
        //Ruta que obtiene los puntos de muestra
        Route::get('get-points-location/{id}', 'SampleTakingController@getPointsLocation');
        //Ruta que obtiene los puntos de muestra
        Route::get('get-points-reem', 'SampleTakingController@getPointsLocationReem');
        //Ruta que obtiene los puntos de muestra
        Route::get('get-points-reemplazo', 'SampleTakingController@getPointsLocationReemplazo');
        //Ruta para ingresar los datos extras que van en el codigo qr
        Route::put('information-qr', 'SampleTakingController@informationQr');
        //Ruta para obtener los ensayos fisicos
        Route::get('get-list-trials-physicists', 'SampleTakingController@getListTrialsPhysicists');
        //Ruta para la migracion de las muestras
        Route::post('migration-modal-sample', 'SampleTakingController@MigrateSampleExcel');
        //Ruta para enviar informacion
        Route::get('get-information-programming/{id}', 'SampleTakingController@getInformationProgramming');
        //Ruta para enviar todos los puntos
        Route::get('get-point-all', 'SampleTakingController@getPointAll');

        // Ruta para la gestion de listTrials
        Route::resource('list-trials', 'ListTrialsController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        // Ruta que obtiene todos los registros de listTrials
        Route::get('get-list-trials', 'ListTrialsController@all')->name('all');
        // Ruta para exportar los datos de listTrials
        Route::post('export-listTrials', 'ListTrialsController@export')->name('export');
        //Ver datos en el formulario
        Route::get('get-generalities-ensayos/{id}/{optional?}', 'ListTrialsController@edit');
        //Ruta para guardar los datos del ensayo de nitritos
        Route::put('generalities-nitritos', 'ListTrialsController@generalitiesNitritos');
        //Ruta para guardar los datos del ensayo de nitratos
        Route::put('generalities-nitratos', 'ListTrialsController@generalitiesNitratos');
        //Ruta para guardar los datos del ensayo de nitratos
        Route::put('generalities-hierro', 'ListTrialsController@generalitiesHierro');
        //Ruta para guardar los datos del ensayo de fosfatos
        Route::put('generalities-fosfatos', 'ListTrialsController@generalitiesFosfatos');
        //Ruta para guardar los datos del ensayo de aluminio
        Route::put('generalities-aluminio', 'ListTrialsController@generalitiesAluminio');
        //Ruta para guardar los datos del ensayo de cloruro
        Route::put('generalities-cloruro', 'ListTrialsController@generalitiesCloruro');
        //Ruta para guardar los datos del ensayo de cloro residual
        Route::put('generalities-cloro-residual', 'ListTrialsController@generalitiesCloroResidual');
        //Ruta para guardar los datos del ensayo de calcio
        Route::put('generalities-calcio', 'ListTrialsController@generalitiesCalcio');
        //Ruta para guardar los datos del ensayo de dureza total
        Route::put('generalities-dureza-total', 'ListTrialsController@generalitiesDurezaTotal');
        //Ruta para guardar los datos del ensayo de acidez
        Route::put('generalities-acidez', 'ListTrialsController@generalitiesAcidez');
        //Ruta para guardar los datos del ensayo de fluoruros
        Route::put('generalities-fluoruros', 'ListTrialsController@generalitiesFluoruros');
        //Ruta para guardar los datos del ensayo de sulfatos
        Route::put('generalities-sulfatos', 'ListTrialsController@generalitiesSulfatos');
        //Ruta para guardar los datos del ensayo de sulfatos
        Route::put('generalities-alcalinidad', 'ListTrialsController@generalitiesAlcalinidad');
        //Ruta para guardar los datos del ensayo de sulfatos
        Route::put('generalities-ph', 'ListTrialsController@generalitiesPh');
        //Ruta para guardar los datos del ensayo de sulfatos
        Route::put('generalities-turbidez', 'ListTrialsController@generalitiesTurbidez');
        //Ruta para guardar los datos del ensayo de Coliformes totales
        Route::put('generalities-coliformes-totales', 'ListTrialsController@generalitiesColiformesTotales');
        //Ruta para guardar los datos del ensayo de escherichia coli
        Route::put('generalities-escherichia-coli', 'ListTrialsController@generalitiesEscherichiaColi');
        //Ruta para guardar los datos del ensayo de bacterias heterotroficas
        Route::put('generalities-bacterias-heterotroficas', 'ListTrialsController@generalitiesBacteriasHeterotroficas');
        //Ruta para guardar los datos del ensayo de color directa
        Route::put('generalities-color', 'ListTrialsController@generalitiesColor');
        Route::put('generalities-olor', 'ListTrialsController@generalitiesOlor');
        Route::put('generalities-conductividad', 'ListTrialsController@generalitiesConductividad');
        Route::put('generalities-sustancias-flotantes', 'ListTrialsController@generalitiesSustanciasFlotantes');
        //Ruta para guardar los datos del ensayo de carbono organico total
        Route::put('generalities-carbono-organico', 'ListTrialsController@generalitiesCarbonoOrganico');
        //Ruta para guardar los datos del ensayo de solidos disueltos
        Route::put('generalities-solidos', 'ListTrialsController@generalitiesSolidos');
        //Ruta para guardar los datos del ensayo de solidos secos
        Route::put('generalities-solidos-secos', 'ListTrialsController@generalitiesSolidosSecos');
        //Ruta para guardar los datos del ensayo de solidos
        Route::put('generalities-plomo', 'ListTrialsController@generalitiesPlomo');
        //Ruta para guardar los datos del ensayo de solidos
        Route::put('generalities-cadmio', 'ListTrialsController@generalitiesCadmio');
        //Ruta para guardar los datos del ensayo de solidos
        Route::put('generalities-mercurio', 'ListTrialsController@generalitiesMercurio');
        //Ruta para guardar los datos del ensayo de solidos
        Route::put('generalities-hidrocarburos', 'ListTrialsController@generalitiesHidrocarburos');
        //Ruta para guardar los datos del ensayo de solidos
        Route::put('generalities-plaguicidas', 'ListTrialsController@generalitiesPlaguicidas');
        //Ruta para guardar los datos del ensayo de solidos
        Route::put('generalities-trialometanos', 'ListTrialsController@generalitiesTrialometanos');
        //Ruta para el formulario de blancos de cartas de control
        Route::put('control-charts-blanco', 'ListTrialsController@controlChartsBlanco');
        //Ruta para el formulario de blancos de cartas de patron
        Route::put('control-charts-patron', 'ListTrialsController@controlChartsPatron');
        //Ruta para el formulario de blancos de cartas de patron alcalinidad
        Route::put('control-patron-alcalinidad', 'ListTrialsController@controlChartsPatronAlcalinidad');
        // Ruta para la gestion de listTrials
        Route::get('sample-receptions', 'SampleTakingController@reception');
        //Ruta para los shows de recepcion de muestras
        Route::get('show-sample-receptions/{id}', 'SampleTakingController@showReception');
        //Obtiene todas las muestras
        Route::get('get-sample-receptions', 'SampleTakingController@allReception')->name('export');
        //Ruta para editar las muestras que estan recepcionadas
        Route::get('edit-sample-receptions/{id}', 'SampleTakingController@editReception');
        //Ruta para actualizar las muestras recepcionadas
        Route::put('update-sample-receptions/{id}', 'SampleTakingController@updateReception');

        // Ruta para la gestion de listTrials
        Route::resource('list-trials', 'ListTrialsController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de listTrials
        Route::get('get-list-trials', 'ListTrialsController@all')->name('all');
        // Ruta para exportar los datos de listTrials
        Route::post('export-listTrials', 'ListTrialsController@export')->name('export');
        //Ruta para guardar los datos del ensayo de nitritos
        Route::put('generalities-nitritos', 'ListTrialsController@generalitiesNitritos');
        //Ruta para guardar los datos de configuracion
        Route::put('update-blanco-patron', 'ListTrialsController@guardarConfiguracion');

        // Ruta para la gestion de listTrials
        Route::get('sample-receptions', 'SampleTakingController@reception');

        Route::get('show-sample-receptions/{id}', 'SampleTakingController@showReception');
        //Obtiene todas las muestras
        Route::get('get-sample-receptions', 'SampleTakingController@allReception')->name('export');
        //llama los datos a mostrar en el formulario de recpecion de muestra
        Route::get('edit-sample-receptions/{id}', 'SampleTakingController@editReception');
        //actualiza el registro de recipcion de muestra
        Route::put('update-sample-receptions/{id}', 'SampleTakingController@updateReception');
        //Rama que exporta en excel de la recepcion
        Route::post('export-Recepcion-de-muestras', 'SampleTakingController@exportReception')->name('exportReception');
        //Ruta que obtiene los funcionarios que pertenecen al leca
        Route::get('get-remplace-admin', 'ListTrialsController@getRemplaceAdmin');

        // Ruta para la gestion de rutina de ensayo
        Route::resource('list-ensayos-rutina', 'RutinaEnsayoController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        // Ruta que obtiene todos los registros de listTrials
        Route::get('get-sample-rutina', 'RutinaEnsayoController@all')->name('all');

        // Ruta para la gestion de rutina de ensayo
        Route::resource('list-ensayos-relacionados', 'EnsayoRelacionadosController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        // Ruta que obtiene todos los registros de listTrials
        Route::get('get-all-ensayos-relacionados', 'EnsayoRelacionadosController@all')->name('all');

        //Aqui empieza lo de aluminio

        // Ruta para la gestion de aluminioget-datos-blanco
        Route::resource('ensayo-aluminio', 'EnsayoAluminioController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-datos-blanco-aluminio', 'EnsayoAluminioController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-aluminio', 'EnsayoAluminioController@getCondicionEnsayos');

        Route::get('get-all-aluminio', 'EnsayoAluminioController@getAllAluminio');

        Route::post('get-estado-aluminio', 'EnsayoAluminioController@estadoEnsayo');

        Route::get('get-ensayop-aluminio/{id}', 'EnsayoAluminioController@getEnsayoPrincipal');

        Route::get('get-ejecutar-aluminio', 'EnsayoAluminioController@indexEjecutar');

        Route::get('get-all-muestras-aluminio', 'EnsayoAluminioController@allMuestras');

        Route::post('store-tendido-aluminio', 'EnsayoAluminioController@storeTendido');

        Route::get('get-datos-tendido-aluminio', 'EnsayoAluminioController@allTendido');

        Route::get('get-tendido-finalizado-aluminio', 'EnsayoAluminioController@allTendidoFinalizado');

        Route::get('get-decimales-patron-aluminio', 'EnsayoAluminioController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-aluminio', 'EnsayoAluminioController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-aluminio', 'EnsayoAluminioController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-aluminio', 'EnsayoAluminioController@consultaDecimalesEnsayo');

        Route::post('dpr-store-aluminio', 'EnsayoAluminioController@storeDpr');

        Route::get('get-datos-pr-aluminio/{id}', 'EnsayoAluminioController@getDatosPr');

        Route::post('porcentual-relativa-store-aluminio', 'EnsayoAluminioController@storeRelativa');

        Route::get('get-datos-relativa-aluminio/{id}', 'EnsayoAluminioController@getDatosRelativa');

        Route::post('get-show-ensayo-aluminio', 'EnsayoAluminioController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-aluminio', 'EnsayoAluminioController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-aluminio', 'ObservacionDuplicadoController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-duplicados-aluminio', 'ObservacionDuplicadoController@all')->name('all');

        Route::post('get-grafico-aluminio', 'EnsayoAluminioController@getGrafico');
        Route::post('get-grafico-carbono', 'EnsayoCarbonoOrganicoController@getGrafico');
        Route::post('get-grafico-fosfato', 'EnsayoFosfatoController@getGrafico');
        Route::post('get-grafico-hierro', 'EnsayoHierroController@getGrafico');
        Route::post('get-grafico-nitrato', 'EnsayoNitratosController@getGrafico');
        Route::post('get-grafico-nitrito', 'EnsayoNitritosController@getGrafico');
        Route::post('get-grafico-plomo', 'EnsayoPlomoController@getGrafico');
        Route::post('get-grafico-cadmio', 'EnsayoCadmioController@getGrafico');
        Route::post('get-grafico-mercurio', 'EnsayoMercurioController@getGrafico');
        Route::post('get-grafico-plaguicidas', 'EnsayoPlaguicidaController@getGrafico');
        Route::post('get-grafico-hidrocarburos', 'EnsayoHidrocarburosController@getGrafico');
        Route::post('get-grafico-trialometanos', 'EnsayoTrialometanosController@getGrafico');

        Route::post('get-grafico-coliformes', 'EnsayoColiformesController@getGrafico');
        Route::post('get-ip-coliformes', 'EnsayoColiformesController@getIp');
        Route::post('get-grafico-escherichia', 'EnsayoEscherichiaController@getGrafico');
        Route::post('get-ip-escherichia', 'EnsayoEscherichiaController@getIp');
        Route::post('get-grafico-heterotroficas', 'EnsayoHeterotroficasController@getGrafico');

        Route::post('get-grafico-dureza', 'EnsayoDurezaController@getGrafico');
        Route::post('get-grafico-alcalinidad', 'EnsayoAlcalinidadController@getGrafico');
        Route::post('get-grafico-cloruro', 'EnsayoCloruroController@getGrafico');
        Route::post('get-grafico-calcio', 'EnsayoCalcioController@getGrafico');
        Route::post('get-grafico-cloro', 'EnsayoCloroController@getGrafico');
        Route::post('get-grafico-fluoruro', 'EnsayoFluorurosController@getGrafico');
        Route::post('get-grafico-sulfatos', 'EnsayoSulfatosController@getGrafico');
        // Route::post('get-grafico-solidos-totales', 'EnsayoSolidosTotalesController@getGrafico');
        Route::post('get-grafico-solidos-secos', 'EnsayoSolidosSecosController@getGrafico');
        Route::post('get-grafico-acidez', 'EnsayoAcidezController@getGrafico');

        Route::post('get-grafico-ph', 'EnsayoPhController@getGrafico');
        Route::post('get-grafico-conductividad', 'EnsayoConductividadController@getGrafico');
        Route::post('get-grafico-color', 'EnsayoColorController@getGrafico');
        Route::post('get-grafico-turbidez', 'EnsayoTurbidezController@getGrafico');

        // Ruta para exportar los datos de observacionDuplicados
        Route::post('export-observacionDuplicados', 'ObservacionDuplicadoController@export')->name('export');

        //Aqui empieza lo de plomo

        // Ruta para la gestion de plomo
        Route::resource('ensayo-plomo', 'EnsayoPlomoController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-datos-blanco-plomo', 'EnsayoPlomoController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-plomo', 'EnsayoPlomoController@getCondicionEnsayos');

        Route::get('get-all-plomo', 'EnsayoPlomoController@getAllPlomo');

        Route::get('get-ensayop-plomo/{id}', 'EnsayoPlomoController@getEnsayoPrincipal');

        Route::post('get-estado-plomo', 'EnsayoPlomoController@estadoEnsayo');

        Route::get('get-ejecutar-plomo', 'EnsayoPlomoController@indexEjecutar');

        Route::get('get-all-muestras-plomo', 'EnsayoPlomoController@allMuestras');

        Route::post('store-tendido-plomo', 'EnsayoPlomoController@storeTendido');

        Route::get('get-datos-tendido-plomo', 'EnsayoPlomoController@allTendido');

        Route::get('get-tendido-finalizado-plomo', 'EnsayoPlomoController@allTendidoFinalizado');

        Route::get('get-decimales-patron-plomo', 'EnsayoPlomoController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-plomo', 'EnsayoPlomoController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-plomo', 'EnsayoPlomoController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-plomo', 'EnsayoPlomoController@consultaDecimalesEnsayo');

        Route::post('dpr-store-plomo', 'EnsayoPlomoController@storeDpr');

        Route::get('get-datos-pr-plomo/{id}', 'EnsayoPlomoController@getDatosPr');

        Route::post('porcentual-relativa-store-plomo', 'EnsayoPlomoController@storeRelativa');

        Route::get('get-datos-relativa-plomo/{id}', 'EnsayoPlomoController@getDatosRelativa');

        Route::post('get-show-ensayo-plomo', 'EnsayoPlomoController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-plomo', 'EnsayoPlomoController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-plomo', 'ObservacionesEspectroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-plomo', 'ObservacionesEspectroController@all')->name('all');

        //Aqui empieza lo de cadmio

        // Ruta para la gestion de cadmio
        Route::resource('ensayo-cadmio', 'EnsayoCadmioController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-datos-blanco-cadmio', 'EnsayoCadmioController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-cadmio', 'EnsayoCadmioController@getCondicionEnsayos');

        Route::get('get-all-cadmio', 'EnsayoCadmioController@getAllCadmio');

        Route::get('get-ensayop-cadmio/{id}', 'EnsayoCadmioController@getEnsayoPrincipal');

        Route::get('get-ejecutar-cadmio', 'EnsayoCadmioController@indexEjecutar');

        Route::get('get-all-muestras-cadmio', 'EnsayoCadmioController@allMuestras');

        Route::post('get-estado-cadmio', 'EnsayoCadmioController@estadoEnsayo');

        Route::post('store-tendido-cadmio', 'EnsayoCadmioController@storeTendido');

        Route::get('get-datos-tendido-cadmio', 'EnsayoCadmioController@allTendido');

        Route::get('get-tendido-finalizado-cadmio', 'EnsayoCadmioController@allTendidoFinalizado');

        Route::get('get-decimales-patron-cadmio', 'EnsayoCadmioController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-cadmio', 'EnsayoCadmioController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-cadmio', 'EnsayoCadmioController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-cadmio', 'EnsayoCadmioController@consultaDecimalesEnsayo');

        Route::post('dpr-store-cadmio', 'EnsayoCadmioController@storeDpr');

        Route::get('get-datos-pr-cadmio/{id}', 'EnsayoCadmioController@getDatosPr');

        Route::post('porcentual-relativa-store-cadmio', 'EnsayoCadmioController@storeRelativa');

        Route::get('get-datos-relativa-cadmio/{id}', 'EnsayoCadmioController@getDatosRelativa');

        Route::post('get-show-ensayo-cadmio', 'EnsayoCadmioController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-cadmio', 'EnsayoCadmioController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-cadmio', 'ObservacionesEspectroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-duplicados-cadmio', 'ObservacionesEspectroController@all')->name('all');

        //Aqui empieza lo de Mercurio

        // Ruta para la gestion de Mercurio
        Route::resource('ensayo-mercurio', 'EnsayoMercurioController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-datos-blanco-mercurio', 'EnsayoMercurioController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-mercurio', 'EnsayoMercurioController@getCondicionEnsayos');

        Route::get('get-all-mercurio', 'EnsayoMercurioController@getAllMercurio');

        Route::get('get-ensayop-mercurio/{id}', 'EnsayoMercurioController@getEnsayoPrincipal');

        Route::get('get-ejecutar-mercurio', 'EnsayoMercurioController@indexEjecutar');

        Route::post('get-estado-mercurio', 'EnsayoMercurioController@estadoEnsayo');

        Route::get('get-all-muestras-mercurio', 'EnsayoMercurioController@allMuestras');

        Route::post('store-tendido-mercurio', 'EnsayoMercurioController@storeTendido');

        Route::get('get-datos-tendido-mercurio', 'EnsayoMercurioController@allTendido');

        Route::get('get-tendido-finalizado-mercurio', 'EnsayoMercurioController@allTendidoFinalizado');

        Route::get('get-decimales-patron-mercurio', 'EnsayoMercurioController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-mercurio', 'EnsayoMercurioController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-mercurio', 'EnsayoMercurioController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-mercurio', 'EnsayoMercurioController@consultaDecimalesEnsayo');

        Route::post('dpr-store-mercurio', 'EnsayoMercurioController@storeDpr');

        Route::get('get-datos-pr-mercurio/{id}', 'EnsayoMercurioController@getDatosPr');

        Route::post('porcentual-relativa-store-mercurio', 'EnsayoMercurioController@storeRelativa');

        Route::get('get-datos-relativa-mercurio/{id}', 'EnsayoMercurioController@getDatosRelativa');

        Route::post('get-show-ensayo-mercurio', 'EnsayoMercurioController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-mercurio', 'EnsayoMercurioController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-mercurio', 'ObservacionesEspectroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-duplicados-mercurio', 'ObservacionesEspectroController@all')->name('all');

        //Aqui empieza lo de Hidrocarburos

        // Ruta para la gestion de Hidrocarburos
        Route::resource('ensayo-hidrocarburos', 'EnsayoHidrocarburosController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-datos-blanco-hidrocarburos', 'EnsayoHidrocarburosController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-hidrocarburos', 'EnsayoHidrocarburosController@getCondicionEnsayos');

        Route::get('get-all-hidrocarburos', 'EnsayoHidrocarburosController@getAllHidrocarburos');

        Route::get('get-ensayop-hidrocarburos/{id}', 'EnsayoHidrocarburosController@getEnsayoPrincipal');

        Route::get('get-ejecutar-hidrocarburos', 'EnsayoHidrocarburosController@indexEjecutar');

        Route::get('get-all-muestras-hidrocarburos', 'EnsayoHidrocarburosController@allMuestras');

        Route::post('get-estado-hidrocarburos', 'EnsayoHidrocarburosController@estadoEnsayo');

        Route::post('store-tendido-hidrocarburos', 'EnsayoHidrocarburosController@storeTendido');

        Route::get('get-datos-tendido-hidrocarburos', 'EnsayoHidrocarburosController@allTendido');

        Route::get('get-tendido-finalizado-hidrocarburos', 'EnsayoHidrocarburosController@allTendidoFinalizado');

        Route::get('get-decimales-patron-hidrocarburos', 'EnsayoHidrocarburosController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-hidrocarburos', 'EnsayoHidrocarburosController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-hidrocarburos', 'EnsayoHidrocarburosController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-hidrocarburos', 'EnsayoHidrocarburosController@consultaDecimalesEnsayo');

        Route::post('dpr-store-hidrocarburos', 'EnsayoHidrocarburosController@storeDpr');

        Route::get('get-datos-pr-hidrocarburos/{id}', 'EnsayoHidrocarburosController@getDatosPr');

        Route::post('porcentual-relativa-store-hidrocarburos', 'EnsayoHidrocarburosController@storeRelativa');

        Route::get('get-datos-relativa-hidrocarburos/{id}', 'EnsayoHidrocarburosController@getDatosRelativa');

        Route::post('get-show-ensayo-hidrocarburos', 'EnsayoHidrocarburosController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-hidrocarburos', 'EnsayoHidrocarburosController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-hidrocarburos', 'ObservacionesEspectroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-duplicados-hidrocarburos', 'ObservacionesEspectroController@all')->name('all');

        //Aqui empieza lo de Plaguicidas

        // Ruta para la gestion de Plaguicidas
        Route::resource('ensayo-plaguicidas', 'EnsayoPlaguicidaController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-datos-blanco-plaguicidas', 'EnsayoPlaguicidaController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-plaguicidas', 'EnsayoPlaguicidaController@getCondicionEnsayos');

        Route::get('get-all-plaguicidas', 'EnsayoPlaguicidaController@getAllPlaguicida');

        Route::get('get-ensayop-plaguicidas/{id}', 'EnsayoPlaguicidaController@getEnsayoPrincipal');

        Route::get('get-ejecutar-plaguicidas', 'EnsayoPlaguicidaController@indexEjecutar');

        Route::post('get-estado-plaguicidas', 'EnsayoPlaguicidaController@estadoEnsayo');

        Route::get('get-all-muestras-plaguicidas', 'EnsayoPlaguicidaController@allMuestras');

        Route::post('store-tendido-plaguicidas', 'EnsayoPlaguicidaController@storeTendido');

        Route::get('get-datos-tendido-plaguicidas', 'EnsayoPlaguicidaController@allTendido');

        Route::get('get-tendido-finalizado-plaguicidas', 'EnsayoPlaguicidaController@allTendidoFinalizado');

        Route::get('get-decimales-patron-plaguicidas', 'EnsayoPlaguicidaController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-plaguicidas', 'EnsayoPlaguicidaController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-plaguicidas', 'EnsayoPlaguicidaController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-plaguicidas', 'EnsayoPlaguicidaController@consultaDecimalesEnsayo');

        Route::post('dpr-store-plaguicidas', 'EnsayoPlaguicidaController@storeDpr');

        Route::get('get-datos-pr-plaguicidas/{id}', 'EnsayoPlaguicidaController@getDatosPr');

        Route::post('porcentual-relativa-store-plaguicidas', 'EnsayoPlaguicidaController@storeRelativa');

        Route::get('get-datos-relativa-plaguicidas/{id}', 'EnsayoPlaguicidaController@getDatosRelativa');

        Route::post('get-show-ensayo-plaguicidas', 'EnsayoPlaguicidaController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-plaguicidas', 'EnsayoPlaguicidaController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-plaguicidas', 'ObservacionesEspectroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-duplicados-plaguicidas', 'ObservacionesEspectroController@all')->name('all');

        //Aqui empieza lo de Trialometanos

        // Ruta para la gestion de Trialometanos
        Route::resource('ensayo-trialometanos', 'EnsayoTrialometanosController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-datos-blanco-trialometanos', 'EnsayoTrialometanosController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-trialometanos', 'EnsayoTrialometanosController@getCondicionEnsayos');

        Route::get('get-all-trialometanos', 'EnsayoTrialometanosController@getAllTrialometanos');

        Route::get('get-ensayop-trialometanos/{id}', 'EnsayoTrialometanosController@getEnsayoPrincipal');

        Route::get('get-ejecutar-trialometanos', 'EnsayoTrialometanosController@indexEjecutar');

        Route::post('get-estado-trialometanos', 'EnsayoTrialometanosController@estadoEnsayo');

        Route::get('get-all-muestras-trialometanos', 'EnsayoTrialometanosController@allMuestras');

        Route::post('store-tendido-trialometanos', 'EnsayoTrialometanosController@storeTendido');

        Route::get('get-datos-tendido-trialometanos', 'EnsayoTrialometanosController@allTendido');

        Route::get('get-tendido-finalizado-trialometanos', 'EnsayoTrialometanosController@allTendidoFinalizado');

        Route::get('get-decimales-patron-trialometanos', 'EnsayoTrialometanosController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-trialometanos', 'EnsayoTrialometanosController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-trialometanos', 'EnsayoTrialometanosController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-trialometanos', 'EnsayoTrialometanosController@consultaDecimalesEnsayo');

        Route::post('dpr-store-trialometanos', 'EnsayoTrialometanosController@storeDpr');

        Route::get('get-datos-pr-trialometanos/{id}', 'EnsayoTrialometanosController@getDatosPr');

        Route::post('porcentual-relativa-store-trialometanos', 'EnsayoTrialometanosController@storeRelativa');

        Route::get('get-datos-relativa-trialometanos/{id}', 'EnsayoTrialometanosController@getDatosRelativa');

        Route::post('get-show-ensayo-trialometanos', 'EnsayoTrialometanosController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-trialometanos', 'EnsayoTrialometanosController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-trialometanos', 'ObservacionesEspectroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-duplicados-trialometanos', 'ObservacionesEspectroController@all')->name('all');

        //Aqui empieza lo de hierro

        // Ruta para la gestion de aluminioget-datos-blanco
        Route::resource('ensayo-hierro', 'EnsayoHierroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-hierro/{id}', 'EnsayoHierroController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-hierro', 'EnsayoHierroController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-hierro', 'EnsayoHierroController@getCondicionEnsayos');

        Route::get('get-all-hierro', 'EnsayoHierroController@getAllHierro');

        Route::post('get-estado-hierro', 'EnsayoHierroController@estadoEnsayo');

        Route::get('get-ejecutar-hierro', 'EnsayoHierroController@indexEjecutar');

        Route::get('get-all-muestras-hierro', 'EnsayoHierroController@allMuestras');

        Route::post('store-tendido-hierro', 'EnsayoHierroController@storeTendido');

        Route::get('get-datos-tendido-hierro', 'EnsayoHierroController@allTendido');

        Route::get('get-tendido-finalizado-hierro', 'EnsayoHierroController@allTendidoFinalizado');

        Route::get('get-decimales-patron-hierro', 'EnsayoHierroController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-hierro', 'EnsayoHierroController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-hierro', 'EnsayoHierroController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-hierro', 'EnsayoHierroController@consultaDecimalesEnsayo');

        Route::post('dpr-store-hierro', 'EnsayoHierroController@storeDpr');

        Route::get('get-datos-pr-hierro/{id}', 'EnsayoHierroController@getDatosPr');

        Route::post('porcentual-relativa-store-hierro', 'EnsayoHierroController@storeRelativa');

        Route::get('get-datos-relativa-hierro/{id}', 'EnsayoHierroController@getDatosRelativa');

        Route::post('get-show-ensayo-hierro', 'EnsayoHierroController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-hierro', 'EnsayoHierroController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-hierro', 'ObservacionesDuplicadoHierroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-duplicados-hierro', 'ObservacionesDuplicadoHierroController@all')->name('all');

        //Aqui empieza lo de Carbono organico

        Route::resource('ensayo-carbono', 'EnsayoCarbonoOrganicoController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-carbono/{id}', 'EnsayoCarbonoOrganicoController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-carbono', 'EnsayoCarbonoOrganicoController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-carbono', 'EnsayoCarbonoOrganicoController@getCondicionEnsayos');

        Route::get('get-all-carbono', 'EnsayoCarbonoOrganicoController@getAllCarbonoOrganico');

        Route::get('get-ejecutar-carbono', 'EnsayoCarbonoOrganicoController@indexEjecutar');

        Route::get('get-all-muestras-carbono', 'EnsayoCarbonoOrganicoController@allMuestras');

        Route::post('get-estado-carbono', 'EnsayoCarbonoOrganicoController@estadoEnsayo');

        Route::post('store-tendido-carbono', 'EnsayoCarbonoOrganicoController@storeTendido');

        Route::get('get-datos-tendido-carbono', 'EnsayoCarbonoOrganicoController@allTendido');

        Route::get('get-tendido-finalizado-carbono', 'EnsayoCarbonoOrganicoController@allTendidoFinalizado');

        Route::get('get-decimales-patron-carbono', 'EnsayoCarbonoOrganicoController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-carbono', 'EnsayoCarbonoOrganicoController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-carbono', 'EnsayoCarbonoOrganicoController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-carbono', 'EnsayoCarbonoOrganicoController@consultaDecimalesEnsayo');

        Route::post('dpr-store-carbono', 'EnsayoCarbonoOrganicoController@storeDpr');

        Route::get('get-datos-pr-carbono/{id}', 'EnsayoCarbonoOrganicoController@getDatosPr');

        Route::post('porcentual-relativa-store-carbono', 'EnsayoCarbonoOrganicoController@storeRelativa');

        Route::get('get-datos-relativa-carbono/{id}', 'EnsayoCarbonoOrganicoController@getDatosRelativa');

        Route::post('get-show-ensayo-carbono', 'EnsayoCarbonoOrganicoController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-carbono', 'EnsayoCarbonoOrganicoController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-carbono', 'ObservacionesDuplicadoCarbonoOrganicoController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-duplicados-carbono', 'ObservacionesDuplicadoCarbonoOrganicoController@all')->name('all');

        //Aqui empieza lo de fosfatos

        // Ruta para la gestion de aluminioget-datos-blanco
        Route::resource('ensayo-fosfato', 'EnsayoFosfatoController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-fosfato/{id}', 'EnsayoFosfatoController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-fosfato', 'EnsayoFosfatoController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-fosfato', 'EnsayoFosfatoController@getCondicionEnsayos');

        Route::get('get-all-fosfato', 'EnsayoFosfatoController@getAllFosfato');

        Route::get('get-ejecutar-fosfato', 'EnsayoFosfatoController@indexEjecutar');

        Route::get('get-all-muestras-fosfato', 'EnsayoFosfatoController@allMuestras');

        Route::post('store-tendido-fosfato', 'EnsayoFosfatoController@storeTendido');

        Route::post('get-estado-fosfato', 'EnsayoFosfatoController@estadoEnsayo');

        Route::get('get-datos-tendido-fosfato', 'EnsayoFosfatoController@allTendido');

        Route::get('get-tendido-finalizado-fosfato', 'EnsayoFosfatoController@allTendidoFinalizado');

        Route::get('get-decimales-patron-fosfato', 'EnsayoFosfatoController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-fosfato', 'EnsayoFosfatoController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-fosfato', 'EnsayoFosfatoController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-fosfato', 'EnsayoFosfatoController@consultaDecimalesEnsayo');

        Route::post('dpr-store-fosfato', 'EnsayoFosfatoController@storeDpr');

        Route::get('get-datos-pr-fosfato/{id}', 'EnsayoFosfatoController@getDatosPr');

        Route::post('porcentual-relativa-store-fosfato', 'EnsayoFosfatoController@storeRelativa');

        Route::get('get-datos-relativa-fosfato/{id}', 'EnsayoFosfatoController@getDatosRelativa');

        Route::post('get-show-ensayo-fosfato', 'EnsayoFosfatoController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-fosfato', 'EnsayoFosfatoController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-fosfato', 'ObservacionesDuplicadoFosfatoController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-duplicados-fosfato', 'ObservacionesDuplicadoFosfatoController@all')->name('all');

        //Aqui empieza lo de nitratos

        // Ruta para la gestion de aluminioget-datos-blanco
        Route::resource('ensayo-nitrato', 'EnsayoNitratosController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-nitrato/{id}', 'EnsayoNitratosController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-nitrato', 'EnsayoNitratosController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-nitrato', 'EnsayoNitratosController@getCondicionEnsayos');

        Route::get('get-all-nitrato', 'EnsayoNitratosController@getAllNitratos');

        Route::post('get-estado-nitrato', 'EnsayoNitratosController@estadoEnsayo');

        Route::get('get-ejecutar-nitrato', 'EnsayoNitratosController@indexEjecutar');

        Route::get('get-all-muestras-nitrato', 'EnsayoNitratosController@allMuestras');

        Route::post('store-tendido-nitrato', 'EnsayoNitratosController@storeTendido');

        Route::get('get-datos-tendido-nitrato', 'EnsayoNitratosController@allTendido');

        Route::get('get-tendido-finalizado-nitrato', 'EnsayoNitratosController@allTendidoFinalizado');

        Route::get('get-decimales-patron-nitrato', 'EnsayoNitratosController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-nitrato', 'EnsayoNitratosController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-nitrato', 'EnsayoNitratosController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-nitrato', 'EnsayoNitratosController@consultaDecimalesEnsayo');

        Route::post('dpr-store-nitrato', 'EnsayoNitratosController@storeDpr');

        Route::get('get-datos-pr-nitrato/{id}', 'EnsayoNitratosController@getDatosPr');

        Route::post('porcentual-relativa-store-nitrato', 'EnsayoNitratosController@storeRelativa');

        Route::get('get-datos-relativa-nitrato/{id}', 'EnsayoNitratosController@getDatosRelativa');

        Route::post('get-show-ensayo-nitrato', 'EnsayoNitratosController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-nitrato', 'EnsayoNitratosController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-nitrato', 'ObservacionesDuplicadoNitratosController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-duplicados-nitrato', 'ObservacionesDuplicadoNitratosController@all')->name('all');

        //Aqui empieza lo de fluoruros

        // Ruta para la gestion de fluoruro get-datos-blanco
        Route::resource('ensayo-fluoruro', 'EnsayoFluorurosController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-all-fluoruro', 'EnsayoFluorurosController@getAllFluoruro');

        Route::get('get-ejecutar-ensayo-fluoruro', 'EnsayoFluorurosController@getCondicionEnsayos');

        Route::get('get-datos-blanco-fluoruro', 'EnsayoFluorurosController@getDatosBlanco');

        Route::get('get-decimales-blanco-fluoruro', 'EnsayoFluorurosController@consultaDecimalesBlanco');

        Route::post('get-estado-fluoruro', 'EnsayoFluorurosController@estadoEnsayo');

        Route::get('get-decimales-patron-fluoruro', 'EnsayoFluorurosController@consultaDecimalesPatron');

        Route::get('get-ejecutar-fluoruro', 'EnsayoFluorurosController@indexEjecutar');

        Route::get('get-datos-tendido-fluoruro', 'EnsayoFluorurosController@allTendido');

        Route::get('get-tendido-finalizado-fluoruro', 'EnsayoFluorurosController@allTendidoFinalizado');

        Route::get('get-decimales-ensayo-fluoruro', 'EnsayoFluorurosController@consultaDecimalesEnsayo');

        Route::get('get-all-muestras-fluoruro', 'EnsayoFluorurosController@allMuestras');

        Route::post('store-tendido-fluoruro', 'EnsayoFluorurosController@storeTendido');

        Route::get('get-ensayop-fluoruro/{id}', 'EnsayoFluorurosController@getEnsayoPrincipal');

        Route::get('get-datos-relativa-fluoruro/{id}', 'EnsayoFluorurosController@getDatosRelativa');

        Route::post('porcentual-relativa-store-fluoruro', 'EnsayoFluorurosController@storeRelativa');

        Route::resource('observaciones-dupli-fluoruro', 'ObservacionesDuplicadoFluoruroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observaciones-dupli-fluoruro', 'ObservacionesDuplicadoFluoruroController@all')->name('all');


        //Aqui empieza lo de sulfatos

        // Ruta para la gestion de fluoruro get-datos-blanco
        Route::resource('ensayo-sulfatos', 'EnsayoSulfatosController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-all-sulfatos', 'EnsayoSulfatosController@getAllSulfatos');

        Route::get('get-ejecutar-ensayo-sulfatos', 'EnsayoSulfatosController@getCondicionEnsayos');

        Route::get('get-datos-blanco-sulfatos', 'EnsayoSulfatosController@getDatosBlanco');

        Route::get('get-decimales-blanco-sulfatos', 'EnsayoSulfatosController@consultaDecimalesBlanco');

        Route::post('get-estado-sulfatos', 'EnsayoSulfatosController@estadoEnsayo');

        Route::get('get-decimales-patron-sulfatos', 'EnsayoSulfatosController@consultaDecimalesPatron');

        Route::get('get-ejecutar-sulfatos', 'EnsayoSulfatosController@indexEjecutar');

        Route::get('get-datos-tendido-sulfatos', 'EnsayoSulfatosController@allTendido');

        Route::get('get-tendido-finalizado-sulfatos', 'EnsayoSulfatosController@allTendidoFinalizado');

        Route::get('get-decimales-ensayo-sulfatos', 'EnsayoSulfatosController@consultaDecimalesEnsayo');

        Route::get('get-all-muestras-sulfatos', 'EnsayoSulfatosController@allMuestras');

        Route::post('store-tendido-sulfatos', 'EnsayoSulfatosController@storeTendido');

        Route::get('get-ensayop-sulfatos/{id}', 'EnsayoSulfatosController@getEnsayoPrincipal');

        Route::get('get-datos-relativa-sulfatos/{id}', 'EnsayoSulfatosController@getDatosRelativa');

        Route::get('get-datos-formulas-sulfatos', 'EnsayoSulfatosController@getDatosFormulas');

        Route::post('porcentual-relativa-store-sulfatos', 'EnsayoSulfatosController@storeRelativa');

        Route::resource('observaciones-dupli-sulfatos', 'ObservacionesDuplicadoSulfatosController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observaciones-dupli-sulfatos', 'ObservacionesDuplicadoSulfatosController@all')->name('all');


        //Aqui empieza lo de solidos disueltos

        // Ruta para la gestion de fluoruro get-datos-blanco
        Route::resource('ensayo-disueltos', 'EnsayoSolidosDisController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-all-disueltos', 'EnsayoSolidosDisController@getAllDisueltos');

        Route::get('get-ejecutar-ensayo-disueltos', 'EnsayoSolidosDisController@getCondicionEnsayos');

        Route::get('get-datos-blanco-disueltos', 'EnsayoSolidosDisController@getDatosBlanco');

        Route::get('get-decimales-blanco-disueltos', 'EnsayoSolidosDisController@consultaDecimalesBlanco');

        Route::post('get-estado-disueltos', 'EnsayoSolidosDisController@estadoEnsayo');

        Route::get('get-decimales-patron-disueltos', 'EnsayoSolidosDisController@consultaDecimalesPatron');

        Route::get('get-ejecutar-disueltos', 'EnsayoSolidosDisController@indexEjecutar');

        Route::get('get-datos-tendido-disueltos', 'EnsayoSolidosDisController@allTendido');

        Route::get('get-tendido-finalizado-disueltos', 'EnsayoSolidosDisController@allTendidoFinalizado');

        Route::get('get-decimales-ensayo-disueltos', 'EnsayoSolidosDisController@consultaDecimalesEnsayo');

        Route::get('get-all-muestras-disueltos', 'EnsayoSolidosDisController@allMuestras');

        Route::post('store-tendido-disueltos', 'EnsayoSolidosDisController@storeTendido');

        Route::get('get-ensayop-disueltos/{id}', 'EnsayoSolidosDisController@getEnsayoPrincipal');

        Route::get('get-datos-relativa-disueltos/{id}', 'EnsayoSolidosDisController@getDatosRelativa');

        Route::post('porcentual-relativa-store-disueltos', 'EnsayoSolidosDisController@storeRelativa');

        Route::post('get-show-blanco-patron-disueltos', 'EnsayoSolidosDisController@getAllShowPatronBlanco');

        Route::post('get-show-ensayo-disueltos', 'EnsayoSolidosDisController@getAllShowEnsayo');

        Route::resource('observaciones-dupli-disueltos', 'ObservacionesDuplicadoSolidosDisController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observaciones-dupli-disueltos', 'ObservacionesDuplicadoSolidosDisController@all')->name('all');

        //Aqui empieza lo de solidos secos

        // Ruta para la gestion de fluoruro get-datos-blanco
        Route::resource('ensayo-secos', 'EnsayoSolidosSecosController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-all-secos', 'EnsayoSolidosSecosController@getAllSecos');

        Route::get('get-ejecutar-ensayo-secos', 'EnsayoSolidosSecosController@getCondicionEnsayos');

        Route::get('get-datos-blanco-secos', 'EnsayoSolidosSecosController@getDatosBlanco');

        Route::get('get-decimales-blanco-secos', 'EnsayoSolidosSecosController@consultaDecimalesBlanco');

        Route::post('get-estado-secos', 'EnsayoSolidosSecosController@estadoEnsayo');

        Route::get('get-decimales-patron-secos', 'EnsayoSolidosSecosController@consultaDecimalesPatron');

        Route::get('get-ejecutar-secos', 'EnsayoSolidosSecosController@indexEjecutar');

        Route::get('get-datos-tendido-secos', 'EnsayoSolidosSecosController@allTendido');

        Route::get('get-tendido-finalizado-secos', 'EnsayoSolidosSecosController@allTendidoFinalizado');

        Route::get('get-decimales-ensayo-secos', 'EnsayoSolidosSecosController@consultaDecimalesEnsayo');

        Route::get('get-all-muestras-secos', 'EnsayoSolidosSecosController@allMuestras');

        Route::post('store-tendido-secos', 'EnsayoSolidosSecosController@storeTendido');

        Route::get('get-ensayop-secos/{id}', 'EnsayoSolidosSecosController@getEnsayoPrincipal');

        Route::get('get-datos-relativa-secos/{id}', 'EnsayoSolidosSecosController@getDatosRelativa');

        Route::post('porcentual-relativa-store-secos', 'EnsayoSolidosSecosController@storeRelativa');

        Route::post('get-show-blanco-patron-secos', 'EnsayoSolidosSecosController@getAllShowPatronBlanco');

        Route::post('get-show-ensayo-secos', 'EnsayoSolidosSecosController@getAllShowEnsayo');

        Route::resource('observaciones-dupli-secos', 'ObservacionesDuplicadoSolidosSecosController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observaciones-dupli-secos', 'ObservacionesDuplicadoSolidosSecosController@all')->name('all');

        //Aqui empieza lo de coliformes totales de microbiologia
        // Ruta para la gestion de coliformes-datos-blanco
        Route::resource('ensayo-coliformes', 'EnsayoColiformesController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-datos-blanco-coliformes', 'EnsayoColiformesController@getDatosBlanco');

        Route::get('get-datos-siembra-coliformes', 'EnsayoColiformesController@getDatosSiembra');

        Route::get('get-datos-users-coliformes', 'EnsayoColiformesController@getDatosUsers');

        Route::get('get-all-coliformes', 'EnsayoColiformesController@getAllColiformes');

        Route::get('get-ejecutar-ensayo-coliformes', 'EnsayoColiformesController@getCondicionEnsayos');

        Route::post('get-estado-coliformes', 'EnsayoColiformesController@estadoEnsayo');

        Route::get('get-decimales-blanco-coliformes', 'EnsayoColiformesController@consultaDecimalesBlanco');

        Route::get('get-ejecutar-coliformes', 'EnsayoColiformesController@indexEjecutar');

        Route::get('get-datos-tendido-coliformes', 'EnsayoColiformesController@allTendido');

        Route::get('get-tendido-finalizado-coliformes', 'EnsayoColiformesController@allTendidoFinalizado');

        Route::get('get-decimales-ensayo-coliformes', 'EnsayoColiformesController@consultaDecimalesEnsayo');

        Route::get('get-all-muestras-coliformes', 'EnsayoColiformesController@allMuestras');

        Route::post('store-tendido-coliformes', 'EnsayoColiformesController@storeTendido');

        Route::post('pozos-coliformes', 'EnsayoColiformesController@getResulPozos');

        Route::post('pozos-ufc-coliformes', 'EnsayoColiformesController@getResulPozosUfc');

        Route::post('get-show-ensayo-coliformes', 'EnsayoColiformesController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-coliformes', 'EnsayoColiformesController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-coliformes', 'ObservacionesMicroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-coliformes', 'ObservacionesMicroController@all')->name('all');

        //Aqui comienzan las rutas para el ensayo de escherichia coli
        // Ruta para la gestion de escherichia-datos-blanco
        Route::resource('ensayo-escherichia', 'EnsayoEscherichiaController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);


        
        // Ruta para la gestion de observacionesDuplicadoColors
        Route::resource('observaciones-dupli-colors', 'ObservacionesDuplicadoColorController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de observacionesDuplicadoColors
        Route::get('get-observaciones-dupli-colors', 'ObservacionesDuplicadoColorController@all')->name('all');
        // Ruta para exportar los datos de observacionesDuplicadoColors
        Route::post('export-observacionesDuplicadoColors', 'ObservacionesDuplicadoColorController@export')->name('export');

        Route::get('get-all-escherichia', 'EnsayoEscherichiaController@getAllEscherichia');

        Route::get('get-ejecutar-ensayo-escherichia', 'EnsayoEscherichiaController@getCondicionEnsayos');

        Route::get('get-datos-blanco-escherichia', 'EnsayoEscherichiaController@getDatosBlanco');

        Route::get('get-datos-users-escherichia', 'EnsayoEscherichiaController@getDatosUsers');
        
        Route::get('get-decimales-blanco-escherichia', 'EnsayoEscherichiaController@consultaDecimalesBlanco');

        Route::post('get-estado-escherichia', 'EnsayoEscherichiaController@estadoEnsayo');

        Route::get('get-ejecutar-escherichia', 'EnsayoEscherichiaController@indexEjecutar');

        Route::get('get-datos-tendido-escherichia', 'EnsayoEscherichiaController@allTendido');

        Route::get('get-tendido-finalizado-escherichia', 'EnsayoEscherichiaController@allTendidoFinalizado');

        Route::get('get-decimales-ensayo-escherichia', 'EnsayoEscherichiaController@consultaDecimalesEnsayo');

        Route::get('get-datos-siembra-escherichia', 'EnsayoEscherichiaController@getDatosSiembra');

        Route::get('get-all-muestras-escherichia', 'EnsayoEscherichiaController@allMuestras');

        Route::post('store-tendido-escherichia', 'EnsayoEscherichiaController@storeTendido');

        Route::post('pozos-escherichia', 'EnsayoEscherichiaController@getResulPozos');

        Route::post('get-show-ensayo-escherichia', 'EnsayoEscherichiaController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-escherichia', 'EnsayoEscherichiaController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-escherichia', 'ObservacionesMicroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-escherichia', 'ObservacionesMicroController@all')->name('all');
        

        //Aqui comienzan las rutas para el ensayo de escherichia coli
        // Ruta para la gestion de heterotroficas-datos-blanco
        Route::resource('ensayo-heterotroficas', 'EnsayoHeterotroficasController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-all-heterotroficas', 'EnsayoHeterotroficasController@getAllHeterotroficas');

        Route::get('get-ejecutar-ensayo-heterotroficas', 'EnsayoHeterotroficasController@getCondicionEnsayos');

        Route::get('get-datos-blanco-heterotroficas', 'EnsayoHeterotroficasController@getDatosBlanco');

        Route::get('get-decimales-blanco-heterotroficas', 'EnsayoHeterotroficasController@consultaDecimalesBlanco');

        Route::get('get-datos-users-heterotroficas', 'EnsayoHeterotroficasController@getDatosUsers');

        Route::post('get-estado-heterotroficas', 'EnsayoHeterotroficasController@estadoEnsayo');

        Route::post('dosis-heterotroficas', 'EnsayoHeterotroficasController@getResulDosis');

        Route::get('get-ejecutar-heterotroficas', 'EnsayoHeterotroficasController@indexEjecutar');

        Route::get('get-datos-tendido-heterotroficas', 'EnsayoHeterotroficasController@allTendido');

        Route::get('get-tendido-finalizado-heterotroficas', 'EnsayoHeterotroficasController@allTendidoFinalizado');

        Route::get('get-decimales-ensayo-heterotroficas', 'EnsayoHeterotroficasController@consultaDecimalesEnsayo');

        Route::get('get-datos-siembra-heterotroficas', 'EnsayoHeterotroficasController@getDatosSiembra');

        Route::get('get-all-muestras-heterotroficas', 'EnsayoHeterotroficasController@allMuestras');

        Route::post('store-tendido-heterotroficas', 'EnsayoHeterotroficasController@storeTendido');

        Route::post('get-show-ensayo-heterotroficas', 'EnsayoHeterotroficasController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-heterotroficas', 'EnsayoHeterotroficasController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-heterotroficas', 'ObservacionesMicroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-heterotroficas', 'ObservacionesMicroController@all')->name('all');

        //Aqui empieza lo de nitritos

        // Ruta para la gestion de aluminioget-datos-blanco
        Route::resource('ensayo-nitrito', 'EnsayoNitritosController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-nitrito/{id}', 'EnsayoNitritosController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-nitrito', 'EnsayoNitritosController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-nitrito', 'EnsayoNitritosController@getCondicionEnsayos');

        Route::get('get-all-nitrito', 'EnsayoNitritosController@getAllNitritos');

        Route::get('get-ejecutar-nitrito', 'EnsayoNitritosController@indexEjecutar');

        Route::post('get-estado-nitrito', 'EnsayoNitritosController@estadoEnsayo');

        Route::get('get-all-muestras-nitrito', 'EnsayoNitritosController@allMuestras');

        Route::post('store-tendido-nitrito', 'EnsayoNitritosController@storeTendido');

        Route::get('get-datos-tendido-nitrito', 'EnsayoNitritosController@allTendido');

        Route::get('get-tendido-finalizado-nitrito', 'EnsayoNitritosController@allTendidoFinalizado');

        Route::get('get-decimales-patron-nitrito', 'EnsayoNitritosController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-nitrito', 'EnsayoNitritosController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-nitrito', 'EnsayoNitritosController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-nitrito', 'EnsayoNitritosController@consultaDecimalesEnsayo');

        Route::post('dpr-store-nitrito', 'EnsayoNitritosController@storeDpr');

        Route::get('get-datos-pr-nitrito/{id}', 'EnsayoNitritosController@getDatosPr');

        Route::post('porcentual-relativa-store-nitrito', 'EnsayoNitritosController@storeRelativa');

        Route::get('get-datos-relativa-nitrito/{id}', 'EnsayoNitritosController@getDatosRelativa');

        Route::post('get-show-ensayo-nitrito', 'EnsayoNitritosController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-nitrito', 'EnsayoNitritosController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-nitrito', 'ObservacionesDuplicadoNitritosController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-duplicados-nitrito', 'ObservacionesDuplicadoNitritosController@all')->name('all');

        //Aqui empieza lo de alcalinidad
        Route::resource('ensayo-alcalinidad', 'EnsayoAlcalinidadController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-alcalinidad/{id}', 'EnsayoAlcalinidadController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-alcalinidad', 'EnsayoAlcalinidadController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-alcalinidad', 'EnsayoAlcalinidadController@getCondicionEnsayos');

        Route::get('get-all-alcalinidad', 'EnsayoAlcalinidadController@getAllAlcalinidad');

        Route::get('get-ejecutar-alcalinidad', 'EnsayoAlcalinidadController@indexEjecutar');

        Route::get('get-all-muestras-alcalinidad', 'EnsayoAlcalinidadController@allMuestras');

        Route::post('get-estado-alcalinidad', 'EnsayoAlcalinidadController@estadoEnsayo');

        Route::post('store-tendido-alcalinidad', 'EnsayoAlcalinidadController@storeTendido');

        Route::get('get-datos-tendido-alcalinidad', 'EnsayoAlcalinidadController@allTendido');

        Route::get('get-tendido-finalizado-alcalinidad', 'EnsayoAlcalinidadController@allTendidoFinalizado');

        Route::get('get-decimales-patron-alcalinidad', 'EnsayoAlcalinidadController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-alcalinidad', 'EnsayoAlcalinidadController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-alcalinidad', 'EnsayoAlcalinidadController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-alcalinidad', 'EnsayoAlcalinidadController@consultaDecimalesEnsayo');

        Route::post('dpr-store-alcalinidad', 'EnsayoAlcalinidadController@storeDpr');

        Route::get('get-datos-promedio-alcalinidad/{id}', 'EnsayoAlcalinidadController@getDatosPromedio');

        Route::get('get-dpr-promedio-alcalinidad/{id}', 'EnsayoAlcalinidadController@getDprPromedio');

        Route::post('porcentual-relativa-store-alcalinidad', 'EnsayoAlcalinidadController@storeRelativa');

        Route::get('get-datos-relativa-alcalinidad/{id}', 'EnsayoAlcalinidadController@getDatosRelativa');

        Route::post('get-show-ensayo-alcalinidad', 'EnsayoAlcalinidadController@getAllShowEnsayo');

        Route::post('get-show-blanco-patron-alcalinidad', 'EnsayoAlcalinidadController@getAllShowPatronBlanco');

        // // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-alcalinidad', 'ObservacionesDuplicadoAlcalinidadController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-alcalinidad', 'ObservacionesDuplicadoAlcalinidadController@all')->name('all');

        //Aqui empieza lo de Acidez
        Route::resource('ensayo-acidez', 'EnsayoAcidezController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-acidez/{id}', 'EnsayoAcidezController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-acidez', 'EnsayoAcidezController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-acidez', 'EnsayoAcidezController@getCondicionEnsayos');

        Route::get('get-all-acidez', 'EnsayoAcidezController@getAllAcidez');

        Route::post('get-estado-acidez', 'EnsayoAcidezController@estadoEnsayo');

        Route::get('get-ejecutar-acidez', 'EnsayoAcidezController@indexEjecutar');

        Route::get('get-all-muestras-acidez', 'EnsayoAcidezController@allMuestras');

        Route::post('store-tendido-acidez', 'EnsayoAcidezController@storeTendido');

        Route::get('get-datos-tendido-acidez', 'EnsayoAcidezController@allTendido');

        Route::get('get-dato-concentracion-acidez', 'EnsayoAcidezController@getConcentracion');

        Route::get('get-tendido-finalizado-acidez', 'EnsayoAcidezController@allTendidoFinalizado');

        Route::get('get-decimales-patron-acidez', 'EnsayoAcidezController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-acidez', 'EnsayoAcidezController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-acidez', 'EnsayoAcidezController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-acidez', 'EnsayoAcidezController@consultaDecimalesEnsayo');

        Route::post('dpr-store-acidez', 'EnsayoAcidezController@storeDpr');

        Route::get('get-datos-promedio-acidez/{id}', 'EnsayoAcidezController@getDatosPromedio');

        Route::post('porcentual-relativa-store-acidez', 'EnsayoAcidezController@storeRelativa');

        Route::get('get-datos-relativa-acidez/{id}', 'EnsayoAcidezController@getDatosRelativa');

        Route::post('get-show-ensayo-acidez', 'EnsayoAcidezController@getAllShowEnsayo');

        Route::get('get-ultimo-acidez', 'EnsayoAcidezController@getUltimoBlanco');

        Route::post('get-show-blanco-patron-acidez', 'EnsayoAcidezController@getAllShowPatronBlanco');

        // // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-acidez', 'ObservacionesDuplicadoAcidezController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-acidez', 'ObservacionesDuplicadoAcidezController@all')->name('all');

        //Aqui empieza lo de Cloruro
        Route::resource('ensayo-cloruro', 'EnsayoCloruroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-cloruro/{id}', 'EnsayoCloruroController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-cloruro', 'EnsayoCloruroController@getDatosBlanco');

        Route::post('get-estado-cloruro', 'EnsayoCloruroController@estadoEnsayo');

        Route::get('get-ejecutar-ensayo-cloruro', 'EnsayoCloruroController@getCondicionEnsayos');

        Route::get('get-all-cloruro', 'EnsayoCloruroController@getAllCloruro');

        Route::get('get-ejecutar-cloruro', 'EnsayoCloruroController@indexEjecutar');

        Route::get('get-all-muestras-cloruro', 'EnsayoCloruroController@allMuestras');

        Route::post('store-tendido-cloruro', 'EnsayoCloruroController@storeTendido');

        Route::get('get-datos-tendido-cloruro', 'EnsayoCloruroController@allTendido');

        Route::get('get-dato-concentracion-cloruro', 'EnsayoCloruroController@getConcentracion');

        Route::get('get-tendido-finalizado-cloruro', 'EnsayoCloruroController@allTendidoFinalizado');

        Route::get('get-decimales-patron-cloruro', 'EnsayoCloruroController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-cloruro', 'EnsayoCloruroController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-cloruro', 'EnsayoCloruroController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-cloruro', 'EnsayoCloruroController@consultaDecimalesEnsayo');

        Route::post('dpr-store-cloruro', 'EnsayoCloruroController@storeDpr');

        Route::get('get-datos-promedio-cloruro/{id}', 'EnsayoCloruroController@getDatosPromedio');

        Route::post('porcentual-relativa-store-cloruro', 'EnsayoCloruroController@storeRelativa');

        Route::get('get-datos-relativa-cloruro/{id}', 'EnsayoCloruroController@getDatosRelativa');

        Route::post('get-show-ensayo-cloruro', 'EnsayoCloruroController@getAllShowEnsayo');

        Route::get('get-ultimo-cloruro', 'EnsayoCloruroController@getUltimoBlanco');

        Route::post('get-show-blanco-patron-cloruro', 'EnsayoCloruroController@getAllShowPatronBlanco');

        // // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-cloruro', 'ObservacionesDuplicadoCloruroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-cloruro', 'ObservacionesDuplicadoCloruroController@all')->name('all');

        //Aqui empieza lo de calcio
        Route::resource('ensayo-calcio', 'EnsayoCalcioController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-calcio/{id}', 'EnsayoCalcioController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-calcio', 'EnsayoCalcioController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-calcio', 'EnsayoCalcioController@getCondicionEnsayos');

        Route::get('get-all-calcio', 'EnsayoCalcioController@getAllCalcio');

        Route::get('get-ejecutar-calcio', 'EnsayoCalcioController@indexEjecutar');

        Route::post('get-estado-calcio', 'EnsayoCalcioController@estadoEnsayo');

        Route::get('get-all-muestras-calcio', 'EnsayoCalcioController@allMuestras');

        Route::post('store-tendido-calcio', 'EnsayoCalcioController@storeTendido');

        Route::get('get-datos-tendido-calcio', 'EnsayoCalcioController@allTendido');

        Route::get('get-dato-concentracion-calcio', 'EnsayoCalcioController@getConcentracion');

        Route::get('get-datos-primario-calcio', 'EnsayoCalcioController@getDatoPrimario');

        Route::get('get-tendido-finalizado-calcio', 'EnsayoCalcioController@allTendidoFinalizado');

        Route::get('get-decimales-patron-calcio', 'EnsayoCalcioController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-calcio', 'EnsayoCalcioController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-calcio', 'EnsayoCalcioController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-calcio', 'EnsayoCalcioController@consultaDecimalesEnsayo');

        Route::post('dpr-store-calcio', 'EnsayoCalcioController@storeDpr');

        Route::get('get-datos-promedio-calcio/{id}', 'EnsayoCalcioController@getDatosPromedio');

        Route::post('porcentual-relativa-store-calcio', 'EnsayoCalcioController@storeRelativa');

        Route::get('get-datos-relativa-calcio/{id}', 'EnsayoCalcioController@getDatosRelativa');

        Route::post('get-show-ensayo-calcio', 'EnsayoCalcioController@getAllShowEnsayo');

        Route::get('get-ultimo-calcio', 'EnsayoCalcioController@getUltimoBlanco');

        Route::post('get-show-blanco-patron-calcio', 'EnsayoCalcioController@getAllShowPatronBlanco');

        // // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-calcio', 'ObservacionesDuplicadoCalcioController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-calcio', 'ObservacionesDuplicadoCalcioController@all')->name('all');

        //Aqui empieza lo de cloro
        Route::resource('ensayo-cloro', 'EnsayoCloroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-cloro/{id}', 'EnsayoCloroController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-cloro', 'EnsayoCloroController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-cloro', 'EnsayoCloroController@getCondicionEnsayos');

        Route::get('get-all-cloro', 'EnsayoCloroController@getAllCloro');

        Route::get('get-ejecutar-cloro', 'EnsayoCloroController@indexEjecutar');

        Route::post('get-estado-cloro', 'EnsayoCloroController@estadoEnsayo');

        Route::get('get-all-muestras-cloro', 'EnsayoCloroController@allMuestras');

        Route::post('store-tendido-cloro', 'EnsayoCloroController@storeTendido');

        Route::get('get-datos-tendido-cloro', 'EnsayoCloroController@allTendido');

        Route::get('get-dato-concentracion-cloro', 'EnsayoCloroController@getConcentracion');

        Route::get('get-datos-primario-cloro', 'EnsayoCloroController@getDatoPrimario');

        Route::get('get-tendido-finalizado-cloro', 'EnsayoCloroController@allTendidoFinalizado');

        Route::get('get-decimales-patron-cloro', 'EnsayoCloroController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-cloro', 'EnsayoCloroController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-cloro', 'EnsayoCloroController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-cloro', 'EnsayoCloroController@consultaDecimalesEnsayo');

        Route::post('dpr-store-cloro', 'EnsayoCloroController@storeDpr');

        Route::get('get-datos-promedio-cloro/{id}', 'EnsayoCloroController@getDatosPromedio');

        Route::post('porcentual-relativa-store-cloro', 'EnsayoCloroController@storeRelativa');

        Route::get('get-datos-relativa-cloro/{id}', 'EnsayoCloroController@getDatosRelativa');

        Route::post('get-show-ensayo-cloro', 'EnsayoCloroController@getAllShowEnsayo');

        Route::get('get-ultimo-cloro', 'EnsayoCloroController@getUltimoBlanco');

        Route::post('get-show-blanco-patron-cloro', 'EnsayoCloroController@getAllShowPatronBlanco');

        // // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-cloro', 'ObservacionesDuplicadoCloroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-cloro', 'ObservacionesDuplicadoCloroController@all')->name('all');

        //Aqui empieza lo de dureza
        Route::resource('ensayo-dureza', 'EnsayoDurezaController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-dureza/{id}', 'EnsayoDurezaController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-dureza', 'EnsayoDurezaController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-dureza', 'EnsayoDurezaController@getCondicionEnsayos');

        Route::get('get-all-dureza', 'EnsayoDurezaController@getAllDureza');

        Route::get('get-ejecutar-dureza', 'EnsayoDurezaController@indexEjecutar');

        Route::get('get-all-muestras-dureza', 'EnsayoDurezaController@allMuestras');

        Route::post('store-tendido-dureza', 'EnsayoDurezaController@storeTendido');

        Route::get('get-datos-tendido-dureza', 'EnsayoDurezaController@allTendido');

        Route::post('get-estado-dureza', 'EnsayoDurezaController@estadoEnsayo');

        Route::get('get-dato-concentracion-dureza', 'EnsayoDurezaController@getConcentracion');

        Route::get('get-datos-primario-dureza', 'EnsayoDurezaController@getDatoPrimario');

        Route::get('get-tendido-finalizado-dureza', 'EnsayoDurezaController@allTendidoFinalizado');

        Route::get('get-decimales-patron-dureza', 'EnsayoDurezaController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-dureza', 'EnsayoDurezaController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-dureza', 'EnsayoDurezaController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-dureza', 'EnsayoDurezaController@consultaDecimalesEnsayo');

        Route::post('dpr-store-dureza', 'EnsayoDurezaController@storeDpr');

        Route::get('get-datos-promedio-dureza/{id}', 'EnsayoDurezaController@getDatosPromedio');

        Route::post('porcentual-relativa-store-dureza', 'EnsayoDurezaController@storeRelativa');

        Route::get('get-datos-relativa-dureza/{id}', 'EnsayoDurezaController@getDatosRelativa');

        Route::post('get-show-ensayo-dureza', 'EnsayoDurezaController@getAllShowEnsayo');

        Route::get('get-ultimo-dureza', 'EnsayoDurezaController@getUltimoBlanco');

        Route::post('get-show-blanco-patron-dureza', 'EnsayoDurezaController@getAllShowPatronBlanco');

        // // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-dureza', 'ObservacionesDuplicadoDurezaController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-dureza', 'ObservacionesDuplicadoDurezaController@all')->name('all');

        //Aqui empieza lo de turbidez
        Route::resource('ensayo-turbidez', 'EnsayoTurbidezController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-turbidez/{id}', 'EnsayoTurbidezController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-turbidez', 'EnsayoTurbidezController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-turbidez', 'EnsayoTurbidezController@getCondicionEnsayos');

        Route::get('get-all-turbidez', 'EnsayoTurbidezController@getAllTurbidez');

        Route::get('get-ejecutar-turbidez', 'EnsayoTurbidezController@indexEjecutar');

        Route::post('get-estado-turbidez', 'EnsayoTurbidezController@estadoEnsayo');

        Route::get('get-all-muestras-turbidez', 'EnsayoTurbidezController@allMuestras');

        Route::post('store-tendido-turbidez', 'EnsayoTurbidezController@storeTendido');

        Route::get('get-datos-tendido-turbidez', 'EnsayoTurbidezController@allTendido');

        Route::get('get-dato-concentracion-turbidez', 'EnsayoTurbidezController@getConcentracion');

        Route::get('get-datos-primario-turbidez', 'EnsayoTurbidezController@getDatoPrimario');

        Route::get('get-tendido-finalizado-turbidez', 'EnsayoTurbidezController@allTendidoFinalizado');

        Route::get('get-decimales-patron-turbidez', 'EnsayoTurbidezController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-turbidez', 'EnsayoTurbidezController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-turbidez', 'EnsayoTurbidezController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-turbidez', 'EnsayoTurbidezController@consultaDecimalesEnsayo');

        Route::post('dpr-store-turbidez', 'EnsayoTurbidezController@storeDpr');

        Route::get('get-std', 'EnsayoTurbidezController@getStd');

        Route::get('get-datos-promedio-turbidez/{id}', 'EnsayoTurbidezController@getDatosPromedio');

        Route::post('porcentual-relativa-store-turbidez', 'EnsayoTurbidezController@storeRelativa');

        Route::get('get-datos-relativa-turbidez/{id}', 'EnsayoTurbidezController@getDatosRelativa');

        Route::post('get-show-ensayo-turbidez', 'EnsayoTurbidezController@getAllShowEnsayo');

        Route::get('get-ultimo-turbidez', 'EnsayoTurbidezController@getUltimoBlanco');

        Route::post('get-show-blanco-patron-turbidez', 'EnsayoTurbidezController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-turbidez', 'ObservacionesDuplicadoTurbiedadController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-turbidez', 'ObservacionesDuplicadoTurbiedadController@all')->name('all');

        //Aqui empieza lo de ph
        Route::resource('ensayo-ph', 'EnsayoPhController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-ph/{id}', 'EnsayoPhController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-ph', 'EnsayoPhController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-ph', 'EnsayoPhController@getCondicionEnsayos');

        Route::get('get-all-ph', 'EnsayoPhController@getAllPh');

        Route::get('get-ejecutar-ph', 'EnsayoPhController@indexEjecutar');

        Route::post('get-estado-ph', 'EnsayoPhController@estadoEnsayo');

        Route::get('get-all-muestras-ph', 'EnsayoPhController@allMuestras');

        Route::post('store-tendido-ph', 'EnsayoPhController@storeTendido');

        Route::get('get-datos-tendido-ph', 'EnsayoPhController@allTendido');

        Route::get('get-dato-concentracion-ph', 'EnsayoPhController@getConcentracion');

        Route::get('get-datos-primario-ph', 'EnsayoPhController@getDatoPrimario');

        Route::get('get-tendido-finalizado-ph', 'EnsayoPhController@allTendidoFinalizado');

        Route::get('get-decimales-patron-ph', 'EnsayoPhController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-ph', 'EnsayoPhController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-ph', 'EnsayoPhController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-ph', 'EnsayoPhController@consultaDecimalesEnsayo');

        Route::post('dpr-store-ph', 'EnsayoPhController@storeDpr');

        Route::get('get-datos-promedio-ph/{id}', 'EnsayoPhController@getDatosPromedio');

        Route::post('porcentual-relativa-store-ph', 'EnsayoPhController@storeRelativa');

        Route::get('get-datos-relativa-ph/{id}', 'EnsayoPhController@getDatosRelativa');

        Route::post('get-show-ensayo-ph', 'EnsayoPhController@getAllShowEnsayo');

        Route::get('get-ultimo-ph', 'EnsayoPhController@getUltimoBlanco');

        Route::post('get-show-blanco-patron-ph', 'EnsayoPhController@getAllShowPatronBlanco');

        // // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-ph', 'ObservacionesDuplicadoPhController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-ph', 'ObservacionesDuplicadoPhController@all')->name('all');

        //Aqui empieza lo de color
        Route::resource('ensayo-color', 'EnsayoColorController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-std-color', 'EnsayoColorController@getStd');

        Route::get('get-ensayop-color/{id}', 'EnsayoColorController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-color', 'EnsayoColorController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-color', 'EnsayoColorController@getCondicionEnsayos');

        Route::get('get-all-color', 'EnsayoColorController@getAllColor');

        Route::get('get-ejecutar-color', 'EnsayoColorController@indexEjecutar');

        Route::get('get-all-muestras-color', 'EnsayoColorController@allMuestras');

        Route::post('store-tendido-color', 'EnsayoColorController@storeTendido');

        Route::post('get-estado-color', 'EnsayoColorController@estadoEnsayo');

        Route::get('get-datos-tendido-color', 'EnsayoColorController@allTendido');

        Route::get('get-dato-concentracion-color', 'EnsayoColorController@getConcentracion');

        Route::get('get-datos-primario-color', 'EnsayoColorController@getDatoPrimario');

        Route::get('get-tendido-finalizado-color', 'EnsayoColorController@allTendidoFinalizado');

        Route::get('get-decimales-patron-color', 'EnsayoColorController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-color', 'EnsayoColorController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-color', 'EnsayoColorController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-color', 'EnsayoColorController@consultaDecimalesEnsayo');

        Route::post('dpr-store-color', 'EnsayoColorController@storeDpr');

        Route::get('get-datos-promedio-color/{id}', 'EnsayoColorController@getDatosPromedio');

        Route::post('porcentual-relativa-store-color', 'EnsayoColorController@storeRelativa');

        Route::get('get-datos-relativa-color/{id}', 'EnsayoColorController@getDatosRelativa');

        Route::post('get-show-ensayo-color', 'EnsayoColorController@getAllShowEnsayo');

        Route::get('get-ultimo-color', 'EnsayoColorController@getUltimoBlanco');

        Route::post('get-show-blanco-patron-color', 'EnsayoColorController@getAllShowPatronBlanco');

        // // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-color', 'ObservacionesDuplicadoColorController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-color', 'ObservacionesDuplicadoColorController@all')->name('all');

        //Aqui empieza lo de conductividad
        Route::resource('ensayo-conductividad', 'EnsayoConductividadController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-conductividad/{id}', 'EnsayoConductividadController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-conductividad', 'EnsayoConductividadController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-conductividad', 'EnsayoConductividadController@getCondicionEnsayos');

        Route::get('get-all-conductividad', 'EnsayoConductividadController@getAllConductividad');

        Route::get('get-ejecutar-conductividad', 'EnsayoConductividadController@indexEjecutar');

        Route::get('get-all-muestras-conductividad', 'EnsayoConductividadController@allMuestras');

        Route::post('store-tendido-conductividad', 'EnsayoConductividadController@storeTendido');

        Route::post('get-estado-conductividad', 'EnsayoConductividadController@estadoEnsayo');

        Route::get('get-datos-tendido-conductividad', 'EnsayoConductividadController@allTendido');

        Route::get('get-dato-concentracion-conductividad', 'EnsayoConductividadController@getConcentracion');

        Route::get('get-datos-primario-conductividad', 'EnsayoConductividadController@getDatoPrimario');

        Route::get('get-tendido-finalizado-conductividad', 'EnsayoConductividadController@allTendidoFinalizado');

        Route::get('get-decimales-patron-conductividad', 'EnsayoConductividadController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-conductividad', 'EnsayoConductividadController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-conductividad', 'EnsayoConductividadController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-conductividad', 'EnsayoConductividadController@consultaDecimalesEnsayo');

        Route::post('dpr-store-conductividad', 'EnsayoConductividadController@storeDpr');

        Route::get('get-datos-promedio-conductividad/{id}', 'EnsayoConductividadController@getDatosPromedio');

        Route::post('porcentual-relativa-store-conductividad', 'EnsayoConductividadController@storeRelativa');

        Route::get('get-datos-relativa-conductividad/{id}', 'EnsayoConductividadController@getDatosRelativa');

        Route::post('get-show-ensayo-conductividad', 'EnsayoConductividadController@getAllShowEnsayo');

        Route::get('get-ultimo-conductividad', 'EnsayoConductividadController@getUltimoBlanco');

        Route::post('get-show-blanco-patron-conductividad', 'EnsayoConductividadController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-conduc', 'ObservacionesDuplicadoConductividadController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-conductividad', 'ObservacionesDuplicadoConductividadController@all')->name('all');

        //Aqui empieza lo de sustancias
        Route::resource('ensayo-sustancias', 'EnsayoSustanciasFlotantesController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-sustancias/{id}', 'EnsayoSustanciasFlotantesController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-sustancias', 'EnsayoSustanciasFlotantesController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-sustancias', 'EnsayoSustanciasFlotantesController@getCondicionEnsayos');

        Route::get('get-all-sustancias', 'EnsayoSustanciasFlotantesController@getAllSustanciasFlotantes');

        Route::get('get-ejecutar-sustancias', 'EnsayoSustanciasFlotantesController@indexEjecutar');

        Route::get('get-all-muestras-sustancias', 'EnsayoSustanciasFlotantesController@allMuestras');

        Route::post('store-tendido-sustancias', 'EnsayoSustanciasFlotantesController@storeTendido');

        Route::post('get-estado-sustancias', 'EnsayoSustanciasFlotantesController@estadoEnsayo');

        Route::get('get-datos-tendido-sustancias', 'EnsayoSustanciasFlotantesController@allTendido');

        Route::get('get-dato-concentracion-sustancias', 'EnsayoSustanciasFlotantesController@getConcentracion');

        Route::get('get-datos-primario-sustancias', 'EnsayoSustanciasFlotantesController@getDatoPrimario');

        Route::get('get-tendido-finalizado-sustancias', 'EnsayoSustanciasFlotantesController@allTendidoFinalizado');

        Route::get('get-decimales-patron-sustancias', 'EnsayoSustanciasFlotantesController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-sustancias', 'EnsayoSustanciasFlotantesController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-sustancias', 'EnsayoSustanciasFlotantesController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-sustancias', 'EnsayoSustanciasFlotantesController@consultaDecimalesEnsayo');

        Route::post('dpr-store-sustancias', 'EnsayoSustanciasFlotantesController@storeDpr');

        Route::get('get-datos-promedio-sustancias/{id}', 'EnsayoSustanciasFlotantesController@getDatosPromedio');

        Route::post('porcentual-relativa-store-sustancias', 'EnsayoSustanciasFlotantesController@storeRelativa');

        Route::get('get-datos-relativa-sustancias/{id}', 'EnsayoSustanciasFlotantesController@getDatosRelativa');

        Route::post('get-show-ensayo-sustancias', 'EnsayoSustanciasFlotantesController@getAllShowEnsayo');

        Route::get('get-ultimo-sustancias', 'EnsayoSustanciasFlotantesController@getUltimoBlanco');

        Route::post('get-show-blanco-patron-sustancias', 'EnsayoSustanciasFlotantesController@getAllShowPatronBlanco');

        // // Ruta para la gestion de observacionDuplicados
        // Route::resource('observacion-duplicados-sustancias', 'ObservacionesDuplicadoSustanciasFlotantesController', ['only' => [
        //     'index', 'store', 'update', 'destroy',
        // ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        // Route::get('get-observacion-sustancias', 'ObservacionesDuplicadoSustanciasFlotanteController@all')->name('all');

        //Aqui empieza lo de olor
        Route::resource('ensayo-olor', 'EnsayoOlorController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);

        Route::get('get-ensayop-olor/{id}', 'EnsayoOlorController@getEnsayoPrincipal');

        Route::get('get-datos-blanco-olor', 'EnsayoOlorController@getDatosBlanco');

        Route::get('get-ejecutar-ensayo-olor', 'EnsayoOlorController@getCondicionEnsayos');

        Route::get('get-all-olor', 'EnsayoOlorController@getAllOlor');

        Route::get('get-ejecutar-olor', 'EnsayoOlorController@indexEjecutar');

        Route::get('get-all-muestras-olor', 'EnsayoOlorController@allMuestras');

        Route::post('store-tendido-olor', 'EnsayoOlorController@storeTendido');

        Route::post('get-estado-olor', 'EnsayoOlorController@estadoEnsayo');

        Route::get('get-datos-tendido-olor', 'EnsayoOlorController@allTendido');

        Route::get('get-dato-concentracion-olor', 'EnsayoOlorController@getConcentracion');

        Route::get('get-datos-primario-olor', 'EnsayoOlorController@getDatoPrimario');

        Route::get('get-tendido-finalizado-olor', 'EnsayoOlorController@allTendidoFinalizado');

        Route::get('get-decimales-patron-olor', 'EnsayoOlorController@consultaDecimalesPatron');

        Route::get('get-decimales-blanco-olor', 'EnsayoOlorController@consultaDecimalesBlanco');

        Route::get('get-decimales-ensayo-olor', 'EnsayoOlorController@consultaDecimalesEnsayo');

        Route::get('get-decimales-ensayo-olor', 'EnsayoOlorController@consultaDecimalesEnsayo');

        Route::post('dpr-store-olor', 'EnsayoOlorController@storeDpr');

        Route::get('get-datos-promedio-olor/{id}', 'EnsayoOlorController@getDatosPromedio');

        Route::post('porcentual-relativa-store-olor', 'EnsayoOlorController@storeRelativa');

        Route::get('get-datos-relativa-olor/{id}', 'EnsayoOlorController@getDatosRelativa');

        Route::post('get-show-ensayo-olor', 'EnsayoOlorController@getAllShowEnsayo');

        Route::get('get-ultimo-olor', 'EnsayoOlorController@getUltimoBlanco');

        Route::post('get-show-blanco-patron-olor', 'EnsayoOlorController@getAllShowPatronBlanco');

        // Ruta para la gestion de observacionDuplicados
        Route::resource('observacion-duplicados-olor', 'ObservacionesDuplicadoOlorController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionDuplicados
        Route::get('get-observacion-olor', 'ObservacionesDuplicadoOlorController@all')->name('all');

        //Aqui se exporta el excel

        Route::post('get-export-excel-aluminio', 'EnsayoAluminioController@exporta');
        Route::post('get-export-excel-cloruro', 'EnsayoCloruroController@exporta');
        Route::post('get-export-excel-nitrato', 'EnsayoNitratosController@exporta');
        Route::post('get-export-excel-nitrito', 'EnsayoNitritosController@exporta');
        Route::post('get-export-excel-fosfato', 'EnsayoFosfatoController@exporta');
        Route::post('get-export-excel-hierro', 'EnsayoHierroController@exporta');
        Route::post('get-export-excel-carbono', 'EnsayoCarbonoOrganicoController@exporta');
        Route::post('get-export-excel-calcio', 'EnsayoCalcioController@exporta');
        Route::post('get-export-excel-cloro', 'EnsayoCloroController@exporta');
        Route::post('get-export-excel-dureza', 'EnsayoDurezaController@exporta');
        Route::post('get-export-excel-acidez', 'EnsayoAcidezController@exporta');
        Route::post('get-export-excel-plomo', 'EnsayoPlomoController@exporta');
        Route::post('get-export-excel-cadmio', 'EnsayoCadmioController@exporta');
        Route::post('get-export-excel-mercurio', 'EnsayoMercurioController@exporta');
        Route::post('get-export-excel-hidrocarburos', 'EnsayoHidrocarburosController@exporta');
        Route::post('get-export-excel-plaguicidas', 'EnsayoPlaguicidaController@exporta');
        Route::post('get-export-excel-trialometanos', 'EnsayoTrialometanosController@exporta');
        Route::post('get-export-excel-alcalinidad', 'EnsayoAlcalinidadController@exporta');
        Route::post('get-export-excel-sulfatos', 'EnsayoSulfatosController@exporta');
        Route::post('get-export-excel-coliformes', 'EnsayoColiformesController@exporta');
        Route::post('get-export-excel-escherichia', 'EnsayoEscherichiaController@exporta');
        Route::post('get-export-excel-heterotroficas', 'EnsayoHeterotroficasController@exporta');
        Route::post('get-export-excel-olor', 'EnsayoOlorController@exporta');
        Route::post('get-export-excel-conductividad', 'EnsayoConductividadController@exporta');
        Route::post('get-export-excel-color', 'EnsayoColorController@exporta');
        Route::post('get-export-excel-fluoruro', 'EnsayoFluorurosController@exporta');
        Route::post('get-export-excel-disueltos', 'EnsayoSolidosDisController@exporta');
        Route::post('get-export-excel-secos', 'EnsayoSolidosSecosController@exporta');
        Route::post('get-export-excel-turbidez', 'EnsayoTurbidezController@exporta');
        Route::post('get-export-excel-ph', 'EnsayoPhController@exporta');

        //Aqui se exportan las rutas de los exceles de google
        Route::get('get-google-excel-aluminio', 'SheetsGoogleController@sheetOperation');

        //Ruta para recuperar informacion de  cloro residual
        Route::get('get-cloruro-information/{id}', 'SampleTakingController@getInformationCloruro');

        //Ruta para obtener el codigo de la muestra con dos de  cloro residual
        Route::get('get-consecutivo-toma/{id}', 'StartSamplingController@getInformationMuestra');

        //Ruta para obtener el codigo de la muestra con promedio de  cloro residual
        Route::get('get-valor-toma/{id}', 'StartSamplingController@getInformationMuestraPromedio');

        //Ruta para obtener el codigo de la muestra con promedio de  cloro residual
        Route::get('get-valor-dpr/{id}', 'StartSamplingController@getCloroDpr');

        //Ver datos en el formulario
        Route::get('get-show-ensayos/{id}/{optional?}', 'EnsayoRelacionadosController@show');

        // Ruta para la gestion de observacionesEspectros
        Route::resource('observaciones-espectros', 'ObservacionesEspectroController', ['only' => [
            'index', 'store', 'update', 'destroy',
        ]]);
        // Ruta que obtiene todos los registros de observacionesEspectros
        Route::get('get-observaciones-espectros', 'ObservacionesEspectroController@all')->name('all');
        // Ruta para exportar los datos de observacionesEspectros
        Route::post('export-observacionesEspectros', 'ObservacionesEspectroController@export')->name('export');


//---------------------------------++++++++++--------------------------------------------------------------------

       
        // Ruta para la gestion de observacionesMicros
        Route::resource('observaciones-micros', 'ObservacionesMicroController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de observacionesMicros
        Route::get('get-observaciones-micros', 'ObservacionesMicroController@all')->name('all');
        // Ruta para exportar los datos de observacionesMicros
        Route::post('export-observacionesMicros', 'ObservacionesMicroController@export')->name('export');


    });

     // Ruta para la gestion de reportManagements
     Route::resource('report-managements', 'ReportManagementController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de reportManagements
    Route::get('get-report-managements', 'ReportManagementController@all')->name('all');
    Route::put('change-status-report', 'ReportManagementController@changeStatusReport');
    Route::get('generate-r/{id}', 'ReportManagementController@getReport');
    Route::get('get-daily-report/{id}', 'ReportManagementController@getDailyReport');
    Route::get('get-customer', 'ReportManagementController@getCustomer');

    // Ruta para exportar los datos de reportManagements
    Route::post('export-reportManagements', 'ReportManagementController@export')->name('export');

    // Ruta para la gestion de reporManagementAttachments
    Route::resource('repor-management-attachments', 'ReporManagementAttachmentController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de reporManagementAttachments
    Route::get('get-repor-management-attachments', 'ReporManagementAttachmentController@all')->name('all');
    // Ruta para exportar los datos de reporManagementAttachments
    Route::post('export-reporManagementAttachments', 'ReporManagementAttachmentController@export')->name('export');

    // Ruta para la gestion de consecutiveSettings
    Route::resource('consecutive-settings', 'ConsecutiveSettingController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    
    Route::get('get-consecutive/{newField}', 'ConsecutiveSettingController@getConsecutive');
    // Ruta que obtiene todos los registros de consecutiveSettings
    Route::get('get-consecutive-settings', 'ConsecutiveSettingController@all')->name('all');
    // Ruta para exportar los datos de consecutiveSettings
    Route::post('export-consecutiveSettings', 'ConsecutiveSettingController@export')->name('export');


    // Ruta para la gestion de informCustomers
    Route::resource('inform-customers', 'InformCustomerController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de informCustomers
    Route::get('get-inform-customers', 'InformCustomerController@all')->name('all');
    // Ruta para exportar los datos de informCustomers
    Route::post('export-informCustomers', 'InformCustomerController@export')->name('export');


    // Ruta para la gestion de informCustomerAttecheds
    Route::resource('inform-customer-attecheds', 'InformCustomerAttechedController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de informCustomerAttecheds
    Route::get('get-inform-customer-attecheds', 'InformCustomerAttechedController@all')->name('all');
    // Ruta para exportar los datos de informCustomerAttecheds
    Route::post('export-informCustomerAttecheds', 'InformCustomerAttechedController@export')->name('export');




//---------------------------------++++++++++--------------------------------------------------------------------


    //Ruta para la vista publica
    Route::resource('public-sample', 'PublicQrController', ['only' => [
        'index',
    ]]);
    Route::get('get-public-sample', 'PublicQrController@all')->name('all');

    Route::get('get-carta-desviacion/{id}', 'ListTrialsController@enviaDesviacion');
    Route::get('get-carta-media/{id}', 'ListTrialsController@enviaMedia');
    Route::get('get-carta-dias/{id}', 'ListTrialsController@getCartaMuestraDias');

});

// Ruta para la gestion de observacionesDuplicadoCloruros
Route::resource('observacionesDuplicadoCloruros', 'ObservacionesDuplicadoCloruroController', ['only' => [
    'index', 'store', 'update', 'destroy',
]]);
// Ruta que obtiene todos los registros de observacionesDuplicadoCloruros
Route::get('get-observacionesDuplicadoCloruros', 'ObservacionesDuplicadoCloruroController@all')->name('all');
// Ruta para exportar los datos de observacionesDuplicadoCloruros
Route::post('export-observacionesDuplicadoCloruros', 'ObservacionesDuplicadoCloruroController@export')->name('export');

// Ruta para la gestion de observacionesDuplicadoCalcios
Route::resource('observacionesDuplicadoCalcios', 'ObservacionesDuplicadoCalcioController', ['only' => [
    'index', 'store', 'update', 'destroy',
]]);
// Ruta que obtiene todos los registros de observacionesDuplicadoCalcios
Route::get('get-observacionesDuplicadoCalcios', 'ObservacionesDuplicadoCalcioController@all')->name('all');
// Ruta para exportar los datos de observacionesDuplicadoCalcios
Route::post('export-observacionesDuplicadoCalcios', 'ObservacionesDuplicadoCalcioController@export')->name('export');

// Ruta para la gestion de observacionesDuplicadoCloros
Route::resource('observacionesDuplicadoCloros', 'ObservacionesDuplicadoCloroController', ['only' => [
    'index', 'store', 'update', 'destroy',
]]);
// Ruta que obtiene todos los registros de observacionesDuplicadoCloros
Route::get('get-observacionesDuplicadoCloros', 'ObservacionesDuplicadoCloroController@all')->name('all');
// Ruta para exportar los datos de observacionesDuplicadoCloros
Route::post('export-observacionesDuplicadoCloros', 'ObservacionesDuplicadoCloroController@export')->name('export');

// Ruta para la gestion de observacionesDuplicadoDurezas
Route::resource('observacionesDuplicadoDurezas', 'ObservacionesDuplicadoDurezaController', ['only' => [
    'index', 'store', 'update', 'destroy',
]]);
// Ruta que obtiene todos los registros de observacionesDuplicadoDurezas
Route::get('get-observacionesDuplicadoDurezas', 'ObservacionesDuplicadoDurezaController@all')->name('all');
// Ruta para exportar los datos de observacionesDuplicadoDurezas
Route::post('export-observacionesDuplicadoDurezas', 'ObservacionesDuplicadoDurezaController@export')->name('export');

// Ruta para la gestion de observacionesDuplicadoTurbidezs
Route::resource('observacionesDuplicadoTurbidezs', 'ObservacionesDuplicadoTurbiedadController', ['only' => [
    'index', 'store', 'update', 'destroy',
]]);
// Ruta que obtiene todos los registros de observacionesDuplicadoTurbidezs
Route::get('get-observacionesDuplicadoTurbidezs', 'ObservacionesDuplicadoTurbiedadController@all')->name('all');
// Ruta para exportar los datos de observacionesDuplicadoTurbidezs
Route::post('export-observacionesDuplicadoTurbidezs', 'ObservacionesDuplicadoTurbiedadController@export')->name('export');

// Ruta para la gestion de observacionesDuplicadoLecturas
Route::resource('observacionesDuplicadoLecturas', 'ObservacionesDuplicadoLecturaController', ['only' => [
    'index', 'store', 'update', 'destroy',
]]);
// Ruta que obtiene todos los registros de observacionesDuplicadoLecturas
Route::get('get-observacionesDuplicadoLecturas', 'ObservacionesDuplicadoLecturaController@all')->name('all');
// Ruta para exportar los datos de observacionesDuplicadoLecturas
Route::post('export-observacionesDuplicadoLecturas', 'ObservacionesDuplicadoLecturaController@export')->name('export');

// Ruta para la gestion de observacionesDuplicadoFluoruros
Route::resource('observacionesDuplicadoFluoruros', 'ObservacionesDuplicadoFluoruroController', ['only' => [
    'index', 'store', 'update', 'destroy'
]]);
// Ruta que obtiene todos los registros de observacionesDuplicadoFluoruros
Route::get('get-observacionesDuplicadoFluoruros', 'ObservacionesDuplicadoFluoruroController@all')->name('all');
// Ruta para exportar los datos de observacionesDuplicadoFluoruros
Route::post('export-observacionesDuplicadoFluoruros', 'ObservacionesDuplicadoFluoruroController@export')->name('export');


// Ruta para la gestion de observacionesDuplicadoSulfatos
Route::resource('observacionesDuplicadoSulfatos', 'ObservacionesDuplicadoSulfatosController', ['only' => [
    'index', 'store', 'update', 'destroy'
]]);
// Ruta que obtiene todos los registros de observacionesDuplicadoSulfatos
Route::get('get-observacionesDuplicadoSulfatos', 'ObservacionesDuplicadoSulfatosController@all')->name('all');
// Ruta para exportar los datos de observacionesDuplicadoSulfatos
Route::post('export-observacionesDuplicadoSulfatos', 'ObservacionesDuplicadoSulfatosController@export')->name('export');


// Ruta para la gestion de observacionesDuplicadoSolidosDis
Route::resource('observacionesDuplicadoSolidosDis', 'ObservacionesDuplicadoSolidosDisController', ['only' => [
    'index', 'store', 'update', 'destroy'
]]);
// Ruta que obtiene todos los registros de observacionesDuplicadoSolidosDis
Route::get('get-observacionesDuplicadoSolidosDis', 'ObservacionesDuplicadoSolidosDisController@all')->name('all');
// Ruta para exportar los datos de observacionesDuplicadoSolidosDis
Route::post('export-observacionesDuplicadoSolidosDis', 'ObservacionesDuplicadoSolidosDisController@export')->name('export');


// // Ruta para la gestion de observacionesDuplicadoSolidosSecos
// Route::resource('observacionesDuplicadoSolidosSecos', 'ObservacionesDuplicadoSolidosSecosController', ['only' => [
//     'index', 'store', 'update', 'destroy'
// ]]);
// // Ruta que obtiene todos los registros de observacionesDuplicadoSolidosSecos
// Route::get('get-observacionesDuplicadoSolidosSecos', 'ObservacionesDuplicadoSolidosSecosController@all')->name('all');
// // Ruta para exportar los datos de observacionesDuplicadoSolidosSecos
// Route::post('export-observacionesDuplicadoSolidosSecos', 'ObservacionesDuplicadoSolidosSecosController@export')->name('export');




// Ruta para la gestion de informCustomerAttecheds
Route::resource('informCustomerAttecheds', 'InformCustomerAttechedController', ['only' => [
    'index', 'store', 'update', 'destroy'
]]);
// Ruta que obtiene todos los registros de informCustomerAttecheds
Route::get('get-informCustomerAttecheds', 'InformCustomerAttechedController@all')->name('all');
// Ruta para exportar los datos de informCustomerAttecheds
Route::post('export-informCustomerAttecheds', 'InformCustomerAttechedController@export')->name('export');
