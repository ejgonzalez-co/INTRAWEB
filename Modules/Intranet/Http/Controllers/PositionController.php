<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Intranet\Http\Requests\CreatePositionRequest;
use Modules\Intranet\Http\Requests\UpdatePositionRequest;
use Modules\Intranet\Repositories\PositionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Illuminate\Support\Facades\Auth;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class PositionController extends AppBaseController {

    /** @var  PositionRepository */
    private $positionRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(PositionRepository $positionRepo) {
        $this->positionRepository = $positionRepo;
    }

    /**
     * Muestra la vista para el CRUD de Position.
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
            return view('intranet::positions.index');
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
        $positions = $this->positionRepository->all();
        return $this->sendResponse($positions->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreatePositionRequest $request
     *
     * @return Response
     */
    public function store(CreatePositionRequest $request) {

        $input = $request->all();

        $position = $this->positionRepository->create($input);

        return $this->sendResponse($position->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdatePositionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePositionRequest $request) {

        $input = $request->all();

        /** @var Position $position */
        $position = $this->positionRepository->find($id);

        if (empty($position)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $position = $this->positionRepository->update($input, $id);

        return $this->sendResponse($position->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un Position del almacenamiento
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

        /** @var Position $position */
        $position = $this->positionRepository->find($id);

        if (empty($position)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $position->delete();

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
        $fileName = time().'-'.trans('positions').'.'.$fileType;

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
