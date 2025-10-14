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

Route::group(['prefix' => 'correspondence'], function () {
    Route::get('obtener-correos-integrados-Webklex', 'CorreoIntegradoController@obtenerCorreosIntegrados_Webklex');

    Route::get('obtener-correos-integrados-laminas', 'CorreoIntegradoController@obtenerCorreosIntegrados_laminas');

    // Última librería oficial y adoptada para la descarga de correos electrónicos al sistema.
    Route::get('obtener-correos-integrados-phpimap', 'CorreoIntegradoController@obtenerCorreosIntegrados_phpimap');

    Route::get('validar-correspondence-external', 'ExternalController@validarExternal');

    Route::post('validar-correspondence-external-codigo/{codigo}', 'ExternalController@validarExternalCodigo');

    Route::post('validar-correspondence-external-documento', 'ExternalController@validarExternalDocumento');


    Route::get('validar-correspondence-internal', 'InternalController@validarInternal');

    Route::post('export-repository-correspondence-internal', 'InternalController@exportRepositoryCorrespondenceInternal');

    Route::post('validar-correspondence-internal-codigo/{codigo}', 'InternalController@validarInternalCodigo');

    Route::post('validar-correspondence-internal-documento', 'InternalController@validarInternalDocumento');


    //rutas usadas en el correo que le llega al ciudadano en recibida
    Route::get('validate-correspondence-received-email', 'ExternalReceivedController@indexEmail');
    Route::post('validar-correspondence-received-code', 'ExternalReceivedController@validarExternalCodigoFromEmail');

    Route::get('validate-correspondence-external-email', 'ExternalController@indexEmail');
    Route::post('validar-correspondence-external-code', 'ExternalController@validarExternalCodigoFromEmail');

    Route::get('watch-archives', 'ExternalController@watchDocument');

    Route::get('watch-archives-received', 'ExternalReceivedController@watchDocument');


    //********** Filtros para la consulta de los PQRS según el consecutivo y código de validación de la correspondencia recibida **********
    // Ruta que obtiene la vista de consulta de p-q-r-s del ciudadano por medio de la correspondencia recibida
    Route::get('search-pqrs-ciudadano', 'ExternalReceivedController@consultarVistaPQRS')->name('consultarVistaPQRS');
    // Ruta que obtiene el registro de p-q-r-s del ciudadano por medio de la correspondencia recibida
    Route::get('get-recibida-pqrs-ciudadano', 'ExternalReceivedController@obtenerPQRSCiudadano')->name('obtenerPQRSCiudadano');

    //Ruta para la migracion de las contingencias de correspondencia recibida
    Route::post('migration-modal-externals', 'ExternalReceivedController@MigrateExternals');

    // Ruta para verificar la cantidad de correos que se descargaron de un día específico, esto validándolo con la cantidad de correos recibidos en el correo integrado
    Route::get('verificar-correos-descargados/{fecha_verificacion?}', 'CorreoIntegradoController@verificarCorreosDescargados');
});


