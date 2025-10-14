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

Route::group(['prefix' => 'contractual-process'], function () {
    Route::get('/', function () {
        return redirect('/dashboard');
    });

    Route::middleware(['auth', 'verified'])->group(function () {

        // Obtiene todos los datos de una constante dependiendo de nombre
        Route::get('get-constants/{name}', 'UtilController@getConstants');
        // Obtiene todos los datos de una constante activa dependiendo de nombre
        Route::get('get-constants-active/{name}', 'UtilController@getConstantsActive');
        // Obtiene los usuarios con el grupo lideres del proceso contractual
        Route::get('get-process-lead-users', 'UtilController@getProcessLeadUsers');
        // Obtiene las vigencias de proyectos del proceso contractual
        Route::get('get-validities', 'UtilController@getValidities');
        // Obtiene los nombres de proyectos del proceso contractual
        Route::get('get-name-projects', 'UtilController@getNameProjects');
        // Obtiene las unidades de gestion del proceso contractual
        Route::get('get-management-unit', 'UtilController@getManagementUnit');
        // Obtiene las lineas de proyecto del proceso contractual
        Route::get('get-project-lines', 'UtilController@getProjectLines');
        // Obtiene las identificaciones de proyecto del proceso contractual
        Route::get('get-poir', 'UtilController@getPoir');
        // Obtiene ciudades por id de depatamento
        Route::get('get-cities-by-department/{id}', 'UtilController@getCitiesByDepartment');
        // Obtiene las versiones del sistema operativo de computadores
        Route::get('get-activity-tariff-harmonization-by-item/{id}', 'UtilController@getActivityTariffHarmonizationByItem');


        // Ruta para la gestion de convocatorias del proceso contractual
        Route::resource('paa-calls', 'PaaCallController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Obtiene las convocatorias del proceso contractual
        Route::get('get-paa-calls', 'PaaCallController@all')->name('all');
        // Ruta para exportar las convocatorias del proceso contractual
        Route::post('export-paa-calls', 'PaaCallController@export')->name('export');

        Route::post('approve-call-paa', 'PaaCallController@approveCallPaa');

        // Ruta para exportar el PAA
        Route::get('export-paa-call/{id}', 'PaaCallController@exportPaaCall');

        // Ruta para cambiar de version el PAA
        Route::post('change-version-paa', 'PaaCallController@changeVersionPaa');

        // Ruta para cambiar de version el PAA
        Route::get('get-paa-versions/{id}', 'PaaCallController@getPaaVersions');

        // Ruta para la gestion de lideres del proceso contractual
        Route::resource('process-leaders', 'ProcessLeadersController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Obtiene los lideres del proceso contractual
        Route::get('get-process-leaders', 'ProcessLeadersController@all')->name('all');
        // Ruta para exportar los lideres del proceso contractual
        Route::post('export-process-leaders', 'ProcessLeadersController@export')->name('export');


        // Ruta para la gestion de necesidades del proceso del proceso contractual
        Route::resource('needs', 'NeedController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Obtiene las necesidades del proceso contractual
        Route::get('get-needs', 'NeedController@all')->name('all');
        // Ruta para exportar las necesidades del proceso contractual
        Route::post('export-needs', 'NeedController@export')->name('export');
        // Obtiene las necesidades sin evaluar del paa
        Route::get('get-unassessed-needs-paa/{id}', 'NeedController@getUnassessedNeedsPaa');
        // Envia notificacion a los lideres de las necesidades sin evaluar
        Route::get('notify-unassessed-needs-paa/{id}', 'NeedController@notifyUnassessedNeedsPaa');
        // Obtiene las novedades de las necesidades del proceso contractual
        Route::get('get-novelties-paa/{id}', 'NeedController@getNoveltiesPaa');
        // Ruta para exportar las necesidades aprobadas del proceso contractual
        Route::get('export-approved-needs/{id}', 'NeedController@exportApprovedNeeds');
        // Ruta para enviar a revision las necesidades
        Route::put('send-review-needs/{id}', 'NeedController@sendReviewNeeds');
        // Ruta para evaluar las necesidades
        Route::post('assess-needs-paa', 'NeedController@assessNeedsPaa');
        // Ruta para la solicitud de modificacion de necesidades
        Route::post('request-modification-paa', 'NeedController@requestModificationPaa');
        // Ruta para procesar la solicitud de modificacion de necesidades
        Route::post('process-modification-request-paa', 'NeedController@processModificationRequestPaa');

        // Ruta para la gestion de necesidades de funcionamiento del proceso del proceso contractual
        Route::resource('functioning-needs', 'FunctioningNeedController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Obtiene las necesidades de funcionamiento del proceso contractual
        Route::get('get-functioning-needs', 'FunctioningNeedController@all')->name('all');
        // Ruta para exportar las necesidades de funcionamiento del proceso contractual
        Route::post('export-functioning-needs', 'FunctioningNeedController@export')->name('export');


        // Ruta para la gestion de necesidades de inversion del proceso del proceso contractual
        Route::resource('investment-needs', 'InvestmentNeedController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Obtiene las necesidades de inversion del proceso contractual
        Route::get('get-investment-needs', 'InvestmentNeedController@all')->name('all');
        // Ruta para exportar las necesidades de inversion del proceso contractual
        Route::post('export-investment-needs', 'InvestmentNeedController@export')->name('export');


        // Ruta para la gestion de fichas del proceso del proceso contractual
        Route::resource('technical-sheets', 'TechnicalSheetsController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Obtiene las fichas del proceso contractual
        Route::get('get-technical-sheets', 'TechnicalSheetsController@all')->name('all');
        // Ruta para exportar las fichas del proceso contractual
        Route::post('export-technical-sheets', 'TechnicalSheetsController@export')->name('export');


        Route::resource('investment-technical-sheets', 'InvestmentTechnicalSheetController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        Route::get('get-investment-technical-sheets', 'InvestmentTechnicalSheetController@all')->name('all');

        Route::post('export-investment-technical-sheets', 'InvestmentTechnicalSheetController@export')->name('export');

        Route::put('objectives-indicators', 'InvestmentTechnicalSheetController@saveGoalsIndicators');

        Route::put('information-tariff-harmonization', 'InvestmentTechnicalSheetController@saveInfoTariffHarmonization');

        Route::put('environmental-impacts', 'InvestmentTechnicalSheetController@saveEnvironmentalImpacts');

        Route::put('chronograms', 'InvestmentTechnicalSheetController@saveChronograms');

        Route::post('alternative-budget', 'InvestmentTechnicalSheetController@saveAlternativeBudget');

        Route::resource('calls-paa', 'PaaCallController', ['only' => [
            'index', 'store', 'show', 'update', 'destroy'
        ]]);


        // Tablero de control
        Route::get('controll-table', 'ControlPanelController@index');

        // Ruta para validar las fechas de cierre de las convocatorias
        Route::get('validate-closing-dates-calls', 'PaaCallController@validateClosingDatesCalls');




        //Routes estudios previos
        Route::resource('pc-previous-studies', 'PcPreviousStudiesController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        Route::get('get-pc-previous-studies', 'PcPreviousStudiesController@all')->name('all');

        Route::post('export-pc-previous-studies', 'PcPreviousStudiesController@export')->name('export');

        //Routes estudios previos
        Route::put('pc-previous-studies-send', 'PcPreviousStudiesController@sendStudie');

        //Routes documents studies previous
        Route::resource('pc-previous-studies-documents', 'PcPreviousStudiesDocumentsController', ['only' => [
            'index', 'update', 'destroy'
        ]]);

        Route::post('pc-previous-studies-documents/{pc}', ['as' => 'documents.store', 'uses' => 'PcPreviousStudiesDocumentsController@store']);

        Route::get('get-pc-previous-studies-documents/{pc}', 'PcPreviousStudiesDocumentsController@all')->name('all');

        Route::post('export-pc-previous-studies-documents', 'PcPreviousStudiesDocumentsController@export')->name('export');


        //radication
        Route::resource('pc-previous-studies-radications', 'PcPreviousStudiesRadicationController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        Route::get('get-pc-previous-studies-radications', 'PcPreviousStudiesRadicationController@all')->name('all');

        Route::post('export-pc-previous-studies-radications', 'PcPreviousStudiesRadicationController@export')->name('export');

        // Obtiene los estados de estudios previos
        Route::get('get-states-previous-studies', 'ControlPanelController@getStatesPreviousStudies');
        // Obtiene los roles de estudios previos
        Route::get('get-rols-previous-studies', 'ControlPanelController@getRolesPreviousStudies');

        Route::get('get-information-previous-studies', 'ControlPanelController@getInformationPreviousStudies');

        //generar pdf desde el listado de estudios previos
        Route::get('pc-previous-studies-generate-pdf/{id}', 'PcPreviousStudiesController@showDocument')->name('pc-previous-studies-generate-pdf.showDocument');

        //Historial de estudios previos       
        Route::resource('pc-previous-studies-history', 'PcPreviousStudiesHistoryController', ['only' => [
            'index', 'update', 'destroy'
        ]]);

        Route::post('pc-previous-studies-history/{pc}', ['as' => 'history.store', 'uses' => 'PcPreviousStudiesHistoryController@store']);

        Route::get('get-pc-previous-studies-history/{pc}', 'PcPreviousStudiesHistoryController@all')->name('all');

        Route::post('export-pc-previous-studies-history', 'PcPreviousStudiesHistoryController@export')->name('export');


        // Ruta para la gestion de plansBudgets
        // Route::resource('plans-budgets-functioning', 'PlansBudgetController', ['only' => [
        //     'index', 'store', 'update', 'destroy'
        // ]]);

         // Ruta que obtiene la vista de las necesidades de funcionamiento
        Route::get('plans-budgets-functioning', 'FunctioningNeedController@index')->name('plans-budgets-functioning');
        // Ruta que obtiene la vista de las necesidades de inversion
        Route::get('plans-budgets-investment', 'InvestmentNeedController@index')->name('plans-budgets-investment');
        // Ruta para guardar los datos del procesar la evaluacion del presupuesto
        Route::post('evaluate-budget-paa', 'PlansBudgetController@evaluateBudgetPaa');
        // Ruta que obtiene todos los registros de plansBudgets
        Route::get('get-plansBudgets', 'PlansBudgetController@all')->name('all');
        // Ruta para exportar los datos de plansBudgets
        Route::post('export-plansBudgets', 'PlansBudgetController@export')->name('export');

        
        Route::get('get-pc-previous-studies-needs', 'PcPreviousStudiesController@getNeedsLeader');
        Route::get('get-pc-previous-studies-needs-only', 'PcPreviousStudiesController@getNeedsSheetLeader');
        Route::get('get-pc-previous-studies-needs-functioning', 'PcPreviousStudiesController@getNeedsFunctioning');
        
        // Obtiene los usuarios segun el rol necesitado    
        Route::get('get-user-by-rol/{id}', 'PcPreviousStudiesController@getUserByRol');


        // Ruta para la gestion de adjuntos de un proceso
        Route::resource('paa-process-attachments', 'PaaProcessAttachmentController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de adjuntos de un proceso
        Route::get('get-paa-process-attachments', 'PaaProcessAttachmentController@all')->name('all');
        // Ruta para exportar los datos de adjuntos de un proceso
        Route::post('export-paa-process-attachments', 'PaaProcessAttachmentController@export')->name('export');
    });


});


//Aqui
// Ruta para la gestion de investmentTechnicalSheets
Route::resource('investmentTechnicalSheets', 'InvestmentTechnicalSheetController', ['only' => [
    'index', 'store', 'update', 'destroy'
]]);
// Ruta que obtiene todos los registros de investmentTechnicalSheets
Route::get('get-investmentTechnicalSheets', 'InvestmentTechnicalSheetController@all')->name('all');
// Ruta para exportar los datos de investmentTechnicalSheets
Route::post('export-investmentTechnicalSheets', 'InvestmentTechnicalSheetController@export')->name('export');





// Ruta para la gestion de investmentNeeds
Route::resource('investmentNeeds', 'InvestmentNeedController', ['only' => [
    'index', 'store', 'update', 'destroy'
]]);
// Ruta que obtiene todos los registros de investmentNeeds
Route::get('get-investmentNeeds', 'InvestmentNeedController@all')->name('all');
// Ruta para exportar los datos de investmentNeeds
Route::post('export-investmentNeeds', 'InvestmentNeedController@export')->name('export');


// Ruta para la gestion de paaNeeds
Route::resource('paaNeeds', 'PaaNeedController', ['only' => [
    'index', 'store', 'update', 'destroy'
]]);
// Ruta que obtiene todos los registros de paaNeeds
Route::get('get-paaNeeds', 'PaaNeedController@all')->name('all');
// Ruta para exportar los datos de paaNeeds
Route::post('export-paaNeeds', 'PaaNeedController@export')->name('export');
