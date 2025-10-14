<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use App\Imports\VehicleFuelImport;
use Modules\Maintenance\Http\Requests\CreateVehicleFuelRequest;
use Modules\Maintenance\Http\Requests\UpdateVehicleFuelRequest;
use Modules\Maintenance\Repositories\VehicleFuelRepository;
use Modules\Maintenance\Models\VehicleFuel;
use Modules\Maintenance\Models\FuelDocument;
use Modules\Maintenance\Models\VehicleFuelMigrationOld;
use Modules\Maintenance\Models\FuelHistory;
use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\Maintenance\RequestExportVehicleFuel;
use App\Exports\Maintenance\RequestExport;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

/**
 * Descripcion de la clase
 *
 * @author Andres Stiven Pinzon G. - Ago. 16- 2021
 * @version 1.0.0
 */
class VehicleFuelController extends AppBaseController {

    /** @var  VehicleFuelRepository */
    private $vehicleFuelRepository;

    /**
     * Constructor de la clase
     *
     * @author Andres Stiven Pinzon G. - Ago. 16- 2021
     * @version 1.0.0
     */
    public function __construct(VehicleFuelRepository $vehicleFuelRepo) {
        $this->vehicleFuelRepository = $vehicleFuelRepo;
    }

    /**
     * Muestra la vista para el CRUD de VehicleFuel.
     *
     * @author Andres Stiven Pinzon G. - Ago. 16- 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::vehicle_fuels.index');
    }


    /**
     * Muestra la vista para importar datos a la tabla de gestion de commbustible de vehiculos.
     *
     * @author Andres Stiven Pinzon G. - Oct. 08- 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function indexImport() {
        return view('maintenance::vehicle_fuels.index_import');
    }

    /**
     * Muestra la vista principal de registros importados de gestion de combustible de vehiculos.
     *
     * @author Andres Stiven Pinzon G. - Oct. 15- 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function indexHistorical() {
        return view('maintenance::vehicle_fuels.index_historical');
    }

    /**
     * Muestra la vista para el CRUD de VehicleFuel.
     *
     * @author Andres Stiven Pinzon G. - Dic. 27- 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexRegisterAll(Request $request) {
        return view('maintenance::vehicle_fuels.index_register_all');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Andres Stiven Pinzon G. - Dic. 27- 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function allRegisters(Request $request) {

        $input = $request->toArray();

        $result = str_replace(' ', '+', $request['f']);

        $filtros = base64_decode($result);

        $countFuels = 0;

        $newFilter = $this->removeExistsAfterCondition($filtros);

        $user=Auth::user();


        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        // Valida si existen las variables del paginado y si esta filtrando
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {

            // Expresión regular para capturar el fragmento "(date_register_name >= 'YYYY-MM-DD' AND date_register_name <= 'YYYY-MM-DD')"
            $pattern = "/\(\s*DATE\(\s*date_register_name\s*\)\s*>=\s*'\d{4}-\d{2}-\d{2}'\s*AND\s*DATE\(\s*date_register_name\s*\)\s*<=\s*'\d{4}-\d{2}-\d{2}'\s*\)/i";


            // Validar si el string contiene el fragmento específico
            if (preg_match($pattern, $filtros, $matches)) {

                // Expresión regular para encontrar fechas en el formato 'YYYY-MM-DD'
                $pattern = "/\b\d{4}-\d{2}-\d{2}\b/";

                // Buscar todas las coincidencias de fechas
                preg_match_all($pattern, $matches[0], $matches);

                // Extraer las fechas del resultado
                $startDate = isset($matches[0][0]) ? $matches[0][0] : null;
                $endDate = isset($matches[0][1]) ? $matches[0][1] : null;

                $endDate = $endDate . ' 23:59:59';

                if(Auth::user()->hasRole('mant Consulta proceso')){
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                   $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where('dependencies_id', $user->id_dependencia)->whereRaw($newFilter)->whereRaw("
                        getDateRegisterName(created_migration, created_at) >= ? 
                        AND getDateRegisterName(created_migration, created_at) <= ?", 
                        [$startDate, $endDate]
                    )->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();
    
                   $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where('dependencies_id', $user->id_dependencia)->whereRaw($newFilter)->whereRaw("
                        getDateRegisterName(created_migration, created_at) >= ? 
                        AND getDateRegisterName(created_migration, created_at) <= ?", 
                        [$startDate, $endDate]
                    )->count();
       
                   }else{
                   
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                    $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->whereRaw($newFilter)->whereRaw("
                        getDateRegisterName(created_migration, created_at) >= ? 
                        AND getDateRegisterName(created_migration, created_at) <= ?", 
                        [$startDate, $endDate]
                    )->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();
        
                    $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->whereRaw($newFilter)->whereRaw("
                        getDateRegisterName(created_migration, created_at) >= ? 
                        AND getDateRegisterName(created_migration, created_at) <= ?", 
                        [$startDate, $endDate]
                    )->count();
                }

            }else{
                
                if(Auth::user()->hasRole('mant Consulta proceso')){
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                   $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where('dependencies_id', $user->id_dependencia)->whereRaw($newFilter)->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();
    
                   $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where('dependencies_id', $user->id_dependencia)->whereRaw($newFilter)->count();
       
                   }else{
                   
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                    $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->whereRaw($newFilter)->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();
        
                    $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->whereRaw($newFilter)->count();
                }

            }



        } else if(isset($request["cp"]) && isset($request["pi"])) {

            if(Auth::user()->hasRole('mant Consulta proceso')){

                // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
               $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where('dependencies_id', $user->id_dependencia)->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

               $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where('dependencies_id', $user->id_dependencia)->count();
   
               }else{
               
                // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

                $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->count();
       
           }

        } else {

            if(Auth::user()->hasRole('mant Consulta proceso')){

                // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
               $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where('dependencies_id', $user->id_dependencia)->latest()->get()->toArray();

               $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where('dependencies_id', $user->id_dependencia)->count();
   
               }else{
               
                // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->latest()->get()->toArray();

                $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->count();
       
           }

        }

        return $this->sendResponseAvanzado($vehicleFuels, trans('data_obtained_successfully'), null, ["total_registros" => $countFuels]);

    }

    /**
     * Obtiene los elementos registrados en el mes presente
     *
     * @author Andres Stiven Pinzon G. - Dic. 27- 2021
     * @version 1.0.1
     *
     * @return Response
     */
    public function all(Request $request) {

        $input = $request->toArray();
        

        $result = str_replace(' ', '+', $request['f']);

        $filtros = base64_decode($result);

        $countFuels = 0;

        $newFilter = $this->removeExistsAfterCondition($filtros);

        $user=Auth::user();

        // $current_date=date('2024-07-01');
        $current_date=date('Y-m-01');
        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        // Valida si existen las variables del paginado y si esta filtrando
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {

            // Expresión regular para capturar el fragmento "(date_register_name >= 'YYYY-MM-DD' AND date_register_name <= 'YYYY-MM-DD')"
            $pattern = "/\(\s*DATE\(\s*date_register_name\s*\)\s*>=\s*'\d{4}-\d{2}-\d{2}'\s*AND\s*DATE\(\s*date_register_name\s*\)\s*<=\s*'\d{4}-\d{2}-\d{2}'\s*\)/i";

            // Validar si el string contiene el fragmento específico
            if (preg_match($pattern, $filtros, $matches)) {

                // Expresión regular para encontrar fechas en el formato 'YYYY-MM-DD'
                $pattern = "/\b\d{4}-\d{2}-\d{2}\b/";

                // Buscar todas las coincidencias de fechas
                preg_match_all($pattern, $matches[0], $matches);

                // Extraer las fechas del resultado
                $startDate = isset($matches[0][0]) ? $matches[0][0] : null;
                $endDate = isset($matches[0][1]) ? $matches[0][1] : null;

                $endDate = $endDate . ' 23:59:59';


                if(Auth::user()->hasRole('mant Consulta proceso')){
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                   $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->where('dependencies_id', $user->id_dependencia)->whereRaw($newFilter)->whereRaw("
                        getDateRegisterName(created_migration, created_at) >= ? 
                        AND getDateRegisterName(created_migration, created_at) <= ?", 
                        [$startDate, $endDate]
                    )->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();
    
                   $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->where('dependencies_id', $user->id_dependencia)->whereRaw($newFilter)->whereRaw("
                        getDateRegisterName(created_migration, created_at) >= ? 
                        AND getDateRegisterName(created_migration, created_at) <= ?", 
                        [$startDate, $endDate]
                    )->count();
       
                   }else{
                   
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                    $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->whereRaw($newFilter)->whereRaw("
                        getDateRegisterName(created_migration, created_at) >= ? 
                        AND getDateRegisterName(created_migration, created_at) <= ?", 
                        [$startDate, $endDate]
                    )->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();
        
                    $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->whereRaw($newFilter)->whereRaw("
                        getDateRegisterName(created_migration, created_at) >= ? 
                        AND getDateRegisterName(created_migration, created_at) <= ?", 
                        [$startDate, $endDate]
                    )->count();
                }

            }else{
                
                if(Auth::user()->hasRole('mant Consulta proceso')){
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                   $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->where('dependencies_id', $user->id_dependencia)->whereRaw($newFilter)->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();
    
                   $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->where('dependencies_id', $user->id_dependencia)->whereRaw($newFilter)->count();
       
                   }else{
                   
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                    $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->whereRaw($newFilter)->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();
        
                    $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->whereRaw($newFilter)->count();
                }

            }



        } else if(isset($request["cp"]) && isset($request["pi"])) {

            if(Auth::user()->hasRole('mant Consulta proceso')){

                // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
               $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->where('dependencies_id', $user->id_dependencia)->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

               $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->where('dependencies_id', $user->id_dependencia)->count();
   
               }else{
               
                // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

                $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->count();
       
           }

        } else {

            if(Auth::user()->hasRole('mant Consulta proceso')){

                // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
               $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->where('dependencies_id', $user->id_dependencia)->latest()->get()->toArray();

               $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->where('dependencies_id', $user->id_dependencia)->count();
   
               }else{
               
                // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                $vehicleFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->latest()->get()->toArray();

                $countFuels = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->count();
       
           }

        }

        return $this->sendResponseAvanzado($vehicleFuels, trans('data_obtained_successfully'), null, ["total_registros" => $countFuels]);

    }

