<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateConfigurationDocumentsRequest;
use Modules\Workhistories\Http\Requests\UpdateConfigurationDocumentsRequest;
use Modules\Workhistories\Repositories\ConfigurationDocumentsRepository;
use Modules\Workhistories\Models\ConfigurationDocuments;

use App\Http\Controllers\AppBaseController;
use App\Exports\WorkHistories\RequestExport;
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
class ConfigurationDocumentsController extends AppBaseController {

    /** @var  ConfigurationDocumentsRepository */
    private $configurationDocumentsRepository;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     */
    public function __construct(ConfigurationDocumentsRepository $configurationDocumentsRepo) {
        $this->configurationDocumentsRepository = $configurationDocumentsRepo;
    }

    /**
     * Muestra la vista para el CRUD de ConfigurationDocuments.
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('workhistories::configuration_documents.index');
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
        $configuration_documents = $this->configurationDocumentsRepository->all();
        return $this->sendResponse($configuration_documents->toArray(), trans('data_obtained_successfully'));
    }

    
    public function getConfigurationDocumentsActive() {
        $documents = ConfigurationDocuments::where('state',1)->latest()->get()->toArray();
        return $this->sendResponse($documents, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param CreateConfigurationDocumentsRequest $request
     *
     * @return Response
     */
    public function store(CreateConfigurationDocumentsRequest $request) {

        $input = $request->all();
        $input["users_id"]=Auth::user()->id;

        $configurationDocuments = $this->configurationDocumentsRepository->create($input);

        return $this->sendResponse($configurationDocuments->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateConfigurationDocumentsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateConfigurationDocumentsRequest $request) {

        $input = $request->all();

        /** @var ConfigurationDocuments $configurationDocuments */
        $configurationDocuments = $this->configurationDocumentsRepository->find($id);

        if (empty($configurationDocuments)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $configurationDocuments = $this->configurationDocumentsRepository->update($input, $id);

        return $this->sendResponse($configurationDocuments->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un ConfigurationDocuments del almacenamiento
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

        /** @var ConfigurationDocuments $configurationDocuments */
        $configurationDocuments = $this->configurationDocumentsRepository->find($id);

        if (empty($configurationDocuments)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $configurationDocuments->delete();

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
        $fileName = time().'-'.trans('configuration_documents').'.'.$fileType;

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
     * Genera el reporte de pensionados Cuotas partes en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - Jun. 02 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('configuration-documents').'.'.$fileType;
        
        return Excel::download(new RequestExport('workhistories::configuration_documents.report_excel', $input['data'], 'd'), $fileName);
    }
}
