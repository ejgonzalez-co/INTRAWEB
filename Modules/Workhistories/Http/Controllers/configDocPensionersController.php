<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateconfigDocPensionersRequest;
use Modules\Workhistories\Http\Requests\UpdateconfigDocPensionersRequest;
use Modules\Workhistories\Repositories\configDocPensionersRepository;
use Modules\Workhistories\Models\configDocPensioners;
use App\Exports\WorkHistories\RequestExport;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Illuminate\Support\Facades\Auth;
/**
 * DEscripcion de la clase
 *
 * @author Erika Johana Gonzalez C. Oct.  22 - 2020
 * @version 1.0.0
 */
class configDocPensionersController extends AppBaseController {

    /** @var  configDocPensionersRepository */
    private $configDocPensionersRepository;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     */
    public function __construct(configDocPensionersRepository $configDocPensionersRepo) {
        $this->configDocPensionersRepository = $configDocPensionersRepo;
    }

    /**
     * Muestra la vista para el CRUD de configDocPensioners.
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('workhistories::config_doc_pensioners.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $configuration_documents = $this->configDocPensionersRepository->all();
        return $this->sendResponse($configuration_documents->toArray(), trans('data_obtained_successfully'));
    }

    
    public function getconfigDocPensionersActive() {
        $documents = configDocPensioners::where('state',1)->latest()->get()->toArray();
        return $this->sendResponse($documents, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param CreateconfigDocPensionersRequest $request
     *
     * @return Response
     */
    public function store(CreateconfigDocPensionersRequest $request) {

        $input = $request->all();
        $input["users_id"]=Auth::user()->id;

        $configDocPensioners = $this->configDocPensionersRepository->create($input);

        return $this->sendResponse($configDocPensioners->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateconfigDocPensionersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateconfigDocPensionersRequest $request) {

        $input = $request->all();

        /** @var configDocPensioners $configDocPensioners */
        $configDocPensioners = $this->configDocPensionersRepository->find($id);

        if (empty($configDocPensioners)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $configDocPensioners = $this->configDocPensionersRepository->update($input, $id);

        return $this->sendResponse($configDocPensioners->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un configDocPensioners del almacenamiento
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var configDocPensioners $configDocPensioners */
        $configDocPensioners = $this->configDocPensionersRepository->find($id);

        if (empty($configDocPensioners)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $configDocPensioners->delete();

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
    /*public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('config_doc_pensioners').'.'.$fileType;

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

    }*/

    /**
     * Genera el reporte de configuración documentos pensionados en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - jun. 1 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('config_doc_pensioners').'.'.$fileType;
        
        return Excel::download(new RequestExport('workhistories::config_doc_pensioners.report_excel', $input['data'], 'd'), $fileName);
    }
}
