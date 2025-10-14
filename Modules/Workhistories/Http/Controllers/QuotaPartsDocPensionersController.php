<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateQuotaPartsDocPensionersRequest;
use Modules\Workhistories\Http\Requests\UpdateQuotaPartsDocPensionersRequest;
use Modules\Workhistories\Repositories\QuotaPartsDocPensionersRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\WorkHistories\RequestExport;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;

use Modules\Workhistories\Models\QuotaPartsPensioner;
use Modules\Workhistories\Models\QuotaPartsDocPensioners;
use Modules\Workhistories\Repositories\QuotaPartsPensionerRepository;
use Modules\Workhistories\Repositories\QuotaPartsDocumentsNewsRepository;
use Illuminate\Support\Facades\Auth;


/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class QuotaPartsDocPensionersController extends AppBaseController {

    /** @var  QuotaPartsDocPensionersRepository */
    private $quotaPartsDocPensionersRepository;

    /** @var  QuotaPartsPensionerRepository */
    private $quotaPartsPensionerRepository;

    /** @var  QuotaPartsDocumentsNewsRepository */
    private $quotaPartsDocumentsNewsRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(QuotaPartsDocumentsNewsRepository $quotaPartsDocumentsNewsRepo,QuotaPartsDocPensionersRepository $quotaPartsDocPensionersRepo,QuotaPartsPensionerRepository $quotaPartsPensionerRepo) {
        $this->quotaPartsDocPensionersRepository = $quotaPartsDocPensionersRepo;
        $this->quotaPartsPensionerRepository = $quotaPartsPensionerRepo;
        $this->quotaPartsDocumentsNewsRepository = $quotaPartsDocumentsNewsRepo;

    }

    /**
     * Muestra la vista para el CRUD de QuotaPartsDocPensioners.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $work_historie = QuotaPartsPensioner::where('id',$request->wh)->first();
        $work_historie_id = $request->wh;
        $name_work_historie =$work_historie["name"]." ".$work_historie["surname"];
        return view('workhistories::quota_parts_doc_pensioners.index',compact(['work_historie_id','name_work_historie']));
    
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

        $quota_parts_doc_pensioners = QuotaPartsDocPensioners::where('cp_pensionados_id',$id)->with(['configDocuments'])->latest()->get()->toArray();

        return $this->sendResponse($quota_parts_doc_pensioners, trans('data_obtained_successfully'));

       // $quota_parts_doc_pensioners = $this->quotaPartsDocPensionersRepository->all();
        //return $this->sendResponse($quota_parts_doc_pensioners->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateQuotaPartsDocPensionersRequest $request
     *
     * @return Response
     */
    public function store(CreateQuotaPartsDocPensionersRequest $request,$id) {
        
        $input = $request->all();
        $input["cp_pensionados_id"]=$id;
        $input["users_id"]=Auth::user()->id;
        $input["users_name"]=Auth::user()->name;
        // Valida se ingresa un adjunto
        if ($request->hasFile('url_document')) {
            $input['url_document'] = substr($input['url_document']->store('public/work_histories/DocumentsPensionersCp'), 7);

            $pensioner = $this->quotaPartsPensionerRepository->find($id);
            $totalDocumentsPensionersNew = $pensioner->total_documents+1;
            $input1["total_documents"]=$totalDocumentsPensionersNew;

            if (empty($pensioner)) {
                return $this->sendError(trans('not_found_element'), 200);
            }
    
            $pensioner = $this->quotaPartsPensionerRepository->update($input1, $id);

       
        }
        $DocumentsPensioners = $this->quotaPartsDocPensionersRepository->create($input);

        $DocumentsPensioners->configDocuments;

        //crear novedad
        
        $inputNew["new"]="Creación del documento: ". $DocumentsPensioners->configDocuments->name;
        $inputNew["type_document"]=$DocumentsPensioners->configDocuments->name;
        $inputNew["cp_p_documents_id"]=$DocumentsPensioners->id;
        $inputNew["cp_pensionados_id"]=$id;
        $inputNew["users_id"]=Auth::user()->id;
        $inputNew["users_name"]=Auth::user()->name;

        $DocumentsPensionersNew = $this->quotaPartsDocumentsNewsRepository->create($inputNew);

        
        return $this->sendResponse($DocumentsPensioners->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateQuotaPartsDocPensionersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQuotaPartsDocPensionersRequest $request) {

 
        $input = $request->all();

        /** @var DocumentsPensioners $DocumentsPensioners */
        $DocumentsPensioners = $this->quotaPartsDocPensionersRepository->find($id);

        if (empty($DocumentsPensioners)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida se ingresa un adjunto
        if ($request->hasFile('url_document')) {
            $input['url_document'] = substr($input['url_document']->store('public/work_histories/DocumentsPensionersCp'), 7);
        }

        $DocumentsPensioners = $this->quotaPartsDocPensionersRepository->update($input, $id);
        $DocumentsPensioners->configDocuments;


        ///novedad
        $inputNew["new"]="Edición del documento: ". $DocumentsPensioners->configDocuments->name;
        $inputNew["type_document"]=$DocumentsPensioners->configDocuments->name;
        $inputNew["cp_p_documents_id"]=$DocumentsPensioners->id;
        $inputNew["cp_pensionados_id"]=$DocumentsPensioners->cp_pensionados_id;
        $inputNew["users_id"]=Auth::user()->id;
        $inputNew["users_name"]=Auth::user()->name;

        $DocumentsPensionersNew = $this->quotaPartsDocumentsNewsRepository->create($inputNew);

        return $this->sendResponse($DocumentsPensioners->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un QuotaPartsDocPensioners del almacenamiento
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

        /** @var QuotaPartsDocPensioners $quotaPartsDocPensioners */
        $quotaPartsDocPensioners = $this->quotaPartsDocPensionersRepository->find($id);

        if (empty($quotaPartsDocPensioners)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $quotaPartsDocPensioners->delete();

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
        $fileName = time().'-'.trans('quota_parts_doc_pensioners').'.'.$fileType;

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
     * Genera el reporte de Documentos pensionado de calculo
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
        $fileName = date('Y-m-d H:i:s').'-'.trans('quota-parts-doc-pensioners').'.'.$fileType;
        
        return Excel::download(new RequestExport('workhistories::quota_parts_doc_pensioners.report_excel', $input['data'], 'd'), $fileName);
    }
}
