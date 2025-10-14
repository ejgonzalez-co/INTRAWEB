<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\Maintenance\RequestExport;
use Modules\HelpTable\Http\Requests\CreateConfigOperationSystemRequest;
use Modules\HelpTable\Http\Requests\UpdateConfigOperationSystemRequest;
use Modules\HelpTable\Models\ConfigOperationSystem;
use Modules\HelpTable\Models\ConfigOperationSystemHistory;
use Modules\HelpTable\Repositories\ConfigOperationSystemRepository;
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
 * @author Kleverman Salazar Florez. - Feb. 28 - 2023
 * @version 1.0.0
 */
class ConfigOperationSystemController extends AppBaseController {

    /** @var  ConfigOperationSystemRepository */
    private $configOperationSystemRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Feb. 28 - 2023
     * @version 1.0.0
     */
    public function __construct(ConfigOperationSystemRepository $configOperationSystemRepo) {
        $this->configOperationSystemRepository = $configOperationSystemRepo;
    }

    /**
     * Muestra la vista para el CRUD de ConfigOperationSystem.
     *
     * @author Kleverman Salazar Florez. - Feb. 28 - 2023
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
                return view('help_table::config_operation_systems.index');
            }
            return redirect('/login');
        }
        if($request->session()->get("is_provider")){
            $providerFullname = $request->session()->get("fullname");
            return response(view('help_table::config_operation_systems.index'))->cookie("provider_name",$providerFullname,60);
        }
        if(isset($cookieProviderName)){
            return response(view('help_table::config_operation_systems.index'))->cookie("provider_name",$cookieProviderName,60);
        }
        return redirect('/login/help-table/providers');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Kleverman Salazar Florez. - Feb. 28 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $config_operation_systems = ConfigOperationSystem::with("OperatingSystemConfigurationHistories")->latest()->get();
        return $this->sendResponse($config_operation_systems->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los sistemas operativos que se encuentren en el estado activo
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getOperatingSystemsActivated(){
        $config_operation_systems_activated = ConfigOperationSystem::select(["id","name"])->where("status","Activo")->orderBy("name")->get();
        return $this->sendResponse($config_operation_systems_activated->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Feb. 28 - 2023
     * @version 1.0.0
     *
     * @param CreateConfigOperationSystemRequest $request
     *
     * @return Response
     */
    public function store(CreateConfigOperationSystemRequest $request) {

        $input = $request->all();
        $userLogged = Auth::user();
        $cookieProviderName = CookieRequest::cookie("provider_name");


        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $configOperationSystem = $this->configOperationSystemRepository->create($input);

            if(isset($cookieProviderName)){
                $this->_createOperationSystemHistoryForProvider($configOperationSystem['id'],$cookieProviderName,"Creación del sistema operativo");
            }
            else{
                $this->_createOperationSystemHistory($configOperationSystem['id'],$userLogged->id,$userLogged->name,"Creación del sistema operativo");
            }

            $configOperationSystem->OperatingSystemConfigurationHistories;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($configOperationSystem->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigOperationSystemController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigOperationSystemController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar Florez. - Feb. 28 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateConfigOperationSystemRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        $input = $request->all();
        $userLogged = Auth::user();
        $cookieProviderName = CookieRequest::cookie("provider_name");

        /** @var ConfigOperationSystem $configOperationSystem */
        $configOperationSystem = $this->configOperationSystemRepository->find($id);

        if (empty($configOperationSystem)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $configOperationSystem = $this->configOperationSystemRepository->update($input, $id);

            if(isset($cookieProviderName)){
                $this->_createOperationSystemHistoryForProvider($configOperationSystem['id'],$cookieProviderName,"Modificación de la información del sistema operativo");
            }
            else{
                $this->_createOperationSystemHistory($configOperationSystem['id'],$userLogged->id,$userLogged->name,"Modificación de la información del sistema operativo");
            }

            $configOperationSystem->OperatingSystemConfigurationHistories;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($configOperationSystem->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigOperationSystemController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigOperationSystemController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ConfigOperationSystem del almacenamiento
     *
     * @author Kleverman Salazar Florez. - Feb. 28 - 2023
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var ConfigOperationSystem $configOperationSystem */
        $configOperationSystem = $this->configOperationSystemRepository->find($id);

        if (empty($configOperationSystem)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $configOperationSystem->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigOperationSystemController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigOperationSystemController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Kleverman Salazar Florez. - Feb. 28 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('config_operation_systems').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) {
            // Descarga el archivo generado
            $mouses = ConfigOperationSystem::all();
            return $this->_exportDataToXlsxFile('help_table::config_operation_systems.exports.xlsx',$mouses,'D','Listado de sistemas operativos.xlsx');
        }
    }

    /**
     * Exporta los datos en un archivo xlsx
     *
     * @author Kleverman Salazar Florez. - Feb. 28 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    private function _exportDataToXlsxFile(string $locationOfTheTemplate,object $data,string $finalColum, string $fileTypeName):object{
        return Excel::download(new RequestExport($locationOfTheTemplate, JwtController::generateToken($data), $finalColum), $fileTypeName);
    }

    /**
     * Crea un registro historico de cambios que se han hecho en un sistema operativo
     *
     * @author Kleverman Salazar Florez. - Feb. 28 - 2023
     * @version 1.0.0
     *
     * @param int $operatingSystemId id del sistema operativo
     * @param int $userId id del usuario
     * @param string $userName nombre del usuario
     * @param string $action accion que se realizo
     *
     */
    private function _createOperationSystemHistory(int $operatingSystemId,int $userId,string $userName,string $action):void{
        ConfigOperationSystemHistory::create(["ht_tic_operating_system_configuration_id" => $operatingSystemId, "user_id" => $userId, "user_name" => $userName, "action" => $action]);
    }

    /**
     * Crea un registro para el historial de un sistema operativo
     *
     * @author Kleverman Salazar Florez. - Feb. 28 - 2023
     * @version 1.0.0
     *
     * @param int $operatingSystemId
     * @param string $userName
     * @param string $action
     *
     */
    private function _createOperationSystemHistoryForProvider(int $operatingSystemId, string $userName,string $action):void{
        ConfigOperationSystemHistory::create(['ht_tic_operating_system_configuration_id' => $operatingSystemId, 'user_name' => $userName, 'action' => $action]);
    }
}
