<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\HelpTable\RequestExport;
use Modules\HelpTable\Http\Requests\CreateConfigTowerRequest;
use Modules\HelpTable\Http\Requests\UpdateConfigTowerRequest;
use Modules\HelpTable\Models\ConfigTower;
use Modules\HelpTable\Models\ConfigTowerHistory;
use Modules\HelpTable\Repositories\ConfigTowerRepository;
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
 * @author Kleverman Salazar Florez. - Ene. 21 - 2023
 * @version 1.0.0
 */
class ConfigTowerController extends AppBaseController {

    /** @var  ConfigTowerRepository */
    private $configTowerRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Ene. 21 - 2023
     * @version 1.0.0
     */
    public function __construct(ConfigTowerRepository $configTowerRepo) {
        $this->configTowerRepository = $configTowerRepo;
    }

    /**
     * Muestra la vista para el CRUD de ConfigTower.
     *
     * @author Kleverman Salazar Florez. - Ene. 21 - 2023
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
                return view('help_table::config_towers.index');
            }
            return redirect('/login');
        }
        if($request->session()->get("is_provider")){
            $providerFullname = $request->session()->get("fullname");
            return response(view('help_table::config_towers.index'))->cookie("provider_name",$providerFullname,60);
        }
        if(isset($cookieProviderName)){
            return response(view('help_table::config_towers.index'))->cookie("provider_name",$cookieProviderName,60);
        }
        return redirect('/login/help-table/providers');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Kleverman Salazar Florez. - Ene. 21 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $config_towers = ConfigTower::with('ConfigurationTowersHistories')->get();
        return $this->sendResponse($config_towers->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 21 - 2023
     * @version 1.0.0
     *
     * @param CreateConfigTowerRequest $request
     *
     * @return Response
     */
    public function store(CreateConfigTowerRequest $request) {

        $userLogged = Auth::user();
        $input = $request->all();

        $cookieProviderName = CookieRequest::cookie("provider_name");

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $configTower = $this->configTowerRepository->create($input);

            if(isset($cookieProviderName)){
                $this->_createConfigTowerHistoryForProvider($configTower['id'],$cookieProviderName,"Creación de la marca del teclado");
            }
            else{
                $this->_createConfigTowerHistory($configTower['id'],$userLogged->id,$userLogged->name,"Creación de la marca del teclado");
            }

            $configTower->ConfigurationTowersHistories;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($configTower->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigTowerController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigTowerController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar Florez. - Ene. 21 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateConfigTowerRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        $userLogged = Auth::user();
        $input = $request->all();

        $cookieProviderName = CookieRequest::cookie("provider_name");

        /** @var ConfigTower $configTower */
        $configTower = $this->configTowerRepository->find($id);

        if (empty($configTower)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $configTower = $this->configTowerRepository->update($input, $id);

            if(isset($cookieProviderName)){
                $this->_createConfigTowerHistoryForProvider($configTower['id'],$cookieProviderName,"Modificación de la información de la torre");
            }
            else{
                $this->_createConfigTowerHistory($configTower['id'],$userLogged->id,$userLogged->name,"Modificación de la información de la torre");
            }

            $configTower->ConfigurationTowersHistories;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($configTower->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigTowerController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigTowerController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ConfigTower del almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 21 - 2023
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var ConfigTower $configTower */
        $configTower = $this->configTowerRepository->find($id);

        if (empty($configTower)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $configTower->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigTowerController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigTowerController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Kleverman Salazar Florez. - Ene. 21 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('config_towers').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) {
            $towers = ConfigTower::all();
            return $this->_exportDataToXlsxFile('help_table::config_towers.exports.xlsx',$towers,'D','Listado de configuraciones de las torres.xlsx');
        }
    }

    /**
     * Exporta los datos en un archivo xlsx
     *
     * @author Kleverman Salazar Florez. - Ene. 21 - 2023
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
     * @author Kleverman Salazar Florez. - Ene. 21 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    private function _createConfigTowerHistory(int $towerId, int $userId, string $userName,string $action):void {
        ConfigTowerHistory::create(['ht_tic_configuration_towers_id' => $towerId, 'user_id' => $userId, 'user_name' => $userName, 'action' => $action]);
    }

    /**
     * Crea un registro para el historial de una torre
     *
     * @author Kleverman Salazar Florez. - Feb. 01 - 2023
     * @version 1.0.0
     *
     * @param int $towerId
     * @param string $userName
     * @param string $action
     *
     */
    private function _createConfigTowerHistoryForProvider(int $towerId, string $userName,string $action):void{
        ConfigTowerHistory::create(['ht_tic_configuration_towers_id' => $towerId, 'user_name' => $userName, 'action' => $action]);
    }

    public function getConfigTowersActives() {
        $configTowers = ConfigTower::where('status', 'Activo')->get();
        return $this->sendResponse($configTowers->toArray(), trans('data_obtained_successfully'));
    }
}
