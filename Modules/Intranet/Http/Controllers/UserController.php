<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\correspondence\RequestExportCorrespondence;
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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Maatwebsite\Excel\Facades\Excel;
use Notification;
use Response;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\JwtController;
use App\Http\Controllers\SendNotificationController;

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
            $verified_accounts = User::whereNotNull("email_verified_at")->where('block', '!=', 1)->whereNotNull('id_cargo')->count();
            $unverified_accounts = User::whereNull("email_verified_at")->whereNotNull('id_cargo')->count();
            $blocked_accounts = User::where("block",1)->whereNull("deleted_at")->whereNotNull('id_cargo')->count();
            $roles = Role::get()->map(function($rol){
                $rol["nombre"] = $rol["name"];
                return $rol;
            });
            return view('intranet::users.index')
                    ->with('roles', $roles)->with("verified_accounts",$verified_accounts)->with("unverified_accounts",$unverified_accounts)->with("blocked_accounts",$blocked_accounts);
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
        // Se encuentra filtrando
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])){
            // Decodifica los campos filtrados
            $filtros = base64_decode($request["f"]);
            if(stripos($filtros, "roles ") !== false){
                // Valida si aparte del filtro de roles viene otro filtro
                if(stripos($filtros," AND ") !== false ){
                    // Obtiene los valores de los campos roles
                    preg_match_all('/roles = \'(\d+)\'/', $filtros,$matches);

                    $filtro = explode(" AND ", $filtros);
                    // Ciclo para eliminar la consulta de roles
                    foreach ($filtro as $key => $parte) {
                        if (stripos($parte,"roles = ") !== false) {
                            unset($filtro[$key]);
                        }
                    }
                    $filtros = implode(' AND ', $filtro);
                }
                else{
                    // Obtiene los valores de los campos roles
                    preg_match_all('/roles = \'(\d+)\'/', $filtros,$matches);

                    $filtro = explode(" or ", $filtros);
                    // Ciclo para eliminar la consulta de roles
                    foreach ($filtro as $key => $parte) {
                        if (stripos($parte,"roles = ") !== false) {
                            unset($filtro[$key]);
                        }
                    }
                    $filtros = implode(' or ', $filtro);
                }
                $users = User::with(['positions', 'dependencies', 'roles', 'workGroups', 'usersHistory','sede'])->whereHas('roles',function ($query) use ($matches){
                    $query->whereIn('id',$matches[1]);
                })->whereRaw(empty($filtros) ? '1=1' : $filtros)->whereNotNull('id_cargo')->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get();
                $quantityUsers = User::whereNotNull('id_cargo')->whereHas('roles',function ($query) use ($matches){
                    $query->whereIn('id',$matches[1]);
                })->whereRaw(empty($filtros) ? '1=1' : $filtros)->count();
            }
            else{
                $users = User::with(['positions', 'dependencies', 'roles', 'workGroups', 'usersHistory','sede'])->whereRaw($filtros)->whereNotNull('id_cargo')->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get();
                $quantityUsers = User::whereNotNull('id_cargo')->whereRaw($filtros)->count();
            }
        }
        else{

            if(isset($request["cp"]) && isset($request["pi"])){
                $users = User::with(['positions', 'dependencies', 'roles', 'workGroups', 'usersHistory','sede'])
                ->whereNotNull('id_cargo')
                ->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))
                ->latest()
                ->get();
            } else {
                $query =$request->input('query');
                $users = User::with(['positions', 'dependencies', 'roles', 'workGroups', 'usersHistory'])->where('name','like','%'.$query.'%')->latest()->get();
            }

            $quantityUsers = User::whereNotNull('id_cargo')->whereNull("deleted_at")->count();
        }

        return $this->sendResponseAvanzado($users->toArray(), trans('data_obtained_successfully'),null,["total_registros" => $quantityUsers]);
    }

      /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Jun. 20 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function allOrderName(Request $request) {
        $users = User::WhereNotNull('id_cargo')->where('block',0)->orderBy("name")->get();
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author German Gonzalez V. - Jan. 01 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function ciudadanos(Request $request) {
        $query =$request->input('query');
        $ciudadanos = User::with(['positions', 'dependencies', 'roles', 'workGroups', 'usersHistory'])->where('name','like','%'.$query.'%')->whereNotNull('id_dependencia')->latest()->get();
        return $this->sendResponse($ciudadanos->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'password' => ['required', 'string',  Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols(),
            'confirmed']]);
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

        // Valida si puede ingresar un nuevo usuario o no
        if(AppBaseController::noUsersAvailable()){
            return $this->sendSuccess("La capacidad de funcionarios ha llegado a su limite, por favor comuniquese con soporte@intraweb.com.co", 'info');
        }

        try {
            // Valida los campos de la solicitud
            $request->validate([
                'password' => [
                    'required',
                    'string',
                    Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols(),
                    'confirmed'
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:users'
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Captura la excepción de validación y retorna un mensaje personalizado
            return $this->sendSuccess("Hay un dato incorrecto por favor verifique: " . $e->getMessage(), 'warning');
        }


        // Inicia la transaccion
        DB::beginTransaction();

        try {
            // Organiza campos booleanos
            $input['block'] = $this->toBoolean($input['block']);
            $input['autorizado_firmar'] = $this->toBoolean($input['autorizado_firmar']);
            $input['sendEmail'] = $this->toBoolean($input['sendEmail']);
            $input['is_assignable_pqr_correspondence'] = $this->toBoolean($input['is_assignable_pqr_correspondence'] ?? 0);
            // Encripta la contrasena
            $input['password'] = Hash::make($input['password']);
            // Tipo de usuario de tipo funcionario
            $input['user_type'] = 'Funcionario';

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

            try{
                // Registro de evento de registro de usuario
                event(new Registered($user));
            }
            catch (\Exception $error) {
                // Envia mensaje de error
                $this->generateSevenLog('userControllerEmail', 'Modules\Intranet\Http\Controllers\UserController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            }

            // Mail::to($user->email)->send(new VerifyMail($user));

            // Obtiene cargo
            $user->positions;
            // Obtiene dependencia
            $user->dependencies;
            // Obtiene grupos de trabajo
            $user->workGroups;
            // Obtiene el historial de cambios del usuario
            $user->usersHistory;
            $user->sede;


        } catch (\Exception $error) {
            dd("Catch de afuera".$error);
            // Devuelve los cambios realizados
            DB::rollback();
            // Envia mensaje de error
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\UserController - '. Auth::user()->name.' -  Error: '.$error->getMessage().' Linea: '.$e->getLine());

            return $this->sendError(trans('msg_error_save'));
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

        $input = $request->except(['created_at', 'updated_at', '_method', 'id']);

        /** @var User $user */
        $oldUser = $this->userRepository->find($id);

        // Valida si existe el usuario
        if (empty($oldUser)) {
            return $this->sendError(trans('not_found_element'), 200);
        }


        try {
            // Valida si se debe actualizar la contrasena
            if (!empty($input['password'])) {
                $request->validate([
                    'password' => ['required', 'string',  Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols(),
                    'confirmed']
                ]);
                // Encripta la contrasena
                $input['password'] = Hash::make($input['password']);
            }

             // Valida si se va a actualizar el correo del usuario, verificando si el correo a actualizar es diferente al actual
            if($oldUser["email"] != $input["email"]) {
                // Valida que el correo del usuario a actualizar, no este ya registrado en la tabla users
                $validaciones = $request->validate([
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Captura la excepción de validación y retorna un mensaje personalizado
            return $this->sendSuccess("Hay un dato incorrecto por favor verifique: " . $e->getMessage(), 'warning');
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
            $input['block'] = isset($input['block']) && ($this->toBoolean($input['block']) || $input['block'] == 1) ? 1 : 0;
            $input['autorizado_firmar'] = isset($input['autorizado_firmar']) && ($this->toBoolean($input['autorizado_firmar']) || $input['autorizado_firmar'] == 1) ? 1 : 0;
            $input['sendEmail'] = isset($input['sendEmail']) && ($this->toBoolean($input['sendEmail']) || $input['sendEmail'] == 1) ? 1 : 0;
            $input['is_assignable_pqr_correspondence'] = isset($input['is_assignable_pqr_correspondence']) && ($this->toBoolean($input['is_assignable_pqr_correspondence']) || $input['is_assignable_pqr_correspondence'] == 1) ? 1 : 0;
            $input['funcionario_encargado'] = isset($input['funcionario_encargado']) && ($this->toBoolean($input['funcionario_encargado']) || $input['funcionario_encargado'] == 1) ? 1 : 0;

            $input['change_user'] = isset($input['change_user'])? $this->toBoolean($input['change_user']) : false;
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

            // Obtiene los roles actuales del usuario antes de la asignación
            $rolesAntes = $user->roles->pluck('name')->toArray();

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
            $user->sede;

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
                    try{
                        // Registro de evento de registro de usuario
                        event(new Registered($user));
                    }
                    catch (\Exception $error) {
                        // Envia mensaje de error
                        $this->generateSevenLog('userControllerEmail', 'Modules\Intranet\Http\Controllers\UserController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                    }
                }

                // Crea un nuevo registro de historial
                $this->userHistoryRepository->create($input);
            }

            // Obtiene el historial de cambios del usuario
            $user->usersHistory;

            // Obtiene los roles actuales después de la asignación
            $rolesDespues = $user->roles->pluck('name')->toArray();

            // Detectar roles asignados
            $rolesAsignados = array_diff($rolesDespues, $rolesAntes);

            // Detectar roles eliminados
            $rolesEliminados = array_diff($rolesAntes, $rolesDespues);

            // Verificar si se asignó "Operador Expedientes Electrónicos" o "Consulta Expedientes Electrónicos"
            if (in_array("Operador Expedientes Electrónicos", $rolesAsignados)) {
                // Llamar función para enviar notificación de asignación de operador
                self::enviar_notificacion_rol($user->toArray(), "Operador Expedientes Electrónicos", "asignado", "crear expedientes y asignar responsables");
            }

            if (in_array("Consulta Expedientes Electrónicos", $rolesAsignados)) {
                // Llamar función para enviar notificación de asignación de consulta
                self::enviar_notificacion_rol($user, "Consulta Expedientes Electrónicos", "asignado", "consultar el expediente y sus documentos asociados");
            }

            // Verificar si se revocó "Operador Expedientes Electrónicos" o "Consulta Expedientes Electrónicos"
            if (in_array("Operador Expedientes Electrónicos", $rolesEliminados)) {
                // Llamar función para enviar notificación de revocación de operador
                self::enviar_notificacion_rol($user, "Operador Expedientes Electrónicos", "revocado");
            }

            if (in_array("Consulta Expedientes Electrónicos", $rolesEliminados)) {
                // Llamar función para enviar notificación de revocación de consulta
                self::enviar_notificacion_rol($user, "Consulta Expedientes Electrónicos", "revocado");
            }

        } catch (\Exception $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\UserController - '. Auth::user()->name.' -  Error: '.$error->getMessage(). '. Linea: '.$error->getLine(). ' Id: '.($oldUser['id'] ?? 'Desconocido'));

            // Envia mensaje de error
            return $this->sendError(trans('msg_error_save'));
            // return $this->sendSuccess(config('constants.support_message'), 'info');

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
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        if(array_key_exists("filtros", $input)) {
            if($input["filtros"] != "") {
                if(stripos($input["filtros"], "roles ") !== false){
                    // Valida si aparte del filtro de roles viene otro filtro
                    if(stripos($input["filtros"]," AND ") !== false ){
                        // Obtiene los valores de los campos roles
                        preg_match_all('/roles = \'(\d+)\'/', $input["filtros"],$matches);

                        $filtro = explode(" AND ", $input["filtros"]);
                        // Ciclo para eliminar la consulta de roles
                        foreach ($filtro as $key => $parte) {
                            if (stripos($parte,"roles = ") !== false) {
                                unset($filtro[$key]);
                            }
                        }
                        $input["filtros"] = implode(' AND ', $filtro);
                    }else{
                        // Obtiene los valores de los campos roles
                        preg_match_all('/roles = \'(\d+)\'/', $input["filtros"],$matches);

                        $filtro = explode(" or ", $input["filtros"]);
                        // Ciclo para eliminar la consulta de roles
                        foreach ($filtro as $key => $parte) {
                            if (stripos($parte,"roles = ") !== false) {
                                unset($filtro[$key]);
                            }
                        }
                        $input["filtros"] = implode(' or ', $filtro);
                    }
                    $input["data"] = User::whereHas('roles',function ($query) use ($matches){
                        $query->whereIn('id',$matches[1]);
                    })->whereRaw(empty($input["filtros"]) ? '1=1' : $input["filtros"])->whereNotNull('id_cargo')->latest()->get()->toArray();
                }else{
                    $input["data"] = User::whereRaw($input["filtros"])->whereNotNull('id_cargo')->latest()->get()->toArray();
                }
                
            }
            else{
                $input["data"] = User::whereNotNull('id_cargo')->latest()->get()->toArray();
            }
        }
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('users').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new RequestExportCorrespondence($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
            // Guarda el archivo excel en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

            // Descarga el archivo generado
            // return Excel::download(new RequestExportCorrespondence($input['data']), $fileName);
            return Excel::download(new RequestExportCorrespondence('intranet::users.report_excel',$input['data'],count($input['data']),'f'), $fileName);
        }
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Carlos Moises Garcia T. - Ene. 19 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getFunctionaries(Request $request) {
        $query =$request->input('query');
        $users = User::role('Intranet')
        ->where('name','like','%'.$query.'%')
        ->latest()
        ->get()
        ->map(function ($user) {
            // Valida si existe una relacion con la dependencias
            if ($user->dependencies) {
                $user->dependency_name = $user->dependencies->nombre;
            } else {
                $user->dependency_name = "No aplica";
            }
            return $user;
        });

        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Carlos Moises Garcia T. - Ene. 19 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getFunctionariesDependencia($request) {
        $users = User::where('id_dependencia',$request)
        ->latest()
        ->get();
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Carlos Moises Garcia T. - Ene. 19 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getCitizens(Request $request) {
        $query = $request->input('query');
        $users = User::role('Ciudadano')
        ->where('name','like','%'.$query.'%')
        ->latest()
        ->get()
        ->map(function ($user) {
            // Valida si existe una relacion con la ciudadano
            if ($user->citizens) {
                $user->document_number = $user->citizens->document_number;
            }else{
                $user->document_number = "1234";
            }
            return $user;
        });

        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Envía una notificación por correo electrónico cuando se le asignan o revocan los roles de operador y/o consulta a un usuario.
     *
     * @param array $datos_destinatario Lista de destinatarios con su información.
     * @param object $rol Nombre del rol asignado o revocado.
     * @param string $tipo Tipo de acción realizada ("asignado" para relacionar el rol, otro valor para revocarlos).
     * @param string $permisos_rol Permisos o acciones otorgados a través del rol relacionado.
     *
     * @return void
     */
    public function enviar_notificacion_rol($datos_destinatario, $rol, $tipo, $permisos_rol = "") {
        // Define el asunto del correo según si los permisos fueron otorgados o revocados
        if ($tipo == "asignado") {
            $asunto = json_decode('{"subject": "[Intraweb] Se le ha asignado un nuevo rol: ' . $rol . '"}');
        } else {
            $asunto = json_decode('{"subject": "[Intraweb] Se le ha revocado el rol: ' . $rol . '"}');
        }

        // Prepara los datos de la notificación
        $notificacion['nombre_rol'] = $rol;
        $notificacion['permisos_rol'] = $permisos_rol;
        $notificacion["nombre_funcionario"] = $datos_destinatario["fullname"];

        try {
            // Envía la notificación según el tipo de permiso (nuevo o revocado)
            SendNotificationController::SendNotification(
                $tipo == "asignado" ?
                'intranet::users.emails.plantilla_notificacion_permiso_rol' :
                'intranet::users.emails.plantilla_notificacion_revocar_permiso_rol',
                $asunto,
                $notificacion,
                $datos_destinatario["email"],
                'Intranet'
            );
        } catch (\Swift_TransportException $e) {
            // Maneja errores de autenticación SMTP y los registra en el log
            $this->generateSevenLog(
                'Intranet',
                'Modules\Intranet\Http\Controllers\UserController - '.
                (Auth::user()->fullname ?? 'Usuario Desconocido') .
                ' - Error: ' . $e->getMessage() . '. Línea: ' . $e->getLine()
            );
        } catch (\Exception $e) {
            // Captura cualquier otro error y lo registra en el log
            $this->generateSevenLog(
                'Intranet',
                'Modules\Intranet\Http\Controllers\UserController - '.
                (Auth::user()->fullname ?? 'Usuario Desconocido') .
                ' - Error: ' . $e->getMessage() . '. Línea: ' . $e->getLine()
            );
        }
    }

}