Route::group(['middleware' => ['auth']], function() {
    Route::group(['prefix' => 'correspondence'], function () {
        Route::get('/', function () {
            dd('This is the Correspondence module index page. Build something great!');
        });

        // Obtiene todos los datos de una constante dependiendo de nombre
        Route::get('get-constants/{name}', 'UtilController@getConstants');

        //RUTAS DE INTERNA

        // Ruta para la gestion de internals
        Route::resource('internals', 'InternalController', ['only' => [
            'index', 'store', 'update', 'destroy','show','edit'
        ]]);

        // Ruta para obtener la información de la correspondencia interna según el id por parámetro. Se usa para las entradas recientes del dashboard
        Route::get('get-internals-show-dashboard/{id}', 'InternalController@showFromDashboard');

        Route::post('internals-delete-file/{id}', 'InternalController@updateFile');

        // Ruta que obtiene todos los registros de internals
        Route::get('get-internals', 'InternalController@all')->name('all');

        // Ruta que obtiene todos los registros de internals
        Route::get('get-internals-repositories', 'InternalController@allRepositoryInternals');

        // Ruta para exportar los datos de internals
        Route::post('export-internals', 'InternalController@export')->name('export');

        // Ruta que exporta el historial de la interna
        Route::post('export-historial-internal/{id}', 'InternalController@exportHistorial')->name('exportHistorial');

        Route::get('get-internal-edit/{id}', 'InternalController@getDataEdit');

        Route::post('adjuntar-rotulo-internal/{id}', 'InternalController@guardarAdjuntoRotulo')->name('guardarAdjuntoRotulo');

        // Ruta para la gestion de internalAnnotations
        Route::resource('internal-annotations', 'InternalAnnotationController', ['only' => [
            'index', 'update', 'destroy'
        ]]);

        Route::post('internal-annotations/{ci}', ['as' => 'internal-annotations.store', 'uses' => 'InternalAnnotationController@store']);

        Route::get('get-internal-annotations/{ci}', 'InternalAnnotationController@all')->name('all');

        Route::post('export-internal-annotations', 'InternalAnnotationController@export')->name('export');

        Route::get('get-recipients-internal', 'InternalController@getRecipients')->name('getRecipients');
        Route::get('get-only-users', 'InternalController@getOnlyUsers')->name('getOnlyUsers');
        Route::get('get-only-users-sign', 'InternalController@getOnlyUsersSign')->name('getOnlyUsersSign');

        Route::get('get-responsables-respuesta-internal', 'InternalController@getResponsableInterna')->name('getResponsableInterna');



        Route::get('get-types-internal', 'InternalController@getTypes')->name('getTypes');

        Route::put('internal-share', 'InternalController@shareInternal');
        Route::put('sign-internal', 'InternalController@signInternal');


        // Ruta para la gestion de correo integrado
        Route::resource('correo-integrados-list', 'CorreoIntegradoControllerList', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de correo integrado
        Route::get('get-correo-integrados-list', 'CorreoIntegradoControllerList@all')->name('all');

        // Ruta para la gestion de internal-types
        Route::resource('internal-types', 'InternalTypeController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de internal-types
        Route::get('get-internal-types', 'InternalTypeController@all')->name('all');
        // Ruta para exportar los datos de internal-types
        Route::post('export-internal-types', 'InternalTypeController@export')->name('export');

        // Ruta para asignar el leido
        Route::post('internal-read/{correspondence_id}', 'InternalController@read')->name('read');

        // Ruta para asignar el leido
        Route::post('internal-read-check/{correspondence_id}/{estado}', 'InternalController@readCheck')->name('readCheck');

        // Rutas para el componente de interna ceropapeles
        Route::post('crear-interna-ceropapeles', 'InternalController@crearInternaCeroPapeles')->name('crearInternaCeroPapeles');

        // Muestra el index que tiene el iframe del sitio anterior de correspondencia interna
        Route::get('repository-correspondence-internal', 'InternalController@indexRepositorio')->name('indexRepositorio');

        //FIN DE RUTAS DE INTERNA

        //RUTAS DE ENVIADA

        // Ruta para la gestion de externals
        Route::resource('externals', 'ExternalController', ['only' => [
            'index', 'store', 'update', 'destroy','show','edit'
        ]]);

        // Ruta para obtener la información de la correspondencia externa enviada según el id por parámetro. Se usa para las entradas recientes del dashboard
        Route::get('get-externals-show-dashboard/{id}', 'ExternalController@showFromDashboard');

        // Ruta que obtiene las correspondencias publicas
        Route::get('external/correspondences-publics', 'ExternalController@getCorrespondencePublics');

        Route::post('externals-delete-file/{id}', 'ExternalController@updateFile');

        // Ruta que obtiene todos los registros de externals
        Route::get('get-externals', 'ExternalController@all')->name('all');

        // Ruta que obtiene todos los registros del historico externals
        Route::get('get-externals-repository', 'ExternalController@allRepositoryExternals')->name('all');
        // Ruta para exportar los datos de externals
        Route::post('export-externals', 'ExternalController@export')->name('export');

        //Ruta para exportar el historial de la  externa enviada
        Route::post('export-historial-external/{id}', 'ExternalController@exportHistorial')->name('exportHistorial');

        Route::get('get-external-edit/{id}', 'ExternalController@getDataEdit');
        Route::post('adjuntar_rotulo/{id}', 'ExternalController@guardarAdjuntoRotulo')->name('guardarAdjuntoRotulo');

        Route::post('document-preview/{id}', 'ExternalController@documentPreview')->name('documentPreview');

        Route::post('document-with-rotule/{id}', 'ExternalController@saveDocumentWithRotule')->name('saveDocumentWithRotule');

        //RUTAS DE ROTULO DE ENVIADA
        Route::post('document-preview-external/{id}', 'ExternalController@documentPreview');

        Route::post('document-with-rotule-external/{id}', 'ExternalController@saveDocumentWithRotule');

        Route::post('update-external-rotule/{id}', 'ExternalController@updateDocumentExternal')->name('updateDocumentExternal');

        // Ruta para la gestion de externalAnnotations
        Route::resource('external-annotations', 'ExternalAnnotationController', ['only' => [
            'index', 'update', 'destroy'
        ]]);

        Route::post('external-annotations/{ci}', ['as' => 'external-annotations.store', 'uses' => 'ExternalAnnotationController@store']);

        Route::get('get-external-annotations/{ci}', 'ExternalAnnotationController@all')->name('all');

        Route::post('export-external-annotations', 'ExternalAnnotationController@export')->name('export');

        Route::get('get-recipients-external', 'ExternalController@getRecipients')->name('getRecipients');
        // Route::get('get-only-users', 'ExternalController@getOnlyUsers')->name('getOnlyUsers');

        Route::get('get-types-external', 'ExternalController@getTypes')->name('getTypes');

        Route::put('external-share', 'ExternalController@shareExternal');

        // Ruta para la gestion de external-types
        Route::resource('external-types', 'ExternalTypeController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de external-types
        Route::get('get-external-types', 'ExternalTypeController@all')->name('all');
        // Ruta para exportar los datos de external-types
        Route::post('export-external-types', 'ExternalTypeController@export')->name('export');

        // Ruta para la gestion de external-types
        Route::resource('external-types', 'ExternalTypeController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de external-types
        Route::get('get-external-types', 'ExternalTypeController@all')->name('all');

        //Editar tamaño del rotulo
        Route::post('/edit-rotule/{module}', 'UtilController@editRotule')->name("editRotule");

        //Obtener propiedades del rotulo
        Route::get('get-rotule-props/{module}', 'ExternalTypeController@getPropsRotule')->name('getPropsRotule');

        //Crear Propiedades del rotulo
        Route::post('create-rotule-props/{module}', 'ExternalTypeController@createProps')->name('createProps');
        // Ruta para exportar los datos de external-types
        Route::post('export-external-types', 'ExternalTypeController@export')->name('export');

        // Ruta para asignar el leido
        Route::post('external-read/{correspondence_id}', 'ExternalController@read')->name('read');

        // Rutas para el componente de interna ceropapeles
        Route::post('crear-externa-ceropapeles', 'ExternalController@crearExternaCeroPapeles')->name('crearExternaCeroPapeles');

        Route::put('sign-external', 'ExternalController@signExternal');

        // Muestra el index que tiene el iframe del sitio anterior de correspondencia enviada
        Route::get('repository-externals', 'ExternalController@indexRepositorio')->name('indexRepositorio');

         // Muestra el index que tiene el iframe del sitio anterior de correspondencia enviada
         Route::post('export-repository-external', 'ExternalController@exportRepositoryExternal');

        //FIN DE RUTAS DE ENVIADA

        //**INICIO RUTAS DE RECIBIDA */

        //RUTAS DE ROTULO DE RECIBIDA
        Route::post('document-preview-external-received/{id}', 'ExternalReceivedController@documentPreview');

        Route::post('document-with-rotule-external-received/{id}', 'ExternalReceivedController@saveDocumentWithRotule');

        Route::post('update-received-rotule/{id}', 'ExternalReceivedController@updateDocumentExternalReceived')->name('updateDocumentExternalReceived');

        // Ruta para la gestion de types-documentaries
        Route::resource('types-documentaries', 'TypeDocumentaryController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de types-documentaries
        Route::get('get-types-documentaries', 'TypeDocumentaryController@all')->name('all');
        // Ruta para exportar los datos de types-documentaries
        Route::post('export-types-documentaries', 'TypeDocumentaryController@export')->name('export');
        // Ruta en la cual se ven los tipos de documentos que estan activos
        Route::get('get-types-documentaries-actives', 'TypeDocumentaryController@getTypesDocumentariesActives')->name('getTypesDocumentariesActives');

        //Ruta para eliminar los adjuntos
        Route::post('received-delete-file/{id}', 'ExternalReceivedController@updateFile');

        // Ruta para asignar el leido
        Route::post('external-received-read/{correspondence_id}', 'ExternalReceivedController@read')->name('read');
        // Ruta para la gestion de external-receiveds
        Route::resource('external-receiveds', 'ExternalReceivedController', ['only' => [
            'index', 'store', 'update', 'destroy', 'show'
        ]]);

        Route::get('get-external-receiveds-edit/{id}', 'ExternalReceivedController@getDataEdit');
        Route::get('get-external-receiveds-show/{id}', 'ExternalReceivedController@show');

        // Ruta para obtener la información de la correspondencia externa recibida según el id por parámetro. Se usa para las entradas recientes del dashboard
        Route::get('get-external-receiveds-show-dashboard/{id}', 'ExternalReceivedController@showFromDashboard');

        // Ruta para obtener la información del correo integrado según el id por parámetro. Se usa para las entradas recientes del dashboard
        Route::get('get-correo-integrado-show-dashboard/{id}', 'CorreoIntegradoController@showFromDashboard');

        // Ruta que obtiene todos los registros de external-receiveds
        Route::get('get-external-receiveds', 'ExternalReceivedController@all')->name('all');

        // Ruta para exportar el detalle sdel historial
        Route::post('export-historial-external-received/{id}', 'ExternalReceivedController@exportHistorial')->name('exportHistorial');

        // Ruta que obtiene todos los registros de external-receiveds
        Route::get('get-external-receiveds-repository', 'ExternalReceivedController@allRepositoryReceiveds');
        // Ruta para devolver la correspondencia recibida
        Route::get('return-external-receiveds/{correspondenceId}/{reason}', 'ExternalReceivedController@returnCorrespondence');

        // Ruta para exportar los datos de external-receiveds
        Route::post('export-external-receiveds', 'ExternalReceivedController@export')->name('export');

        //Ruta para ir a la vista del repositorio de externa recibida
        Route::get('repository-external-receiveds', 'ExternalReceivedController@indexRepositorio')->name('indexRepositorio');


        Route::post('attach-received-label/{id}', 'ExternalReceivedController@saveAttachmentLabel')->name('saveAttachmentLabel');

        Route::post('export-repository-external-receiveds', 'ExternalReceivedController@exportRepositoryExternalReceiveds')->name('exportRepositoryExternalReceiveds');

        // Ruta para exportar los datos de external-receiveds
        Route::put('external-share-received', 'ExternalReceivedController@externalShare');

        // Ruta para la gestion de receivedAnnotations
        Route::resource('received-annotations', 'ReceivedAnnotationController', ['only' => [
            'index', 'update', 'destroy'
        ]]);

        Route::post('received-annotations/{ci}', ['as' => 'received-annotations.store', 'uses' => 'ReceivedAnnotationController@store']);
        // Ruta que obtiene todos los registros de receivedAnnotations
        Route::get('get-received-annotations/{cr}', 'ReceivedAnnotationController@all')->name('all');
        // Ruta para exportar los datos de receivedAnnotations
        Route::post('export-received-annotations', 'ReceivedAnnotationController@export')->name('export');

        // Ruta para la gestion de sents
        Route::resource('sents', 'SentController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de sents
        Route::get('get-sents', 'SentController@all')->name('all');
        // Ruta para exportar los datos de sents
        Route::post('export-sents', 'SentController@export')->name('export');


        // Ruta para la gestion de ciudadanos desde el módulo de la intranet
        Route::resource('citizens', '\Modules\Intranet\Http\Controllers\CitizenController', ['only' => [
            'store', 'update', 'destroy'
        ]]);
        // Ruta para el index de ciudadanos desde el módulo de la intranet
        Route::get('citizens-correspondence', '\Modules\Intranet\Http\Controllers\CitizenController@index')->name('citizens-correspondence.index');
        // Ruta para la gestion de ciudadanos
        Route::get('get-citizens', '\Modules\Intranet\Http\Controllers\CitizenController@all')->name('all');

        Route::get('get-citizens-by-name', '\Modules\Intranet\Http\Controllers\CitizenController@getCitizensByName');

        // Ruta para la gestion de correo-integrados
        Route::resource('correo-integrados', 'CorreoIntegradoController', ['only' => [
            'index', 'store', 'update', 'destroy' , 'show'
        ]]);
        // Ruta que obtiene todos los registros de correo-integrados
        Route::get('get-correo-integrados', 'CorreoIntegradoController@all')->name('all');
        // Ruta para exportar los datos de correo-integrados
        Route::post('export-correo-integrados', 'CorreoIntegradoController@export')->name('export');

        Route::put('correo-integrados-configuracion', 'CorreoIntegradoController@guardarConfiguracionCorreo');

        // Ruta que obtiene todos los registros de correo-integrados
        Route::get('get-correo-integrados-tablero', 'CorreoIntegradoController@consultaTablero')->name('consultaTablero');

        // Ruta para la gestion de planillas
        Route::resource('planillas', 'PlanillaController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de planillas
        Route::get('get-planillas', 'PlanillaController@all')->name('all');
        // Ruta para exportar los datos de planillas
        Route::post('export-planillas', 'PlanillaController@export')->name('export');

        // Ruta para la gestion de planilla-rutas
        Route::resource('planilla-rutas', 'PlanillaRutaController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de planilla-rutas
        Route::get('get-planilla-rutas', 'PlanillaRutaController@all')->name('all');
        // Ruta para exportar los datos de planilla-rutas
        Route::post('export-planilla-rutas', 'PlanillaRutaController@export')->name('export');


        Route::post('validar-second-password', 'UtilController@validarSecondPassword');

        Route::get('faker', 'ExternalController@faker');

        Route::get('chatgpt', 'InternalController@sendMessage');

        //Ruta para ejecutar la funcion para llenar los datos de la tabla
        Route::get('scriptdata','ExternalController@scriptData');

        //Ruta para ejecutar traer las anotaciones de la externa
        Route::get('get-annotations/{id}','ExternalController@getAnnotation');

        Route::put('change-recibido-fisico/{id}', 'ExternalReceivedController@updateRecibidoFisico');

        Route::post('preview-document', 'InternalController@previewDocument')->name('previewDocument');

        Route::post('preview-document-external', 'ExternalController@previewDocument')->name('previewDocument');

        Route::get('obtener-correos-spam-phpimap', 'CorreoIntegradoSpamController@obtenerCorreosIntegradosSpam_phpimap')->name('obtenerCorreosIntegradosSpam_phpimap');

        // Ruta para la gestion de correo-integrado-spams
        Route::resource('correo-integrado-spams', 'CorreoIntegradoSpamController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de correo-integrado-spams
        Route::get('get-correo-integrado-spams', 'CorreoIntegradoSpamController@all')->name('all');
        // Ruta para exportar los datos de correo-integrado-spams
        Route::post('export-correo-integrado-spams', 'CorreoIntegradoSpamController@export')->name('export');

    });

});

