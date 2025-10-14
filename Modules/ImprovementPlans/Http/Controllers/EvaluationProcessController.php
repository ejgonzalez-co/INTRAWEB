<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\ImprovementPlans\RequestExport;
use Modules\ImprovementPlans\Http\Requests\CreateEvaluationProcessRequest;
use Modules\ImprovementPlans\Http\Requests\UpdateEvaluationProcessRequest;
use Modules\ImprovementPlans\Repositories\EvaluationProcessRepository;
use Modules\ImprovementPlans\Models\EvaluationProcess;
use Modules\ImprovementPlans\Models\ContentManagement;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\JwtController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\ImprovementPlans\Models\Rol;
use Modules\ImprovementPlans\Models\RolPermission;

/**
 * Descripcion de la clase
 *
 * @author Kleverman Salazar Florez. - Ago 30 - 2023
 * @version 1.0.0
 */
class EvaluationProcessController extends AppBaseController {

    /** @var  EvaluationProcessRepository */
    private $evaluationProcessRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     */
    public function __construct(EvaluationProcessRepository $evaluationProcessRepo) {
        $this->evaluationProcessRepository = $evaluationProcessRepo;
    }

    /**
     * Muestra la vista para el CRUD de EvaluationProcess.
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
            $canManage = $canGenerateReports =  true;
            $canGenerateReports = true;
        } elseif ($userLogged->hasRole($allowedRoles)) {
            $canManage = $canGenerateReports = $this->hasPermissions($userLogged);
        }
        else{
            return abort(403,"No se encuentra autorizado.");
        }
    
        return view('improvementplans::evaluation_processes.index')->with("can_manage",$canManage)->with("can_generate_reports",$canGenerateReports);
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
        // $allowedRoles = Rol::whereIn('id', RolPermission::where('module', 'Evaluadores')->groupBy('role_id')->pluck('role_id'))->pluck('name')->toArray();
        $allowedRoles = ["Evaluadores - Gestión (crear, editar y eliminar registros)", "Evaluadores - Reportes", "Evaluadores - Solo consulta"];
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
        //     ->where('module', 'Evaluadores')->first();

        return $user->hasRole('Evaluadores - Gestión (crear, editar y eliminar registros)') || $user->hasRole('Evaluadores - Reportes');
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
        $evaluation_processes = EvaluationProcess::with('user')->latest()->get();
        return $this->sendResponse($evaluation_processes->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ago 30 - 2023
     * @version 1.0.0
     *
     * @param CreateEvaluationProcessRequest $request
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
            $evaluationProcess = $this->evaluationProcessRepository->create($input);

            // Obtiene relaciones
            $evaluationProcess->user;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($evaluationProcess->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\EvaluationProcessController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\EvaluationProcessController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateEvaluationProcessRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        $input = $request->all();

        /** @var EvaluationProcess $evaluationProcess */
        $evaluationProcess = $this->evaluationProcessRepository->find($id);

        if (empty($evaluationProcess)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $evaluationProcess = $this->evaluationProcessRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($evaluationProcess->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\EvaluationProcessController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\EvaluationProcessController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un EvaluationProcess del almacenamiento
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

        /** @var EvaluationProcess $evaluationProcess */
        $evaluationProcess = $this->evaluationProcessRepository->find($id);

        if (empty($evaluationProcess)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $evaluationProcess->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\EvaluationProcessController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\EvaluationProcessController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('evaluation_processes').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) {
            $data = JwtController::decodeToken($input["data"]);
            array_walk($data, fn(&$object) => $object = (array) $object);
            return Excel::download(new RequestExport('improvementplans::evaluation_processes.exports.xlsx', $data, 'c'), $fileName);
        }
    }
}
