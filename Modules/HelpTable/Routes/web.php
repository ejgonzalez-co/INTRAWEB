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

Route::group(['prefix' => 'help-table'], function () {
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/', function () {
            return redirect('components?module=help_table');
        });
        // Obtiene lo roles disponibles
        Route::get('get-roles', 'UtilController@roles')->name('get-roles');
        // Obtiene todos los usuarios tic
        Route::get('get-users-tic', 'UtilController@getUsersTic');
        // Obtiene todos los usuarios de soporte tic
        Route::get('get-support-users-tic', 'UtilController@getSupportUsersTic');
        // Obtiene todos los usuarios de administrador tic
        Route::get('get-admin-users-tic', 'UtilController@getAdminUsersTic');
        // Obtiene todos los usuarios proveedores tic
        Route::get('get-supplier-users-tic', 'UtilController@getSupplierUsersTic');
        // Obtiene todos los datos de una constante dependiendo de nombre
        Route::get('get-constants/{name}', 'UtilController@getConstants');

        // Obtiene las versiones del sistema operativo de computadores
        Route::get('get-operating-systems-by-so/{id}', 'UtilController@getOperatingSystemsBySO');
        // Obtiene las versiones de ofimatica
        Route::get('get-state-assets-tic', 'UtilController@getStateAssetsTic');

        // Almacena archivos temporales de mesa de ayuda
        Route::post('temporary-files', 'UtilController@uploadTempFile');

        // Ruta para la gestion de los usuarios tecnicos de la mesa de ayuda tic
        Route::resource('users', 'UserController', ['only' => [
            'index', 'show', 'store', 'update', 'destroy'
        ]]);
        // Obtiene los usuarios tecnicos de la mesa de ayuda tic
        Route::get('get-users', 'UserController@all')->name('all');
        // Ruta para exportar los usuarios tecnicos de la mesa de ayuda tic
        Route::post('export-users', 'UserController@export')->name('export');


        // Ruta para la gestion de los proveedores tics de la mesa de ayuda tic
        Route::resource('tic-providers', 'TicProviderController', ['only' => [
            'index', 'show', 'store', 'update', 'destroy'
        ]]);
        // Obtiene los proveedores tics de la mesa de ayuda tic
        Route::get('get-tic-providers', 'TicProviderController@all')->name('all');
        // Ruta para exportar los proveedores tics de la mesa de ayuda tic
        Route::post('export-tic-providers', 'TicProviderController@export')->name('export');


        // Ruta para la gestion de las vigencias de la mesa de ayuda tic
        Route::resource('tic-period-validities', 'TicPeriodValidityController', ['only' => [
            'index', 'show', 'store', 'update', 'destroy'
        ]]);
        // Obtiene las vigencias de la mesa de ayuda tic
        Route::get('get-tic-period-validities', 'TicPeriodValidityController@all')->name('all');
        // Ruta para exportar las vigencias de la mesa de ayuda tic
        Route::post('export-tic-period-validities', 'TicPeriodValidityController@export')->name('export');

        // Ruta para la gestion de categorias de activos
        Route::resource('tic-type-tic-categories', 'TicTypeTicCategoryController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene las categorias de activos
        Route::get('get-tic-type-tic-categories', 'TicTypeTicCategoryController@all')->name('all');

        Route::get('get-categories-actives', 'TicTypeTicCategoryController@getCategories')->name('getCategories');

        // Ruta para exportar los datos de categorias de activos
        Route::post('export-tic-type-tic-categories', 'TicTypeTicCategoryController@export')->name('export');

        // Ruta para la gestion de los tipos de activos de la mesa de ayuda tic
        Route::resource('tic-type-assets', 'TicTypeAssetController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los tipos de activos de la mesa de ayuda tic
        Route::get('get-tic-type-assets', 'TicTypeAssetController@all')->name('all');
        // Obtiene los tipos de activos por la categoria de la mesa de ayuda tic
        Route::get('get-tic-type-assets-by-category/{id}', 'TicTypeAssetController@getTypeTicAssetsByCategory');
        // Ruta para exportar los tipos de activos de la mesa de ayuda tic
        Route::post('export-tic-type-assets', 'TicTypeAssetController@export')->name('export');

        // Ruta para la gestion de activos de la mesa de ayuda tic
        Route::resource('tic-assets', 'TicAssetController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Obtiene los activos de la mesa de ayuda tic
        Route::get('get-tic-assets', 'TicAssetController@all')->name('all');
        // Obtiene los activos por la categoria de la mesa de ayuda tic
        Route::get('get-tic-assets-by-category/{id}', 'TicAssetController@getTicAssetsByCategory');
        // Ruta para exportar los activos de la mesa de ayuda tic
        Route::post('export-tic-assets', 'TicAssetController@export')->name('export');

        Route::post('migrate-tic-assets', 'TicAssetController@migrateTicAssets');
        // Ruta para la gestion de tic-asset-documents
        Route::resource('tic-asset-documents', 'TicAssetDocumentController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        
        // Ruta que obtiene todos los registros de tic-asset-documents
        Route::get('get-tic-asset-documents', 'TicAssetDocumentController@all')->name('all');

        // Ruta para exportar los datos de tic-asset-documents
        Route::post('export-tic-asset-documents', 'TicAssetDocumentController@export')->name('export');

        // Ruta para la gestion de mantenimientos de activos de la mesa de ayuda tic
        Route::resource('tic-maintenances', 'TicMaintenanceController', ['only' => [
            'index', 'show', 'store', 'update', 'destroy'
        ]]);



        // Obtiene los mantenimientos de activos de la mesa de ayuda tic
        Route::get('get-tic-maintenances', 'TicMaintenanceController@all')->name('all');
        // Ruta para exportar los activos de la mesa de ayuda tic
        Route::post('export-tic-maintenances', 'TicMaintenanceController@export')->name('export');


        // Ruta para la gestion de tipos de solicitudes de la mesa de ayuda tic
        Route::resource('tic-type-requests', 'TicTypeRequestController', ['only' => [
            'index', 'show', 'store', 'update', 'destroy'
        ]]);
        // Obtiene los tipos de solicitudes de la mesa de ayuda tic
        Route::get('get-tic-type-requests', 'TicTypeRequestController@all')->name('all');
        // Ruta para exportar los tipos de solicitudes de la mesa de ayuda tic
        Route::post('export-tic-type-requests', 'TicTypeRequestController@export')->name('export');


        // Ruta para la gestion de estados de solicitudes de la mesa de ayuda tic
        Route::resource('tic-request-statuses', 'TicRequestStatusController', ['only' => [
            'index', 'show', 'store', 'update', 'destroy'
        ]]);
        // Obtiene los estados de solicitudes de la mesa de ayuda tic
        Route::get('get-tic-request-statuses', 'TicRequestStatusController@all')->name('all');
        // Ruta para exportar los estados de solicitudes de la mesa de ayuda tic
        Route::post('export-tic-request-statuses', 'TicRequestStatusController@export')->name('export');


        // Ruta para la gestion de solicitudes de la mesa de ayuda tic
        Route::resource('tic-requests', 'TicRequestController', ['only' => [
            'index', 'show', 'store', 'update', 'destroy'
        ]]);
        Route::put('tic-requests-request', 'TicRequestController@updateStatus');
        // Obtiene las solicitudes de la mesa de ayuda tic
        Route::get('get-tic-requests', 'TicRequestController@all')->name('all');
        // Ruta para exportar las solicitudes de la mesa de ayuda tic
        Route::post('export-tic-requests', 'TicRequestController@export')->name('export');

        // Obtiene las solicitudes de la mesa de ayuda tic
        Route::get('validate-registration-requests', 'TicRequestController@validateRegistrationRequests');

        // Ruta para la gestion de solicitudes de la mesa de ayuda tic
        // Route::get('requests-tv', 'TicRequestController@getRequestsTv');


        // Ruta de la gestion de calendario laboral
        Route::resource('holiday-calendars', 'HolidayCalendarController', ['only' => [
            'index', 'show', 'store', 'update', 'destroy'
        ]]);
        // Route::get('get-holiday-calendars', 'HolidayCalendarController@all')->name('all');
        // Obtiene los datos del calendario laboral
        Route::get('get-working-hours', 'HolidayCalendarController@allWorkingHours');
        // Route::post('export-holiday-calendars', 'HolidayCalendarController@export')->name('export');

        // Ruta para la gestion de encuestas de la mesa de ayuda tic
        Route::resource('tic-poll-questions', 'TicPollQuestionController', ['only' => [
            'index', 'show', 'store', 'update', 'destroy'
        ]]);
        // Obtiene las preguntas de la encuesta de satisfacion de la mesa de ayuda tic
        Route::get('get-tic-poll-questions', 'TicPollQuestionController@all')->name('all');
        // Ruta para exportar las preguntas de la encuesta de satisfacion de la mesa de ayuda tic
        Route::post('export-tic-poll-questions', 'TicPollQuestionController@export')->name('export');


        // Ruta para la gestion de encuesta de satisfacion de la solicitud de la mesa de ayuda tic
        Route::resource('tic-satisfaction-polls', 'TicSatisfactionPollController', ['only' => [
            'index', 'show', 'store', 'update', 'destroy'
        ]]);
        // Obtiene las encuestas de satisfacion de la solicitud de la mesa de ayuda tic
        Route::get('get-tic-satisfaction-polls', 'TicSatisfactionPollController@all')->name('all');
        // Ruta para exportar las encuestas de satisfacion de la solicitud de la mesa de ayuda tic
        Route::post('export-tic-satisfaction-polls', 'TicSatisfactionPollController@export')->name('export');


        Route::resource('statistics', 'StatisticController', ['only' => [
            'index', 'show', 'store', 'update', 'destroy'
        ]]);
        Route::get('get-statistics', 'StatisticController@all')->name('all');

        Route::post('export-statistics', 'StatisticController@export')->name('export');

        // Ruta para la gestion de la base de conocimiento de la mesa de ayuda tic
        Route::resource('tic-knowledge-bases', 'TicKnowledgeBaseController', ['only' => [
            'index', 'show', 'store', 'update', 'destroy'
        ]]);
        // Obtiene los conocimientos de la base de conocimiento de la mesa de ayuda tic
        Route::get('get-tic-knowledge-bases', 'TicKnowledgeBaseController@all')->name('all');
        // Ruta para exportar los conocimientos de la base de conocimiento de la mesa de ayuda tic
        Route::post('export-tic-knowledge-bases', 'TicKnowledgeBaseController@export')->name('export');

        // Ruta para exportar los conocimientos de la base de conocimiento de la mesa de ayuda tic
        Route::get('get-status/{status}', 'TicRequestController@getStatuses');

        // Ruta para la gestion de ticRequestsDocuments
        Route::resource('tic-requests-documents', 'TicRequestsDocumentsController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de ticRequestsDocuments
        Route::get('get-tic-requests-documents', 'TicRequestsDocumentsController@all')->name('all');
        // Ruta para exportar los datos de ticRequestsDocuments
        Route::post('export-ticRequestsDocuments', 'TicRequestsDocumentsController@export')->name('export');

            // Ruta para la gestion de sedes
        Route::resource('sedes', 'SedeController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de sedes
        Route::get('get-sedes', 'SedeController@all')->name('all');
        // Ruta para exportar los datos de sedes
        Route::post('export-sedes', 'SedeController@export')->name('export');


    });

    // Ruta para visualizar la vista para el televisor
    Route::get('tic-requests-tv', 'TicRequestController@getRequestsTv');
    // Obtiene las solicitudes para la vista del televisor
    Route::get('get-tic-requests-tv', 'TicRequestController@allTv')->name('all');

    Route::get('tic-requests/{request}', 'TicRequestController@show');

    // Ruta para la gestion de los conceptos tecnicos
    Route::resource('technical-concepts', 'TechnicalConceptController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);

    // Ruta que obtiene todos los registros de los conceptos tecnicos
    Route::get('get-technical-concepts', 'TechnicalConceptController@all')->name('all');

    // Ruta que obtiene todos los usuarios cuyo rol sea Soporte TIC
    Route::get('technicians', 'TechnicalConceptController@getTechnicians')->name('all');

    // Ruta que obtiene todos los usuarios cuyo rol sea Soporte TIC
    Route::get('tics-users', 'TechnicalConceptController@getTicsUsers');

    // Ruta que obtiene todos los usuarios cuyo rol sea Soporte TIC
    Route::get('dependencies', 'TechnicalConceptController@getDependencies');

    // Ruta que obtiene todos los usuarios cuyo rol sea Revisor concepto técnico TIC
    Route::get('reviewers', 'TechnicalConceptController@getReviewers');

    // Ruta que enviar la solicitud a revision
    Route::put('send-to-reviewers', 'TechnicalConceptController@sendRequestTechnicalConceptToReview')->name('all');

    // Ruta que obtiene todos los usuarios cuyo rol sea Usuario TIC
    Route::get('staff-members', 'TechnicalConceptController@getStaffMembers');

    // Ruta que obtiene todos los usuarios cuyo rol sea Usuario TIC
    Route::get('certificate/{technicalConceptId}', 'TechnicalConceptController@exportTechnicalConceptPdf');

    // Ruta para exportar los datos de los conceptos tecnicos
    Route::post('export-technicalConcepts', 'TechnicalConceptController@export')->name('export');

    // Ruta para crear una solicitud de concepto tecnico
    Route::post('request-technical-concept', 'TechnicalConceptController@createRequestTechnicalConcept');

    // Ruta para enviar la solicitud para aprobación
    Route::put('validate-request-status', 'TechnicalConceptController@validateRequetStatus');

    // Ruta para aprobar o devolver la solicitud
    Route::put('validate-approval-request', 'TechnicalConceptController@validateApprovalRequet');

    Route::get('get-dependencias-tics/{id}', 'TicRequestController@getDependenciasTicRequest');

    Route::get('get-sedes-tics', 'TicRequestController@getSedesTicRequest');

    Route::get('cron-expired', 'TicRequestController@emailExpiredCron');

    //************ De aquí en adelante es una actualización que solo tiene la Intraepa ************

    // Ruta para la gestion de dataAnalytics
    Route::resource('data-analytics', 'DataAnalyticController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de dataAnalytics
    Route::get('get-data-analytics', 'DataAnalyticController@all')->name('all');
    // Ruta para exportar los datos de dataAnalytics
    Route::post('export-dataAnalytics', 'DataAnalyticController@export')->name('export');

    Route::get('export-graph/{dateInitial}/{dateFinal}', 'DataAnalyticController@exportGraphOfSatisfationPollByDateRange');

    // Ruta para la gestion de equipmentProviders
    Route::resource('providers', 'EquipmentProviderController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de equipmentProviders
    Route::get('get-providers', 'EquipmentProviderController@all')->name('all');
    // Ruta para exportar los datos de equipmentProviders
    Route::post('export-equipmentProviders', 'EquipmentProviderController@export')->name('export');

    // Ruta para la gestion de configTowers
    Route::resource('config-towers', 'ConfigTowerController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);

    // Ruta que obtiene todos los registros de configTowers
    Route::get('get-config-towers', 'ConfigTowerController@all')->name('all');

    // Ruta que obtiene todos los registros de configTowers
    Route::get('get-config-towers-actives', 'ConfigTowerController@getConfigTowersActives')->name('getConfigTowersActives');


    // Ruta para exportar los datos de configTowers
    Route::post('export-configTowers', 'ConfigTowerController@export')->name('export');

    // Ruta para la gestion de configKeyBoards
    Route::resource('config-keyboards', 'ConfigKeyBoardController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);

    // Ruta que obtiene todos los registros de configKeyBoards
    Route::get('get-config-keyboards', 'ConfigKeyBoardController@all')->name('all');

    // Ruta para exportar los datos de configKeyBoards
    Route::post('export-configKeyBoards', 'ConfigKeyBoardController@export')->name('export');

    // Ruta para la gestion de configMice
    Route::resource('config-mouses', 'ConfigMouseController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);

    // Ruta que obtiene todos los registros de configMice
    Route::get('get-config-mouses', 'ConfigMouseController@all')->name('all');

    // Ruta para exportar los datos de configMice
    Route::post('export-configMice', 'ConfigMouseController@export')->name('export');

    // Ruta para la gestion de configMonitors
    Route::resource('config-monitors', 'ConfigMonitorController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);

    // Ruta que obtiene todos los registros de configMonitorsz
    Route::get('get-config-monitors', 'ConfigMonitorController@all')->name('all');

    // Ruta que obtiene todos los registros de configMonitorsz con estado activo
    Route::get('get-config-monitors-actives', 'ConfigMonitorController@configActives')->name('configActives');

    // Ruta para exportar los datos de configMonitors
    Route::post('export-configMonitors', 'ConfigMonitorController@export')->name('export');

    // Ruta para la gestion de equipmentResumes
    Route::resource('equipment-resumes', 'EquipmentResumeController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);

    // Ruta que obtiene todos los registros de equipmentResumes
    Route::get('get-equipment-resumes', 'EquipmentResumeController@all')->name('all');

    // Ruta para exportar los datos de equipmentResumes
    Route::post('export-equipmentResumes', 'EquipmentResumeController@export')->name('export');

    Route::get('get-equipment-resumes-all', 'EquipmentResumeController@getEquipmentResume')->name('getEquipmentResume');

    Route::get('tic-maintenances-equipment', 'TicMaintenanceController@indexMaintenanceEquipment')->name('indexMaintenanceEquipment');

    Route::get('get-tic-maintenances-equipment', 'TicMaintenanceController@getMaintenanceEquipment')->name('getMaintenanceEquipment');

    // Ruta para exportar los datos de equipmentResumeDocuments
    Route::post('import-data-equipment-resumes', 'EquipmentResumeController@importDataOfEquipmentResumes');

    // Ruta para la gestion de equipmentResumeDocuments
    Route::resource('equipment-resume-documents', 'EquipmentResumeDocumentController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de equipmentResumeDocuments
    Route::get('get-equipment-resume-documents', 'EquipmentResumeDocumentController@all')->name('all');
    // Ruta para exportar los datos de equipmentResumeDocuments
    Route::post('export-equipmentResumeDocuments', 'EquipmentResumeDocumentController@export')->name('export');
    // Ruta para la gestion de equipmentResumeBackups
    Route::resource('equipment-resume-history', 'EquipmentResumeBackupController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de equipmentResumeBackups
    Route::get('get-equipment-resume-history', 'EquipmentResumeBackupController@all')->name('all');
    // Ruta que obtiene todos los registros de equipmentResumeBackups
    Route::get('export-equipment-resume-history/{equipmentResumeHistoryId}', 'EquipmentResumeBackupController@exportResumeHistoryInExcelFile');
    // Ruta para exportar los datos de equipmentResumeBackups
    Route::post('export-equipmentResumeBackups', 'EquipmentResumeBackupController@export')->name('export');

    // Ruta para la gestion de configOperationSystems
    Route::resource('config-operation-systems', 'ConfigOperationSystemController', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);

    // Ruta que obtiene todos los registros de configOperationSystems
    Route::get('get-config-operation-systems', 'ConfigOperationSystemController@all')->name('all');

    // Ruta que obtiene todos los registros de configOperationSystems
    Route::get('config-operation-systems-activated', 'ConfigOperationSystemController@getOperatingSystemsActivated');

    // Ruta para exportar los datos de configOperationSystems
    Route::post('export-configOperationSystems', 'ConfigOperationSystemController@export')->name('export');

    Route::get('get-dependencies', 'EquipmentResumeController@obtener_dependencias')->name('obtener_dependencias');

    //************ Fin actualización que solo tiene la Intraepa ************

        // Ruta para la gestion de config-tower-references
        Route::resource('config-tower-references', 'ConfigTowerReferenceController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de config-tower-references
        Route::get('get-config-tower-references', 'ConfigTowerReferenceController@all')->name('all');
        // Ruta para exportar los datos de config-tower-references
        Route::post('export-config-tower-references', 'ConfigTowerReferenceController@export')->name('export');
        //Ruta para traer todos los registros que estan en activos
        Route::get('get-config-tower-references-active', 'ConfigTowerReferenceController@getReferenceActive')->name('getReferenceActive');
   

        // Ruta para la gestion de config-tower-sizes
        Route::resource('config-tower-sizes', 'ConfigTowerSizeController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de config-tower-sizes
        Route::get('get-config-tower-sizes', 'ConfigTowerSizeController@all')->name('all');
        // Ruta para exportar los datos de config-tower-sizes
        Route::post('export-config-tower-sizes', 'ConfigTowerSizeController@export')->name('export');
        
        //Ruta para traer todos los registros que estan en activos
        Route::get('get-config-tower-size-active', 'ConfigTowerSizeController@getTowerSizeActive')->name('getTowerSizeActive');

        // Ruta para la gestion de config-tower-processors
        Route::resource('config-tower-processors', 'ConfigTowerProcessorController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de config-tower-processors
        Route::get('get-config-tower-processors', 'ConfigTowerProcessorController@all')->name('all');
        // Ruta para exportar los datos de config-tower-processors
        Route::post('export-config-tower-processors', 'ConfigTowerProcessorController@export')->name('export');
        //Ruta para traer todos los registros que estan en activos
        Route::get('get-config-tower-processor-active', 'ConfigTowerProcessorController@getTowerProcessorActive')->name('getTowerProcessorActive');


        // Ruta para la gestion de config-tower-memory-rams
        Route::resource('config-tower-memory-rams', 'ConfigTowerMemoryRamController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de config-tower-memory-rams
        Route::get('get-config-tower-memory-rams', 'ConfigTowerMemoryRamController@all')->name('all');
        // Ruta para exportar los datos de config-tower-memory-rams
        Route::post('export-config-tower-memory-rams', 'ConfigTowerMemoryRamController@export')->name('export');
        //Ruta para traer todos los registros que estan en activos
        Route::get('get-config-tower-rams-active', 'ConfigTowerMemoryRamController@getRamsActive')->name('getRamsActive');

        // Ruta para la gestion de config-tower-ssd-capacities
        Route::resource('config-tower-ssd-capacities', 'ConfigTowerSsdCapacityController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de config-tower-ssd-capacities
        Route::get('get-config-tower-ssd-capacities', 'ConfigTowerSsdCapacityController@all')->name('all');
        // Ruta para exportar los datos de config-tower-ssd-capacities
        Route::post('export-config-tower-ssd-capacities', 'ConfigTowerSsdCapacityController@export')->name('export');
        //Ruta para traer todos los registros que estan en activos
        Route::get('get-config-tower-ssd-active', 'ConfigTowerSsdCapacityController@getSsdActive')->name('getSsdActive');

        // Ruta para la gestion de config-tower-hdd-capacities
        Route::resource('config-tower-hdd-capacities', 'ConfigTowerHddCapacityController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de config-tower-hdd-capacities
        Route::get('get-config-tower-hdd-capacities', 'ConfigTowerHddCapacityController@all')->name('all');
        // Ruta para exportar los datos de config-tower-hdd-capacities
        Route::post('export-config-tower-hdd-capacities', 'ConfigTowerHddCapacityController@export')->name('export');
        //Ruta para traer todos los registros que estan en activos
        Route::get('get-config-tower-hdd-active', 'ConfigTowerHddCapacityController@getHddActive')->name('getHddActive');

        // Ruta para la gestion de config-tower-video-cards
        Route::resource('config-tower-video-cards', 'ConfigTowerVideoCardController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de config-tower-video-cards
        Route::get('get-config-tower-video-cards', 'ConfigTowerVideoCardController@all')->name('all');
        // Ruta para exportar los datos de config-tower-video-cards
        Route::post('export-config-tower-video-cards', 'ConfigTowerVideoCardController@export')->name('export');
        //Ruta para traer todos los registros que estan en activos
        Route::get('get-config-tower-video-card-active', 'ConfigTowerVideoCardController@getVideoCardActive')->name('getVideoCardActive');


         // Ruta para la gestion de config-shared-folders
        Route::resource('config-shared-folders', 'ConfigSharedFolderController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de config-shared-folders
        Route::get('get-config-shared-folders', 'ConfigSharedFolderController@all')->name('all');
        // Ruta para exportar los datos de config-shared-folders
        Route::post('export-config-shared-folders', 'ConfigSharedFolderController@export')->name('export');
        //Ruta para traer todos los registros que estan en activos
        Route::get('get-config-tower-shared-folder-active', 'ConfigSharedFolderController@getSharedFolderActive')->name('getSharedFolderActive');


        // Ruta para la gestion de config-network-cards
        Route::resource('config-network-cards', 'ConfigNetworkCardController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de config-network-cards
        Route::get('get-config-network-cards', 'ConfigNetworkCardController@all')->name('all');
        // Ruta para exportar los datos de config-network-cards
        Route::post('export-config-network-cards', 'ConfigNetworkCardController@export')->name('export');
        // Ruta para exportar los datos de config-network-cards
        Route::get('get-config-tower-network-card-active', 'ConfigNetworkCardController@getNetworkCardActive')->name('getNetworkCardActive');


        // Ruta para la gestion de config-office-versions
        Route::resource('config-office-versions', 'ConfigOfficeVersionController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de config-office-versions
        Route::get('get-config-office-versions', 'ConfigOfficeVersionController@all')->name('all');
        // Ruta para exportar los datos de config-office-versions
        Route::post('export-config-office-versions', 'ConfigOfficeVersionController@export')->name('export');
        // Ruta para exportar los datos de config-network-cards
        Route::get('get-config-office-versions-active', 'ConfigOfficeVersionController@getOfficeVersionActive')->name('getOfficeVersionActive');

    
        // Ruta para la gestion de config-storage-statuses
        Route::resource('config-storage-statuses', 'ConfigStorageStatusController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de config-storage-statuses
        Route::get('get-config-storage-statuses', 'ConfigStorageStatusController@all')->name('all');
        // Ruta para exportar los datos de config-storage-statuses
        Route::post('export-config-storage-statuses', 'ConfigStorageStatusController@export')->name('export');
        // Ruta para exportar los datos de config-network-cards
        Route::get('get-config-storage-statuses-active', 'ConfigStorageStatusController@getStorageStatusActive')->name('getStorageStatusActive');


        // Ruta para la gestion de config-unnecessary-apps
        Route::resource('config-unnecessary-apps', 'ConfigUnnecessaryAppsController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de config-unnecessary-apps
        Route::get('get-config-unnecessary-apps', 'ConfigUnnecessaryAppsController@all')->name('all');
        // Ruta para exportar los datos de config-unnecessary-apps
        Route::post('export-config-unnecessary-apps', 'ConfigUnnecessaryAppsController@export')->name('export');
        // Ruta para exportar los datos de config-network-cards
        Route::get('get-config-unnecessary-apps-active', 'ConfigUnnecessaryAppsController@getUnnecessaryAppsActive')->name('getUnnecessaryAppsActive');

        // Ruta para la gestion de equipment-purchase-details
        Route::resource('equipment-purchase-details', 'EquipmentPurchaseDetailController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de equipment-purchase-details
        Route::get('get-equipment-purchase-details', 'EquipmentPurchaseDetailController@all')->name('all');
        // Ruta para exportar los datos de equipment-purchase-details
        Route::post('export-equipment-purchase-details', 'EquipmentPurchaseDetailController@export')->name('export');

        Route::get('equipment-purchase', 'EquipmentPurchaseDetailController@getEquipmentPurchase')->name('getEquipmentPurchase');


        });


    