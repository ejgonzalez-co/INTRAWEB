<?php

namespace Modules\ContractualProcess\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ContractualProcess\Http\Requests\CreateTechnicalSheetsRequest;
use Modules\ContractualProcess\Http\Requests\UpdateTechnicalSheetsRequest;
use Modules\ContractualProcess\Repositories\TechnicalSheetsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class TechnicalSheetsController extends AppBaseController {

    /** @var  TechnicalSheetsRepository */
    private $technicalSheetsRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TechnicalSheetsRepository $technicalSheetsRepo) {
        $this->technicalSheetsRepository = $technicalSheetsRepo;
    }

    /**
     * Muestra la vista para el CRUD de TechnicalSheets.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('contractual_process::technical_sheets.index');
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
        $technical_sheets = $this->technicalSheetsRepository->all();
        return $this->sendResponse($technical_sheets->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateTechnicalSheetsRequest $request
     *
     * @return Response
     */
    public function store(CreateTechnicalSheetsRequest $request) {

        $input = $request->all();

        $technicalSheets = $this->technicalSheetsRepository->create($input);

        return $this->sendResponse($technicalSheets->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTechnicalSheetsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTechnicalSheetsRequest $request) {

        $input = $request->all();

        /** @var TechnicalSheets $technicalSheets */
        $technicalSheets = $this->technicalSheetsRepository->find($id);

        if (empty($technicalSheets)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $technicalSheets = $this->technicalSheetsRepository->update($input, $id);

        return $this->sendResponse($technicalSheets->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un TechnicalSheets del almacenamiento
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

        /** @var TechnicalSheets $technicalSheets */
        $technicalSheets = $this->technicalSheetsRepository->find($id);

        if (empty($technicalSheets)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $technicalSheets->delete();

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
        $fileName = time().'-'.trans('technical_sheets').'.'.$fileType;

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
