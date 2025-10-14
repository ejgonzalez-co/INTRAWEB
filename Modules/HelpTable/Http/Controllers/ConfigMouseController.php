<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\Maintenance\RequestExport;
use Modules\HelpTable\Http\Requests\CreateConfigMouseRequest;
use Modules\HelpTable\Http\Requests\UpdateConfigMouseRequest;
use Modules\HelpTable\Repositories\ConfigMouseRepository;
use Modules\HelpTable\Models\ConfigMouse;
use Modules\HelpTable\Models\ConfigMouseHistory;
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
class ConfigMouseController extends AppBaseController {

    /** @var  ConfigMouseRepository */
    private $configMouseRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Ene. 23 - 2023
     * @version 1.0.0
     */
    public function __construct(ConfigMouseRepository $configMouseRepo) {
        $this->configMouseRepository = $configMouseRepo;
    }

    /**
     * Muestra la vista para el CRUD de ConfigMouse.
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
                return view('help_table::config_mouses.index');
            }
            return redirect('/login');
        }
        if($request->session()->get("is_provider")){
            $providerFullname = $request->session()->get("fullname");
            return response(view('help_table::config_mouses.index'))->cookie("provider_name",$providerFullname,60);
        }
        if(isset($cookieProviderName)){
            return response(view('help_table::config_mouses.index'))->cookie("provider_name",$cookieProviderName,60);
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
        $config_mice = ConfigMouse::with('MouseConfigurationsHistories')->get();
        return $this->sendResponse($config_mice->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 23 - 2023
     * @version 1.0.0
     *
     * @param CreateConfigMouseRequest $request
     *
     * @return Response
     */
    public function store(CreateConfigMouseRequest $request) {

        $userLogged = Auth::user();
        $input = $request->all();

        $cookieProviderName = CookieRequest::cookie("provider_name");

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $configMouse = $this->configMouseRepository->create($input);

            if(isset($cookieProviderName)){
                $this->_createConfigMouseHistoryForProvider($configMouse['id'],$cookieProviderName,"Creación de la marca del mouse");
            }
            else{
                $this->_createConfigMouseHistory($configMouse['id'],$userLogged->id,$userLogged->name,"Creación de la marca del mouse");
            }

            $configMouse->MouseConfigurationsHistories;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($configMouse->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigMouseController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigMouseController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateConfigMouseRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        $userLogged = Auth::user();
        $input = $request->all();

        $cookieProviderName = CookieRequest::cookie("provider_name");

        /** @var ConfigMouse $configMouse */
        $configMouse = $this->configMouseRepository->find($id);

        if (empty($configMouse)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $configMouse = $this->configMouseRepository->update($input, $id);

            if(isset($cookieProviderName)){
                $this->_createConfigMouseHistoryForProvider($configMouse['id'],$cookieProviderName,"Modificación de la información del mouse");
            }
            else{
                $this->_createConfigMouseHistory($configMouse['id'],$userLogged->id,$userLogged->name,"Modificación de la información del mouse");
            }

            $configMouse->MouseConfigurationsHistories;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($configMouse->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigMouseController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigMouseController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ConfigMouse del almacenamiento
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

        /** @var ConfigMouse $configMouse */
        $configMouse = $this->configMouseRepository->find($id);

        if (empty($configMouse)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $configMouse->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigMouseController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\ConfigMouseController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('config_mice').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) {
            $mouses = ConfigMouse::all();
            return $this->_exportDataToXlsxFile('help_table::config_mouses.exports.xlsx',$mouses,'D','Listado de configuraciones de los mouses.xlsx');
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
        return Excel::download(new RequestExport($locationOfTheTemplate, JwtController::generateToken($data), $finalColum), $fileTypeName);
    }

    /**
     * Crea un registro para el historial de una torre
     *
     * @author Kleverman Salazar Florez. - Ene. 23 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    private function _createConfigMouseHistory(int $mouseId, int $userId, string $userName,string $action):void {
        ConfigMouseHistory::create(['ht_tic_mouse_configurations_id' => $mouseId, 'user_id' => $userId, 'user_name' => $userName, 'action' => $action]);
    }

    /**
     * Crea un registro para el historial de un mouse
     *
     * @author Kleverman Salazar Florez. - Feb. 01 - 2023
     * @version 1.0.0
     *
     * @param int $mouseId
     * @param string $userName
     * @param string $action
     *
     */
    private function _createConfigMouseHistoryForProvider(int $mouseId, string $userName,string $action):void{
        ConfigMouseHistory::create(['ht_tic_mouse_configurations_id' => $mouseId, 'user_name' => $userName, 'action' => $action]);
    }
}
