<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\ImprovementPlans\RequestExport;
use Modules\ImprovementPlans\Http\Requests\CreateUserRequest;
use Modules\ImprovementPlans\Http\Requests\UpdateUserRequest;
use App\User;
use Modules\ImprovementPlans\Repositories\UserRepository;
use Modules\ImprovementPlans\Models\ContentManagement;
use Modules\ImprovementPlans\Models\UserOtherDependence;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\ImprovementPlans\Http\Controllers\UtilController;
use Modules\ImprovementPlans\Models\Rol;
use Modules\ImprovementPlans\Models\RolPermission;
use Illuminate\Support\Facades\Crypt;

/**
 * Descripcion de la clase
 *
 * @author Kleverman Salazar Florez. - Ago 30 - 2023
 * @version 1.0.0
 */
class UserController extends AppBaseController {

    /** @var  UserRepository */
    private $userRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     */
    public function __construct(UserRepository $userRepo) {
        $this->userRepository = $userRepo;
    }

    /**
     * Muestra la vista para el CRUD de User.
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        // Obtiene los roles permitidos
        $allowedRoles = $this->getAllowedRoles();

        // Obtiene la instancia del usuario en sesion
        $userLogged = Auth::user();
    
        // Valida si el usuario tiene los permisos de editar y generar reportes
        $canManage = $canGenerateReports = false;
        if ($userLogged->hasRole('Registered')) {
            $canManage = true;
            $canGenerateReports = true;
        } elseif ($userLogged->hasRole($allowedRoles)) {
            $canManage = $canGenerateReports = $this->hasPermissions($userLogged);
        }
        else{
            return abort(403,"No se encuentra autorizado.");
        }
    
        return view('improvementplans::users.index')->with("can_manage",$canManage)->with("can_generate_reports",$canGenerateReports);
    }

            /**
    * Obtiene el valor del campo de gestion de contenido.
    *
    * @author Kleverman Salazar Florez. - Ago. 06 - 2023
    * @version 1.0.0
    *
    * @param string $name
    * @return string
    */
    public function getContentManagementSetting(string $name): string
    {
        $setting = ContentManagement::where('name', $name)->first();

        if ($setting) {
            return $setting->color;
        }
        return null;
    }

    /**
     * Obtiene los roles que tienen acceso al modulo.
     * 
     * @author Kleverman Salazar Florez. - Ago. 06 - 2023
     * @version 1.0.0
     *
     * @return array
     */
    public function getAllowedRoles(): array
    {
        $allowedRoles = Rol::whereIn('id', RolPermission::where('module', 'Administración')->groupBy('role_id')->pluck('role_id'))->pluck('name')->toArray();
        return $allowedRoles;
    }

    /**
     * Valida si el usuario tiene permisos para generar reportes y gestionar.
     * 
     * @author Kleverman Salazar Florez. - Ago. 06 - 2023
     * @version 1.0.0
     * 
     * @param $user
     * @return bool
     */
    public function hasPermissions($user): bool
    {
        if ($user->hasRole('Registered')) {
            return true;
        }

        $allowedRoles = $this->getAllowedRoles();
        if (!$user->hasRole($allowedRoles)) {
            return false;
        }

        $rolePermissions = RolPermission::where('role_id', Rol::where('name', $allowedRoles[0])->first()->id)
            ->where('module', 'Administración')->first();

        return $rolePermissions->can_manage || $rolePermissions->can_generate_reports;
    }
    
