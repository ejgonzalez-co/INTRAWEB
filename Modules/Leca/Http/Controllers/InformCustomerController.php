<?php

namespace Modules\leca\Http\Controllers;

use App\Exports\GenericExport;
use Modules\leca\Http\Requests\CreateInformCustomerRequest;
use Modules\leca\Http\Requests\UpdateInformCustomerRequest;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Leca\Models\InformCustomer;
use Modules\Leca\Models\Customers;


/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class InformCustomerController extends AppBaseController {



    /**
     * Muestra la vista para el CRUD de InformCustomer.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('leca::inform_customers.index')->with('document', $request['document']);
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
        $input = $request->all();
        $customer = Customers:: where('identification_number', $input['ducument'])->get()->first();
        // dd($customer);

        $inform_customers = InformCustomer::with(['lcCustomers'])->where('lc_customers_id',$customer->id )->where('status','Informe finalizado.')->latest()->get();
        return $this->sendResponse($inform_customers->toArray(), trans('data_obtained_successfully'));
    }

}
