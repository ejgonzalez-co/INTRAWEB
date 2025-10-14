<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateBudgetAssignationRequest;
use Modules\Maintenance\Http\Requests\UpdateBudgetAssignationRequest;
use Modules\Maintenance\Repositories\BudgetAssignationRepository;
use App\Http\Controllers\AppBaseController;
use Modules\Maintenance\Models\BudgetAssignation;
use Modules\Maintenance\Models\HistoryBudgetAssignation;
use App\Exports\Maintenance\ContractExport\AssignationExport;
use Modules\Maintenance\Models\ContractNews;
use Modules\Maintenance\Models\ProviderContract;
use Modules\Maintenance\Models\HistoryContractNews;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;


/**
 * Esta es la clase paraa la asignacion presupuestal
 *
 * @author Nicolas Dario Ortiz P. - Sep. 20 - 2021
 * @version 1.0.0
 */
class BudgetAssignationController extends AppBaseController {

    /** @var  BudgetAssignationRepository */
    private $budgetAssignationRepository;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz P. - Sep. 20 - 2021
     * @version 1.0.0
     */
    public function __construct(BudgetAssignationRepository $budgetAssignationRepo) {
        $this->budgetAssignationRepository = $budgetAssignationRepo;
    }

    /**
     * Muestra la vista para el CRUD de BudgetAssignation.
     *
     * @author Nicolas Dario Ortiz P. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        //Busca el contrato proveedor y lo envia para incializarlo
        $providerContract=ProviderContract::with('providers')->find($request['mpc']);
        return view('maintenance::budget_assignations.index', compact('providerContract'))->with("mpc", $request['mpc'] ?? null);
    }

        /**
     * Muestra el detalle completo de elemento existente
     *
     * @author Nicolas Dario Ortiz P. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function show($id) {
        //Busca el elemento y posteriormente envia sus relaciones
        $budgetAssignation=$this->budgetAssignationRepository->find($id);
        $budgetAssignation->mantAdministrationCostItems;
        $budgetAssignation->mantHistoryAdministrationCostItems;

        return $this->sendResponse($budgetAssignation->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Nicolas Dario Ortiz P. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        //Envia todos los registros de asignacion con su respectiva relacion
        $budget_assignations = BudgetAssignation::with(['mantProviderContract'])->where('mant_provider_contract_id', $request['mpc'])->latest()->get();

        return $this->sendResponse($budget_assignations->toArray(), trans('data_obtained_successfully'));
    }

      /**
     * Guarda una novedad en el contrato
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 13 - 2021
     * @version 1.0.0
     *
     * @param CreateBudgetAssignationRequest $request
     *
     * @return Response
     */
    public function saveNewContract(Request $request){
        //Recupera el usuario en sesion
        $user=Auth::user();
        //Busca el objeto que se va guardar
        $budgetAssignations = BudgetAssignation::with(['mantProviderContract', 'mantAdministrationCostItems'])->where('id', $request['id'])->get();
        //Se crea un objeto que es noticia nueva en el contrato
        $newContract=new ContractNew();
        //Verifica que si envien el cdp modificado se actualice en el objeto
        if($request['cdp_modify']){
            $newContract->cdp_modify=$request['cdp_modify'];
            $budgetAssignations[0]->value_cdp=$budgetAssignations[0]->value_cdp+$request['cdp_modify'];
        }else{
            //Verifica que si envien el valor del contrato se modifique en el objeto
            if($request['contract_modify']){
                $value= $budgetAssignations[0]->value_contract+$request['contract_modify'];
                //Verifica que el valor del contrato nuevo tiene que ser menor al valor del cdp
                if($value<$budgetAssignations[0]->value_cdp){
                    //Guarda el dato con el contrato modificado
                    $newContract->contract_modify=$request['contract_modify'];
                    $budgetAssignations[0]->value_contract=$budgetAssignations[0]->value_contract+$request['contract_modify'];
                }else{
                    //Retorna un mensaje de error
                    return $this->sendResponse("error", 'El valor del contrato debe ser menor que el valor del cdp.', 'warning');
                }
                
            }
        }
        //Se le resta y se le asigna el nuevo cdp disponible
        $budgetAssignations[0]->cdp_available=$budgetAssignations[0]->value_cdp-$budgetAssignations[0]->value_contract;
        //Se asignan los valores a novedades del contrato
        $newContract->mant_provider_contract_id=$request['mant_provider_contract_id'];
        $newContract->type_new=$request['type_new'];
        $newContract->date_new=$request['date_new'];
        $newContract->observation=$request['observationNews'];
        $newContract->users_id=$user->id;
        $newContract->name_user=$user->name;
        $budgetAssignations[0]->save();
        $newContract->save();
        //Se crea un registro en el historial de asignacion de presupuesto
        $historyAssignation->name="Registro creado";
        $historyAssignation=new ContractNews();
        $historyAssignation->name="Novedad en el contrato: " . $request['type_new'];
        $historyAssignation->observation=$request['observationNews'];
        $historyAssignation->name_user=$user->name;
        $historyAssignation->value_cdp= $budgetAssignations[0]->value_cdp;
        $historyAssignation->value_contract=$budgetAssignations[0]->value_contract;
        $historyAssignation->cdp_available=$budgetAssignations[0]->cdp_available;
        $historyAssignation->users_id=$user->id;
        $historyAssignation->mant_provider_contract_id=$budgetAssignations[0]->mant_provider_contract_id;
        $historyAssignation->save();

        return $this->sendResponse($budgetAssignations[0]->toArray(), trans('msg_success_update'));
    }

