<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Leca\Http\Requests\CreateMonthlyRoutinesHasUsersRequest;
use Modules\Leca\Http\Requests\UpdateMonthlyRoutinesHasUsersRequest;
use Modules\Leca\Repositories\MonthlyRoutinesHasUsersRepository;
use App\Http\Controllers\AppBaseController;
use App\User;
use Modules\Leca\Models\ListTrials;
use Modules\Leca\Models\MonthlyRoutines;
use Modules\Leca\Models\MonthlyRoutinesHasUsers;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class MonthlyRoutinesHasUsersController extends AppBaseController {

    /** @var  MonthlyRoutinesHasUsersRepository */
    private $monthlyRoutinesHasUsersRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(MonthlyRoutinesHasUsersRepository $monthlyRoutinesHasUsersRepo) {
        $this->monthlyRoutinesHasUsersRepository = $monthlyRoutinesHasUsersRepo;
    }

    /**
     * Muestra la vista para el CRUD de MonthlyRoutinesHasUsers.
     *
     * @author José Manuel Marín Londoño. - Dic. 06 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('leca::monthly_routines_has_users.index')->with("lc_monthly_routines_id", $request['lc_monthly_routines_id'] ?? null);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Dic. 06 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        $monthly_routines_has_users = MonthlyRoutinesHasUsers::with(['users','lcListTrials'])->where('lc_monthly_routines_id',$request['lc_monthly_routines_id'])->latest()->get();
        // $monthly_routines_has_users = $this->monthlyRoutinesHasUsersRepository->all();
        return $this->sendResponse($monthly_routines_has_users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateMonthlyRoutinesHasUsersRequest $request
     *
     * @return Response
     */
    public function store(CreateMonthlyRoutinesHasUsersRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $monthlyRoutinesHasUsers = $this->monthlyRoutinesHasUsersRepository->create($input);

            $monthlyRoutinesHasUsers->users;
            $monthlyRoutinesHasUsers->lcListTrials;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($monthlyRoutinesHasUsers->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesHasUsersController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesHasUsersController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateMonthlyRoutinesHasUsersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMonthlyRoutinesHasUsersRequest $request) {

        $input = $request->all();

        /** @var MonthlyRoutinesHasUsers $monthlyRoutinesHasUsers */
        $monthlyRoutinesHasUsers = $this->monthlyRoutinesHasUsersRepository->find($id);

        if (empty($monthlyRoutinesHasUsers)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $monthlyRoutinesHasUsers = $this->monthlyRoutinesHasUsersRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($monthlyRoutinesHasUsers->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesHasUsersController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesHasUsersController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un MonthlyRoutinesHasUsers del almacenamiento
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

        /** @var MonthlyRoutinesHasUsers $monthlyRoutinesHasUsers */
        $monthlyRoutinesHasUsers = $this->monthlyRoutinesHasUsersRepository->find($id);

        if (empty($monthlyRoutinesHasUsers)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $monthlyRoutinesHasUsers->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesHasUsersController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesHasUsersController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
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
        $fileName = time().'-'.trans('monthly_routines_has_users').'.'.$fileType;

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

    /**
	 * Obtiene todos los funcionarios para el automcomplete
	 *
	 * @author Josè Manuel Marìn Londoño. - Nov. 22 - 2021
	 * @version 1.0.0
	 *
	 * @return Response
	 */
    public function getOficcials(Request $request) {
        $usersOfficials = User::role(['Analista microbiológico','Analista fisicoquímico','Personal de Apoyo','Toma de Muestra'])->where('name',"like","%".$request['query']."%")->get();
        // $usersOfficials = User::all();
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
        // $listTrials = DB::table('lc_list_trials')->get();
        return $this->sendResponse($listTrials, trans('data_obtained_successfully'));
    }
}
