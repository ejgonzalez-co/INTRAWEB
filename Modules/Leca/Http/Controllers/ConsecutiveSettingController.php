<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Leca\Http\Requests\CreateConsecutiveSettingRequest;
use Modules\Leca\Http\Requests\UpdateConsecutiveSettingRequest;
use Modules\Leca\Repositories\ConsecutiveSettingRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Leca\Models\ConsecutiveSetting;
use Modules\Leca\Models\ReportManagement;
use App\Exports\Leca\RequestExport;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ConsecutiveSettingController extends AppBaseController {

    /** @var  ConsecutiveSettingRepository */
    private $consecutiveSettingRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ConsecutiveSettingRepository $consecutiveSettingRepo) {
        $this->consecutiveSettingRepository = $consecutiveSettingRepo;
    }

    /**
     * Muestra la vista para el CRUD de ConsecutiveSetting.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        // esta consulta, se encarga de traer el ultimo registro que contenga un consecutivo IC.
        $consecutivoActualIC = ReportManagement::orderBy('id', 'desc')
        ->where('lc_rm_report_management.consecutive','like','%'.'IC'.'%')
        ->get()->first();

        // esta consulta, se encarga de traer el ultimo registro que contenga un consecutivo IE.
        $consecutivoActualIE = ReportManagement::orderBy('id', 'desc')
        ->where('lc_rm_report_management.consecutive','like','%'.'IE'.'%')
        ->get()->first();

        if($consecutivoActualIC == null && $consecutivoActualIE == null){
            return view('leca::consecutive_settings.index');
        } else {
            return view('leca::consecutive_settings.index')->with('idIC',$consecutivoActualIC['id'])->with('idIE',$consecutivoActualIE['id']);
        }
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

        $consecutive_settings = ConsecutiveSetting::where('coments_consecutive','<>',null)->latest()->get();
        return $this->sendResponse($consecutive_settings->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateConsecutiveSettingRequest $request
     *
     * @return Response
     */
    public function store(CreateConsecutiveSettingRequest $request) {

        $input = $request->all();

       
    if(isset($input['nex_consecutiveIE'])){ 
    
        $ultimoConsecutivo=DB::table('lc_rm_report_management as reporte')->select('reporte.nex_consecutiveIE as prox')->where('reporte.id',$input['id_consecutiveIE'])->get()->first();
  
        if ($input['nex_consecutiveIE'] <= $ultimoConsecutivo->prox ){
            return $this->sendSuccess('Error'.'<br>'.'El nuevo consecutivo, debe de ser mayor al actual', 'error');
 
        }else{
        $consecutiveSetting = ReportManagement::orderBy('id', 'desc')
        ->where('lc_rm_report_management.consecutive','like','%'.'IE'.'%')
        ->get()->first();
        $consecutiveSetting->nex_consecutiveIC = null;
        $consecutiveSetting->nex_consecutiveIE = $input['nex_consecutiveIE'];
        $consecutiveSetting->coments_consecutive = $input['coments_consecutive']; 

     }
    }

    if(isset($input['nex_consecutiveIC'])){ 

        $ultimoConsecutivo=DB::table('lc_rm_report_management as reporte')->select('reporte.nex_consecutiveIC as prox')->where('reporte.id',$input['id_consecutiveIC'])->get()->first();
        
        if ($input['nex_consecutiveIC'] <= $ultimoConsecutivo->prox ){
            return $this->sendSuccess('Error'.'<br>'.'El nuevo consecutivo, debe de ser mayor al actual', 'error');
 
        }else{
        $consecutiveSetting = ReportManagement::orderBy('id', 'desc')
        ->where('lc_rm_report_management.consecutive','like','%'.'IC'.'%')
        ->get()->first();
        $consecutiveSetting->nex_consecutiveIE = null;
        $consecutiveSetting->nex_consecutiveIC = $input['nex_consecutiveIC'];
        $consecutiveSetting->coments_consecutive = $input['coments_consecutive'];
     }
    }

    

       
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $consecutiveSetting ->save();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($consecutiveSetting->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ConsecutiveSettingController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ConsecutiveSettingController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateConsecutiveSettingRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateConsecutiveSettingRequest $request) {

        $input = $request->all();

        /** @var ConsecutiveSetting $consecutiveSetting */
        $consecutiveSetting = $this->consecutiveSettingRepository->find($id);

        if (empty($consecutiveSetting)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $consecutiveSetting = $this->consecutiveSettingRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($consecutiveSetting->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ConsecutiveSettingController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ConsecutiveSettingController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ConsecutiveSetting del almacenamiento
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

        /** @var ConsecutiveSetting $consecutiveSetting */
        $consecutiveSetting = $this->consecutiveSettingRepository->find($id);

        if (empty($consecutiveSetting)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $consecutiveSetting->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ConsecutiveSettingController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ConsecutiveSettingController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        // $fileName = date('Y-m-d H:i:s').'-'.trans('request_authorizations').'.'.$fileType;
        $fileName = 'setting.' . $fileType;
        return Excel::download(new RequestExport('leca::consecutive_settings.report_excel', $input['data'], 'c'), $fileName);

        // return Excel::download(new RequestExport('sanitary_authorization::request_authorizations.report_excel', $input['data']), $fileName);
    }

   
    public function  getConsecutive($newFiel) {

        $consecutivoActual = ReportManagement::select(['consecutive', 'nex_consecutiveIC'])->orderBy('id', 'desc')
        ->where('lc_rm_report_management.consecutive','like','%'.'IC'.'%')
        ->get()->first();
        
        
        return $this->sendResponse($consecutivoActual->nex_consecutiveIC, trans('msg_success_save'));
    }


    
}
