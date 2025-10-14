<?php

namespace Modules\CitizenPoll\Http\Controllers;

use App\Exports\GenericExport;
use Modules\CitizenPoll\Http\Requests\CreatePollsRequest;
use Modules\CitizenPoll\Http\Requests\UpdatePollsRequest;
use Modules\CitizenPoll\Repositories\PollsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\CitizenPoll\RequestExport;
use Maatwebsite\Excel\Facades\Excel;
use Flash;
use Response;

/**
 * Descripcion de la clase
 *
 * @author Andres Stiven Pinzon. - Abr. 30 - 2021
 * @version 1.0.0
 */
class PollsController extends AppBaseController {

    /** @var  PollsRepository */
    private $pollsRepository;

    /**
     * Constructor de la clase
     *
     * @author Andres Stiven Pinzon G. - Abr. 30 - 2020
     * @version 1.0.0
     */
    public function __construct(PollsRepository $pollsRepo) {
        $this->pollsRepository = $pollsRepo;
    }

    /**
     * Muestra la vista para el CRUD de Polls.
     *
     * @author Andres Stiven Pinzon G. - Abr. 30 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('citizen_poll::polls.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Andres Stiven Pinzon G. - Abr. 30 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $polls = $this->pollsRepository->all();
        return $this->sendResponse($polls->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Andres Stiven Pinzon G. - Abr. 30 - 2020
     * @version 1.0.0
     *
     * @param CreatePollsRequest $request
     *
     * @return Response
     */
    public function store(CreatePollsRequest $request) {

        $input = $request->all();
        //dd($input);

        $polls = $this->pollsRepository->create($input);
        
        return $this->sendResponse($polls->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Andres Stiven Pinzon G. - Abr. 30 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdatePollsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePollsRequest $request) {

        $input = $request->all();

        /** @var Polls $polls */
        $polls = $this->pollsRepository->find($id);

        if (empty($polls)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $polls = $this->pollsRepository->update($input, $id);

        return $this->sendResponse($polls->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un Polls del almacenamiento
     *
     * @author Andres Stiven Pinzon G. - Abr. 30 - 2020
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var Polls $polls */
        $polls = $this->pollsRepository->find($id);

        if (empty($polls)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $polls->delete();

        return $this->sendSuccess(trans('msg_success_drop'));

    }


    /**
     * Genera el reporte de encuestas en hoja de calculo
     *
     * @author José Manuel Marín Londoño. - Dic. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('polls').'.'.$fileType;
        
        return Excel::download(new RequestExport('citizen_poll::polls.report_excel', $input['data']), $fileName);
    }
}