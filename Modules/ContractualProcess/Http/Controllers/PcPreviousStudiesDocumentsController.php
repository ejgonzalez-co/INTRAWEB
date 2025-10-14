<?php

namespace Modules\ContractualProcess\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ContractualProcess\Http\Requests\CreatePcPreviousStudiesDocumentsRequest;
use Modules\ContractualProcess\Http\Requests\UpdatePcPreviousStudiesDocumentsRequest;
use Modules\ContractualProcess\Repositories\PcPreviousStudiesDocumentsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Modules\ContractualProcess\Models\PcPreviousStudiesDocuments;
use Illuminate\Support\Facades\Auth;
use App\Exports\RequestExport;

/**
 * DEscripcion de la clase
 *
 * @author Erika Johana Gonzalez. Ene. 20 - 2021
 * @version 1.0.0
 */
class PcPreviousStudiesDocumentsController extends AppBaseController {

    /** @var  PcPreviousStudiesDocumentsRepository */
    private $pcPreviousStudiesDocumentsRepository;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     */
    public function __construct(PcPreviousStudiesDocumentsRepository $pcPreviousStudiesDocumentsRepo) {
        $this->pcPreviousStudiesDocumentsRepository = $pcPreviousStudiesDocumentsRepo;
    }

    /**
     * Muestra la vista para el CRUD de PcPreviousStudiesDocuments.
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $previousStudiesId =$request->pc;
        return view('contractual_process::pc_previous_studies_documents.index',compact(['previousStudiesId']));

    }


    /**
     * Obtiene todos los elementos existentes
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all($id) {
        $pc_previous_studies_documents = PcPreviousStudiesDocuments::where('pc_previous_studies_id',$id)->latest()->get()->toArray();
        return $this->sendResponse($pc_previous_studies_documents, trans('data_obtained_successfully'));
    }


    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     *
     * @param CreatePcPreviousStudiesDocumentsRequest $request
     *
     * @return Response
     */

    public function store(CreatePcPreviousStudiesDocumentsRequest $request,$id) {
        // dd($id);
         $input = $request->all();
         $input["pc_previous_studies_id"] = $id;
         $input["users_name"] = Auth::user()->name;
         // Valida se ingresa un adjunto
         if ($request->hasFile('url_document')) {
             $input['url_document'] = substr($input['url_document']->store('public/pc/previousEstudies/documents'), 7);       
         }
         $documents = $this->pcPreviousStudiesDocumentsRepository->create($input);
 
         return $this->sendResponse($documents->toArray(), trans('msg_success_save'));
     }

      /**
     * Actualiza un registro especifico.
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdatePcPreviousStudiesDocumentsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePcPreviousStudiesDocumentsRequest $request) {

        $input = $request->all();
        $input["users_name"] = Auth::user()->name;

        /** @var Documents $documents */
        $documents = $this->pcPreviousStudiesDocumentsRepository->find($id);

        if (empty($documents)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida se ingresa un adjunto
        if ($request->hasFile('url_document')) {
            $input['url_document'] = substr($input['url_document']->store('public/pc/previousEstudies/documents'), 7);
        }

        $documents = $this->pcPreviousStudiesDocumentsRepository->update($input, $id);
    
        return $this->sendResponse($documents->toArray(), trans('msg_success_update'));

    }


    /**
     * Elimina un PcPreviousStudiesDocuments del almacenamiento
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var PcPreviousStudiesDocuments $pcPreviousStudiesDocuments */
        $pcPreviousStudiesDocuments = $this->pcPreviousStudiesDocumentsRepository->find($id);

        if (empty($pcPreviousStudiesDocuments)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $pcPreviousStudiesDocuments->delete();

        return $this->sendSuccess(trans('msg_success_drop'));

    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        // $input = $request->all();

        // // Tipo de archivo (extencion)
        // $fileType = $input['fileType'];
        // // Nombre de archivo con tiempo de creacion
        // $fileName = time().'-'.trans('pc_previous_studies_documents').'.'.$fileType;

        // // Valida si el tipo de archivo es pdf
        // if (strcmp($fileType, 'pdf') == 0) {
        //     // Guarda el archivo pdf en ubicacion temporal
        //     // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

        //     // Descarga el archivo generado
        //     return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        // } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
        //     // Guarda el archivo excel en ubicacion temporal
        //     // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

        //     // Descarga el archivo generado
        //     return Excel::download(new GenericExport($input['data']), $fileName);
        // }

        $input = $request->all();
        // dd($input['data']);

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // $fileName = date('Y-m-d H:i:s').'-'.trans('request_authorizations').'.'.$fileType;
        $fileName = 'setting.' . $fileType;
        return Excel::download(new RequestExport('contractual_process::pc_previous_studies_documents.report_excel', $input['data'], 'C'), $fileName);

        // return Excel::download(new RequestExport('sanitary_authorization::request_authorizations.report_excel', $input['data']), $fileName);
    


    }
}
