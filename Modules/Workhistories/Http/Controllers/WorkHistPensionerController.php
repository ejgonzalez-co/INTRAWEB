<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateWorkHistPensionerRequest;
use Modules\Workhistories\Http\Requests\UpdateWorkHistPensionerRequest;
use Modules\Workhistories\Repositories\WorkHistPensionerRepository;
use Modules\Workhistories\Repositories\HistoryPensionerRepository;
use Modules\Workhistories\Repositories\DocumentsNewsRepository;
use Modules\Workhistories\Models\WorkHistPensioner;
use Modules\Workhistories\Models\WorkRequest;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Illuminate\Support\Facades\Auth;
use App\Exports\ExportViewExcel;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use App\Exports\WorkHistories\RequestExport;
use App\Http\Controllers\JwtController;

/**
 * DEscripcion de la clase
 *
 * @author Erika Johana Gonzalez C. Oct.  22 - 2020
 * @version 1.0.0
 */
class WorkHistPensionerController extends AppBaseController {

    /** @var  WorkHistPensionerRepository */
    private $WorkHistPensionerRepository;

    /** @var  HistoryPensionerRepository */
    private $HistoryPensionerRepository;

    /** @var  DocumentsNewsRepository */
    private $documentsNewsRepository;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     */
    public function __construct(DocumentsNewsRepository $documentsNewsRepo, WorkHistPensionerRepository $WorkHistPensionerRepo, HistoryPensionerRepository $workHistoriesHistoryRepo) {
        $this->WorkHistPensionerRepository = $WorkHistPensionerRepo;
        $this->HistoryPensionerRepository = $workHistoriesHistoryRepo;
        $this->documentsNewsRepository = $documentsNewsRepo;

    }

