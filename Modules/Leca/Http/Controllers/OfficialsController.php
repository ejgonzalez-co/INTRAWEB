<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Leca\Http\Requests\CreateOfficialsRequest;
use Modules\Leca\Http\Requests\UpdateOfficialsRequest;
use Modules\Leca\Repositories\OfficialsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Flash;
use Modules\Leca\Models\Officials;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use App\Http\Controllers\SendNotificationController;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class OfficialsController extends AppBaseController {

    /** @var  OfficialsRepository */
    private $officialsRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(OfficialsRepository $officialsRepo) {
        $this->officialsRepository = $officialsRepo;
    }

    /**
     * Muestra la vista para el CRUD de Officials.
     *
     * @author José Manuel Marín Londoño. - Nov. 17 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('leca::officials.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Nov. 17 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $officials = $this->officialsRepository->all();
        return $this->sendResponse($officials->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author José Manuel Marín Londoño. - Nov. 17 - 2021
     * @version 1.0.0
     *
     * @param CreateOfficialsRequest $request
     *
     * @return Response
     */
    public function store(CreateOfficialsRequest $request) {

        $user = Auth::user();
        $input = $request->all();
        if ($request->hasFile('firm')) {
            $file = $request->file('firm');
            $name = time().$file->getClientOriginalName();
            $file = $name;
            $url = explode(".", $file);

            $input['firm'] = substr($input['firm']->store('public/documents/documents_firms'), 7);

        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            //Guarda el campo users_id del usuarios que esta en sesion
            $input['users_id'] = Auth::user()->id;
            $input['state'] = 1;
            $input['receptionist'] = "No";

            //Carácteres para la contraseña
            $chain = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
            $chain .= '0123456789' ;
            $chain .= '.';
            //Se obtiene la longitud de la cadena de caracteres
            $chainLength=strlen($chain);
            //Se define la varibale que va a tener la contraseña
            $password = "";
            //Se define la longitud de la contraseña, en este caso son 10, pero se pueden poner lo que se necesite
            $lengthPassword = 10;

            //Se empieza a crear la contraseña
            for($i = 1; $i <= $lengthPassword ; $i++) {
                //Definimos un numero aleatorio entre 0 y la longitud de la cadena de caracteres -1
                $position=rand(0,$chainLength-1);
                //obtenemos un caracter aleatorio escogido de la cadena de caracteres
                $password .= substr($chain,$position,1);
            }
            $input['password'] = $password;
            $pin = "";

            //Se crea un valor aleatorio de 6 digitos
            $pinRand = rand(1000,999999);
            //Se crea la variable
            $pin = date("Y")."-LC-".$pinRand;
            $input['pin'] = $pin;
            
            // Inserta el registro en la base de datos
            $officials = $this->officialsRepository->create($input);

            //Recupera el correo que se ingreso al cual se le enviara el correo
            $mailToSend = $input['email'];
            $custom = json_decode('{"subject": "LECA (EPA)"}');
            //Envia notificacion al usuario asignado
            // Mail::to($mailToSend)->send(new SendMail('leca::officials.email.email_to_officials_leca', $officials, $custom));
            SendNotificationController::SendNotification('leca::officials.email.email_to_officials_leca',$custom,$officials,$mailToSend,'Leca');


            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($officials->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\OfficialsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\OfficialsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateOfficialsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOfficialsRequest $request) {

        $input = $request->all();

        /** @var Officials $officials */
        $officials = $this->officialsRepository->find($id);

        if (empty($officials)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $officials = $this->officialsRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($officials->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\OfficialsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\OfficialsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un Officials del almacenamiento
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

        /** @var Officials $officials */
        $officials = $this->officialsRepository->find($id);

        if (empty($officials)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $officials->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\OfficialsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\OfficialsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('officials').'.'.$fileType;

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
     * Funcion para Inactivar Funcionario
     * @author José Manuel Marín Londoño. - Nov. 18 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function inactivateOfficial(Request $request)
    {
        $input = $request->toArray();

        //Recupera el id del funcionario para acttualizarlo
        $idOfficials = $input['id'];
        $officials = Officials::find($idOfficials);
        $officials->state = 2;
        $officials->save();

        return $this->sendResponse($officials->toArray(), trans('msg_success_update'));
    }

    /**
     * Funcion para activar Funcionario
     * @author José Manuel Marín Londoño. - Nov. 18 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function activateOfficial(Request $request)
    {
        $input = $request->toArray();
        //Recupera el id del funcionario para acttualizarlo
        $idOfficials = $input['id'];
        $officials = Officials::find($idOfficials);
        $officials->state = 1;
        $officials->save();

        return $this->sendResponse($officials->toArray(), trans('msg_success_update'));
    }

    /**
     * Funcion para Habilitar al recepcionista
     * @author José Manuel Marín Londoño. - Nov. 18 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function enableReceptionist(Request $request)
    {
        $input = $request->toArray();
        //Recupera el id del funcionario para acttualizarlo
        $idOfficials = $input['id'];
        $officials = Officials::find($idOfficials);
        $officials->receptionist = "Si";
        $officials->save();

        return $this->sendResponse($officials->toArray(), trans('msg_success_update'));
    }

    /**
     * Funcion para inhabilitar al recepcionista
     * @author José Manuel Marín Londoño. - Nov. 18 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function disableReceptionist(Request $request)
    {
        $input = $request->toArray();
        //Recupera el id del funcionario para acttualizarlo
        $idOfficials = $input['id'];
        $officials = Officials::find($idOfficials);
        $officials->receptionist = "No";
        $officials->save();

        return $this->sendResponse($officials->toArray(), trans('msg_success_update'));
    }
}
