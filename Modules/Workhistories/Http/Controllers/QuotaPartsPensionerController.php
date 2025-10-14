<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateQuotaPartsPensionerRequest;
use Modules\Workhistories\Http\Requests\UpdateQuotaPartsPensionerRequest;
use Modules\Workhistories\Repositories\QuotaPartsPensionerRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\WorkHistories\RequestExport;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Illuminate\Support\Facades\Auth;

use Modules\Workhistories\Repositories\QuotaPartsPensionerHistoryRepository;
use Modules\Workhistories\Models\QuotaPartsPensioner;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class QuotaPartsPensionerController extends AppBaseController {

    /** @var  QuotaPartsPensionerRepository */
    private $quotaPartsPensionerRepository;

    /** @var  QuotaPartsPensionerHistoryRepository */
    private $quotaPartsPensionerHistoryRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(QuotaPartsPensionerRepository $quotaPartsPensionerRepo,QuotaPartsPensionerHistoryRepository $quotaPartsPensionerHistoryRepo) {
        $this->quotaPartsPensionerRepository = $quotaPartsPensionerRepo;
        $this->quotaPartsPensionerHistoryRepository = $quotaPartsPensionerHistoryRepo;

    }

    /**
     * Muestra la vista para el CRUD de QuotaPartsPensioner.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('workhistories::quota_parts_pensioners.index');
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

        $quota_parts_pensioners = QuotaPartsPensioner::with(['workHistoriesCps','workHistoriesCpPensionadosHes','workHistoriesCpPDocumentsNews','workHistoriesCpPNewsUsers','workHistoriesCpPDocuments','quotaPartsNews'])->latest()->get();
       
        return $this->sendResponse($quota_parts_pensioners->toArray(), trans('data_obtained_successfully'));

       // $quota_parts_pensioners = $this->quotaPartsPensionerRepository->all();
        //return $this->sendResponse($quota_parts_pensioners->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateQuotaPartsPensionerRequest $request
     *
     * @return Response
     */
    public function store(CreateQuotaPartsPensionerRequest $request) {

        $input = $request->all();
        $input["state"]=1;
        $input["total_documents"]=0;
        $input["users_id"]=Auth::user()->id;
        $input["users_name"]=Auth::user()->name;

        $registro = $this->quotaPartsPensionerRepository->create($input);

        $input['cp_pensionados_id'] = $registro->id;
        // Crea un nuevo registro de historial
        $this->quotaPartsPensionerHistoryRepository->create($input);
        //Obtiene el historial
        $registro->workHistoriesCpPensionadosHes;

        return $this->sendResponse($registro->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateQuotaPartsPensionerRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQuotaPartsPensionerRequest $request) {


        $input = $request->all();

        $quotaPartsPensioner = $this->quotaPartsPensionerRepository->find($id);

        if (empty($quotaPartsPensioner)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $quotaPartsPensioner = $this->quotaPartsPensionerRepository->update($input, $id);
        
        $input["users_name"]=Auth::user()->name;           
        $input['cp_pensionados_id'] = $quotaPartsPensioner->id;
        // Crea un nuevo registro de historial
        $this->quotaPartsPensionerHistoryRepository->create($input);
        //Obtiene el historial
        $quotaPartsPensioner->workHistoriesCpPensionadosHes;

        return $this->sendResponse($quotaPartsPensioner->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un QuotaPartsPensioner del almacenamiento
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

        /** @var QuotaPartsPensioner $quotaPartsPensioner */
        $quotaPartsPensioner = $this->quotaPartsPensionerRepository->find($id);

        if (empty($quotaPartsPensioner)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $quotaPartsPensioner->delete();

        return $this->sendSuccess(trans('msg_success_drop'));

    }

    /**
	 * Obtiene los fallecidos
	 *
	 * @author Carlos Moises Garcia T. - Oct. 27 - 2020
	 * @version 1.0.0
	 *
	 * @return Response
	 */
   public function getDeceasedCp(Request $request) {
        $query = $request->input('query');
        $deceaseds = QuotaPartsPensioner::where('deceased','=','Si')->where('number_document','like','%'.$query.'%')->latest()->get();
        return $this->sendResponse($deceaseds->toArray(), trans('data_obtained_successfully'));
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
        $fileName = time().'-'.trans('quota_parts_pensioners').'.'.$fileType;

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
     * Genera el reporte de pensionados Cuotas partes en hoja de calculo
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
        $fileName = date('Y-m-d H:i:s').'-'.trans('quota-parts-pensioners').'.'.$fileType;
        
        return Excel::download(new RequestExport('workhistories::quota_parts_pensioners.report_excel', $input['data'], 'k'), $fileName);
    }
}