    /**
     * Limpia el query de los filtros no deseados en la variable
     *
     * @param string $query La consulta a limpiar.
     * @return string La consulta limpia.
     */
    public function removeExistsAfterCondition($query) {
        // Expresión regular para encontrar la condición `typeQuery LIKE '%MANT_RESUME_MACHINERY_VEHICLES_YELLOW_ID,PERFORMANCE_BY_GALLON%'`
        $pattern1 = "/\b(typeQuery\s+LIKE\s+'%MANT_RESUME_MACHINERY_VEHICLES_YELLOW_ID,PERFORMANCE_BY_GALLON%')\s*(AND\s*)?/i";
        
        // Expresión regular para encontrar la condición `(date_register_name >= 'YYYY-MM-DD' AND date_register_name <= 'YYYY-MM-DD')`
        $pattern2 = "/\(\s*DATE\(\s*date_register_name\s*\)\s*>=\s*'\d{4}-\d{2}-\d{2}'\s*AND\s*DATE\(\s*date_register_name\s*\)\s*<=\s*'\d{4}-\d{2}-\d{2}'\s*\)\s*(AND\s*)?/i";

        // Expresión regular para encontrar la condición `todos LIKE '%TODOS%'`
        $pattern3 = "/\btodos\s+LIKE\s+'%TODOS%'\s*(AND\s*)?/i";

        // Reemplaza las condiciones encontradas por una cadena vacía
        $cleanedQuery = preg_replace([$pattern1, $pattern2, $pattern3], '', $query);
        
        // Elimina cualquier "AND" colgante al final de la consulta
        $cleanedQuery = preg_replace("/\s+AND\s*$/i", '', $cleanedQuery);
        
        // Si la consulta está vacía después de limpiar, devuelve "1=1"
        if (empty(trim($cleanedQuery))) {
            $cleanedQuery = "1=1";
        }

        return $cleanedQuery;
    }

    
    
    

