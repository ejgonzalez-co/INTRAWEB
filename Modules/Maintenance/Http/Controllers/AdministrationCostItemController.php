<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateAdministrationCostItemRequest;
use Modules\Maintenance\Http\Requests\UpdateAdministrationCostItemRequest;
use Modules\Maintenance\Repositories\AdministrationCostItemRepository;
use Modules\Maintenance\Models\AdministrationCostItem;
use Modules\Maintenance\Models\CenterCost;
use Modules\Maintenance\Models\Heading;
use Modules\Maintenance\Models\BudgetAssignation;
use App\Exports\Maintenance\ContractExport\CostItemExport;
use Modules\Maintenance\Models\HistoryCostItem;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use Modules\Maintenance\Models\AddingCostItem;
use DB;

/**
 * Clase para administrar los rubros
 *
 * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
 * @version 1.0.0
 */
class AdministrationCostItemController extends AppBaseController {

    /** @var  AdministrationCostItemRepository */
    private $administrationCostItemRepository;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     */
    public function __construct(AdministrationCostItemRepository $administrationCostItemRepo) {
        $this->administrationCostItemRepository = $administrationCostItemRepo;
    }

    /**
     * Muestra la vista para el CRUD de AdministrationCostItem.
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        //busca la asignacion de presupuesto para inicializarla en el index
        $budgetAssignation=BudgetAssignation::find($request['mpc']);
        //Retorna la vista de index de asignacion de presupuesto con el id del equies yy la asignacion encontrada
        return view('maintenance::administration_cost_items.index', compact('budgetAssignation'))->with("mpc", $request['mpc'] ?? null);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        //Busca todos los objetos de rubros organizados del ultimo al primero con sus relaciones
        $administration_cost_items = AdministrationCostItem::with(['mantBudgetAssignation', 'mantCenterCost','mantHeading','mantBudgetExecutions'])->where('mant_budget_assignation_id', $request['mpc'])->latest()->get();
        //Retorna la el arreglo
        return $this->sendResponse($administration_cost_items->toArray(), trans('data_obtained_successfully'));
    }



        /**
     * Muestra el detalle completo de elemento existente
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function show($id) {
        //Busca el rubro
        $administrationCostItem = $this->administrationCostItemRepository->find($id);
        //Veifica que exista el rubro
        if (empty($administrationCostItem)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //LLama la relacion del rubro
        $administrationCostItem->mantBudgetExecutions;
        //Retorna el objeto que se va mostrar
        return $this->sendResponse($administrationCostItem->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param CreateAdministrationCostItemRequest $request
     *
     * @return Response
     */
    public function store(CreateAdministrationCostItemRequest $request) {
        //Recupera el usuario en sesion
        $user=Auth::user();
        //Recupera todo lo que viene del formulario
        $input = $request->all();
        // Busca la asignacion de presupuesto con la relacion de rubros y que sea igual a id 
        $budgetAssignation=BudgetAssignation::with(['mantAdministrationCostItems'])->where('id', $request['mant_budget_assignation_id'])->get();
        //verifica el total de items
        $totalItems=0;        
        //Envia todos los rubros que existen
        $costItem=$budgetAssignation[0]->mantAdministrationCostItems;
        //Recorreo los rubros que existen y se suma el total de rubros
        foreach ($costItem as $key => $value) {            
            //Acumula los valores del rubro
            $totalItems+=$value->value_item;
        }
        //Se le suma el nuevo valor del item
        $totalItems+=$request['value_item'];
        //Se verifica que el valor de todos los rubros mas el nuevo no superen el valor asignado del contrato
        if($totalItems <= $budgetAssignation[0]->value_contract){
                
            //Busca en los rubros
            $heading=Heading::find($input['mant_heading_id']);
            //Busca en el centro de costos
            $centerCost=CenterCost::find($input['mant_center_cost_id']);
            
            //Se guarda el nombre del rubro
            $input['name']=$heading->name_heading;
            //Se guarda el nombre del centro de costos
            $input['cost_center_name']=$centerCost->name;
            //Guarda el nombre del usuario
            $input['name_user']=$user->name;
            //Guarda el id del usuario en sesion
            $input['users_id']=$user->id;
            
            try {
                // Inserta el registro en la base de datos
                $administrationCostItem = $this->administrationCostItemRepository->create($input);

                // AddingCostItem:: create([
                // 'mant_contract_news_id'=> 0,
                // 'mant_administration_cost_items_id' => $administrationCostItem->id,
                // 'code_cost'=> $administrationCostItem->code_cost,
                // 'name'=> $administrationCostItem->name,
                // 'cost_center'=> $administrationCostItem->cost_center,
                // 'cost_center_name'=> $administrationCostItem->cost_center_name,
                // 'value_item'=> $administrationCostItem->value_item]);


                //Crea un registro de historial
                $historyCostItem=new HistoryCostItem();
                $historyCostItem->name="Registro creado";
                $historyCostItem->observation="El registro se creo";
                $historyCostItem->name_cost=$administrationCostItem->name;
                $historyCostItem->code_cost=$administrationCostItem->code_cost;
                $historyCostItem->cost_center=$administrationCostItem->cost_center;
                $historyCostItem->cost_center_name=$administrationCostItem->cost_center_name;
                $historyCostItem->value_item=$administrationCostItem->value_item;
                $historyCostItem->users_id=$user->id;
                $historyCostItem->mant_budget_assignation_id=$administrationCostItem->mant_budget_assignation_id;
                $historyCostItem->name_user=$user->name;
                $historyCostItem->save();


    
                return $this->sendResponse($administrationCostItem->toArray(), trans('msg_success_save'));
            } catch (\Illuminate\Database\QueryException $error) {
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdministrationCostItemController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdministrationCostItemController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
                // Retorna error de tipo logico
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
            }
        }else{

            return $this->sendResponse("error", 'El valor de los rubros debe ser menor que el valor del contrato', 'warning');
        }
    
    }

    
    /**
     * Obtiene el codigo del rubro
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 23 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getCodAdministrationItem($id){
        if($id){
        $headings = Heading::find($id);
    }else{
        $headings="";
    }
        
        return $this->sendResponse($headings->code_heading, trans('data_obtained_successfully'));
    }

      /**
     * Obtiene el codigo del centro de costos
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 23 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getCodCenterAdministrationItem($id){
        if($id){
            $centerCost = CenterCost::find($id);
        }else{
            $centerCost="";
        }
        return $this->sendResponse($centerCost->code_center, trans('data_obtained_successfully'));
    }
    

     /**
     * Obtiene el valor disponible del contrato
     *
     * @author Nicolas Dario Ortiz Peña. - Jul. 30 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getAvaibleContract($id)
    {   
        //Busca la asignacion de presupuesto con la relacion
        $budgetAssignation=BudgetAssignation::with(['mantAdministrationCostItems'])->where('id', $id)->get();
        $totalItems=0;        
        //Asigna la relacion de rubros a esa variable
        $costItem=$budgetAssignation[0]->mantAdministrationCostItems;
        //Se recorren todos los rubros
        foreach ($costItem as $key => $value) {
            //Se suma el valor de cada rubro de determinada asignacion            
            $totalItems+=$value->value_item;
        }
        //Se hace la resta y queda el valor disponiole del contrato
        $total=$budgetAssignation[0]->value_contract-$totalItems;
       
        return $this->sendResponse($total, trans('data_obtained_successfully'));
    }

     /**
     * Obtiene todos los elementos existentes de rubros
     *
     * @author Nicolas Dario Ortiz Peña. - Jul. 30 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getHeading()
    {
        
        $headings = Heading::all();
        
        return $this->sendResponse($headings->toArray(), trans('data_obtained_successfully'));
    }

       /**
     * Obtiene todos los elementos existentes de rubros que sean aseo y de la dependencia del usuario en sesion
     *
     * @author Nicolas Dario Ortiz Peña. - Jul. 30 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getHeadingUnityAseo($dependencia)
    {
        
        $depend = '';
        if (is_numeric($dependencia)) {
            $depend = $dependencia;
        } else {
            $depend = DB:: table('dependencias')->where('nombre', $dependencia)->first();
            $depend = $depend->id;
        }
    
    if(Auth::user()->hasRole("Administrador de mantenimientos")){
        if($dependencia == 19 || $dependencia == 23){
            $headings = AdministrationCostItem::join("mant_budget_assignation as a","mant_budget_assignation_id","=", "a.id")->join('mant_provider_contract as s', 'a.mant_provider_contract_id', '=', 's.id')
            ->join('mant_heading as k', 'mant_heading_id', '=', 'k.id')
            // ->select('k.*','cost_center_name')
            ->select('k.name_heading','k.code_heading', DB::raw("REPLACE(REPLACE(cost_center_name, '\r', ''), '\n', '') as cost_center_name"),DB::raw("CONCAT(k.id, '-', REPLACE(REPLACE(cost_center_name, '\r', ''), '\n', '')) AS id_combinado"))
            // ->select('k.name_heading','k.code_heading', DB::raw("REPLACE(REPLACE(cost_center_name, '\r', ''), '\n', '') as cost_center_name"), 'k.id')
            ->distinct()
            ->where("cost_center_name","ASEO")
            ->get();
        }
        else{
            $headings = AdministrationCostItem::join("mant_budget_assignation as a","mant_budget_assignation_id","=", "a.id")->join('mant_provider_contract as s', 'a.mant_provider_contract_id', '=', 's.id')
            ->join('mant_heading as k', 'mant_heading_id', '=', 'k.id')
            // ->select('k.*','cost_center_name')
            ->select('k.name_heading','k.code_heading', DB::raw("REPLACE(REPLACE(cost_center_name, '\r', ''), '\n', '') as cost_center_name"),DB::raw("CONCAT(k.id, '-', REPLACE(REPLACE(cost_center_name, '\r', ''), '\n', '')) AS id_combinado"))
            // ->select('k.name_heading','k.code_heading', DB::raw("REPLACE(REPLACE(cost_center_name, '\r', ''), '\n', '') as cost_center_name"), 'k.id')
            ->distinct()
            ->get();
        }

        // dd($headings);
        // $headings = DB::table('mant_administration_cost_items as c')
        // ->join('mant_budget_assignation as a', 'c.mant_budget_assignation_id', '=', 'a.id')
        // ->join('mant_provider_contract as s', 'a.mant_provider_contract_id', '=', 's.id')
        // ->join('mant_heading as k', 'c.mant_heading_id', '=', 'k.id')
        // ->select('k.*','c.cost_center_name')
        // ->distinct('name_heading')
        // ->get();

        // dd($headings->toArray());
    }
    else{
        $headings = DB::table('mant_administration_cost_items as c')
        ->join('mant_budget_assignation as a', 'c.mant_budget_assignation_id', '=', 'a.id')
        ->join('mant_provider_contract as s', 'a.mant_provider_contract_id', '=', 's.id')
        ->join('mant_heading as k', 'c.mant_heading_id', '=', 'k.id')
        ->where('s.dependencias_id', $depend)
        ->where('c.cost_center', 3)
        ->select('k.*','c.cost_center_name')
        ->get();
    }
        return $this->sendResponse($headings->toArray(), trans('data_obtained_successfully'));
    }

    public function getItemsByDependency(int $dependencyId){
        // $headings = DB::table('mant_administration_cost_items as c')
        // ->join('mant_budget_assignation as a', 'c.mant_budget_assignation_id', '=', 'a.id')
        // ->join('mant_provider_contract as s', 'a.mant_provider_contract_id', '=', 's.id')
        // ->join('mant_heading as k', 'c.mant_heading_id', '=', 'k.id')
        // ->where('s.dependencias_id', $dependencyId)
        // ->where('c.cost_center', 3)
        // ->select('k.*')
        // ->get();
        try{
            if($dependencyId == 19 || $dependencyId == 23){
                $headings = DB::table('mant_administration_cost_items as aci')
                ->join('mant_budget_assignation as ba', 'aci.mant_budget_assignation_id', '=', 'ba.id')
                ->join('mant_provider_contract as pc', 'ba.mant_provider_contract_id', '=', 'pc.id')
                ->where('pc.condition','Activo')
                ->join('mant_heading as k', 'aci.mant_heading_id', '=', 'k.id')
                ->where('aci.cost_center', 3)
                ->select('k.*')
                ->get();
            }
            else{
                $headings = DB::table('mant_administration_cost_items as aci')
                ->join('mant_budget_assignation as ba', 'aci.mant_budget_assignation_id', '=', 'ba.id')
                ->join('mant_provider_contract as pc', 'ba.mant_provider_contract_id', '=', 'pc.id')
                ->where('pc.condition','Activo')
                ->join('mant_heading as k', 'aci.mant_heading_id', '=', 'k.id')
                ->where('aci.cost_center', "!=",3)
                ->select('k.*')
                ->get();
            }
        }
        catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdministrationCostItemController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
        }
        return $this->sendResponse($headings->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los rubros que esten relacionados a un objeto de contrato
     *
     * @author Kleverman Salazar Florez. - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function getItemsByObjectContractId(int $contractObjectId) : array{
        $headings = DB::table('mant_administration_cost_items as c')
        ->join('mant_budget_assignation as a', 'c.mant_budget_assignation_id', '=', 'a.id')
        ->join('mant_provider_contract as s', 'a.mant_provider_contract_id', '=', 's.id')
        ->join('mant_heading as k', 'c.mant_heading_id', '=', 'k.id')
        ->where('s.id', $contractObjectId)
        ->where('c.cost_center', 3)
        ->select('c.*')
        ->get()->toArray();
        
        
        $administrationCostItem = AdministrationCostItem::whereIn("id",array_column($headings,"id"))->get()->toArray();

        return $this->sendResponse($administrationCostItem, trans('data_obtained_successfully'));
    }


      /**
     * Obtiene todos los elementos existentes de rubros
     *
     * @author Nicolas Dario Ortiz Peña. - Jul. 30 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getHeadingUnity($activeId)
    {
        $headings = DB::table('mant_sn_actives_heading')
        // ->where('s.dependencias_id', '=', $depend)
        ->select(['id','rubro_id','rubro_codigo as code_heading','centro_costo_codigo'])
        ->where("activo_id",$activeId)
        ->whereNull("deleted_at")
        ->get()->map(function($heading){
            $heading->name_heading = Heading::select("name_heading")->where("id",$heading->rubro_id)->first()->name_heading;
            $heading->center_cost_name = CenterCost::select("name")->where("code_center",$heading->centro_costo_codigo)->first()->name;
            return $heading;
        });
        // $headings = DB::table('mant_sn_actives_heading')
        // // ->where('s.dependencias_id', '=', $depend)
        // ->select(['id','rubro_id','rubro_codigo as code_heading','centro_costo_codigo'])
        // ->where("activo_id",$activeId)
        // ->whereNull("deleted_at")
        // ->get()->toArray();

        // dd(CenterCost::get()->toArray());
        // dd($headings);

        return $this->sendResponse($headings->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Obtiene todos los elementos existentes de rubros por el rubro
     *
     * @author leonardo herrera. - 2025
     * @version 1.0.0
     *
     * @return Response
     */
    public function getHeadingUnityByRubro($rubro_id)
    {
        $headings = DB::table('mant_sn_actives_heading')
        // ->where('s.dependencias_id', '=', $depend)
        ->select(['id','rubro_id','rubro_codigo as code_heading','centro_costo_codigo'])
        ->where("rubro_id",$rubro_id)
        ->whereNull("deleted_at")
        ->get()->map(function($heading){
            $heading->name_heading = Heading::select("name_heading")->where("id",$heading->rubro_id)->first()->name_heading;
            $heading->center_cost_name = CenterCost::select("name")->where("code_center",$heading->centro_costo_codigo)->first()->name;
            return $heading;
        });

        return $this->sendResponse($headings->toArray(), trans('data_obtained_successfully'));
    }


