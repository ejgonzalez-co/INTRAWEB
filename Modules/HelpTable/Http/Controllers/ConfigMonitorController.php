<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\HelpTable\RequestExport;
use Modules\HelpTable\Http\Requests\CreateConfigMonitorRequest;
use Modules\HelpTable\Http\Requests\UpdateConfigMonitorRequest;
use Modules\HelpTable\Repositories\ConfigMonitorRepository;
use Modules\HelpTable\Models\ConfigMonitor;
use Modules\HelpTable\Models\ConfigMonitorHistory;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\JwtController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Cookie;
use Request as CookieRequest;

/**
 * Descripcion de la clase
 *
 * @author Kleverman Salazar Florez. - Ene. 23 - 2023
 * @version 1.0.0
 */
class ConfigMonitorController extends AppBaseController {

    /** @var  ConfigMonitorRepository */
    private $configMonitorRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Ene. 23 - 2023
     * @version 1.0.0
     */
    public function __construct(ConfigMonitorRepository $configMonitorRepo) {
        $this->configMonitorRepository = $configMonitorRepo;
    }

    /**
     * Muestra la vista para el CRUD de ConfigMonitor.
     *
     * @author Kleverman Salazar Florez. - Ene. 23 - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $cookieProviderName = CookieRequest::cookie("provider_name");
        if(Auth::user() != null){
            if(Auth::user()->hasRole("Administrador TIC")){
                return view('help_table::config_monitors.index');
            }
            return redirect('/login');
        }
        if($request->session()->get("is_provider")){
            $providerFullname = $request->session()->get("fullname");
            return response(view('help_table::config_monitors.index'))->cookie("provider_name",$providerFullname,60);
        }
        if(isset($cookieProviderName)){
            return response(view('help_table::config_monitors.index'))->cookie("provider_name",$cookieProviderName,60);
        }
        return redirect('/login/help-table/providers');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Kleverman Salazar Florez. - Ene. 23 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $config_monitors = ConfigMonitor::with('MonitorConfigurationsHistories')->get();
        return $this->sendResponse($config_monitors->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 23 - 2023
     * @version 1.0.0
     *
     * @param CreateConfigMonitorRequest $request
     *
     * @return Response
     */
    public function store(CreateConfigMonitorRequest $request) {

        $userLogged = Auth::user();
        $input = $request->all();

        $cookieProviderName = CookieRequest::cookie("provider_name");

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $configMonitor = $this->configMonitorRepository->create($input);

            if(isset($cookieProviderName)){
                $this->_createConfigMonitorsHistoryForProvider($configMonitor['id'],$cookieProviderName,"Creación de la marca del monitor");
            }
            else{
                $this->_createConfigMonitorsHistory($configMonitor['id'],$userLogged->id,$userLogged->name,"Creación de la marca del monitor");
            }

            $configMonitor->MonitorConfigurationsHistories;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($configMonitor->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigMonitorController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigMonitorController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar Florez. - Ene. 23 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateConfigMonitorRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        $userLogged = Auth::user();
        $input = $request->all();

        $cookieProviderName = CookieRequest::cookie("provider_name");

        /** @var ConfigMonitor $configMonitor */
        $configMonitor = $this->configMonitorRepository->find($id);

        if (empty($configMonitor)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $configMonitor = $this->configMonitorRepository->update($input, $id);

            if(isset($cookieProviderName)){
                $this->_createConfigMonitorsHistoryForProvider($configMonitor['id'],$cookieProviderName,"Modificación de la información del monitor");
            }
            else{
                $this->_createConfigMonitorsHistory($configMonitor['id'],$userLogged->id,$userLogged->name,"Modificación de la información del monitor");
            }

            $configMonitor->MonitorConfigurationsHistories;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($configMonitor->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigMonitorController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigMonitorController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ConfigMonitor del almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 23 - 2023
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var ConfigMonitor $configMonitor */
        $configMonitor = $this->configMonitorRepository->find($id);

        if (empty($configMonitor)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $configMonitor->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigMonitorController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigMonitorController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Kleverman Salazar Florez. - Ene. 23 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('config_monitors').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) {
            $monitors = ConfigMonitor::all();
            return $this->_exportDataToXlsxFile('help_table::config_monitors.exports.xlsx',$monitors,'D','Listado de los teclados.xlsx');
        }
    }

    /**
     * Exporta los datos en un archivo xlsx
     *
     * @author Kleverman Salazar Florez. - Ene. 23 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    private function _exportDataToXlsxFile(string $locationOfTheTemplate,object $data,string $finalColum, string $fileTypeName):object{
        return Excel::download(new RequestExport($locationOfTheTemplate, $data, $finalColum), $fileTypeName);
    }

    /**
     * Crea un registro para el historial de una torre
     *
     * @author Kleverman Salazar Florez. - Ene. 23 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    private function _createConfigMonitorsHistory(int $monitorId, int $userId, string $userName,string $action):void {
        ConfigMonitorHistory::create(['ht_tic_monitor_configurations_id' => $monitorId, 'user_id' => $userId, 'user_name' => $userName, 'action' => $action]);
    }

    /**
     * Crea un registro para el historial de una torre
     *
     * @author Kleverman Salazar Florez. - Feb. 01 - 2023
     * @version 1.0.0
     *
     * @param int $monitorId
     * @param string $userName
     * @param string $action
     *
     */
    private function _createConfigMonitorsHistoryForProvider(int $monitorId, string $userName,string $action):void{
        ConfigMonitorHistory::create(['ht_tic_monitor_configurations_id' => $monitorId, 'user_name' => $userName, 'action' => $action]);
    }
    public function configActives(){
        $configMonitors = ConfigMonitor::where('status', 'Activo')->get();
        return $this->sendResponse($configMonitors->toArray(), trans('data_obtained_successfully'));
    }
}
