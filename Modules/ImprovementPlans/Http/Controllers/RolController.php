<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\ImprovementPlans\RequestExport;
use Modules\ImprovementPlans\Http\Requests\CreateRolRequest;
use Modules\ImprovementPlans\Http\Requests\UpdateRolRequest;
use Modules\ImprovementPlans\Repositories\RolRepository;
use Modules\ImprovementPlans\Models\Rol;
use Modules\ImprovementPlans\Models\ContentManagement;
use Modules\ImprovementPlans\Models\RolPermission;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Spatie\Permission\Models\Role;

/**
 * Descripcion de la clase
 *
 * @author Kleverman Salazar Florez. - Ago. 24 - 2023
 * @version 1.0.0
 */
class RolController extends AppBaseController {

    /** @var  RolRepository */
    private $rolRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Ago. 24 - 2023
     * @version 1.0.0
     */
    public function __construct(RolRepository $rolRepo) {
        $this->rolRepository = $rolRepo;
    }

    /**
     * Muestra la vista para el CRUD de Rol.
     *
     * @author Kleverman Salazar Florez. - Ago. 24 - 2023
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
    
        return view('improvementplans::rols.index')->with("can_manage",$canManage)->with("can_generate_reports",$canGenerateReports);
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
     * @param User $user
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
     * @author Kleverman Salazar Florez. - Ago. 24 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $rols = Rol::with('RolPermissions')->latest()->get();
        return $this->sendResponse($rols->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ago. 24 - 2023
     * @version 1.0.0
     *
     * @param CreateRolRequest $request
     *
     * @return Response
     */
    public function store(Request $request) {

        $input = $request->all();
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $rol = Role::create([
                "name" => $input["name"],
                "guard_name" => "web",
                "description" => $input["description"],
            ]);
            
            // Asigna los permisos al rol
            foreach ($input["rol_permissions"] as $rol_permissions) {
                $rolPermission = json_decode($rol_permissions);
                $this->assignPermissionToRole($rol["id"],$rolPermission->module,property_exists($rolPermission,'can_manage'),property_exists($rolPermission,'can_generate_reports'),property_exists($rolPermission,'only_consultation'));
            }
            
            // Efectua los cambios realizados
            DB::commit();

            // Relaciones
            $rol->RolPermissions;

            return $this->sendResponse($rol->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\RolController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\RolController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Asigna los permisos correspondiente a un rol
     *
     * @author Kleverman Salazar Florez. - Ago. 24 - 2023
     * @version 1.0.0
     *
     * @param CreateRolRequest $request
     *
     * @return Response
     */
    private function assignPermissionToRole(int $roleId, string $module, bool $canManage, bool $canGenerateReports, bool $onlyConsultation) : void{
        RolPermission::create(["role_id" => $roleId,"module" => $module, "can_manage" => $canManage, "can_generate_reports" => $canGenerateReports, "only_consultation" => $onlyConsultation]);
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar Florez. - Ago. 24 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateRolRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRolRequest $request) {

        $input = $request->all();

        /** @var Rol $rol */
        $rol = $this->rolRepository->find($id);

        if (empty($rol)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $rol = $this->rolRepository->update($input, $id);

            RolPermission::where("role_id",$id)->forceDelete();
            
            // Asigna los permisos al rol
            foreach ($input["rol_permissions"] as $rol_permissions) {
                $rolPermission = json_decode($rol_permissions);
                $this->assignPermissionToRole($rol["id"],$rolPermission->module,property_exists($rolPermission,'can_manage'),property_exists($rolPermission,'can_generate_reports'),property_exists($rolPermission,'only_consultation'));
            }

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($rol->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\RolController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\RolController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un Rol del almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ago. 24 - 2023
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var Rol $rol */
        $rol = $this->rolRepository->find($id);

        if (empty($rol)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $rol->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\RolController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\RolController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Kleverman Salazar Florez. - Ago. 24 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('rols').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) {
            $rols = Rol::with('RolPermissions')->orderBy("name")->get()->toArray();
            return Excel::download(new RequestExport('improvementplans::rols.exports.xlsx', $rols , 'G'), $fileName);
        }
    }
}