    /**
     * Registra datos en la tabla de gestion de vehiculos por medio de una importacion.
     *
     * @author Andres Stiven Pinzon G. - Oct. 12- 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function historicalRegisters(Request $request){
        // dd($request->toArray());
        (new VehicleFuelImport())->import($request->file('path_general_document'));
        return $this->sendResponse([], trans('msg_success_save'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Andres Stiven Pinzon G. - Ago. 18 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getVehicle(Request $request) {
        
        //Consulta todos los datos del vehiculo de la placa ingresada
        $vehicle = ResumeMachineryVehiclesYellow::with(['dependencies','assetType'])->where("plaque","like","%".$request['query']."%")
        ->get();
        $consulta = $vehicle->toArray();
        //Se valida que la consulta no este vacia
        if(count($consulta)){
            return $this->sendResponse($vehicle->toArray(), trans('data_obtained_successfully'));
        }
        else{
            //si esta vacia se retorna un mensaje de error
            return $this->sendResponse([],"Error,Por favor ingrese un numero de placa valido");
        }
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Kleverman Salazar. - Jul. 17 - 2025
     * @version 1.0.0
     *
     * @return Response
     */
    public function getVehicleFieldByPlate(Request $request,string $fieldName) {
        
        //Consulta todos los datos del vehiculo de la placa ingresada
        $vehicle = ResumeMachineryVehiclesYellow::select([$fieldName,"plaque"])->where("plaque","like","%".$request['query']."%")
        ->get()->toArray();

        //Se valida que la consulta no este vacia
        if(!empty($vehicle)){
            return $this->sendResponse($vehicle, trans('data_obtained_successfully'));
        }

        return $this->sendResponse([],"Error,Por favor ingrese un numero de placa valido");
    }