    /**
     * Obtiene todos los elementos existentes
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $users = User::with(['positions', 'dependencies', 'roles'])->latest()->get();
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getUserOrderName() {
        $users = User::orderBy("name")->get();
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los cargos existentes
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getCharges(){
        $charges = DB::table('cargos')
        ->select(['id','nombre'])
        ->orderBy("nombre")
        ->get();
        return $this->sendResponse($charges->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todas las dependencias existentes
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getDependences(){
        $dependences = DB::table('dependencias')
        ->select(['id','nombre'])
        ->orderBy("nombre")
        ->get();
       
        return $this->sendResponse($dependences->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene las dependencias existentes filtradas por el nombre
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getDependencesByName(Request $request){
        $dependences = DB::table('dependencias')
        ->select(['id','nombre'])
        ->where("nombre","like","%".$request["query"]."%")
        ->orderBy("nombre")
        ->get();
        return $this->sendResponse($dependences->toArray(), trans('data_obtained_successfully'));
    }

    public function getActiveUsers(Request $request){
        $users = User::where("name","like","%".$request["query"]."%")
        ->where("block", 0)
        ->orderBy("name")
        ->get()
        ->toArray();
        return $this->sendResponse($users, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los usuarios existentes
     *
     * @author Kleverman Salazar Florez. - Ago. 07 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getUsersByName(Request $request){
        $users = User::select(["id","name"])->where("name","like","%".$request['query']."%")->orderBy("name")->get();
        return $this->sendResponse($users, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {

        $input = $request->all();

        $input["password"] = '';
        $input["email_verified_at"] = date('Y-m-d H:i:s');

        // Validacion de correo existente
        $count_email = User::where('email',$input['email'])
        ->count();

        if($count_email > 0){
            return $this->sendSuccess('Este correo ya se encuentra registrado en el sistema.', 'error');
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            
            // Inserta el registro en la base de datos
            $user = $this->userRepository->create($input);
        
            // Asigna las dependencias
            if(!empty($input["dependencies"])){
                foreach ($input["dependencies"] as $dependence) {
                    $decodedDependence = json_decode($dependence);
    
                    // Verifica si el 'dependence_id' existe, de lo contrario usa 'id'
                    $dependenceId = !empty($decodedDependence->dependence_id) ? $decodedDependence->dependence_id : $decodedDependence->id;
    
                    // Obtiene el nombre de la dependencia
                    $dependenceName = $decodedDependence->nombre;
    
                    // Asigna la dependencia al usuario
                    $this->_assignDependence($user["id"], $dependenceName, $dependenceId);
                }
            }

            $user["encrypted_mail"] = Crypt::encryptString($user["email"]);

            UtilController::sendMail([$user['email']],"improvementplans::users.mails.new_user",$user,"Planes de mejoramiento institucional (PMI)"); // Envia un correo para verificar la cuenta

            // Valida si vienen roles para asignar
            if (!empty($input['roles'])) {
                // Asigna los roles seleccionados
                $user->syncRoles($input['roles']);
            }
            
            // Registro de evento de registro de usuario
            event(new Registered($user));

            // Obtiene relaciones
            $user->positions;
            $user->dependencies;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($user->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\UserController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\UserController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Asigna una dependencia a un usuario
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    private function _assignDependence(int $userId, string $dependenceName,int $dependenceId) : void{
        UserOtherDependence::create(["users_id" => $userId,"nombre" => $dependenceName,"dependence_id"=>$dependenceId]);
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request) {
        $input = $request->all();
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $user = $this->userRepository->update($input, $id);

            UserOtherDependence::where("users_id",$id)->delete();

        // Asigna las dependencias
        if (!empty($input["dependencies"])) {
            foreach ($input["dependencies"] as $dependence) {
                $decodedDependence = json_decode($dependence);

                // Verifica si el 'dependence_id' existe, de lo contrario usa 'id'
                $dependenceId = !empty($decodedDependence->dependence_id) ? $decodedDependence->dependence_id : $decodedDependence->id;

                // Obtiene el nombre de la dependencia
                $dependenceName = $decodedDependence->nombre;

                // Asigna la dependencia al usuario
                $this->_assignDependence($user["id"], $dependenceName, $dependenceId);
            }
        }


            // Valida si vienen roles para actualizar
            if (empty($input['roles'])) {
                $user->syncRoles([]);
            } else {
                // Asigna los roles seleccionados
                $user->syncRoles($input['roles']);
            }

            // Obtiene relaciones
            $user->positions;
            $user->dependencies;

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($user->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\UserController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\UserController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un User del almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
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

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $user->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\UserController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\UserController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
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
        } else if (strcmp($fileType, 'xlsx') == 0) {
            $users = User::with(['positions', 'dependencies', 'roles'])->orderBy("name")->orderBy("name")->get()->toArray();
            return Excel::download(new RequestExport('improvementplans::users.exports.xlsx', $users, 'G'), $fileName);
        }
    }
}
