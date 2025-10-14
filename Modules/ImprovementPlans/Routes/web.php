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
    Route::group(['prefix' => 'improvement-plans'], function () {
        Route::get('/', function () {
            dd('This is the Improvementplans module index page. Build something great!');
        });

        Route::get('get-roles', 'UtilController@roles');

        // Ruta para la gestion de rols
        Route::resource('rols', 'RolController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene todos los registros de rols
        Route::get('get-rols', 'RolController@all')->name('all');
        
        // Ruta para exportar los datos de rols
        Route::post('export-rols', 'RolController@export')->name('export');
        
        // Ruta para la gestion de users
        Route::resource('users', 'UserController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta para obtener usuarios mediante una query
        Route::get('get-users-by-name', 'UserController@getUsersByName');

        // Ruta que obtiene todos los registros de users
        Route::get('get-users', 'UserController@all')->name('all');

        // Ruta que obtiene todos los registros de users
        Route::get('get-active-users', 'UserController@getActiveUsers');

        Route::get('get-users-order-name', 'UserController@getUserOrderName');

        // Ruta que obtiene todos los registros de users
        Route::get('get-search-dependences', 'UserController@getDependencesByName');

        // Ruta que obtiene todos los registros de users
        Route::get('get-charges', 'UserController@getCharges')->name('all');

        // Ruta que obtiene todos los registros de users
        Route::get('get-dependences', 'UserController@getDependences')->name('all');

        // Ruta para exportar los datos de users
        Route::post('export-users', 'UserController@export')->name('export');

        // Ruta para la gestion de evaluationProcesses
        Route::resource('evaluation-processes', 'EvaluationProcessController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene todos los registros de evaluationProcesses
        Route::get('get-evaluationProcesses', 'EvaluationProcessController@all')->name('all');

        // Ruta para exportar los datos de evaluationProcesses
        Route::post('export-evaluationProcesses', 'EvaluationProcessController@export')->name('export');

        // Ruta para la gestion de typeEvaluations
        Route::resource('type-evaluations', 'TypeEvaluationController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene todos los registros de typeEvaluations
        Route::get('get-typeEvaluations', 'TypeEvaluationController@all')->name('all');

        Route::get('active-type-evaluations', 'TypeEvaluationController@getActiveTypesEvaluation');

        // Ruta para exportar los datos de typeEvaluations
        Route::post('export-typeEvaluations', 'TypeEvaluationController@export')->name('export');

        // Ruta para la gestion de sourceInformations
        Route::resource('source-informations', 'SourceInformationController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene todos los registros de sourceInformations
        Route::get('get-sourceInformations', 'SourceInformationController@all')->name('all');

        Route::get('get-active-source-information', 'SourceInformationController@getActiveSourceInformation');
        
        // Ruta para exportar los datos de sourceInformations
        Route::post('export-sourceInformations', 'SourceInformationController@export')->name('export');

        // Ruta para la gestion de typeImprovementOpportunities
        Route::resource('type-improvement-opportunities', 'TypeImprovementOpportunityController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene todos los registros de typeImprovementOpportunities
        Route::get('get-typeImprovementOpportunities', 'TypeImprovementOpportunityController@all')->name('all');

        Route::get('get-active-types-opportunities-improvement', 'TypeImprovementOpportunityController@getActiveTypeOpportunitiesImprovement');

        // Ruta para exportar los datos de typeImprovementOpportunities
        Route::post('export-typeImprovementOpportunities', 'TypeImprovementOpportunityController@export')->name('export');

        // Ruta para la gestion de evaluationCriterias
        Route::resource('evaluation-criterias', 'EvaluationCriteriaController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene todos los registros de evaluationCriterias
        Route::get('get-evaluationCriterias', 'EvaluationCriteriaController@all')->name('all');

        Route::get('active-evaluation-criteria', 'EvaluationCriteriaController@getActiveEvaluationCriteria');

        // Ruta para exportar los datos de evaluationCriterias
        Route::post('export-evaluationCriterias', 'EvaluationCriteriaController@export')->name('export');

        // Ruta para la gestion de contentManagements
        Route::resource('content-managements', 'ContentManagementController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene todos los registros de contentManagements
        Route::get('get-contentManagements', 'ContentManagementController@all')->name('all');

        // Ruta para exportar los datos de contentManagements
        Route::post('export-contentManagements', 'ContentManagementController@export')->name('export');

        // Ruta para la gestion de evaluations
        Route::resource('evaluations', 'EvaluationController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene todos los registros de evaluations
        Route::get('get-evaluations', 'EvaluationController@all')->name('all');

        // Ruta para exportar los datos de evaluations
        Route::post('export-evaluations', 'EvaluationController@export')->name('export');

        // Ruta para ejecutar el proceso evaluacion
        Route::put('execute-evaluation-process','EvaluationController@executeEvaluationProcess');

        // Ruta para la gestion de improvementOpportunities
        Route::resource('improvement-opportunities', 'ImprovementOpportunityController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene todos los registros de improvementOpportunities
        Route::get('get-improvement-opportunities', 'ImprovementOpportunityController@all')->name('all');

        Route::get('get-non-compliant-evaluation-criteria/{evaluationId}', 'ImprovementOpportunityController@getNonCompliantEvaluationCriteria');

        Route::get('activities-to-assign-responsibles/{goalId}', 'ImprovementOpportunityController@getActivitiesByGoal');

        // Ruta para exportar los datos de improvementOpportunities
        Route::post('export-improvementOpportunities', 'ImprovementOpportunityController@export')->name('export');

        // Ruta para la gestion de typeImprovementPlans
        Route::resource('type-improvement-plans', 'TypeImprovementPlanController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene todos los registros de typeImprovementPlans
        Route::get('get-type-improvement-plans', 'TypeImprovementPlanController@all')->name('all');

        Route::get('get-active-types-improvement-plans', 'TypeImprovementPlanController@getActiveTypesImprovementPlans');

        Route::get('get-configuration-time-messages', 'TypeImprovementPlanController@getConfigurationMessageTime');

        Route::get('holiday-calendar', 'TypeImprovementPlanController@getHolidayCalendar');

        // Ruta para exportar los datos de typeImprovementPlans
        Route::post('export-typeImprovementPlans', 'TypeImprovementPlanController@export')->name('export');

        Route::post('time-messages', 'TypeImprovementPlanController@configureMessageTime');

        // Ruta para la gestion de evaluationEvaluateds
        Route::resource('my-evaluations', 'EvaluationEvaluatedController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de evaluationEvaluateds
        Route::get('get-my-evaluations', 'EvaluationEvaluatedController@all')->name('all');
        // Ruta para exportar los datos de evaluationEvaluateds
        Route::post('export-evaluationEvaluateds', 'EvaluationEvaluatedController@export')->name('export');

        // Ruta para la gestion de calendarEvaluations
        Route::resource('calendar-evaluations', 'CalendarEvaluationController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de calendarEvaluations
        Route::get('get-calendar-evaluations', 'CalendarEvaluationController@all')->name('all');
        // Ruta para exportar los datos de calendarEvaluations
        Route::post('export-calendarEvaluations', 'CalendarEvaluationController@export')->name('export');

        // Ruta para la gestion de improvementPlans
        Route::resource('improvement-plans', 'ImprovementPlanController', ['only' => [
            'index', 'store', 'update', 'destroy', 'show'
        ]]);
        // Ruta que obtiene todos los registros de improvementPlans
        Route::get('get-improvement-plans', 'ImprovementPlanController@all')->name('all');

        // Ruta para enviar un plan de mejoramiento a revision del evaluador
        Route::get('send-review-improvement-plan/{improvementPlanId}', 'ImprovementPlanController@sendReviewImprovementPlan');

        // Ruta para exportar los datos de improvementPlans
        Route::post('export-improvementPlans', 'ImprovementPlanController@export')->name('export');

        // Ruta para solicitar modificacion del plan
        Route::put('execute-processes-modification','ImprovementPlanController@executeProcessModification');

        // Ruta que obtiene todos los registros de las actividades del plan
        Route::get('evaluation-activities/{planId}', 'ImprovementPlanController@getActivitesPlan');

        // Ruta para la gestion de nonConformingCriterias
        Route::resource('non-conforming-criterias', 'NonConformingCriteriaController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de nonConformingCriterias
        Route::get('get-non-conforming-criterias', 'NonConformingCriteriaController@all')->name('all');
        // Ruta para exportar los datos de nonConformingCriterias
        Route::post('export-nonConformingCriterias', 'NonConformingCriteriaController@export')->name('export');

        // Ruta para la gestion de goals
        Route::resource('goals', 'GoalController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de goals
        Route::get('get-goals', 'GoalController@all')->name('all');
        // Ruta para exportar los datos de goals
        Route::post('export-goals', 'GoalController@export')->name('export');

        // Ruta para exportar los datos de improvementOpportunities
        Route::put('assign-responsible-to-activity', 'GoalController@assignResponsibleToActivity');

        // Ruta para la gestion de approvedImprovementPlans
        Route::resource('approved-improvement-plans', 'ApprovedImprovementPlanController', ['only' => [
            'index', 'store', 'update', 'destroy', 'show'
        ]]);

        // Ruta que obtiene todos los registros de approvedImprovementPlans
        Route::get('get-approved-improvement-plans', 'ApprovedImprovementPlanController@all')->name('all');

        // Ruta que exporta la información de un plan de mejoramiento
        Route::get('export-improvement-plan/{improvementPlanId}', 'ApprovedImprovementPlanController@exportImprovementPlan');

        // Ruta para exportar los datos de approvedImprovementPlans
        Route::post('export-approvedImprovementPlans', 'ApprovedImprovementPlanController@export')->name('export');

        // Ruta para solicitar modificacion del plan
        Route::put('modification-processes','ApprovedImprovementPlanController@modificationProcess');

        // Ruta que obtiene todos los registros de las actividades del plan que se hallan solicitado
        Route::get('evaluation-activities-select/{planId}', 'ApprovedImprovementPlanController@getActivitesPlanSelect');

        // Ruta para la gestion de goalProgresses
        Route::resource('goal-progresses', 'GoalProgressController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene todos los registros de goalProgresses
        Route::get('get-goal-progresses', 'GoalProgressController@all')->name('all');

        // Ruta que obtiene todos los registros de goalProgresses
        Route::get('activities/{goalId}', 'GoalProgressController@getActivitesGoal');
        
        // Ruta para enviar el avance a revision
        Route::get('send-review-goal-progress/{goalProgressId}', 'GoalProgressController@sendReviewGoalProgress');

        // Ruta que obtiene todos los registros de goalProgresses
        Route::get('activity-weigth/{activityId}', 'GoalProgressController@getActivityWeigth');

        // Ruta para exportar los datos de goalProgresses
        Route::post('export-goalProgresses', 'GoalProgressController@export')->name('export');

        Route::put('approved-progress', 'GoalProgressController@approvedProgress');
        
        Route::get('send-request-improvement-plan/{improvementPlanId}', 'ImprovementPlanController@sendRequestImprovementPlan');
        
        Route::get('process-request-improvement-plan/{improvementPlanId}', 'ImprovementPlanController@processRequestImprovementPlan');

        // Ruta para la gestion de closedImprovementPlans
        Route::resource('closed-improvement-plans', 'ClosedImprovementPlanController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene todos los registros de closedImprovementPlans
        Route::get('get-closed-improvement-plans', 'ClosedImprovementPlanController@all')->name('all');

        // Ruta que exporta la información de un plan de mejoramiento
        Route::get('export-improvement-plan-closed/{improvementPlanId}', 'ClosedImprovementPlanController@exportImprovementPlan');

        // Ruta para exportar los datos de closedImprovementPlans
        Route::post('export-closedImprovementPlans', 'ClosedImprovementPlanController@export')->name('export');

        Route::get('send-improvement-plan/{improvementPlanId}', 'ImprovementPlanController@sendImprovementPlan');

        
        // Ruta para la gestion de annotationEvaluations
        Route::resource('annotation-evaluations', 'AnnotationEvaluationController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de annotationEvaluations
        Route::get('get-annotation-evaluations', 'AnnotationEvaluationController@all')->name('all');
        // Ruta para exportar los datos de annotationEvaluations
        Route::post('export-annotationEvaluations', 'AnnotationEvaluationController@export')->name('export');

        Route::post('closed-improvement-delete-file/{id}', 'ClosedImprovementPlanController@updateFile');

    });
});

