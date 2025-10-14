<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Response;
use DB;
use Auth;
use Redirect;
use App\User;
use Illuminate\Support\Facades\Cookie;

class LoginJoomlaController extends Controller
{

   /**
     * Crea la sesion de vuv2 y redirije a vuv1 para crear la sesion alli
     *
     * @author Carlos Moises Garcia T. - Marz. 11 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function loguearse(Request $request) {

      // Obtiene el id del usuario que viene por la url
      $user_id = str_replace(" ", "+", $request['u']);

      // Obtiene el password del usuario que viene por la url
      $password = str_replace(" ", "+", $request['p']);

      //Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
      $key = 'logueo_jooomla';  

      // Quita el base64 de la clave
      $encryption_key = base64_decode($key);

      // Desencripta el username que viene por la url
      list($encrypted_data_name, $iv) = explode('::', base64_decode($user_id), 2);
      $user_id =  openssl_decrypt($encrypted_data_name, 'AES-128-CBC', $encryption_key, 0, $iv);

      // Desencripta el username que viene por la url
      list($encrypted_data, $iv) = explode('::', base64_decode($password), 2);
      $password =  openssl_decrypt($encrypted_data, 'AES-128-CBC', $encryption_key, 0, $iv);

      // Validad que el id y el password no esten vacios
      if ($user_id != "" && $password) {

         // Obtiene los datos del usuario en vuv2
         $user = DB::table('users')->where('user_joomla_id',  $user_id)->first();
         // Obtiene los datos del usuario en joomla
         $user_joomla = DB::connection('joomla')
         ->table('users')
         ->join('intranet_usuario', 'intranet_usuario.userid', '=', 'users.id')
         ->select('users.*', 'intranet_usuario.id_cargo', 'intranet_usuario.dependencia', 'intranet_usuario.cargo', 'intranet_usuario.id_dependencia')
         ->where('users.id', $user_id)->first();

         // Valida si el usuario esta vacio
         if (empty($user_joomla->id)) {
            // Obtiene los datos del usuario ciudadano en joomla
            $user_joomla = DB::connection('joomla')
            ->table('users')
            ->join('ciudadano', 'ciudadano.username', '=', 'users.username')
            ->select('users.*')
            ->where('users.id', $user_id)->first();
         }

         // Valida que el cargo no este vacio
         if (!empty($user_joomla->cargo)) {
            // Obtiene los datos del cargo
            $charges = DB::table('cargos')->where('nombre', '=',  $user_joomla->cargo)->first();
         }

         // Valida si existe el usuario en vuv2
         if ($user) {
            // Obtiene todos los grupos de joomla relacionados al usuario
            $groups_user = DB::connection('joomla')
            ->table('user_usergroup_map')
            ->join('usergroups', 'usergroups.id', '=', 'user_usergroup_map.group_id')
            //->select('usergroups.title')
            ->where('user_usergroup_map.user_id', $user_id)
            ->pluck('usergroups.title');


            // valida si tiene cargo para determinar si es un funcionario
            if (!empty($user_joomla->cargo)) {
               // Valida si hay cambios el los datos del usuario
               if ($user->name != $user_joomla->name ||
               $user->email != $user_joomla->email ||
               $user->id_cargo != $charges->id ||
               $user->password != Hash::make($password)) {
                  // Obtiene el registro del usuario
                  $user = User::find($user->id);
                  // Actualiza el registro del usuario
                  $user->update([
                     'id_cargo' => $charges->id,
                     'name' => $user_joomla->name,
                     'email' => $user_joomla->email,
                     'password' => Hash::make($password),
                  ]);
               }
            } else {
               // Valida si hay cambios el los datos del usuario
               if ($user->name != $user_joomla->name ||
               $user->email != $user_joomla->email ||
               $user->password != Hash::make($password)) {
                  // Obtiene el registro del usuario
                  $user = User::find($user->id);
                  // Actualiza el registro del usuario
                  $user->update([
                     'name' => $user_joomla->name,
                     'email' => $user_joomla->email,
                     'password' => Hash::make($password),
                  ]);
               }
            }

            // Asigna los roles seleccionados
            $user->syncRoles($groups_user->all());
         } else {
            // Obtiene todos los grupos de joomla relacionados al usuario
            $groups_user = DB::connection('joomla')
            ->table('user_usergroup_map')
            ->join('usergroups', 'usergroups.id', '=', 'user_usergroup_map.group_id')
            //->select('usergroups.title')
            ->where('user_usergroup_map.user_id', $user_id)
            ->pluck('usergroups.title');

            // Valida que la dependencia no este vacia
            if (!empty($user_joomla->dependencia)) {
               // Obtiene los datos de la dependencia
               $dependency = DB::table('dependencias')->where('nombre', '=',  $user_joomla->dependencia)->first();
            }

            // Inserta el registro del usuario
            $user = User::create([
               'id_cargo' => empty($charges->id)? null : $charges->id,
               'id_dependencia' => empty($dependency->id)? null : $dependency->id,
               'name' => $user_joomla->name,
               'email' => $user_joomla->email,
               'password' => Hash::make($password),
               'username' => $user_joomla->username,
               'block' => 0,
               'sendEmail' => $user_joomla->sendEmail,
               'email_verified_at' => date('Y-m-d H:i:s'),
               'user_joomla_id' => $user_joomla->id,
            ]);
            // Registro de evento de registro de usuario
            // event(new Registered($user));
            // Asigna los roles seleccionados
            $user->syncRoles($groups_user->all());
         }

         // Validad que se cree la sesion
         if (Auth::attempt(['email' => $user->email, 'password' => $password])) {
            
            // Validad si la peticion viene de formasonline
            if (!empty($request['f'])) {

               
               // Obtiene la ruta de la formaonline que viene por la url
               $url = str_replace(" ", "+", $request['f']);
               
               //Código que desencripta el username que viene por la url
               list($encrypted, $iv) = explode('::', base64_decode($url), 2);
               $url =  openssl_decrypt($encrypted, 'AES-128-CBC', $encryption_key, 0, $iv);

               // Redireccion a FormasOnline
               return Redirect::to(config('app.url_joomla').'/'.$url);
            } else{
               // Redireccion que sesion creada
               return Redirect::to(config('app.url_joomla').'/index.php?option=com_intranet&view=perfil');
            }
         } else {
            // Redireccion cuando tiene usuario en joomla pero no en laravel
            return Redirect::to(config('app.url_joomla').'/index.php?option=com_intranet&view=perfil');
         }
      }else {
         // Redireccion cuando las credenciales no existen
         return Redirect::to(config('app.url_joomla').'/index.php?option=com_formasonline&formasonlineform=cerrar_sesion');
      }
   }

   /**
     * Cierra la sesion de vuv2 y redirije a vuv1 para cerrar la sesion alli
     *
     * @author Carlos Moises Garcia T. - Marz. 11 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Redirect
     */
   public function logout(Request $request){
      // Cierra la sesion existente
      Auth::logout();
      
      // Redireccion a FormasOnline para cerrar la sesion
      return Redirect::to(config('app.url_joomla').'/index.php?option=com_formasonline&formasonlineform=cerrar_sesion');
   }
}
