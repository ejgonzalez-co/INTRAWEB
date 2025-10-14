<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateWorkHistoriesActiveRequest;
use Modules\Workhistories\Http\Requests\UpdateWorkHistoriesActiveRequest;
use Modules\Workhistories\Repositories\WorkHistoriesActiveRepository;
use Modules\Workhistories\Repositories\WorkHistoriesHistoryRepository;
use Modules\Workhistories\Repositories\DocumentsNewsRepository;
use Modules\Workhistories\Models\WorkHistoriesActive;
use Modules\Workhistories\Models\WorkHistoriesNovelty;
use Modules\Workhistories\Models\WorkRequest;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use App\Exports\ExportViewExcel;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use App\Exports\WorkHistories\RequestExport;
use App\Http\Controllers\JwtController;
use Auth;

/**
 * DEscripcion de la clase
 *
 * @author Erika Johana Gonzalez C. Oct.  22 - 2020
 * @version 1.0.0
 */
class WorkHistoriesActiveController extends AppBaseController {

    /** @var  WorkHistoriesActiveRepository */
    private $workHistoriesActiveRepository;

    /** @var  WorkHistoriesHistoryRepository */
    private $WorkHistoriesHistoryRepository;

    /** @var  DocumentsNewsRepository */
    private $documentsNewsRepository;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     */
    public function __construct(DocumentsNewsRepository $documentsNewsRepo, WorkHistoriesActiveRepository $workHistoriesActiveRepo, WorkHistoriesHistoryRepository $workHistoriesHistoryRepo) {
        $this->workHistoriesActiveRepository = $workHistoriesActiveRepo;
        $this->WorkHistoriesHistoryRepository = $workHistoriesHistoryRepo;
        $this->documentsNewsRepository = $documentsNewsRepo;

    }

