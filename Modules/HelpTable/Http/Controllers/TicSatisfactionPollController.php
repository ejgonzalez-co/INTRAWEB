<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use Modules\HelpTable\Http\Requests\CreateTicSatisfactionPollRequest;
use Modules\HelpTable\Http\Requests\UpdateTicSatisfactionPollRequest;
use Modules\HelpTable\Repositories\TicSatisfactionPollRepository;
use Modules\HelpTable\Repositories\TicRequestRepository;
use Modules\HelpTable\Models\TicSatisfactionPoll;
use Modules\HelpTable\Models\TicPollQuestion;
use Modules\HelpTable\Models\TicRequest;
use Modules\HelpTable\Models\TicRequestHistory;
use Modules\HelpTable\Models\TicRequestStatus;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Modules\HelpTable\Http\Controllers\UtilController;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class TicSatisfactionPollController extends AppBaseController {

    /** @var  TicSatisfactionPollRepository */
    private $ticSatisfactionPollRepository;

    /** @var  TicRequestRepository */
    private $ticRequestRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(
        TicSatisfactionPollRepository $ticSatisfactionPollRepo,
        TicRequestRepository $ticRequestsRepo
        ) {
        $this->ticSatisfactionPollRepository = $ticSatisfactionPollRepo;
        $this->ticRequestRepository = $ticRequestsRepo;
    }

    /**
     * Muestra la vista para el CRUD de TicSatisfactionPoll.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('tic_satisfaction_polls.index');
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
        $tic_satisfaction_polls = $this->ticSatisfactionPollRepository->all();
        return $this->sendResponse($tic_satisfaction_polls->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateTicSatisfactionPollRequest $request
     *
     * @return Response
     */
    public function store(CreateTicSatisfactionPollRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();

        try {
            // Obtiene la fecha actual cuando se registro las respuestas
            $currentDate =  date('Y-m-d H:i:s');
            // Asigna el id del usuario logueado
            $input['users_id'] = Auth::user()->id;
            // Obtiene la solicitud a la cual se va a realizar la encuesta
            $ticRequest = TicRequest::find($input['id']);
            // Obtiene el estado de cerrado de las solicitudes
            $ticRequestStatus = TicRequestStatus::where('id', 5)->first();
            // Asigna los datos del estado de la solicitud
            $input['ht_tic_request_status_id']  =  $ticRequestStatus->id;
            $input['request_status']            =  $ticRequestStatus->name;

            $input['ht_tic_requests_id']        = $ticRequest->id;
            // Asigna el siguimiento automatico
            $input['tracing'] = "Encuesta contestada por el usuario";

            // Recorre las respuesta de la encuesta realizada por el usuario
            foreach ($input['question'] as $key => $question) {

                $reply = explode("_", $question);
                // Obtiene la informacion de la encuesta
                $ticPollQuestions = TicPollQuestion::find($reply[1]);
                // Agrega contenido al siguimiento automatico
                // $input['tracing'] .= $ticPollQuestions->question.":<b> ".$reply[0]."</b><br>";
                // Inserta el registro de la encuesta de satisfaccion
                TicSatisfactionPoll::create([
                    'question'           => $ticPollQuestions->question,
                    'reply'              => $reply[0],
                    'users_id'           => $input['users_id'],
                    'functionary_id'     => $ticRequest->assigned_by_id,
                    'ht_tic_requests_id' => $input['ht_tic_requests_id'],
                ]);
            }

            // Actualiza los datos de la solicitud
            $ticRequest = $this->ticRequestRepository->update([
                'request_status'           => $input['request_status'] ,
                'ht_tic_request_status_id' => $input['ht_tic_request_status_id'],
                'survey_status'  => $this->getObjectOfList(config('help_table.tic_poll_status'), 'name', 'Encuesta realizada')->id,
            ], $input['id']);

            $ticRequest->users;
            $ticRequest->users->dependencies = $ticRequest->users->dependencies;
            $ticRequest->ticTypeRequest;
            $ticRequest->ticRequestStatus;
            $ticRequest->ticRequestHistories;
            $ticRequest->ticSatisfactionPolls;
            $ticRequest = UtilController::stateTimeline($ticRequest);
            
            // Inserta el registro en el historial de la solicitud
            TicRequestHistory::create($input);
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicSatisfactionPollController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicSatisfactionPollController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }

        // Efectua los cambios realizados
        DB::commit();

        return $this->sendResponse($ticRequest->toArray(), trans('msg_success_save'));
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTicSatisfactionPollRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicSatisfactionPollRequest $request) {

        $input = $request->all();

        /** @var TicSatisfactionPoll $ticSatisfactionPoll */
        $ticSatisfactionPoll = $this->ticSatisfactionPollRepository->find($id);

        if (empty($ticSatisfactionPoll)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Actualiza el registro
            $ticSatisfactionPoll = $this->ticSatisfactionPollRepository->update($input, $id);
        
            return $this->sendResponse($ticSatisfactionPoll->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicSatisfactionPollController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicSatisfactionPollController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un TicSatisfactionPoll del almacenamiento
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

        /** @var TicSatisfactionPoll $ticSatisfactionPoll */
        $ticSatisfactionPoll = $this->ticSatisfactionPollRepository->find($id);

        if (empty($ticSatisfactionPoll)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $ticSatisfactionPoll->delete();

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
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('tic_satisfaction_polls').'.'.$fileType;

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
