<?php
namespace Modules\Maintenance\Http\Controllers;
use App\Exports\GenericExport;
    use Modules\Maintenance\Http\Requests\CreateIndicatorsRequest;
    use App\Exports\Maintenance\IndicatorExport\FuelVehiclesIndicatorExport;
    use Modules\Maintenance\Http\Requests\UpdateIndicatorsRequest;
    use App\Http\Controllers\AppBaseController;
    use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;
    use Modules\Maintenance\Models\Category;
    use Modules\Maintenance\Models\VehicleFuel;   
    use Illuminate\Http\Request;
    use Flash;
    use Maatwebsite\Excel\Facades\Excel;
    use \stdClass;
    use Response;
    use Auth;
    use Modules\Maintenance\Models\TireReferences;
    use Modules\Maintenance\Models\ResumeEquipmentMachinery;
    use Modules\Maintenance\Models\ResumeEquipmentMachineryLeca;
    use Modules\Maintenance\Models\ResumeEquipmentLeca;
    use Modules\Maintenance\Models\ResumeInventoryLeca;
    use Modules\Maintenance\Models\AssetType;
    use Modules\Maintenance\Models\TireQuantitites;
    use Modules\Maintenance\Models\TireInformations;
    use Modules\Maintenance\Models\Providers;
    use Modules\Maintenance\Models\ProviderContract;
    use Illuminate\Support\Carbon;
use DB;

/**
 * Esta clase es de los indicadores
 *
 * @author leonardo Herrera. - enero 2023
 * @version 1.0.0
*/
class DataAnalyticsController extends AppBaseController {

    /**
     * Muestra la vista para el CRUD de Indicators.
     *
     * @author leonardo Herrera. - enero 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
    */
    public function index(Request $request) {        
        return view('maintenance::dataAnalytics.index');           
    }