     /**
     * Obtiene todos los elementos existentes de centro de costos
     * @author Nicolas Dario Ortiz Peña. - Jul. 30 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getCenterCost()
    {
        $centerCost = CenterCost::all();
       
        return $this->sendResponse($centerCost->toArray(), trans('data_obtained_successfully'));
    }
    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateAdministrationCostItemRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAdministrationCostItemRequest $request) {
        $user=Auth::user();
        $input = $request->all();

        /** @var AdministrationCostItem $administrationCostItem */
        $administrationCostItem = $this->administrationCostItemRepository->find($id);

        if (empty($administrationCostItem)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Actualiza el registro
            $administrationCostItem = $this->administrationCostItemRepository->update($input, $id);
            //Crea un registro en el historial de rubros
            $historyCostItem=new HistoryCostItem();
                $historyCostItem->name="Registro editado";
                $historyCostItem->observation="El registro se edito";
                $historyCostItem->name_cost=$administrationCostItem->name;
                $historyCostItem->code_cost=$administrationCostItem->code_cost;
                $historyCostItem->cost_center=$administrationCostItem->cost_center;
                $historyCostItem->cost_center_name=$administrationCostItem->cost_center_name;
                $historyCostItem->value_item=$administrationCostItem->value_item;
                $historyCostItem->users_id=$user->id;
                $historyCostItem->mant_budget_assignation_id=$administrationCostItem->mant_budget_assignation_id;
                $historyCostItem->name_user=$user->name;
                $historyCostItem->save();
        
            return $this->sendResponse($administrationCostItem->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdministrationCostItemController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdministrationCostItemController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un AdministrationCostItem del almacenamiento
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
    public function destroy(Request $request) {

        $user=Auth::user();
        /** @var AdministrationCostItem $administrationCostItem */

        //busca el rubro que se va eliminar
        $administrationCostItem = $this->administrationCostItemRepository->find($request['id']);
        //Busca todos los rubros
        $administrationCostItem=AdministrationCostItem::with('mantBudgetExecutions')->where('id',$request['id'])->get();
        //Busca la relacion de ejecutados
        $execution=$administrationCostItem[0]->mantBudgetExecutions;
        //Verifica que existan ejecuciones presupuestales
        if(count($execution)!=0){
            return $this->sendResponse("error", 'Debe eliminar primero todas las ejecuciones presupuestales.', 'warning');
        }else{
            if (empty($administrationCostItem[0])) {
                return $this->sendError(trans('not_found_element'), 200);
            }
    
            try {
              
                //Crea un registro en el historial de rubros
                $historyCostItem=new HistoryCostItem();
                $historyCostItem->name="Registro eliminado";
                $historyCostItem->observation=$request['observationDelete'];
                $historyCostItem->name_cost=$administrationCostItem[0]->name;
                $historyCostItem->code_cost=$administrationCostItem[0]->code_cost;
                $historyCostItem->cost_center=$administrationCostItem[0]->cost_center;
                $historyCostItem->cost_center_name=$administrationCostItem[0]->cost_center_name;
                $historyCostItem->value_item=$administrationCostItem[0]->value_item;
                $historyCostItem->users_id=$user->id;
                $historyCostItem->mant_budget_assignation_id=$administrationCostItem[0]->mant_budget_assignation_id;
                $historyCostItem->name_user=$user->name;
                $historyCostItem->save();

                  // Elimina el registro
                  $administrationCostItem[0]->delete();

                  return $this->sendResponse($administrationCostItem[0]->toArray(), trans('msg_success_update'));

            } catch (\Illuminate\Database\QueryException $error) {
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdministrationCostItemController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdministrationCostItemController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
                // Retorna error de tipo logico
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
            }
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
        $fileName = date('Y-m-d H:i:s').'-'.trans('Rubros').'.'.$fileType;
        
        return Excel::download(new CostItemExport('maintenance::administration_cost_items.report_excel', $input['data'],'h'), $fileName);
    }
}
