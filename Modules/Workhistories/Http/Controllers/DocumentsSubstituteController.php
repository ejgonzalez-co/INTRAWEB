<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateDocumentsSubstituteRequest;
use Modules\Workhistories\Http\Requests\UpdateDocumentsSubstituteRequest;
use Modules\Workhistories\Repositories\DocumentsSubstituteRepository;
use Modules\Workhistories\Repositories\SubstituteRepository;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Modules\Workhistories\Models\Substitute;
use Modules\Workhistories\Models\DocumentsSubstitute;
use Modules\Workhistories\Repositories\DocumentsSubstituteNewsRepository;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class DocumentsSubstituteController extends AppBaseController {

    /** @var  DocumentsSubstituteRepository */
    private $documentsSubstituteRepository;

    
    /** @var  SubstituteRepository */
    private $substituteRepository;

        
    /** @var  DocumentsSubstituteNewsRepository */
    private $documentsSubstituteNewsRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(DocumentsSubstituteNewsRepository $documentsSubstituteNewsRepo,DocumentsSubstituteRepository $documentsSubstituteRepo, SubstituteRepository $substituteRepo) {
        $this->documentsSubstituteRepository = $documentsSubstituteRepo;
        $this->substituteRepository = $substituteRepo;
        $this->documentsSubstituteNewsRepository = $documentsSubstituteNewsRepo;


    }

    /**
     * Muestra la vista para el CRUD de DocumentsSubstitute.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
      public function index(Request $request) {

        $substitute = Substitute::where('id',$request->wh)->first();
        $substitute_id = $request->wh;
        $name_substitute =$substitute["name"]." ".$substitute["surname"];
        return view('workhistories::documents_substitutes.index',compact(['substitute_id','name_substitute']));
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

        $documentsSubstitutes = DocumentsSubstitute::where('work_histories_p_substitute_id',$id)->with(['workHistoriesConfigDocuments'])->latest()->get()->toArray();

        return $this->sendResponse($documentsSubstitutes, trans('data_obtained_successfully'));

   }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateDocumentsSubstituteRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentsSubstituteRequest $request,$id) {

    
        $input = $request->all();
        $input["work_histories_p_substitute_id"]=$id;
        $input["users_id"]=Auth::user()->id;
        $input["users_name"]=Auth::user()->name;
        // Valida se ingresa un adjunto
        if ($request->hasFile('url_document')) {
            $input['url_document'] = substr($input['url_document']->store('public/work_histories/DocumentsPSubstitutes'), 7);

            $substitute = $this->substituteRepository->find($id);
            $totalDocumentsSubstituteNew = $substitute->total_documents+1;
            $input1["total_documents"]=$totalDocumentsSubstituteNew;

            if (empty($substitute)) {
                return $this->sendError(trans('not_found_element'), 200);
            }
    
            $substitute = $this->substituteRepository->update($input1, $id);
       
        }
        $DocumentsSubstitute = $this->documentsSubstituteRepository->create($input);
        $DocumentsSubstitute->workHistoriesConfigDocuments;

        $inputNew["new"]="Creación del documento: ". $DocumentsSubstitute->workHistoriesConfigDocuments->name;
        $inputNew["type_document"]=$DocumentsSubstitute->workHistoriesConfigDocuments->name;
        $inputNew["p_substitute_doc_id"]=$DocumentsSubstitute->id;
        $inputNew["work_histories_p_substitute_id"]=$id;
        $inputNew["users_id"]=Auth::user()->id;
        $inputNew["users_name"]=Auth::user()->name;

        $DocumentsSubstituteNew = $this->documentsSubstituteNewsRepository->create($inputNew);
       // dd($DocumentsSubstituteNew);

        return $this->sendResponse($DocumentsSubstitute->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateDocumentsSubstituteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentsSubstituteRequest $request) {

        $input = $request->all();

        /** @var DocumentsSubstitute $documentsSubstitute */
        $documentsSubstitute = $this->documentsSubstituteRepository->find($id);

        if (empty($documentsSubstitute)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $documentsSubstitute = $this->documentsSubstituteRepository->update($input, $id);

        $inputNew["new"]="Edición del documento: ". $documentsSubstitute->workHistoriesConfigDocuments->name;
        $inputNew["type_document"]=$documentsSubstitute->workHistoriesConfigDocuments->name;
        $inputNew["p_substitute_doc_id"]=$documentsSubstitute->id;
        $inputNew["work_histories_p_substitute_id"]=$documentsSubstitute->work_histories_p_substitute_id;
        $inputNew["users_id"]=Auth::user()->id;
        $inputNew["users_name"]=Auth::user()->name;

        $DocumentsSubstituteNew = $this->documentsSubstituteNewsRepository->create($inputNew);

        return $this->sendResponse($documentsSubstitute->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un DocumentsSubstitute del almacenamiento
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

        
        /** @var documentsSubstitute $documentsSubstitute */
        $documentsSubstitute = $this->documentsSubstituteRepository->find($id);
        $documentsSubstitute->workHistoriesConfigDocuments;
        if($documentsSubstitute->url_document){
           Storage::delete("public/".$documentsSubstitute->url_document);

        }

        if (empty($documentsSubstitute)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $substituteRepository = $this->substituteRepository->find($documentsSubstitute->work_histories_p_substitute_id);
        $totaldocumentsSubstituteNew = $substituteRepository->total_documents-1;
       // dd($totaldocumentsSubstituteNew);

        $input1["total_documents"]=$totaldocumentsSubstituteNew;

        if (empty($substituteRepository)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $substituteRepository = $this->substituteRepository->update($input1, $documentsSubstitute->work_histories_p_substitute_id);
   
        ///novedad
        $inputNew["new"]="Eliminación del documento: ". $documentsSubstitute->workHistoriesConfigDocuments->name;
        $inputNew["type_document"]=$documentsSubstitute->workHistoriesConfigDocuments->name;
        $inputNew["p_substitute_doc_id"]=$documentsSubstitute->id;
        $inputNew["work_histories_p_substitute_id"]=$documentsSubstitute->work_histories_p_substitute_id;
        $inputNew["users_id"]=Auth::user()->id;
        $inputNew["users_name"]=Auth::user()->name;

        $documentsSubstituteNew = $this->documentsSubstituteNewsRepository->create($inputNew);

        $documentsSubstitute->delete();
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
        $fileName = time().'-'.trans('documents_substitutes').'.'.$fileType;

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
