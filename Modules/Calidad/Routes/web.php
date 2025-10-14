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

Route::group(['middleware' => ['auth']], function() {
    Route::prefix('calidad')->group(function() {
        Route::get('/', 'CalidadController@index');

        // Ruta para la gestion de tipo-sistemas
        Route::resource('tipo-sistemas', 'TipoSistemaController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de tipo-sistemas
        Route::get('get-tipo-sistemas', 'TipoSistemaController@all')->name('all');

        Route::get('get-tipo-sistemas-activos', 'TipoSistemaController@obtenerTiposActivos')->name('obtenerTiposActivos');
        // Ruta para exportar los datos de tipo-sistemas
        Route::post('export-tipo-sistemas', 'TipoSistemaController@export')->name('export');

        // Ruta para la gestion de documento-tipo-documentos
        Route::resource('documento-tipo-documentos', 'DocumentoTipoDocumentoController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de documento-tipo-documentos
        Route::get('get-documento-tipo-documentos', 'DocumentoTipoDocumentoController@all')->name('all');
        // Ruta para exportar los datos de documento-tipo-documentos
        Route::post('export-documento-tipo-documentos', 'DocumentoTipoDocumentoController@export')->name('export');

        // Ruta para la gestion de tipo-procesos
        Route::resource('tipo-procesos', 'TipoProcesoController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de tipo-procesos
        Route::get('get-tipo-procesos', 'TipoProcesoController@all')->name('all');
        // Ruta para exportar los datos de tipo-procesos
        Route::post('export-tipo-procesos', 'TipoProcesoController@export')->name('export');

        // Ruta para la gestion de procesos
        Route::resource('procesos', 'ProcesoController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de procesos
        Route::get('get-procesos', 'ProcesoController@all')->name('all');

        // Ruta que obtiene todos los registros de procesos agripados por su proceso padre, si lo tiene
        Route::get('obtener-procesos', 'ProcesoController@obtenerProcesos')->name('obtenerProcesos');

        // Ruta para exportar los datos de procesos
        Route::post('export-procesos', 'ProcesoController@export')->name('export');

        Route::get('obtener-responsables', 'ProcesoController@obtenerResponsables')->name('obtenerResponsables');

        // Ruta para la gestion de documentos
        Route::resource('documentos-calidad', 'DocumentoController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de documentos
        Route::get('get-documentos', 'DocumentoController@all')->name('all');
        // Ruta que obtiene todos los registros de documentos
        Route::get('documentos-calidad/get-documentos', 'DocumentoController@all')->name('all');
        // Ruta para exportar los datos de documentos
        Route::post('export-documentos', 'DocumentoController@export')->name('export');

        // Rutas para el componente de documentosElectronicos
        Route::post('crear-documento', 'DocumentoController@crearDocumento')->name('crearDocumento');

        // Ruta para asignar el leido al documento
        Route::post('documentos-leido/{documento_id}', 'DocumentoController@leido')->name('leido');

        // Ruta que obtiene todos los registros de documentos en estado 'Público'
        Route::get('obtener-documentos-publicos', 'DocumentoController@obtenerDocumentosPublicos')->name('obtenerDocumentosPublicos');

        Route::get('get-usuarios', 'DocumentoController@getUsuarios')->name('getUsuarios');

        Route::get('get-tipo-documentos-activos', 'DocumentoTipoDocumentoController@obtenerTiposDocumentosActivos')->name('obtenerTiposDocumentosActivos');

        Route::get('get-tipo-documentos-activos-documentos/{tipo_sistema}', 'DocumentoTipoDocumentoController@obtenerTiposDocumentosActivosDoc')->name('obtenerTiposDocumentosActivosDoc');

        Route::get('get-procesos-activos', 'ProcesoController@obtenerProcesosActivos')->name('obtenerProcesosActivos');

        Route::get('get-procesos-activos-documentos/{tipo_sistema}', 'ProcesoController@obtenerProcesosActivosDoc')->name('obtenerProcesosActivosDoc');

        Route::get('get-procesos-activos-distribucion', 'ProcesoController@obtenerProcesosActivosDis')->name('obtenerProcesosActivosDis');


        Route::get('get-procesos-activos-macro/{macroproceso}', 'ProcesoController@obtenerSubProcesosActivos')->name('obtenerSubProcesosActivos');

        Route::get('get-solo-procesos-activos', 'ProcesoController@obtenerSoloProcesosActivos')->name('obtenerSoloProcesosActivos');

        // Ruta que obtiene todos los registros de documentos
        Route::get('arbol-documentos', 'DocumentoController@arbolDocumentos')->name('arbolDocumentos');

        Route::get('obtener-arbol-documentos', 'DocumentoController@obtenerArbolDocumentos')->name('obtenerArbolDocumentos');

        // Ruta para la gestion de documento-solicitud-documentals
        Route::resource('documento-solicitud-documentals', 'DocumentoSolicitudDocumentalController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de documento-solicitud-documentals
        Route::get('get-documento-solicitud-documentals', 'DocumentoSolicitudDocumentalController@all')->name('all');
        // Ruta para exportar los datos de documento-solicitud-documentals
        Route::post('export-documento-solicitud-documentals', 'DocumentoSolicitudDocumentalController@export')->name('export');

        // Ruta que exporta el historial de una solicitud documental
        Route::post('export-historial-solicitud-documental/{id}', 'DocumentoSolicitudDocumentalController@exportarHistorial')->name('exportarHistorial');

        Route::put('gestionar-solicitud-documental', 'DocumentoSolicitudDocumentalController@gestionarSolicitudDocumental');

        // Ruta que exporta el historial de una solicitud documental
        Route::post('generar-nueva-version', 'DocumentoController@generarNuevaVersion')->name('generarNuevaVersion');

        //Ruta inicial
        Route::get('/', 'MapaProcesosController@index');

        // Ruta para la gestion de mapa-procesos
        Route::resource('mapa-procesos', 'MapaProcesosController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de mapa-procesos
        Route::get('get-mapa-procesos', 'MapaProcesosController@all')->name('all');
        // Ruta para exportar los datos de mapa-procesos
        Route::post('export-mapa-procesos', 'MapaProcesosController@export')->name('export');
        // Ruta para guardar los enlaces creados por el usuario en el mapa de procesos
        Route::post('guardar-links-mapa-procesos/{imageId}', 'MapaProcesosController@guardarLinksMapaProcesos')->name('guardarLinksMapaProcesos');

        Route::get('mapa-procesos-publico', 'MapaProcesosController@index_publico')->name('mapa-procesos-publico');

        Route::get('get-mapa-procesos-publico', 'MapaProcesosController@all_publico')->name('get-mapa-procesos-publico');

        // Ruta para guardar los enlaces creados por el usuario en el mapa de procesos
        Route::delete('eliminar-mapa-procesos/{imageId}', 'MapaProcesosController@destroy')->name('destroy');

        // Ruta que se usa en los enlaces de los procesos de los mapas de procesos, pasando como parámentro el nombre del proceso
        Route::get('documentos-calidad/{nombre_proceso}', 'DocumentoController@index')->name('index');
    });
});
