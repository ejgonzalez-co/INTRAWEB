<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateQuotaPartsRequest;
use Modules\Workhistories\Http\Requests\UpdateQuotaPartsRequest;
use Modules\Workhistories\Repositories\QuotaPartsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;

use Modules\Workhistories\Models\QuotaPartsPensioner;
use Modules\Workhistories\Models\QuotaPartsDocPensioners;
use Modules\Workhistories\Repositories\QuotaPartsPensionerRepository;
use Modules\Workhistories\Repositories\QuotaPartsDocumentsNewsRepository;
use Illuminate\Support\Facades\Auth;
use Modules\Workhistories\Repositories\QuotaPartsNewsRepository;
use Modules\Workhistories\Repositories\QuotaPartsHistoryRepository;
use Illuminate\Support\Facades\Storage;
use Modules\Workhistories\Models\QuotaParts;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class QuotaPartsController extends AppBaseController {

    /** @var  QuotaPartsRepository */
    private $quotaPartsRepository;

    /** @var  QuotaPartsPensionerRepository */
    private $quotaPartsPensionerRepository;

    
    /** @var  QuotaPartsNewsRepository */
    private $quotaPartsNewsRepository;

    /** @var  QuotaPartsHistoryRepository */
    private $quotaPartsHistoryRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(QuotaPartsHistoryRepository $quotaPartsHistoryRepo,QuotaPartsNewsRepository $quotaPartsNewsRepo,QuotaPartsRepository $quotaPartsRepo,QuotaPartsPensionerRepository $quotaPartsPensionerRepo) {
        $this->quotaPartsRepository = $quotaPartsRepo;
        $this->quotaPartsPensionerRepository = $quotaPartsPensionerRepo;
        $this->quotaPartsNewsRepository = $quotaPartsNewsRepo;
        $this->quotaPartsHistoryRepository = $quotaPartsHistoryRepo;

    }

    /**
     * Muestra la vista para el CRUD de QuotaParts.
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
        return view('workhistories::quota_parts.index',compact(['work_historie_id','name_work_historie']));
    
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
        //$quota_parts = $this->quotaPartsRepository->all();
        //return $this->sendResponse($quota_parts->toArray(), trans('data_obtained_successfully'));

        $quota_parts_doc_pensioners = QuotaParts::where('cp_pensionados_id',$id)->latest()->get()->toArray();

        return $this->sendResponse($quota_parts_doc_pensioners, trans('data_obtained_successfully'));

    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateQuotaPartsRequest $request
     *
     * @return Response
     */
    public function store(CreateQuotaPartsRequest $request,$id) {

        $input = $request->all();
        $input["cp_pensionados_id"]=$id;
        $input["users_id"]=Auth::user()->id;
        $input["users_name"]=Auth::user()->name;
        // Valida se ingresa un adjunto
        if ($request->hasFile('url_document')) {
            $input['url_document'] = substr($input['url_document']->store('public/work_histories/PensionersQp'), 7);

            /*
            $pensioner = $this->quotaPartsPensionerRepository->find($id);
            $totalDocumentsPensionersNew = $pensioner->total_documents+1;
            $input1["total_documents"]=$totalDocumentsPensionersNew;

            if (empty($pensioner)) {
                return $this->sendError(trans('not_found_element'), 200);
            }
    
            $pensioner = $this->quotaPartsPensionerRepository->update($input1, $id);*/

       
        }
        $quotaPart = $this->quotaPartsRepository->create($input);

       // $DocumentsPensioners->configDocuments;

        
        $input['work_histories_cp_id'] = $quotaPart->id;
        // Crea un nuevo registro de historial
        $this->quotaPartsHistoryRepository->create($input);
        //Obtiene el historial
        $quotaPart->quotaPartsHistory;

        //crear novedad
        
        $inputNew["new"]="Creación de la cuota parte: ". $quotaPart->name_company;
        $inputNew["type_document"]="Cuota parte";
        $inputNew["work_histories_cp_id"]=$quotaPart->id;
        $inputNew["cp_pensionados_id"]=$id;
        $inputNew["users_id"]=Auth::user()->id;
        $inputNew["users_name"]=Auth::user()->name;

        $DocumentsPensionersNew = $this->quotaPartsNewsRepository->create($inputNew);

        
        return $this->sendResponse($quotaPart->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateQuotaPartsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQuotaPartsRequest $request) {

     
        $input = $request->all();

        $quotaPart = $this->quotaPartsRepository->find($id);

        if (empty($quotaPart)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida se ingresa un adjunto
        if ($request->hasFile('url_document')) {
            $input['url_document'] = substr($input['url_document']->store('public/work_histories/PensionersQp'), 7);
        }

        $quotaPart = $this->quotaPartsRepository->update($input, $id);

            
        $input['work_histories_cp_id'] = $quotaPart->id;
        // Crea un nuevo registro de historial
        $this->quotaPartsHistoryRepository->create($input);
        //Obtiene el historial
        $quotaPart->quotaPartsHistory;

        ///novedad
        $inputNew["new"]="Creación de la cuota parte: ". $quotaPart->name_company;
        $inputNew["type_document"]="Cuota parte";
        $inputNew["work_histories_cp_id"]=$quotaPart->id;
        $inputNew["cp_pensionados_id"]=$quotaPart->cp_pensionados_id;;
        $inputNew["users_id"]=Auth::user()->id;
        $inputNew["users_name"]=Auth::user()->name;

        $quotaPartsNews= $this->quotaPartsNewsRepository->create($inputNew);

        return $this->sendResponse($quotaPart->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un QuotaParts del almacenamiento
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


             $quotaPart = $this->quotaPartsRepository->find($id);
             if($quotaPart->url_document){
                Storage::delete("public/".$quotaPart->url_document);
     
             }
     
             if (empty($quotaPart)) {
                 return $this->sendError(trans('not_found_element'), 200);
             }
     
            ///novedad
            $inputNew["new"]="Eliminación de la cuota parte: ". $quotaPart->name_company;
            $inputNew["type_document"]="Cuota parte";
            $inputNew["work_histories_cp_id"]=$quotaPart->id;
            $inputNew["cp_pensionados_id"]=$quotaPart->cp_pensionados_id;;
            $inputNew["users_id"]=Auth::user()->id;
            $inputNew["users_name"]=Auth::user()->name;
    
            $quotaPartsNews= $this->quotaPartsNewsRepository->create($inputNew);
     
             $quotaPart->delete();
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
        $fileName = time().'-'.trans('quota_parts').'.'.$fileType;

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
