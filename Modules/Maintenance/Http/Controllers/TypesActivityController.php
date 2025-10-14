<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateTypesActivityRequest;
use Modules\Maintenance\Http\Requests\UpdateTypesActivityRequest;
use Modules\Maintenance\Repositories\TypesActivityRepository;
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
class TypesActivityController extends AppBaseController {

    /** @var  TypesActivityRepository */
    private $typesActivityRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TypesActivityRepository $typesActivityRepo) {
        $this->typesActivityRepository = $typesActivityRepo;
    }

    /**
     * Muestra la vista para el CRUD de TypesActivity.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::types_activities.index');
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
        $types_activities = $this->typesActivityRepository->all();
        return $this->sendResponse($types_activities->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateTypesActivityRequest $request
     *
     * @return Response
     */
    public function store(CreateTypesActivityRequest $request) {

        $input = $request->all();

        $typesActivity = $this->typesActivityRepository->create($input);

        return $this->sendResponse($typesActivity->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTypesActivityRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTypesActivityRequest $request) {

        $input = $request->all();

        /** @var TypesActivity $typesActivity */
        $typesActivity = $this->typesActivityRepository->find($id);

        if (empty($typesActivity)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $typesActivity = $this->typesActivityRepository->update($input, $id);

        return $this->sendResponse($typesActivity->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un TypesActivity del almacenamiento
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

        /** @var TypesActivity $typesActivity */
        $typesActivity = $this->typesActivityRepository->find($id);

        if (empty($typesActivity)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $typesActivity->delete();

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
        $fileName = time().'-'.trans('types_activities').'.'.$fileType;

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
