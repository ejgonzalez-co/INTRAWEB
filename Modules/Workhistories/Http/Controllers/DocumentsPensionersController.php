<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateDocumentsPensionersRequest;
use Modules\Workhistories\Http\Requests\UpdateDocumentsPensionersRequest;
use Modules\Workhistories\Repositories\DocumentsPensionersRepository;
use Modules\Workhistories\Models\DocumentsPensioners;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\WorkHistories\RequestExport;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Modules\Workhistories\Models\WorkHistPensioner;
use Illuminate\Support\Facades\Storage;
use Modules\Workhistories\Repositories\WorkHistPensionerRepository;
use Modules\Workhistories\Repositories\DocumentsPensionersNewsRepository;
use Illuminate\Support\Facades\Auth;
/**
 * DEscripcion de la clase
 *
 * @author Erika Johana Gonzalez C. Oct.  22 - 2020
 * @version 1.0.0
 */
class DocumentsPensionersController extends AppBaseController {

    /** @var  DocumentsPensionersRepository */
    private $DocumentsPensionersRepository;

    /** @var  WorkHistPensionerRepository */
    private $WorkHistPensionerRepository;

    /** @var  DocumentsPensionersNewsRepository */
    private $DocumentsPensionersNewsRepository;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     */
    public function __construct(DocumentsPensionersNewsRepository $DocumentsPensionersNewsRepo, WorkHistPensionerRepository $WorkHistPensionerRepo,DocumentsPensionersRepository $DocumentsPensionersRepo) {
        $this->DocumentsPensionersRepository = $DocumentsPensionersRepo;
        $this->WorkHistPensionerRepository = $WorkHistPensionerRepo;
        $this->DocumentsPensionersNewsRepository = $DocumentsPensionersNewsRepo;


    }

    /**
     * Muestra la vista para el CRUD de DocumentsPensioners.
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $work_historie = WorkHistPensioner::where('id',$request->wh)->first();
        $work_historie_id = $request->wh;
        $name_work_historie =$work_historie["name"]." ".$work_historie["surname"];
        return view('workhistories::documents_pensioners.index',compact(['work_historie_id','name_work_historie']));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all($id) {
        //$DocumentsPensioners = DocumentsPensioners::where('work_histories_p_id',$id)->with(['workHistoriesPConfigDocuments'])->latest()->get()->toArray();
        
        $DocumentsPensioners = DocumentsPensioners::where('work_histories_p_id',$id)->with(['workHistoriesPConfigDocuments'])->latest()->get()->toArray();

        return $this->sendResponse($DocumentsPensioners, trans('data_obtained_successfully'));
    }


    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param CreateDocumentsPensionersRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentsPensionersRequest $request,$id) {
        //dd($id);

        $input = $request->all();
        $input["work_histories_p_id"]=$id;
        $input["users_id"]=Auth::user()->id;
        $input["users_name"]=Auth::user()->name;
        // Valida se ingresa un adjunto
        if ($request->hasFile('url_document')) {
            $input['url_document'] = substr($input['url_document']->store('public/work_histories/DocumentsPensioners'), 7);

            $WorkHistPensioner = $this->WorkHistPensionerRepository->find($id);
            $totalDocumentsPensionersNew = $WorkHistPensioner->total_documents+1;
            $input1["total_documents"]=$totalDocumentsPensionersNew;

            if (empty($WorkHistPensioner)) {
                return $this->sendError(trans('not_found_element'), 200);
            }
    
            $WorkHistPensioner = $this->WorkHistPensionerRepository->update($input1, $id);

       
        }
        $DocumentsPensioners = $this->DocumentsPensionersRepository->create($input);

        $DocumentsPensioners->workHistoriesPConfigDocuments;

        //crear novedad
        
        $inputNew["new"]="Creación del documento: ". $DocumentsPensioners->workHistoriesPConfigDocuments->name;
        $inputNew["type_document"]=$DocumentsPensioners->workHistoriesPConfigDocuments->name;
        $inputNew["documents_id"]=$DocumentsPensioners->id;
        $inputNew["work_histories_p_id"]=$id;
        $inputNew["users_id"]=Auth::user()->id;
        $inputNew["users_name"]=Auth::user()->name;

        $DocumentsPensionersNew = $this->DocumentsPensionersNewsRepository->create($inputNew);

        
        return $this->sendResponse($DocumentsPensioners->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateDocumentsPensionersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentsPensionersRequest $request) {

        $input = $request->all();

        /** @var DocumentsPensioners $DocumentsPensioners */
        $DocumentsPensioners = $this->DocumentsPensionersRepository->find($id);

