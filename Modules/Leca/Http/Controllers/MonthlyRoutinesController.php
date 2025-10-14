<?php

namespace Modules\Leca\Http\Controllers;

use DB;
use Auth;
use Flash;
use App\User;
use Response;
use Illuminate\Http\Request;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Leca\Models\Officials;
use Modules\Leca\Models\ListTrials;
use App\Http\Controllers\AppBaseController;
use Modules\Leca\Models\MonthlyRoutines;
use Modules\Leca\Models\MonthlyRoutinesHasUsers;
use Modules\Leca\Models\MonthlyRoutinesOfficials;
use Modules\Leca\Repositories\MonthlyRoutinesRepository;
use Modules\Leca\Http\Requests\CreateMonthlyRoutinesRequest;
use Modules\Leca\Http\Requests\UpdateMonthlyRoutinesRequest;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class MonthlyRoutinesController extends AppBaseController {

    /** @var  MonthlyRoutinesRepository */
    private $monthlyRoutinesRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(MonthlyRoutinesRepository $monthlyRoutinesRepo) {
        $this->monthlyRoutinesRepository = $monthlyRoutinesRepo;
    }

    /**
     * Muestra la vista para el CRUD de MonthlyRoutines.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('leca::monthly_routines.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $monthly_routines = MonthlyRoutines::with(['lcOfficials'])->get();
        // $monthly_routines = $this->monthlyRoutinesRepository->all();
        return $this->sendResponse($monthly_routines->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateMonthlyRoutinesRequest $request
     *
     * @return Response
     */
    public function store(CreateMonthlyRoutinesRequest $request) {

        $user = Auth::user();
        $input = $request->all();
        //Recibe lo que viene por el listado dinamico
        // $DinamycListString = $input['lc_prueba'];

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $input['users_id'] = Auth::user()->id;
            // Inserta el registro en la base de datos
            $monthlyRoutines = $this->monthlyRoutinesRepository->create($input);
            // $count = 0;
            //Convierte ese string en un array asociativo
            // foreach($DinamycListString as $data){
            //     $arrayPointData = json_decode($data, true);
            //     $enterOfficial = $arrayPointData["routine_analyst"];
            //     $multipleChoice = $arrayPointData["multiselect"];
            //     // dd($multipleChoice);
            //     // $multipleChoice = $arrayPointData[$count]->multiselect;
            //     foreach($multipleChoice as $new){
            //         $idMultiselect = $new["id"];
            //         MonthlyRoutinesHasUsers::create([
            //             'lc_monthly_routines_id' => $monthlyRoutines->id,
            //             'users_id' => $enterOfficial,
            //             'lc_list_trials_id' => $idMultiselect
            //         ]);
            //         // DB::table('lc_monthly_routines_has_lc_officials')->insert([
            //         //     'lc_monthly_routines_id' => $monthlyRoutines->id,
            //         //     'lc_officials_id' => $enterOfficial,
            //         //     'lc_list_trials_id' => $idMultiselect
            //         // ]);
            //     }
            //     // $count ++;
            // }
            
            //Envia las relaciones con la tabla de usuarios
            $monthlyRoutines->users;
            //Envia la relacion con la tabla de funcionarios

            // $monthlyRoutines->lcPrueba;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($monthlyRoutines->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateMonthlyRoutinesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMonthlyRoutinesRequest $request) {

        $input = $request->all();
        // $DinamycListString = $input['lc_prueba'];
        /** @var MonthlyRoutines $monthlyRoutines */
        $monthlyRoutines = $this->monthlyRoutinesRepository->find($id);

        if (empty($monthlyRoutines)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $rutinasUsuarios=MonthlyRoutinesOfficials::where('lc_monthly_routines_id', $id)->get();

        if(count( $rutinasUsuarios)>0){
            foreach ($rutinasUsuarios as $key => $value) {
                
                $value->fecha_inicio=$monthlyRoutines->routine_start_date;
                $value->fecha_fin=$monthlyRoutines->routine_end_date;
                $value->save();
            }
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $monthlyRoutines = $this->monthlyRoutinesRepository->update($input, $id);
            // DB::table('lc_monthly_routines_has_lc_officials')->where('lc_monthly_routines_id', $monthlyRoutines->id)->delete();

            // foreach($DinamycListString as $data){
            //     $arrayPointData = json_decode($data, true);

            //     $enterOfficial = $arrayPointData["routine_analyst"] ?? $arrayPointData["lc_officials_id"];
            //     $multipleChoice = $arrayPointData["multiselect"];
            //     foreach($multipleChoice as $new){
            //         $idMultiselect = $new["id"];
            //         MonthlyRoutinesHasUsers::create([
            //             'lc_monthly_routines_id' => $monthlyRoutines->id,
            //             'users_id' => $enterOfficial,
            //             'lc_list_trials_id' => $idMultiselect
            //         ]);
            //     }
            // }
            //Envia la relacion con la tabla de usuarios
            $monthlyRoutines->users;
            //Envia la relacion con la tablad de funcionarios
            // $monthlyRoutines->lcOfficials;
            // $monthlyRoutines->lcPrueba;

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($monthlyRoutines->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un MonthlyRoutines del almacenamiento
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

        /** @var MonthlyRoutines $monthlyRoutines */
        $monthlyRoutines = $this->monthlyRoutinesRepository->find($id);

        if (empty($monthlyRoutines)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            $officialRoutine=MonthlyRoutinesOfficials::where('lc_monthly_routines_id', $id)->delete();

            // Elimina el registro
            $monthlyRoutines->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\MonthlyRoutinesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('monthly_routines').'.'.$fileType;

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
