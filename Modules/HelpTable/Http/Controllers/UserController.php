<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use App\Http\Controllers\AppBaseController;
use Modules\Intranet\Http\Controllers\UtilController;
use App\Mail\VerifyMail;
use Modules\Intranet\Http\Requests\CreateUserRequest;
use Modules\Intranet\Http\Requests\UpdateUserRequest;
use Modules\Intranet\Repositories\UserRepository;
use Modules\Intranet\Repositories\UserHistoryRepository;
use App\Notifications\UserEmailNotification;
use App\Exports\RequestExport;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Notification;
use Response;
use Spatie\Permission\Models\Role;

/**
 * Clase de funcionarios
 *
 * @author Jhoan Sebastian Chilito S. - Jun. 20 - 2020
 * @version 1.0.0
 */
class UserController extends AppBaseController {

    /** @var  UserRepository */
    private $userRepository;
    /** @var  UserHistoryRepository */
    private $userHistoryRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Jul. 27 - 2020
     * @version 1.0.0
     */
    public function __construct(
        UserHistoryRepository $userHistoryRepo,
        UserRepository $userRepo
    ) {
        $this->userHistoryRepository = $userHistoryRepo;
        $this->userRepository = $userRepo;
    }

    /**
     * Muestra la vista para el CRUD de User.
     *
     * @author Jhoan Sebastian Chilito S. - Jun. 20 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador intranet"])){
            $roles = Role::all();
            return view('help_table::users.index')
                    ->with('roles', $roles);
        }
        return view("auth.forbidden");
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Jun. 20 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        $query =$request->input('query');
        $users = User::role('Soporte TIC')->with(['positions', 'dependencies', 'roles', 'workGroups', 'usersHistory'])->where('name','like','%'.$query.'%')->latest()->get();
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Ago. 18 - 2020
     * @version 1.0.0
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request) {

        $input = $request->all();

        // dd($input);
        // Inicia la transaccion
        DB::beginTransaction();

        try {
            // Valida si el usuario ya existe
            if (!empty($input['user_id'])) {
                // Busca el usuario seleccionado por el usuario
                $user = $this->userRepository->find($input['user_id']);

                $user->assignRole('Soporte TIC');

            } else {
                // Organiza campos booleanos
                $input['block'] = $this->toBoolean($input['block']);
                $input['sendEmail'] = $this->toBoolean($input['sendEmail']);
                // Encripta la contrasena
                $input['password'] = Hash::make($input['password']);

                // Valida si se ingresa la imagen de perfil
                if ($request->hasFile('url_img_profile')) {
                    $input['url_img_profile'] = substr($input['url_img_profile']->store('public/users/avatar'), 7);
                } else {
                    // Establece imagen de perfil por defecto
                    $input['url_img_profile'] = 'users/avatar/default.png';
                }
                // Valida si se ingresa la firma digital
                if ($request->hasFile('url_digital_signature')) {
                    $input['url_digital_signature'] = substr($input['url_digital_signature']->store('public/users/signature'), 7);
                }
                // Agrega el id del usuario que crea el registro
                $input['cf_user_id'] = Auth::id();
                // Crea un nuevo usuario
                $user = $this->userRepository->create($input);

                // Valida si viene grupos de trabajo para asignar
                if (!empty($input['work_groups'])) {
                    // Inserta relacion con grupos de trabajo
                    $user->workGroups()->sync($input['work_groups']);
                }
                $user->assignRole('Soporte TIC');
                
                // Registro de evento de registro de usuario
                event(new Registered($user));

                // Mail::to($user->email)->send(new VerifyMail($user));

                // Obtiene cargo
                $user->positions;
                // Obtiene dependencia
                $user->dependencies;
                // Obtiene grupos de trabajo
                $user->workGroups;
                // Obtiene el historial de cambios del usuario
                $user->usersHistory;
            }

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Envia mensaje de error
            return $this->sendSuccess("Lo sentimos, hubo un problema al procesar su solicitud. Por favor, verifique que la información sea correcta. En caso de persistir el inconveniente, no dude en contactarnos a través de " . env("MAIL_SUPPORT") ?? 'soporte@seven.com.co. ' . trans($error->errorInfo[2]), 'error');
        }  catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            return $this->sendSuccess("Lo sentimos, hubo un problema al procesar su solicitud. Por favor, verifique que la información sea correcta. En caso de persistir el inconveniente, no dude en contactarnos a través de " . env("MAIL_SUPPORT") ?? 'soporte@seven.com.co. '.$e->getMessage(), 'error');
        }
        // Efectua los cambios realizados
        DB::commit();

        return $this->sendResponse($user->toArray(), trans('msg_success_save'));

    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Ago. 25 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request) {

        $input = $request->all();

        /** @var User $user */
        $oldUser = $this->userRepository->find($id);

