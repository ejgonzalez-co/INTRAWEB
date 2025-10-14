<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateDocumentsRequest;
use Modules\Workhistories\Http\Requests\UpdateDocumentsRequest;
use Modules\Workhistories\Repositories\DocumentsRepository;
use Modules\Workhistories\Models\Documents;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\WorkHistories\RequestExport;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Modules\Workhistories\Models\WorkHistoriesActive;
use Illuminate\Support\Facades\Storage;
use Modules\Workhistories\Repositories\WorkHistoriesActiveRepository;
use Modules\Workhistories\Repositories\DocumentsNewsRepository;
use Illuminate\Support\Facades\Auth;
/**
 * DEscripcion de la clase
 *
 * @author Erika Johana Gonzalez C. Oct.  22 - 2020
 * @version 1.0.0
 */
class DocumentsController extends AppBaseController {

    /** @var  DocumentsRepository */
    private $documentsRepository;

    /** @var  WorkHistoriesActiveRepository */
    private $workHistoriesActiveRepository;

    /** @var  DocumentsNewsRepository */
    private $documentsNewsRepository;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     */
    public function __construct(DocumentsNewsRepository $documentsNewsRepo, WorkHistoriesActiveRepository $workHistoriesActiveRepo,DocumentsRepository $documentsRepo) {
        $this->documentsRepository = $documentsRepo;
        $this->workHistoriesActiveRepository = $workHistoriesActiveRepo;
        $this->documentsNewsRepository = $documentsNewsRepo;


    }

    /**
     * Muestra la vista para el CRUD de Documents.
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $work_historie = WorkHistoriesActive::where('id',$request->wh)->first();
        $work_historie_id = $request->wh;
        $name_work_historie =$work_historie["name"]." ".$work_historie["surname"];
        return view('workhistories::documents.index',compact(['work_historie_id','name_work_historie']));
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
        $documents = Documents::where('work_histories_id',$id)->with(['workHistoriesConfigDocuments'])->latest()->get()->toArray();
        return $this->sendResponse($documents, trans('data_obtained_successfully'));
    }


    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param CreateDocumentsRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentsRequest $request,$id) {
       // dd($id);
        $input = $request->all();
        $input["work_histories_id"]=$id;
        $input["users_id"]=Auth::user()->id;
        $input["users_name"]=Auth::user()->name;

        // Valida se ingresa un adjunto
        if ($request->hasFile('url_document')) {
            $input['url_document'] = substr($input['url_document']->store('public/work_histories/documents'), 7);

            $workHistoriesActive = $this->workHistoriesActiveRepository->find($id);
            $totalDocumentsNew = $workHistoriesActive->total_documents+1;
            $input1["total_documents"]=$totalDocumentsNew;

            if (empty($workHistoriesActive)) {
                return $this->sendError(trans('not_found_element'), 200);
            }
    
            $workHistoriesActive = $this->workHistoriesActiveRepository->update($input1, $id);

       
        }
        $documents = $this->documentsRepository->create($input);

        $documents->workHistoriesConfigDocuments;

        //crear novedad
        $inputNew["new"]="Creación del documento: ". $documents->workHistoriesConfigDocuments->name;
        $inputNew["type_document"]=$documents->workHistoriesConfigDocuments->name;
        $inputNew["work_histories_documents_id"]=$documents->id;
        $inputNew["work_histories_id"]=$id;
        $inputNew["users_id"]=Auth::user()->id;
        $inputNew["users_name"]=Auth::user()->name;

        $documentsNew = $this->documentsNewsRepository->create($inputNew);

        
        return $this->sendResponse($documents->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateDocumentsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentsRequest $request) {

        $input = $request->all();

        /** @var Documents $documents */
        $documents = $this->documentsRepository->find($id);

        if (empty($documents)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida se ingresa un adjunto
        if ($request->hasFile('url_document')) {
            $input['url_document'] = substr($input['url_document']->store('public/work_histories/documents'), 7);
        }

        $documents = $this->documentsRepository->update($input, $id);
        $documents->workHistoriesConfigDocuments;


        ///novedad
        $inputNew["new"]="Edición del documento: ". $documents->workHistoriesConfigDocuments->name;
        $inputNew["type_document"]=$documents->workHistoriesConfigDocuments->name;
        $inputNew["work_histories_documents_id"]=$documents->id;
        $inputNew["work_histories_id"]=$documents->work_histories_id;
        $inputNew["users_id"]=Auth::user()->id;
        $inputNew["users_name"]=Auth::user()->name;

        $documentsNew = $this->documentsNewsRepository->create($inputNew);

        return $this->sendResponse($documents->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un Documents del almacenamiento
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

        /** @var Documents $documents */
        $documents = $this->documentsRepository->find($id);
        $documents->workHistoriesConfigDocuments;
        if($documents->url_document){
           Storage::delete("public/".$documents->url_document);

        }

        if (empty($documents)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $workHistoriesActive = $this->workHistoriesActiveRepository->find($documents->work_histories_id);
        $totalDocumentsNew = $workHistoriesActive->total_documents-1;
       // dd($totalDocumentsNew);

        $input1["total_documents"]=$totalDocumentsNew;

        if (empty($workHistoriesActive)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $workHistoriesActive = $this->workHistoriesActiveRepository->update($input1, $documents->work_histories_id);
   
        ///novedad
        $inputNew["new"]="Eliminación del documento: ". $documents->workHistoriesConfigDocuments->name;
        $inputNew["type_document"]=$documents->workHistoriesConfigDocuments->name;
        $inputNew["work_histories_documents_id"]=$documents->id;
        $inputNew["work_histories_id"]=$documents->work_histories_id;
        $inputNew["users_id"]=Auth::user()->id;
        $inputNew["users_name"]=Auth::user()->name;

        $documentsNew = $this->documentsNewsRepository->create($inputNew);

        $documents->delete();
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
        $fileName = time().'-'.trans('documents').'.'.$fileType;

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
     * @author Andres Stiven Pinzon G. - Jun. 03 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('documents').'.'.$fileType;
        
        return Excel::download(new RequestExport('workhistories::documents.report_excel', $input['data'], 'e'), $fileName);
    }
}
