<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ImprovementPlans\Http\Requests\CreateContentManagementRequest;
use Modules\ImprovementPlans\Http\Requests\UpdateContentManagementRequest;
use Modules\ImprovementPlans\Repositories\ContentManagementRepository;
use Modules\ImprovementPlans\Models\ContentManagement;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\ImprovementPlans\Http\Controllers\UtilController;
use Illuminate\Support\Facades\Validator;
use Modules\ImprovementPlans\Models\Rol;
use Modules\ImprovementPlans\Models\RolPermission;

/**
 * Descripcion de la clase
 *
 * @author Kleverman Salazar Florez. - Ago 30 - 2023
 * @version 1.0.0
 */
class ContentManagementController extends AppBaseController {

    /** @var  ContentManagementRepository */
    private $contentManagementRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     */
    public function __construct(ContentManagementRepository $contentManagementRepo) {
        $this->contentManagementRepository = $contentManagementRepo;
    }

    /**
     * Muestra la vista para el CRUD de ContentManagement.
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
    
        return view('improvementplans::content_managements.index')->with("can_manage", $canManage)->with("can_generate_reports", $canGenerateReports);
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
        // $allowedRoles = Rol::whereIn('id', RolPermission::where('module', 'Administración')->groupBy('role_id')->pluck('role_id'))->pluck('name')->toArray();
        $allowedRoles = ["Administración - Gestión (crear, editar y eliminar registros)", "Administración - Reportes", "Administración - Solo consulta"];
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

        // $rolePermissions = RolPermission::where('role_id', Rol::where('name', $allowedRoles[0])->first()->id)
        //     ->where('module', 'Administración')->first();

        return $user->hasRole('Administración - Gestión (crear, editar y eliminar registros)') || $user->hasRole('Administración - Reportes');
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
        $content_managements = $this->contentManagementRepository->all();
        return $this->sendResponse($content_managements->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     *
     * @param CreateContentManagementRequest $request
     *
     * @return Response
     */
    public function store(CreateContentManagementRequest $request) {

        $input = $request->all();
        // Se crea una instancia de la clase principal para enviarla como parámetro y usar las funciones no estáticas
        $app_base_controller = new AppBaseController();
        $input["archive"] = UtilController::uploadFile($input["archive"],'public/improvement_plan/content_management_archives', $app_base_controller);

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $contentManagement = $this->contentManagementRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($contentManagement->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ContentManagementController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ContentManagementController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        // TODO: Crea la funcionalidad de editar textos secundarios

        $input = $request->all();

        // Validar la existencia de un archivo para realizar las validaciones de extension y peso
        if(isset($input["archive"])){
            $validatorMimes = Validator::make($request->all(), [
                'archive' => 'required|file|mimes:jpeg,jpg,png',
            ]);
    
            $validatorWeight = Validator::make($request->all(), [
                'archive' => 'required|file|max:1024',
            ]);
    
            if ($validatorMimes->fails()) {
                return $this->sendSuccess("El archivo no es de extensión png, jpg o jpeg.", 'error');
            }
    
            if ($validatorWeight->fails()) {
                return $this->sendSuccess("El archivo debe tener un tamaño menor a 1MB.", 'error');
            }
        }

        // Se crea una instancia de la clase principal para enviarla como parámetro y usar las funciones no estáticas
        $app_base_controller = new AppBaseController();
        $input["archive"] = isset($input["archive"]) ? UtilController::uploadFile($input["archive"],'public/improvement_plan/content_management_archives', $app_base_controller): null;

        /** @var ContentManagement $contentManagement */
        $contentManagement = $this->contentManagementRepository->find($id);

        if (empty($contentManagement)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $contentManagement = $this->contentManagementRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($contentManagement->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ContentManagementController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ContentManagementController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ContentManagement del almacenamiento
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

        /** @var ContentManagement $contentManagement */
        $contentManagement = $this->contentManagementRepository->find($id);

        if (empty($contentManagement)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $contentManagement->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ContentManagementController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ContentManagementController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('content_managements').'.'.$fileType;

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
