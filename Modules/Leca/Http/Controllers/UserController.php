<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\GenericExport;
use App\Http\Controllers\AppBaseController;
use App\Mail\VerifyMail;
use Modules\Intranet\Http\Requests\CreateUserRequest;
use Modules\Intranet\Http\Requests\UpdateUserRequest;
use Modules\Intranet\Repositories\UserRepository;
use Modules\Intranet\Repositories\UserHistoryRepository;
use App\Notifications\UserEmailNotification;
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
        $roles = Role::all();
        return view('leca::users.index')
                ->with('roles', $roles);
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
        // $query =$request->input('query');
        // $users = User::with(['positions', 'dependencies', 'roles', 'workGroups', 'usersHistory'])->where('name','like','%'.$query.'%')->latest()->get();
        // return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
        // $users=User::role(['Analista','Recepcionista','Personal de Apoyo','Toma de Muestra'])->get();
        $users=User::role(['Analista microbiológico','Analista fisicoquímico','Recepcionista','Personal de Apoyo','Toma de Muestra'])->with(['positions', 'dependencies', 'roles', 'usersHistory'])->latest()->get();
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

        // Inicia la transaccion
        // DB::beginTransaction();
        $hoy = date("Y-m-d H:i:s");    
        // try {
            // Organiza campos booleanos
            $input['block'] = $this->toBoolean($input['block']);
            $input['sendEmail'] = $this->toBoolean($input['sendEmail']);
            // Encripta la contrasena
            $input['password'] = Hash::make($input['password']);
            $input['email_verified_at'] =$hoy;

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
            // Valida si vienen roles para asignar
            if (!empty($input['roles'])) {
                // Asigna los roles seleccionados
                $user->syncRoles($input['roles']);
            }
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

        // } catch (\Exception $e) {
        //     // Devuelve los cambios realizados
        //     DB::rollback();
        //     // Envia mensaje de error
        //     return $this->sendError(trans('msg_error_save'));
        // }

        // // Efectua los cambios realizados
        // DB:commit();

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

        $input = $request->except(['created_at', 'updated_at', '_method', 'id']);

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
            // Obtiene grupos de trabajo
            $oldUser->workGroups;
            // Obtiene roles
            $oldUser->roles;

            // Organiza campos booleanos
            $input['block'] = $this->toBoolean($input['block']);
            $input['sendEmail'] = $this->toBoolean($input['sendEmail']);
            $input['change_user'] = isset($input['change_user'])? $this->toBoolean($input['change_user']) : false;

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
            // Valida si se ingresa la firma digital
            if ($request->hasFile('url_digital_signature')) {
                $input['url_digital_signature'] = substr($input['url_digital_signature']->store('public/users/signature'), 7);
            }

            // Valida si es un cambio de usuario
            if ($input['change_user'] == true) {
                $input['email_verified_at'] = null;
            }

            // Actualiza datos de usuario
            $user = $this->userRepository->update($input, $id);

            // Valida si viene grupos de trabajo para actualizar
            if (empty($input['work_groups'])) {
                $user->workGroups()->detach();
            } else {
                // Inserta relacion con grupos de trabajo
                $user->workGroups()->sync($input['work_groups']);
            }
            // Valida si vienen roles para actualizar
            if (empty($input['roles'])) {
                $user->syncRoles([]);
            } else {
                // Asigna los roles seleccionados
                $user->syncRoles($input['roles']);
            }

            /** Obtiene relaciones de usuario actualizado */
            // Obtiene cargo
            $user->positions;
            // Obtiene dependencia
            $user->dependencies;
            // Obtiene grupos de trabajo
            $user->workGroups;
            // Obtiene roles
            $user->roles;

            // Prepara los datos de usuario para comparar
            $arrDataUser = Arr::dot(UtilController::dropNullEmptyList($user->replicate()->toArray(), true, 'roles', 'work_groups')); // Datos actuales de usuario
            $arrDataOldUser = Arr::dot(UtilController::dropNullEmptyList($oldUser->replicate()->toArray(), true, 'roles', 'work_groups')); // Datos antiguos de usuario

            // Datos diferenciados
            $arrDiff = array_diff_assoc($arrDataUser, $arrDataOldUser);

            // Valida si los datos antiguos son diferentes a los actuales
            if ( count($arrDiff) > 0) {
                // Lista diferencial sin la notacion punto
                $arrDiffUndot = UtilController::arrayUndot($arrDiff);
                // Asigna el id del usuario para el registro del historial
                $input['users_id'] = $user->id;
                // Agrega el id del usuario que crea el registro
                $input['cf_user_id'] = Auth::id();
                // Agrega roles nuevos
                $input['roles'] = array_key_exists('roles', $arrDiffUndot) ?
                    json_encode($user->roles->toArray()) : json_encode($oldUser->roles->toArray());
                // Agrega grupos de trabajo asignado
                $input['work_groups'] = array_key_exists('work_groups', $arrDiffUndot) ?
                    json_encode($user->workGroups->toArray()) : json_encode($oldUser->workGroups->toArray());

                // Valida si es un cambio de usuario
                if ($input['change_user'] == true) {
                    // Envia correo de verificacion de usuario nuevo
                    event(new Registered($user)); // Registro de evento de registro de usuario
                }

                // Crea un nuevo registro de historial
                $this->userHistoryRepository->create($input);
            }

            // Obtiene el historial de cambios del usuario
            $user->usersHistory;

        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\UserController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
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

    /**
     * Organiza la exportacion de datos
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('users').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
            // Guarda el archivo excel en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName);
        }

    }
}
