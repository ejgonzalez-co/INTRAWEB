//// Definimos un grupo de rutas con un prefijo dinámico y protegidas por el middleware de autenticación.
Route::prefix('{{ $config->modelNames->dashedPlural }}')->middleware(['auth'])->group(function() {

    //Ruta inicial
    Route::get('/', '{{ $config->modelNames->name }}Controller@index');

    // Ruta para la gestion de {{ $config->modelNames->dashedPlural }}
    Route::resource('{{ $config->modelNames->dashedPlural }}', '{{ $config->prefixes->getRoutePrefixWith('/') }}{{ $config->modelNames->name }}Controller', ['only' => [
        'index', 'store', 'update', 'destroy'
    ]]);
    // Ruta que obtiene todos los registros de {{ $config->modelNames->dashedPlural }}
    Route::get('get-{{ $config->modelNames->dashedPlural }}', '{{ $config->prefixes->getRoutePrefixWith('/') }}{{ $config->modelNames->name }}Controller@all')->name('all');
    // Ruta para exportar los datos de {{ $config->modelNames->dashedPlural }}
    Route::post('export-{{ $config->modelNames->dashedPlural }}', '{{ $config->prefixes->getRoutePrefixWith('/') }}{{ $config->modelNames->name }}Controller@export')->name('export');
});