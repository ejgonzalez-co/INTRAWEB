<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateSubstituteRequest;
use Modules\Workhistories\Http\Requests\UpdateSubstituteRequest;
use Modules\Workhistories\Repositories\SubstituteRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\WorkHistories\RequestExport;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Illuminate\Support\Facades\Auth;
use Modules\Workhistories\Models\Substitute;
use Modules\Workhistories\Repositories\SubstituteHistoryRepository;
use Modules\Workhistories\Repositories\WorkHistPensionerRepository;
use Modules\Workhistories\Repositories\QuotaPartsPensionerRepository;


/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class SubstituteController extends AppBaseController {

    /** @var  SubstituteRepository */
    private $substituteRepository;

    /** @var  SubstituteRepository */
    private $substituteHistoryRepository;

    /** @var  WorkHistPensionerRepository */
    private $workHistPensionerRepository;

    /** @var  QuotaPartsPensionerRepository */
    private $quotaPartsPensionerRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(QuotaPartsPensionerRepository $quotaPartsPensionerRepo,SubstituteRepository $substituteRepo,SubstituteHistoryRepository $substituteHistoryRepo,WorkHistPensionerRepository $workHistPensionerRepo) {
        $this->substituteRepository = $substituteRepo;
        $this->SubstituteHistoryRepository = $substituteHistoryRepo;
        $this->WorkHistPensionerRepository = $workHistPensionerRepo;
        $this->quotaPartsPensionerRepository = $quotaPartsPensionerRepo;


    }

    /**
     * Muestra la vista para el CRUD de Substitute.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('workhistories::substitutes.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {

       // $substitutes = $this->substituteRepository->all();
        $substitutes = Substitute::with(['workHistoriesP','workHistoriesCp','documentsSubstitute','substituteHistory','documentsNews'])->latest()->get();
        return $this->sendResponse($substitutes->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateSubstituteRequest $request
     *
     * @return Response
     */
    public function store(CreateSubstituteRequest $request) {
        $input = $request->all();
        $input["users_id"]=Auth::user()->id;
        $input["users_name"]=Auth::user()->name;
        $input["state"]=1;
        //dd($input);

       
        //work_histories_p_id
        $substitute = $this->substituteRepository->create($input);

        if($input["category"]=='Pensionado'){
            $substitute->workHistoriesP;
            $dataWorkHistoriePensioner = $this->WorkHistPensionerRepository->find($input["work_histories_p_id"]);

        }else{
            $substitute->workHistoriesCp;
            $dataWorkHistoriePensioner = $this->quotaPartsPensionerRepository->find($input["work_histories_cp_pensionados_id"]);

            
        }

        //dd($substitute);
        //historial


        
        if($input["category"]=='Pensionado'){

            $input["pensioner_id"]=$input["work_histories_p_id"];

        }else{
            $input["pensioner_id"]=$input["work_histories_cp_pensionados_id"];
            
        }
        $input["name_pensioner"]=$dataWorkHistoriePensioner->name." ".$dataWorkHistoriePensioner->surname;
        $input["document_pensioner"]=$dataWorkHistoriePensioner->number_document;

        $input['work_histories_p_substitute_id'] = $substitute->id;
        // Crea un nuevo registro de historial
        $this->SubstituteHistoryRepository->create($input);
        //Obtiene el historial
        $substitute->substituteHistory;

        return $this->sendResponse($substitute->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateSubstituteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSubstituteRequest $request) {

        $input = $request->all();

        /** @var Substitute $substitute */
        $substitute = $this->substituteRepository->find($id);

        if (empty($substitute)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $substitute = $this->substituteRepository->update($input, $id);

        if($input["category"]=='Pensionado'){
            $substitute->workHistoriesP;
            $dataWorkHistoriePensioner = $this->WorkHistPensionerRepository->find($input["work_histories_p_id"]);

        }else{
            $substitute->workHistoriesCp;
            $dataWorkHistoriePensioner = $this->quotaPartsPensionerRepository->find($input["work_histories_cp_pensionados_id"]);

            
        }

        if($input["category"]=='Pensionado'){

            $input["pensioner_id"]=$input["work_histories_p_id"];

        }else{
            $input["pensioner_id"]=$input["work_histories_cp_pensionados_id"];
            
        }

         //historial
        // $dataWorkHistoriePensioner = $this->WorkHistPensionerRepository->find($input["work_histories_p_id"]);

        // $input["pensioner_id"]=$input["work_histories_p_id"];
         $input["name_pensioner"]=$dataWorkHistoriePensioner->name." ".$dataWorkHistoriePensioner->surname;
         $input["document_pensioner"]=$dataWorkHistoriePensioner->number_document;
 
         $input['work_histories_p_substitute_id'] = $substitute->id;
         // Crea un nuevo registro de historial
         $this->SubstituteHistoryRepository->create($input);
         //Obtiene el historial
         $substitute->substituteHistory;

         
        return $this->sendResponse($substitute->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un Substitute del almacenamiento
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

        /** @var Substitute $substitute */
        $substitute = $this->substituteRepository->find($id);

        if (empty($substitute)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $substitute->delete();

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
        $fileName = time().'-'.trans('substitutes').'.'.$fileType;

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
     * Genera el reporte de sustitutos (pensionados) en hoja de calculo
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
        $fileName = date('Y-m-d H:i:s').'-'.trans('substitutes').'.'.$fileType;
        
        return Excel::download(new RequestExport('workhistories::substitutes.report_excel', $input['data'], 'n'), $fileName);
    }
}
