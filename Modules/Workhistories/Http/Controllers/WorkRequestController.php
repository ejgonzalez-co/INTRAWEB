<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateWorkRequestRequest;
use Modules\Workhistories\Http\Requests\UpdateWorkRequestRequest;
use Modules\Workhistories\Repositories\WorkRequestRepository;
use Modules\Workhistories\Models\WorkHistoriesActive;
use Modules\Workhistories\Models\WorkHistPensioner;
use Modules\Workhistories\Models\WorkRequest;
use App\User;
use Modules\Workhistories\Models\WorkRequestHistory;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
/**
 * Descripcion de la clase
 *
 * @author Nicolas Dario Ortiz Peña. - Oct. 20 - 2021
 * @version 1.0.0
 */
class WorkRequestController extends AppBaseController {

    /** @var  WorkRequestRepository */
    private $workRequestRepository;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Oct. 20 - 2021
     * @version 1.0.0
     */
    public function __construct(WorkRequestRepository $workRequestRepo) {
        $this->workRequestRepository = $workRequestRepo;
    }

    /**
     * Muestra la vista para el CRUD de WorkRequest.
     *
     * @author Nicolas Dario Ortiz Peña. - Oct. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('workhistories::work_requests.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Nicolas Dario Ortiz Peña. - Oct. 20 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {

        //Si el usuario en sesion es administrador puede observar todas las solicitudes
        if(Auth::user()->hasRole('Administrador historias laborales')){
            $work_requests = WorkRequest::with(['users'])->latest()->get();
            return $this->sendResponse($work_requests->toArray(), trans('data_obtained_successfully'));

        }else{
            //Si el usuario en sesion es gestor puede ver solo sus solicitudes
            if(Auth::user()->hasRole('Gestor hojas de vida')){
                $user=Auth::user();
                $work_requests = WorkRequest::with(['users'])->where('users_id', $user->id)->latest()->get();
                return $this->sendResponse($work_requests->toArray(), trans('data_obtained_successfully'));
            }
        }
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Oct. 20 - 2021
     * @version 1.0.0
     *
     * @param CreateWorkRequestRequest $request
     *
     * @return Response
     */
    public function store(CreateWorkRequestRequest $request) {

        $user=Auth::user();

        $input = $request->all();
        //Reasigna los valores del input cuando se va crear un registro con los campos de cada registro
        $input['users_id']=$user->id;
        if($input['option']=="1"){
            $input['work_histories_id']=$input['user_id'];
            $input['state']="Activo";
        }else{
            $input['work_histories_p_id']=$input['user_id'];
            $input['state']="Pensionado";
        }
        $input['user_id']=$input['user_id'];
        $input['user_name']=$input['ciudadano']['name'].' '.$input['ciudadano']['surname'].' - '.$input['ciudadano']['dependencias_name'];
        $input['consultation_time']=$input['consultation_time'];
        $input['reason_consultation']=$input['reason_consultation'];
        $input['condition']="Pendiente";
        $input['document_user']=$input['ciudadano']['number_document'];
        $input['create_user']=$user->name;
        $input['dependencia_user_create']=$user->dependencies->id;
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $workRequest = $this->workRequestRepository->create($input);
            $workRequest->users;
            // Efectua los cambios realizados
            DB::commit();

             //Busca los usuarios administradores
            $users = User::role('Administrador historias laborales')->get();

            foreach ($users as  $value) {

                $value->history= $workRequest;

                //Asunto del correo
                $custom = json_decode('{"subject": "Notificación de solicitud de hoja de vida."}');

                // Envia notificacion al usuario asignado
                Mail::to($value)->send(new SendMail('workhistories::work_requests.email.email_request_admin', $value, $custom));

            }


            return $this->sendResponse($workRequest->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Workhistories\Http\Controllers\WorkRequestController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Workhistories\Http\Controllers\WorkRequestController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }

    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Nicolas Dario Ortiz Peña. - Oct. 20 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateWorkRequestRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWorkRequestRequest $request) {

        $input = $request->all();

        /** @var WorkRequest $workRequest */
        $workRequest = $this->workRequestRepository->find($id);

        if (empty($workRequest)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $workRequest = $this->workRequestRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($workRequest->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Workhistories\Http\Controllers\WorkRequestController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Workhistories\Http\Controllers\WorkRequestController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un WorkRequest del almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Oct. 20 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var WorkRequest $workRequest */
        $workRequest = $this->workRequestRepository->find($id);

        if (empty($workRequest)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $workRequest->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Workhistories\Http\Controllers\WorkRequestController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Workhistories\Http\Controllers\WorkRequestController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Nicolas Dario Ortiz Peña. - Oct. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('work_requests').'.'.$fileType;

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
     * Organiza la exportacion de datos
     *
     * @author Nicolas Dario Ortiz Peña. - Oct. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function getUserWork(Request $request) {
        $query =$request->input('query');
        $work = WorkHistoriesActive::where('number_document','like','%'.$query.'%')->latest()->get();
        return $this->sendResponse($work->toArray(), trans('data_obtained_successfully'));
    }

            /**
     * Organiza la exportacion de datos
     *
     * @author Nicolas Dario Ortiz Peña. - Oct. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function getUserPensionary(Request $request) {
        $query =$request->input('query');
        $work = WorkHistPensioner::where('number_document','like','%'.$query.'%')->latest()->get();
        return $this->sendResponse($work->toArray(), trans('data_obtained_successfully'));
    }

                /**
     *Cancelacion de orden
     *
     * @author Nicolas Dario Ortiz Peña. - Oct. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function cancelRequest(Request $request) {
        //LLama la solicitud
        $reques=WorkRequest::where('id', $request['data']['id'])->get();
        //Cambia su condicion a cancelado
        $reques[0]->condition="Cancelado";
        //Se le asigna la respuesta
        $reques[0]->answer=$request['answer'];
        $reques[0]->save();

        $user=Auth::user();
        //Crea el historial
            $history=new WorkRequestHistory();
            //Asigna usuarios aprobado
            $history->user_aprobed=$user->name;
            //Asigna el id de la hoja de vida
            $history->work_request_cv_id=$reques[0]->id;
            //Verifica si es pensionado
            if( $reques[0]->state=="Pensionado"){
                $history->work_histories_p_id=$reques[0]->work_histories_p_id;
                $history->work_histories_p_users_id=$reques[0]->work_histories_p_users_id;
            }else{
                //Si no es usuario activo
                $history->work_histories_id=$reques[0]->work_histories_id;
            }
            // EN el historial se guarda la  respuesta y el estado cancelado
            $history->observation=$request['answer'];
            $history->condition='Cancelado';
            $history->save();


             //Busca el usuario que creo la solicitud en este caso el administrador
            $user = User::find($reques[0]->users_id);
            $user->history=$reques[0];



            //Asunto del correo
            $custom = json_decode('{"subject": "Notificación rechazo de solicitud de hoja de vida."}');

            // Envia notificacion al usuario asignado
            Mail::to($user)->send(new SendMail('workhistories::work_requests.email.email_request_deny', $user, $custom));

        return $this->sendResponse($reques[0], trans('data_obtained_successfully'));
    }

                    /**
     *Aprobada de orden
     *
     * @author Nicolas Dario Ortiz Peña. - Oct. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function approbRequest(Request $request) {
        //Verifica que la fecha inicial sea menor que la fecha final
        if($request['date_start'] < $request['date_final']){
            //Busca la solicitud
            $reques=WorkRequest::where('id', $request['data']['id'])->get();
            //Se le asignan los campos entrantes a los atributos
            $reques[0]->condition="Aprobado";
            $reques[0]->answer=$request['answer'];
            $reques[0]->date_start=$request['date_start'];
            $reques[0]->date_final=$request['date_final'];
            $reques[0]->save();

            $user=Auth::user();
            //Se crea el historial para el nuevo estado
            $history=new WorkRequestHistory();
            //Se le asignan los atributos al historial
            $history->user_aprobed=$user->name;
            $history->work_request_cv_id=$reques[0]->id;
            //Verifica que sea pensionado
            if( $reques[0]->state=="Pensionado"){
                $history->work_histories_p_id=$reques[0]->work_histories_p_id;
                $history->work_histories_p_users_id=$reques[0]->work_histories_p_users_id;
            }else{
                //Si no es pensionado es un usuario activo
                $history->work_histories_id=$reques[0]->work_histories_id;
            }
            $history->observation=$request['answer'];
            $history->condition='Aprobado';
            //Guarda el historial
            $history->save();

            //Busca el usuario que creo la solicitud en este caso el administrador
            $user = User::find($reques[0]->users_id);
            $user->history=$reques[0];
               //Asunto del correo
            $custom = json_decode('{"subject": "Notificación de solicitud de hoja de vida."}');

               // Envia notificacion al usuario asignado
               Mail::to($user)->send(new SendMail('workhistories::work_requests.email.email_request_accept', $user, $custom));

            return $this->sendResponse($reques[0], trans('data_obtained_successfully'));
        }else{

            return $this->sendResponse('error', trans('data_obtained_successfully'));
        }
    }


            /**
     * Cambia el estado de una solicitud cuando esta activo
     *
     * @author Nicolas Dario Ortiz Peña - Oct. 22 - 2021
     * @version 1.0.0
     */
    public function changeCondition() {
        //Busca la solicitud que este en estado aprobado o en ejecucion
        $workRequest=WorkRequest::with(['workHistories', 'workHistoriesP', 'users'])->where('condition', 'Aprobado')->orwhere('condition', 'En ejecución')->get();
        //Recupera la hora y fecha actual
        $date = date('Y-m-d H:i:s');
        //Filtra por las solicitudes que esten en condicion aprobado
        $workRequestAprobado=$workRequest->where('condition', 'Aprobado');
        //se recorre el arreglo de las solicitudes que han sido aprobadas
        foreach ($workRequestAprobado as $value) {
            //Si las solicitudes cumplen esta condicion cambian de estado a en ejecucion
            if($value['date_start']< $date &&  $value['date_final'] > $date){
                $value->condition='En ejecución';

                $value->save();
            }
        }

        //Se filtra por solicitudes en estado en ejecucion
        $workRequestEjecucion=$workRequest->where('condition', 'En ejecución');
        //Se recorre el arreglo de solicitudes en ejecucion
        foreach ($workRequestEjecucion as $value) {
            //Si cumple esta condicion cambia de estado a finalizado
            if($value['date_final'] < $date){
                $value->condition='Finalizado';

                $value->save();
            }
        }

    }
}
