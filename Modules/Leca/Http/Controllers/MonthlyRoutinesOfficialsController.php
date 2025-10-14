<?php

namespace Modules\Leca\Http\Controllers;

use DB;
use Auth;
use Flash;
use App\User;
use Response;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Leca\RutinaFuncionario;
use Modules\Leca\Models\ListTrials;
use App\Http\Controllers\AppBaseController;
use Modules\Leca\Models\MonthlyRoutines;
use Modules\Leca\Models\MonthlyRoutinesOfficials;
use Modules\Leca\Models\HistorialFuncionarioRutina;
use Modules\Leca\Repositories\MonthlyRoutinesOfficialsRepository;
use Modules\Leca\Http\Requests\CreateMonthlyRoutinesOfficialsRequest;
use Modules\Leca\Http\Requests\UpdateMonthlyRoutinesOfficialsRequest;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class MonthlyRoutinesOfficialsController extends AppBaseController {

    /** @var  MonthlyRoutinesOfficialsRepository */
    private $monthlyRoutinesOfficialsRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(MonthlyRoutinesOfficialsRepository $monthlyRoutinesOfficialsRepo) {
        $this->monthlyRoutinesOfficialsRepository = $monthlyRoutinesOfficialsRepo;
    }

    /**
     * Muestra la vista para el CRUD de MonthlyRoutinesOfficials.
     *
     * @author José Manuel Marín Londoño. - Dic. 13 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $monthly = MonthlyRoutines::where("id", $request['lc_monthly_routines_id'] ?? null)->first();
        $starDate = $monthly->routine_start_date;
        $separateOption = explode(' ',$starDate);
        $newStart = $separateOption[0];
        $endDate = $monthly->routine_end_date;
        $separateEnd = explode(' ',$endDate);
        $newEnd = $separateEnd[0];

        return view('leca::monthly_routines_officials.index',compact(['newStart','newEnd']))->with("lc_monthly_routines_id", $request['lc_monthly_routines_id'] ?? null);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Dic. 13 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        $monthly_routines_officials = MonthlyRoutinesOfficials::with(['users','lcListTrials','historiales'])->where('lc_monthly_routines_id',$request['lc_monthly_routines_id'])->latest()->get();
        // $monthly_routines_officials = $this->monthlyRoutinesOfficialsRepository->all();
        return $this->sendResponse($monthly_routines_officials->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author José Manuel Marín Londoño. - Dic. 13 - 2021
     * @version 1.0.0
     *
     * @param CreateMonthlyRoutinesOfficialsRequest $request
     *
     * @return Response
     */
    public function store(CreateMonthlyRoutinesOfficialsRequest $request) {
        
        $input = $request->all();
        $rutina=MonthlyRoutines::find($input['lc_monthly_routines_id']);


        $input['fecha_inicio']=$rutina->routine_start_date;
        $input['fecha_fin']=$rutina->routine_end_date;

        // Inicia la transaccion
        // DB::beginTransaction();
        // try {
            //Guarda el nommbre del usuario con el que se registro desde un principio
            $input['user_name'] = $input['objet_oficial_monthly']['name'];
            // Inserta el registro en la base de datos
            $monthlyRoutinesOfficials = $this->monthlyRoutinesOfficialsRepository->create($input);

            // Valida si viene grupos de trabajo para asignar
            if (!empty($input['lc_list_trials'])) {
                // Inserta relacion con grupos de trabajo
                $monthlyRoutinesOfficials->lcListTrials()->sync($input['lc_list_trials']);
            }
            //Obtiene los usuarios
            $monthlyRoutinesOfficials->users;
            //Obtiene los ensayos
            $monthlyRoutinesOfficials->lcListTrials;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($monthlyRoutinesOfficials->toArray(), trans('msg_success_save'));
        // } catch (\Illuminate\Database\QueryException $error) {
        //     // Devuelve los cambios realizados
        //     DB::rollback();
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesOfficialsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
        //     // Retorna mensaje de error de base de datos
        //     return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        // } catch (\Exception $e) {
        //     // Devuelve los cambios realizados
        //     DB::rollback();
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesOfficialsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
        //     // Retorna error de tipo logico
        //     return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        // }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateMonthlyRoutinesOfficialsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMonthlyRoutinesOfficialsRequest $request) {   
        
        $user=Auth::user();
        $input = $request->except(['created_at', 'updated_at', '_method', 'id']);


        $userNameMonthly = User::where('id',$input["users_id"])->first();

        $input['user_name'] = $userNameMonthly->name;

        /** @var MonthlyRoutinesOfficials $monthlyRoutinesOfficials */
        $OldMonthlyRoutinesOfficials = $this->monthlyRoutinesOfficialsRepository->find($id);

        if (empty($OldMonthlyRoutinesOfficials)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $OldMonthlyRoutinesOfficials->lcListTrials;

            $historial=new HistorialFuncionarioRutina();
            $historial->name=$user->name;
            $historial->name_user_p=$userNameMonthly->name;
            $historial->name_user_s=$OldMonthlyRoutinesOfficials->user_name;
            $historial->observacion=$input['observation'];
            $historial->lc_monthly_routines_officials_id=$OldMonthlyRoutinesOfficials->id;
            $historial->save();

            // Actualiza el registro
            $monthlyRoutinesOfficials = $this->monthlyRoutinesOfficialsRepository->update($input, $id);

            //Valida si viene snsayos para actualizar
            if (empty($input['lc_list_trials'])) {
                $monthlyRoutinesOfficials->lcListTrials()->detach();
            } else {
                //Inserta relacion con los ensayos
                $monthlyRoutinesOfficials->lcListTrials()->sync($input['lc_list_trials']);
            }

            //Obtiene los usuarios
            $monthlyRoutinesOfficials->users;
            //Obtiene los ensayos
            $monthlyRoutinesOfficials->lcListTrials;
            //Obtiene el historial
            $monthlyRoutinesOfficials->historiales;

            // Prepara los datos de usuario para comparar
            $arrDataMonthlyRoutinesOfficials = Arr::dot(UtilController::dropNullEmptyList($monthlyRoutinesOfficials->replicate()->toArray(), true,'lc_list_trials')); // Datos actuales de usuario
            $arrDataOldMonthlyRoutinesOfficials = Arr::dot(UtilController::dropNullEmptyList($OldMonthlyRoutinesOfficials->replicate()->toArray(), true,'lc_list_trials')); // Datos antiguos de usuario

            // Datos diferenciados
            $arrDiff = array_diff_assoc($arrDataMonthlyRoutinesOfficials, $arrDataOldMonthlyRoutinesOfficials);

            // Valida si los datos antiguos son diferentes a los actuales
            if ( count($arrDiff) > 0) {
                // Lista diferencial sin la notacion punto
                $arrDiffUndot = UtilController::arrayUndot($arrDiff);
                // Agrega ensayos de trabajo asignados
                $input['lc_list_trials'] = array_key_exists('lc_list_trials', $arrDiffUndot) ?
                    json_encode($monthlyRoutinesOfficials->lcListTrials->toArray()) : json_encode($OldMonthlyRoutinesOfficials->lcListTrials->toArray());
            }

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($monthlyRoutinesOfficials->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesOfficialsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesOfficialsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un MonthlyRoutinesOfficials del almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var MonthlyRoutinesOfficials $monthlyRoutinesOfficials */
        $monthlyRoutinesOfficials = $this->monthlyRoutinesOfficialsRepository->find($id);

        if (empty($monthlyRoutinesOfficials)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $monthlyRoutinesOfficials->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesOfficialsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesOfficialsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
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

    //     // Tipo de archivo (extencion)
    //     $fileType = $input['fileType'];
    //     // Nombre de archivo con tiempo de creacion
    //     $fileName = time().'-'.trans('monthly_routines_officials').'.'.$fileType;

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
     * Genera el reporte de excel
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
         
        
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('Rutina funcionarios').'.'.$fileType;
        
        return Excel::download(new RutinaFuncionario('leca::monthly_routines_officials.report_excel', $input['data'],'e'), $fileName);
    }

    /**
	 * Obtiene todos los funcionarios para el automcomplete
	 *
	 * @author Josè Manuel Marìn Londoño. - Nov. 22 - 2021
	 * @version 1.0.0
	 *
	 * @return Response
	 */
    public function getOficcials(Request $request) {
        // $usersOfficials = User::role(['Analista','Recepcionista','Personal de Apoyo','Toma de Muestra'])->where('name',"like","%".$request['query']."%")->get();

        $usersOfficials = User::role(['Analista fisicoquímico','Analista microbiológico'])->where('name',"like","%".$request['query']."%")->get();
        return $this->sendResponse($usersOfficials, trans('data_obtained_successfully'));
    }

    /**
	 * Obtiene todos los ensayos
	 *
	 * @author Josè Manuel Marìn Londoño. - Nov. 22 - 2021
	 * @version 1.0.0
	 *
	 * @return Response
	 */
    public function getListTrials(Request $request) {
        
        $listTrials = ListTrials::all();
        // dd($listTrials->toArray());
        return $this->sendResponse($listTrials, trans('data_obtained_successfully'));
    }
}
