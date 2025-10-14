<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateMinorEquipmentFuelRequest;
use Modules\Maintenance\Http\Requests\UpdateMinorEquipmentFuelRequest;
use Modules\Maintenance\Repositories\MinorEquipmentFuelRepository;
use Modules\Maintenance\Models\Dependency;
use Modules\Maintenance\Models\MinorEquipmentFuel;
use Modules\Maintenance\Models\FuelEquipmentHistory;
use App\Exports\Maintenance\FuelMinorExport\FuelExport;
use Illuminate\Support\Facades\Storage;
use App\User;
use Modules\Maintenance\Models\EquipmentMinorFuelConsumption;
use Modules\Maintenance\Models\HistoryEquipmentMinor;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

/**
 * Combustible de equipos menores
 *
 * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
 * @version 1.0.0
 */
class MinorEquipmentFuelController extends AppBaseController {

    /** @var  MinorEquipmentFuelRepository */
    private $minorEquipmentFuelRepository;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     */
    public function __construct(MinorEquipmentFuelRepository $minorEquipmentFuelRepo) {
        $this->minorEquipmentFuelRepository = $minorEquipmentFuelRepo;
    }

    /**
     * Muestra la vista para el CRUD de MinorEquipmentFuel.
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::minor_equipment_fuels.index');
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
        
        $user=Auth::user();

        // Variable para contar el número total de registros de la consulta realizada
        $count_notifications = 0;

        $value_exists_after;

        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        // Valida si existen las variables del paginado y si esta filtrando
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {

            $filtros = base64_decode($request["f"]);

            //Valida su en los filtro vienen exists_after y total_consume_fuel
            if (str_contains($filtros, 'exists_after') && str_contains($filtros, 'total_consume_fuel ')) {

                //Obtiene el valor de ingresaron en el campo total_consume_fuel
                preg_match("/total_consume_fuel LIKE '%(\d+)%'/", $filtros, $matches);

                //Valida si el valor es diferente de vacio y asigna el valor a la variable $number
                if (isset($matches[1])) {
                    $number = $matches[1];
                } else {
                    $number = 'na';
                }

                //Valida si en el filtro viene un valor true o false
                if (str_contains($filtros, 'TRUE')) {
                    $value_exists_after = 'true';
                }else{
                    $value_exists_after = 'false';
                }

                //Remueve los querys de exists_after y total_consume_fuel de los filtros para darles un tratado especial
                $filtros_new = $this->removeExistsAfterCondition($filtros);

                if(Auth::user()->hasRole('mant Consulta proceso')){

                    $minor_equipment_fuels = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->whereRaw($filtros_new)->whereRaw('CheckMinorEquipment(dependencias_id, created_at) = ' .$value_exists_after)->whereRaw('CAST(CalculateTotalGallonsSupplied(id) AS CHAR) LIKE ?', ['%' . $number . '%'])->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

                    
    
                    $count_notifications = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->whereRaw($filtros_new)->whereRaw('CheckMinorEquipment(dependencias_id, created_at) = ' .$value_exists_after)->whereRaw('CAST(CalculateTotalGallonsSupplied(id) AS CHAR) LIKE ?', ['%' . $number . '%'])->count();
    
            
                }else{
                        
                    $minor_equipment_fuels = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->whereRaw($filtros_new)->whereRaw('CheckMinorEquipment(dependencias_id, created_at) = ' .$value_exists_after)->whereRaw('CAST(CalculateTotalGallonsSupplied(id) AS CHAR) LIKE ?', ['%' . $number . '%'])->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();
    
                    $count_notifications = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->whereRaw($filtros_new)->whereRaw('CheckMinorEquipment(dependencias_id, created_at) = ' .$value_exists_after)->whereRaw('CAST(CalculateTotalGallonsSupplied(id) AS CHAR) LIKE ?', ['%' . $number . '%'])->count();
            
                }

            } else if (str_contains($filtros, 'exists_after')) {


                if (str_contains($filtros, 'TRUE')) {
                    $value_exists_after = 'true';
                }else{
                    $value_exists_after = 'false';
                }

                $filtros_new = $this->removeExistsAfterCondition($filtros);

                if(Auth::user()->hasRole('mant Consulta proceso')){

                    $minor_equipment_fuels = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->whereRaw($filtros_new)->whereRaw('CheckMinorEquipment(dependencias_id, created_at) = ' .$value_exists_after)->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

                    
    
                    $count_notifications = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->whereRaw($filtros_new)->whereRaw('CheckMinorEquipment(dependencias_id, created_at) = ' .$value_exists_after)->count();
    
            
                }else{
                        
                    $minor_equipment_fuels = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->whereRaw($filtros_new)->whereRaw('CheckMinorEquipment(dependencias_id, created_at) = ' .$value_exists_after)->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();
    
                    $count_notifications = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->whereRaw($filtros_new)->whereRaw('CheckMinorEquipment(dependencias_id, created_at) = ' .$value_exists_after)->count();
            
                }
                
            } else if(str_contains($filtros, 'total_consume_fuel ')) {

                preg_match("/total_consume_fuel LIKE '%(\d+)%'/", $filtros, $matches);

                if (isset($matches[1])) {
                    $number = $matches[1];
                } else {
                    $number = 'na';
                }


                $filtros_new = $this->removeExistsAfterCondition($filtros);

                if(Auth::user()->hasRole('mant Consulta proceso')){

                    $minor_equipment_fuels = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->whereRaw($filtros_new)->whereRaw('CAST(CalculateTotalGallonsSupplied(id) AS CHAR) LIKE ?', ['%' . $number . '%'])->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

                    
    
                    $count_notifications = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->whereRaw($filtros_new)->whereRaw('CAST(CalculateTotalGallonsSupplied(id) AS CHAR) LIKE ?', ['%' . $number . '%'])->count();
    
            
                }else{
                        
                    $minor_equipment_fuels = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->whereRaw($filtros_new)->whereRaw('CAST(CalculateTotalGallonsSupplied(id) AS CHAR) LIKE ?', ['%' . $number . '%'])->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();
    
                    $count_notifications = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->whereRaw($filtros_new)->whereRaw('CAST(CalculateTotalGallonsSupplied(id) AS CHAR) LIKE ?', ['%' . $number . '%'])->count();
            
                }

            } else {

                if(Auth::user()->hasRole('mant Consulta proceso')){

                    $minor_equipment_fuels = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->whereRaw($filtros)->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();
                    
    
                    $count_notifications = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->whereRaw($filtros)->count();
    
            
                }else{
                        
                    $minor_equipment_fuels = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->whereRaw($filtros)->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();
    
                    $count_notifications = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->whereRaw($filtros)->count();
            
                }
            }

        } else if(isset($request["cp"]) && isset($request["pi"])) {

            if(Auth::user()->hasRole('mant Consulta proceso')){

                $minor_equipment_fuels = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

                $count_notifications = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->count();

        
            }else{
                    
                $minor_equipment_fuels = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

                $count_notifications = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->count();
        
            }

        } else {

            
            if(Auth::user()->hasRole('mant Consulta proceso')){

                $minor_equipment_fuels = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->latest()->get()->toArray();

                $count_notifications = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->count();

        
            }else{
                    
                $minor_equipment_fuels = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->latest()->get()->toArray();

                $count_notifications = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->count();
        
            }

        }


        // return $this->sendResponse($notifications->toArray(), trans('data_obtained_successfully'));

        return $this->sendResponseAvanzado($minor_equipment_fuels, trans('data_obtained_successfully'), null, ["total_registros" => $count_notifications]);
    }

    /**
     * Limpia el query de los filtros no deseados en la variable
     *
     * @author Johan David Velasco. - Agosto. 16 - 2024
     * @version 1.0.0
     *
     * @param  $query
     *
     * @return String
     */
    public function removeExistsAfterCondition($query) {
        // Expresión regular para encontrar las condiciones `exists_after LIKE '%TRUE%'`, `exists_after LIKE '%FALSE%'` y `total_consume_fuel LIKE '%[cualquier número]%'`
        $pattern = "/\b(exists_after\s+LIKE\s+'%(TRUE|FALSE)%'|total_consume_fuel\s+LIKE\s+'%[^']+%')\s*(AND\s*)?/i";

        // Reemplaza las condiciones encontradas por una cadena vacía
        $cleanedQuery = preg_replace($pattern, '', $query);

        // Elimina cualquier "AND" colgante al fina l de la consulta
        $cleanedQuery = preg_replace("/\s+AND\s*$/i", '', $cleanedQuery);

        if (empty($cleanedQuery)) {
            $cleanedQuery = "1=1";
        }
    
        return $cleanedQuery;
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param CreateMinorEquipmentFuelRequest $request
     *
     * @return Response
     */
    public function store(CreateMinorEquipmentFuelRequest $request) {
    
        //Recupera el usuario en sesion
        $user=Auth::user();
        
        $input = $request->all();
        //Se crea el campo dependencia id y se le asigna el campo proceso responsable
        $input['dependencias_id']=$input['responsible_process'];
        //Se crea el campo user id y se asigna el valor del usuario en sesion
        $input['users_id']=$user->id;
        //Obtiene todos los registros de equipos menores que tienen esa dependencia
        $minorEquipmentFuelLasts = MinorEquipmentFuel::where('dependencias_id',$input['dependencias_id'])->get();
        if(count($minorEquipmentFuelLasts)>0){
        //Obtiene el ultimo registro de el arreglo de la consulta
        $minorEquipmentFuelLast=$minorEquipmentFuelLasts->last();
        //Se le asigna el valor de la nueva fecha inicial del nuevo periodo como fecha final del anterior
        $minorEquipmentFuelLast->end_date_fortnight=$input['start_date_fortnight'];
        //Se guardan los cambios
        $minorEquipmentFuelLast->save();
        }
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            

            // Inserta el registro en la base de datos
            $minorEquipmentFuel = $this->minorEquipmentFuelRepository->create($input);
            $minorEquipmentFuel ->dependencias;

            //Crea un registro en el historial
            $history=new HistoryEquipmentMinor();
            $history->mant_minor_equipment_fuel_id= $minorEquipmentFuel->id;
            $history->dependencias_id=$input['dependencias_id'];
            $history->users_id=$user->id;
            $history->name="El registro fue creado";
            $history->position="Creado";
            $history->approved_process=$input['approved_process'];
            $history->create=$minorEquipmentFuel->created_at;
            $history->process_leader_name=$minorEquipmentFuel->name_leader;
            $history->save();

            //Se crea y guarda un modelo en el historial de consumo combustible equipos menores
            $historyequipt= new FuelEquipmentHistory();
            $historyequipt->users_id= $user->id;
            $historyequipt->date_register= $minorEquipmentFuel->created_at;
            $historyequipt->description= "Se crea el registro.";
            $historyequipt->action= "Crear";
            $historyequipt->name_user= $user->name;
            $historyequipt->dependencia=  $minorEquipmentFuel->dependencias->nombre;
            $historyequipt->id_equipment_fuel=  $minorEquipmentFuel->id;
            $historyequipt->save();


            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($minorEquipmentFuel->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\MinorEquipmentFuelController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\MinorEquipmentFuelController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }



    /**
     * Actualiza un registro especifico.
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateMinorEquipmentFuelRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMinorEquipmentFuelRequest $request) {
        
        $user=Auth::user();
        $input = $request->all();
        
        /** @var MinorEquipmentFuel $minorEquipmentFuel */
        $minorEquipmentFuel = $this->minorEquipmentFuelRepository->find($id);

        if (empty($minorEquipmentFuel)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        $input['dependencias_id']=$input['responsible_process'];
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $aux= $minorEquipmentFuel ->dependencias_id;
            // Actualiza el registro
            $minorEquipmentFuel = $this->minorEquipmentFuelRepository->update($input, $id);
            //Si la dependencia despues de editar sigue siendo la misma entonces solo envia a actualizar una dependencia
            if($aux== $minorEquipmentFuel->dependencias_id){
                //Envia al metodo paraa actualizar las tablas
                $this->ActualizaTabla($minorEquipmentFuel->dependencias_id, $minorEquipmentFuel->fuel_type);
            }else{
                //Si la dependencia es diferente envia a actualizar las dos dependencias
                $this->ActualizaTabla($minorEquipmentFuel->dependencias_id, $minorEquipmentFuel->fuel_type);
                $this->ActualizaTabla($aux, $minorEquipmentFuel->fuel_type);
            }
            

            //Crea un registro en el historial
            $history=new HistoryEquipmentMinor();
            $history->mant_minor_equipment_fuel_id= $minorEquipmentFuel->id;
            $history->dependencias_id=$input['dependencias_id'];
            $history->users_id=$user->id;
            $history->name="El registro fue editado";
            $history->position="Editado";
            $history->approved_process=$input['approved_process'];
            $history->process_leader_name=$minorEquipmentFuel->name_leader;
            $history->create=$minorEquipmentFuel->updated_at;
            $history->save();
            
            
            //Se crea y guarda un modelo en el historial de consumo combustible equipos menores
            $historyequipt= new FuelEquipmentHistory();
            $historyequipt->users_id= $user->id;
            $historyequipt->date_register= $minorEquipmentFuel->created_at;
            $historyequipt->description= $input['descriptionDelete'];
            $historyequipt->action= "Editar";
            $historyequipt->name_user= $user->name;
            $historyequipt->dependencia=  $minorEquipmentFuel->dependencias->nombre;
            $historyequipt->id_equipment_fuel=  $minorEquipmentFuel->id;
            $historyequipt->save();


            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($minorEquipmentFuel->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\MinorEquipmentFuelController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\MinorEquipmentFuelController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un MinorEquipmentFuel del almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
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
        /** @var MinorEquipmentFuel $minorEquipmentFuel */
        $minorEquipmentFuel = MinorEquipmentFuel::with('mantEquipmentFuelConsumptions')->where('id',$request['id'])->get();
       //Verifica que en la relacion si exista un elemento
        if(count($minorEquipmentFuel[0]->mantEquipmentFuelConsumptions)>0){
            
            return $this->sendResponse("error", 'Para poder eliminar debe primero eliminar todas las relaciones de consumo de combustible por equipo.', 'warning');
        }else{
            
            if (empty($minorEquipmentFuel[0])) {
                return $this->sendError(trans('not_found_element'), 200);
            }       
            
            //LLama el metodo para verificar que va eliminar
            $sentinel=$this->verifyValuesDelete( $minorEquipmentFuel[0]);
            //Verifica si es false
            if($sentinel[0]==false){
                return $this->sendResponse("error", "No se puede editar existe una incongruencia en el registro creado en la fecha:  $sentinel[1]", 'warning');
            }

            // Inicia la transaccion
            DB::beginTransaction();
            try {
                $minorAux= $minorEquipmentFuel[0];

                //Envia todas las relaciones
                $documentsMinorEquipment=$minorEquipmentFuel[0]->mantDocumentsMinorEquipments()->get();
                //Recorre la relaacion
                foreach ($documentsMinorEquipment as $value) {
                    //Lo divide por comas
                    $urls=explode(",", $value->url);
                    //Recorre y elimina los documentos del servidor
                    foreach ($urls as $variable) {
                        Storage::disk('public')->delete($variable);
                    }
    
                }

                //Se crea y guarda un modelo en el historial de consumo combustible equipos menores
                $historyequipt= new FuelEquipmentHistory();
                $historyequipt->users_id= $user->id;
                $historyequipt->date_register= $minorEquipmentFuel[0]->created_at;
                $historyequipt->description= $request['observationDelete'];
                $historyequipt->action= "Eliminar";
                $historyequipt->name_user= $user->name;
                $historyequipt->dependencia=  $minorEquipmentFuel[0]->dependencias->nombre;
                $historyequipt->id_equipment_fuel=  $minorEquipmentFuel[0]->id;
                $historyequipt->save();

                // Elimina el registro
                $minorEquipmentFuel[0]->delete();
                $minorEquipmentFuel[0]->mantDocumentsMinorEquipments()->delete();
                $minorEquipmentFuel[0]->mantHistoryMinorEquipments()->delete();

                //Envia los datos para actualizar la tabla
                $this->ActualizaTabla( $minorAux->dependencias_id, $minorAux->fuel_type);

                
                // Efectua los cambios realizados
                DB::commit();

                return $this->sendResponse($minorEquipmentFuel[0]->toArray(), trans('msg_success_update'));

            } catch (\Illuminate\Database\QueryException $error) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\MinorEquipmentFuelController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\MinorEquipmentFuelController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
                // Retorna error de tipo logico
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
            }
            
        }
        
    }

    // /**
    //  * Organiza la exportacion de datos
    //  *
    //  * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
    //  * @version 1.0.0
    //  *
    //  * @param Request $request datos recibidos
    //  */
    // public function export(Request $request) {
    //     $input = $request->all();

    //     // Tipo de archivo (extencion)
    //     $fileType = $input['fileType'];
    //     // Nombre de archivo con tiempo de creacion
    //     $fileName = time().'-'.trans('minor_equipment_fuels').'.'.$fileType;

    //     // Valida si el tipo de archivo es pdf
    //     if (strcmp($fileType, 'pdf') == 0) {
    //         // Guarda el archivo pdf en ubicacion temporal
    //         // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

    //         // Descarga el archivo generado
    //         return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
    //     } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
    //         // Guarda el archivo excel en ubicacion temporal
    //         // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

    //         // Descarga el archivo generado
    //         return Excel::download(new GenericExport($input['data']), $fileName);
    //     }
    // }

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

        $user=Auth::user();

        if(array_key_exists("filtros", $input)) {

            //Valida si se tienen filtros al exportar excel
            if($input["filtros"] != "") {

                $filtros = $input["filtros"];
                // Suponiendo que $filtros es un string que contiene las condiciones SQL
                if (preg_match('/responsible_process\s+LIKE\s+\'%(\d+)%\'/', $filtros, $matches)) {
                    // Extraer el número que está entre los símbolos %
                    $numero = $matches[1];
                    
                    // Reemplazar la condición LIKE por una condición =
                    $filtros = preg_replace(
                        '/responsible_process\s+LIKE\s+\'%\d+%\'/', 
                        "responsible_process = '$numero'", 
                        $filtros
                    );
                }

                 //Valida su en los filtro vienen exists_after y total_consume_fuel
                if (str_contains($filtros, 'exists_after') && str_contains($filtros, 'total_consume_fuel ')) {

                    //Obtiene el valor de ingresaron en el campo total_consume_fuel
                    preg_match("/total_consume_fuel LIKE '%(\d+)%'/", $filtros, $matches);

                    //Valida si el valor es diferente de vacio y asigna el valor a la variable $number
                    if (isset($matches[1])) {
                        $number = $matches[1];
                    } else {
                        $number = 'na';
                    }

                    //Valida si en el filtro viene un valor true o false
                    if (str_contains($filtros, 'TRUE')) {
                        $value_exists_after = 'true';
                    }else{
                        $value_exists_after = 'false';
                    }

                    //Remueve los querys de exists_after y total_consume_fuel de los filtros para darles un tratado especial
                    $filtros_new = $this->removeExistsAfterCondition($filtros);

                    if(Auth::user()->hasRole('mant Consulta proceso')){

                        $input["data"] = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->whereRaw($filtros_new)->whereRaw('CheckMinorEquipment(dependencias_id, created_at) = ' .$value_exists_after)->whereRaw('CAST(CalculateTotalGallonsSupplied(id) AS CHAR) LIKE ?', ['%' . $number . '%'])->latest()->get()->toArray();

                    }else{
                            
                        $input["data"] = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->whereRaw($filtros_new)->whereRaw('CheckMinorEquipment(dependencias_id, created_at) = ' .$value_exists_after)->whereRaw('CAST(CalculateTotalGallonsSupplied(id) AS CHAR) LIKE ?', ['%' . $number . '%'])->latest()->get()->toArray();
        
                
                    }
                    //Valida si solo viene en el filtro exists_after
                    } else if (str_contains($filtros, 'exists_after')) {


                        if (str_contains($filtros, 'TRUE')) {
                            $value_exists_after = 'true';
                        }else{
                            $value_exists_after = 'false';
                        }

                        $filtros_new = $this->removeExistsAfterCondition($filtros);

                        if(Auth::user()->hasRole('mant Consulta proceso')){

                            $input["data"] = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->whereRaw($filtros_new)->whereRaw('CheckMinorEquipment(dependencias_id, created_at) = ' .$value_exists_after)->latest()->get()->toArray();
                    
                        }else{
                                
                            $input["data"] = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->whereRaw($filtros_new)->whereRaw('CheckMinorEquipment(dependencias_id, created_at) = ' .$value_exists_after)->latest()->get()->toArray();

                        }
                        
                    //Valida si solo viene el en filtro total_consume_fuel
                    } else if(str_contains($filtros, 'total_consume_fuel ')) {

                        preg_match("/total_consume_fuel LIKE '%(\d+)%'/", $filtros, $matches);

                        if (isset($matches[1])) {
                            $number = $matches[1];
                        } else {
                            $number = 'na';
                        }


                        $filtros_new = $this->removeExistsAfterCondition($filtros);

                        if(Auth::user()->hasRole('mant Consulta proceso')){

                            $input["data"] = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->whereRaw($filtros_new)->whereRaw('CAST(CalculateTotalGallonsSupplied(id) AS CHAR) LIKE ?', ['%' . $number . '%'])->latest()->get()->toArray();
            
                    
                        }else{
                                
                            $input["data"] = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->whereRaw($filtros_new)->whereRaw('CAST(CalculateTotalGallonsSupplied(id) AS CHAR) LIKE ?', ['%' . $number . '%'])->latest()->get()->toArray();
                    
                        }

                    } else {

                        if(Auth::user()->hasRole('mant Consulta proceso')){

                            $input["data"] = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->whereRaw($filtros)->latest()->get()->toArray();
                   
                        }else{
                                
                            $input["data"] = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->whereRaw($filtros)->latest()->get()->toArray();

                        }
                    }

            } else {
                if(Auth::user()->hasRole('mant Consulta proceso')){

                    $input["data"] = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('responsible_process', $user->id_dependencia)->latest()->get()->toArray();
            
                }else{
                        
                    $input["data"] = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->latest()->get()->toArray();
            
                }
            }
            
        }

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('minor_equipment_fuels').'.'.$fileType;
        
        return Excel::download(new FuelExport('maintenance::minor_equipment_fuels.report_excel', $input['data'],'q'), $fileName);
    }

     /**
     * Obtiene todas las dependencias 
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 27 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getDependency(){
         //Obtiene todas las dependencias
         $dependency = Dependency::all();

         return $this->sendResponse($dependency->toArray(), trans('data_obtained_successfully'));
    }

     /**
     * Obtiene todos los usuarios con el id de la dependencia
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 27 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getUserDependency($id)
    {
       
        //Obtiene todos los usuarios con el id que ingresa por parametro de la dependencia
        $users = User::where('id_dependencia', $id)->get();
        
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    
    
     /**
     * Obtiene la suma de todos los registros de consumo de combustible por equipo
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 30 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getTotalConsumption($id, $tipo)
    {
        $total=0;
        $consumption=MinorEquipmentFuel::with('mantEquipmentFuelConsumptions')->where('responsible_process', $id)->where('fuel_type', $tipo)->latest()->first();
        if($consumption!=null){
        foreach ($consumption->mantEquipmentFuelConsumptions as $value) {
            $total+= $value->gallons_supplied;
        }
        }
        
        return $this->sendResponse($total, trans('data_obtained_successfully'));
    }

     /**
     * Obtiene el ultimo saldo de combustible
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 30 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getFinalFuel($id, $tipo)
    {
        
        $total=0;
        
        $consumption=MinorEquipmentFuel::with('mantEquipmentFuelConsumptions')->where('responsible_process', $id)->where('fuel_type', $tipo)->latest()->first();
        
        if($consumption!=null){
            $total= $consumption->final_fuel_balance;
        }
        
        
        return $this->sendResponse($total, trans('data_obtained_successfully'));
    }


     /**
     * Actualiza la tabla cuando hay cambios
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 30 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function ActualizaTabla($id, $tipo_fuel)
    {
                //saldo inicial de combustible --  initial_fuel_balance -- es el saldo final del registro anterior
                //mas compras en el periodo   --  more_buy_fortnight -- 
                //menos entregas de combustible  --  less_fuel_deliveries -- es la suma del registro de consumo de combustible por equipos menores
                //saldo final de combustible  --  final_fuel_balance --- es la resta entre saldo inicial de combustible mas compras del periodo menos entregas de combustible
             
                $minorEquipmentTwo = MinorEquipmentFuel::with('mantEquipmentFuelConsumptions')->where('dependencias_id', $id)->where('fuel_type', $tipo_fuel)->get();
                //Recorre cada posicion del arreglo y se le asigna los nuevos valores
                for($i=1; $i< count($minorEquipmentTwo);$i++){                   
                    $minorEquipmentTwo[$i]->initial_fuel_balance=$minorEquipmentTwo[$i-1]->final_fuel_balance;
                    $minorEquipmentTwo[$i]->less_fuel_deliveries=$minorEquipmentTwo[$i-1]->total_consume_fuel;
                    $minorEquipmentTwo[$i]->final_fuel_balance=($minorEquipmentTwo[$i]->initial_fuel_balance+$minorEquipmentTwo[$i]->more_buy_fortnight)-$minorEquipmentTwo[$i]->less_fuel_deliveries;
                    $minorEquipmentTwo[$i]->save();
                }
    }


    

    public function getRequirementOperation($id)
    {
        $requirement_for_operation = MinorEquipmentFuel ::select('fuel_type')->where('id', $id)->get()->first();
        return $this->sendResponse($requirement_for_operation->fuel_type, trans('data_obtained_successfully'));
    }

       /**
     * Verifica que el total de saldos finales de la dependencia sea mayor que el total de todas las suma de consumo de combustible
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 09 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function verifyValuesDelete($minorEquipment)
    {
        //El objeto padre del consumo que se va editar- Galon nuevo que se va guardar- Galones que se van a editar
        
        $totalValueFinal=0;
        $totalConsumption=0;
        $array=[];
        //Se inicializa el arreglo
        $array[0]=true;
        $array[1]=null;
        //Se buscan todos los registros posteriores al que se desea editar
        $minorEquipmentTwo = MinorEquipmentFuel::with('mantEquipmentFuelConsumptions')->where('dependencias_id', $minorEquipment->dependencias_id)->where('created_at','>',$minorEquipment->created_at)->get();
        //Verifica que si existan registros posteriores
        if(count($minorEquipmentTwo)!=0){
            $valueDifferent=$minorEquipment->final_fuel_balance-$minorEquipment->total_consume_fuel;
            foreach ($minorEquipmentTwo as $value) {
                $valueDiferentLess=$value->final_fuel_balance-$valueDifferent;
                if($valueDiferentLess < $value->total_consume_fuel){
                    $array[0]=false;
                    $array[1]=$value->created_at;
                    return $array;
                }
            }
        }
        return $array;
    }



}
