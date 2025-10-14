<?php
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\SnsController;
use App\Http\Controllers\ProfileController;

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
Route::get('/generar-imagen', [ProfileController::class, 'generarImagenConTexto']);
Route::post('/subir-firma', [ProfileController::class, 'subirFirma']);
Route::get('/generar-imagen-firma-general', [ProfileController::class, 'generarImagenConTextoGeneral']);
Route::post('/validar-info-edit', [ProfileController::class, 'validarCambioInformacion']);


//Ruta para obtener los rebotes de los correos
Route::post('/sns-handler', [SnsController::class, 'processNotification']);


Route::get('/', function () {
    // return view('welcome');
    return redirect('/login');
});

Route::get('/firma', 'NavigationController@firmarPDF');
Route::get('/firma2', 'NavigationController@firmarPDFV2');
Route::get('/firmaTCPDF', 'NavigationController@firmarPDF_TCPDF');
Route::get('/firmaTCPDFV2', 'NavigationController@firmarPDF_TCPDFV2');

Route::get('/firmaPAdES', 'NavigationController@signPDF');
Route::get('/firmaCAdES', 'NavigationController@signDocx');
Route::get('/firmaXAdES', 'NavigationController@signXML');


Route::get('/create-role', function () {

    Role::create([
        "name" => "Administrador de mantenimientos"
    ]);
    return view('welcome');
});

Route::get('report-excel-proovedores-joomla/{tc}','UtilController@reportExcelProovedoresJoomla');

Route::get('cancel-subscription','UtilController@cancelSubscriptionView');

Route::post('send-forward-email','SendNotificationController@forwardNotification');

Route::get('/track-mail','UtilController@trackMail');

Route::post('cancel-subscription-process','UtilController@cancelSubscriptionProcess');

// Almacena archivos desde el componete de input file
Route::post('upload-input-files', 'AppBaseController@uploadInputFile');

// Elimina archivos desde el componete de input file
Route::post('delete-input-files', 'AppBaseController@deleteInputFile');


// ********** Rutas para almacenamiento y consulta de adjuntos en un bucket S3 de AWS **********
// Almacena archivos desde el componete de input file en un bucket de AWS
Route::post('upload-input-files-aws', 'AppBaseController@uploadInputFileAWS');

Route::post('read-object-aws', 'AppBaseController@readObjectAWS');
// *********************************************************************************************


Auth::routes(['verify' => true]);

Route::middleware(['auth'])->group(function () {
    // Ruta alterna que sirve para la generacion del token de google (integracion)
    Route::get('create-token-google', '\App\Http\Controllers\GoogleController@__construct');

    Route::get('/home', 'HomeController@index')->middleware('verified')->name('home');
    Route::get('/components', 'NavigationController@components');
    Route::get('/modules', 'NavigationController@modules');

    // Condiciones para la carga de la ruta modules
    Route::get('/modules', function() {
        // Si el usuario tiene el rol de ciudadano, es correcto que cargue dicha ruta
        if(Auth::user()->hasRole("Ciudadano")) {
            return app()->call('App\Http\Controllers\NavigationController@modules');
        } else {
            // De lo contrario se asume que es un funcionario y se redirige al controlador de dicha ruta
            return redirect("/dashboard");
        }
    });

    // Condiciones para la carga de la ruta dashboard
    Route::get('/dashboard', function() {
        // Si el usuario tiene el rol de ciudadano, se redirige a la ruta de modules
        if(Auth::user()->hasRole("Ciudadano")) {
            return redirect("/modules");
        } else {
            // De lo contrario se redirige al controlador de dicha ruta
            return app()->call('App\Http\Controllers\NavigationController@dashboard');
        }
    });

    // Condiciones para la carga de la ruta home
    Route::get('/home', function() {
        // Si el usuario tiene el rol de ciudadano, se redirige a la ruta de modules
        if(Auth::user()->hasRole("Ciudadano")) {
            return redirect("/modules");
        } else {
            // De lo contrario se asume que es un funcionario y se redirige a la ruta de dashboard
            return redirect("/dashboard");
        }
    });

    Route::resource('profile', 'ProfileController', ['only' => [
        'index', 'update'
    ]]);
    Route::get('/get-auth-profile', 'ProfileController@getAuthUser');

    Route::post('/service-terms-change-password', 'NavigationController@serviceTermsChangePassword')->name("service-terms-change-password");

    // Ruta para obtener las vigencias disponibles y asignadas según el trámite (nombreTabla)
    Route::get('get-vigencias/{nombreTabla}/{campoTabla}', 'AppBaseController@getVigencias')->name('getVigencias');
    // Ruta para obtener las vigencias disponibles según un nombreTabla
    Route::get('get-vigencias-tablas/{nombreTabla}', 'AppBaseController@getVigenciasTablas')->name('getVigenciasTablas');
    Route::get('/obtener-totales-dashboard', 'NavigationController@obtenerTotalesDashboard');
    Route::get('/obtener-entradas-recientes', 'NavigationController@obtenerEntradasRecientesDashboard');

    Route::get('consulta-buscador-dashboard', 'NavigationController@buscadorDashboard')->name('buscadorDashboard');

    // Ruta para cerrar sesión, esta se llama desde el sitio de Joomla
    Route::get('/logout-verification-joomla', function() {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            // Cerrar sesión
            Auth::logout();
            // Redirigir o devolver la respuesta según sea necesario
            return redirect('/login')->with('message', 'Has cerrado sesión con éxito.');
        }

        // Si no está autenticado, redirigir al login
        return redirect('/login');
    });
});

