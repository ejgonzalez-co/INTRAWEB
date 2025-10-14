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
Route::prefix('documentos-electronicos')->group(function() {
    Route::get('firmar', 'DocumentoController@indexSign');
    Route::get('validar-codigo/{id}', 'DocumentoController@indexSign');

    Route::post('validar-codigo-ingresado', 'DocumentoController@validarCodigoIngresado');
    Route::put('firmar-documento', 'DocumentoController@firmarDocumento');

    Route::put('enviar-documento', 'DocumentoController@enviarDocumento');

    Route::get('validar-documento-electronico', 'DocumentoController@indexValidarDocumento');

    Route::post('validar-adjunto-documento-electronico', 'DocumentoController@validarAdjuntoDocumento');

    Route::post('validar-documento-electronico-codigo/{codigo}', 'DocumentoController@validarDocumento');
});
Route::group(['middleware' => ['auth']], function() {
    Route::prefix('documentos-electronicos')->group(function() {
        Route::get('/', 'DocumentoController@index');
        // Ruta para la gestion de tipo-documentos
        Route::resource('tipo-documentos', 'TipoDocumentoController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de tipo-documentos
        Route::get('get-tipo-documentos', 'TipoDocumentoController@all')->name('all');
        Route::get('get-recipients', 'TipoDocumentoController@getRecipients');
        // Ruta que obtiene los registros de tipo-documentos según sean los permisos de la dependencia del usuario
        Route::get('obtener-tipo-documentos-filtros-docs', 'TipoDocumentoController@getDocumentTypes')->name('getDocumentTypes');
        // Ruta para exportar los datos de tipo-documentos
        Route::post('export-tipo-documentos', 'TipoDocumentoController@export')->name('export');
        // Ruta que obtiene todos los registros de las dependencias
        Route::get('get-dependencies', 'TipoDocumentoController@obtener_dependencias')->name('obtener_dependencias');

        // Ruta para la gestion de documentos
        Route::resource('documentos', 'DocumentoController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de documentos
        Route::get('get-documentos', 'DocumentoController@all')->name('all');
        // Ruta para exportar los datos de documentos
        Route::post('export-documentos', 'DocumentoController@export')->name('export');

        // Ruta para asignar el leido al documento
        Route::post('documentos-leido/{documento_id}', 'DocumentoController@leido')->name('leido');

        // Rutas para el componente de documentosElectronicos
        Route::post('crear-documento', 'DocumentoController@crearDocumento')->name('crearDocumento');

        Route::get('get-compartidos', 'DocumentoController@getCompartidos')->name('getCompartidos');
        Route::get('get-asociar-documentos', 'DocumentoController@getDocumentos')->name('getDocumentos');

        Route::post('documentos-delete-file/{id}', 'DocumentoController@updateFile');

        Route::get('get-usuarios', 'DocumentoController@getUsuarios')->name('getUsuarios');
        Route::get('get-usuarios-firmar', 'DocumentoController@getUsuariosFirmar')->name('getUsuariosFirmar');
        Route::get('get-hidden-data/{id}', 'DocumentoController@getHiddenData')->name('getHiddenData');


        // Ruta para asignar el leido de las anotaciones
        Route::post('documentos-leido-anotacion/{documento_id}', 'DocumentoController@leidoAnotacion')->name('leidoAnotacion');

        // Ruta para la gestion de documento-anotacions
        Route::resource('documento-anotacions', 'DocumentoAnotacionController', ['only' => [
            'index', 'update', 'destroy'
        ]]);

        Route::post('documento-anotacions/{de}', ['as' => 'documento-anotacions.store', 'uses' => 'DocumentoAnotacionController@store']);

        // Ruta que obtiene todos los registros de documento-anotacions
        Route::get('get-documento-anotacions/{de}', 'DocumentoAnotacionController@all')->name('all');
        // Ruta para exportar los datos de documento-anotacions
        Route::post('export-documento-anotacions', 'DocumentoAnotacionController@export')->name('export');

        // Ruta para obtener la información del documento según el id por parámetro. Se usa para las entradas recientes del dashboard y para los links a los documentos
        Route::get('get-documentos-show-dashboard/{id}', 'DocumentoController@showFromDashboard');

        // Ruta que exporta el historial de un documento
        Route::post('export-historial-documento/{id}', 'DocumentoController@exportarHistorial')->name('exportarHistorial');

        Route::put('documento-compartir', 'DocumentoController@compartirDocumento');
        // Ruta para validar si el metadato que se va a eliminar tiene o no relación con un documento, o sea si ya esta en uso
        Route::post('validar-metadato-eliminar', 'TipoDocumentoController@validarEliminarMetadato')->name('validarEliminarMetadato');
        // Ruta para reenviar el correo a un usuario externo que el estado sea Pendiente de firma
        Route::post('reenviar-correo-usuario-externo', 'DocumentoController@reenviarCorreoUsuarioExterno')->name('reenviarCorreoUsuarioExterno');
    });
});
