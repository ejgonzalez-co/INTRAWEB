<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateButgetExecutionRequest;
use Modules\Maintenance\Http\Requests\UpdateButgetExecutionRequest;
use Modules\Maintenance\Repositories\ButgetExecutionRepository;
use Modules\Maintenance\Models\ButgetExecution;
use Modules\Maintenance\Models\AdministrationCostItem;
use Modules\Maintenance\Models\ProviderContract;
use Modules\Maintenance\Models\BudgetAssignation;
use App\Exports\Maintenance\ContractExport\ContractExport;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use App\User;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use App\Http\Controllers\SendNotificationController;

/**
 * Esta es la clase de ejecucion de rubros
 *
 * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
 * @version 1.0.0
 */
class ButgetExecutionController extends AppBaseController {

    /** @var  ButgetExecutionRepository */
    private $butgetExecutionRepository;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     */
    public function __construct(ButgetExecutionRepository $butgetExecutionRepo) {
        $this->butgetExecutionRepository = $butgetExecutionRepo;
    }

    /**
     * Muestra la vista para el CRUD de ButgetExecution.
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $administrationItem=AdministrationCostItem::find($request['mpc']);
        return view('maintenance::butget_executions.index', compact('administrationItem'))->with("mpc", $request['mpc'] ?? null);
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
        $butget_executions = ButgetExecution::with('mantAdministrationCostItems')->where('mant_administration_cost_items_id', $request['mpc'])->latest()->get();
        return $this->sendResponse($butget_executions->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param CreateButgetExecutionRequest $request
     *
     * @return Response
     */
    public function store(CreateButgetExecutionRequest $request) {
        
        $input = $request->all();
        // dd($input);
            //verifica que el valor registrado sea mayor que el valor disponible
            if($request['value_available']>= $input['executed_value'] || $request['value_available_actuels']>= $input['executed_value']){

                try {
                    //Recupera el usuario ne sesion
                    $user=Auth::user();            
                    //Se crea el campo con el nombre del usuario        
                    $input['name_user']=$user->name;
                    //Se recupera el id
                    $input['users_id']=$user->id;
                    // Inserta el registro en la base de datos
                    $butgetExecution = $this->butgetExecutionRepository->create($input);
                    $butgetExecution->mantAdministrationCostItems;
                    //LLama metodo de enviar correo
                    $this->enviaCorreo($butgetExecution);
                    return $this->sendResponse($butgetExecution->toArray(), trans('msg_success_save'));
                } catch (\Illuminate\Database\QueryException $error) {
                    // Inserta el error en el registro de log
                    $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\ButgetExecutionController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                    // Retorna mensaje de error de base de datos
                    return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
                } catch (\Exception $e) {
                    // Inserta el error en el registro de log
                    $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\ButgetExecutionController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
                    // Retorna error de tipo logico
                    return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
                }



            }else{
                return $this->sendResponse("error", 'El valor ejecutado no puede ser superior al valor disponible.', 'warning');
            }
        
    }

    /**
     * Actualiza tabla de ejecucion de presupuesto.
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param updateTable $inputValue
     *
     * @return Response $sentinel
     */
    public function updateTable($id){

        $total=0;
        $cont=0;
        $sentinel=false;
        //Busca un rubro con la relacion de ejecucion presupuestal
        $administrationItem=AdministrationCostItem::with('mantBudgetExecutions')->find($id);
        
        //verifica que si tenga ejecucion presupuestal
        if(count($administrationItem->mantBudgetExecutions)>0){
            //Se le asigna todos los objetos de ejecucion presupuestal
            $arrayButget=$administrationItem->mantBudgetExecutions;
            //se le asigna el nuevo valor disponible del valor del rubro
            $arrayButget[0]->new_value_available=$administrationItem->value_item-$arrayButget[0]->executed_value;
            // se va recorrer el arreglo de todas las ejecuciones presupuestales
            for ($i=1; $i < count($arrayButget); $i++) {
                //Al valor actual de valor disponible se le asigna la operacion del registro anterior
                $arrayButget[$i]->new_value_available=$arrayButget[$i-1]->new_value_available-$arrayButget[$i]->executed_value;
                $arrayButget[$i]->save();
            }
        }
        
    }