        if (empty($DocumentsPensioners)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida se ingresa un adjunto
        if ($request->hasFile('url_document')) {
            $input['url_document'] = substr($input['url_document']->store('public/work_histories/DocumentsPensioners'), 7);
        }

        $DocumentsPensioners = $this->DocumentsPensionersRepository->update($input, $id);
        $DocumentsPensioners->workHistoriesPConfigDocuments;


        ///novedad
        $inputNew["new"]="Edición del documento: ". $DocumentsPensioners->workHistoriesPConfigDocuments->name;
        $inputNew["type_document"]=$DocumentsPensioners->workHistoriesPConfigDocuments->name;
        $inputNew["documents_id"]=$DocumentsPensioners->id;
        $inputNew["work_histories_p_id"]=$DocumentsPensioners->work_histories_p_id;
        $inputNew["users_id"]=Auth::user()->id;
        $inputNew["users_name"]=Auth::user()->name;

        $DocumentsPensionersNew = $this->DocumentsPensionersNewsRepository->create($inputNew);

        return $this->sendResponse($DocumentsPensioners->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un DocumentsPensioners del almacenamiento
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

        /** @var DocumentsPensioners $DocumentsPensioners */
        $DocumentsPensioners = $this->DocumentsPensionersRepository->find($id);
        $DocumentsPensioners->workHistoriesPConfigDocuments;
        if($DocumentsPensioners->url_document){
           Storage::delete("public/".$DocumentsPensioners->url_document);

        }

        if (empty($DocumentsPensioners)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $WorkHistPensioner = $this->WorkHistPensionerRepository->find($DocumentsPensioners->work_histories_p_id);
        $totalDocumentsPensionersNew = $WorkHistPensioner->total_documents-1;
       // dd($totalDocumentsPensionersNew);

        $input1["total_documents"]=$totalDocumentsPensionersNew;

        if (empty($WorkHistPensioner)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $WorkHistPensioner = $this->WorkHistPensionerRepository->update($input1, $DocumentsPensioners->work_histories_p_id);
   
        ///novedad
        $inputNew["new"]="Eliminación del documento: ". $DocumentsPensioners->workHistoriesPConfigDocuments->name;
        $inputNew["type_document"]=$DocumentsPensioners->workHistoriesPConfigDocuments->name;
        $inputNew["documents_id"]=$DocumentsPensioners->id;
        $inputNew["work_histories_p_id"]=$DocumentsPensioners->work_histories_p_id;
        $inputNew["users_id"]=Auth::user()->id;
        $inputNew["users_name"]=Auth::user()->name;

        $DocumentsPensionersNew = $this->DocumentsPensionersNewsRepository->create($inputNew);

        $DocumentsPensioners->delete();
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
        $fileName = time().'-'.trans('DocumentsPensioners').'.'.$fileType;

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
     * Genera el reporte de documentos Historia laboral pensionado en hoja de calculo
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
        $fileName = date('Y-m-d H:i:s').'-'.trans('documents_pensioners').'.'.$fileType;
        
        return Excel::download(new RequestExport('workhistories::documents_pensioners.report_excel', $input['data'], 'e'), $fileName);
    }
}
