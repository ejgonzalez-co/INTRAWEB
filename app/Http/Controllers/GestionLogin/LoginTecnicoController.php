<?php

namespace App\Http\Controllers\GestionLogin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Redirect;

class LoginTecnicoController extends Controller
{
    //Función la cual permite validar y crear la sesión
    public function index()
    {
        return view("tramite.Ivc.tecnicos.authTecnicos.login");
    }

    //Función la cual permite validar y crear la sesión
    public function logueo(Request $request)
    {

        //Obtiene el token encriptado que viene por la url
        //$pass  = str_replace(" ", "+", $request['p']);

        //Obtiene el username encriptado que viene por la url

        //dd($request['username']);
        $name = str_replace(" ", "+", $request['username']);

        //Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
        $key = 'logueo_jooomla';  

        //Código para quitar el base64 de la clave
        $encryption_key = base64_decode($key);

        $username = $request['username'];

        try {
            //Código que desencripta el password que viene por la url
            /*list($encrypted_data, $iv) = explode('::', base64_decode($pass), 2);
            $password =  openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);*/

            //Código que desencripta el username que viene por la url
            /*
            list($encrypted_data_name, $iv) = explode('::', base64_decode($name), 2);
            $username =  openssl_decrypt($encrypted_data_name, 'aes-256-cbc', $encryption_key, 0, $iv);

            $username = $request['username'];
            */
            //Condición que valida que el token no venga vacio y el username
            if (/*$password != "" && $username != "" && $password == $username*/$username != "") {

                //Valida si las credenciales existan en la base de datos
                if(Auth::attempt(['username'=>$username, 'password'=>$username])){

                    //Crea la sesión
                    session_start();

                    //Redirección al panel del técnico
                    return Redirect::to('https://www.app.ventanillaunicavirtualquindio.gov.co/panel-tecnico-ivc');
                }else{
                    //Redirección cuando las credenciales no existen
                    return Redirect::to('https://www.app.ventanillaunicavirtualquindio.gov.co/login-tecnico');
                }
            }else{
                //Redirección cuando las credenciales son erroneas
                return Redirect::to('https://www.app.ventanillaunicavirtualquindio.gov.co/login-tecnico');
            }
        }
        catch (\Throwable $th) {
           //Redirección cuando las credenciales son erroneas
           return Redirect::to('https://www.app.ventanillaunicavirtualquindio.gov.co/login-tecnico');
        }
    }

    //Función que permite cerrar sesión
    public function logout(){
        Auth::logout();
        //Redirección al panel del ciudadano
        return Redirect::to('https://www.app.ventanillaunicavirtualquindio.gov.co/index.php?option=com_formasonline&formasonlineform=cerrar_sesion');
    }
}
