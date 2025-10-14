<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Leca\Http\Requests\CreateWeeklyRoutinesRequest;
use Modules\Leca\Http\Requests\UpdateWeeklyRoutinesRequest;
use Modules\Leca\Repositories\WeeklyRoutinesRepository;
use Modules\Leca\Models\WeeklyRoutines;
use App\Http\Controllers\AppBaseController;
use Modules\Leca\Models\MonthlyRoutines;
use Modules\Leca\Models\MonthlyRoutinesOfficials;
use App\User;
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
class WeeklyRoutinesController extends AppBaseController {

    /** @var  WeeklyRoutinesRepository */
    private $weeklyRoutinesRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(WeeklyRoutinesRepository $weeklyRoutinesRepo) {
        $this->weeklyRoutinesRepository = $weeklyRoutinesRepo;
    }

    /**
     * Muestra la vista para el CRUD de WeeklyRoutines.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
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
        
        // $lc_monthly_routines_id = lc_monthly_routines_id
        return view('leca::weekly_routines.index',compact(['newStart','newEnd']))->with("lc_monthly_routines_id", $request['lc_monthly_routines_id'] ?? null);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        $weekly_routines = WeeklyRoutines::with(['users','lcMonthlyRoutines','usersContract'])->where('lc_monthly_routines_id',$request['lc_monthly_routines_id'])->latest()->get();
        // $weekly_routines = $this->weeklyRoutinesRepository->all();
        return $this->sendResponse($weekly_routines->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateWeeklyRoutinesRequest $request
     *
     * @return Response
     */
    public function store(CreateWeeklyRoutinesRequest $request) {

        $input = $request->all();
        //Recupera lo que recibe por el calendario
        $nonBusiness = $input['non_business_days'];
        //Recorre los dias no habiles
        foreach($nonBusiness as $value){
            //Separa apartir de una cadena que recibe despues de la fecha
            $option = explode("T",$value);
            //Escoje una nueva posicion
            $newOption = $option[0];
            //Separa la nueva posicion por comillas
            $separateOption = explode('"',$newOption);
            //Escoje la posicion final
            $endOption = $separateOption[1];
            //Crea una arreglo con las posiciones nuevas
            $newArrayOfficials[] = $endOption;
        }
        //Recibe el arreglo final con todas las posiciones y las convierte en un objeto para almacenarlo en la base de datos
        $endConvert = json_encode($newArrayOfficials);
        //Separa por el corchete inical
        $optionConvert = explode('[',$endConvert);
        //Separa por el corchete final
        $newOptionConvert = explode(']',$optionConvert[1]);
        //Escoje el objeto en una posicion para que guarde solo las fechas
        $endOptionConvert = $newOptionConvert[0];



        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $input['user_name_officials'] = $input['name'];
            //Indica que el campo de dia no habil es igual a lo que recibe en la ultima posicion convertida a objeto
            $input['non_business_days'] = $endOptionConvert;
            // Inserta el registro en la base de datos
            $weeklyRoutines = $this->weeklyRoutinesRepository->create($input);
            //Envia la relacion con usuarios
            $weeklyRoutines->users;
            //Envia la relacion con usuarios de contrato
            $weeklyRoutines->usersContract;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($weeklyRoutines->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\WeeklyRoutinesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\WeeklyRoutinesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateWeeklyRoutinesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWeeklyRoutinesRequest $request) {

        $input = $request->all();
        //Recupera lo que recibe por el calendario
        $nonBusiness = $input['non_business_days'];
        //Recorre los dias no habiles
        foreach($nonBusiness as $value){
            //Separa apartir de una cadena que recibe despues de la fecha
            $option = explode("T",$value);
            //Escoje una nueva posicion
            $newOption = $option[0];
            //Separa la nueva posicion por comillas
            $separateOption = explode('"',$newOption);
            //Escoje la posicion final
            $endOption = $separateOption[1];
            //Crea una arreglo con las posiciones nuevas
            $newArrayOfficials[] = $endOption;
        }
        //Recibe el arreglo final con todas las posiciones y las convierte en un objeto para almacenarlo en la base de datos
        $endConvert = json_encode($newArrayOfficials);
        //Separa por el corchete inical
        $optionConvert = explode('[',$endConvert);
        //Separa por el corchete final
        $newOptionConvert = explode(']',$optionConvert[1]);
        //Escoje el objeto en una posicion para que guarde solo las fechas
        $endOptionConvert = $newOptionConvert[0];

        //Indica que el campo de dia no habil es igual a lo que recibe en la ultima posicion convertida a objeto
        $input['non_business_days'] = $endOptionConvert;

        /** @var WeeklyRoutines $weeklyRoutines */
        $weeklyRoutines = $this->weeklyRoutinesRepository->find($id);



        if (empty($weeklyRoutines)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            if(array_key_exists("name", $input)){
                $input['user_name_officials'] = $input['name'];
            } else {
                $input['user_name_officials'] = $input['user_name_officials'];
            }
            // Actualiza el registro
            $weeklyRoutines = $this->weeklyRoutinesRepository->update($input, $id);

            //Envia la relacion con usuarios
            $weeklyRoutines->users;
            //Envia la relacion con usuarios de contrato
            $weeklyRoutines->usersContract;
            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($weeklyRoutines->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\WeeklyRoutinesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\WeeklyRoutinesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un WeeklyRoutines del almacenamiento
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

        /** @var WeeklyRoutines $weeklyRoutines */
        $weeklyRoutines = $this->weeklyRoutinesRepository->find($id);

        if (empty($weeklyRoutines)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $weeklyRoutines->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\WeeklyRoutinesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\WeeklyRoutinesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('weekly_routines').'.'.$fileType;

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
     * Obtiene todos los puntos de muestra para la programacion de toma de muestra
     *
     * @author José Manuel Marín Londoño. - Dic. 01 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getOficcialsMontlyRoutines(Request $request, $id)
    {
        $weeklyRoutines = MonthlyRoutinesOfficials::with(['users'])->where('lc_monthly_routines_id',$id)->latest()->get();
        foreach($weeklyRoutines as $officials){
            $newArrayOfficials[] = $officials->users;
        }
        return $this->sendResponse($newArrayOfficials, trans('data_obtained_successfully'));
    }

    /**
	 * Obtiene todos los funcionarios para el automcomplete
	 *
	 * @author Josè Manuel Marìn Londoño. - Nov. 22 - 2021
	 * @version 1.0.0
	 *
	 * @return Response
	 */
    public function getOficcialsReplacement(Request $request) {
        $usersOfficials = User::role(['Personal de Apoyo'])->where('name',"like","%".$request['query']."%")->get();
        // $usersOfficials = User::all();
        return $this->sendResponse($usersOfficials, trans('data_obtained_successfully'));
    }
}