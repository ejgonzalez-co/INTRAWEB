<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateOilRequest;
use Modules\Maintenance\Http\Requests\UpdateOilRequest;
use Modules\Maintenance\Repositories\OilRepository;
use Modules\Maintenance\Models\OilElementWearConfiguration;
use Modules\Maintenance\Models\Oil;
use App\Exports\Maintenance\RequestExport;
use Modules\Maintenance\Models\OilControlLaboratory;
use Modules\Maintenance\Models\OilElementWear;
use Modules\Maintenance\Models\OilHistory;
use App\Http\Controllers\AppBaseController;
use App\User;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use App\Http\Controllers\SendNotificationController;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class OilController extends AppBaseController {

    /** @var  OilRepository */
    private $oilRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(OilRepository $oilRepo) {
        $this->oilRepository = $oilRepo;
    }

    /**
     * Muestra la vista para el CRUD de Oil.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::oil.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Andres Stiven Pinzon G. - Dic. 18 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {

        $user=Auth::user();

        if(Auth::user()->hasRole('mant Consulta proceso')){
        // $oil = $this->oilRepository->all();
        $oil = Oil::with(['resumeMachineryVehiclesYellow','assetType','oilControlLaboratories','dependencias','oilElementWears'])->where('dependencies_id', $user->id_dependencia)->latest()->get();
            
        }else{

        $oil = Oil::with(['resumeMachineryVehiclesYellow','assetType','oilControlLaboratories','dependencias','oilElementWears'])->latest()->get();

        }
        return $this->sendResponse($oil->toArray(), trans('data_obtained_successfully'));
    
    }

    /**
     * Obtiene el grupo de la configuracion de desgaste
     *
     * @author Johan david. - Ene. 23 - 2025
     * @version 1.0.0
     *
     * @return Response
     */
    public function groupConfigurationOil($id) {
        $wear = OilElementWearConfiguration::where('id',$id)->get()->first();
        return $this->sendResponse($wear, trans('data_obtained_successfully'));

    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Andres Stiven Pinzon G. - Dic. 18 - 2021
     * @version 1.0.0
     *
     * @param CreateOilRequest $request
     *
     * @return Response
     */
    public function store(CreateOilRequest $request) {
        
        $input = $request->toArray();

        //Recupera el usuario en sesion
        $user=Auth::user();
        
        $componentName= $input['component']; 

        $input['component_name'] = $componentName;
            
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $oil = $this->oilRepository->create($input);
            // dd("componente",$oil);
            //Se crea y guarda un modelo en el historial de aceites
            $oilHistory= new OilHistory();
            $oilHistory->users_id=$user->id;
            $oilHistory->action="Crear";
            $oilHistory->description="Se crea el registro";
            $oilHistory->name_user=$user->name;
            $oilHistory->plaque= $oil->resumeMachineryVehiclesYellow->plaque;
            $oilHistory->dependencia=$oil->dependencias->nombre;
            $oilHistory->consecutive=$oil->component;
            $oilHistory->save();

            // Condición para validar si existe algún registro de elementos de desgaste
            if (!empty($input['oil_element_wears'])) {
                
                // Ciclo para recorrer todos los registros de elemento de desgaste
                foreach($input['oil_element_wears'] as $oilElement){
                    $elementWears = json_decode($oilElement);
                    // Se crean la cantidad de registros ingresados por el usuario
                    $oilWear=OilElementWear::create([
                        'number_control_laboratory' => $elementWears->number_control_laboratory,
                        'detected_value' => $elementWears->detected_value,
                        'mant_oil_element_wear_configurations_id' => $elementWears->mant_oil_element_wear_configurations_id,
                        'range' => $elementWears->range,
                        'group' => $elementWears->group,
                        'mant_oils_id' => $oil->id
                    ]);
                    if($oilWear->range=="Alto"){
                        $this->enviaCorreoTwo($oil, $oilWear);
                    }
                }
            }

            // Condición para validar si existe algún registro de control de laboratorios
            if (!empty($input['oil_control_laboratories'])) {
                
                // Ciclo para recorrer todos los registros de control de laboratorios
                foreach($input['oil_control_laboratories'] as $oilControl){
                    $controlLaboratories = json_decode($oilControl);
                    // Se crean la cantidad de registros ingresados por el usuario
                    OilControlLaboratory::create([
                        'number_control_laboratory' => $controlLaboratories->number_control_laboratory ?? null,
                        'date_sampling' => $controlLaboratories->date_sampling ? $controlLaboratories->date_sampling: '',
                        'date_process' => $controlLaboratories->date_process,
                        'hourmeter' => $controlLaboratories->hourmeter ?? null,
                        'oil_hours' => $controlLaboratories->oil_hours ?? null,
                        'kilometer' => $controlLaboratories->kilometer ?? null,
                        'filling' => $controlLaboratories->filling,
                        'change_oil' => $controlLaboratories->change_oil ?? null,
                        'change_filter' => $controlLaboratories->change_filter ?? null,
                        'filling_units' => $controlLaboratories->filling_units ?? null,
                        'result' => $controlLaboratories->result ?? null,
                        'type_result' => $controlLaboratories->type_result ?? null,
                        'observation' =>$controlLaboratories->observation ?? null,
                        'mant_oils_id' => $oil->id
                    ]);
                }
            }
            $oil->resumeMachineryVehiclesYellow;
            $oil->oilControlLaboratories;
            $oil->dependencias;
            $oil->assetType;
            $oil->oilElementWears[0]->oilElementWearConfigurations;
            // Efectua los cambios realizados
            DB::commit();

            

            return $this->sendResponse($oil->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Dic. 19 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateOilRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOilRequest $request) {

        $input = $request->all();


        //Recupera el usuario en sesion
        $user=Auth::user();

        /** @var Oil $oil */
        $oil = $this->oilRepository->find($id);

        if (empty($oil)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $oil = $this->oilRepository->update($input, $id);
            
            OilElementWear::where('mant_oils_id', $oil->id)->delete();
            // Condición para validar si existe algún registro de elemento de desgaste
            if (!empty($input['oil_element_wears'])) {
                // Eliminar los registros de contactos existentes según el id del registro principal
                // Ciclo para recorrer todos los registros de contactos
                foreach($input['oil_element_wears'] as $oilElement){
                    $elementWears = json_decode($oilElement);

                    OilElementWear::create([
                        'number_control_laboratory' => $elementWears->number_control_laboratory ?? null,
                        'detected_value' => $elementWears->detected_value ?? null,
                        'mant_oil_element_wear_configurations_id' => $elementWears->mant_oil_element_wear_configurations_id ?? null,
                        'range' => $elementWears->range ?? null,
                        'group' => $elementWears->group ?? null,
                        'mant_oils_id' => $oil->id ?? null
                    ]);


                }
            }

            // Condición para validar si existe algún registro de control de laboratorios
            if (!empty($input['oil_control_laboratories'])) {
                // Eliminar los registros de control de laboratorios existentes según el id del registro principal
                OilControlLaboratory::where('mant_oils_id', $oil->id)->delete();
                // Ciclo para recorrer todos los registros de contactos
                foreach($input['oil_control_laboratories'] as $oilControl){
                    $controlLaboratories = json_decode($oilControl);

                    // Se crean la cantidad de registros ingresados por el usuario
                    OilControlLaboratory::create([
                        'date_sampling' => $controlLaboratories->date_sampling ?? null,
                        'date_process' => $controlLaboratories->date_process ?? null,
                        'hourmeter' => $controlLaboratories->hourmeter ?? null,
                        'oil_hours' => $controlLaboratories->oil_hours ?? null,
                        'kilometer' => $controlLaboratories->kilometer ?? null,
                        'filling' => $controlLaboratories->filling ?? null,
                        'change_oil' => $controlLaboratories->change_oil ?? null,
                        'change_filter' => $controlLaboratories->change_filter ?? null,
                        'filling_units' => $controlLaboratories->filling_units ?? null,
                        'result' => $controlLaboratories->result ?? null,
                        'type_result' => $controlLaboratories->type_result ?? null,
                        'observation' =>$controlLaboratories->observation ?? null,
                        'mant_oils_id' => $oil->id ?? null
                    ]);
                }
            }

            $oil->resumeMachineryVehiclesYellow;
            $oil->oilControlLaboratories;
            $oil->dependencias;
            $oil->assetType;
            $oil->oilElementWears;

            //Se crea y guarda un modelo en el historial de aceites
            $oilHistory= new OilHistory();
            $oilHistory->users_id=$user->id;
            $oilHistory->action="Editar";
            $oilHistory->description=$request['descriptionDelete'];
            $oilHistory->name_user=$user->name;
            $oilHistory->plaque= $oil->resumeMachineryVehiclesYellow->plaque;
            $oilHistory->dependencia=$oil->dependencias->nombre;
            $oilHistory->consecutive=$oil->component;
            $oilHistory->save();

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($oil->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Obtiene los nombres de los elementos de degaste.
     *
     * @author Andres Stiven Pinzon G. - Dic. 18 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getElementWear($param) {
        //Consulta todos los elementos de desgaste registrados
        $elementWear = OilElementWearConfiguration::where('component',$param)->get();

        return $this->sendResponse($elementWear->toArray(), trans('data_obtained_successfully'));
    }

     /**
     * Obtiene los nombres de los elementos de degaste.
     *
     * @author Andres Stiven Pinzon G. - Dic. 21 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getElement($id) {
        //Consulta los rangos superior e inferior de un elemento de desgaste
        $elementWear = OilElementWearConfiguration::select(['rank_higher','rank_lower'])->where('id','=',$id)->get()->toArray();
        // dd("consullta de leemento",$elementWear);
        if(count($elementWear)){
            return $this->sendResponse($elementWear[0], trans('data_obtained_successfully'));
        }
    }

    /**
     * Elimina un registro de geston de aceites del almacenamiento
     *
     * @author Andres Stiven Pinzon G. - Dic. 21 - 2020
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Request $request) {

        //Recupera el usuario en sesion
        $user=Auth::user();

        /** @var Oil $oil */
        $oil = $this->oilRepository->find($request['id']);

        if (empty($oil)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $oil->delete();

            //Se crea y guarda un modelo en el historial de aceites
            $oilHistory= new OilHistory();
            $oilHistory->users_id=$user->id;
            $oilHistory->action="Eliminar";
            $oilHistory->description=$request['observationDelete'];
            $oilHistory->name_user=$user->name;
            $oilHistory->plaque= $oil->resumeMachineryVehiclesYellow->plaque;
            $oilHistory->dependencia=$oil->dependencias->nombre;
            $oilHistory->consecutive=$oil->component;
            $oilHistory->save();



            
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Genera el reporte de gestion de gestion de aceites en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - Dic. 19 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();
        // dd($input['data']);
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];

        $fileName = date('Y-m-d H:i:s').'-'.trans('oil').'.'.$fileType;
        
        return Excel::download(new RequestExport('maintenance::oil.report_excel', $input['data'],'l'), $fileName);
    }

    /**
     * Envia correo
     *
     * @author Nicolas Dario Ortiz Peña. - febrero. 17 - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function enviaCorreoTwo($oil, $butgetExecution) {
                
                //obtiene usuarios con el rol especificado
                $users = User::role('Administrador de mantenimientos')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                //dd($emails);
                //Asigna los datos que van en el correo
                $butgetExecution["view"] = "maintenance::emails_maintenance.email_admin_oil_wear";
                $butgetExecution["title"] = "Aceites";
                $butgetExecution["nombreComponente"] = $oil->component_name;
                $butgetExecution["placa"] = $oil->resumeMachineryVehiclesYellow->plaque;
                $butgetExecution["proceso"] = $oil->dependencias->nombre;
                $butgetExecution["created_at_wear"] = $butgetExecution->created_at;
                $butgetExecution["wear_Range"] = $butgetExecution->range;
                $butgetExecution["numWear"] = $butgetExecution->number_control_laboratory;
                
                // Envia el correo 
                $this->sendEmail('maintenance::emails_maintenance.template', $butgetExecution->toArray(), $emails, "Aceites");


                //obtiene usuarios con el rol especificado
                $users = User::role('mant Operador apoyo administrativo')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                //dd($emails);
                //Asigna los datos que van en el correo
                $butgetExecution["view"] = "maintenance::emails_maintenance.email_ope_oil_wear";
                $butgetExecution["title"] = "Aceites";
                $butgetExecution["nombreComponente"] = $oil->component_name;
                $butgetExecution["placa"] = $oil->resumeMachineryVehiclesYellow->plaque;
                $butgetExecution["proceso"] = $oil->dependencias->nombre;
                $butgetExecution["created_at_wear"] = $butgetExecution->created_at;
                $butgetExecution["wear_Range"] = $butgetExecution->range;
                $butgetExecution["numWear"] = $butgetExecution->number_control_laboratory;
                
                // Envia el correo 
                $this->sendEmail('maintenance::emails_maintenance.template', $butgetExecution->toArray(), $emails, "Aceites");



                //obtiene usuarios con el rol especificado
                $users = User::role('mant Operador Aceites')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                //dd($emails);
                //Asigna los datos que van en el correo
                $butgetExecution["view"] = "maintenance::emails_maintenance.email_ope_oa_oil_wear";
                $butgetExecution["title"] = "Aceites";
                $butgetExecution["nombreComponente"] = $oil->component_name;
                $butgetExecution["placa"] = $oil->resumeMachineryVehiclesYellow->plaque;
                $butgetExecution["proceso"] = $oil->dependencias->nombre;
                $butgetExecution["created_at_wear"] = $butgetExecution->created_at;
                $butgetExecution["wear_Range"] = $butgetExecution->range;
                $butgetExecution["numWear"] = $butgetExecution->number_control_laboratory;
                
                // Envia el correo 
                $this->sendEmail('maintenance::emails_maintenance.template', $butgetExecution->toArray(), $emails, "Aceites");
        
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

            SendNotificationController::SendNotification($view,$asunto,$data,$emails,'Mantenimientos de activos - Gestión de aceites');
        }
    }

}
