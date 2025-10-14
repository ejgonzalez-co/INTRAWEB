<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Intranet\Http\Requests\CreateHeadquarterRequest;
use Modules\Intranet\Http\Requests\UpdateHeadquarterRequest;
use Modules\Intranet\Repositories\HeadquarterRepository;
use Modules\Intranet\Models\Headquarter;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class HeadquarterController extends AppBaseController {

    /** @var  HeadquarterRepository */
    private $headquarterRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(HeadquarterRepository $headquarterRepo) {
        $this->headquarterRepository = $headquarterRepo;
    }

    /**
     * Muestra la vista para el CRUD de Headquarter.
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
            return view('intranet::headquarters.index');
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
   
        $headquarters = Headquarter::select('nombre','descripcion','codigo','id')->latest()->get();

        return $this->sendResponse($headquarters->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateHeadquarterRequest $request
     *
     * @return Response
     */
    public function store(CreateHeadquarterRequest $request) {

        $input = $request->all();

        $headquarter = $this->headquarterRepository->create($input);

        return $this->sendResponse($headquarter->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateHeadquarterRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHeadquarterRequest $request) {

        $input = $request->all();

        /** @var Headquarter $headquarter */
        $headquarter = $this->headquarterRepository->find($id);

        if (empty($headquarter)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $headquarter = $this->headquarterRepository->update($input, $id);

        return $this->sendResponse($headquarter->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un Headquarter del almacenamiento
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

        /** @var Headquarter $headquarter */
        $headquarter = $this->headquarterRepository->find($id);

        if (empty($headquarter)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $headquarter->delete();

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
        $fileName = time().'-'.trans('headquarters').'.'.$fileType;

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