    /**
     * Muestra la vista para el CRUD de WorkHistoriesActive.
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
        return view('workhistories::work_histories_actives.index');
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

            $work_histories_actives = WorkHistoriesActive::with(['workHistoriesHistory','documentsNews','workHistorieNews','workHistorieDocuments', 'historyRequest', 'requestWorkHistorie'])->latest()->get();



            return $this->sendResponse($work_histories_actives->toArray(), trans('data_obtained_successfully'));

        }else{
            //SI es gestor de hojas de vida le carga solamente las hojas de vida que tiene aprobados
            if(Auth::user()->hasRole('Gestor hojas de vida')){


                //Consulta todas las hojas de vida
                $work_histories_actives = WorkHistoriesActive::with(['workHistoriesHistory','documentsNews','workHistorieNews','workHistorieDocuments', 'requestWorkHistorie'])->latest()->get();


                $workHistories=[];

                //Consulta todas las solicitudes que estan ejecucion
                $workRequest=WorkRequest::with(['workHistories', 'workHistoriesP', 'users'])->where('condition', 'En ejecución')->get();

                //Recorre el arreglo de las solicitudes
                foreach ($workRequest as  $valueOne) {
                    //Recorre el arreglo de las hojas de vida
                    foreach ($work_histories_actives as  $valueTwo) {
                        //verifica que cada atributo respectivo sea igual
                        if($valueOne->work_histories_id == $valueTwo->id){
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

        $work_histories_actives = $this->workHistoriesActiveRepository->find($id);

        if (empty($work_histories_actives)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $work_histories_actives->workHistoriesHistory;
        $work_histories_actives->documentsNews;
        $work_histories_actives->workHistorieNews;
        $work_histories_actives->workHistorieDocuments;
        $work_histories_actives->workHistorieFamilies;
        $work_histories_actives->workHistoriesNovelties;
        $work_histories_actives->historyRequest;

        return $this->sendResponse($work_histories_actives->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param CreateWorkHistoriesActiveRequest $request
     *
     * @return Response
     */
    public function store(CreateWorkHistoriesActiveRequest $request) {

        $input = $request->all();
        $input["state"]=1;
        $input["total_documents"]=0;
        $input["users_id"]=Auth::user()->id;
        $input["users_name"]=Auth::user()->name;

        $dependency = DB::table('dependencias')->where('id', '=',  $input["dependencias_id"])->first();
        $input["dependencias_name"]=$dependency->nombre;

        $workHistoriesActive = $this->workHistoriesActiveRepository->create($input);



        $input['work_histories_id'] = $workHistoriesActive->id;
        // Crea un nuevo registro de historial
        $this->WorkHistoriesHistoryRepository->create($input);
        //Obtiene el historial
        $workHistoriesActive->workHistoriesHistory;

        return $this->sendResponse($workHistoriesActive->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Erika Johana Gonzalez C. Oct.  22 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateWorkHistoriesActiveRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWorkHistoriesActiveRequest $request) {

        $input = $request->all();

        if($request->name){
            $dependency = DB::table('dependencias')->where('id', '=',  $input["dependencias_id"])->first();
            $input["dependencias_name"]=$dependency->nombre;
        }
        $workHistoriesActive = $this->workHistoriesActiveRepository->find($id);

        if (empty($workHistoriesActive)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida si se esta actualizando el estado
        if ($workHistoriesActive->state != $input["state"]) {
            // Obtiene el nombre del estado anterior
            $stateNameOld = $this->getObjectOfList(config('workhistories.state_work_histories_active'), 'id', $workHistoriesActive->state)->name;
            // Obtiene el nombre del estado nuevo
            $stateName = $this->getObjectOfList(config('workhistories.state_work_histories_active'), 'id', $input["state"])->name;
            // Inserta el registro en la tabla de novedades
            $workHistoriesNovelty = WorkHistoriesNovelty::create([
                'users_id' => Auth::user()->id,
                'work_histories_id' => $workHistoriesActive->id,
                'user_name' => Auth::user()->name,
                'type_novelty' => 'Actualización de estado',
                'description' => "Se actualiza el estado de <b>".$stateNameOld."</b> a <b>".$stateName."</b>",
            ]);
        }

        $workHistoriesActive = $this->workHistoriesActiveRepository->update($input, $id);
        //Valida el name para que en la accion de cambiar estado no cree un historial
        if($request->name){

            $input["users_name"]=Auth::user()->name;
            $input['work_histories_id'] = $workHistoriesActive->id;
            // Crea un nuevo registro de historial
            $this->WorkHistoriesHistoryRepository->create($input);

        }
        $workHistoriesActive->workHistoriesHistory;
        $workHistoriesActive->workHistoriesNovelties;



        return $this->sendResponse($workHistoriesActive->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un WorkHistoriesActive del almacenamiento
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

        /** @var WorkHistoriesActive $workHistoriesActive */
        $workHistoriesActive = $this->workHistoriesActiveRepository->find($id);

        if (empty($workHistoriesActive)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $workHistoriesActive->delete();

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
    // public function export(Request $request) {
    //     dd($request['data']);
    //     $input = $request->all();

    //     // Tipo de archivo (extencion)
    //     $fileType = $input['fileType'];
    //     // Nombre de archivo con tiempo de creacion
    //     $fileName = time().'-'.trans('work_histories_actives').'.'.$fileType;

    //     // Valida si el tipo de archivo es pdf
    //     if (strcmp($fileType, 'pdf') == 0) {
    //         // Guarda el archivo pdf en ubicacion temporal
    //         // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

    //         // Descarga el archivo generado
    //         return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
    //     } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
    //         // Guarda el archivo excel en ubicacion temporal
    //         // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

    //         // Descarga el archivo generado
    //         return Excel::download(new GenericExport($input['data']), $fileName);
    //     }

    // }


    /**
     * Genera el reporte de historias laborales en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - Jul. 9 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('work_histories_actives').'.'.$fileType;

        return Excel::download(new RequestExport('workhistories::work_histories_actives.report_excel', $input['data'], 'i'), $fileName);
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

        $data = WorkHistoriesActive::where("id",$id)->latest()->get();
       // $nameFile = "prueba.xlsx";

       // return Excel::download(new ExportViewExcel('workhistories::work_histories_actives.reportExcel',$data),$nameFile);


        $fileName = date('Y-m-d H:i:s').'.xlsx';

        return Excel::download(new RequestExport('workhistories::work_histories_actives.reportExcel', JwtController::generateToken($data)), $fileName);



    }

    /**
     * Genera el documento de la historia laboral
     *
     * @author Erika Johana Gonzalez - Dic. 29 - 2020
     * @version 1.0.0
     */
    public static function generateDocument($idWorkHistorie) {

        $workHistorie = WorkHistoriesActive::find($idWorkHistorie);

        //$workHistorie = WorkHistoriesActive::with(['workHistoriesHistory','documentsNews','workHistorieNews','workHistorieDocuments'])->where('id', $idWorkHistorie)->get();

        // Obtiene relacion de documentos
        $workHistorie->workHistorieDocuments;
      //  dd($workHistorie->workHistorieDocuments);
        // Genera el archivo a base de una plantilla con los datos del deudor
        $pdf = PDF::loadView('workhistories::exports.document_historie', ['workHistorie' => $workHistorie])
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
        $date = date('Y-m-d H:i:s');
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
