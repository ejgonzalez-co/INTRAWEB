<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use Modules\HelpTable\Http\Requests\CreateTicPollQuestionRequest;
use Modules\HelpTable\Http\Requests\UpdateTicPollQuestionRequest;
use Modules\HelpTable\Repositories\TicPollQuestionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class TicPollQuestionController extends AppBaseController {

    /** @var  TicPollQuestionRepository */
    private $ticPollQuestionRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TicPollQuestionRepository $ticPollQuestionRepo) {
        $this->ticPollQuestionRepository = $ticPollQuestionRepo;
    }

    /**
     * Muestra la vista para el CRUD de TicPollQuestion.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('help_table::tic_poll_questions.index');
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
        $tic_poll_questions = $this->ticPollQuestionRepository->all();
        return $this->sendResponse($tic_poll_questions->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateTicPollQuestionRequest $request
     *
     * @return Response
     */
    public function store(CreateTicPollQuestionRequest $request) {

        $input = $request->all();

        try {
            // Inserta el registro en la base de datos
            $ticPollQuestion = $this->ticPollQuestionRepository->create($input);

            return $this->sendResponse($ticPollQuestion->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicPollQuestionController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicPollQuestionController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateTicPollQuestionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicPollQuestionRequest $request) {

        $input = $request->all();

        /** @var TicPollQuestion $ticPollQuestion */
        $ticPollQuestion = $this->ticPollQuestionRepository->find($id);

        if (empty($ticPollQuestion)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Actualiza el registro
            $ticPollQuestion = $this->ticPollQuestionRepository->update($input, $id);
        
            return $this->sendResponse($ticPollQuestion->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicPollQuestionController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicPollQuestionController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un TicPollQuestion del almacenamiento
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

        /** @var TicPollQuestion $ticPollQuestion */
        $ticPollQuestion = $this->ticPollQuestionRepository->find($id);

        if (empty($ticPollQuestion)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $ticPollQuestion->delete();

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
        $fileName = time().'-'.trans('tic_poll_questions').'.'.$fileType;

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
