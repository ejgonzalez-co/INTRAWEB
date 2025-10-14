<?php

namespace Modules\ContractualProcess\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ContractualProcess\Http\Requests\CreatePcPreviousStudiesHistoryRequest;
use Modules\ContractualProcess\Http\Requests\UpdatePcPreviousStudiesHistoryRequest;
use Modules\ContractualProcess\Repositories\PcPreviousStudiesHistoryRepository;
use Modules\ContractualProcess\Models\PcPreviousStudiesHistory;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;

/**
 * DEscripcion de la clase
 *
 * @author Erika Johana Gonzalez. Marzo. 01 - 2021
 * @version 1.0.0
 */
class PcPreviousStudiesHistoryController extends AppBaseController {

    /** @var  PcPreviousStudiesHistoryRepository */
    private $pcPreviousStudiesHistoryRepository;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez. Marzo. 01 - 2021
     * @version 1.0.0
     */
    public function __construct(PcPreviousStudiesHistoryRepository $pcPreviousStudiesHistoryRepo) {
        $this->pcPreviousStudiesHistoryRepository = $pcPreviousStudiesHistoryRepo;
    }

    /**
     * Muestra la vista para el CRUD de PcPreviousStudiesHistory.
     *
     * @author Erika Johana Gonzalez. Marzo. 01 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $previousStudiesId =$request->pc;
        return view('contractual_process::pc_previous_studies_history.index',compact(['previousStudiesId']));

    }


    /**
     * Obtiene todos los elementos existentes
     *
     * @author Erika Johana Gonzalez. Marzo. 01 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all($id) {

        $pc_previous_studies_history = PcPreviousStudiesHistory::with(['pcPreviousStudiesTipificationsH'])->where('pc_previous_studies_id',$id)->latest()->get()->toArray();
        return $this->sendResponse($pc_previous_studies_history, trans('data_obtained_successfully'));
    }
    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Erika Johana Gonzalez. Marzo. 01 - 2021
     * @version 1.0.0
     *
     * @param CreatePcPreviousStudiesHistoryRequest $request
     *
     * @return Response
     */
    public function store(CreatePcPreviousStudiesHistoryRequest $request,$id) {

        $input = $request->all();

        $pcPreviousStudiesHistory = $this->pcPreviousStudiesHistoryRepository->create($input);

        return $this->sendResponse($pcPreviousStudiesHistory->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Erika Johana Gonzalez. Marzo. 01 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdatePcPreviousStudiesHistoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePcPreviousStudiesHistoryRequest $request) {

        $input = $request->all();

        /** @var PcPreviousStudiesHistory $pcPreviousStudiesHistory */
        $pcPreviousStudiesHistory = $this->pcPreviousStudiesHistoryRepository->find($id);

        if (empty($pcPreviousStudiesHistory)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $pcPreviousStudiesHistory = $this->pcPreviousStudiesHistoryRepository->update($input, $id);

        return $this->sendResponse($pcPreviousStudiesHistory->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un PcPreviousStudiesHistory del almacenamiento
     *
     * @author Erika Johana Gonzalez. Marzo. 01 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var PcPreviousStudiesHistory $pcPreviousStudiesHistory */
        $pcPreviousStudiesHistory = $this->pcPreviousStudiesHistoryRepository->find($id);

        if (empty($pcPreviousStudiesHistory)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $pcPreviousStudiesHistory->delete();

        return $this->sendSuccess(trans('msg_success_drop'));

    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Erika Johana Gonzalez. Marzo. 01 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('pc_previous_studies_histories').'.'.$fileType;

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
