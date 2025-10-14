<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use App\User;
use Modules\HelpTable\Http\Requests\CreateEquipmentProviderRequest;
use Modules\HelpTable\Http\Requests\UpdateEquipmentProviderRequest;
use Modules\HelpTable\Repositories\EquipmentProviderRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Http\Controllers\SendNotificationController;

/**
 * Descripcion de la clase
 *
 * @author Kleverman Salazar Florez. - Ene. 27 - 2023
 * @version 1.0.0
 */
class EquipmentProviderController extends AppBaseController {

    /** @var  EquipmentProviderRepository */
    private $equipmentProviderRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Ene. 27 - 2023
     * @version 1.0.0
     */
    public function __construct(EquipmentProviderRepository $equipmentProviderRepo) {
        $this->equipmentProviderRepository = $equipmentProviderRepo;
    }

    /**
     * Muestra la vista para el CRUD de EquipmentProvider.
     *
     * @author Kleverman Salazar Florez. - Ene. 27 - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('help_table::equipment_providers.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Kleverman Salazar Florez. - Ene. 27 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $equipment_providers = $this->equipmentProviderRepository->all();
        return $this->sendResponse($equipment_providers->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 27 - 2023
     * @version 1.0.0
     *
     * @param CreateEquipmentProviderRequest $request
     *
     * @return Response
     */
    public function store(CreateEquipmentProviderRequest $request) {

        $input = $request->all();

        $pin = $this->generateRandomPin(10000,100000);
        $password = $this->generateRandomPassword('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#*.$',10);

        $input['pin'] = $pin;
        $input['password'] = $password;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $equipmentProvider = $this->equipmentProviderRepository->create($input);


            $equipmentProvider['decrypted_password'] = $password;

            $this->_sendEmails($equipmentProvider['email'],$equipmentProvider,'help_table::equipment_providers.mails.provider_created' ,'Notificación de la Dirección Ti de las Empresas Públicas de Armenia (EPA)');

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($equipmentProvider->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentProviderController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentProviderController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar Florez. - Ene. 27 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateEquipmentProviderRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEquipmentProviderRequest $request) {

        $input = $request->all();

        /** @var EquipmentProvider $equipmentProvider */
        $equipmentProvider = $this->equipmentProviderRepository->find($id);

        if (empty($equipmentProvider)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $equipmentProvider = $this->equipmentProviderRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($equipmentProvider->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentProviderController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentProviderController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un EquipmentProvider del almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 27 - 2023
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var EquipmentProvider $equipmentProvider */
        $equipmentProvider = $this->equipmentProviderRepository->find($id);

        if (empty($equipmentProvider)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $equipmentProvider->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentProviderController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentProviderController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('equipment_providers').'.'.$fileType;

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
     * Genera un pin de valor aleatorio.
     *
     * @author Kleverman Salazar Florez - Ene. 27 - 2023
     * @version 1.0.0
     *
     * @param int $minimumNumber
     * @param int $maximumNumber
     *
     * @return Int
     *
     */
    public function generateRandomPin(int $minimumNumber, int $maximumNumber) : int{
        return rand($minimumNumber,$maximumNumber);
    }

    /**
     * Genera una contraseña con valor aleatorio.
     *
     * @author Kleverman Salazar Florez - Ene. 27 - 2023
     * @version 1.0.0
     *
     * @param string $allowedCharacterString
     * @param int $quantityToBeShown
     *
     * @return String
     *
     */
    public function generateRandomPassword(string $allowedCharacterString, int $quantityToBeShown) : string{
        return substr(str_shuffle($allowedCharacterString),0,$quantityToBeShown);
    }

    /**
     * Envia correos a los destinatarios.
     *
     * @author Kleverman Salazar Florez - Ene. 27 - 2023
     * @version 1.0.0
     *
     * @param string $email
     * @param object $data
     * @param string $locationTemplate
     * @param string $subjectText
     *
     */
    private static function _sendEmails(string $mail,object $data,string $locationTemplate,string $subjectText) : void{
        $custom = json_decode('{"subject":"'.$subjectText.'"}');

        // Mail::to($mail)->send(new SendMail($locationTemplate, $data, $custom));
        SendNotificationController::SendNotification($locationTemplate,$custom,$data,$mail,'Mesa de ayuda');
        
    }
}