    /**
     * Verifica que si se pueda guardar un registro.
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param verifyUpdate $inputValue
     *
     * @return Response $sentinel
     */
    public function verifyUpdate($id, $inputValue, $budgetExecution){
        $total=0;
        $cont=0;
        $sentinel=false;
        //Busca un rubro
        $administrationItem=AdministrationCostItem::with('mantBudgetExecutions')->find($id);
        //asigna todas las ejecuciones presupuestales
        $item=$administrationItem->mantBudgetExecutions;
        //recorre todas las ejecuciones presupuestales
        foreach ($item as $value) {
            //se le asigna todos los valores de ejecucion
            $cont+=$value->executed_value;
        }
        //Se hace la resta de el total de ejecuciones  a la ejecucion
        $total=$cont-$budgetExecution->executed_value;
        //Se se suma el nuevo valor que ingresa
        $total=$total+$inputValue;
        //Se le resta al valor del rubro el valor ejecutado
        $total=$administrationItem->value_item-$total;
        //Si total es mayor que cero
        if($total<0){
            $sentinel=true;
        }else{
            $sentinel=false;
        }
        return $sentinel;
    }
    

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateButgetExecutionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateButgetExecutionRequest $request) {

        $input = $request->all();

        /** @var ButgetExecution $butgetExecution */
        $butgetExecution = $this->butgetExecutionRepository->find($id);

        if (empty($butgetExecution)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        $sentinel=$this->verifyUpdate($input['mant_administration_cost_items_id'], $input['executed_value'], $butgetExecution);

        if($sentinel==true){
            return $this->sendResponse("error", 'El valor ejecutado no puede ser superior al valor disponible actual.', 'warning');
        }

        try {
            // Actualiza el registro
            $butgetExecution = $this->butgetExecutionRepository->update($input, $id);
            $butgetExecution->mantAdministrationCostItems;

            $this->updateTable($butgetExecution->mant_administration_cost_items_id);
            return $this->sendResponse($butgetExecution->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\ButgetExecutionController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\ButgetExecutionController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Envia correo
     *
     * @author Nicolas Dario Ortiz Peña. - febrero. 17 - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function enviaCorreo($butgetExecution) {
        //Valida que el procentaje este por envima de los 35% 
        if($butgetExecution->mantAdministrationCostItems->mantBudgetAssignation->mantProviderContract->total_percentage>35){
                //obtiene usuarios con el rol especificado
                $users = User::role('Administrador de mantenimientos')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                //dd($emails);
                //Asignan valores que van en el correo
                $butgetExecution["view"] = "maintenance::emails_maintenance.email_admin_provider_contract";
                $butgetExecution["title"] = "Contrato de los proveedores";
                $butgetExecution["valor"]=number_format($butgetExecution->mantAdministrationCostItems->mantBudgetAssignation->mantProviderContract->value_avaible, 2);
                $butgetExecution["porcentaje"]=number_format($butgetExecution->mantAdministrationCostItems->mantBudgetAssignation->mantProviderContract->total_percentage, 2);

                
                // Envia el correo 
                $this->sendEmail('maintenance::emails_maintenance.template', $butgetExecution->toArray(), $emails, "Contrato de los proveedores");

        }
        

    }

    /**
     * Envia correo electronico segun los parametros
     *
     * @author Erika Johana Gonzalez - Mazzo. 04 - 2021
     * @version 1.0.0
     */
    public static function sendEmail($view, $data, $emails, $title){

        if(isset($emails)) {

            $asunto = json_decode('{"subject": "' . $title . '"}');

            SendNotificationController::SendNotification($view,$asunto,$data,$emails,'Mantenimientos de activos - Contrato de proveedores');
        }
    }

    /**
     * valor del CDP 
     * *
     * @authorLeonardo Herrera. - Agosto. 25 - 2023
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function getValueCdp($id){
        $value=BudgetAssignation::select('value_cdp')->where('mant_provider_contract_id',$id)->first();
        return $this->sendResponse( $value->value_cdp, trans('msg_success_save'));
    }

     /**
     * valor del contrato
     * *
     * @authorLeonardo Herrera. - Agosto. 25 - 2023
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function getValueContract($id){
        $value=BudgetAssignation::select('value_contract')->where('mant_provider_contract_id',$id)->first();
        return $this->sendResponse( $value->value_contract, trans('msg_success_save'));
    }

    /**
     * Envia el valor disponible 
     * *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function getValueAvaible($id){
        $total=0;
        $cont=0;
        //rubros
        $administrationItem=AdministrationCostItem::with('mantBudgetExecutions')->find($id);
        //ejecucion de rubro
        $item=$administrationItem->mantBudgetExecutions;
        //Hace la suma de valores ejecutados
        foreach ($item as $value) {
            $cont+=$value->executed_value;
        }
        //Se le suma el valor del rubro menos el valor ingresado
        $total= $administrationItem->value_item-$cont;
      
        return $this->sendResponse( $total, trans('msg_success_save'));
    }

    /**
     * Envia el valor disponible 
     * *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function getValueAvaibleNovelty($cdp,$contract){
        dd($cdp,$contract);
        $total=0;
        $cont=0;
        //rubros
        $administrationItem=AdministrationCostItem::with('mantBudgetExecutions')->find($id);
        //ejecucion de rubro
        $item=$administrationItem->mantBudgetExecutions;
        //Hace la suma de valores ejecutados
        foreach ($item as $value) {
            $cont+=$value->executed_value;
        }
        //Se le suma el valor del rubro menos el valor ingresado
        $total= $administrationItem->value_item-$cont;
      
        return $this->sendResponse( $total, trans('msg_success_save'));
    }


    /**
     * Envia el valor disponible del valor a editar
     * *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function getValueAvaibleEdit($id){
        
        $total=0;
        
        $administrationItem=ButgetExecution::find($id);
        //ENvia el valor del rubro a editar
        $total=$administrationItem->executed_value+$administrationItem->new_value_available;
        
        return $this->sendResponse( $total, trans('msg_success_save'));
        
        
    }

    /**
     * Envia el rubro  
     * *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function getAdministrationItem($id){
        $administrationItem=AdministrationCostItem::with('mantBudgetExecutions')->find($id);
        
        return $this->sendResponse($administrationItem->toArray(), trans('msg_success_save'));
    }

    /**
     * Envia el porcentaje  
     * *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function getValuePercentage($id){
        $percentage=0;
        $budgetExecution = ButgetExecution::where('mant_administration_cost_items_id', $id)->get();
       //ENvia el valor del procentaje
        if($budgetExecution->count() > 0){
            foreach ($budgetExecution as  $value) {
                # code...
                $percentage+=$value->percentage_execution_item;
            }
            

        }        
        return $this->sendResponse($percentage, trans('msg_success_save'));
    }

    /**
     * Elimina un ButgetExecution del almacenamiento
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

        try {
            //Envia el objeto ejecucion
            $butgetExecution = ButgetExecution::with('mantAdministrationCostItems')->find($id);
            
            //Envia el valor ejecutado
            $value=$butgetExecution->executed_value;
            $createdAt=$butgetExecution->created_at;
            //A una nueva variable le envio la relacion del objeto ejecucion 
            $administrationItem=$butgetExecution->mantAdministrationCostItems;
            //Comprueba que el objeto no este vacio
            if (empty($butgetExecution)) {
                return $this->sendError(trans('not_found_element'), 200);
            }

            //Elimia el objeto ejecucion
            $butgetExecution->delete();
            //Envia el ultimo registro de objeto ejecucion
            $items=$administrationItem->mantBudgetExecutions->where('created_at','>',$createdAt);
            //Verifica que si existan objetos
            if($items != null){
                foreach ($items as $item) {
                    $item->new_value_available=$item->new_value_available+$value;
                    $item->save();
                }
            }
            
            $this->updateTable($butgetExecution->mant_administration_cost_items_id);
            
            // Elimina el registro
            

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\ButgetExecutionController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\ButgetExecutionController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = date('Y-m-d H:i:s').'-'.trans('butget_executions').'.'.$fileType;
        
        return Excel::download(new ContractExport('maintenance::butget_executions.report_excel', $input['data'],'h'), $fileName);
    }
}
