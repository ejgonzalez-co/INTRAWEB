<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateDocumentsProviderContractRequest;
use Modules\Maintenance\Http\Requests\UpdateDocumentsProviderContractRequest;
use Modules\Maintenance\Repositories\DocumentsProviderContractRepository;
use Modules\Maintenance\Models\BudgetAllocationProvider;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use App\Http\Controllers\JwtController;
use Modules\Maintenance\Models\Providers;
use Modules\Maintenance\Models\DocumentsProviderContract;
use Modules\Maintenance\Models\ProviderContract;
/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class DocumentsProviderContractController extends AppBaseController {

    /** @var  DocumentsProviderContractRepository */
    private $documentsProviderContractRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(DocumentsProviderContractRepository $documentsProviderContractRepo) {
        $this->documentsProviderContractRepository = $documentsProviderContractRepo;
    }

    /**
     * Muestra la vista para el CRUD de DocumentsProviderContract.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {        
        $name_provider=null;
        $provider = ProviderContract::where('id',$request->mpc)->first();
        $provider_id = $request->mpc;
        
        if($provider != null){
            $name_provider = $provider->providers->name;
        }

        
        return view('maintenance::documents_provider_contracts.index',compact(['provider_id','name_provider']));
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
        $documents_provider_contracts = DocumentsProviderContract::where('mant_provider_contract_id', $id)->with(['providerContract'])->latest()->get()->toArray();
        return $this->sendResponse($documents_provider_contracts, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateDocumentsProviderContractRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentsProviderContractRequest $request, $id) {
        
        $input = $request->all();
        $input["mant_provider_contract_id"] = $id;
        $input['url_document'] = implode(",", $input["url_document"]);

        $documentsProviderContract = $this->documentsProviderContractRepository->create($input);

        // Ejecuta el modelo del proveedor
        $documentsProviderContract->providerContract;

        return $this->sendResponse($documentsProviderContract->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateDocumentsProviderContractRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentsProviderContractRequest $request) {

        $input = $request->all();

        /** @var DocumentsProviderContract $documentsProviderContract */
        $documentsProviderContract = $this->documentsProviderContractRepository->find($id);

        if (empty($documentsProviderContract)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $input['url_document'] = implode(",", $input["url_document"]);

        $documentsProviderContract = $this->documentsProviderContractRepository->update($input, $id);

        // Ejecuta el modelo del proveedor
        $documentsProviderContract->mantProviders;

        return $this->sendResponse($documentsProviderContract->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un DocumentsProviderContract del almacenamiento
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

        /** @var DocumentsProviderContract $documentsProviderContract */
        $documentsProviderContract = $this->documentsProviderContractRepository->find($id);

        if (empty($documentsProviderContract)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $documentsProviderContract->delete();

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

        $data = JwtController::decodeToken($input['data']);

        array_walk($data, fn(&$object) => $object = (array) $object);


        foreach ($data as &$item) {
            if (isset($item['provider_contract'])) {
                unset($item['provider_contract']);
                unset($item['url_document']);
                unset($item['mant_provider_contract_id']);
            }
        };

        $input['data'] = JwtController::generateToken($data);


        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];

        
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('documents_provider_contracts').'.'.$fileType;

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
