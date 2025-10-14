<?php

namespace Modules\Configuracion\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Configuracion\Http\Requests\CreateConfigurationGeneralRequest;
use Modules\Configuracion\Http\Requests\UpdateConfigurationGeneralRequest;
use Modules\Configuracion\Repositories\ConfigurationGeneralRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Configuracion\Models\ConfigurationGeneral;
use Illuminate\Support\Facades\Storage;
use Modules\Intranet\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Notifications\Messages\MailMessage;
use App\Http\Controllers\SendNotificationController;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ConfigurationGeneralController extends AppBaseController {

    /** @var  ConfigurationGeneralRepository */
    private $configurationGeneralRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ConfigurationGeneralRepository $configurationGeneralRepo) {
        $this->configurationGeneralRepository = $configurationGeneralRepo;
    }

    /**
     * Muestra la vista para el CRUD de ConfigurationGeneral.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $client_email = ConfigurationGeneral::select("correo_cliente")->first()->correo_cliente;
        if(Auth::user()->hasRole("Administrador intranet")){
            return view('configuracion::configuration_generals.index',compact("client_email"));
        }
        return view("auth.forbidden");
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
         $configuration_generals = $this->configurationGeneralRepository->all();
        return $this->sendResponse($configuration_generals->toArray(), trans('data_obtained_successfully'));
    }

    public function allModules() {

        $modulos = DB::table('configuration_modules')->get();

        return $this->sendResponse($modulos->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Notifica si el espacio de almacenamiento esta en sus limites
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function notifyStorageSpace(){
        $percentageExecutedSpace = $this->percentageExecutedSpace();
        $custom = json_decode('{"subject": "Notificación del almacenamiento del sitio '.env("APP_NAME").'"}');
        $client_email = ConfigurationGeneral::first()->correo_cliente;
        $notificationInformation = [];
        $notificationInformation["percentage"] = floor($percentageExecutedSpace);
        
        if(($percentageExecutedSpace >= 70 && $percentageExecutedSpace <= 71) || ($percentageExecutedSpace >= 80 && $percentageExecutedSpace <= 81) || ($percentageExecutedSpace >= 90 && $percentageExecutedSpace <= 91) ){
            SendNotificationController::SendNotification('configuracion::configuration_generals.mails.notification_storage_space',$custom,$notificationInformation,[$client_email,env("SOPORTE_EMAIL")],'Configuración');

            echo("Se enviaron los correos satisfactoriamente.");
        }
    }

    public function percentageExecutedSpace() : float{
        $storageQuantityUsed = $this->_getAmountFileStorageConsumed() + $this->_getAmountDatabaseStorageConsumed();
        $storageCapacityLimit = $this->_getStorageCapacityLimit();
        $percentageExecutedSpace = ($storageQuantityUsed * 100) / $storageCapacityLimit;
        return $percentageExecutedSpace;
    }

    public function getUserAndStorageCapacity(){
        $userAndStorageCapacityInformation = [];
        $userAndStorageCapacityInformation["number_users"] = $this->_getEmployees();
        $userAndStorageCapacityInformation["limit_number_users"] = $this->_getLimitNumberEmployees();
        $userAndStorageCapacityInformation["storage_limit_quantity"] = $this->_getStorageCapacityLimit();
        $userAndStorageCapacityInformation["storage_limit_quantity_in_gb"] = number_format($userAndStorageCapacityInformation["storage_limit_quantity"] / 1000,2). " GB";
        $userAndStorageCapacityInformation["storage_quantity_used"] = $this->_getAmountFileStorageConsumed() + $this->_getAmountDatabaseStorageConsumed();
        $userAndStorageCapacityInformation["storage_quantity_used_in_gb"] = $userAndStorageCapacityInformation["storage_quantity_used"] >= 1000 ? number_format($userAndStorageCapacityInformation["storage_quantity_used"] / 1000,2)." GB" : number_format($userAndStorageCapacityInformation["storage_quantity_used"],2). " MB" ;
        $userAndStorageCapacityInformation["storage_progress_used"] = number_format($this->percentageExecutedSpace(),2);

        return $this->sendResponse($userAndStorageCapacityInformation, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene la cantidad de funcionarios
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @return int
     */
    private function _getEmployees(): int {
        $availableUsers = User::whereNotNull('id_cargo')->count();
        return $availableUsers;
    }

    /**
     * Obtiene la cantidad limite de funcionarios
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @return int
     */
    private function _getLimitNumberEmployees(): int {
        $userCapacity = ConfigurationGeneral::select("limite_usuarios")->first()->limite_usuarios;
        return $userCapacity;
    }

    /**
     * Obtiene la cantidad limite que puede ocupar el sitio con la base de datos y adjuntos
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @return int
     */
    private function _getStorageCapacityLimit(): int{
        $storageCapacityLimit = ConfigurationGeneral::select("limite_almacenamiento")->first()->limite_almacenamiento;
        return $storageCapacityLimit;
    }

    /**
     * Obtiene la cantidad consumida en los adjuntos del storage
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @return float
     */
    private function _getAmountFileStorageConsumed(): float {
        $rutaCarpeta = 'public';

        // Obtiene el tamaño de la carpeta en bytes
        $archivos = Storage::disk('local')->allFiles($rutaCarpeta);

        $pesoTotal = 0;
        foreach ($archivos as $archivo) {
            $pesoTotal += Storage::disk('local')->size($archivo);
          }

        // Convierte el tamaño a megabytes
        $pesoMb = ($pesoTotal / 1024) / 1024;

        return $pesoMb;
    }

    /**
     * Obtiene la cantidad consumida en la base de datos
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @return float
     */
    private function _getAmountDatabaseStorageConsumed() : float{
        $totalSize = DB::table('information_schema.tables')
        ->where('table_schema', env("DB_DATABASE"))
        ->select(DB::raw('SUM(ROUND(data_length + index_length) / 1024 / 1024) AS total_size_mb'))
        ->first();

        return (float) $totalSize->total_size_mb;
    }


    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateConfigurationGeneralRequest $request
     *
     * @return Response
     */
    public function store(CreateConfigurationGeneralRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            if ($request->hasFile('logo')) {
                $input['logo'] = substr($input['logo']->store('public/configuracion/logo'), 7);
            }
            if ($request->hasFile('imagen_fondo')) {
                $input['imagen_fondo'] = substr($input['imagen_fondo']->store('public/configuracion/fondo'), 7);
            }

            if ($request->hasFile('imagen_fondo_responsive')) {
                $input['imagen_fondo_responsive'] = substr($input['imagen_fondo_responsive']->store('public/configuracion/fondo'), 7);
            }
            // Inserta el registro en la base de datos
            $configurationGeneral = $this->configurationGeneralRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($configurationGeneral->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\ConfigurationGeneralController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\ConfigurationGeneralController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateConfigurationGeneralRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateConfigurationGeneralRequest $request) {

        $input = $request->all();

        /** @var ConfigurationGeneral $configurationGeneral */
        $configurationGeneral = $this->configurationGeneralRepository->find($id);

        if (empty($configurationGeneral)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        // try {

            if ($request->hasFile('logo')) {
                // Se crea una instancia de la clase principal para enviarla como parámetro y usar las funciones no estáticas
                $app_base_controller = new AppBaseController();
                $input["logo"] = AppBaseController::uploadFile($input['logo'],'public/configuracion/logo', $app_base_controller);
                if(is_null($input["logo"])){
                    return $this->sendSuccess("La capacidad de almacenamiento ha llegado a su limite, por favor comuniquese con soporte@intraweb.com.co", 'info');
                }
            }
            if ($request->hasFile('imagen_fondo')) {
                $input['imagen_fondo'] = substr($input['imagen_fondo']->store('public/configuracion/fondo'), 7);
            }
            if ($request->hasFile('imagen_fondo_responsive')) {
                $input['imagen_fondo_responsive'] = substr($input['imagen_fondo_responsive']->store('public/configuracion/fondo'), 7);
            }
            // Actualiza el registro
            $configurationGeneral = $this->configurationGeneralRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($configurationGeneral->toArray(), trans('msg_success_update'));
        // } catch (\Illuminate\Database\QueryException $error) {
        //     // Devuelve los cambios realizados
        //     DB::rollback();
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\ConfigurationGeneralController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
        //     // Retorna mensaje de error de base de datos
        //     return $this->sendSuccess(config('constants.support_message'), 'info');
        // } catch (\Exception $e) {
        //     // Devuelve los cambios realizados
        //     DB::rollback();
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\ConfigurationGeneralController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
        //     // Retorna error de tipo logico
        //     return $this->sendSuccess(config('constants.support_message'), 'info');
        // }
    }

    /**
     * Elimina un ConfigurationGeneral del almacenamiento
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

        /** @var ConfigurationGeneral $configurationGeneral */
        $configurationGeneral = $this->configurationGeneralRepository->find($id);

        if (empty($configurationGeneral)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $configurationGeneral->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\ConfigurationGeneralController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\ConfigurationGeneralController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
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
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('configuration_generals').'.'.$fileType;

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
