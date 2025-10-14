<?php

namespace Modules\UpdateCitizenData\Http\Controllers;

use App\Exports\GenericExport;
use Modules\UpdateCitizenData\Http\Requests\CreateUdcRequestRequest;
use Modules\UpdateCitizenData\Http\Requests\UpdateUdcRequestRequest;
use Modules\UpdateCitizenData\Repositories\UdcRequestRepository;
use Modules\UpdateCitizenData\Repositories\UdcRequestHistoryRepository;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Illuminate\Support\Facades\Auth;
use App\Exports\Udc\RequestExport;

use Modules\UpdateCitizenData\Models\UdcRequest;


/**
 * DEscripcion de la clase
 *
 * @author Erika Johana Gonzalez. - Mayo. 02 - 2021
 * @version 1.0.0
 */
class UdcRequestCitizenController extends AppBaseController {

    /** @var  UdcRequestRepository */
    private $udcRequestRepository;

    /** @var  UdcRequestHistoryRepository */
    private $udcRequestHistoryRepository;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez. - Mayo. 02 - 2021
     * @version 1.0.0
     */
    public function __construct(UdcRequestRepository $udcRequestRepo,UdcRequestHistoryRepository $udcRequestHistoryRepo) {
        $this->udcRequestRepository = $udcRequestRepo;
        $this->udcRequestHistoryRepository = $udcRequestHistoryRepo;

    }

    /**
     * Muestra la vista para el CRUD de UdcRequest.
     *
     * @author Erika Johana Gonzalez. - Mayo. 02 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('update_citizen_data::udc_requests.index_citizen');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Erika Johana Gonzalez. - Mayo. 02 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $udc_requests = $this->udcRequestRepository->all()->where("state","3");
        return $this->sendResponse($udc_requests->toArray(), trans('data_obtained_successfully'));
    }



    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Erika Johana Gonzalez. - Mayo. 02 - 2021
     * @version 1.0.0
     *
     * @param CreateUdcRequestRequest $request
     *
     * @return Response
     */
    public function store(CreateUdcRequestRequest $request) {
        
        try {
            
            $input = $request->all();
            $input["state"]=1;
    
            $register = UdcRequest::where('identification', '=', $input["identification"])->first();
    
            if ($register === null) {
    
                $udcRequest = $this->udcRequestRepository->create($input);
    
            }else{
    
                $id = $register->id;
                /** @var UdcRequest $udcRequest */
                $udcRequest = $this->udcRequestRepository->find($id);
    
                if (empty($udcRequest)) {
                    return $this->sendError(trans('not_found_element'), 200);
                }
    
                $udcRequest = $this->udcRequestRepository->update($input, $id);
            }
            
            $input["users_name"]=$udcRequest->citizen_name;
            $input['udc_request_id'] = $udcRequest->id;
            // Crea un nuevo registro de historial
            $this->udcRequestHistoryRepository->create($input);
    
             $udcRequest->udcRequestHistories;
    
            return $this->sendResponse($udcRequest->toArray(), "Gracias por diligenciar la Encuesta Actualización de Datos Personales. EPA, la empresa de todos.","success");

        } catch (\Illuminate\Database\QueryException $error) {

            return $this->sendSuccess("Hay un dato incorrecto, por favor verifique o comuníquese con soporte@seven.com.co: '" . trans($error->errorInfo[2])."'", 'error');
        }
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Erika Johana Gonzalez. - Mayo. 02 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateUdcRequestRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUdcRequestRequest $request) {

        $input = $request->all();

        /** @var UdcRequest $udcRequest */
        $udcRequest = $this->udcRequestRepository->find($id);

        if (empty($udcRequest)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $udcRequest = $this->udcRequestRepository->update($input, $id);

        $input["users_name"]=Auth::user()->name;
        $input['udc_request_id'] = $id;

        // Crea un nuevo registro de historial
        $this->udcRequestHistoryRepository->create($input);

    
        $udcRequest->udcRequestHistories;

        return $this->sendResponse($udcRequest->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un UdcRequest del almacenamiento
     *
     * @author Erika Johana Gonzalez. - Mayo. 02 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var UdcRequest $udcRequest */
        $udcRequest = $this->udcRequestRepository->find($id);

        if (empty($udcRequest)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $udcRequest->delete();

        return $this->sendSuccess(trans('msg_success_drop'));

    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Erika Johana Gonzalez. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {


        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('polls').'.'.$fileType;
        
        return Excel::download(new RequestExport('update_citizen_data::udc_requests.report_excel', $input['data']), $fileName);


        /*
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('udc_requests').'.'.$fileType;

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
        }*/

    }
}
