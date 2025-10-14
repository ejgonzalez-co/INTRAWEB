<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Intranet\Http\Requests\CreatePollQuestionRequest;
use Modules\Intranet\Http\Requests\UpdatePollQuestionRequest;
use Modules\Intranet\Repositories\PollQuestionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use Modules\Intranet\Models\Poll;
use Modules\Intranet\Models\PollQuestionOptions;
use Modules\Intranet\Models\PollQuestion;
use DB;
/**
 * Clase que controla todo lo relacionado con preguntas de encuestas de satisfaccion del modulo intranet
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class PollQuestionController extends AppBaseController {

    /** @var  PollQuestionRepository */
    private $pollQuestionRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(PollQuestionRepository $pollQuestionRepo) {
        $this->pollQuestionRepository = $pollQuestionRepo;
    }

    /**
     * Muestra la vista para el CRUD de PollQuestion.
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
            return view('intranet::poll_questions.index',compact(['poll_id','poll_title']));
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

        $poll_questions = PollQuestion::where('intranet_polls_id',$id)->with(['intranetPollsQuestionsOptions'])->latest()->get();
        return $this->sendResponse($poll_questions->toArray(), trans('data_obtained_successfully'));

    }

    public function allOrder($id) {

        $poll_questions = PollQuestion::where('intranet_polls_id',$id)->with(['intranetPollsQuestionsOptions'])->get();
        return $this->sendResponse($poll_questions->toArray(), trans('data_obtained_successfully'));

    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreatePollQuestionRequest $request
     *
     * @return Response
     */
    public function store(CreatePollQuestionRequest $request, $id) {

        $input = $request->all();
        $input["intranet_polls_id"]=$id;
        // Inicia la transaccion
        DB::beginTransaction();

        try {
            // Inserta el registro en la base de datos
            $pollQuestion = $this->pollQuestionRepository->create($input);

            // Valida si tiene lista de preguntas
            if (!empty($input['question_list'])) {

                //recorre las opciones de pregunta
                foreach($input['question_list'] as $option){

                    $questionOption = PollQuestionOptions::create([
                        'option_question' => $option,
                        'intranet_polls_questions_id' => $pollQuestion->id,
                    ]);
                    $pollQuestion->intranetPollsQuestionsOptions()->save($questionOption);

                }

            }

            $pollQuestion->intranetPollsQuestionsOptions;
            // Efectua los cambios realizados
            DB::commit();
            return $this->sendResponse($pollQuestion->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\PollQuestionController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
        catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\PollQuestionController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdatePollQuestionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePollQuestionRequest $request) {

        $input = $request->all();

        /** @var PollQuestion $pollQuestion */
        $pollQuestion = $this->pollQuestionRepository->find($id);

        if (empty($pollQuestion)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Actualiza el registro
            $pollQuestion = $this->pollQuestionRepository->update($input, $id);
            
            //valida las opciones de respuesta
            if (!empty($input['question_list'])) {

                PollQuestionOptions::where('intranet_polls_questions_id', $pollQuestion->id)->delete();

                foreach($input['question_list'] as $option){

                    $questionOption = PollQuestionOptions::create([
                        'option_question' => $option,
                        'intranet_polls_questions_id' => $pollQuestion->id,
                    ]);
                    $pollQuestion->intranetPollsQuestionsOptions()->save($questionOption);

                }

            }

            $pollQuestion->intranetPollsQuestionsOptions;

            return $this->sendResponse($pollQuestion->toArray(), trans('msg_success_update'));
        } 
        catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\PollQuestionController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
        catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\PollQuestionController - '. Auth::user()->name.' -  Error: '.$e->getMessage().$e->getMessage(). 'Linea: '.$e->getLine(). ' Id: '.($pollQuestion['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un PollQuestion del almacenamiento
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

        /** @var PollQuestion $pollQuestion */
        $pollQuestion = $this->pollQuestionRepository->find($id);

        if (empty($pollQuestion)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $pollQuestion->delete();

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
        $fileName = time().'-'.trans('poll_questions').'.'.$fileType;

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
