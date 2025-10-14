<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateContractNewsRequest;
use Modules\Maintenance\Http\Requests\UpdateContractNewsRequest;
use Modules\Maintenance\Repositories\ContractNewsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Maintenance\Models\ContractNews;
use Modules\Maintenance\Models\ProviderContract;
use Modules\Maintenance\Models\BudgetAssignation;
use Modules\Maintenance\Models\HistoryContractNews;
use Modules\Maintenance\Models\HistoryBudgetAssignation;
use Modules\Maintenance\Models\AdministrationCostItem;
use Modules\Maintenance\Models\ButgetExecution;
use Illuminate\Support\Carbon;
use Modules\Maintenance\Models\AddingCostItem;


/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ContractNewsController extends AppBaseController {

    /** @var  ContractNewsRepository */
    private $contractNewsRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ContractNewsRepository $contractNewsRepo) {
        $this->contractNewsRepository = $contractNewsRepo;
    }

    /**
     * Muestra la vista para el CRUD de ContractNews.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        // dd($request->toArray());

        
        // Extrae el id del contrato
        $budgetAssignation=BudgetAssignation::where('id', $request['mpc'])->get()->first();
        $consecutive = $budgetAssignation->consecutive_cdp;
       //Busca el contrato proveedor y lo envia para incializarlo
       $providerContract=ProviderContract::with(['providers'])->find($budgetAssignation->mant_provider_contract_id);
       $rubrosItems = DB::table('mant_administration_cost_items')->where('mant_budget_assignation_id', $request['mpc'])->whereNull('deleted_at')->get();
        return view('maintenance::contract_news.index', compact(['providerContract','rubrosItems']))->with("mpc", $request['mpc'] ?? null)->with("addingCostItem", 'addingCostItem' ?? null);
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
       // Extrae el id del contrato
       $providerContract=BudgetAssignation::find($request['mpc']);
       // consulta el contrato con el id anterior
       $new_contracts = ContractNews::with(['historyNovelty'])->where('mant_provider_contract_id',$providerContract->mant_provider_contract_id)->latest()->get();
       return $this->sendResponse($new_contracts->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Consulta los datos que se mostraran en la tabla a la hora de editar
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function edit($id, $optional = '')
    {
        // $nuevo = ContractNews::where('id', $id)->get()->toArray();
        // $new_contracts = ContractNews::with(['historyNovelty','addingCostItem'])->where('mant_provider_contract_id',$nuevo[0]['mant_provider_contract_id'])->latest()->get();
        $new_contracts = ContractNews::with(['historyNovelty','addingCostItem'])->where('id', $id)->latest()->get();

        // dd($new_contracts->toArray());
        $addingCostItem = DB::table('mant_adding_cost_item')->where('mant_contract_news_id', $id)->whereNull('deleted_at')->get();

        $mergedData = array_merge(
            $new_contracts->toArray()[0],
            $addingCostItem->toArray()
          );

          $mergedData['addingCostItem'] = $addingCostItem;
        return $this->sendResponse($mergedData, trans('data_obtained_successfully'));
        // return $this->sendResponse($new_contracts, trans('data_obtained_successfully'));  
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateContractNewsRequest $request
     *
     * @return Response
     */
    public function store(CreateContractNewsRequest $request) {

    
        $user=Auth::user();
        $input = $request->all();
                  
        $input['name_user']=$user->name;
        $input['users_id']=$user->id;

        // Valida si se ingresa la imagen el adjunto 
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $name = time().$file->getClientOriginalName();
            $file = $name;
            $url = explode(".", $file);

            $input['attachment'] = substr($input['attachment']->store('public/documents/maintenance'), 7);

        }
        // Se genera el consecutivo segun el tipo de novedad.
        $input['consecutive'] = $this->getConsecutive($input['mant_provider_contract_id'],$input['novelty_type']);
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $contractNews = $this->contractNewsRepository->create($input);

            // se consulta el contrato con el fin de modificar la fecha de terminacion mas adelante
            $providerContract = ProviderContract::where('id',$input['mant_provider_contract_id'])->get()->first();
            //se guarda la fecha de terminacion de contrato anterior para mas adelante guardarla en el historial
            $datePreviusContractTerm = $providerContract->closing_date ;
            // consulta la asignación del presupuesto inicial.
            $budget = BudgetAssignation :: where('mant_provider_contract_id', $input['mant_provider_contract_id'] )->first();
        
            // se instancian los historiales
            $historyContractNews=new HistoryContractNews();
            $historyAssignation=new HistoryBudgetAssignation();



        if ($input['novelty_type'] == 'Prórroga') {
            // cambia la fecha de finalización del contrato en el formulario de contrato de proveedores
             $providerContract->closing_date = $input['date_contract_term'];
             $providerContract->save();

            $historyAssignation->name="Prórroga creada";

        }
        if ($input['novelty_type'] == 'Suspensión' || $input['novelty_type'] == 'Reinicio') {
            if ( $input['novelty_type']== 'Reinicio' ) {
                $providerContract->closing_date = $input['date_contract_term'];
                $historyAssignation->name = "Reinicio creado";
            }if ($input['novelty_type']== 'Suspensión' && $input['type_suspension']== 'Término fijo' ) {
                $providerContract->closing_date = $input['date_contract_term'];
                $historyAssignation->name = "Suspensión creada";
            }
            $providerContract->save();
            
        }
        if ($input['novelty_type'] == 'Adición al contrato') {
            
            // Arreglo donde se van a guardar la lista de rubros
            $rubroArray = [];
            // Inicializar la variable para almacenar la suma de los valores que entran
            $sumaValueEdition = 0;
       
            // Iterar sobre cada elemento del array para sumar los valores entrantes
            foreach ($input['rubrosItems'] as $item) {
                // Decodificar el JSON y sumar el valor de 'value_edition'
                $decodedItem = json_decode($item, true);
                $sumaValueEdition += $decodedItem['value_eddition'];
            }
            // Verificar si la suma de los valores de 'value_edition' es igual al valor del CDP
            if ($input['cdp_available'] != $sumaValueEdition) {
                return $this->sendSuccess('La distribución del valor del CDP en los rubros excede los límites establecidos. Para poder continuar,
                asegúrese de que el valor sea igual al valor del CDP, ya que no puede ser mayor ni menor al valor del CDP.', 'error');
            }
            // dd(json_decode($input['rubrosItems'][0]));
            // Adicionar el valor al contrato
            $budget->old_value = $budget->value_contract;
            $budget->value_contract += $input['value_cdp'];
            $budget->update();
            // Iterar sobre cada elemento del array para actualizar los valores en la base de datos
            foreach ($input['rubrosItems'] as $item) {
                // Decodificar el JSON y obtener información del rubro
                $decodedItem = json_decode($item, true);
                $id = $decodedItem['id'];
        
                // Consultar el valor actual del rubro y adicionar el valor ingresado en los inputs.
                $valorActual = AdministrationCostItem::find($id);
                $valorActual->value_eddition = $decodedItem['value_eddition'];
                $valorActual->value_item_old = $decodedItem['value_item'];
                $valorActual->value_item += $decodedItem['value_eddition'];
                $valorActual->update();

                AddingCostItem:: create(['mant_contract_news_id'=> $contractNews->id,
                'mant_administration_cost_items_id' => $valorActual->id,
                'value_addition'=>$valorActual->value_eddition,
                'value_item_old'=>$valorActual->value_item_old,
                'code_cost'=> $valorActual->code_cost,
                'name'=> $valorActual->name,
                'cost_center'=> $valorActual->cost_center,
                'cost_center_name'=> $valorActual->cost_center_name,
                'value_item'=> $valorActual->value_item]);
        
              
                // Consultar ejecuciones asociadas al rubro
                $budgetExecutions = ButgetExecution::where('mant_administration_cost_items_id', $valorActual->id)->get();
        
                // Verificar si hay resultados en la consulta antes de iterar
                if ($budgetExecutions->isNotEmpty()) {
                    foreach ($budgetExecutions as $budgetExecution) {



                        // Actualizar el nuevo valor disponible y el porcentaje de ejecución
                        $budgetExecution->update([
                            'old_value_available' => $budgetExecution->new_value_available,
                            'new_value_available' => $budgetExecution->new_value_available + $decodedItem['value_eddition']
                        ]);
        
                        $percentage = ($budgetExecution->executed_value / $valorActual->value_item) * 100;
                        $budgetExecution->update(['percentage_execution_item' => $percentage]);
                    }
                }

                $valorActual = AdministrationCostItem::find($id);
                $valorActual->value_eddition = '';
                $valorActual->update();

                
            }
        
            // Actualizar el nombre en la asignación de historial
            $historyAssignation->name = "Adición creada";
        }

        $historyContractNews->users_id=$user->id;
        $historyContractNews->mant_contract_news_id  = $contractNews->id;
        $historyContractNews->user_name=$user->name;
        $historyContractNews->description = 'Registro creado';
        $historyContractNews->novelty_type = $input['novelty_type'];
        $historyContractNews->date_previus_contract_term=isset($datePreviusContractTerm) ?$datePreviusContractTerm : '';
        $historyContractNews->date_contract_term = isset($input['date_contract_term']) ? $input['date_contract_term'] : 'NA';


        $historyAssignation->novelty_type= $input['novelty_type'];
        $historyAssignation->consecutive=$contractNews->consecutive;
        $historyAssignation->observation='El registro se creo';
        $historyAssignation->consecutive_cdp=$contractNews->consecutive_cdp;
        $historyAssignation->name_user=$user->name;
        $historyAssignation->value_cdp = isset($input['value_cdp']) ? $input['value_cdp'] : $budget->value_cdp;
        $historyAssignation->users_id=$user->id;
        $historyAssignation->mant_provider_contract_id=$contractNews->mant_provider_contract_id;
        $historyAssignation->value_contract = $budget->value_contract;
        $historyAssignation->cdp_avaible=isset($contractNews->cdp_available)? $contractNews->cdp_available: 'NA';
        $historyAssignation->consecutive_cdp =  $budget->consecutive_cdp;
        $historyAssignation->number_cdp = isset($input['number_cdp']) ? $input['number_cdp'] : 'NA';
        $historyAssignation->date_modification = isset($input['date_modification']) ? $input['date_modification'] : 'NA';
        $historyAssignation->time_extension = isset($input['time_extension']) ? $input['time_extension'] : NULL;
        $historyAssignation->date_contract_term = isset($input['date_contract_term']) ? $input['date_contract_term'] : 'NA';
        $historyAssignation->type_suspension = isset($input['type_suspension']) ? $input['type_suspension'] : NULL;
        $historyAssignation->date_start_suspension = isset($input['date_start_suspension']) ? $input['date_start_suspension'] : NULL;
        $historyAssignation->time_suspension = isset($input['time_suspension']) ? $input['time_suspension'] : 'NA';
        $historyAssignation->date_act_suspension = isset($input['date_act_suspension']) ? $input['date_act_suspension'] : NULL;
        $historyAssignation->date_end_suspension = isset($input['date_end_suspension']) ? $input['date_end_suspension'] : 'NA';
        $historyAssignation->date_last_suspension = isset($input['date_last_suspension']) ? $input['date_last_suspension'] : NULL;

        $historyContractNews->save();
        $historyAssignation->save();
        // se guarda la relacion
        $contractNews->historyNovelty;
        $contractNews->rubrosItems;
        $contractNews->addingCostItem;

            // Efectua los cambios realizados
            DB::commit();
// Recarga la página usando AJAX
// return $this->dispatchBrowserEvent('refresh');
            return $this->sendResponse($contractNews->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\ContractNewsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\ContractNewsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateContractNewsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractNewsRequest $request) {

        $user=Auth::user();
        $input = $request->all();
        // Valida si se ingresa un archivi
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $name = time().$file->getClientOriginalName();
            $file = $name;
            $url = explode(".", $file);
            
                $input['attachment'] = substr($input['attachment']->store('public/documents/maintenance'), 7);
        
        }

        /** @var ContractNews $contractNews */
        $contractNews = $this->contractNewsRepository->find($id);

        if (empty($contractNews)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $contractNews = $this->contractNewsRepository->update($input, $id);

            // se consulta el contrato con el fin de modificar la fecha de terminacion mas adelante
            $providerContract = ProviderContract::where('id',$input['mant_provider_contract_id'])->get()->first();
            //se guarda la fecha de terminacion de contrato anterior para mas adelante guardarla en el historial
            $datePreviusContractTerm = $providerContract->closing_date ;
             // consulta la asignación del presupuesto inicial.
             $budget = BudgetAssignation:: where('mant_provider_contract_id', $input['mant_provider_contract_id'] )->first();

            // se instancian los historiales
            $historyContractNews=new HistoryContractNews();
            $historyAssignation=new HistoryBudgetAssignation();

            if ( $input['novelty_type'] == 'Prórroga') {
                // cambia la fecha de finalización del contrato en el formulario de contrato de proveedores
                $providerContract->closing_date = $input['date_contract_term'];
                $providerContract->save();

                $historyAssignation->name="Prórroga modificada";
               
            }
            if ($input['novelty_type'] == 'Suspensión' || $input['novelty_type'] == 'Reinicio') {
                if ( $input['novelty_type']== 'Reinicio' ) {
                    $providerContract->closing_date = $input['date_contract_term'];
                    $historyAssignation->name = "Reinicio modificado";
                }if ($input['novelty_type']== 'Suspensión' && $input['type_suspension']== 'Término fijo' ) {
                    $providerContract->closing_date = $input['date_contract_term'];
                    $historyAssignation->name = "Suspensión modificada";
                }
                $providerContract->save();
                
            }
            if ($input['novelty_type'] == 'Adición al contrato') {
                // Arreglo donde se van a guardar la lista de rubros
                $rubroArray = [];
                // Inicializar la variable para almacenar la suma de los valores que entran
                $sumaValueEdition = 0;
            
                // Iterar sobre cada elemento del array para sumar los valores entrantes
                foreach ($input['adding_cost_item'] as $item) {
                    // Decodificar el JSON y sumar el valor de 'value_edition'
                    $decodedItem = json_decode($item, true);
                    $sumaValueEdition += $decodedItem['value_addition'];
                }
           
             
                // Verificar si la suma de los valores de 'value_edition' es igual al valor del CDP
                if ($input['cdp_available'] != $sumaValueEdition) {
                    return $this->sendSuccess('La distribución del valor del CDP en los rubros excede los límites establecidos. Para poder continuar, 
                    asegúrese de que el valor sea igual al valor del CDP, ya que no puede ser mayor ni menor al valor del CDP.', 'error');
                }
             

               
            
                // Adicionar el valor al contrato
                $budget->value_contract =  $budget->old_value;
                $budget->value_contract += $input['value_cdp'];
                $budget->update();
            
                // Iterar sobre cada elemento del array para actualizar los valores en la base de datos
                foreach ($input['adding_cost_item'] as $item) {
                    
                    // Decodificar el JSON y obtener información del rubro
                    $decodedItem = json_decode($item, true);
                    $id = $decodedItem['mant_administration_cost_items_id'];
                    
            
                    // Consultar el valor actual del rubro y adicionar el valor ingresado en los inputs.
                    $valorActual = AdministrationCostItem::find($id);
                    $old = $valorActual->value_item_old;
                    $valorActual->value_item = $valorActual->value_item_old;
                    $valorActual->value_eddition = 0;
                    // $valorActual->value_item_old = 0;
                    $valorActual->value_eddition = $decodedItem['value_addition'];
                    $valorActual->value_item += $decodedItem['value_addition'];
                    $valorActual->update();

                    $flag =  AddingCostItem:: where('mant_contract_news_id',$contractNews->id)
                    ->where('mant_administration_cost_items_id',$id)->first();
                    $flag->value_addition = $decodedItem['value_addition'];
                    $flag->value_item_old = $valorActual->value_item_old;
                    $flag->update();

                    // Consultar ejecuciones asociadas al rubro
                    $budgetExecutions = ButgetExecution::where('mant_administration_cost_items_id', $valorActual->id)->get();
            
                    // Verificar si hay resultados en la consulta antes de iterar
                    if ($budgetExecutions->isNotEmpty()) {
                        foreach ($budgetExecutions as $budgetExecution) {
                            // Actualizar el nuevo valor disponible y el porcentaje de ejecución
                            $budgetExecution->update([
                                'new_value_available' => $budgetExecution->old_value_available + $decodedItem['value_addition']
                            ]);
            
                            $percentage = ($budgetExecution->executed_value / $valorActual->value_item) * 100;
                            $budgetExecution->update(['percentage_execution_item' => $percentage]);
                        }
                    }
                }
            
                // Actualizar el nombre en la asignación de historial
                $historyAssignation->name = "Adición editada";
            }
    

            $historyContractNews->created_at = $contractNews->updated_at;
            $historyContractNews->users_id=$user->id;
            $historyContractNews->mant_contract_news_id  = $contractNews->id;
            $historyContractNews->user_name=$user->name;
            $historyContractNews->description = 'Registro modificado';
            $historyContractNews->novelty_type = $input['novelty_type'];
            $historyContractNews->date_previus_contract_term=isset($datePreviusContractTerm) ?$datePreviusContractTerm : '';
            $historyContractNews->date_contract_term = isset($input['date_contract_term']) ? $input['date_contract_term'] : 'NA';


            $historyAssignation->created_at = $contractNews->updated_at;
            $historyAssignation->novelty_type= $input['novelty_type'];
            $historyAssignation->consecutive=$contractNews->consecutive;
            $historyAssignation->observation='El registro se modificó';
            $historyAssignation->consecutive_cdp=$contractNews->consecutive_cdp;
            $historyAssignation->name_user=$user->name;
            $historyAssignation->value_cdp = isset($input['value_cdp']) ? $input['value_cdp'] : $budget->value_cdp;
            $historyAssignation->users_id=$user->id;
            $historyAssignation->mant_provider_contract_id=$contractNews->mant_provider_contract_id;
            $historyAssignation->value_contract = $budget->value_contract;
            $historyAssignation->cdp_avaible=isset($contractNews->cdp_available)? $contractNews->cdp_available: NULL;
            $historyAssignation->consecutive_cdp =  $budget->consecutive_cdp;
            $historyAssignation->number_cdp = isset($input['number_cdp']) ? $input['number_cdp'] : NULL;
            $historyAssignation->date_modification = isset($input['date_modification']) ? $input['date_modification'] : NULL;
            $historyAssignation->time_extension = isset($input['time_extension']) ? $input['time_extension'] : NULL;
            $historyAssignation->date_contract_term = isset($input['date_contract_term']) ? $input['date_contract_term'] : NULL;
            $historyAssignation->type_suspension = isset($input['type_suspension']) ? $input['type_suspension'] : NULL;
            $historyAssignation->date_start_suspension = isset($input['date_start_suspension']) ? $input['date_start_suspension'] : NULL;
            $historyAssignation->time_suspension = isset($input['time_suspension']) ? $input['time_suspension'] : NULL;
            $historyAssignation->date_act_suspension = isset($input['date_act_suspension']) ? $input['date_act_suspension'] : NULL;
            $historyAssignation->date_end_suspension = isset($input['date_end_suspension']) ? $input['date_end_suspension'] : NULL;
            $historyAssignation->date_last_suspension = isset($input['date_last_suspension']) ? $input['date_last_suspension'] : NULL;



            $historyContractNews->save();
            $historyAssignation->save();

            $contractNews->historyNovelty;
            

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($contractNews->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\ContractNewsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\ContractNewsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ContractNews del almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function elimina($mpc, $id) {

        // dd($request->toArray());
        /** @var ContractNews $contractNews */
        $contractNews = $this->contractNewsRepository->find($id);

        if ($contractNews->novelty_type == 'Adición al contrato' ) {
        $rubros = AdministrationCostItem::where('mant_budget_assignation_id',$mpc)->get();

        foreach ( $rubros as $item) {
            // Decodificar el JSON y obtener información del rubro
            $decodedItem =  $item->toArray();
            $id2 = $decodedItem['id'];
    
            $rubroActual = AdministrationCostItem::find($id2);
            
             
          
            // Consultar ejecuciones asociadas al rubro
            $budgetExecutions = ButgetExecution::where('mant_administration_cost_items_id', $rubroActual->id)->get();
    
            // Verificar si hay resultados en la consulta antes de iterar
            if ($budgetExecutions->isNotEmpty()) {

                foreach ($budgetExecutions as $budgetExecution) {
                    $contractNews->created_at = Carbon::now()->format('Y-m-d');
                    if ($contractNews->created_at <= $budgetExecution->date) {
                        return $this->sendSuccess('No es posible eliminar la novedad, debido a que existe ejecución presupuestal en:  rubro '.$rubroActual->name. '<br> Centro de costo: ' .$rubroActual->cost_center_name. '<br> Número de acta:'.$budgetExecution->minutes , 'info');
                    }
                }
                foreach ($budgetExecutions as $budgetExecutionRestart) {
                    // Actualizar el nuevo valor disponible y el porcentaje de ejecución
                    if ($budgetExecutionRestart->old_value_available >= 0) {

                        $budgetExecutionRestart->update([
                            'new_value_available' => $budgetExecutionRestart->old_value_available,
                            'old_value_available' => 0
                        ]);
                    }
                    else{

                        $budgetExecutionRestart->update([
                            'new_value_available' => $budgetExecutionRestart->new_value_available - $rubroActual->value_edition
                        ]);
                        
                    $percentage = ($budgetExecutionRestart->executed_value / $rubroActual->value_item_old ) * 100;
                    $budgetExecutionRestart->update(['percentage_execution_item' => $percentage]);
                    }
                }

                $rubroActual->value_item = $rubroActual->value_item_old;
                $rubroActual->value_edition = '';
                $rubroActual->update();  
            }
        }

           // Consultar el valor actual del rubro y adicionar el valor ingresado en los inputs.
           $budget = BudgetAssignation :: where('mant_provider_contract_id',$contractNews->mant_provider_contract_id )->first();
           $budget->value_contract = $budget->old_value;
           $budget->old_value = '';
           $budget->update();  
       
        }

        if (empty($contractNews)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $contractNews->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\ContractNewsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\ContractNewsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('contract_news').'.'.$fileType;

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
     * obtine la fecha de ultimo contrato suspendido
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function getDateLastSuspension ($id) {

        $lastSuspension = ContractNews::where('mant_provider_contract_id' , $id )->where('novelty_type' ,'Suspensión')->orderBy('id', 'desc')->first();
        return $this->sendResponse($lastSuspension->date_start_suspension, trans('msg_success_save'));

    }

    /**
     * obtine genera el consecutivo
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function getConsecutive ($contractId,$noveltyType) {

        $consecutivoAnterior = ContractNews::select('consecutive')->where('mant_provider_contract_id' , $contractId)->where('novelty_type' , $noveltyType)->orderBy('id', 'desc')->first();
            // Asigna el consecutivo para adicion del contrato
            if (empty($consecutivoAnterior )) {
                return '01';
            }else {
                $actual =  intval($consecutivoAnterior->consecutive);
                return  str_pad($actual + 1, 2, '0', STR_PAD_LEFT);
            }
    }

    /**
     * obtine genera el consecutivo
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function datosCostItem () {
        $rubro = AdministrationCostItem:: where('mant_budget_assignation_id',43)->get();
        return $rubro;
    }
    
}
