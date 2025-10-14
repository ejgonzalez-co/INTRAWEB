<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Intranet\Http\Requests\CreatePollAnswerRequest;
use Modules\Intranet\Http\Requests\UpdatePollAnswerRequest;
use Modules\Intranet\Repositories\PollAnswerRepository;
use Modules\Intranet\Repositories\PollRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use Modules\Intranet\Models\Poll;
use Modules\Intranet\Models\PollAnswer;
use Modules\Intranet\Models\PollQuestion;
use App\Exports\Intranet\Poll\RequestExport;
use App\Exports\Intranet\Poll\RequestExportQuestions;


use DB;

/**
 * Clase que controla todo lo relacionado con respuestas de  encuestas de satisfaccion del modulo intranet
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class PollAnswerController extends AppBaseController {

    /** @var  PollAnswerRepository */
    private $pollAnswerRepository;
      /** @var  PollRepository */
      private $pollRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(PollAnswerRepository $pollAnswerRepo,PollRepository $pollRepo) {
        $this->pollAnswerRepository = $pollAnswerRepo;
        $this->pollRepository = $pollRepo;

    }

    /**
     * Muestra la vista para el CRUD de PollAnswer.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador intranet","Administrador intranet de descargas","Administrador intranet de eventos","Administrador intranet de encuestas"])){
            $poll_id = $request->poll;
            $poll = Poll::where('id',$poll_id)->first();
            $poll_title = $poll->title;
            return view('intranet::poll_answers.index',compact(['poll_id','poll_title']));
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
    public function all($id) {
        $poll_answers = PollAnswer::with("intranetPollsQuestions")->where('intranet_polls_id',$id)->latest()->get();
        return $this->sendResponse($poll_answers->toArray(), trans('data_obtained_successfully'));

    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreatePollAnswerRequest $request
     *
     * @return Response
     */
    public function store(CreatePollAnswerRequest $request) {

        $input = $request->all();
        $idPoll = $input["id"];
        unset($input["id"]);
        // Inicia la transaccion
        DB::beginTransaction();
        try {

            //se recorren las respuesta que llegan desde el componente PollAnswer
            foreach ($input as $key => $value) {
                //se quita la palabra question y queda el id de la pregunta para asociar la respuesta
                $idQuestion = str_replace("question", "", $key);
                //se asignan los datos
                $dataAnswerUser["intranet_polls_questions_id"] = $idQuestion;
                $dataAnswerUser["users_id"] = Auth::user()->id;
                $dataAnswerUser["intranet_polls_id"] = $idPoll;
                $dataAnswerUser["users_name"] = Auth::user()->name;
                $dataAnswerUser["answer"] = $value;
                $pollAnswer = $this->pollAnswerRepository->create($dataAnswerUser);

            }

            $poll = $this->pollRepository->find($idPoll);
            // Efectua los cambios realizados
            DB::commit();
            // Inserta el registro en la base de datos
            return $this->sendResponse($poll->toArray(), trans('Encuesta enviada satisfactoriamente! ¡Gracias por ayudarnos a mejorar! Tu opinión nos importa'), "success");

            //return $this->sendResponse($pollAnswer->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\PollAnswerController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
        catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\PollAnswerController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdatePollAnswerRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePollAnswerRequest $request) {

        $input = $request->all();

        $pollAnswer = $this->pollAnswerRepository->find($id);

        if (empty($pollAnswer)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $pollAnswer = $this->pollAnswerRepository->update($input, $id);
            // Efectua los cambios realizados
            DB::commit();
            return $this->sendResponse($pollAnswer->toArray(), trans('msg_success_update'));
        } 
        catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\PollAnswerController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
        catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\PollAnswerController - '. Auth::user()->name.' -  Error: '.$e->getMessage(). 'Linea: '.$e->getLine(). ' Id: '.($pollAnswer['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un PollAnswer del almacenamiento
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

        /** @var PollAnswer $pollAnswer */
        $pollAnswer = $this->pollAnswerRepository->find($id);

        if (empty($pollAnswer)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $pollAnswer->delete();

        return $this->sendSuccess(trans('msg_success_drop'));
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Erika Johana Gonzalez C. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export($id) {
       
        $poll_question = PollQuestion::with("intranetPollsAnswers")->where('intranet_polls_id',$id)->latest()->get()->toArray();
        // dd( $poll_question );

        $fileName = date('Y-m-d H:i:s').'-'.trans('poll_answers').'.xlsx';
        
        return Excel::download(new RequestExport('intranet::poll_answers.report_excel', $poll_question), $fileName);
    }

        /**
     * Organiza la exportacion de datos
     *
     * @author Erika Johana Gonzalez C. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function exportQuestions($id) {
       
        $poll_question = PollQuestion::with("intranetPollsAnswers")->where('intranet_polls_id',$id)->latest()->get()->toArray();
        // dd( $poll_question );

        $fileName = date('Y-m-d H:i:s').'-'.trans('poll_answers').'.xlsx';
        
        return Excel::download(new RequestExportQuestions('intranet::poll_answers.report_excel_questions', $poll_question), $fileName);
    }
}
