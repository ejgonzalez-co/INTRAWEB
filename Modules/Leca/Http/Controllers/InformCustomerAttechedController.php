<?php

namespace Modules\leca\Http\Controllers;

use App\Exports\GenericExport;
use Modules\leca\Http\Requests\CreateInformCustomerAttechedRequest;
use Modules\leca\Http\Requests\UpdateInformCustomerAttechedRequest;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use Modules\Leca\Models\ReporManagementAttachment;
use Modules\Leca\Models\ReportManagement;
use DB;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class InformCustomerAttechedController extends AppBaseController {

   

    /**
     * Muestra la vista para el CRUD de InformCustomerAtteched.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $cuatomer =  ReportManagement:: with('lcCustomers')->where('id',$request['report_id'])->get()->first();
        $doc= $cuatomer->lcCustomers->identification_number;
        return view('leca::inform_customer_attecheds.index',compact('doc'))->with('report_id', $request['report_id']);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        $inform_customer_attecheds = ReporManagementAttachment::with(['reportManagement'])->where("lc_rm_report_management_id", $request['report_id'])->where('status','Activo')->get();

        return $this->sendResponse($inform_customer_attecheds->toArray(), trans('data_obtained_successfully'));
    }

  
}
