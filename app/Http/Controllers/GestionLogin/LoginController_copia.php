<?php

namespace App\Http\Controllers\GestionLogin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Redirect;

class LoginController extends Controller
{
    //Función la cual permite validar y crear la sesión
    public function index()
    {

    }

    //Función la cual permite validar y crear la sesión
    public function logueo(Request $request)
    {

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
            $password =  openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);*/

            //Código que desencripta el username que viene por la url
            list($encrypted_data_name, $iv) = explode('::', base64_decode($name), 2);
            $username =  openssl_decrypt($encrypted_data_name, 'aes-256-cbc', $encryption_key, 0, $iv);
            
            //Condición que valida que el token no venga vacio y el username
            if (/*$password != "" && $username != "" && $password == $username*/$username != "") {

                //Valida si las credenciales existan en la base de datos
                if(Auth::attempt(['username'=>$username, 'password'=>$username])){

                    //Crea la sesión
                    session_start();

                    //Condición que valida si el usuario es un ciudadano
                    if ($user == 1) {

                        //Redirección al panel del ciudadano
                        
                        return Redirect::to('https://www.ventanillaunicavirtualquindio.gov.co/index.php?option=com_formasonline&amp;formasonlineform=PANELCIUDADANO&amp;event=load');
                        // return Redirect::to('https://ventanillaunicavirtualquindio.gov.co/index.php?option=com_formasonline&amp;formasonlineform=FormaCiudadanoInfo&event=login');
                    }
                    //Condición cuando el usuario es un funcionario
                    else{
                        //dd($user);
                        //Redirección al perfil del funcionario
                                             
                        return Redirect::to('https://www.ventanillaunicavirtualquindio.gov.co/index.php?option=com_intranet&view=perfil');
                    }
                }else{
                    //Redirección cuando las credenciales no existen
                    return Redirect::to('https://www.ventanillaunicavirtualquindio.gov.co/index.php?option=com_formasonline&formasonlineform=cerrar_sesion');
                }
            }else{
                //Redirección cuando las credenciales son erroneas
                return Redirect::to('https://www.ventanillaunicavirtualquindio.gov.co/index.php?option=com_formasonline&formasonlineform=cerrar_sesion');
            }
        }
        catch (\Throwable $th) {
           //Redirección cuando las credenciales son erroneas
           return Redirect::to('https://www.ventanillaunicavirtualquindio.gov.co/index.php?option=com_formasonline&formasonlineform=cerrar_sesion');
        }
    }

    //Función que permite cerrar sesión
    public function logout(){
        Auth::logout();
        
        //Redirección al panel del ciudadano
        return Redirect::to('https://www.ventanillaunicavirtualquindio.gov.co/index.php?option=com_formasonline&formasonlineform=cerrar_sesion');
    }
}
