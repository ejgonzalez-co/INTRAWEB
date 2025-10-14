<?php
namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateProviderContractRequest;
use Modules\Maintenance\Http\Requests\UpdateProviderContractRequest;
use Modules\Maintenance\Repositories\ProviderContractRepository;
use App\Http\Controllers\AppBaseController;
use Modules\Maintenance\Models\Dependency;
use App\Exports\Maintenance\ContractExport\ProviderContractExport;
use Modules\HelpTable\Models\HolidayCalendar;
use Illuminate\Http\Request;
use App\Exports\Maintenance\RequestExport;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use App\User;
use Modules\Maintenance\Models\BudgetAllocationProvider;
use Modules\Maintenance\Models\ProviderContract;
use Modules\Maintenance\Models\ContractNew;
use Modules\Maintenance\Models\HistoryProviderContract;
use Modules\Maintenance\Models\BudgetAssignation;
/**
 * Esta es la clase de contrato de proveedor
 *
 * @author Nicolas Dario Ortiz Peña. - Sep. 20 - 2021
 * @version 1.0.0
 */
class ProviderContractController extends AppBaseController {

    /** @var  ProviderContractRepository */
    private $providerContractRepository;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 20 - 2021
     * @version 1.0.0
     */
    public function __construct(ProviderContractRepository $providerContractRepo) {
        $this->providerContractRepository = $providerContractRepo;
    }

