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

Route::prefix('expedientes-electronicos')->group(function() {
    Route::get('validar-documento-expediente', 'ExpedienteController@indexValidarDocumento');

    Route::post('validar-adjunto-documento-expediente', 'ExpedienteController@validarAdjuntoDocumento');

    Route::post('validar-documento-expediente-codigo/{codigo}', 'ExpedienteController@validarDocumento');

    Route::get('usuarios-externos', 'ExpedienteController@indexUsuarioExterno')->name('indexUsuarioExterno');
    
    // Ruta que obtiene todos los registros de expedientes
    Route::get('get-expedientes-usuario-externo', 'ExpedienteController@allUsuarioExterno')->name('allUsuarioExterno');

    // Ruta para la gestion de documentos-expedientes
    Route::get('documentos-expedientes-usuario-externo', 'DocumentosExpedienteController@indexDocumentosUsuarioExterno')->name('indexDocumentosUsuarioExterno');
    
    // Ruta que obtiene todos los registros de documentos-expedientes
    Route::get('get-documentos-expedientes-usuario-externo', 'DocumentosExpedienteController@allDocumentosUsuarioExterno')->name('allDocumentosUsuarioExterno');

    Route::get('documentos-expedientes-detalle-usuario-externo/{id}', 'DocumentosExpedienteController@show')->name('show');

    // Ruta para asignar el leido al expediente
    Route::post('expediente-leido/{expediente_id}/{rol_aplicado}', 'ExpedienteController@registrarLeido')->name('registrarLeido');

    //Ruta que obtiene los tipos documentales según la serie y subserie seleccionada en la creación y asignación del documento al expediente (que_desea_hacer=2)
    Route::get('get-tipos-documentales-crear-expedientes/{serieId}/{subSerieId?}', 'DocumentosExpedienteController@tiposDocumentalesExpedienteCreado')->name('tiposDocumentalesExpedienteCreado');

    // Obtiene los usuarios que han asociado al menos un documento a un expediente
    Route::get('get-users-asocio-documento-filtro/{id_expediente}', 'DocumentosExpedienteController@getUsersAsocioDocumentoFiltro')->name('getUsersAsocioDocumentoFiltro');

    // Ruta para exportar los datos de documentos-expedientes
    Route::post('export-documentos-expedientes', 'DocumentosExpedienteController@export')->name('export');

    //Ruta que obtiene los tipos documentales para los documentos del expediente
    Route::get('get-tipos-documentales/{encripteId?}', 'DocumentosExpedienteController@tiposDocumentales')->name('tiposDocumentales');
});

