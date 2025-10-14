<?php
namespace App\Http\Controllers\GestionLogin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Redirect;
use DB;

class LoginController extends Controller {

    //Función la cual permite validar y crear la sesión
    public function index() {
    }

    //Función la cual permite validar y crear la sesión
    public function logueo1(Request $request) {
        //Obtiene el token encriptado que viene por la url

        //$pass  = str_replace(" ", "+", $request['p']);

        //Obtiene el username encriptado que viene por la url
        $name = str_replace(" ", "+", $request['u']);

        //Obtiene el valor si es un ciudadano
        $user = $request['t'];

        //Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
        $key = 'logueo_jooomla';  

        //Código para quitar el base64 de la clave
        $encryption_key = base64_decode($key);

        try {
            //Código que desencripta el password que viene por la url
            /*list($encrypted_data, $iv) = explode('::', base64_decode($pass), 2);
            $password =  openssl_decrypt($encrypted_data, 'AES-128-CBC', $encryption_key, 0, $iv);*/

            //Código que desencripta el username que viene por la url

            list($encrypted_data_name, $iv) = explode('::', base64_decode($name), 2);
            $username =  openssl_decrypt($encrypted_data_name, 'AES-128-CBC', $encryption_key, 0, $iv);

            //Condición que valida que el token no venga vacio y el username
            if (/*$password != "" && $username != "" && $password == $username*/$username != "") {

                //Valida si las credenciales existan en la base de datos
                if(Auth::attempt(['username'=>$username, 'password'=>$username])) {
                    //Crea la sesión
                    session_start();
                    //Condición que valida si el usuario es un ciudadano
                    if ($user == 1) {
                        //Redirección al panel del ciudadano
                        return Redirect::to('https://ventanillaunicavirtualquindio.gov.co/index.php?option=com_formasonline&amp;formasonlineform=FormaCiudadanoInfo&event=login');
                    } else { //Condición cuando el usuario es un funcionario
                        //Redirección al perfil del funcionario
                        return Redirect::to('https://ventanillaunicavirtualquindio.gov.co/index.php?option=com_intranet&view=perfil');

                    }
                } else{
                    //Redirección cuando las credenciales no existen
                    return Redirect::to('https://ventanillaunicavirtualquindio.gov.co/index.php?option=com_formasonline&formasonlineform=cerrar_sesion');
                }
            } else {

                //Redirección cuando las credenciales son erroneas
                return Redirect::to('https://ventanillaunicavirtualquindio.gov.co/index.php?option=com_formasonline&formasonlineform=cerrar_sesion');
            }
        }
        catch (\Throwable $th) {
           //Redirección cuando las credenciales son erroneas
           return Redirect::to('https://ventanillaunicavirtualquindio.gov.co/index.php?option=com_formasonline&formasonlineform=cerrar_sesion');
        }
    }

    //Función la cual permite validar y crear la sesión
    public function logueo(Request $request) {
        // Obtiene el email del usuario que viene por la url
        $email = str_replace(" ", "+", $request['e']);
        // Obtiene la url que viene por la url
        $url = base64_decode(str_replace(" ", "+", $request['u']));
        //Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
        $key = 'logueo_jooomla';  
        // Quita el base64 de la clave
        $encryption_key = base64_decode($key);
        
        // Desencripta el email que viene por la url
        list($encrypted_data_email, $iv) = explode('::', base64_decode($email), 2);
        $email =  openssl_decrypt($encrypted_data_email, 'AES-128-CBC', $encryption_key, 0, $iv);

        // Valida que el email no venga vacio
        if ($email) {
           // Obtiene los datos del usuario
            $user = DB::table('ventanilla_users')->where('email',  $email)->first();

            //Valida si las credenciales existan en la base de datos
            if(Auth::attempt(['username'=>$user->username, 'password'=>$user->username])) {
                //Crea la sesión
                session_start();
                //Redirección
                return Redirect::to($url);
            }
        }
    }

    //Función que permite cerrar sesión
    public function logout1() {   

        Auth::logout();
        // Obtiene la url que viene por la url
        $url = config('app.url_joomla_vuv2').'/cerrar-session';
        // Redireccion a FormasOnline para cerrar la sesion
        return Redirect::to($url);
        
        //Redirección al panel del ciudadano
        // return Redirect::to('https://ventanillaunicavirtualquindio.gov.co/index.php?option=com_formasonline&formasonlineform=cerrar_sesion');
    }

    //Función que permite cerrar sesión
    public function logout(Request $request) {

        Auth::logout();
        // Obtiene la url que viene por la url
        $url = base64_decode(str_replace(" ", "+", $request['u']));
        if (empty($url)) {
            $url = config('app.url_joomla').'/index.php?option=com_formasonline&formasonlineform=cerrar_sesion';
        }
        // Redireccion a FormasOnline para cerrar la sesion
        return Redirect::to($url);
    }
}
