<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Http;
// use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Hautelook\Phpass\PasswordHash;
use App\Http\Controllers\AppBaseController;
use Modules\Maintenance\Models\ProviderContract;
use Modules\Maintenance\Models\Providers;
use App\Http\Controllers\JwtController;
use Firebase\JWT\JWT;
use Modules\ExpedientesElectronicos\Models\PermisoUsuariosExpediente;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle account login request
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try{
            $input = $request->all();
            /**
             * Se elimina la posición _token y el remember del array input,
             * ya que genera error al obtener la información del usuario por el email que se esta intentando loguear
             */
            unset($input["_token"]);
            unset($input["remember"]);
            // Obtiene los datos del usuario que se esta logueando a partir del correo
            $user = Auth::getProvider()->retrieveByCredentials($input);
            // Cadena de caracteres permitidos para la encriptación de los parámetros para el envio de la sesión en Joomla
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            if(!empty($user["block"]) && $user["block"] == 1) {
                return redirect()->to('login')->withErrors(['email' => 'Este usuario se encuentra bloqueado. Para recuperar el acceso, comuníquese con el administrador del sistema.']);
            } else if((!empty($user["contract_start"]) && date("Y-m-d") < $user["contract_start"]) || (!empty($user["contract_end"]) && date("Y-m-d") > $user["contract_end"])) {
                return redirect()->to('login')->withErrors(['email' => 'Su fecha de contrato ha caducado. Por favor, contacte al administrador del sistema para verificar su estado de contrato.']);
            }
            // Si el usuario cuenta con password asignado en la tabla de usuarios, quiere decir que ya tiene una contraseña creada y habilitada
            if($user && $user["password"]) {

                //Registra la ultima conexion del usuario
                User::where('id',$user['id'])->update([
                    'ultima_conexion'=> date("Y-m-d H:i:s")
                ]);
                
                // Intenta levantar la sesión en Laravel
                if(Auth::attempt(['email' => $input["email"], 'password' => $input["password"]])) {
                    // Si el usuario tiene el valor de username, es un usuario de integración con Joomla,
                    // además, si la integración con Joomla esta habilitada
                    if($user["username"] && config("app.integracion_sitio_joomla")) {
                        $rolesUser = $user->getRoleNames();
                        $rolesNecesariosJoomla = ["Registered","Intranet"];
                        $roles = array_merge($rolesUser->toArray(), $rolesNecesariosJoomla);
                        $rolesEncode = json_encode($roles);
                
                        // Redirige al sitio de integración Joomla
                        return redirect(config("app.url_joomla")."/sesion_joomla_repositorio.php?u=".base64_encode($user['username']).":".substr(str_shuffle($permitted_chars), 0, 4)."&p=".base64_encode($input['password']).":".substr(str_shuffle($permitted_chars), 0, 4)."&a=".base64_encode("login").":".substr(str_shuffle($permitted_chars), 0, 4)."&h=".base64_encode(config("app.url")).":".substr(str_shuffle($permitted_chars), 0, 4)."&ap=1&uji=".base64_encode($user['user_joomla_id'])."&R=".base64_encode($rolesEncode));
                    } else {
                        // Registra la ultima conexion del usuario
                        User::where('id', $user['id'])->update([
                            'ultima_conexion' => date("Y-m-d H:i:s")
                        ]);
                        // Se redirige a la función de authenticated para posteriormente redirigirlo a la vista de módulos
                        return $this->authenticated($request, $user);
                    }
                } else {
                    return redirect()->to('login')->withErrors(['email' => 'Estas credenciales no coinciden con nuestros registros.']);
                }

                


            } else {
                // Si el usuario tiene el valor de username, es un usuario de integración con Joomla, además, si la integración con Joomla esta habilitada
                if($user && $user["username"] && config("app.integracion_sitio_joomla")) {
                    // Integracion de contraseñas
                    if($user["password"] == ""){
                        if($this->isCorrectJoomlaPassword($user["username"],$input["password"])){
                            // Actualiza la contraseña con el hashing de Laravel
                            User::where("id",$user["id"])->update(["password" => Hash::make($input["password"])]);

                            // Obtiene los datos del usuario que se esta logueando a partir del correo
                            $user = Auth::getProvider()->retrieveByCredentials($input);

                            // Loguea el usuario verificado previamente
                            Auth::login($user);

                            //Registra la ultima conexion del usuario
                            User::where('id',$user['id'])->update([
                                'ultima_conexion'=> date("Y-m-d H:i:s")
                            ]);

                            // Se redirige a la función de authenticated para posteriormente redirigirlo a la vista de módulos
                            // return $this->authenticated($request, $user);
                            return redirect(config("app.url_joomla")."/sesion_joomla_repositorio.php?u=".base64_encode($user['username']).":".substr(str_shuffle($permitted_chars), 0, 4)."&p=".base64_encode($input['password']).":".substr(str_shuffle($permitted_chars), 0, 4)."&a=".base64_encode("login").":".substr(str_shuffle($permitted_chars), 0, 4)."&h=".base64_encode(config("app.url")).":".substr(str_shuffle($permitted_chars), 0, 4));
                        }
                        else{
                            return redirect()->to('login')->withErrors(['email' => 'Este usuario no tiene credenciales de acceso válidas, por favor de click en recuperar contraseña o comuníquese con el administrador.']);
                        }
                    }else{
                        return redirect()->to('login')->withErrors(['email' => 'Este usuario no tiene credenciales de acceso válidas, por favor de click en recuperar contraseña o comuníquese con el administrador.']);
                    }
                }else{
                    return redirect()->to('login')->withErrors(['email' => 'Este usuario no tiene credenciales de acceso válidas, por favor de click en recuperar contraseña o comuníquese con el administrador.']);

                }
            }
        }catch (\Exception $e) {
            $appBaseController = new AppBaseController();
            $appBaseController->generateSevenLog('login', 'App\Http\Controllers\Auth\loginController -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine());
            return redirect()->to('login')->withErrors(['server' => 'Ocurrió un problema al conectarse al servidor. Intente más tarde o contacte al soporte técnico.']);
        }
    }

    /**
     * Verifica la contraseña de acceso del usuario obtenidas desde el sitio de Joomla
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function isCorrectJoomlaPassword(string $username,string $password) : bool{
        $userPassword = DB::connection('joomla')->table('users')->where('username',$username)->first()->password;

        // Valida si la contraseña tiene un hashing MD5 o Bcrypt
        if(strpos($userPassword,'$P$') === 0){
            $passwordHasher = new PasswordHash(10,true);
            $match = $passwordHasher->CheckPassword($password,$userPassword);
        }
        else{
            $userPassword = explode(":",$userPassword)[0];
            $match = $userPassword == md5($password);
        }
        return $match;
    }

    /**
     * Verifica las credenciales de acceso del usuario obtenidas desde el sitio de Joomla,
     * si son correctas, lo loguea, de lo contrario arroja un mensaje de credenciales inválidas
     *
     * @param Request $request
     * @return void
     */
    public function verificarCredencialesAcceso(Request $request)
    {
        $input = $request->all();
        // Obtiene las credenciales de acceso y las decodifica
        $input["email"] = base64_decode(explode(":", $_GET["e"])[0]);
        $input["password"] = base64_decode(explode(":", $_GET["p"])[0]);
        // Elimina las propiedades de credenciales de acceso encriptadas procedentes del sitio de Joomla
        unset($input["e"]);
        unset($input["p"]);
        // Obtiene la información del usuario según el correo electrónico recibido
        $user = Auth::getProvider()->retrieveByCredentials($input);
        // Valida si existe el usuario según el correo obtenido por parámetro desde el sitio de Joomla
        if($user) {
            // Actualiza el password del usuario en la base de datos local (VUV3) del sitio
            User::where("email", $input["email"])->update(["password" => Hash::make($input["password"])]);
            // Loguea el usuario verificado previamente
            Auth::login($user);
            // Se redirige a la función de authenticated para posteriormente redirigirlo a la vista de módulos
            return $this->authenticated($request, $user);
        } else {
            return redirect()->to('login')->withErrors(['email' => 'Estas credenciales no coinciden con nuestros registros.']);
        }
    }

    /**
     * Handle response after user authenticated
     *
     * @param Request $request
     * @param Auth $user
     *
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user)
    {
        // return redirect()->intended();
        // Valida el rol del usuario y redirecciona al módulo correspondiente
        if(Auth::user()->hasRole('Ciudadano')) {
            return redirect(config("app.url")."/modules");
        } else {
            return redirect(config("app.url")."/dashboard");
        }
    }

    /**
     * Redirecciona a la vista principal dependiento del dominio
     *
     * @return URL de la vista principal
     */
    public function redirectTo()
    {
        //
    }

    /**
     * Cierre la sesión del usuario de la aplicación.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Se obtienen los datos del usuario en sesión
        $user = Auth::user();

        // Cierra la sesión en laravel
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        // Si el usuario tiene el valor de username, es un usuario de integración con Joomla y debe cerrar la sesión también allá, además, si la integración con Joomla esta habilitada
        if($user["username"] && config("app.integracion_sitio_joomla")) {
            // Cadena de caracteres permitidos para la encriptación de los parámetros para el envio de la sesión en Joomla
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            // Redirige al sitio de integración Joomla para cerrar la sesión allá
            return redirect(config("app.url_joomla")."/sesion_joomla_repositorio.php?a=".base64_encode("logout").":".substr(str_shuffle($permitted_chars), 0, 4)."&h=".base64_encode(config("app.url")).":".substr(str_shuffle($permitted_chars), 0, 4));
        } else {
            return redirect('/');
        }
    }

    public function logoutOutsideVendor(Request $request){
        // Borra todos los datos de la sesion en linea
        session()->flush();
        return redirect('/login-outside-vendor');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginFormExterna()
    {
        return view('auth.login_outside_vendor');
    }

         /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function loginExternaProvider(Request $request) {

        // dd("Breakpoint");
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts lcfor this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {

            return $this->sendFailedLoginResponse($request);
        }


        $conection = $this->getConectionProvider($request);
        if( gettype($conection)  != "string"){
            // Valida si el proveedor esta en un estado inactivo
            session()->put('correo', $request->input('correo'));
            session()->put('outside', true);
            session()->put('dni', $conection->identification);
            session()->put('name',$conection->name);
            session()->put('id',$conection->id);


            // return redirect("/laboratory_management/laboratory-vew-student-teachers?document=".$request["correo"])->with("is_teacher",true);
            return redirect("/maintenance/request-need-orders?rn=MsQs==");
        }
        $this->incrementLoginAttempts($request);
        return redirect("/login-outside-vendor",302)->with("user_error",$conection);

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        // return $this->sendFailedLoginResponse($request);
    }

         /**
     * genera la consulta par ver si el proveedor si existe
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function getConectionProvider($data) : Providers|string {
        $provider = Providers::select(["id","name","identification"])->where("state","Activo")->where("mail",$data['correo'])->where('identification',$data["pin"])->get()->first();

        if(is_null($provider)){
            return "Lo sentimos pero el correo ".$data['correo'] ." no se encuentra registrado en el sistema como un proveedor.";
        }

        $quantityProvidersContractAssigned = ProviderContract::where("mant_providers_id",$provider["id"])->where("condition","Activo")->count();
        return $quantityProvidersContractAssigned === 0 ? "Lo sentimos ". $provider['name'] ." no se encuentra con contratos en estado Activo." : $provider;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginFormExternaExpedientes()
    {
        return view('auth.login_exteno_expedientes');
    }

    /**
     * Loguin de usuarios externos para visualizar los expedientes.
     *
     * @author Desarrollador Seven - 2025
     * @version 1.0.0
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function loginExternoExpedientes(Request $request) {
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {

            return $this->sendFailedLoginResponse($request);
        }

        $conection = $this->getConection($request);
        // dd($conection);
        if( gettype($conection)  != "string"){
            // Valida si el proveedor esta en un estado inactivo
            session()->put('correo', $request->input('correo'));
            session()->put('pin_acceso', $conection->pin_acceso);
            session()->put('permiso', $conection->permiso);
            session()->put('outside', true);
            session()->put('nombre', $conection->nombre);
            session()->put('id', $conection->id);

            // return redirect("/expedientes-electronicos/usuarios-externos?c=".JwtController::generateToken($conection->correo."|".$conection->pin_acceso));
            return redirect("/expedientes-electronicos/usuarios-externos");
        }
        $this->incrementLoginAttempts($request);
        return redirect("/login-usuarios-externos-expedientes",302)->with("user_error", $conection);
    }

    /**
     * Verifica la consulta para validar las credenciales del usuario externo de expedientes
     *
     * @author Desarrollador Seven - 2025
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function getConection($data) : PermisoUsuariosExpediente|string {
        $usuario_externo = PermisoUsuariosExpediente::select(["id","nombre","correo","pin_acceso","permiso"])->whereNull("deleted_at")->where("correo", $data['correo'])->where('pin_acceso', $data["pin_acceso"])->get()->first();

        if(is_null($usuario_externo)){
            return "Lo sentimos, las credenciales ingresadas no son válidas. Verifique su correo y pin de acceso e intente nuevamente.";
        } else {
            return $usuario_externo;
        }
    }

    public function logoutExternoExpedientes(Request $request) {
        // Borra todos los datos de la sesion en linea
        session()->flush();
        return redirect('/login-usuarios-externos-expedientes');
    }
}
