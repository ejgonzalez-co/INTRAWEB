<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateIndicatorsRequest;
use App\Exports\Maintenance\IndicatorExport\FuelVehiclesIndicatorExport;
use Modules\Maintenance\Http\Requests\UpdateIndicatorsRequest;
use App\Http\Controllers\AppBaseController;
use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;
use Modules\Maintenance\Models\ResumeEquipmentMachinery;
use Modules\Maintenance\Models\ResumeEquipmentMachineryLeca;
use Modules\Maintenance\Models\ResumeEquipmentLeca;
use Modules\Maintenance\Models\ResumeInventoryLeca;
use Modules\Maintenance\Models\EquipmentMinorFuelConsumption;
use Modules\Maintenance\Models\MinorEquipmentFuel;
use Modules\Maintenance\Models\HistoryEquipmentMinor;
use Modules\Maintenance\Models\Dependency;
use App\Exports\Maintenance\FuelMinorExport\FuelExport;
use Modules\Maintenance\Models\ProviderContract;
use Modules\Maintenance\Models\AssetType;
use Modules\Maintenance\Models\VehicleFuel;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use \stdClass;
use Response;
use Auth;
use DB;

/**
 * Esta clase es de los indicadores
 *
 * @author Nicolas Dario Ortiz Peña. - Septiembre. 23 - 2021
 * @version 1.0.0
 */
class IndicatorsController extends AppBaseController {