     /**
     *consulta los activos pertenecientes a una dependencia 
     *
     * @author leonardo Herrera. - enero 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
    */
    public function  getAsset($parameters, Request $request) { 

        $array=explode(",", $parameters);
        $dependencias_id=$array[0];
        $state=$array[1];
        $activos= [];
        

        
            if (str_contains("Todos", $request['query'])  || str_contains("todos", $request['query'])  ){
               
                $activos []=array("nombre_activo" => "Todos");
             
                 return $this->sendResponse($activos, trans('msg_success_update'));
            }
            else {
                if ($state != 'Todas') {
                    $activos=  DB::select("SELECT DISTINCT  name_vehicle_machinery as nombre_activo 
                                                FROM mant_resume_machinery_vehicles_yellow 
                                                WHERE status = '$state' 
                                                AND name_vehicle_machinery LIKE '%$request[query]%' 
                                                AND dependencias_id = $dependencias_id 
                                                AND deleted_at IS NULL UNION ALL
                            SELECT DISTINCT name_equipment as nombre_activo 
                                                FROM mant_resume_equipment_machinery 
                                                WHERE status = '$state' 
                                                AND name_equipment LIKE '%$request[query]%' 
                                                AND dependencias_id = $dependencias_id 
                                                AND deleted_at IS NULL UNION ALL
                            SELECT DISTINCT name_equipment_machinery as nombre_activo 
                                                FROM mant_resume_equipment_machinery_leca 
                                                WHERE status = '$state' 
                                                AND name_equipment_machinery LIKE '%$request[query]%' 
                                                AND  dependencias_id = $dependencias_id 
                                                AND deleted_at IS NULL UNION ALL
                            SELECT DISTINCT name_equipment as nombre_activo 
                                                FROM mant_resume_equipment_leca 
                                                WHERE status = '$state' 
                                                AND name_equipment LIKE '%$request[query]%' 
                                                AND dependencias_id = $dependencias_id 
                                                AND deleted_at IS NULL");
                } 
                else {
                    $activos =  DB::select("SELECT DISTINCT  name_vehicle_machinery as nombre_activo 
                                                FROM mant_resume_machinery_vehicles_yellow 
                                                WHERE  name_vehicle_machinery LIKE '%$request[query]%' 
                                                AND dependencias_id = $dependencias_id 
                                                AND deleted_at IS NULL UNION ALL
                            SELECT DISTINCT name_equipment as nombre_activo 
                                                FROM mant_resume_equipment_machinery 
                                                WHERE  name_equipment LIKE '%$request[query]%' 
                                                AND dependencias_id = $dependencias_id 
                                                AND deleted_at IS NULL UNION ALL
                            SELECT DISTINCT name_equipment_machinery as nombre_activo 
                                                FROM mant_resume_equipment_machinery_leca 
                                                WHERE  name_equipment_machinery LIKE '%$request[query]%' 
                                                AND  dependencias_id = $dependencias_id 
                                                AND deleted_at IS NULL UNION ALL
                            SELECT DISTINCT name_equipment as nombre_activo 
                                                FROM mant_resume_equipment_leca 
                                                WHERE  name_equipment LIKE '%$request[query]%' 
                                                AND dependencias_id = $dependencias_id 
                                                AND deleted_at IS NULL");
                }
                
            }
     
            return $this->sendResponse($activos, trans('msg_success_update'));
    }

    /**
     * Consulta las categorias de los vehiculos.
     *
     * @author leonardo Herrera. - enero 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
    */
    public function  getVehicleFleet() {
        $mant_category = Category:: where('mant_asset_type_id',11)->orWhere('mant_asset_type_id',8)->get();
        return $this->sendResponse($mant_category, trans('msg_success_update'));
    }

    /**
     * Obtiene todos los nombres de los activos segun su tipo de combustible.
     *
     * @author Kleverman Salazar Florez. - Feb. 09 - 2023
     * @version 1.0.0
     *
     * @param string $fuelType
     *
     * @return Response
    */
    public function getAssetsNameByFuelType2(Request $request,string $id,int $idAssetProcess): array{


        $array = explode(',', $id);
        $fuelType =  $array[0];
        $typeAverage = $array[1];
        $column = " ";

        if($typeAverage == "rendimiento en Km-gln"  || $typeAverage == "rendimiento en hr-gln") {
            if($typeAverage == "rendimiento en Km-gln") { // se selecciona la columna en base de dato segun el promedio requerido
                $column = "variation_route_hour"; 
            } 
           if ($typeAverage == "rendimiento en hr-gln")  {
                $column = "variation_tanking_hour";
            }
           
                $assetsName = DB:: table ('mant_vehicle_fuels as combustible')-> select(["name_vehicle_machinery"])->join('mant_resume_machinery_vehicles_yellow as maquinaria','combustible.mant_resume_machinery_vehicles_yellow_id', '=', 'maquinaria.id' )
                ->distinct("maquinaria.name_vehicle_machinery")
                ->where("maquinaria.name_vehicle_machinery", 'like', '%'.$request["query"].'%')
                ->where("maquinaria.fuel_type",$fuelType)
                ->where("maquinaria.dependencias_id",$idAssetProcess)
                ->where("maquinaria.body_type","!=",null)
                ->whereNotNull('combustible.'.$column)->get()->toArray();
                

        }

        if($typeAverage == "Variación en Km recorridos por tanqueo"  || $typeAverage == "Variación de horas en los tanqueos") {
            if($typeAverage == "Variación en Km recorridos por tanqueo") { // se selecciona la columna en base de dato segun el promedio requerido
                $column = "variation_route_hour"; 
            } 
           if ($typeAverage == "Variación de horas en los tanqueos")  {
                $column = "variation_tanking_hour";
            }
           
                $assetsName = DB:: table ('mant_vehicle_fuels as combustible')-> select(["name_vehicle_machinery"])->join('mant_resume_machinery_vehicles_yellow as maquinaria','combustible.mant_resume_machinery_vehicles_yellow_id', '=', 'maquinaria.id' )
                ->distinct("maquinaria.body_type")
                ->where("maquinaria.body_type",'like','%'.$request["query"].'%')
                ->where("maquinaria.fuel_type",$fuelType)
                ->where("maquinaria.dependencias_id",$idAssetProcess)
                ->where("maquinaria.body_type","!=",null)
                ->where("maquinaria.body_type","!=",null)
                ->where('combustible.'.$column,'<>',null)
                ->get()->toArray();
        }
       
        return $this->sendResponse($assetsName, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los nombres de los activos segun su tipo de combustible.
     *
     * @author Kleverman Salazar Florez. - Abr. 04 - 2023
     * @version 1.0.0
     *
     * @param string $nameVehicleMachinery nombre del activo
     *
     * @return Response
    */
    public function getBodyTypeByNameVehicleMachinery(string $nameVehicleMachinery){
        $bodyTypes = ResumeMachineryVehiclesYellow::select(["body_type"])->distinct("body_type")->where("name_vehicle_machinery",$nameVehicleMachinery)->get()->toArray();

        // Si la cantidad de tipos de carrocerias es mayor a 1 se añade la opcion del campo de todos
        if(count($bodyTypes) > 1){
            $optionOfAllBodyTypes= ["body_type" => "Todos"];
            array_push($bodyTypes, $optionOfAllBodyTypes);
        }
        return $this->sendResponse($bodyTypes, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos las placas cuando el tipo de indicador sea rendimiento de combustible.
     *
     * @author Kleverman Salazar Florez. - Abr. 10 - 2023
     * @version 1.0.0
     *
     * @param int $assestProcessId id del proceso del activo
     * @param string $fuelType tipo de combustible
     * @param string $assetsName nombre del activo
     * @param string $bodyType tipo de carroceria
     *
     * @return Response
    */
    public function getPlaquesForFuelEfficiency(  $assestProcessId,string $fuelType,string $assetsName, string $bodyType){
        if($bodyType === "Todos"){
            $plaques = ResumeMachineryVehiclesYellow::select(["id","plaque"])->where("name_vehicle_machinery",$assetsName)->where("fuel_type",$fuelType)->where("dependencias_id",$assestProcessId)->where('deleted_at',NULL)->where("status",'Activo')->get()->toArray();

            if(count($plaques) > 1){
                $optionOfAllPlaques= array(
                    "plaque" => "Todos",
                    "id" => "todos"
                );
                array_push($plaques,$optionOfAllPlaques);
            }
            return $this->sendResponse($plaques, trans('data_obtained_successfully'));
        }
        $plaques = ResumeMachineryVehiclesYellow::select(["id","plaque"])->distinct("plaque")->where("name_vehicle_machinery",$assetsName)->where('deleted_at',NULL)->where("body_type",$bodyType)->where("fuel_type",$fuelType)->where("dependencias_id",$assestProcessId)->where("status",'Activo')->get()->toArray();

        if(count($plaques) > 1){
            $optionOfAllPlaques= array(
                "plaque" => "Todos",
                "id" => "todos"
            );
            array_push($plaques,$optionOfAllPlaques);
        }
        return $this->sendResponse($plaques, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los nombres de los activos segun su tipo de combustible.
     *
     * @author Kleverman Salazar Florez. - Feb. 09 - 2023
     * @version 1.0.0
     *
     * @param string $fuelType
     *
     * @return Response
    */
    public function getAssetsNameByFuelType(string $fuelType, Request $request): array{
        $array = explode(',', $fuelType);
        $fuelType =  $array[0];
        $idAssetProcess = $array[1];
        
                 $assetsName = DB:: table ('mant_vehicle_fuels as combustible')-> select(["name_vehicle_machinery"])->join('mant_resume_machinery_vehicles_yellow as maquinaria','combustible.mant_resume_machinery_vehicles_yellow_id', '=', 'maquinaria.id' )
                ->distinct("maquinaria.name_vehicle_machinery")
                ->where("maquinaria.name_vehicle_machinery",'like','%'.$request["query"].'%')
                ->where("maquinaria.fuel_type",$fuelType)
                ->where("maquinaria.dependencias_id",$idAssetProcess)
                ->where("maquinaria.body_type","!=",null)
                ->where("maquinaria.body_type","!=",null)
                ->get()->toArray();
       
        return $this->sendResponse($assetsName, trans('data_obtained_successfully'));

    }

    /**
     * Obtiene todos los nombres de los activos segun su tipo de dependencia y estado.
     *
     * @author Kleverman Salazar Florez. - Feb. 13 - 2023
     * @version 1.0.0
     *
     * @param int $dependenceId
     * @param string $status
     *
     * @return Response
    */
    public function getAssetsNameByDependenceAndStatus( $depencenceId,  $status,Request $request): array{
        if ($status != 'Todas') {
            $assetsName = ResumeMachineryVehiclesYellow::select(["body_type"])->distinct("body_type")->where("dependencias_id", $depencenceId)
            ->where("status", $status)->where("body_type", 'like','%'.$request["query"].'%')->get()->toArray();
        } else{
            $assetsName = ResumeMachineryVehiclesYellow::select(["body_type"])->distinct("body_type")->where("dependencias_id", $depencenceId)->where("body_type", "<>", null)->get()->toArray();
        }
        return $this->sendResponse($assetsName, trans('data_obtained_successfully'));
    }

    /**
     * Consulta las placas que se relacionan  a el vehiculo seleccionado.
     *
     * @author leonardo Herrera. - enero 2023
     * @version 1.0.0
     *
     * @param string $informationAsset
     *
     * @return Response
    */
    public function getPlateByAsset(string $informationAsset ) {

        $listInformationAsset = explode(",", $informationAsset);
        $assetsName = $listInformationAsset[0];
        $fuelType = $listInformationAsset[1];
        $listPlates = [];

        
            $plate = ResumeMachineryVehiclesYellow::where('body_type',$assetsName)->where('fuel_type',$fuelType)->get()->toArray();
       
        
        $optionOfAllPlates= array(
            "plaque" => "Todos",
            "id" => "todos"
        );

        array_push($listPlates, $optionOfAllPlates);
       
        $listPlates = array_merge($plate, $listPlates);

        return $this->sendResponse($listPlates, trans('msg_success_update'));
    }

    /**
     * Consulta todos lo proveesores de la entidaad
     *
     * @author leonardo Herrera. - enero 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
    */
    public function getProvider() {

      
        $provider =ProviderContract::join('mant_providers as p', 'mant_provider_contract.mant_providers_id', '=', 'p.id')
        ->whereNull('p.deleted_at')
        ->whereNull('mant_provider_contract.deleted_at')
        ->select('p.name', 'p.id')
        ->get()->toArray();
        
        $all= array(
            "name" => "Todos",
            "id" => "todos"
        );
        array_push($provider, $all);
        return $this->sendResponse($provider, trans('msg_success_update'));
    }

    /**
     * Consulta todos lo proveesores de la entidaad
     *
     * @author leonardo Herrera. - enero 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
    */
    public function getContract($provider) {

        $contract = ProviderContract::all()->toArray();
       
        if ($provider != 'todos') {
            $contract = ProviderContract::where('mant_providers_id',$provider)->where('deleted_at',NULL)->get()->toArray();
        } 
        $all= array(
            "contract_number" => "Todos",
            "id" => "todos"
        );
        array_push($contract, $all);
        return $this->sendResponse($contract, trans('msg_success_update'));
    }

    /**
     * Verifica que si exista un resultado a la consulta
     *
     * @author Nicolas Dario Ortiz Peña. - enero 2023
     * @version 1.0.0
     *
     * @return Response
    */
    public function verifyDataAnalytics(Request $request) { 
        $input = $request-> all();
        //array donde se guardaran todas las placas a graficar.
        $vehiclesResult = [];
        //Recibe todos los valores del request
        $indicatorType=$request['indicator_type'];
        $type_promedio=$request['type_promedio'];
        $init_date=$request['init_date'];
        $final_date=$request['final_date'];


        //Verifica si se selecciono Recorrido
        if($indicatorType == "Rendimiento de combustible"){
            $column = ($type_promedio == "rendimiento en Km-gln") ? "variation_route_hour" : "variation_tanking_hour";
          
            $quantityOptionAllPlates = 0;
            $quantityOptionPlates = 0;

            foreach ($input["list_indicator"] as $key => $idCategory) { 
                foreach ($idCategory as $key2 => $value2) {                             
                    foreach($value2 as $key3 => $idVehicle) { 
                        $vehicle = DB::table('mant_vehicle_fuels as tanqueo') -> select(
                            DB::raw('round(avg(tanqueo.performance_by_gallon),2) as average'),
                                DB::raw("vehiculos.plaque as placa"))
                                ->join ('mant_resume_machinery_vehicles_yellow as vehiculos','tanqueo.mant_resume_machinery_vehicles_yellow_id','=','vehiculos.id' )
                                ->join ('mant_category as categorias','categorias.id','=','vehiculos.mant_category_id' )
                                ->where($column,'<>',null)
                                ->where('tanqueo.created_at','>=',$init_date)->where('tanqueo.created_at','<=',$final_date)
                                ->where('tanqueo.deleted_at',NULL)
                                ->where('vehiculos.deleted_at',NULL)
                                ->where('vehiculos.status','Activo');
                            
                                //con el filtro en tipo de carroceria es todos
                                 if ( $input["body_type"] == 'Todos'){

                                    // si en el filtro se pone carroceria es todos y en placa todas 
                                    if ( $idVehicle["placa"] == 'todos'){
                                    
                                        $quantityOptionAllPlates++;
                                        $vehicleSave = $vehicle->where('vehiculos.name_vehicle_machinery',key($input['list_indicator']))
                                                               ->where('vehiculos.fuel_type',$input['type_combustible'])
                                                               ->groupBy('placa')->orderBy('placa')->get()->toArray();

                                        // Con esta linea nos aseguramos que el promedio siempre sea float     
                                        $vehicleSave = array_map(function($point) {
                                            $point->average = (float) $point->average;
                                            return $point;
                                        }, $vehicleSave);
                                        $vehiclesResult=$vehicleSave;
                                    }
                                    // si en el filtro se seleccionan carroceria es todos placas en espesifico
                                    else {
                                        $quantityOptionPlates++;
                                        if(($quantityOptionAllPlates >= 2) || ($quantityOptionAllPlates > 0 && $quantityOptionPlates > 0)){
                                            return $this->sendSuccess('El grafico no se pudo realizar, por favor validar la información diligenciada.', 'error');
                                        }
                                        $vehicleSave = $vehicle->where('tanqueo.mant_resume_machinery_vehicles_yellow_id', $idVehicle["placa"])->get()->toArray();
                                
                                        // Con esta linea nos aseguramos que el promedio siempre sea float     
                                        $vehicleSave = array_map(function($point) {
                                            $point->average = (float) $point->average;
                                            return $point;
                                        }, $vehicleSave);
                                        array_push($vehiclesResult, $vehicleSave[0]);
                                    }
                                }
                                //cuando se selecciona un tipo de carroceria en especifico
                                else {
                                   if ( $idVehicle["placa"] == 'todos'){
                                    
                                    $quantityOptionAllPlates++;

                                    if(($quantityOptionAllPlates >= 2) || ($quantityOptionAllPlates > 0 && $quantityOptionPlates > 0)){
                                        return $this->sendSuccess('El grafico no se pudo realizar, por favor validar la información diligenciada.', 'error');
                                    }
                                    $vehicleSave = $vehicle->where('vehiculos.name_vehicle_machinery',key($input['list_indicator']))
                                                            ->where('vehiculos.fuel_type',$input['type_combustible'])
                                                            ->where('vehiculos.body_type',$input['body_type'])
                                                            ->groupBy('placa')->orderBy('placa')->get()->toArray();
                                   
                                    // Con esta linea nos aseguramos que el promedio siempre sea float                         
                                    $vehicleSave = array_map(function($point) {
                                        $point->average = (float) $point->average;
                                        return $point;
                                    }, $vehicleSave);


                                    $vehiclesResult=$vehicleSave;
                                  
                                    } else {
                                        $quantityOptionPlates++;
                                        if(($quantityOptionAllPlates >= 2) || ($quantityOptionAllPlates > 0 && $quantityOptionPlates > 0)){
                                            return $this->sendSuccess('El grafico no se pudo realizar, por favor validar la información diligenciada.', 'error');
                                        }
                                        $vehicleSave = $vehicle->where('tanqueo.mant_resume_machinery_vehicles_yellow_id', $idVehicle["placa"])
                                                                ->where('vehiculos.body_type',$input['body_type'])->get()->toArray();
                                
                                        // Con esta linea nos aseguramos que el promedio siempre sea float     
                                        $vehicleSave[0]->average = (float) $vehicleSave[0]->average;
                                        array_push($vehiclesResult, $vehicleSave[0]);
                                    }
                                }
                    }
                }
            }  
            $flag=0;
            foreach ($vehiclesResult as $key => $average) {
                $flag += $average->average;
            }           
            // Si no existen registros con esos filtros se retorna false
            if($flag==0){
                return $this->sendSuccess('No se encuentran registros con esos filtros desde controlador.', 'error');
            }else{
                //SI si existen registros con ese filtro se retorna true
                return $this->sendResponse( $vehiclesResult, 'si se encontraron registros.', 'warning');
            }       

        }
        // Condicion si recibe informacion con el filtro de consumo de combustible.
        elseif ($indicatorType == "Consumo combustible") {                     
            $vehiclesResult = [];
            $quantityOptionAllPlates = 0;
            $quantityOptionPlates = 0;

            foreach ($input["list_indicator"] as $key => $idCategory) { 
                foreach ($idCategory as $key2 => $value2) {                             
                    foreach($value2 as $key3 => $idVehicle) { 
                            
                    $vehicle = DB::table('mant_vehicle_fuels as tanqueo') -> select(
                        DB::raw('ROUND(SUM(tanqueo.fuel_quantity),3) as average'),
                        DB::raw("DATE_FORMAT(tanqueo.created_at, '%Y') as new_date"),
                        DB::raw("vehiculos.plaque as placa"),
                        DB::raw("categorias.name as nombre_categoria"))
                        ->join ('mant_resume_machinery_vehicles_yellow as vehiculos','tanqueo.mant_resume_machinery_vehicles_yellow_id','=','vehiculos.id' )
                        ->join ('mant_category as categorias','categorias.id','=','vehiculos.mant_category_id' )
                        ->where('tanqueo.created_at','>=',$init_date)->where('tanqueo.created_at','<=',$final_date)
                        ->where('tanqueo.deleted_at',NULL)
                        ->where('vehiculos.status','Activo')
                        ->where('vehiculos.deleted_at',NULL);

                        //con el filtro en tipo de carroceria es todos
                        if ( $input["body_type"] == 'Todos'){

                            // si en el filtro se pone carroceria es todos y en placa todas 
                            if ( $idVehicle["placa"] == 'todos'){
                            
                                $quantityOptionAllPlates++;

                                
                                $vehicleSave = $vehicle->where('vehiculos.name_vehicle_machinery',key($input['list_indicator']))
                                                       ->where('tanqueo.deleted_at',NULL)
                                                       ->where('vehiculos.fuel_type',$input['type_combustible'])
                                                       ->groupBy('placa')->orderBy('placa')->get()->toArray();
                                // Con esta linea nos aseguramos que el promedio siempre sea float     
                                $vehicleSave = array_map(function($point) {
                                    $point->average = (float) $point->average;
                                    return $point;
                                }, $vehicleSave);
                                $vehiclesResult=$vehicleSave;
                               
                            }
                            // si en el filtro se seleccionan carroceria es todos placas en espesifico
                            else {
                                $quantityOptionPlates++;
                                if(($quantityOptionAllPlates >= 2) || ($quantityOptionAllPlates > 0 && $quantityOptionPlates > 0)){
                                    return $this->sendSuccess('El grafico no se pudo realizar, por favor validar la información diligenciada.', 'error');
                                }
                                $vehicleSave = $vehicle -> where('tanqueo.mant_resume_machinery_vehicles_yellow_id', $idVehicle["placa"])->where('tanqueo.deleted_at',NULL)->get()->toArray();
                                // Con esta linea nos aseguramos que el promedio siempre sea float     
                                $vehicleSave[0]->average = (float) $vehicleSave[0]->average;
                                array_push($vehiclesResult, $vehicleSave[0]);
                            }
                        }
                        //cuando se selecciona un tipo de carroceria en especifico
                        else {
                           if ( $idVehicle["placa"] == 'todos'){
                            
                            $quantityOptionAllPlates++;

                            if(($quantityOptionAllPlates >= 2) || ($quantityOptionAllPlates > 0 && $quantityOptionPlates > 0)){
                                return $this->sendSuccess('El grafico no se pudo realizar, por favor validar la información diligenciada.', 'error');
                            }
                            $vehicleSave = $vehicle->where('vehiculos.name_vehicle_machinery',key($input['list_indicator']))
                                                    ->where('vehiculos.fuel_type',$input['type_combustible'])
                                                    ->where('vehiculos.body_type',$input['body_type'])
                                                    ->groupBy('placa')->orderBy('placa')->get()->toArray();
                                // Con esta linea nos aseguramos que el promedio siempre sea float     
                                $vehicleSave = array_map(function($point) {
                                    $point->average = (float) $point->average;
                                    return $point;
                                }, $vehicleSave);
                                $vehiclesResult=$vehicleSave;
                          
                            } else {
                                $quantityOptionPlates++;
                                if(($quantityOptionAllPlates >= 2) || ($quantityOptionAllPlates > 0 && $quantityOptionPlates > 0)){
                                    return $this->sendSuccess('El grafico no se pudo realizar, por favor validar la información diligenciada.', 'error');
                                }
                                $vehicleSave = $vehicle->where('tanqueo.mant_resume_machinery_vehicles_yellow_id', $idVehicle["placa"])
                                                        ->where('vehiculos.body_type',$input['body_type'])->get()->toArray();
                        
                                 // Con esta linea nos aseguramos que el promedio siempre sea float     
                                 $vehicleSave[0]->average = (float) $vehicleSave[0]->average;
                                array_push($vehiclesResult, $vehicleSave[0]);
                            }
                        }

                    }
                }
            }
    
            // se realiza la verificacion de que por lo menos una placa tenga un promedio.
            $flag=0;
            foreach ($vehiclesResult as $key => $average) {
                $flag += $average->average;
            }                  
            // Si no existen registros con esos filtros se retorna false
            if($flag==0){
                return $this->sendSuccess('No se encuentran registros con esos filtros desde controlador.', 'error');
            }else{
                //SI si existen registros con ese filtro se retorna true
                return $this->sendResponse( $vehiclesResult, 'si se encontraron registros.', 'warning');
            }       
        }
        // Condicion si recibe informacion con el Recorrido por tanqueo.
        elseif ($indicatorType == "Recorrido por tanqueo") {
          
            $column = ($type_promedio == "Variación en Km recorridos por tanqueo") ? "variation_route_hour" : "variation_tanking_hour";
            $vehiclesResult = [];
            $quantityOptionAllPlates = 0;
            $quantityOptionPlates = 0;

                foreach ($input["list_indicator"] as $key => $idCategory) { 
                    foreach ($idCategory as $key2 => $value2) {                             
                        foreach($value2 as $key3 => $idVehicle) { 
                            
                            $vehicle = DB::table('mant_vehicle_fuels as tanqueo') -> select(
                                DB::raw('ROUND(SUM(tanqueo.'.$column.'),2) as average'),
                                DB::raw("DATE_FORMAT(tanqueo.created_at, '%Y') as new_date"),
                                DB::raw("vehiculos.plaque as placa"),
                                DB::raw("categorias.name as nombre_categoria"))
                                ->join ('mant_resume_machinery_vehicles_yellow as vehiculos','tanqueo.mant_resume_machinery_vehicles_yellow_id','=','vehiculos.id' )
                                ->join ('mant_category as categorias','categorias.id','=','vehiculos.mant_category_id' )
                                ->where($column,'<>',null)
                                ->where('tanqueo.created_at','>=',$init_date)->where('tanqueo.created_at','<=',$final_date)
                                ->where('tanqueo.deleted_at',NULL)
                                ->where('vehiculos.status','Activo')
                                ->where('vehiculos.deleted_at',NULL);
                            
                                //con el filtro en tipo de carroceria es todos
                                 if ( $input["body_type"] == 'Todos'){

                                    // si en el filtro se pone carroceria es todos y en placa todas 
                                    if ( $idVehicle["placa"] == 'todos'){
                                    
                                        $quantityOptionAllPlates++;
                                        $vehicleSave = $vehicle->where('vehiculos.name_vehicle_machinery',key($input['list_indicator']))
                                                               ->where('vehiculos.fuel_type',$input['type_combustible'])
                                                               ->groupBy('placa')->orderBy('placa')->get()->toArray();
                                         // Con esta linea nos aseguramos que el promedio siempre sea float     
                                        $vehicleSave = array_map(function($point) {
                                            $point->average = (float) $point->average;
                                            return $point;
                                        }, $vehicleSave);                      
                                        $vehiclesResult=$vehicleSave;
                                       
                                    }
                                    // si en el filtro se seleccionan carroceria es todos placas en espesifico
                                    else {
                                        $quantityOptionPlates++;
                                        if(($quantityOptionAllPlates >= 2) || ($quantityOptionAllPlates > 0 && $quantityOptionPlates > 0)){
                                            return $this->sendSuccess('El grafico no se pudo realizar, por favor validar la información diligenciada.', 'error');
                                        }
                                        $vehicleSave = $vehicle->where('tanqueo.mant_resume_machinery_vehicles_yellow_id', $idVehicle["placa"])->get()->toArray();
                                        // Con esta linea nos aseguramos que el promedio siempre sea float     
                                        $vehicleSave[0]->average = (float) $vehicleSave[0]->average;
                                        array_push($vehiclesResult, $vehicleSave[0]);
                                    }
                                }
                                //cuando se selecciona un tipo de carroceria en especifico
                                else {
                                   if ( $idVehicle["placa"] == 'todos'){
                                    
                                    $quantityOptionAllPlates++;

                                    if(($quantityOptionAllPlates >= 2) || ($quantityOptionAllPlates > 0 && $quantityOptionPlates > 0)){
                                        return $this->sendSuccess('El grafico no se pudo realizar, por favor validar la información diligenciada.', 'error');
                                    }
                                    $vehicleSave = $vehicle->where('vehiculos.name_vehicle_machinery',key($input['list_indicator']))
                                                            ->where('vehiculos.fuel_type',$input['type_combustible'])
                                                            ->where('vehiculos.body_type',$input['body_type'])
                                                            ->groupBy('placa')->orderBy('placa')->get()->toArray();
                                    // Con esta linea nos aseguramos que el promedio siempre sea float     
                                    $vehicleSave = array_map(function($point) {
                                        $point->average = (float) $point->average;
                                        return $point;
                                    }, $vehicleSave);                          
                                    $vehiclesResult=$vehicleSave;
                                  
                                    } else {
                                        $quantityOptionPlates++;
                                        if(($quantityOptionAllPlates >= 2) || ($quantityOptionAllPlates > 0 && $quantityOptionPlates > 0)){
                                            return $this->sendSuccess('El grafico no se pudo realizar, por favor validar la información diligenciada.', 'error');
                                        }
                                        $vehicleSave = $vehicle->where('tanqueo.mant_resume_machinery_vehicles_yellow_id', $idVehicle["placa"])
                                                                ->where('vehiculos.body_type',$input['body_type'])->get()->toArray();
                                        // Con esta linea nos aseguramos que el promedio siempre sea float     
                                        $vehicleSave[0]->average = (float) $vehicleSave[0]->average;
                                        array_push($vehiclesResult, $vehicleSave[0]);
                                    }
                                }
                        }
                    }
                }
                 // se realiza la verificacion de que por lo menos una placa tenga un promedio.
                
                $flag=0;
                    foreach ($vehiclesResult as $key => $average) {
                        $flag += $average->average;
                    }
                    // Si no existen registros con esos filtros se retorna false
                    if($flag==0){
                        return $this->sendSuccess('No se encuentran registros con esos filtros desde controlador.', 'error');
                    }else{
                        //SI si existen registros con ese filtro se retorna true
                        return $this->sendResponse( $vehiclesResult, 'si se encontraron registros.', 'warning');
                    }       
        }
        // Condicion si recibe informacion con e filtro de activos.
        elseif ($indicatorType == "Activos") {
            $activos = [];
            $activosResult= [];
            foreach ($input["list_indicator"] as $key => $tipo_activo) { 
                foreach ($tipo_activo as $key2 => $value2) {                             
                    foreach($value2 as $key3 => $nombre_activo) { 

                        $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::select(
                        DB::raw('name_vehicle_machinery as placa'),
                        DB::raw('CAST(COUNT(name_vehicle_machinery) AS DECIMAL(10,12)) as average'),)
                        
                        ->where('created_at','>=',$init_date)->where('created_at','<=',$final_date);
                        if ($input['status'] !='Todas') {
                            $resume_machinery_vehicles_yellows->where('status',$input['status'])->get()->toArray();
                        }
                        if ($nombre_activo['asset'] == 'Todos') {
                            $activos1 = $resume_machinery_vehicles_yellows->where('dependencias_id',$input['asset_process_id'])
                            ->groupBy('placa')->orderBy('placa')->get()->toArray();
                        
                            } else {
                                $activos1 =  $resume_machinery_vehicles_yellows ->where('name_vehicle_machinery',$nombre_activo['asset'])->where('dependencias_id',$input['asset_process_id'])->latest()->get()->toArray();
                            }
                        foreach($activos1 as $activo) {
                            if ($activo['placa'] != null) {
                                $activo['average'] = (float) $activo['average'];
                                array_push($activos, $activo);
                            }
                            continue;
                        }
                                
                        $resume_equipment_machinery = ResumeEquipmentMachinery::select(
                            DB::raw('name_equipment as placa'),
                            DB::raw('CAST(COUNT(name_equipment) AS DECIMAL(10,12)) as average'),)->where('created_at','>',$init_date)->where('created_at','<',$final_date);
                            if ($input['status'] !='Todas') {
                             $resume_equipment_machinery->where('status',$input['status'])->get()->toArray();
                            }
                            if ($nombre_activo['asset']== 'Todos') {
                                $activos2 = $resume_equipment_machinery->where('dependencias_id',$input['asset_process_id'])
                                ->groupBy('placa')->orderBy('placa')->get()->toArray();
                          
                                } else {
                                    $activos2 =  $resume_equipment_machinery ->where('name_equipment',$nombre_activo['asset'])->where('dependencias_id',$input['asset_process_id'])->latest()->get()->toArray();
                                }
            
                        foreach($activos2 as $activo) {
                            if ($activo['placa'] != null) {
                                $activo['average'] = (float) $activo['average'];

                                array_push($activos, $activo);
                            }
                            continue;
                        }
                        
                        $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::select(
                            DB::raw('name_equipment_machinery as placa'),
                            DB::raw('CAST(COUNT(name_equipment_machinery) AS DECIMAL(10,12)) as average'),)->where('created_at','>',$init_date)->where('created_at','<',$final_date);
                            if ($input['status'] !='Todas') {
                             $resume_equipment_machinery_lecas->where('status',$input['status'])->get()->toArray();
                            }
                            if ($nombre_activo['asset']== 'Todos') {
                                $activos3 = $resume_equipment_machinery_lecas->where('dependencias_id',$input['asset_process_id'])
                                ->groupBy('placa')->orderBy('placa')->get()->toArray();
                                } else {
                                    $activos3 =  $resume_equipment_machinery_lecas ->where('name_equipment_machinery',$nombre_activo['asset'])->where('dependencias_id',$input['asset_process_id'])->latest()->get()->toArray();
                                }
            
                        foreach($activos3 as $activo) {
                            if ($activo['placa'] != null) {
                                $activo['average'] = (float) $activo['average'];
                                array_push($activos, $activo);
                            }
                            continue;
                        }
                        
                        $resume_equipment_lecas = ResumeEquipmentLeca::select(
                            DB::raw('name_equipment as placa'),
                            DB::raw('CAST(COUNT(name_equipment) AS DECIMAL(10,12)) as average'),)->where('created_at','>',$init_date)->where('created_at','<',$final_date);
                            if ($input['status'] !='Todas') {
                             $resume_equipment_lecas->where('status',$input['status'])->get()->toArray();
                            }
                            if ($nombre_activo['asset']== 'Todos') {
                                $activos4 = $resume_equipment_lecas->where('dependencias_id',$input['asset_process_id'])
                                ->groupBy('placa')->orderBy('placa')->get()->toArray();
                                } else {
                                    $activos4 =  $resume_equipment_lecas ->where('name_equipment',$nombre_activo['asset'])->where('dependencias_id',$input['asset_process_id'])->latest()->get()->toArray();
                                }
            
                        foreach($activos4 as $activo) {
                            if ($activo['placa'] != null) {
                                $activo['average'] = (float) $activo['average'];
                                array_push($activos, $activo);
                            }
                            continue;
                        }
                       
                       
                    }
                }
            }
       
            $activosResult = json_decode(json_encode($activos));
            (object) $activosResult;
             // se realiza la verificacion de que por lo menos una placa tenga un promedio.
            $flag=0;
                foreach ($activosResult as $key => $average) {
                    $flag += $average->average;
                }
                // Si no existen registros con esos filtros se retorna false
                if($flag==0){
                    return $this->sendSuccess('No se encuentran registros con esos filtros desde controlador.', 'error');
                }else{
                    //SI si existen registros con ese filtro se retorna true
                    return $this->sendResponse( $activosResult, 'si se encontraron registros.', 'warning');
                }    
        }
        // Condicion si recibe informacion con e filtro de Ejecución de los contratos.
        elseif ($indicatorType == "Ejecución de los contratos") {
        
            $total=0;
            $totalItems=0;
            $items=0;
            $retorno=[];
            $arreglo=[];
            $provider='';
            //retorna todo los contratos
          
            foreach ($input['list_indicator'] as  $contratos) {
             $provider =  key($input['list_indicator']);
                foreach ($contratos as  $contrato) {
                    foreach ($contrato as  $contrato_id) {
                        if ($contrato_id['contract_id'] != 'todos') {
                            $providerContract = ProviderContract::with(["mantBudgetAssignation", "providers"])->where('deleted_at',NULL)->where('id',$contrato_id['contract_id'])->where('start_date','>=',Carbon::parse($init_date )->format('Y-m-d'))->where('start_date','<=',Carbon::parse($final_date)->format('Y-m-d'))->get();
                            
                        } 
                        else {
                           
                            if ($provider != 'todos') {
                               
                                $providerContract = ProviderContract::with(["mantBudgetAssignation", "providers"])->where('deleted_at',NULL)->where('mant_providers_id',$provider)->where('start_date','>=',$init_date)->where('start_date','<=',$final_date)->get();
                            }else {
                                $providerContract = ProviderContract::with(["mantBudgetAssignation", "providers"])->where('start_date','>=',$init_date)->where('start_date','<=',$final_date)->where('deleted_at',NULL)->get();
                            }
                           
                            
                        }
                        foreach ($providerContract as  $providerContract) {
                            //Se recorre la relacion
                            foreach ($providerContract->mantBudgetAssignation as $budgetAsignation) {
                                //Se verifica que existan rubros
                                if ($budgetAsignation->mantAdministrationCostItems) {
                                    //Se le asignan todos los rubros que existan
                                    $items=$budgetAsignation->mantAdministrationCostItems;
                                    //Se verifica que existan rubros
                                    if ($items) {
                                        //Se recorre cada rubro
                                        foreach ($items as  $item) {
                                            //Se verifica que exista cada rubro
                                            if ($item) {
                                                //Se recorre la relacion de ejecucion
                                                foreach ($item->mantBudgetExecutions as $budgetExecution) {
                                                    //Se suman todos los valores ejecutados a una variable
                                                    $totalItems+=$budgetExecution->executed_value;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                                    //Se verifica que total item sea diferente de 0 para poder hacer la operacion y guardar todo en total
                                    if($providerContract->total_value_contract && $totalItems!=0){
                                        $total=($totalItems/$providerContract->total_value_contract)*100;

                                    $retorno = [
                                        'placa' => $providerContract['contract_number'],
                                        'average' => $total,
                                    ];
                                    array_push($arreglo,$retorno );

                                    $total=0;
                                    $totalItems=0;
                                    $items=0;
                                     }
                        }
                    }
                }
            }           
            
         $arreglo = json_decode(json_encode($arreglo));
      
            
            $flag=0;
            foreach ($arreglo as $key => $average) {
                $flag += $average->average;
            }                  
            // Si no existen registros con esos filtros se retorna false
            if($flag==0){
                return $this->sendSuccess('No se encuentran registros con esos filtros desde controlador.', 'error');
            }else{
                //SI si existen registros con ese filtro se retorna true
                return $this->sendResponse($arreglo, trans('data_obtained_successfully'));
            }    
        }
    }
 }