        // Valida si existe el usuario
        if (empty($oldUser)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();

        try {
            /** Obtiene relaciones de usuario antiguo */
            // Obtiene cargo
            $oldUser->positions;
            // Obtiene dependencia
            $oldUser->dependencies;

            // Organiza campos booleanos
            $input['block']       = $this->toBoolean($input['block']);
            $input['sendEmail']   = $this->toBoolean($input['sendEmail']);

            // Valida si se debe actualizar la contrasena
            if (!empty($input['password'])) {
                $request->validate([
                    'password' => 'required|confirmed|min:6'
                ]);
                // Encripta la contrasena
                $input['password'] = Hash::make($input['password']);
            }
            // Valida si se ingresa la imagen de perfil
            if ($request->hasFile('url_img_profile')) {
                $input['url_img_profile'] = substr($input['url_img_profile']->store('public/users/avatar'), 7);
            }

            // Actualiza datos de usuario
            $user = $this->userRepository->update($input, $id);

            /** Obtiene relaciones de usuario actualizado */
            // Obtiene cargo
            $user->positions;
            // Obtiene dependencia
            $user->dependencies;

            // Prepara los datos de usuario para comparar
            $arrDataUser = Arr::dot(UtilController::dropNullEmptyList($user->replicate()->toArray(), true)); // Datos actuales de usuario
            $arrDataOldUser = Arr::dot(UtilController::dropNullEmptyList($oldUser->replicate()->toArray(), true)); // Datos antiguos de usuario

            // // Datos diferenciados
            $arrDiff = array_diff_assoc($arrDataUser, $arrDataOldUser);

            // Valida si los datos antiguos son diferentes a los actuales
            if ( count($arrDiff) > 0) {
                // Lista diferencial sin la notacion punto
                $arrDiffUndot = UtilController::arrayUndot($arrDiff);
                // Asigna el id del usuario para el registro del historial
                $input['users_id'] = $user->id;
                // Agrega el id del usuario que crea el registro
                $input['cf_user_id'] = Auth::id();
                // Convierte en string el array de roles
                $input['roles'] = json_encode($input['roles']);
                // // Crea un nuevo registro de historial
                $this->userHistoryRepository->create($input);
            }

            // Obtiene el historial de cambios del usuario
            $user->usersHistory;

        }  catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Envia mensaje de error
            return $this->sendSuccess("Lo sentimos, hubo un problema al procesar su solicitud. Por favor, verifique que la información sea correcta. En caso de persistir el inconveniente, no dude en contactarnos a través de " . env("MAIL_SUPPORT") ?? 'soporte@seven.com.co. ' . trans($error->errorInfo[2]), 'error');
        }  catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            return $this->sendSuccess("Lo sentimos, hubo un problema al procesar su solicitud. Por favor, verifique que la información sea correcta. En caso de persistir el inconveniente, no dude en contactarnos a través de " . env("MAIL_SUPPORT") ?? 'soporte@seven.com.co. ' .$e->getMessage(), 'error');
        }  
        // Efectua los cambios realizados
        DB::commit();

        return $this->sendResponse($user->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un User del almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Jun. 20 - 2020
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $user->delete();

        return $this->sendSuccess(trans('msg_success_drop'));

    }

    // /**
    //  * Organiza la exportacion de datos
    //  *
    //  * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
    //  * @version 1.0.0
    //  *
    //  * @param Request $request datos recibidos
    //  */
    // public function export(Request $request) {
    //     $input = $request->all();
    //     dd($input);
    //     // Tipo de archivo (extencion)
    //     $fileType = $input['fileType'];
    //     // Nombre de archivo con tiempo de creacion
    //     $fileName = time().'-'.trans('ht_tic_type_assets').'.'.$fileType;

    //     // Valida si el tipo de archivo es pdf
    //     if (strcmp($fileType, 'pdf') == 0) {
    //         // Guarda el archivo pdf en ubicacion temporal
    //         // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

    //         // Descarga el archivo generado
    //         return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
    //     } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
    //         // Guarda el archivo excel en ubicacion temporal
    //         // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

    //         // Descarga el archivo generado
    //         return Excel::download(new GenericExport($input['data']), $fileName);
    //     }

    // }

    /**
     * Genera el reporte de tecnicos en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - Oct. 22 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('tic_assets').'.'.$fileType;
        
        return Excel::download(new RequestExport('help_table::users.report_excel', $input['data'], 'i'), $fileName);
    }
}