// Ruta que obtiene todos los registros de estados por pais
Route::get('get-states-by-country/{country}', 'UtilController@getStatesByCountry');

// Ruta que obtiene todos los registros de estados por pais
Route::get('get-cities-by-state/{state}', 'UtilController@getCitiesByState');

Route::get('refresh_captcha', 'CaptchaController@refreshCaptcha')->name('refresh_captcha');

// Chequea el estado de la sesión del usuario
Route::get('check-session', 'AppBaseController@checkSession');

Route::get('verificarCredencialesAcceso', 'App\Http\Controllers\Auth\LoginController@verificarCredencialesAcceso');

Route::post('/guardar-imagen-firma', function (Request $request) {
    $nombreImagen =  $request->input('ruta').'imagen_' . time() . '.png';

    $parts = explode(',', json_decode($request->input('imagen'))->imagen);

    if (strpos($parts[0], 'data:image/png;base64') === 0) {
        $base64 = $parts[count($parts) - 1];
        $imagen = base64_decode($base64);
    } else {
            // Not a PNG image
            $imagen = null;
        }

    try {
        // Ruta donde se guardará la imagen (por ejemplo, la carpeta storage/app/public)
        Storage::disk('public')->put($nombreImagen, $imagen);
        return response()->json([
            'success' => true,
            'ruta_imagen' => $nombreImagen,
        ]);
    } catch (Exception $e) {
        // Handle storage errors gracefully
        return response()->json([
            'success' => false,
            'message' => 'Error saving image: ' . $e->getMessage(),
        ], 500);
    }
});


Route::post('/guardar-prueba', function (Request $request) {

    // dd($request->all());

    return response()->json([
        'success' => true,
        'ruta_imagen' => $request->all(),
    ]);
});

// Ruta para iniciar sesion como
Route::get('login-outside-vendor', 'Auth\LoginController@showLoginFormExterna');
Route::post('login-outside-vendor', 'Auth\LoginController@loginExternaProvider');
Route::get('logout-outside-vendor', 'Auth\LoginController@logoutOutsideVendor');
// Ruta para manejar la redirección de Google OAuth después de iniciar sesión.
Route::get('/inicio_sesion_google', function () {
    return view('inicio_sesion_google', [
        'appUrl' => env('APP_URL')
    ]);
});


// Ruta para iniciar sesion los usuarios externos de expedientes
Route::get('login-usuarios-externos-expedientes', 'Auth\LoginController@showLoginFormExternaExpedientes');
Route::post('login-usuarios-externos-expedientes', 'Auth\LoginController@loginExternoExpedientes');
Route::get('logout-usuarios-externos-expedientes', 'Auth\LoginController@logoutExternoExpedientes');

// Ruta para validar si el correo ya existe en la tabla de usuarios
Route::get('/search-mail-citizen/{param}','\Modules\Intranet\Http\Controllers\CitizenController@searchMailCitizen');