Route::group(['middleware' => ['auth']], function() {
    Route::prefix('expedientes-electronicos')->group(function() {
        Route::get('/', 'ExpedientesElectronicosController@index');
        // Ruta que obtiene todos los registros de las dependencias
        Route::get('get-dependencies', 'ExpedienteController@obtener_dependencias')->name('obtener_dependencias');

        // Ruta para la gestion de expedientes
        Route::resource('expedientes', 'ExpedienteController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de expedientes
        Route::get('get-expedientes', 'ExpedienteController@all')->name('all');
        // Ruta para exportar los datos de expedientes
        Route::post('export-expedientes', 'ExpedienteController@export')->name('export');
        // Ruta que obtiene los tipos de expedientes
        Route::get('get-tipo-expediente', 'ExpedienteController@obtenerTipoExpediente')->name('obtenerTipoExpediente');
        // Obtiene el expediente para mostrar el documento
        Route::get('obtener-expediente', 'ExpedienteController@obtenerExpediente')->name('obtener-expediente');
        // Envia la peticion para asociar el documento al expediente
        Route::post('asociar-expediente/{expediente_id}', ['as' => 'asociar-expediente.asociarDocumento', 'uses' => 'DocumentosExpedienteController@asociarDocumento']);
        // Ruta que obtiene los expedientes que se revisen por los filtros
        Route::get('get-expediente-filtros/{query?}', 'ExpedienteController@getExpedienteFiltros')->name('getExpedienteFiltros');
        // Ruta para cerrar el expediente
        Route::get('cerrar-expediente/{documentoId}', 'ExpedienteController@enviarFirmaCerrado');
        // Ruta que obtiene los usuarios responsables para expedientes
        Route::get('obtener-responsable', 'ExpedienteController@obtenerResponsable')->name('obtenerResponsable');
        // Envia la peticion para crear el expediente y asociar el documento que se seleeciono
        Route::post('asociar-expediente-asociar', ['as' => 'asociar-expediente-asociar.crearExpedienteAsociar', 'uses' => 'DocumentosExpedienteController@crearExpedienteAsociar']);
        // Ruta para aprobar o devolver un expediente
        Route::put('aprobar-firmar-expedientes', 'ExpedienteController@aprobarFirmarExpedientes');

        // Ruta para la gestion de documentos-expedientes
        Route::resource('documentos-expedientes', 'DocumentosExpedienteController', ['only' => [
            'index', 'store', 'update', 'destroy', 'show'
        ]]);
        // Ruta que obtiene todos los registros de documentos-expedientes
        Route::get('get-documentos-expedientes', 'DocumentosExpedienteController@all')->name('all');
        
        //Ruta para eliminar los documentos del input file
        Route::post('expediente-doc-delete-file/{id}', 'DocumentosExpedienteController@updateFile');
        //Ruta que obtiene los pqrs que esten en estado
        Route::get('modulo-pqrs', 'DocumentosExpedienteController@moduloPqrs')->name('moduloPqrs');
        // Ruta que obtiene los registros de interna
        Route::get('modulo-interna', 'DocumentosExpedienteController@moduloInterna')->name('moduloInterna');
        // Ruta que obtiene los registros de externa recibida
        Route::get('modulo-recibida', 'DocumentosExpedienteController@moduloRecibida')->name('moduloRecibida');
        // Ruta que obtiene los registros de externa enviada
        Route::get('modulo-enviada', 'DocumentosExpedienteController@moduloEnviada')->name('moduloEnviada');
        // Ruta que obtiene los registros de documentos electronicos
        Route::get('modulo-documentos-electronicos', 'DocumentosExpedienteController@moduloDocumentoselectronicos')->name('moduloDocumentoselectronicos');
        // Ruta para cambiar el estado del documento a eliminado
        Route::get('cambiar-estado-documento/{documentoId}/{dato}', 'DocumentosExpedienteController@cambiarEstadoDocumento');
        //Ruta que obtiene los tipos documentales disponibles, para listarlos en el filtro de búsqueda
        Route::get('get-tipos-documentales-filtro', 'DocumentosExpedienteController@obtenerTiposDocumentalesFiltro')->name('obtenerTiposDocumentalesFiltro');

        // Ruta para la gestion de tipos-documentals
        Route::resource('tipos-documentals', 'TiposDocumentalController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de tipos-documentals
        Route::get('get-tipos-documentals', 'TiposDocumentalController@all')->name('all');
        // Ruta para exportar los datos de tipos-documentals
        Route::post('export-tipos-documentals', 'TiposDocumentalController@export')->name('export');

        // Ruta para la gestion de doc-expediente-historials
        Route::resource('doc-expediente-historials', 'DocExpedienteHistorialController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de doc-expediente-historials
        Route::get('get-doc-expediente-historials', 'DocExpedienteHistorialController@all')->name('all');
        // Ruta para exportar los datos de doc-expediente-historials
        Route::post('export-doc-expediente-historials', 'DocExpedienteHistorialController@export')->name('export');
        // Consulta usuarios y depedendencias a partir de un query ingresado
        Route::get('get-usuarios-autorizados', 'ExpedienteController@getUsuariosAutorizados')->name('getUsuariosAutorizados');

        // Ruta para la gestion de expediente-historials
        Route::resource('expediente-historials', 'ExpedienteHistorialController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de expediente-historials
        Route::get('get-expediente-historials', 'ExpedienteHistorialController@all')->name('all');
        // Ruta para exportar los datos de expediente-historials
        Route::post('export-expediente-historials', 'ExpedienteHistorialController@export')->name('export');
        // Ruta que obtiene si la correspondencia ya esta relacionada al expediente seleccionado
        Route::get('get-correspondencia-relacionada/{campo}/{id_expediente}', 'ExpedienteHistorialController@correspondenciaRelacionada')->name('correspondenciaRelacionada');
        // Ruta para obtener los datos del expediente al momento de abril la informacion
        Route::get('get-informacion-expediente/{idExpediente}', 'ExpedienteController@getInformacionExpediente')->name('getInformacionExpediente');

        // Ruta que exporta el historial del expediente
        Route::post('export-historial-expediente/{id}', 'ExpedienteController@exportHistorial')->name('exportHistorial');

        // Obtiene los usuarios que han asociado al menos un documento a un expediente
        Route::get('get-users-responsable-expediente-filtro', 'ExpedienteController@getUsersResponsableExpedienteFiltro')->name('getUsersResponsableExpedienteFiltro');

        // Registrar el leído de las anotaciones de un expediente según el expediente_id y el usuario
        Route::post('leido-anotacion-expediente/{ee_expediente_id}', 'ExpedienteController@leidoAnotacionExpediente')->name('leidoAnotacionExpediente');
        // Ruta para crear una anotación de un expediente
        Route::post('expedientes-anotaciones/{expediente_id}', 'ExpedienteController@guardarAnotacionExpediente')->name('guardarAnotacionExpediente');

        // Registrar el leído de las anotaciones de un documento de un expediente según el expediente_id y el usuario
        Route::post('leido-anotacion-documento-expediente/{ee_expediente_id}', 'DocumentosExpedienteController@leidoAnotacionDocumentoExpediente')->name('leidoAnotacionDocumentoExpediente');
        // Ruta para crear una anotación de un documento de un expediente
        Route::post('documentos-expedientes-anotaciones/{expediente_id}', 'DocumentosExpedienteController@guardarAnotacionDocumentoExpediente')->name('guardarAnotacionDocumentoExpediente');


        // Descargar los documentos autorizados de un expediente
        Route::post('descargar-documentos-expediente/{ee_expediente_id}', 'DocumentosExpedienteController@descargarDocumentosExpediente')->name('descargarDocumentosExpediente');
    });
});
