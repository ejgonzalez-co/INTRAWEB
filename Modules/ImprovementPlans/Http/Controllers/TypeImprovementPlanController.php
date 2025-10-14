<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\ImprovementPlans\RequestExport;
use Modules\ImprovementPlans\Http\Requests\CreateTypeImprovementPlanRequest;
use Modules\ImprovementPlans\Http\Requests\UpdateTypeImprovementPlanRequest;
use Modules\ImprovementPlans\Repositories\TypeImprovementPlanRepository;
use Modules\ImprovementPlans\Models\ContentManagement;
use Modules\ImprovementPlans\Models\TypeImprovementPlan;
use Modules\ImprovementPlans\Models\HolidayCalendar;
use Modules\ImprovementPlans\Models\ConfigurationMessageTime;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\JwtController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\ImprovementPlans\Http\Controllers\UtilController;
use Carbon\Carbon;
use Modules\ImprovementPlans\Models\Rol;
use Modules\ImprovementPlans\Models\RolPermission;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class TypeImprovementPlanController extends AppBaseController {

    /** @var  TypeImprovementPlanRepository */
    private $typeImprovementPlanRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(TypeImprovementPlanRepository $typeImprovementPlanRepo) {
        $this->typeImprovementPlanRepository = $typeImprovementPlanRepo;
    }

    /**
     * Muestra la vista para el CRUD de TypeImprovementPlan.
     *
     * @author Desarrollador Seven - 2022
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
    
        return view('improvementplans::type_improvement_plans.index')->with("can_manage",$canManage)->with("can_generate_reports",$canGenerateReports);
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
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $type_improvement_plans = $this->typeImprovementPlanRepository->all();
        return $this->sendResponse($type_improvement_plans->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes que su estado este activo
     *
     * @author Kleverman Salazar Florez. - Ago. 07 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getActiveTypesImprovementPlans(TypeImprovementPlan $type_improvement_plans){
        $type_improvement_plans = $type_improvement_plans->select(["id","name"])->where("status","Activo")->get()->toArray();
        return $this->sendResponse($type_improvement_plans, trans('data_obtained_successfully'));
    }

    public function getHolidayCalendar(){
        $holidayCalendar =  HolidayCalendar::select("date")->get()->toArray();
        return $this->sendResponse($holidayCalendar, trans('data_obtained_successfully'));
    }

    public function getConfigurationMessageTime(){
        $configurationMessageTime = ConfigurationMessageTime::first();

        if (!empty($configurationMessageTime)) {
            return $this->sendResponse($configurationMessageTime->toArray(), trans('data_obtained_successfully'));
        }else{
            dd('Falta configurar los tiempos de mensaje');
        }
    }

    /**
     * Configura los tiempos de mensaje de un registro
     *
     * @author Kleverman Salazar Florez. - Ago. 07 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function configureMessageTime(Request $request){
        $input = $request->all();

        // Valida si viene fechas para asignar
        if (!empty($input['date'])){
            HolidayCalendar::truncate();

            // Recorre las fechas seleccionadas
            foreach ($input['date'] as $date) {
                $date = str_replace('"','', $date);

                $holidayCalendar = HolidayCalendar::create([
                    'date' => Carbon::parse($date)->format('Y-m-d'),
                ]);
            }
        }

        ConfigurationMessageTime::truncate();
        $configurationMessageTime =ConfigurationMessageTime::create($input);

        return $this->sendResponse($holidayCalendar->toArray(), trans('msg_success_update'));
    }


    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {

        $input = $request->all();

        $input["user_id"] = Auth::user()->id;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $typeImprovementPlan = $this->typeImprovementPlanRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($typeImprovementPlan->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\TypeImprovementPlanController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\TypeImprovementPlanController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        $input = $request->all();

        $input["user_id"] = Auth::user()->id;

        /** @var TypeImprovementPlan $typeImprovementPlan */
        $typeImprovementPlan = $this->typeImprovementPlanRepository->find($id);

        if (empty($typeImprovementPlan)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $typeImprovementPlan = $this->typeImprovementPlanRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($typeImprovementPlan->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\TypeImprovementPlanController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\TypeImprovementPlanController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un TypeImprovementPlan del almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var TypeImprovementPlan $typeImprovementPlan */
        $typeImprovementPlan = $this->typeImprovementPlanRepository->find($id);

        if (empty($typeImprovementPlan)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $typeImprovementPlan->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\TypeImprovementPlanController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\TypeImprovementPlanController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('type_improvement_plans').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) {
            $data = JwtController::decodeToken($input["data"]);
            array_walk($data, fn(&$object) => $object = (array) $object);
            return UtilController::exportReportToXlsxFile('improvementplans::type_improvement_plans.exports.xlsx', $data, 'd', 'Reporte de las oportunidades de mejora.xlsx');
        }
    }
}