    /**
     * Muestra la vista para el CRUD de Indicators.
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 23 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::indicators.index');
    }

        /**
     * Genera el reporte de excel
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 23 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function reportExcel(Request $request) {
        //Recibe todos los valores del request
        $indicatorType=$request['indicator_type'];
        $dependencias_id=$request['dependencias_id'];
        $mant_asset_type_id=$request['mant_asset_type_id'];
        $name_equipment=$request['name_equipment'];
        $init_date=$request['init_date'];
        $final_date=$request['final_date'];
        $typeConsumption=$request['type_consumption'];
        //Verifica si se selecciono Recorrido
        if($indicatorType == "Recorrido"){

            //LLama todos los registros de combustible vehiculo entre esas fechas
            $vehicle=VehicleFuel::with(['assetType', 'resumeMachineryVehiclesYellow', 'dependencias'])->where('created_at','>',$init_date)->where('created_at','<',$final_date)->latest()->get();

            //Verifica que no venga vacio
            if($dependencias_id!=null){
                //Si no es vacio filtra por la dependencia
                $vehicle=$vehicle->where('dependencies_id', $dependencias_id);
            }
            //Verifica que no venga vacio
            if($mant_asset_type_id!=null){
                //Si el tipo de activo nos es nullo filtra
                $vehicle=$vehicle->where('mant_asset_type_id', $mant_asset_type_id);
            }
            //Verifica que no venga vacio
            if($name_equipment!=null){
                //Si el nombre del equipo no es null filtra por nombre del equipo
                $vehicle=$vehicle->where('mant_resume_machinery_vehicles_yellow_id', $name_equipment);
            }
            
            if(count($vehicle)==0){
                return $this->sendResponse("error", 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                $NewVariable = $vehicle->toArray();
                array_push($NewVariable, [],[]);
                // dd($vehicle->toArray());
                // Tipo de archivo (extencion)
                $fileType = 'xlsx';
                $fileName = date('Y-m-d H:i:s').'-'.trans('Indicadorcombustible').'.'.$fileType;
                //Retorna el archivo de excel
                return Excel::download(new FuelVehiclesIndicatorExport('maintenance::indicators.report_excel_tour', $NewVariable, 'f'), $fileName);
            }
        } 
        //Valida si se selecciono Trabajo en horas
        else if($indicatorType=="Trabajo en horas"){
            //LLama todos los registros de combustible vehiculo entre esas fechas
            $vehicle=VehicleFuel::with(['assetType', 'resumeMachineryVehiclesYellow', 'dependencias'])->where('created_at','>',$init_date)->where('created_at','<',$final_date)->latest()->get();

            //Verifica que no venga vacio
            if($dependencias_id!=null){
                //Si no es vacio filtra por la dependencia
                $vehicle=$vehicle->where('dependencies_id', $dependencias_id);
            }
            //Verifica que no venga vacio
            if($mant_asset_type_id!=null){
                //Si el tipo de activo nos es nullo filtra
                $vehicle=$vehicle->where('mant_asset_type_id', $mant_asset_type_id);
            }
            //Verifica que no venga vacio
            if($name_equipment!=null){
                //Si el nombre del equipo no es null filtra por nombre del equipo
                $vehicle=$vehicle->where('mant_resume_machinery_vehicles_yellow_id', $name_equipment);
            }
            if(count($vehicle)==0){
                return $this->sendResponse("error", 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                $NewVariable = $vehicle->toArray();
                array_push($NewVariable, [],[]);
                // Tipo de archivo (extencion)
                $fileType = 'xlsx';
                $fileName = date('Y-m-d H:i:s').'-'.trans('Indicadorcombustible').'.'.$fileType;
                //Retorna el archivo de excel
                return Excel::download(new FuelVehiclesIndicatorExport('maintenance::indicators.report_excel_working_hours', $NewVariable, 'f'), $fileName);
            }
        }
        //Verifica si selecciono consumo de combustible
        else if($indicatorType=="Consumo combustible"){
            //Verifica si selecciono consumo por equipo menor
            if($typeConsumption=="Consumo por equipo menor"){
                if($dependencias_id == null){
                    $vehicle = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('created_at','<=',$final_date)->latest()->get();
                } else {
                    $vehicle = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('created_at','<=',$final_date)->where('dependencias_id', '=',$dependencias_id)->latest()->get();
                }

                if(count($vehicle)==0){
                    return $this->sendResponse("error", 'No se encuentran registros con esos filtros.', 'warning');
                }else{
                    // Tipo de archivo (extencion)
                    $fileType = 'xlsx';
                    $fileName = date('Y-m-d H:i:s').'-'.trans('Indicadorcombustible').'.'.$fileType;
                    return Excel::download(new FuelExport('maintenance::indicators.report_excel_minor_equiptment', $vehicle->toArray(),'q'), $fileName);


                    // $fileType = 'xlsx';
                    // $fileName = date('Y-m-d H:i:s').'-'.trans('Indicadorcombustible').'.'.$fileType;x
                    // //Retorna el archivo de excel
                    // return Excel::download(new FuelVehiclesIndicatorExport('maintenance::indicators.report_excel_Fuel_Vehicle', $vehicle->toArray(),'i'), $fileName);
                }
            }else{
                //Verifica si selecciona consumo de combustible por equipo
                if($typeConsumption=="Consumo combustible vehiculo"){
                    //LLama todos los registros de combustible vehiculo entre esas fechas
                    $vehicle=VehicleFuel::with(['assetType', 'resumeMachineryVehiclesYellow', 'dependencias'])->where('created_at','>',$init_date)->where('created_at','<',$final_date)->latest()->get();
                    
                    //Verifica que no venga vacio
                    if($dependencias_id!=null){
                        //Si no es vacio filtra por la dependencia
                        $vehicle=$vehicle->where('dependencies_id', $dependencias_id);
                    }
                    //Verifica que no venga vacio
                    if($mant_asset_type_id!=null){
                        //Si el tipo de activo nos es nullo filtra
                        $vehicle=$vehicle->where('mant_asset_type_id', $mant_asset_type_id);
                    }
                    //Verifica que no venga vacio
                    if($name_equipment!=null){
                        //Si el nombre del equipo no es null filtra por nombre del equipo
                        $vehicle=$vehicle->where('mant_resume_machinery_vehicles_yellow_id', $name_equipment);
                        
                    }
                    
                    
                    if(count($vehicle)==0){
                        return $this->sendResponse("error", 'No se encuentran registros con esos filtros.', 'warning');
                    }else{
                        // Tipo de archivo (extencion)
                        $fileType = 'xlsx';
                        $fileName = date('Y-m-d H:i:s').'-'.trans('Indicadorcombustible').'.'.$fileType;
                        //Retorna el archivo de excel
                        return Excel::download(new FuelVehiclesIndicatorExport('maintenance::indicators.report_excel_Fuel_Vehicle', $vehicle->toArray(),'i'), $fileName);
                    }
                }
            }

        }
        //Valida si se selecciono Recorrido en Km y horas
        else if($indicatorType=="Recorrido en Km y horas"){
            //LLama todos los registros de combustible vehiculo entre esas fechas
            $vehicle=VehicleFuel::with(['assetType', 'resumeMachineryVehiclesYellow', 'dependencias'])->where('created_at','>',$init_date)->where('created_at','<',$final_date)->latest()->get();

            //Verifica que no venga vacio
            if($dependencias_id!=null){
                //Si no es vacio filtra por la dependencia
                $vehicle=$vehicle->where('dependencies_id', $dependencias_id);
            }
            //Verifica que no venga vacio
            if($mant_asset_type_id!=null){
                //Si el tipo de activo nos es nullo filtra
                $vehicle=$vehicle->where('mant_asset_type_id', $mant_asset_type_id);
            }
            //Verifica que no venga vacio
            if($name_equipment!=null){
                //Si el nombre del equipo no es null filtra por nombre del equipo
                $vehicle=$vehicle->where('mant_resume_machinery_vehicles_yellow_id', $name_equipment);
            }
            if(count($vehicle)==0){
                return $this->sendResponse("error", 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                $NewVariable = $vehicle->toArray();
                array_push($NewVariable, [],[]);
                // Tipo de archivo (extencion)
                $fileType = 'xlsx';
                $fileName = date('Y-m-d H:i:s').'-'.trans('Indicadorcombustible').'.'.$fileType;
                //Retorna el archivo de excel
                return Excel::download(new FuelVehiclesIndicatorExport('maintenance::indicators.report_excel_distance_km_and_hours', $NewVariable, 'g'), $fileName);
            }
        }
        //Valida si se selecciono Rendimiento en Km por galón
        else if($indicatorType=="Rendimiento en Km por galón"){
            //LLama todos los registros de combustible vehiculo entre esas fechas
            $vehicle=VehicleFuel::with(['assetType', 'resumeMachineryVehiclesYellow', 'dependencias'])->where('created_at','>',$init_date)->where('created_at','<',$final_date)->latest()->get();

            //Verifica que no venga vacio
            if($dependencias_id!=null){
                //Si no es vacio filtra por la dependencia
                $vehicle=$vehicle->where('dependencies_id', $dependencias_id);
            }
            //Verifica que no venga vacio
            if($mant_asset_type_id!=null){
                //Si el tipo de activo nos es nullo filtra
                $vehicle=$vehicle->where('mant_asset_type_id', $mant_asset_type_id);
            }
            //Verifica que no venga vacio
            if($name_equipment!=null){
                //Si el nombre del equipo no es null filtra por nombre del equipo
                $vehicle=$vehicle->where('mant_resume_machinery_vehicles_yellow_id', $name_equipment);
            }
            if(count($vehicle)==0){
                return $this->sendResponse("error", 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                $NewVariable = $vehicle->toArray();
                array_push($NewVariable, []);
                // Tipo de archivo (extencion)
                $fileType = 'xlsx';
                $fileName = date('Y-m-d H:i:s').'-'.trans('Indicadorcombustible').'.'.$fileType;
                //Retorna el archivo de excel
                return Excel::download(new FuelVehiclesIndicatorExport('maintenance::indicators.report_excel_distance_km_gallon', $NewVariable, 'f'), $fileName);
            }
        }
        //Valida si se selecciono Rendimiento en horas por galón
        else if($indicatorType=="Rendimiento en horas por galón"){
            //LLama todos los registros de combustible vehiculo entre esas fechas
            $vehicle=VehicleFuel::with(['assetType', 'resumeMachineryVehiclesYellow', 'dependencias'])->where('created_at','>',$init_date)->where('created_at','<',$final_date)->latest()->get();

            //Verifica que no venga vacio
            if($dependencias_id!=null){
                //Si no es vacio filtra por la dependencia
                $vehicle=$vehicle->where('dependencies_id', $dependencias_id);
            }
            //Verifica que no venga vacio
            if($mant_asset_type_id!=null){
                //Si el tipo de activo nos es nullo filtra
                $vehicle=$vehicle->where('mant_asset_type_id', $mant_asset_type_id);
            }
            //Verifica que no venga vacio
            if($name_equipment!=null){
                //Si el nombre del equipo no es null filtra por nombre del equipo
                $vehicle=$vehicle->where('mant_resume_machinery_vehicles_yellow_id', $name_equipment);
            }
            $firstVariable = $vehicle->toArray();
            $secondVariable = reset($firstVariable);
            if($dependencias_id != null){
                $vehicle=$vehicle->where('asset_name', $secondVariable['asset_name']);
            }
            if(count($vehicle)==0){
                return $this->sendResponse("error", 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                $NewVariable = $vehicle->toArray();
                // array_push($NewVariable, []);
                // Tipo de archivo (extencion)
                $fileType = 'xlsx';
                $fileName = date('Y-m-d H:i:s').'-'.trans('Indicadorcombustible').'.'.$fileType;
                //Retorna el archivo de excel
                return Excel::download(new FuelVehiclesIndicatorExport('maintenance::indicators.report_excel_distance_Hr_gallon', $NewVariable, 'f'), $fileName);
            }
        }
        //Valida si se selecciono Recorrido en Km y horas por galón
        else if($indicatorType=="Recorrido en Km y horas por galón"){
            //LLama todos los registros de combustible vehiculo entre esas fechas
            $vehicle=VehicleFuel::with(['assetType', 'resumeMachineryVehiclesYellow', 'dependencias'])->where('created_at','>',$init_date)->where('created_at','<',$final_date)->latest()->get();

            //Verifica que no venga vacio
            if($dependencias_id!=null){
                //Si no es vacio filtra por la dependencia
                $vehicle=$vehicle->where('dependencies_id', $dependencias_id);
            }
            //Verifica que no venga vacio
            if($mant_asset_type_id!=null){
                //Si el tipo de activo nos es nullo filtra
                $vehicle=$vehicle->where('mant_asset_type_id', $mant_asset_type_id);
            }
            //Verifica que no venga vacio
            if($name_equipment!=null){
                //Si el nombre del equipo no es null filtra por nombre del equipo
                $vehicle=$vehicle->where('mant_resume_machinery_vehicles_yellow_id', $name_equipment);
            }
            $firstVariable = $vehicle->toArray();
            $secondVariable = reset($firstVariable);
            if($dependencias_id != null){
                $vehicle=$vehicle->where('asset_name', $secondVariable['asset_name']);
            }
            if(count($vehicle)==0){
                return $this->sendResponse("error", 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                $NewVariable = $vehicle->toArray();
                array_push($NewVariable, [],[],[]);
                // Tipo de archivo (extencion)
                $fileType = 'xlsx';
                $fileName = date('Y-m-d H:i:s').'-'.trans('Indicadorcombustible').'.'.$fileType;
                //Retorna el archivo de excel
                return Excel::download(new FuelVehiclesIndicatorExport('maintenance::indicators.report_excel_distance_Km_Hr_gallon', $NewVariable, 'g'), $fileName);
            }
        }
        //Valida que se alla seleccionado Porcentaje de ejecución por contrato
        else if($indicatorType=="Porcentaje de ejecución por contrato"){
            if($dependencias_id == null){
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->latest()->get();
            } else {
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->where('dependencias_id', '=',$dependencias_id)->latest()->get();
            }
            if(count($provider_contracts)==0){
                return $this->sendResponse("error", 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                // dd($provider_contracts[0]->mantBudgetAssignation[0]->mantAdministrationCostItems[0]->mantBudgetExecutions[0]);
                // Tipo de archivo (extencion)
                $fileType = 'xlsx';
                $fileName = date('Y-m-d H:i:s').'-'.trans('indicadorContratoProovedores').'.'.$fileType;
                //Retorna el archivo de excel
                return Excel::download(new FuelVehiclesIndicatorExport('maintenance::indicators.report_excel_provider_contracts', $provider_contracts->toArray(),'u'), $fileName);
            }
        }
        //Valida que se alla seleccionado Porcentaje de ejecución de todos los contratos
        else if($indicatorType=="Porcentaje de ejecución de todos los contratos"){
            if($dependencias_id == null){
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->latest()->get();
            } else {
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->where('dependencias_id', '=',$dependencias_id)->latest()->get();
            }
            if(count($provider_contracts)==0){
                return $this->sendResponse("error", 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                $NewVariable = $provider_contracts->toArray();
                array_push($NewVariable, []);
                // dd($provider_contracts[0]->mantBudgetAssignation[0]->mantAdministrationCostItems[0]->mantBudgetExecutions[0]);
                // Tipo de archivo (extencion)
                $fileType = 'xlsx';
                $fileName = date('Y-m-d H:i:s').'-'.trans('indicadorContratoProovedores').'.'.$fileType;

                // dd($NewVariable);
                //Retorna el archivo de excel
                return Excel::download(new FuelVehiclesIndicatorExport('maintenance::indicators.report_excel_provider_all_contracts', $NewVariable,'u'), $fileName);
            }
        }
        //Valida que se alla seleccionado Valor contratado por contrato
        else if($indicatorType=="Valor contratado por contrato"){
            if($dependencias_id == null){
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->latest()->get();
            } else {
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->where('dependencias_id', '=',$dependencias_id)->latest()->get();
            }
            if(count($provider_contracts)==0){
                return $this->sendResponse("error", 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                $NewVariable = $provider_contracts->toArray();
                array_push($NewVariable, []);
                // dd($provider_contracts[0]->mantBudgetAssignation[0]->mantAdministrationCostItems[0]->mantBudgetExecutions[0]);
                // Tipo de archivo (extencion)
                $fileType = 'xlsx';
                $fileName = date('Y-m-d H:i:s').'-'.trans('indicadorContratoProovedores').'.'.$fileType;
                //Retorna el archivo de excel
                return Excel::download(new FuelVehiclesIndicatorExport('maintenance::indicators.report_excel_provider_contractually_contracted', $NewVariable, 'f'), $fileName);
            }
        }
        //Valida que se alla seleccionado Valor ejecutado por contrato
        else if($indicatorType=="Valor ejecutado por contrato"){
            if($dependencias_id == null){
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->latest()->get();
            } else {
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->where('dependencias_id', '=',$dependencias_id)->latest()->get();
            }
            if(count($provider_contracts)==0){
                return $this->sendResponse("error", 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                $NewVariable = $provider_contracts->toArray();
                array_push($NewVariable, []);
                // dd($provider_contracts[0]->mantBudgetAssignation[0]->mantAdministrationCostItems[0]->mantBudgetExecutions[0]);
                // Tipo de archivo (extencion)
                $fileType = 'xlsx';
                $fileName = date('Y-m-d H:i:s').'-'.trans('indicadorContratoProovedores').'.'.$fileType;
                //Retorna el archivo de excel
                return Excel::download(new FuelVehiclesIndicatorExport('maintenance::indicators.report_excel_provider_executed_contracted', $NewVariable,'g'), $fileName);
            }
        }
        //Valida que se alla seleccionado Cantidad de activos por estado y dependencia
        else if($indicatorType=="Cantidad de activos por estado y dependencia"){
            if($dependencias_id == null){
                $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::with(["provider", "mantCategory","assetType", "dependencies", "mantDocumentsAsset"])->select(DB::raw('*, count(*) as cont'))->groupBy(['dependencias_id','mant_asset_type_id','status'])->latest()->get();
                $activos = $resume_machinery_vehicles_yellows->toArray();

                $resume_equipment_machinery = ResumeEquipmentMachinery::with(["provider", "mantCategory", "dependencies", "characteristicsEquipment","assetType", "mantDocumentsAsset"])->select(DB::raw('*, count(*) as cont'))->groupBy(['dependencias_id','mant_asset_type_id','status'])->latest()->get();
                $activos2 = $resume_equipment_machinery->toArray();

                foreach($activos2 as $activo) {
                    array_push($activos, $activo);
                }

                $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::with(["provider", "mantCategory", "dependencies", "compositionEquipmentLeca","assetType", "maintenanceEquipmentLeca", "mantDocumentsAsset"])->select(DB::raw('*, count(*) as cont'))->groupBy(['dependencias_id','mant_asset_type_id'])->latest()->get();
                $activos3 = $resume_equipment_machinery_lecas->toArray();

                foreach($activos3 as $activo) {
                    array_push($activos, $activo);
                }

                $resume_equipment_lecas = ResumeEquipmentLeca::with(["provider", "mantCategory", "dependencies", "specificationsEquipmentLeca", "mantDocumentsAsset","assetType"])->select(DB::raw('*, count(*) as cont'))->groupBy(['dependencias_id','mant_asset_type_id'])->latest()->get();
                $activos4 = $resume_equipment_lecas->toArray();

                foreach($activos4 as $activo) {
                    array_push($activos, $activo);
                }

                $resume_inventory_lecas = ResumeInventoryLeca::with(["provider","dependencies", "mantCategory", "scheduleInventoryLeca", "mantDocumentsAsset","assetType"])->select(DB::raw('*, count(*) as cont'))->groupBy(['dependencias_id','mant_asset_type_id'])->latest()->get();
                $activos5 = $resume_inventory_lecas->toArray();

                foreach($activos5 as $activo) {
                    array_push($activos, $activo);
                }
            } else {
                $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::with(["provider", "mantCategory","assetType", "dependencies", "mantDocumentsAsset", "TireReferencesFront", "TireReferencesRear"])->where('dependencias_id', $dependencias_id)->select(DB::raw('*, count(*) as cont'))->groupBy(['dependencias_id','mant_asset_type_id','status'])->latest()->get();
                $activos = $resume_machinery_vehicles_yellows->toArray();

                $resume_equipment_machinery = ResumeEquipmentMachinery::with(["provider", "mantCategory", "dependencies", "characteristicsEquipment","assetType", "mantDocumentsAsset"])->where('dependencias_id', $dependencias_id)->select(DB::raw('*, count(*) as cont'))->groupBy(['dependencias_id','mant_asset_type_id','status'])->latest()->get();
                $activos2 = $resume_equipment_machinery->toArray();

                foreach($activos2 as $activo) {
                    array_push($activos, $activo);
                }

                $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::with(["provider", "mantCategory", "dependencies", "compositionEquipmentLeca","assetType", "maintenanceEquipmentLeca", "mantDocumentsAsset"])->where('dependencias_id', $dependencias_id)->select(DB::raw('*, count(*) as cont'))->groupBy(['dependencias_id','mant_asset_type_id'])->latest()->get();
                $activos3 = $resume_equipment_machinery_lecas->toArray();

                foreach($activos3 as $activo) {
                    array_push($activos, $activo);
                }

                $resume_equipment_lecas = ResumeEquipmentLeca::with(["provider", "mantCategory", "dependencies", "specificationsEquipmentLeca", "mantDocumentsAsset","assetType"])->where('dependencias_id', $dependencias_id)->select(DB::raw('*, count(*) as cont'))->groupBy(['dependencias_id','mant_asset_type_id'])->latest()->get();
                $activos4 = $resume_equipment_lecas->toArray();

                foreach($activos4 as $activo) {
                    array_push($activos, $activo);
                }

                $resume_inventory_lecas = ResumeInventoryLeca::with(["provider","dependencies", "mantCategory", "scheduleInventoryLeca", "mantDocumentsAsset","assetType"])->select(DB::raw('*, count(*) as cont'))->groupBy(['dependencias_id','mant_asset_type_id'])->latest()->get();
                $activos5 = $resume_inventory_lecas->toArray();

                foreach($activos5 as $activo) {
                    array_push($activos, $activo);
                }
            }
            if(count($activos)==0){
                return $this->sendResponse("error", 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                $fileType = 'xlsx';
                $fileName = date('Y-m-d H:i:s').'-'.trans('indicadorContratoProovedores').'.'.$fileType;
                //Retorna el archivo de excel
                return Excel::download(new FuelVehiclesIndicatorExport('maintenance::indicators.report_excel_quantity_assets', $activos,'e'), $fileName);
            }
        }
    }

    /**
     * Verifica que si exista un resultado a la consulta
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 23 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function verifyIndicator(Request $request) {
        //Recibe todos los valores del request
        $indicatorType=$request['indicator_type'];
        $dependencias_id=$request['dependencias_id'];
        $mant_asset_type_id=$request['mant_asset_type_id'];
        $name_equipment=$request['name_equipment'];
        $init_date=$request['init_date'];
        $final_date=$request['final_date'];
        $typeConsumption=$request['type_consumption'];

        //Verifica si se selecciono Recorrido
        if($indicatorType == "Recorrido"){
            //LLama todos los registros de combustible vehiculo entre esas fechas
            $vehicle=VehicleFuel::with(['assetType', 'resumeMachineryVehiclesYellow', 'dependencias'])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->latest()->get();

            //Verifica que no venga vacio
            if($dependencias_id!=null){
                //Si no es vacio filtra por la dependencia
                $vehicle=$vehicle->where('dependencie
                s_id', $dependencias_id);
            }
            
            //Verifica que no venga vacio
            if($mant_asset_type_id!=null){
                //Si el tipo de activo nos es nullo filtra
                $vehicle=$vehicle->where('mant_asset_type_id', $mant_asset_type_id);
            }
            
            //Verifica que no venga vacio
            if($name_equipment!=null){
                //Si el nombre del equipo no es null filtra por nombre del equipo
                $vehicle=$vehicle->where('mant_resume_machinery_vehicles_yellow_id', $name_equipment);
                
            }

            //Si no existen registros con esos filtros se retorna false
            if(count($vehicle)==0){
                return $this->sendResponse( false, 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                //SI si existen registros con ese ffiltro se retorna true
                return $this->sendResponse( true, 'Se encontraron registros con esos filtros', 'success');
            }
        }
        //Verifica si selecciono trabajo en horas
        else if($indicatorType=="Trabajo en horas"){
            //LLama todos los registros de combustible vehiculo entre esas fechas
            $vehicle=VehicleFuel::with(['assetType', 'resumeMachineryVehiclesYellow', 'dependencias'])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->latest()->get();

            //Verifica que no venga vacio
            if($dependencias_id!=null){
                //Si no es vacio filtra por la dependencia
                $vehicle=$vehicle->where('dependencies_id', $dependencias_id);
            }
            
            //Verifica que no venga vacio
            if($mant_asset_type_id!=null){
                //Si el tipo de activo nos es nullo filtra
                $vehicle=$vehicle->where('mant_asset_type_id', $mant_asset_type_id);
            }
            
            //Verifica que no venga vacio
            if($name_equipment!=null){
                //Si el nombre del equipo no es null filtra por nombre del equipo
                $vehicle=$vehicle->where('mant_resume_machinery_vehicles_yellow_id', $name_equipment);
                
            }
            
            //Si no existen registros con esos filtros se retorna false
            if(count($vehicle)==0){
                return $this->sendResponse( false, 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                //SI si existen registros con ese ffiltro se retorna true
                return $this->sendResponse( true, 'No se encuentran registros con esos filtros.', 'warning');
            }
        }
        //Verifica si selecciono consumo de combustible
        else if($indicatorType=="Consumo combustible"){
            //Verifica si selecciono consumo por equipo menor
            if($typeConsumption=="Consumo por equipo menor"){
                if($dependencias_id == null){
                    $vehicle = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('created_at','<=',$final_date)->latest()->get();
                } else {
                    $vehicle = MinorEquipmentFuel::with(['dependencias', 'mantDocumentsMinorEquipments','dependenciasResponsable', 'dependenciasAprobo','mantEquipmentFuelConsumptions','mantHistoryMinorEquipments'])->where('created_at','<=',$final_date)->where('dependencias_id', '=',$dependencias_id)->latest()->get();
                }

                //Si no existen registros con esos filtros se retorna false
                if(count($vehicle)==0){
                    return $this->sendResponse( false, 'No se encuentran registros con esos filtros.', 'warning');
                }else{
                    //SI si existen registros con ese ffiltro se retorna true
                    return $this->sendResponse( true, 'No se encuentran registros con esos filtros.', 'warning');
                }
            }else{
                //Verifica si selecciona consumo de combustible por equipo
                if($typeConsumption=="Consumo combustible vehiculo"){

                    
                    //LLama todos los registros de combustible vehiculo entre esas fechas
                    $vehicle=VehicleFuel::with(['assetType', 'resumeMachineryVehiclesYellow', 'dependencias'])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->latest()->get();
                    
                    //Verifica que no venga vacio
                    if($dependencias_id!=null){
                        //Si no es vacio filtra por la dependencia
                        $vehicle=$vehicle->where('dependencies_id', $dependencias_id);
                    }
                    
                    //Verifica que no venga vacio
                    if($mant_asset_type_id!=null){
                        //Si el tipo de activo nos es nullo filtra
                        $vehicle=$vehicle->where('mant_asset_type_id', $mant_asset_type_id);
                    }
                    
                    //Verifica que no venga vacio
                    if($name_equipment!=null){
                        //Si el nombre del equipo no es null filtra por nombre del equipo
                        $vehicle=$vehicle->where('mant_resume_machinery_vehicles_yellow_id', $name_equipment);
                        
                    }
                    
                    //Si no existen registros con esos filtros se retorna false
                    if(count($vehicle)==0){
                        return $this->sendResponse( false, 'No se encuentran registros con esos filtros.', 'warning');
                    }else{
                        //SI si existen registros con ese ffiltro se retorna true
                        return $this->sendResponse( true, 'No se encuentran registros con esos filtros.', 'warning');
                    }
                }
            }

        }
        //Verifica si selecciono Recorrido en Km y horas
        else if($indicatorType=="Recorrido en Km y horas"){
            //LLama todos los registros de combustible vehiculo entre esas fechas
            $vehicle=VehicleFuel::with(['assetType', 'resumeMachineryVehiclesYellow', 'dependencias'])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->latest()->get();

            //Verifica que no venga vacio
            if($dependencias_id!=null){
                //Si no es vacio filtra por la dependencia
                $vehicle=$vehicle->where('dependencies_id', $dependencias_id);
            }
            
            //Verifica que no venga vacio
            if($mant_asset_type_id!=null){
                //Si el tipo de activo nos es nullo filtra
                $vehicle=$vehicle->where('mant_asset_type_id', $mant_asset_type_id);
            }
            
            //Verifica que no venga vacio
            if($name_equipment!=null){
                //Si el nombre del equipo no es null filtra por nombre del equipo
                $vehicle=$vehicle->where('mant_resume_machinery_vehicles_yellow_id', $name_equipment);
                
            }
            
            //Si no existen registros con esos filtros se retorna false
            if(count($vehicle)==0){
                return $this->sendResponse( false, 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                //SI si existen registros con ese ffiltro se retorna true
                return $this->sendResponse( true, 'No se encuentran registros con esos filtros.', 'warning');
            }
        }
        //Verifica si selecciono Rendimiento en Km por galón
        else if($indicatorType=="Rendimiento en Km por galón"){
            //LLama todos los registros de combustible vehiculo entre esas fechas
            $vehicle=VehicleFuel::with(['assetType', 'resumeMachineryVehiclesYellow', 'dependencias'])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->latest()->get();

            //Verifica que no venga vacio
            if($dependencias_id!=null){
                //Si no es vacio filtra por la dependencia
                $vehicle=$vehicle->where('dependencies_id', $dependencias_id);
            }
            
            //Verifica que no venga vacio
            if($mant_asset_type_id!=null){
                //Si el tipo de activo nos es nullo filtra
                $vehicle=$vehicle->where('mant_asset_type_id', $mant_asset_type_id);
            }
            
            //Verifica que no venga vacio
            if($name_equipment!=null){
                //Si el nombre del equipo no es null filtra por nombre del equipo
                $vehicle=$vehicle->where('mant_resume_machinery_vehicles_yellow_id', $name_equipment);
                
            }
            
            //Si no existen registros con esos filtros se retorna false
            if(count($vehicle)==0){
                return $this->sendResponse( false, 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                //SI si existen registros con ese ffiltro se retorna true
                return $this->sendResponse( true, 'No se encuentran registros con esos filtros.', 'warning');
            }
        }
        //Verifica si selecciono Rendimiento en horas por galón
        else if($indicatorType=="Rendimiento en horas por galón"){
            //LLama todos los registros de combustible vehiculo entre esas fechas
            $vehicle=VehicleFuel::with(['assetType', 'resumeMachineryVehiclesYellow', 'dependencias'])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->latest()->get();

            //Verifica que no venga vacio
            if($dependencias_id!=null){
                //Si no es vacio filtra por la dependencia
                $vehicle=$vehicle->where('dependencies_id', $dependencias_id);
            }
            
            //Verifica que no venga vacio
            if($mant_asset_type_id!=null){
                //Si el tipo de activo nos es nullo filtra
                $vehicle=$vehicle->where('mant_asset_type_id', $mant_asset_type_id);
            }
            
            //Verifica que no venga vacio
            if($name_equipment!=null){
                //Si el nombre del equipo no es null filtra por nombre del equipo
                $vehicle=$vehicle->where('mant_resume_machinery_vehicles_yellow_id', $name_equipment);
                
            }
            $firstVariable = $vehicle->toArray();
            $secondVariable = reset($firstVariable);
            if($dependencias_id != null){
                $vehicle=$vehicle->where('asset_name', $secondVariable['asset_name']);
            }
            //Si no existen registros con esos filtros se retorna false
            if(count($vehicle)==0){
                return $this->sendResponse( false, 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                //SI si existen registros con ese ffiltro se retorna true
                return $this->sendResponse( true, 'No se encuentran registros con esos filtros.', 'warning');
            }
        }
        //Verifica si selecciono Recorrido en Km y horas por galón
        else if($indicatorType=="Recorrido en Km y horas por galón"){
            //LLama todos los registros de combustible vehiculo entre esas fechas
            $vehicle=VehicleFuel::with(['assetType', 'resumeMachineryVehiclesYellow', 'dependencias'])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->latest()->get();

            //Verifica que no venga vacio
            if($dependencias_id!=null){
                //Si no es vacio filtra por la dependencia
                $vehicle=$vehicle->where('dependencies_id', $dependencias_id);
            }
            
            //Verifica que no venga vacio
            if($mant_asset_type_id!=null){
                //Si el tipo de activo nos es nullo filtra
                $vehicle=$vehicle->where('mant_asset_type_id', $mant_asset_type_id);
            }
            
            //Verifica que no venga vacio
            if($name_equipment!=null){
                //Si el nombre del equipo no es null filtra por nombre del equipo
                $vehicle=$vehicle->where('mant_resume_machinery_vehicles_yellow_id', $name_equipment);
                
            }
            $firstVariable = $vehicle->toArray();
            $secondVariable = reset($firstVariable);
            if($dependencias_id != null){
                $vehicle=$vehicle->where('asset_name', $secondVariable['asset_name']);
            }
            //Si no existen registros con esos filtros se retorna false
            if(count($vehicle)==0){
                return $this->sendResponse( false, 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                //SI si existen registros con ese ffiltro se retorna true
                return $this->sendResponse( true, 'No se encuentran registros con esos filtros.', 'warning');
            }
        }
        //Verificar si se selecciono Porcentaje de ejecución por contrato
        else if($indicatorType=="Porcentaje de ejecución por contrato"){
            if($dependencias_id == null){
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->latest()->get();
            } else {
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->where('dependencias_id', '=',$dependencias_id)->latest()->get();
            }
            //Si no existen registros con esos filtros se retorna false
            if(count($provider_contracts)==0){
                return $this->sendResponse( false, 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                //SI si existen registros con ese ffiltro se retorna true
                return $this->sendResponse( true, 'No se encuentran registros con esos filtros.', 'warning');
            }
        }
        //Verificar si se selecciono Porcentaje de ejecución de todos los contratos
        else if($indicatorType=="Porcentaje de ejecución de todos los contratos"){
            if($dependencias_id == null){
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->latest()->get();
            } else {
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->where('dependencias_id', '=',$dependencias_id)->latest()->get();
            }
            //Si no existen registros con esos filtros se retorna false
            if(count($provider_contracts)==0){
                return $this->sendResponse( false, 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                //SI si existen registros con ese ffiltro se retorna true
                return $this->sendResponse( true, 'No se encuentran registros con esos filtros.', 'warning');
            }
        }
        //Verificar si se selecciono Valor contratado por contrato
        else if($indicatorType=="Valor contratado por contrato"){
            if($dependencias_id == null){
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->latest()->get();
            } else {
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->where('dependencias_id', '=',$dependencias_id)->latest()->get();
            }
            //Si no existen registros con esos filtros se retorna false
            if(count($provider_contracts)==0){
                return $this->sendResponse( false, 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                //SI si existen registros con ese ffiltro se retorna true
                return $this->sendResponse( true, 'No se encuentran registros con esos filtros.', 'warning');
            }
        }
        //Verificar si se selecciono Valor ejecutado por contrato
        else if($indicatorType=="Valor ejecutado por contrato"){
            if($dependencias_id == null){
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->latest()->get();
            } else {
                $provider_contracts = ProviderContract::with(["providers", "budgetAllocationProviders", "mantContractNew","dependencias","mantBudgetAssignation"])->where('created_at','>=',$init_date)->where('created_at','<=',$final_date)->where('dependencias_id', '=',$dependencias_id)->latest()->get();
            }
            //Si no existen registros con esos filtros se retorna false
            if(count($provider_contracts)==0){
                return $this->sendResponse( false, 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                //SI si existen registros con ese ffiltro se retorna true
                return $this->sendResponse( true, 'No se encuentran registros con esos filtros.', 'warning');
            }
        }
        //Verificar si se selecciono Valor ejecutado por contrato
        else if($indicatorType=="Cantidad de activos por estado y dependencia"){
            if($dependencias_id == null){
                $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::with(["provider", "mantCategory","assetType", "dependencies", "mantDocumentsAsset"])->latest()->get();
                $activos = $resume_machinery_vehicles_yellows->toArray();

                $resume_equipment_machinery = ResumeEquipmentMachinery::with(["provider", "mantCategory", "dependencies", "characteristicsEquipment","assetType", "mantDocumentsAsset"])->latest()->get();
                $activos2 = $resume_equipment_machinery->toArray();

                foreach($activos2 as $activo) {
                    array_push($activos, $activo);
                }

                $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::with(["provider", "mantCategory", "dependencies", "compositionEquipmentLeca","assetType", "maintenanceEquipmentLeca", "mantDocumentsAsset"])->latest()->get();
                $activos3 = $resume_equipment_machinery_lecas->toArray();

                foreach($activos3 as $activo) {
                    array_push($activos, $activo);
                }

                $resume_equipment_lecas = ResumeEquipmentLeca::with(["provider", "mantCategory", "dependencies", "specificationsEquipmentLeca", "mantDocumentsAsset","assetType"])->latest()->get();
                $activos4 = $resume_equipment_lecas->toArray();

                foreach($activos4 as $activo) {
                    array_push($activos, $activo);
                }

                $resume_inventory_lecas = ResumeInventoryLeca::with(["provider", "mantCategory", "scheduleInventoryLeca", "mantDocumentsAsset","assetType"])->latest()->get();
                $activos5 = $resume_inventory_lecas->toArray();

                foreach($activos5 as $activo) {
                    array_push($activos, $activo);
                }
            } else {
                $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::with(["provider", "mantCategory","assetType", "dependencies", "mantDocumentsAsset", "TireReferencesFront", "TireReferencesRear"])->where('dependencias_id', $dependencias_id)->latest()->get();
                $activos = $resume_machinery_vehicles_yellows->toArray();

                $resume_equipment_machinery = ResumeEquipmentMachinery::with(["provider", "mantCategory", "dependencies", "characteristicsEquipment","assetType", "mantDocumentsAsset"])->where('dependencias_id', $dependencias_id)->latest()->get();
                $activos2 = $resume_equipment_machinery->toArray();

                foreach($activos2 as $activo) {
                    array_push($activos, $activo);
                }

                $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::with(["provider", "mantCategory", "dependencies", "compositionEquipmentLeca","assetType", "maintenanceEquipmentLeca", "mantDocumentsAsset"])->where('dependencias_id', $dependencias_id)->latest()->get();
                $activos3 = $resume_equipment_machinery_lecas->toArray();

                foreach($activos3 as $activo) {
                    array_push($activos, $activo);
                }

                $resume_equipment_lecas = ResumeEquipmentLeca::with(["provider", "mantCategory", "dependencies", "specificationsEquipmentLeca", "mantDocumentsAsset","assetType"])->where('dependencias_id', $dependencias_id)->latest()->get();
                $activos4 = $resume_equipment_lecas->toArray();

                foreach($activos4 as $activo) {
                    array_push($activos, $activo);
                }

                $resume_inventory_lecas = ResumeInventoryLeca::with(["provider", "mantCategory", "scheduleInventoryLeca", "mantDocumentsAsset","assetType"])->latest()->get();
                $activos5 = $resume_inventory_lecas->toArray();

                foreach($activos5 as $activo) {
                    array_push($activos, $activo);
                }
            }
            //Si no existen registros con esos filtros se retorna false
            if(count($activos)==0){
                return $this->sendResponse( false, 'No se encuentran registros con esos filtros.', 'warning');
            }else{
                //SI si existen registros con ese ffiltro se retorna true
                return $this->sendResponse( true, 'No se encuentran registros con esos filtros.', 'warning');
            }
        }
    }

    /**
     * Envia los nombres de los activos dependiendo de la dependencia y del tipo de activo
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 23 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getNameActive($id){
        $array=explode(",", $id);

        $idType=$array[0];
        $idDependencia=$array[1];
        
        if(is_numeric($idType)  && is_numeric($idDependencia) ){

                
            $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::where('mant_asset_type_id', $idType)->where('dependencias_id',$idDependencia)->latest()->get()->map(function($item, $key){
            
            
                // Se valida que el registro tenga algunas de las siguientes relaciones
                if ($item->plaque) {
                    $item["complement"] = $item->plaque;
                }
                else {
                    $item["complement"] = $item->no_inventory;
                }
                // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                // con su respectivo registro y se listara en la vista
                return $item;
            });

            $activos = $resume_machinery_vehicles_yellows->toArray();


            $resume_equipment_machinery = ResumeEquipmentMachinery::where('mant_asset_type_id', $idType)->where('dependencias_id',$idDependencia)->latest()->get()->map(function($item, $key){
            
                
                // Se valida que el registro tenga algunas de las siguientes relaciones
                if ($item->plaque) {
                    $item["complement"] = $item->plaque;
                }
                else {
                    $item["complement"] = $item->no_inventory;
                }
                // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                // con su respectivo registro y se listara en la vista
                return $item;

                
            });

            $activos2 = $resume_equipment_machinery->toArray();

            

            foreach($activos2 as $activo) {
                array_push($activos, $activo);
            }


            $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::where('mant_asset_type_id', $idType)->where('dependencias_id',$idDependencia)->latest()->get()->map(function($item, $key){
            
                // Se valida que el registro tenga algunas de las siguientes relaciones
                if ($item->plaque) {
                    $item["complement"] = $item->plaque;
                }
                else {
                    $item["complement"] = $item->no_inventory;
                }
                // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                // con su respectivo registro y se listara en la vista
                return $item;
            });

            $activos3 = $resume_equipment_machinery_lecas->toArray();

            foreach($activos3 as $activo) {
                array_push($activos, $activo);
            }


            $resume_equipment_lecas = ResumeEquipmentLeca::where('mant_asset_type_id', $idType)->where('dependencias_id',$idDependencia)->latest()->get()->map(function($item, $key){
            
                // Se valida que el registro tenga algunas de las siguientes relaciones
                if ($item->plaque) {
                    $item["complement"] = $item->plaque;
                }
                else {
                    $item["complement"] = $item->no_inventory;
                }
                // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                // con su respectivo registro y se listara en la vista
                return $item;
            });

            $activos4 = $resume_equipment_lecas->toArray();

            foreach($activos4 as $activo) {
                array_push($activos, $activo);
            }


            $resume_inventory_lecas = ResumeInventoryLeca::where('mant_asset_type_id', $idType)->where('dependencias_id',$idDependencia)->latest()->get()->map(function($item, $key){
            
                // // Se valida que el registro tenga algunas de las siguientes relaciones
                if ($item->plaque) {
                    $item["complement"] = $item->plaque;
                }
                else {
                    $item["complement"] = $item->no_inventory;
                }
                // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                // con su respectivo registro y se listara en la vista
                return $item;
            });

            $activos5 = $resume_inventory_lecas->toArray();

            foreach($activos5 as $activo) {
                array_push($activos, $activo);
            }
            
            
            return $this->sendResponse($activos, trans('data_obtained_successfully'));

        }else{

            if( is_numeric($idType)  && is_numeric($idDependencia)==false ){
                                    
                $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::where('mant_asset_type_id', $idType)->latest()->get()->map(function($item, $key){
            
            
                    // Se valida que el registro tenga algunas de las siguientes relaciones
                    if ($item->plaque) {
                        $item["complement"] = $item->plaque;
                    }
                    else {
                        $item["complement"] = $item->no_inventory;
                    }
                    // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                    // con su respectivo registro y se listara en la vista
                    return $item;
                });
                $activos = $resume_machinery_vehicles_yellows->toArray();


                $resume_equipment_machinery = ResumeEquipmentMachinery::where('mant_asset_type_id', $idType)->latest()->get()->map(function($item, $key){
            
            
                    // Se valida que el registro tenga algunas de las siguientes relaciones
                    if ($item->plaque) {
                        $item["complement"] = $item->plaque;
                    }
                    else {
                        $item["complement"] = $item->no_inventory;
                    }
                    // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                    // con su respectivo registro y se listara en la vista
                    return $item;
                });
                $activos2 = $resume_equipment_machinery->toArray();

                foreach($activos2 as $activo) {
                    array_push($activos, $activo);
                }


                $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::where('mant_asset_type_id', $idType)->latest()->get()->map(function($item, $key){
            
            
                    // Se valida que el registro tenga algunas de las siguientes relaciones
                    if ($item->plaque) {
                        $item["complement"] = $item->plaque;
                    }
                    else {
                        $item["complement"] = $item->no_inventory;
                    }
                    // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                    // con su respectivo registro y se listara en la vista
                    return $item;
                });
                $activos3 = $resume_equipment_machinery_lecas->toArray();

                foreach($activos3 as $activo) {
                    array_push($activos, $activo);
                }


                $resume_equipment_lecas = ResumeEquipmentLeca::where('mant_asset_type_id', $idType)->latest()->get()->map(function($item, $key){
            
            
                    // Se valida que el registro tenga algunas de las siguientes relaciones
                    if ($item->plaque) {
                        $item["complement"] = $item->plaque;
                    }
                    else {
                        $item["complement"] = $item->no_inventory;
                    }
                    // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                    // con su respectivo registro y se listara en la vista
                    return $item;
                });

                $activos4 = $resume_equipment_lecas->toArray();

                foreach($activos4 as $activo) {
                    array_push($activos, $activo);
                }


                $resume_inventory_lecas = ResumeInventoryLeca::where('mant_asset_type_id', $idType)->latest()->get()->map(function($item, $key){
            
            
                    // Se valida que el registro tenga algunas de las siguientes relaciones
                    if ($item->plaque) {
                        $item["complement"] = $item->plaque;
                    }
                    else {
                        $item["complement"] = $item->no_inventory;
                    }
                    // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                    // con su respectivo registro y se listara en la vista
                    return $item;
                });

                $activos5 = $resume_inventory_lecas->toArray();

                foreach($activos5 as $activo) {
                    array_push($activos, $activo);
                }
                
                return $this->sendResponse($activos, trans('data_obtained_successfully'));
            }

            if(is_numeric($idType)==false  && is_numeric($idDependencia)){
                
            $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::where('dependencias_id',$idDependencia)->latest()->get()->map(function($item, $key){
            
            
                    // Se valida que el registro tenga algunas de las siguientes relaciones
                    if ($item->plaque) {
                        $item["complement"] = $item->plaque;
                    }
                    else {
                        $item["complement"] = $item->no_inventory;
                    }
                    // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                    // con su respectivo registro y se listara en la vista
                    return $item;
                });

            $activos = $resume_machinery_vehicles_yellows->toArray();


            $resume_equipment_machinery = ResumeEquipmentMachinery::where('dependencias_id',$idDependencia)->latest()->get()->map(function($item, $key){
            
            
                    // Se valida que el registro tenga algunas de las siguientes relaciones
                    if ($item->plaque) {
                        $item["complement"] = $item->plaque;
                    }
                    else {
                        $item["complement"] = $item->no_inventory;
                    }
                    // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                    // con su respectivo registro y se listara en la vista
                    return $item;
                });

            $activos2 = $resume_equipment_machinery->toArray();

            foreach($activos2 as $activo) {
                array_push($activos, $activo);
            }


            $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::where('dependencias_id',$idDependencia)->latest()->get()->map(function($item, $key){
            
            
                    // Se valida que el registro tenga algunas de las siguientes relaciones
                    if ($item->plaque) {
                        $item["complement"] = $item->plaque;
                    }
                    else {
                        $item["complement"] = $item->no_inventory;
                    }
                    // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                    // con su respectivo registro y se listara en la vista
                    return $item;
                });

            $activos3 = $resume_equipment_machinery_lecas->toArray();

            foreach($activos3 as $activo) {
                array_push($activos, $activo);
            }


            $resume_equipment_lecas = ResumeEquipmentLeca::where('dependencias_id',$idDependencia)->latest()->get()->map(function($item, $key){
            
            
                    // Se valida que el registro tenga algunas de las siguientes relaciones
                    if ($item->plaque) {
                        $item["complement"] = $item->plaque;
                    }
                    else {
                        $item["complement"] = $item->no_inventory;
                    }
                    // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                    // con su respectivo registro y se listara en la vista
                    return $item;
                });

            $activos4 = $resume_equipment_lecas->toArray();

            foreach($activos4 as $activo) {
                array_push($activos, $activo);
            }


            $resume_inventory_lecas = ResumeInventoryLeca::where('dependencias_id',$idDependencia)->latest()->get()->map(function($item, $key){
            
            
                    // Se valida que el registro tenga algunas de las siguientes relaciones
                    if ($item->plaque) {
                        $item["complement"] = $item->plaque;
                    }
                    else {
                        $item["complement"] = $item->no_inventory;
                    }
                    // Se retorna el arreglo item,el cual contendra una variable de estado publicacion la cual se retornara
                    // con su respectivo registro y se listara en la vista
                    return $item;
                });
                
            $activos5 = $resume_inventory_lecas->toArray();

            foreach($activos5 as $activo) {
                array_push($activos, $activo);
            }
            
            return $this->sendResponse($activos, trans('data_obtained_successfully'));
            }
        }
    }

    /**
     * Obtiene todas las dependencias 
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 27 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getDependencyIndicator(){
        //Obtiene todas las dependencias
        $dependency = Dependency::all()->toArray();

        //Se crea un objeto y se le agregan los atributos
        $object = new stdClass();
        $object->id=null;
        $object->nombre="Todos";
        //Se agrega a la parte inicial de la lista
        array_unshift($dependency,  $object);
        
        
        return $this->sendResponse($dependency, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 27 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getAssetIndicator(Request $request) {
        //Obtiene los tipos de activos
        $asset_types = AssetType::all()->toArray();
        //Se crea un objeto y se le agregan los atributos
        $object = new stdClass();
        $object->id=null;
        $object->name="Todos";
        //Se agrega a la parte inicial de la lista
        array_unshift($asset_types,  $object);

        // $asset_types = AssetType::with(['dependency'])->latest()->get();
        return $this->sendResponse($asset_types, trans('data_obtained_successfully'));
    }
    
}
