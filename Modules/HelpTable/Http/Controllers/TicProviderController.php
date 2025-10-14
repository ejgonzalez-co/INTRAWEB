<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use App\Mail\VerifyMail;
use Modules\Intranet\Http\Controllers\UtilController;
use Modules\HelpTable\Http\Requests\CreateTicProviderRequest;
use Modules\HelpTable\Http\Requests\UpdateTicProviderRequest;
use Modules\HelpTable\Repositories\TicProviderRepository;
use Modules\Intranet\Repositories\UserRepository;
use Modules\Intranet\Repositories\UserHistoryRepository;
use Modules\HelpTable\Models\TicProvider;
use App\User;
use App\Http\Controllers\AppBaseController;
use App\Notifications\UserEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use App\Exports\RequestExport;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DateTime;
use DB;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class TicProviderController extends AppBaseController {

    /** @var  UserRepository */
    private $userRepository;
    /** @var  UserHistoryRepository */
    private $userHistoryRepository;
/** @var  TicProviderRepository */
    private $ticProviderRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(
        UserHistoryRepository $userHistoryRepo,
        UserRepository $userRepo,
        TicProviderRepository $ticProviderRepo) {
        $this->userHistoryRepository = $userHistoryRepo;
        $this->userRepository = $userRepo;
        $this->ticProviderRepository = $ticProviderRepo;
    }

    /**
     * Muestra la vista para el CRUD de TicProvider.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador TIC"])){
            return view('help_table::tic_providers.index');
        }
        return view("auth.forbidden");
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
        $tic_providers = TicProvider::
        with(['users'])
        ->latest()
        ->whereHas('users', function($query) {
            $query->where('deleted_at', '=', null);
        })
        ->get()
        ->map(function($item, $key){
            
            if ($item->users) {
                $item->email     = $item->users->email;
                $item->name      =  $item->users->name;
                $item->block     =  $item->users->block;
                $item->sendEmail =  $item->users->sendEmail;
            }
            return $item;
        });
        return $this->sendResponse($tic_providers->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateTicProviderRequest $request
     *
     * @return Response
     */
    public function store(CreateTicProviderRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();

        try {
            $input['contract_start'] = explode(" ",$input['contract_start']);
            $input['contract_start'] = $input['contract_start'][0];

            $input['contract_end'] = explode(" ",$input['contract_end']);
            $input['contract_end'] = $input['contract_end'][0];
            //Se valida que la fecha de fin del contrato no sea anterior a la de inicio
            $start = new DateTime($input['contract_start']);
            $end = new DateTime($input['contract_end']);

            if ($end < $start) {
                return $this->sendSuccess('La fecha de fin del contrato debe de ser mayor a la fecha inicial', 'error');
            }

            // Organiza campos booleanos
            $input['block'] = $this->toBoolean($input['block']);
            $input['sendEmail'] = $this->toBoolean($input['sendEmail']);
            // Encripta la contrasena
            $input['password'] = Hash::make($input['password']);

            // Valida si se ingresa la imagen de perfil
            if ($request->hasFile('url_img_profile')) {
                $input['url_img_profile'] = substr($input['url_img_profile']->store('public/users/avatar'), 7);
            } else {
                // Establece imagen de perfil por defecto
                $input['url_img_profile'] = 'users/avatar/default.png';
            }

            // Agrega el id del usuario que crea el registro
            $input['cf_user_id'] = Auth::id();
            
            // Al ser proveedor acepta los terminos de servicios
            $input['accept_service_terms'] = true;

            // Crea un nuevo usuario
            $user = $this->userRepository->create($input);

            $user->assignRole('Proveedor TIC');

            // Registro de evento de registro de usuario
            event(new Registered($user));

            // Envia correo de confirmacion de la cuenta
            // Mail::to($user->email)->send(new VerifyMail($user));

            // Obtiene el historial de cambios del usuario
            $user->usersHistory;

          
            // Agrega el id del usuario que crea el registro
            $input['users_id'] = $user->id;

            // Inserta el registro en la base de datos
            $ticProvider = $this->ticProviderRepository->create($input);
            $ticProvider->users;

            $ticProvider->name      =  $ticProvider->users->name;
            $ticProvider->block     =  $ticProvider->users->block;
            $ticProvider->sendEmail =  $ticProvider->users->sendEmail;
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicProviderController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicProviderController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
        // Efectua los cambios realizados
        DB::commit();

        return $this->sendResponse($ticProvider->toArray(), trans('msg_success_save'));
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTicProviderRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicProviderRequest $request) {
        
      
        $input = $request->all();
        /** @var TicProvider $ticProvider */
        $ticProvider = $this->ticProviderRepository->find($id);
        
            //Se valida que la fecha de fin del contrato no sea anterior a la de inicio
            $start = new DateTime($input['contract_start']);
            $end = new DateTime($input['contract_end']);

            if ($end < $start) {
                return $this->sendSuccess('La fecha de fin del contrato debe de ser mayor a la fecha inicial', 'error');
            }

        /* * Actualiza el estado de bloqueo de un usuario en función del valor del estado.
*/
        // Verificar si el valor de 'state' es 2 (activo)
    if ($request['state'] == 2) {
        // Si el estado es 2, se activa el bloqueo del usuario (block = 1)
        $stateProvider['block'] = 1;
        // Realizar la actualización en el repositorio de usuarios
        $updateState = $this->userRepository->update($stateProvider, $request['users_id']);
        
    
    // Verificar si el valor de 'state' es 1 (desbloqueo)
    } else if ($request['state'] == 1) {
        // Si el estado es 1, se desactiva el bloqueo del usuario (block = 0)
        $stateProvider['block'] = 0;
        // Realizar la actualización en el repositorio de usuarios
        $updateState = $this->userRepository->update($stateProvider, $request['users_id']);
    }


        if (empty($ticProvider)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida si se debe actualizar la contrasena
        if (!empty($input['password'])) {
            $request->validate([
                'password' => 'required|confirmed|min:6'
            ]);
            // Encripta la contrasena
            $input['password'] = Hash::make($input['password']);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        
        try {
            //Quitar las horas de los campos del contrato
            $oldUser = $this->userRepository->find($ticProvider->users_id);

            // Organiza campos booleanos
            $input['block']       = $this->toBoolean($input['block']);
            $input['sendEmail']   = $this->toBoolean($input['sendEmail']);

            
            // Valida si se ingresa la imagen de perfil
            if ($request->hasFile('url_img_profile')) {
                $input['url_img_profile'] = substr($input['url_img_profile']->store('public/users/avatar'), 7);
            }

            // Actualiza datos de usuario
            $user = $this->userRepository->update($input, $ticProvider->users_id);

            // Prepara los datos de usuario para comparar
            $arrDataUser = Arr::dot(UtilController::dropNullEmptyList($user->replicate()->toArray(), true)); // Datos actuales de usuario
            $arrDataOldUser = Arr::dot(UtilController::dropNullEmptyList($oldUser->replicate()->toArray(), true)); // Datos antiguos de usuario

            // // Datos diferenciados
            $arrDiff = array_diff_assoc($arrDataUser, $arrDataOldUser);

            // Valida si los datos antiguos son diferentes a los actuales
            if ( count($arrDiff) > 0) {
                // Lista diferencial sin la notacion punto
                $arrDiffUndot = UtilController::arrayUndot($arrDiff);
                // Asigna el id del usuario para el registro del historial
                $input['users_id'] = $user->id;
                // Agrega el id del usuario que crea el registro
                $input['cf_user_id'] = Auth::id();

                // Crea un nuevo registro de historial
                $this->userHistoryRepository->create($input);
            }

            // Obtiene el historial de cambios del usuario
            $user->usersHistory;

            // Actualiza el registro
            $ticProvider = $this->ticProviderRepository->update($input, $id);
            $ticProvider->users;

            $ticProvider->name      =  $ticProvider->users->name;
            $ticProvider->block     =  $ticProvider->users->block;
            $ticProvider->sendEmail =  $ticProvider->users->sendEmail;
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicProviderController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicProviderController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
        // Efectua los cambios realizados
        DB::commit();

        return $this->sendResponse($ticProvider->toArray(), trans('msg_success_update'));
    }

    /**
     * Elimina un TicProvider del almacenamiento
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

        /** @var TicProvider $ticProvider */
        $ticProvider = $this->ticProviderRepository->find($id);

        if (empty($ticProvider)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $ticProvider->delete();

        return $this->sendSuccess(trans('msg_success_drop'));
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    // public function export(Request $request) {
    //     $input = $request->all();

    //     // Tipo de archivo (extencion)
    //     $fileType = $input['fileType'];
    //     // Nombre de archivo con tiempo de creacion
    //     $fileName = time().'-'.trans('tic_providers').'.'.$fileType;

    //     // Valida si el tipo de archivo es pdf
    //     if (strcmp($fileType, 'pdf') == 0) {
    //         // Guarda el archivo pdf en ubicacion temporal
    //         // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

    //         // Descarga el archivo generado
    //         return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
    //     } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
    //         // Guarda el archivo excel en ubicacion temporal
    //         // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

    //         // Descarga el archivo generado
    //         return Excel::download(new GenericExport($input['data']), $fileName);
    //     }
    // }

    /**
     * Genera el reporte de proveedores tic en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - May. 31 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('tic_providers').'.'.$fileType;
        
        return Excel::download(new RequestExport('help_table::tic_providers.report_excel', $input['data'], 'i'), $fileName);
    }
}