    /**
     * Muestra la vista para el CRUD de WorkHistPensioner.
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $requestWork=WorkRequest::all();
        foreach ($requestWork as $value) {
            # code...
            $value->change_state;
        }
        return view('workhistories::work_hist_pensioners.index');
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

        //Si es administrador le cargan todas las hojas de vida
        if(Auth::user()->hasRole('Administrador historias laborales')){
            $work_histories_actives = WorkHistPensioner::with(['workHistoriesHistory','documentsNews','workHistorieNews','workHistorieDocuments', 'historyRequest', 'requestWorkHistorie'])->latest()->get();


            //$work_histories_actives = WorkHistPensioner::latest()->get();

            return $this->sendResponse($work_histories_actives->toArray(), trans('data_obtained_successfully'));

        }else{
            //SI es gestor de hojas de vida le carga solamente las hojas de vida que tiene aprobados
            if(Auth::user()->hasRole('Gestor hojas de vida')){

                //Consulta todas las hojas de vida de pensionados
                $work_histories_actives = WorkHistPensioner::with(['workHistoriesHistory','documentsNews','workHistorieNews','workHistorieDocuments', 'historyRequest', 'requestWorkHistorie'])->latest()->get();


                $workHistories=[];

                //Consulta todas las solicitudes que estan ejecucion
                $workRequest=WorkRequest::with(['workHistories', 'workHistoriesP', 'users'])->where('condition', 'En ejecución')->get();

                //Recorre el arreglo de las solicitudes
                foreach ($workRequest as  $valueOne) {
                    //Recorre el arreglo de las hojas de vida
                    foreach ($work_histories_actives as  $valueTwo) {
                        //verifica que cada atributo respectivo sea igual
                        if($valueOne->work_histories_p_id == $valueTwo->id){
                        //Lo agregan al arreglo
                        array_push( $workHistories, $valueTwo);
                        }
                    }
                }
                return $this->sendResponse($workHistories, trans('data_obtained_successfully'));
            }
        }



    }

           /**
     * Muestra el detalle completo del elemento existente
     *
     * @author Erika Johana Gonzalez - Abr. 16 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function show($id) {

        $work_histories_pensioner = $this->WorkHistPensionerRepository->find($id);

        if (empty($work_histories_pensioner)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $work_histories_pensioner->workHistoriesHistory;
        $work_histories_pensioner->documentsNews;
        $work_histories_pensioner->workHistorieNews;
        $work_histories_pensioner->workHistorieDocuments;
        $work_histories_pensioner->workHistorieFamilies;
        $work_histories_pensioner->historyRequest;

        return $this->sendResponse($work_histories_pensioner->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param CreateWorkHistPensionerRequest $request
     *
     * @return Response
     */
    public function store(CreateWorkHistPensionerRequest $request) {

        $input = $request->all();
        $input["state"]=1;
        $input["total_documents"]=0;
        $input["users_id"]=Auth::user()->id;
        $input["users_name"]=Auth::user()->name;

        $dependency = DB::table('dependencias')->where('id', '=',  $input["dependencias_id"])->first();
        $input["dependencias_name"]=$dependency->nombre;

        $WorkHistPensioner = $this->WorkHistPensionerRepository->create($input);

        $input['work_histories_p_id'] = $WorkHistPensioner->id;
        // Crea un nuevo registro de historial
        $this->HistoryPensionerRepository->create($input);
        //Obtiene el historial
        $WorkHistPensioner->workHistoriesHistory;

        return $this->sendResponse($WorkHistPensioner->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateWorkHistPensionerRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWorkHistPensionerRequest $request) {

        $input = $request->all();

        if($request->name){
            $dependency = DB::table('dependencias')->where('id', '=',  $input["dependencias_id"])->first();
            $input["dependencias_name"]=$dependency->nombre;
        }
        $WorkHistPensioner = $this->WorkHistPensionerRepository->find($id);

        if (empty($WorkHistPensioner)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $WorkHistPensioner = $this->WorkHistPensionerRepository->update($input, $id);
        //Valida el name para que en la accion de cambiar estado no cree un historial
        if($request->name){

            $input["users_name"]=Auth::user()->name;
            $input['work_histories_p_id'] = $WorkHistPensioner->id;
            $input['state'] = 1;

            // Crea un nuevo registro de historial
            $this->HistoryPensionerRepository->create($input);

        }
         $WorkHistPensioner->workHistoriesHistory;

        return $this->sendResponse($WorkHistPensioner->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un WorkHistPensioner del almacenamiento
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

        /** @var WorkHistPensioner $WorkHistPensioner */
        $WorkHistPensioner = $this->WorkHistPensionerRepository->find($id);

        if (empty($WorkHistPensioner)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $WorkHistPensioner->delete();

        return $this->sendSuccess(trans('msg_success_drop'));

    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Erika Johana Gonzalez C. - Oct. 20 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('work_histories_actives').'.'.$fileType;

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

        /**
     * Organiza la exportacion de datos
     *
     * @author Erika Johana Gonzalez C. - Oct. 20 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function exportFromView(Request $request,$id) {

        $input = $request->all();

        $data = WorkHistPensioner::where("id",$id)->latest()->get();

        $fileName = date('Y-m-d H:i:s').'.xlsx';

        return Excel::download(new RequestExport('workhistories::work_hist_pensioners.reportExcel', JwtController::generateToken($data)), $fileName);

    }

    /**
	 * Obtiene los fallecidos
	 *
	 * @author Carlos Moises Garcia T. - Oct. 27 - 2020
	 * @version 1.0.0
	 *
	 * @return Response
	 */
   public function getDeceased(Request $request) {
        $query = $request->input('query');
        $deceaseds = WorkHistPensioner::where('deceased','=','Si')->where('number_document','like','%'.$query.'%')->latest()->get();
        return $this->sendResponse($deceaseds->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Genera el documento de la historia laboral
     *
     * @author Erika Johana Gonzalez - Dic. 29 - 2020
     * @version 1.0.0
     */
    public static function generateDocument($idWorkHistorie) {

        $workHistorie = WorkHistPensioner::find($idWorkHistorie);

        // Obtiene relacion de documentos
        $workHistorie->workHistorieDocuments;
      //  dd($workHistorie->workHistorieDocuments);
        // Genera el archivo a base de una plantilla con los datos del deudor
        $pdf = PDF::loadView('workhistories::exports.document_historie_pensioner', ['workHistorie' => $workHistorie])
            ->setPaper([0, 0, 612.00, 996.00]);
        return $pdf;
    }


    /**
     * Visualiza el documento pdf de la historia laboral
     *
     * @author Erika Johana Gonzalez - Dic. 29 - 2020
     * @version 1.0.0
     */
    public function showDocument($idWorkHistorie) {
        return $this->generateDocument($idWorkHistorie)->stream('archivo.pdf');
    }

            /**
     * Cambia el estado de una solicitud cuando esta activo
     *
     * @author Nicolas Dario Ortiz Peña - Oct. 22 - 2021
     * @version 1.0.0
     */
    public function changeCondition() {
        //Busca la solicitud que este en estado aprobado o en ejecucion
        $workRequest=WorkRequest::with(['workHistories', 'workHistoriesP', 'users'])->where('condition', 'Aprobado')->orwhere('condition', 'En ejecución')->get();
        //Recupera la hora y fecha actual
        $date = date('Y-m-d h:i:s');
        //Filtra por las solicitudes que esten en condicion aprobado
        $workRequestAprobado=$workRequest->where('condition', 'Aprobado');
        //se recorre el arreglo de las solicitudes que han sido aprobadas
        foreach ($workRequestAprobado as $value) {
            //Si las solicitudes cumplen esta condicion cambian de estado a en ejecucion
            if($value['date_start']< $date &&  $value['date_final'] > $date){
                $value->condition='En ejecución';

                $value->save();
            }
        }

        //Se filtra por solicitudes en estado en ejecucion
        $workRequestEjecucion=$workRequest->where('condition', 'En ejecución');
        //Se recorre el arreglo de solicitudes en ejecucion
        foreach ($workRequestEjecucion as $value) {
            //Si cumple esta condicion cambia de estado a finalizado
            if($value['date_final'] < $date){
                $value->condition='Finalizado';

                $value->save();
            }
        }

    }

}