    /**
     * Obtiene todos los registros de gestion de combustible de vehiculos importados
     *
     * @author Andres Stiven Pinzon G. - Oct. 15 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getHistoricalVehicleFuel() {
        //Consulta todos los datos de los registros importados
        $vehicle = VehicleFuelMigrationOld::with(['assetType','resumeMachineryVehiclesYellow'])->latest()->get();
        
        return $this->sendResponse($vehicle->toArray(),trans('data_obtained_successfully'));
    }

    /**
     * Obtiene el horometro anterior o kilometraje anterior del registro de un vehiculo
     *
     * @author Andres Stiven Pinzon G. - Ago. 19 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getInfo($id) {
        // $info = VehicleFuel::select("current_hourmeter","current_mileage")->where("mant_resume_machinery_vehicles_yellow_id","=",$id)->latest()->first();
        // $info = VehicleFuel::select("current_hourmeter","current_mileage")->where("mant_resume_machinery_vehicles_yellow_id","=",$id)->orderby('id','DESC')->limit(1)->get();
        // $info = VehicleFuel::where("mant_resume_machinery_vehicles_yellow_id","=",$id)->where("created_at","<",$current_date)->limit(1)->get();

        //Obtiene el horometro actual o el kilometraje actual del ultimo registro asociado a la placa
        $info = Db::select('SELECT * FROM mant_vehicle_fuels WHERE mant_resume_machinery_vehicles_yellow_id = '.$id.' AND deleted_at IS NULL ORDER BY IF(created_migration IS NOT NULL, CONCAT(created_migration, " ", tanking_hour), created_at) DESC');

        if(!empty($info)){
            return $this->sendResponse($info[0], trans('data_obtained_successfully'));
        }
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Andres Stiven Pinzon G. - Ago. 16- 2021
     * @version 1.0.0
     *
     * @param CreateVehicleFuelRequest $request
     *
     * @return Response
     */
    public function store(CreateVehicleFuelRequest $request) {
        //Recupera el usuario en sesion
        $user=Auth::user();
        $input = $request->all();
        
        //Trae el ultimo registro del vehiculo con esa placa
        // $info = VehicleFuel::where("mant_resume_machinery_vehicles_yellow_id","=",$input['mant_resume_machinery_vehicles_yellow_id'])->latest()->first();
        $info = Db::select('SELECT * FROM mant_vehicle_fuels WHERE mant_resume_machinery_vehicles_yellow_id = '.$input['mant_resume_machinery_vehicles_yellow_id'].' AND deleted_at IS NULL ORDER BY IF(created_migration IS NOT NULL, CONCAT(created_migration, " ", tanking_hour), created_at) DESC');
        // dd(count($info));
        // if( $info[0] ==null)
        if( count($info)==0){
            // Inicia la transaccion
            DB::beginTransaction();
            try {
        
            // Inicia la transaccion
            // DB::beginTransaction();
            // try {
                
                // Inserta el registro en la base de datos
                $vehicleFuel = $this->vehicleFuelRepository->create($input);

                $vehicleFuel->dependencias;
                $vehicleFuel->resumeMachineryVehiclesYellow;
                $vehicleFuel->assetType;

                $historyFuel= new FuelHistory();
                $historyFuel->description="Se creo registro";
                $historyFuel->plaque=$vehicleFuel->resumeMachineryVehiclesYellow->plaque;
                $historyFuel->user_name=$user->name;
                $historyFuel->id_fuel=$vehicleFuel->id; 
                $historyFuel->date_register=$vehicleFuel->created_at;
                $historyFuel->action="Crear";
                $historyFuel->users_id=$user->id;
                $historyFuel->save();



                // Efectua los cambios realizados
                DB::commit();

                return $this->sendResponse($vehicleFuel->toArray(), trans('msg_success_save'));
            } catch (\Illuminate\Database\QueryException $error) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\VehicleFuelController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\VehicleFuelController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
                // Retorna error de tipo logico
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
            }
        }else{
            if($request['current_mileage']!=null){
                //Se valida que el nuevo registro tenga un kilometraje mayor al campo kilometraje actual del registro anterior del vehiculo con esa placa
                if($input['current_mileage'] > $info[0]->current_mileage){
        
                    //current_mileage
                    if(!empty($info[0])){
                        // $input['previous_mileage'] = $dataDetails->current_mileage;
                        $input['previous_mileage'] = $info[0]->current_mileage;
                    }
                    
                    // Inicia la transaccion
                    DB::beginTransaction();
                    try {
                        // Inserta el registro en la base de datos
                        $vehicleFuel = $this->vehicleFuelRepository->create($input);
            
                        $vehicleFuel->dependencias;
                        $vehicleFuel->resumeMachineryVehiclesYellow;
                        $vehicleFuel->assetType;

                        //Se guarda en el historial de consumo de combustible
                        $historyFuel= new FuelHistory();
                        $historyFuel->description="Se creo registro";
                        $historyFuel->plaque=$vehicleFuel->resumeMachineryVehiclesYellow->plaque;
                        $historyFuel->user_name=$user->name;
                        $historyFuel->id_fuel=$vehicleFuel->id; 
                        $historyFuel->action="Crear";
                        $historyFuel->date_register=$vehicleFuel->created_at;
                        $historyFuel->users_id=$user->id;
                        $historyFuel->save();

                        // Efectua los cambios realizados
                        DB::commit();
            
                        return $this->sendResponse($vehicleFuel->toArray(), trans('msg_success_save'));
                    } catch (\Illuminate\Database\QueryException $error) {
                        // Devuelve los cambios realizados
                        DB::rollback();
                        // Inserta el error en el registro de log
                        $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\VehicleFuelController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                        // Retorna mensaje de error de base de datos
                        return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
                    } catch (\Exception $e) {
                        // Devuelve los cambios realizados
                        DB::rollback();
                        // Inserta el error en el registro de log
                        $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\VehicleFuelController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
                        // Retorna error de tipo logico
                        return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
                    }
                }else{
                    $message="El valor de kilometros actual tiene que ser mayor que: ".$info[0]->current_mileage;
                            return $this->sendResponse(1,  $message );
                }    
            }else{
                //Se valida que el nuevo registro tenga un horometro mayor al campo horometro actual del registro anterior del vehiculo con esa placa
                if($input['current_hourmeter'] > $info[0]->current_hourmeter){
                    //current_mileage
                    if(!empty($info[0])){
                        // $input['previous_hourmeter'] = $dataDetails->current_hourmeter;
                        $input['previous_hourmeter'] = $info[0]->current_hourmeter;
                        
                        
                    }
                    
                    // Inicia la transaccion
                    DB::beginTransaction();
                    try {
                        // Inserta el registro en la base de datos
                        $vehicleFuel = $this->vehicleFuelRepository->create($input);
            
                        $vehicleFuel->dependencias;
                        $vehicleFuel->resumeMachineryVehiclesYellow;
                        $vehicleFuel->assetType;
                        //Se guarda en el historial de consumo de combustible
                        $historyFuel= new FuelHistory();
                        $historyFuel->description="Se creo registro";
                        $historyFuel->plaque=$vehicleFuel->resumeMachineryVehiclesYellow->plaque;
                        $historyFuel->user_name=$user->name;
                        $historyFuel->date_register=$vehicleFuel->created_at;
                        $historyFuel->id_fuel=$vehicleFuel->id; 
                        $historyFuel->action="Crear";
                        $historyFuel->users_id=$user->id;
                        $historyFuel->save();
                        
                        // Efectua los cambios realizados
                        DB::commit();
            
                        return $this->sendResponse($vehicleFuel->toArray(), trans('msg_success_save'));
                    } catch (\Illuminate\Database\QueryException $error) {
                        // Devuelve los cambios realizados
                        DB::rollback();
                        // Inserta el error en el registro de log
                        $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\VehicleFuelController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                        // Retorna mensaje de error de base de datos
                        return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
                    } catch (\Exception $e) {
                        // Devuelve los cambios realizados
                        DB::rollback();
                        // Inserta el error en el registro de log
                        $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\VehicleFuelController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
                        // Retorna error de tipo logico
                        return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
                    }
                    }else{
                        $message="El valor del Horometro actual tiene que ser mayor que: ".$info[0]->current_hourmeter;
                        return $this->sendResponse(1,  $message );
                    }  
    
            }


        }
        
    }

    /**
     * Agrega los documentos a un registro de combustible.
     *
     * @author Kleverman Salazar Florez. - Dic. 21 - 2022
     * @version 1.0.0
     *
     * @param Requests $request
     *
     * @return Response
     */
    public function addDocumentByVehicleFuels(Request $request){
        $input = $request->all();

        if($request->hasFile('attached_invoce')){
            $urlDocumentFile = substr($input['attached_invoce']->store('public/maintenance/fuel_documents'), 7);
            $input['attached_invoce'] = $urlDocumentFile;
        }
        if($request->hasFile('photo_tachometer_hourmeter')){
            $urlDocumentFile = substr($input['photo_tachometer_hourmeter']->store('public/maintenance/fuel_documents'), 7);
            $input['photo_tachometer_hourmeter'] = $urlDocumentFile;
        }
        VehicleFuel::where('id',$input['id'])->update(['document_name' => $input['document_name'],'document_description' => $input['document_description'],'attached_invoce' => $input['attached_invoce'], 'photo_tachometer_hourmeter' => $input['photo_tachometer_hourmeter']]);

        $vehicleFuel = VehicleFuel::where('id',$input['id'])->get()->first();

        return $this->sendResponse($vehicleFuel->toArray(), trans('msg_success_update'));
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Andres Stiven Pinzon G. - Ago. 16- 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateVehicleFuelRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateVehicleFuelRequest $request) {
        //Arreglo a retornar
        $array= array();
        $user=Auth::user();
        $input = $request->all();
        
        //Trae el registro posterior del vehiculo con esa placa
        $infoPost = VehicleFuel::where([
            ['created_at', '>', $input['created_at']],
            ["mant_resume_machinery_vehicles_yellow_id","=",$input['mant_resume_machinery_vehicles_yellow_id']]
            ])->whereNull("deleted_at")->limit(1)->get();
        //Trae el registro anterior del vehiculo con esa placa
        $infoLast = VehicleFuel::where([
            ['created_at', '<', $input['created_at']],
            ["mant_resume_machinery_vehicles_yellow_id","=",$input['mant_resume_machinery_vehicles_yellow_id']]
            ])->whereNull("deleted_at")->limit(1)->get();
        
        //Se valida que el request traiga un atributo con el nombre de current_mileage
        if($request['current_mileage']!=null){

            //Se valida que las 2 consultas traigan datos
            if(count($infoLast) !=0 && count($infoPost) !=0){
                $varNum= floatval($input['current_mileage']);
                
                //Se valida que el nuevo valor de current mileage sea mayor que el del registro anterior y menor que el del registro posterior
                if($varNum <= $infoLast[0]->current_mileage || $varNum >= $infoPost[0]->current_mileage){                            
                    $message="El valor del kilometraje actual tiene que ser menor a: ". $infoPost[0]->current_mileage. " y tiene que ser mayor que: ". $infoLast[0]->current_mileage;
                    return $this->sendResponse(1, $message);
                 }
            }else{
                if(count($infoPost)!=0 && count($infoLast)==0){
                    $varNum= floatval($input['current_mileage']);                
                    if($varNum >= $infoPost[0]->current_mileage){
                        $message="El valor del kilometraje actual tiene que ser menor a:  " .$infoPost[0]->current_mileage;
                        return $this->sendResponse(1, $message );
                    }
                }else{
                    if(count($infoPost) ==0 && count($infoLast)!=0){                    
                        $varNum= floatval($input['current_mileage']);
                    
                        if($varNum <= $infoLast[0]->current_mileage){
                            $message="El valor del kilometraje actual tiene que ser mayor a: " .$infoLast[0]->current_mileage;
                            return $this->sendResponse(1,  $message);
                        }
                    }
                }
            }
        }else{
            //Se valida que el request traiga un campo llamado current hourmeter
            if($request['current_hourmeter']!=null){

                //Se valida que las 2 consultas traigan datos
                if(count($infoLast) !=0 && count($infoPost) !=0){
                    $varNum= floatval($input['current_hourmeter']);
                    
                    //Se valida que el nuevo valor de current hourmeter sea mayor que el del registro anterior y menor que el del registro posterior
                     if($varNum <= $infoLast[0]->current_hourmeter || $varNum >= $infoPost[0]->current_hourmeter){                            
                         $message="El valor del horometro actual tiene que ser menor a: ". $infoPost[0]->current_hourmeter. " y tiene que ser mayor que: ". $infoLast[0]->current_hourmeter;
                         return $this->sendResponse(1, $message);
                     }
                }else{
                    if(count($infoPost)!=0 && count($infoLast)==0){
                        $varNum= floatval($input['current_hourmeter']);                
                        if($varNum >= $infoPost[0]->current_hourmeter){
                            $message="El valor del horometro actual tiene que ser menor a:  " .$infoPost[0]->current_hourmeter;
                            return $this->sendResponse(1, $message );
                        }
                    }else{
                        if(count($infoPost) ==0 && count($infoLast)!=0){                    
                            $varNum= floatval($input['current_hourmeter']);
                        if($varNum <= $infoLast[0]->current_hourmeter){
                            $message="El valor del horometro actual tiene que ser mayor a: " .$infoLast[0]->current_hourmeter;
                                return $this->sendResponse(1,  $message);
                            }
                        }
                    }
                }
            }



        }

        /** @var VehicleFuel $vehicleFuel */
        $vehicleFuel = $this->vehicleFuelRepository->find($id);
        
        if (empty($vehicleFuel)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
      
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            
            if($vehicleFuel->current_mileage != $request['current_mileage'] || $vehicleFuel->current_hourmeter != $request['current_hourmeter']){
                //Si la consulta posterior a la que se esta editando ,trae datos,entonces se procedera a actualizar el campo current mileage y a todos en los
                //que sean resultado de formulas y en los que este campo este involucrado
                if(count($infoPost) !=0){
                    if($request['current_mileage']){
                        $infoPost[0]->previous_mileage=$input['current_mileage'];
                        $infoPost[0]->variation_route_hour= $infoPost[0]->current_mileage-$input['current_mileage'];
                        $infoPost[0]->performance_by_gallon=$infoPost[0]->variation_route_hour/$infoPost[0]->fuel_quantity;
                        
                    }else{
                        //Si la consulta posterior a la que se esta editando ,trae datos,entonces se procedera a actualizar el campo current hourmeter y a todos en los
                        //que sean resultado de formulas y en los que este campo este involucrado
                        if($request['current_hourmeter']){
                            $infoPost[0]->previous_hourmeter=$input['current_hourmeter'];
                            $infoPost[0]->variation_tanking_hour= $infoPost[0]->current_hourmeter-$input['current_hourmeter'];
                            $infoPost[0]->performance_by_gallon=$infoPost[0]->variation_tanking_hour/$infoPost[0]->fuel_quantity;
                        
                        }
                    }
                    
                    $infoPost[0]->save();
                    //Se agregan los datos actualizados al array que se va a retornar
                    array_push( $array, $infoPost[0]);
                }
            }
            
            // Actualiza el registro
            $vehicleFuel = $this->vehicleFuelRepository->update($input, $id);
            $vehicleFuel->dependencias;
            $vehicleFuel->resumeMachineryVehiclesYellow;
            $vehicleFuel->assetType;

            //Se guarda en el historial de consumo de combustible
            $historyFuel= new FuelHistory();
            $historyFuel->description=$input['descriptionDelete'];
            $historyFuel->plaque=$vehicleFuel->resumeMachineryVehiclesYellow->plaque;
            $historyFuel->user_name=$user->name;
            $historyFuel->id_fuel=$vehicleFuel->id;
            $historyFuel->date_register=$vehicleFuel->created_at;
            $historyFuel->action="Editar";
            $historyFuel->users_id=$user->id;
            $historyFuel->save();

            // Efectua los cambios realizados
            DB::commit();
            array_push( $array, $vehicleFuel->toArray());
            
            return $this->sendResponse($array, trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\VehicleFuelController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\VehicleFuelController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
        
        
    }

    /**
     * Elimina un VehicleFuel del almacenamiento
     *
     * @author Andres Stiven Pinzon G. - Ago. 16- 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Request $request) {

        
        $array= array();
        $user=Auth::user();
        
        /** @var VehicleFuel $vehicleFuel */
        $vehicleFuel = $this->vehicleFuelRepository->find($request['id']);

        // dd($vehicleFuel->current_mileage);
        if (empty($vehicleFuel)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // $infoPost = VehicleFuel::where([
        //     ['created_at', '>', $vehicleFuel->created_at],
        //     ["mant_resume_machinery_vehicles_yellow_id","=",$vehicleFuel->mant_resume_machinery_vehicles_yellow_id]
        //     ])->limit(1)->get();
        // $infoLast = VehicleFuel::where([
        //     ['created_at', '<', $vehicleFuel->created_at],
        //     ["mant_resume_machinery_vehicles_yellow_id","=",$vehicleFuel->mant_resume_machinery_vehicles_yellow_id]
        //     ])->latest()->get();

        //Trae el registro anterior del vehiculo con esa placa
        $infoLast = VehicleFuel::where([
            ['invoice_date', '<=', $vehicleFuel->invoice_date],['id','<',$vehicleFuel->id],
            ["mant_resume_machinery_vehicles_yellow_id","=",$vehicleFuel->mant_resume_machinery_vehicles_yellow_id]
            ])->orderby('invoice_date','DESC')->limit(1)->get();

        //Trae el registro posterior del vehiculo con esa placa
        $infoPost = VehicleFuel::where([
            ['invoice_date', '>=', $vehicleFuel->invoice_date],['id','>',$vehicleFuel->id],
            ["mant_resume_machinery_vehicles_yellow_id","=",$vehicleFuel->mant_resume_machinery_vehicles_yellow_id]
            ])->limit(1)->get();

        // $infoLast = Db::select('SELECT * FROM mant_vehicle_fuels WHERE mant_resume_machinery_vehicles_yellow_id = 117 AND deleted_at IS NULL AND created_migration IS NOT NULL AND (CONCAT(created_migration, " ", tanking_hour) < created_at OR  created_at < created_at) ORDER BY CONCAT(created_migration, " ", tanking_hour) DESC ');
        // $infoLast = Db::select('SELECT * FROM mant_vehicle_fuels WHERE mant_resume_machinery_vehicles_yellow_id = '.$vehicleFuel->mant_resume_machinery_vehicles_yellow_id.' AND deleted_at IS NULL AND created_at < IF(created_migration IS NOT NULL, CONCAT('.$vehicleFuel->created_migration.', " ", '.$vehicleFuel->tanking_hour.'),'.$vehicleFuel->created_at.') ORDER BY IF(created_migration IS NOT NULL,CONCAT(created_migration, " ",tanking_hour),created_at) DESC ');

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            //Se valida que las 2 consultas contengan datos
            if(count($infoLast) !=0 && count($infoPost) !=0){
                // $currMileage= $vehicleFuel->current_mileage;
                if($vehicleFuel->current_mileage){
                    //Se actualiza el kilometraje anterior y todos los campos los cuales son resultado de formulas en los que el kilometraje anterior
                    //se vio involucrado
                    $infoPost[0]->previous_mileage=$infoLast[0]->current_mileage;
                    $infoPost[0]->variation_route_hour= $infoPost[0]->current_mileage-$infoLast[0]->current_mileage;
                    $infoPost[0]->performance_by_gallon=$infoPost[0]->variation_route_hour/$infoPost[0]->fuel_quantity;
                    
                }else{
                    if($vehicleFuel->current_hourmeter){
                        //Se actualiza el kilometraje anterior y todos los campos los cuales son resultado de formulas en los que el kilometraje anterior
                        //se vio involucrado
                        $infoPost[0]->previous_hourmeter=$infoLast[0]->current_hourmeter;
                        $infoPost[0]->variation_tanking_hour= $infoPost[0]->current_hourmeter-$infoLast[0]->current_hourmeter;
                        $infoPost[0]->performance_by_gallon=$infoPost[0]->variation_tanking_hour/$infoPost[0]->fuel_quantity;
                        
                    }
                }
                //Se almacenan los cambios
                $infoPost[0]->save();
            }
            else{
                // Si no hay un registro posterior al que se va a eliminar ,simplementes este se eliminara 
                if(count($infoPost) == 0){
                    $vehicleFuel->delete();

                    //Se guarda en el historial de consumo de combustible
                    $historyFuel= new FuelHistory();
                    $historyFuel->description=$request['observationDelete'];
                    $historyFuel->plaque=$vehicleFuel->resumeMachineryVehiclesYellow->plaque;
                    $historyFuel->user_name=$user->name;
                    $historyFuel->id_fuel=$vehicleFuel->id;
                    $historyFuel->date_register=$vehicleFuel->created_at;
                    $historyFuel->action="Eliminar";
                    $historyFuel->users_id=$user->id;
                    $historyFuel->save();

                    // Efectua los cambios realizados
                    DB::commit();

                    // return $this->sendResponse($array, trans('Delete'));
                    return $this->sendSuccess(trans('msg_success_drop'));
                }
                // Si no hay un registro anterior al que se va a eliminar ,este actualizara el valor del kilometraje anterior del siguiente y se eliminara 
                elseif(count($infoLast) == 0){            
                    // $currMileage= $vehicleFuel->current_mileage;

                    //Se valida si el registro contiene un kilometraje actual
                    if($vehicleFuel->current_mileage){
                        //Se actualiza el kilometraje anterior del registro posterior y todos los campos los cuales son resultado de formulas en los que el kilometraje anterior
                        //se vio involucrado
                        $infoPost[0]->previous_mileage=0;
                        $infoPost[0]->variation_route_hour= $infoPost[0]->current_mileage-$infoPost[0]->previous_mileage;
                        $infoPost[0]->performance_by_gallon=$infoPost[0]->variation_route_hour/$infoPost[0]->fuel_quantity;
                    
                    }else{
                        //Se valida si el registro contiene un horometro actual
                        if($vehicleFuel->current_hourmeter){
                            //Se actualiza el horometro anterior del registro posterior y todos los campos los cuales son resultado de formulas en los que el horometro anterior
                            //se vio involucrado
                            $infoPost[0]->previous_hourmeter=0;
                            $infoPost[0]->variation_tanking_hour= $infoPost[0]->current_hourmeter-$infoPost[0]->previous_hourmeter;
                            $infoPost[0]->performance_by_gallon=$infoPost[0]->variation_tanking_hour/$infoPost[0]->fuel_quantity;
                        
                        }
                    }
                    //Se almacenan los cambios
                    $infoPost[0]->save();
                }
            }
            
            // Elimina el registro
            $vehicleFuel->delete();

            $historyFuel= new FuelHistory();
            $historyFuel->description=$request['observationDelete'];
            $historyFuel->plaque=$vehicleFuel->resumeMachineryVehiclesYellow->plaque;
            $historyFuel->user_name=$user->name;
            $historyFuel->date_register=$vehicleFuel->created_at;
            $historyFuel->id_fuel=$vehicleFuel->id; 
            $historyFuel->action="Eliminar";
            $historyFuel->users_id=$user->id;
            $historyFuel->save();


            // Efectua los cambios realizados
            DB::commit();

            // return $this->sendResponse($array, trans('Delete'));
            return $this->sendSuccess(trans('msg_success_drop'));

            // return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\VehicleFuelController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\VehicleFuelController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }        
            if(count($infoLast) ==0){
                if($vehicleFuel->current_mileage){
                    
                    $infoPost[0]->previous_mileage=0;
                    $infoPost[0]->variation_route_hour= $infoPost[0]->current_mileage-0;
                    $infoPost[0]->performance_by_gallon=$infoPost[0]->variation_route_hour/$infoPost[0]->fuel_quantity;
                    
                }else{
                    if($vehicleFuel->current_hourmeter){
                        
                        $infoPost[0]->previous_hourmeter=0;
                        $infoPost[0]->variation_tanking_hour= $infoPost[0]->current_hourmeter-0;
                        $infoPost[0]->performance_by_gallon=$infoPost[0]->variation_tanking_hour/$infoPost[0]->fuel_quantity;
                    
                    }
                }
                array_push( $array, $infoPost[0]);
                $infoPost[0]->save();
            }
            if(count($infoLast) !=0){

                if($vehicleFuel->current_mileage){
                    
                    $infoPost[0]->previous_mileage=$infoLast[0]->current_mileage;
                    $infoPost[0]->variation_route_hour= $infoPost[0]->current_mileage-$infoLast[0]->current_mileage;
                    $infoPost[0]->performance_by_gallon=$infoPost[0]->variation_route_hour/$infoPost[0]->fuel_quantity;
                    
                }else{
                    if($vehicleFuel->current_hourmeter){
                        
                        $infoPost[0]->previous_hourmeter=$infoLast[0]->current_hourmeter;
                        $infoPost[0]->variation_tanking_hour= $infoPost[0]->current_hourmeter-$infoLast[0]->current_hourmeter;
                        $infoPost[0]->performance_by_gallon=$infoPost[0]->variation_tanking_hour/$infoPost[0]->fuel_quantity;
                    
                    }
                }
                array_push( $array, $infoPost[0]);
                $infoPost[0]->save();
            }

            return $this->sendResponse($array, trans('msg_success_update'));
    
    }

    // /**
    //  * Organiza la exportacion de datos
    //  *php
    //  * @author Andres Stiven Pinzon G. - Ago. 14 - 2021
    //  * @version 1.0.0
    //  *
    //  * @param Request $request datos recibidos
    //  */
    // public function export(Request $request) {
    //     $input = $request->all();

    //     // Tipo de archivo (extencion)
    //     $fileType = $input['fileType'];
    //     // Nombre de archivo con tiempo de creacion
    //     $fileName = time().'-'.trans('vehicle_fuels').'.'.$fileType;

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
     * Genera el reporte de gestion de combustibles en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - Ago. 18 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        $filtros = $input["filtros"];

        $current_date=date('Y-m-01');


        $newFilter = $this->removeExistsAfterCondition($filtros);
          

        if (str_contains($filtros, 'todos')) {
            // Expresión regular para capturar el fragmento "(date_register_name >= 'YYYY-MM-DD' AND date_register_name <= 'YYYY-MM-DD')"
            $pattern = "/\(\s*DATE\(\s*date_register_name\s*\)\s*>=\s*'\d{4}-\d{2}-\d{2}'\s*AND\s*DATE\(\s*date_register_name\s*\)\s*<=\s*'\d{4}-\d{2}-\d{2}'\s*\)/i";

            // Validar si el string contiene el fragmento específico
            if (preg_match($pattern, $filtros, $matches)) {

                // Expresión regular para encontrar fechas en el formato 'YYYY-MM-DD'
                $pattern = "/\b\d{4}-\d{2}-\d{2}\b/";

                // Buscar todas las coincidencias de fechas
                preg_match_all($pattern, $matches[0], $matches);

                // Extraer las fechas del resultado
                $startDate = isset($matches[0][0]) ? $matches[0][0] : null;
                $endDate = isset($matches[0][1]) ? $matches[0][1] : null;

                $endDate = $endDate . ' 23:59:59';

                if(Auth::user()->hasRole('mant Consulta proceso')){
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                    $input['data'] = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where('dependencies_id', $user->id_dependencia)->whereRaw($newFilter)->whereRaw("
                        getDateRegisterName(created_migration, created_at) >= ? 
                        AND getDateRegisterName(created_migration, created_at) <= ?", 
                        [$startDate, $endDate])->latest()->get()->toArray();

                    }else{
                    
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                    $input['data'] = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->whereRaw($newFilter)->whereRaw("
                        getDateRegisterName(created_migration, created_at) >= ? 
                        AND getDateRegisterName(created_migration, created_at) <= ?", 
                        [$startDate, $endDate]
                    )->latest()->get()->toArray();

                }

            }else{
                
                if(Auth::user()->hasRole('mant Consulta proceso')){
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                    $input['data'] = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where('dependencies_id', $user->id_dependencia)->whereRaw($newFilter)->latest()->get()->toArray();

                    }else{
                    
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                    $input['data'] = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->whereRaw($newFilter)->latest()->get()->toArray();

                }

            }
        }else{
        // Expresión regular para capturar el fragmento "(date_register_name >= 'YYYY-MM-DD' AND date_register_name <= 'YYYY-MM-DD')"
        $pattern = "/\(\s*DATE\(\s*date_register_name\s*\)\s*>=\s*'\d{4}-\d{2}-\d{2}'\s*AND\s*DATE\(\s*date_register_name\s*\)\s*<=\s*'\d{4}-\d{2}-\d{2}'\s*\)/i";
            if (preg_match($pattern, $filtros, $matches)) {

                // Expresión regular para encontrar fechas en el formato 'YYYY-MM-DD'
                $pattern = "/\b\d{4}-\d{2}-\d{2}\b/";

                // Buscar todas las coincidencias de fechas
                preg_match_all($pattern, $matches[0], $matches);

                // Extraer las fechas del resultado
                $startDate = isset($matches[0][0]) ? $matches[0][0] : null;
                $endDate = isset($matches[0][1]) ? $matches[0][1] : null;

                $endDate = $endDate . ' 23:59:59';

                if(Auth::user()->hasRole('mant Consulta proceso')){
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                   $input['data'] = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->where('dependencies_id', $user->id_dependencia)->whereRaw($newFilter)->whereRaw("
                        getDateRegisterName(created_migration, created_at) >= ? 
                        AND getDateRegisterName(created_migration, created_at) <= ?", 
                        [$startDate, $endDate]
                    )->latest()->get()->toArray();
       
                   }else{
                   
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                    $input['data'] = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->whereRaw($newFilter)->whereRaw("
                        getDateRegisterName(created_migration, created_at) >= ? 
                        AND getDateRegisterName(created_migration, created_at) <= ?", 
                        [$startDate, $endDate]
                    )->latest()->get()->toArray();
        
                }

            }else{
                
                if(Auth::user()->hasRole('mant Consulta proceso')){
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                    $input['data'] = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->where('dependencies_id', $user->id_dependencia)->whereRaw($newFilter)->latest()->get()->toArray();
    
       
                   }else{
                   
                    // Realiza la consulta en la tabla de combustibles, filtra los registros del mes actual y año actual
                    $input['data'] = VehicleFuel::with(['resumeMachineryVehiclesYellow','assetType','dependencias'])->where("invoice_date",">=",$current_date)->where("invoice_date","<=",now())->whereRaw($newFilter)->latest()->get()->toArray();

                }

            }

        }
        // dd($input['data']);
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        
        //Agrupa por numero de placa en el reporte
        usort($input['data'], function ($a,$b) {     
            return $b['mant_resume_machinery_vehicles_yellow_id']-$a['mant_resume_machinery_vehicles_yellow_id'];
        });

        $fileName = date('Y-m-d H:i:s').'-'.trans('vehicle_fuel').'.'.$fileType;
        
        return Excel::download(new RequestExportVehicleFuel('maintenance::vehicle_fuels.report_excel', $input['data'],'u'), $fileName);
    }

    /**
     * Genera el reporte del historico de gestion de combustibles en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - Oct. 29 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function exportHistorical(Request $request) {
        $input = $request->all();
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        
        //Agrupa por numero de placa en el reporte
        usort($input['data'], function ($a,$b) {     
            return $b['mant_resume_machinery_vehicles_yellow_id']-$a['mant_resume_machinery_vehicles_yellow_id'];
        });

        $fileName = date('Y-m-d H:i:s').'-'.trans('vehicle_fuel').'.'.$fileType;
        
        return Excel::download(new RequestExportVehicleFuel('maintenance::vehicle_fuels.report_excel_historical', $input['data'],'r'), $fileName);
    }
    // get-vehicle-fuels-delete
}