    /**ContractNews
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 13 - 2021
     * @version 1.0.0
     *
     * @param CreateBudgetAssignationRequest $request
     *
     * @return Response
     */
    public function store(CreateBudgetAssignationRequest $request) {
        $user=Auth::user();
        $contract = ProviderContract:: select('future_validity')->where('id',$request['mant_provider_contract_id'])->get()->first();
        //Valida que el valor del contrato sea menor o igual al valor del cdp
        if($request['value_contract'] <= $request['value_cdp']  ||  $contract->future_validity =='Si'){
            $input = $request->all();
            

            try {
                $user=Auth::user();                    
                $input['name_user']=$user->name;
                $input['users_id']=$user->id;

                if (array_key_exists('attachment', $input)) {
                        $input['attachment'] = implode(",", $input["attachment"]);
                    }
                // Inserta el registro en la base de datos
                $budgetAssignation = $this->budgetAssignationRepository->create($input);
                //Crea el historial de asignacion de presupuesto

                $newContract = new ContractNews();
                $newContract->novelty_type="Asignación presupuestal";
                $newContract->consecutive="01";
                $newContract->observation=$input['observation'];
                // $newContract->attachment=$input['attachment'];
                $newContract->attachment = isset($input['attachment']) ? $input['attachment'] : NULL;
                $newContract->consecutive_cdp=$budgetAssignation->consecutive_cdp;
                $newContract->name_user=$user->name;
                $newContract->value_cdp= $budgetAssignation->value_cdp;
                $newContract->value_contract=$budgetAssignation->value_contract;
                $newContract->cdp_available=$budgetAssignation->cdp_available;
                $newContract->users_id=$user->id;
                $newContract->mant_provider_contract_id=$budgetAssignation->mant_provider_contract_id;
                $newContract->save();

                
                $historyAssignation=new HistoryBudgetAssignation();
                $historyAssignation->novelty_type="Asignación presupuestal";
                $historyAssignation->consecutive="01";
                $historyAssignation->name="Registro creado";
                $historyAssignation->observation='El registro se creo';
                $historyAssignation->consecutive_cdp=$budgetAssignation->consecutive_cdp;
                $historyAssignation->name_user=$user->name;
                $historyAssignation->value_cdp= $budgetAssignation->value_cdp;
                $historyAssignation->value_contract=$budgetAssignation->value_contract;
                $historyAssignation->cdp_avaible=$budgetAssignation->cdp_available;
                $historyAssignation->users_id=$user->id;
                $historyAssignation->mant_provider_contract_id=$budgetAssignation->mant_provider_contract_id;
                $historyAssignation->save();

                $historyContractNews=new HistoryContractNews();
                $historyContractNews->users_id=$user->id;
                $historyContractNews->mant_contract_news_id  = $newContract->id;
                $historyContractNews->user_name=$user->name;
                $historyContractNews->description = 'Registro creado';
                $historyContractNews->novelty_type = "Asignación presupuestal";
                $historyContractNews->save();

                return $this->sendResponse($budgetAssignation->toArray(), trans('msg_success_save'));
            } catch (\Illuminate\Database\QueryException $error) {
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\BudgetAssignationController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\BudgetAssignationController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
                // Retorna error de tipo logico
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
            }

        } else{
                //Si el valor del cdp es menor que el valor del contrato
                return $this->sendResponse("error", 'El valor del contrato debe ser menor o igual al valor del CDP.', 'warning');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateBudgetAssignationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBudgetAssignationRequest $request) {
        //Recupera usuario en sesion
        $user=Auth::user();
        $input = $request->all();
        if (array_key_exists('attachment', $input)) {
            $input['attachment'] = implode(",", $input["attachment"]);
        }
        /** @var BudgetAssignation $budgetAssignation */
        $budgetAssignation = $this->budgetAssignationRepository->find($id);

        if (empty($budgetAssignation)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {

             // Actualiza el registro
             $budgetAssignation = $this->budgetAssignationRepository->update($input, $id);

            $newContract=  ContractNews:: where('mant_provider_contract_id', $input['mant_provider_contract_id'])->where('novelty_type','Asignación presupuestal')->get()->first();
            $newContract->observation=$input['observation'];
            $newContract->attachment=isset($input['attachment']) ? $input['attachment'] : NULL;
            $newContract->consecutive_cdp=$budgetAssignation->consecutive_cdp;
            $newContract->value_cdp= $budgetAssignation->value_cdp;
            $newContract->value_contract=$budgetAssignation->value_contract;
            $newContract->cdp_available=$budgetAssignation->cdp_available;
            $newContract->save();
            
           
            //Crea un registro de historial de asignacion presupuestal
            $historyAssignation=new HistoryBudgetAssignation();
            $historyAssignation->name="Registro editado";
            $historyAssignation->observation="El registro se edito";
            $historyAssignation->name_user=$user->name;
            $historyAssignation->value_cdp= $budgetAssignation->value_cdp;
            $historyAssignation->value_contract=$budgetAssignation->value_contract;
            $historyAssignation->cdp_avaible=$budgetAssignation->cdp_available;
            $historyAssignation->users_id=$user->id;
            $historyAssignation->mant_provider_contract_id=$budgetAssignation->mant_provider_contract_id;
            $historyAssignation->save();

            $historyContractNews=new HistoryContractNews();
            $historyContractNews->created_at= $newContract->updated_at;
            $historyContractNews->users_id=$user->id;
            $historyContractNews->mant_contract_news_id  = $newContract->id;
            $historyContractNews->user_name=$user->name;
            // $historyContractNews->date_previus_contract_term=$datePreviusContractTerm;
            // $historyContractNews->date_contract_term = $input['date_contract_term'];
            $historyContractNews->description = 'Registro modificado';
            // $historyContractNews->novelty_type = $input['novelty_type'];
            $historyContractNews->save();
        
            return $this->sendResponse($budgetAssignation->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\BudgetAssignationController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\BudgetAssignationController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un BudgetAssignation del almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Request $request) {
        //Recupera usuario en sesion
        $user=Auth::user();
        /** @var BudgetAssignation $budgetAssignation */
        $budgetAssignation = BudgetAssignation::with(['mantAdministrationCostItems'])->where('id', $request['id'])->get();
        //Verifica que no tenga registros de rubros
        if(count($budgetAssignation[0]->mantAdministrationCostItems)>0){
            return $this->sendResponse("error", 'Debe eliminar primero todos los rubros.', 'warning');
        }

        if (empty($budgetAssignation[0])) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        try {
            // Elimina el registro
            $budgetAssignation[0]->delete();
            //Crea un registro de historial de asignacion

            $contractNew = ContractNews:: where('mant_provider_contract_id',$request['mant_provider_contract_id'] )->delete();  

            $historyAssignation=new HistoryBudgetAssignation();
            $historyAssignation->name="Registro eliminado";
            $historyAssignation->observation=$request['observationDelete'];
            $historyAssignation->name_user=$user->name;
            $historyAssignation->value_cdp= $budgetAssignation[0]->value_cdp;
            $historyAssignation->value_contract=$budgetAssignation[0]->value_contract;
            // $historyAssignation->cdp_available=$budgetAssignation[0]->cdp_available;
            $historyAssignation->users_id=$user->id;
            $historyAssignation->mant_provider_contract_id=$budgetAssignation[0]->mant_provider_contract_id;
            $historyAssignation->save();

            return $this->sendResponse($budgetAssignation[0]->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\BudgetAssignationController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\BudgetAssignationController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

        /**
     * Genera el reporte de excel
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
        $fileName = date('Y-m-d H:i:s').'-'.trans('Asignación presupuestal').'.'.$fileType;
        
        return Excel::download(new AssignationExport('maintenance::budget_assignations.report_excel', $input['data'],'g'), $fileName);
    }

}