    /**
     * Muestra la vista para el CRUD de ProviderContract.
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::provider_contracts.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew"])->latest()->get();
        return $this->sendResponse($provider_contracts->toArray(), trans('data_obtained_successfully'));
    }
    
    public function getContracts(){
        $contracts = ProviderContract::select(["contract_number","object","id"])->where("condition","Activo")->orderBy("object")->get();
        return $this->sendResponse($contracts->toArray(), trans('data_obtained_successfully'));
    }

    public function getContractsByExternalProvider(int $providerId){
        $contracts = ProviderContract::select(["contract_number","object","id"])->where("mant_providers_id",$providerId)->orderBy("object")->get();
        return $this->sendResponse($contracts->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @param CreateProviderContractRequest $request
     *
     * @return Response
     */
    public function store(CreateProviderContractRequest $request) {
        //El usuario que inicia sesion
        $user=Auth::user(); 
        $input = $request->all();
        if($input['start_date']>$input['closing_date']){
            return $this->sendResponse("error", 'La fecha inicial del contrato debe ser anterior a la fecha de cierre del contrato.', 'warning');
        }

        //Crea el campo condicion y lo inicializa en activo
        $input['condition']="Activo";
        
        $users = User::where('id', $input['users_id'])->get();
        $input['vigencia']=date('Y');

        
        //Crea el registro de contrato proovedor
        $providerContract = $this->providerContractRepository->create($input);
        $providerContract->providers;
        
        //Crea un registro de historial contrato proveedor
        $historyProvider=new HistoryProviderContract();
        $historyProvider->name="Registro creado";
        $historyProvider->observation="El registro se creo";
        $historyProvider->name_user=$user->name;
        $historyProvider->users_id=$user->id;
        $historyProvider->value_contract=$providerContract->total_value_contract;
        
        $historyProvider->cd_avaible=$providerContract->total_value_avaible_cdp;       
        $historyProvider->object=$providerContract->object;
        $historyProvider->provider=$providerContract->providers->name;
        $historyProvider->contract_number=$providerContract->contract_number;
        $historyProvider->type_contract=$providerContract->type_contract;
        $historyProvider->condition=$providerContract->condition;
        $historyProvider->dependencias_id=$providerContract->dependencias_id;
        $historyProvider->manager_dependencia=$providerContract->manager_dependencia;
        //Lo guarda
        $historyProvider->save();
        
        
        // Ejecuta el modelo de proveedores
        $providerContract->providers;
        // Ejecuta el modelo de autorización de asiganción presupuestal
        $providerContract->budgetAllocationProviders;
        // // Ejecuta el modelo de documentos del contrato del proveedor
        // $providerContract->documentsProviderContracts;
        // // Ejecuta el modelo de actividades del proveedor
        // $providerContract->importActivitiesProviderContracts;
        // // Ejecuta el modelo de repuestos del proveedor
        // $providerContract->importSparePartsProviderContracts;

        return $this->sendResponse($providerContract->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Nicolas Dario Ortiz P. - Sep. 09 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateProviderContractRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProviderContractRequest $request) {
        //Recupera el usuario que este en sesion
        $user=Auth::user(); 
        $input = $request->all();
        if($input['start_date']>$input['closing_date']){
            return $this->sendResponse("error", 'La fecha inicial del contrato debe ser anterior a la fecha de cierre del contrato.', 'warning');
        }

        
       
        /** @var ProviderContract $providerContract */
        $providerContract = $this->providerContractRepository->find($id);

        //Se valida que si la vigencia futura,no tenga una asignación de rubro asignada
        if ($providerContract->future_validity == 'Si'  &&  $input['future_validity'] == 'No'){
            $budgetAsignation = BudgetAssignation:: where('mant_provider_contract_id', $providerContract->id )->first();
            if (isset($budgetAsignation)) {
                return $this->sendResponse("error", 'No se puede realizar el cambio, debido a que ya existe una asignación presupuestal.', 'warning');
            }
        }
        if (empty($providerContract)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //Actualiza el objeto
        $providerContract = $this->providerContractRepository->update($input, $id);
        //Crea un registro en el historial de contrato proveedor
        $historyProvider=new HistoryProviderContract();
        $historyProvider->name="Registro editado";
        $historyProvider->observation="Se edito el registro";
        $historyProvider->name_user=$user->name;
        $historyProvider->users_id=$user->id;
        $historyProvider->value_contract=$providerContract->total_value_contract;
        $historyProvider->cd_avaible=$providerContract->total_value_avaible_cdp;       
        $historyProvider->object=$providerContract->object;
        $historyProvider->provider=$providerContract->providers->name;
        $historyProvider->contract_number=$providerContract->contract_number;
        $historyProvider->type_contract=$providerContract->type_contract;
        $historyProvider->condition=$providerContract->condition;        
        $historyProvider->dependencias_id=$providerContract->dependencias_id;
        $historyProvider->manager_dependencia=$providerContract->manager_dependencia;
        $historyProvider->save();


        // Condición para validar si existe algún registro de asignación presupuestal
        if (!empty($input['budget_allocation_providers'])) {
            // Eliminar los registros de categorías autorizadas existentes según el id del registro principal
            BudgetAllocationProvider::where('mant_provider_contract_id', $providerContract->id)->delete();
            // Ciclo para recorrer todos los registros de asignación presupuestal
            foreach($input['budget_allocation_providers'] as $option){

                $arrayAllocationProvider = json_decode($option);
                // Se crean la cantidad de registros ingresados por el usuario
                BudgetAllocationProvider::create([
                    'process' => $arrayAllocationProvider->process,
                    'available' => $arrayAllocationProvider->available,
                    'executed' => $arrayAllocationProvider->executed,
                    'dependencias_id' => $arrayAllocationProvider->dependencias_id,
                    'mant_provider_contract_id' => $providerContract->id
                    ]);
            }
        }
        // Ejecuta el modelo de autorización de asiganción presupuestal
        $providerContract->budgetAllocationProviders;
        // // Ejecuta el modelo de documentos del contrato del proveedor
        // $providerContract->documentsProviderContracts;
        // // Ejecuta el modelo de actividades del proveedor
        // $providerContract->importActivitiesProviderContracts;
        // // Ejecuta el modelo de repuestos del proveedor
        // $providerContract->importSparePartsProviderContracts;

        return $this->sendResponse($providerContract->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un ProviderContract del almacenamiento
     *
     * @author Nicolas Dario Ortiz P. - Sep. 09 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Request $request) {
        $user=Auth::user();
        /** @var ProviderContract $providerContract */
        $providerContract = ProviderContract::with(["mantBudgetAssignation", "providers"])->where('id', $request['id'])->get();
        //Verifica que no tenga ninguna asignacion de presupuesto de manera interna
        if(count($providerContract[0]->mantBudgetAssignation)>0){
            return $this->sendResponse("error", 'Debe eliminar primero todos las asignaciones de presupuesto.', 'warning');
        }
        //verifica que la variable no venga vacia
        if (empty($providerContract[0])) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //Crea un registro en el historial de contrato proveedor cuando elimina y se ingresa la observacion
        $historyProvider=new HistoryProviderContract();
        $historyProvider->name="Registro eliminado";
        $historyProvider->observation=$request['observationDelete'];
        $historyProvider->name_user=$user->name;
        $historyProvider->users_id=$user->id;
        $historyProvider->value_contract=$providerContract[0]->total_value_contract;
        $historyProvider->cd_avaible=$providerContract[0]->total_value_avaible_cdp;       
        $historyProvider->object=$providerContract[0]->object;        
        $historyProvider->provider=$providerContract[0]->providers->name;
        $historyProvider->contract_number=$providerContract[0]->contract_number;
        $historyProvider->type_contract=$providerContract[0]->type_contract;
        $historyProvider->condition=$providerContract[0]->condition;        
        $historyProvider->dependencias_id=$providerContract[0]->dependencias_id;
        $historyProvider->manager_dependencia=$providerContract[0]->manager_dependencia;
        $historyProvider->save();
        //Se elimina el registro
        $providerContract[0]->delete();

        //Se retorna el elemento eliminado
        return $this->sendResponse($providerContract[0]->toArray(), trans('msg_success_update'));

    }


    /**
     * Muestra el detalle completo de elemento existente
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function show($id) {
        //Se busca el provider contract con ese id
        $providerContract = $this->providerContractRepository->find($id);
        //Verifica que la variable no venga vacia
        if (empty($providerContract)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //Retorna las relaciones del objeto que se va mostrar
        $providerContract->providers;
        $providerContract->budgetAllocationProviders;
        $providerContract->documentsProviderContracts;
        $providerContract->importActivitiesProviderContracts;
        $providerContract->importSparePartsProviderContracts;
        $providerContract->mantHistoryBudgetAssignation;
        $providerContract->mantBudgetAssignation;
        $providerContract->dependencias;
        //Retorna el objeto
        return $this->sendResponse($providerContract->toArray(), trans('data_obtained_successfully'));
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
        $fileName = time().'-'.trans('provider_contracts').'.'.$fileType;

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
     * Genera el reporte de los contratos de los proveedores en hoja de calculo
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('provider_contracts').'.'.$fileType;
        
        return Excel::download(new ProviderContractExport('maintenance::provider_contracts.report_excel', $input['data'], 'n'), $fileName);
    }



    /**
     * Cambia el estado del contrato
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 15 - 2021
     * @version 1.0.0
     *
     * @param CreateProviderContractRequest $request
     *
     * @return Response
     */
public function newCondition(Request $request){
    //Recupera el usuario en sesion
    $user=Auth::user();
    //Busca el contrato que se le va cambiar el estado
    $providerContract = ProviderContract::with(["mantBudgetAssignation", "providers"])->where('id', $request['id'])->get();
    //Verifica que si el contrato esta activo lo vuelva inactivo
    if(  $providerContract[0]->condition=="Activo"){
        $providerContract[0]->condition="Inactivo";
        $providerContract[0]->save();
    }else{
        //Si no esta activo es porque esta inactivo entonces lo convierte a activo
        $providerContract[0]->condition="Activo";
        $providerContract[0]->save();
    }
    
    //Crea un registro en historial de contrato proveedor cuando le cambia de estado
    $historyProvider=new HistoryProviderContract();
    $historyProvider->name="Registro editado: cambio de estado - ". $providerContract[0]->condition;
    $historyProvider->observation=$request['observationDelete'];
    $historyProvider->name_user=$user->name;
    $historyProvider->users_id=$user->id;
    $historyProvider->value_contract=$providerContract[0]->total_value_contract;
    $historyProvider->cd_avaible=$providerContract[0]->total_value_avaible_cdp;       
    $historyProvider->object=$providerContract[0]->object;        
    $historyProvider->provider=$providerContract[0]->providers->name;
    $historyProvider->contract_number=$providerContract[0]->contract_number;
    $historyProvider->type_contract=$providerContract[0]->type_contract;
    $historyProvider->condition=$providerContract[0]->condition;            
    $historyProvider->dependencias_id=$providerContract[0]->dependencias_id;
    $historyProvider->manager_dependencia=$providerContract[0]->manager_dependencia;
    $historyProvider->save();

    return $this->sendResponse($providerContract[0]->toArray(), trans('msg_success_update'));

}

    /**
     * Muestra la vista para el CRUD de ProviderContract.
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 15 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexBudget(Request $request) {
        return view('maintenance::budget.index');
    }


        /**
     * Muestra la vista para el CRUD de ProviderContract.
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 15 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexExecution(Request $request) {
        
        return view('maintenance::execution_budget_look.index')->with("mpc", $request['mpc'] ?? null);;
    }


                /**
     * Envia todos los contratos dependiendo de la dependencia
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 15 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function contractDependencia(Request $request) {

    if (Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Consulta general') ){

        $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew"])->latest()->get();
        return $this->sendResponse($provider_contracts->toArray(), trans('data_obtained_successfully'));
        
        return $this->sendResponse($executionitem, trans('data_obtained_successfully'));

    }else{

        $user=Auth::user();

        $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew"])->where('dependencias_id', $user->id_dependencia)->latest()->get();
        return $this->sendResponse($provider_contracts->toArray(), trans('data_obtained_successfully'));
        
        return $this->sendResponse($executionitem, trans('data_obtained_successfully'));

    }
        
    }

    
            /**
     * Envia todas las ejecuciones de presupuesto dependiendo el contrato proveedor
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 15 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function allExecution(Request $request) {
        $executionitem=[];
        //retorna todo los contratos
        $providerContract = ProviderContract::with(["mantBudgetAssignation", "providers"])->where('id', $request['mpc'])->get();
        // recorre todos los contratos de los proveedores
        foreach ($providerContract as $valueProvider) {
            //Recorre la relacion de asignacion de presupuesto
            foreach ($valueProvider->mantBudgetAssignation as  $valueAssignation) {
                //Recorre la relacion de rubros
                foreach ($valueAssignation->mantAdministrationCostItems as  $valueItem) {
                    //Recorre la relacion de ejecucion de presupuesto
                    foreach ($valueItem->mantBudgetExecutions as $valueExecution) {
                        # code...
                        $valueExecution->mantAdministrationCostItems;
                        $executionitem[]=$valueExecution;
                    }
                }
            }
            
        }
        
        return $this->sendResponse($executionitem, trans('data_obtained_successfully'));
    }

              /**
     * Envia todas las ejecuciones de presupuesto dependiendo el contrato proveedor
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 15 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getfechasfestivas() {
        
        
        $holidaycalentar = HolidayCalendar::select("date")->get()->toArray();
        // dd($holidaycalentar);
        return $this->sendResponse($holidaycalentar, trans('data_obtained_successfully'));
    }
                  /**
     * Envia todas las ejecuciones de presupuesto dependiendo el contrato proveedor
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 15 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getDayToday() {
        
        
        $holidaycalentar = 5;
        // dd($holidaycalentar);
        return $this->sendResponse($holidaycalentar, trans('data_obtained_successfully'));
    }

                    /**
     * Envia todas las ejecuciones de presupuesto dependiendo el contrato proveedor
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 15 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getGeneralHour() {
        
        $variable=[];

        $variable[0]=8;
        $variable[1]=12;
        $variable[2]=14;
        $variable[3]=18;
        $variable[4]=45;
        return $this->sendResponse($variable, trans('data_obtained_successfully'));
    }
}
