<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateTireInformationsRequest;
use Modules\Maintenance\Http\Requests\UpdateTireInformationsRequest;
use Modules\Maintenance\Repositories\TireInformationsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;
use Modules\Maintenance\Models\TireQuantitites;
use Modules\Maintenance\Models\TireInformations;
use Modules\Maintenance\Models\TireWears;
use Modules\Maintenance\Models\TireBrand;
use Modules\Maintenance\Models\TireReference;
use Modules\Maintenance\Models\TireReferences;
use Modules\Maintenance\Models\tireInformationHistory;
use Modules\Maintenance\Models\TireHistory;
use Modules\Maintenance\Models\SetTire;
use Modules\Maintenance\Models\TireHistoryMileage;
use Modules\Maintenance\Models\VehicleFuel;
use App\Exports\Maintenance\ResquestExportTires;
use Modules\Intranet\Models\Dependency;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use DateTime;
use App\Http\Controllers\JwtController;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class TireInformationsController extends AppBaseController
{

    /** @var  TireInformationsRepository */
    private $TireInformationsRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TireInformationsRepository $TireInformationsRepo)
    {
        $this->TireInformationsRepository = $TireInformationsRepo;
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Johan david Velasco - sep. 16 . 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //Valida si trae el id del activo para condicionar el listado
        if(empty($request['machinery'])){
            return view('maintenance::tire_informations.index');
        }else{
            $plaque = ResumeMachineryVehiclesYellow::where('id', '=', $request['machinery'])->get()->first()->toArray();
            
            return view('maintenance::tire_informations.index')->with("machinery",  $request['machinery'])->with("plaque_vehicle", $plaque['plaque']); 
        }    
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Johan david Velasco - sep. 16 . 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request)
    {
        $count_tires = 0;

        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        // Valida si existen las variables del paginado y si esta filtrando
        if(isset($request["f"]) && $request["f"] != "" && isset($request["?cp"]) && isset($request["pi"])) {

        $filtro = base64_decode($request["f"]);

        // cambia la clausula de consulta de LIKE  a =
        $filtro = $this->changeQueryOperator($filtro,'tire_brand', '=');
            
            if (empty($request['machinery'])) {
                $tire_informations = TireInformations::with(['ResumeMachineryVehiclesYellow','TireWears', 'excel', 'TireHistories','TireHistoryMileage','VehiclesFuels'])->whereRaw( $filtro )->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();


                $count_tires = TireInformations::with(['ResumeMachineryVehiclesYellow','TireWears', 'excel', 'TireHistories','TireHistoryMileage','VehiclesFuels'])->whereRaw( $filtro )->count();

            }else{

                $tire_informations = TireInformations::with(['ResumeMachineryVehiclesYellow','TireWears', 'excel', 'TireHistories','TireHistoryMileage','VehiclesFuels'])->where('mant_resume_machinery_vehicles_yellow_id',$request['machinery'])->where('assignment_type','=','Activo')->where('state','!=','Dada de baja')->whereRaw( $filtro )->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

                $count_tires = TireInformations::with(['ResumeMachineryVehiclesYellow','TireWears', 'excel', 'TireHistories','TireHistoryMileage','VehiclesFuels'])->where('mant_resume_machinery_vehicles_yellow_id',$request['machinery'])->where('assignment_type','=','Activo')->where('state','!=','Dada de baja')->whereRaw( $filtro )->count();
            }

        } else if(isset($request["?cp"]) && isset($request["pi"])) {

            if (empty($request['machinery'])) {
                $tire_informations = TireInformations::with(['ResumeMachineryVehiclesYellow','TireWears', 'excel', 'TireHistories','TireHistoryMileage','VehiclesFuels'])->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

                $count_tires = TireInformations::with(['ResumeMachineryVehiclesYellow','TireWears', 'excel', 'TireHistories','TireHistoryMileage','VehiclesFuels'])->count();

            }else{

                $tire_informations = TireInformations::with(['ResumeMachineryVehiclesYellow','TireWears', 'excel', 'TireHistories','TireHistoryMileage','VehiclesFuels'])->where('mant_resume_machinery_vehicles_yellow_id',$request['machinery'])->where('assignment_type','=','Activo')->where('state','!=','Dada de baja')->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

                $count_tires = TireInformations::with(['ResumeMachineryVehiclesYellow','TireWears', 'excel', 'TireHistories','TireHistoryMileage','VehiclesFuels'])->where('mant_resume_machinery_vehicles_yellow_id',$request['machinery'])->where('assignment_type','=','Activo')->where('state','!=','Dada de baja')->count();
            }

        } else {

            if (empty($request['machinery'])) {
                $tire_informations = TireInformations::with(['ResumeMachineryVehiclesYellow','TireWears', 'excel', 'TireHistories','TireHistoryMileage','VehiclesFuels'])->latest()->get()->toArray();

                $count_tires = TireInformations::with(['ResumeMachineryVehiclesYellow','TireWears', 'excel', 'TireHistories','TireHistoryMileage','VehiclesFuels'])->count();

            }else{

                $tire_informations = TireInformations::with(['ResumeMachineryVehiclesYellow','TireWears', 'excel', 'TireHistories','TireHistoryMileage','VehiclesFuels'])->where('mant_resume_machinery_vehicles_yellow_id',$request['machinery'])->where('assignment_type','=','Activo')->where('state','!=','Dada de baja')->latest()->get()->toArray();

                $count_tires = TireInformations::with(['ResumeMachineryVehiclesYellow','TireWears', 'excel', 'TireHistories','TireHistoryMileage','VehiclesFuels'])->where('mant_resume_machinery_vehicles_yellow_id',$request['machinery'])->where('assignment_type','=','Activo')->where('state','!=','Dada de baja')->count();
            }

        }


        // $tire_informations = TireInformations::with(['TireWears'])->where('mant_tire_informations_id',$request['data_id'])->latest()->get();
        return $this->sendResponseAvanzado($tire_informations, trans('data_obtained_successfully'), null, ["total_registros" => $count_tires]);

    }

    /**
     * Obtiene todas las marcas de llantas
     *
     * @author Johan David Velasco R. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function allBrands()
    {
        $brand = TireBrand::orderBy('brand_name', 'asc')
        ->get();
        return $this->sendResponse($brand->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todas las referencias de llantas
     *
     * @author Johan David Velasco R. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function allReferences()
    {
        $references = SetTire::groupBy('tire_reference')
        ->orderBy('tire_reference', 'asc')
        ->get();
        return $this->sendResponse($references->toArray(), trans('data_obtained_successfully'));
    }
    
    /**
     * Obtiene todas las referencias de una llanta en especifico
     *
     * @author Kleverman Salzar Florez. - Jul. 31 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function tireReferencesByTireBrand(int $tireBrandId)
    {
        $tireReferences = TireReference::select(["id","reference_name"])->where("mant_tire_brand_id",$tireBrandId)->orderBy("reference_name")->get();
        return $this->sendResponse($tireReferences->toArray(), trans('data_obtained_successfully'));
    }
    
    /**
     * Obtiene obtiene el desgaste de la llanta
     *
     * @author Johan David Velasco R. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function MaxWear($id)
    {
        $maxWear = SetTire::where('id',$id)->first()->toArray();
        $tireBrand = TireBrand::where('id',$maxWear['mant_tire_brand_id'])->first()->toArray();

        $dataNew = array_merge($maxWear, $tireBrand);

        return $this->sendResponse($dataNew, trans('data_obtained_successfully'));
        
    }

    /**
     * Consulta el kilometraje del combustible
     *
     * @author Johan David Velasco R. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function checkFuelMileage($idMachinery,$fecha)
    {
        // Eliminar la parte entre paréntesis
        $dateString = preg_replace('/\s*\(.*\)$/', '', $fecha);

        $date = new \DateTime($dateString);
        $formattedDate = $date->format('Y-m-d');

        $post = VehicleFuel::where('mant_resume_machinery_vehicles_yellow_id', $idMachinery)
        ->orderByRaw('ABS(DATEDIFF(invoice_date, "' . $formattedDate . '"))')
        ->first();


        if (!empty($post)) {
            return $this->sendResponse($post->toArray(), trans('data_obtained_successfully'));
        }else{
            return $this->sendResponse($post, trans('data_obtained_successfully'));

        }


    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Johan david Velasco - sep. 16 . 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
      
        //Asigna lo traido por el Request en la variable Tire
        $tire = $request->all();

        //Obtiene los datos del usuario en sesion
        $user=Auth::user();
      

        //Valida si el estado es de tipo almacen o activo
        if ($tire['assignment_type'] == 'Almacén') {

            $tireReference = TireReferences::where('id',$tire['mant_set_tires_id'])->get()->first()->toArray();

            //Asigna el nombre de la referencia al campó para almacenarlo
            $tire['tire_reference'] = $tireReference['name'];

            // Inicia la transaccion
                DB::beginTransaction();
                try {

                    $availableDepth = "";

                    $infoTire = ['assignment_type'=>$tire['assignment_type'],'mant_set_tires_id'=>$tire['mant_set_tires_id'],'tire_reference'=>$tire['tire_reference'], 'date_register'=>$tire['date_register'],'depth_tire'=>$tire['depth_tire'],'reference_name' => $tire["reference_name"],'tire_brand'  =>$tire["tire_brand"], 'available_depth'=>$availableDepth, 'plaque'=>' ', 'cost_tire'=>$tire['cost_tire'],'position_tire'=>$tire['position_tire'] ?? null, 'tire_reference'=>$tireReference['name']];

                    // Inserta el registro en la base de datos
                    $TireInformations = $this->TireInformationsRepository->create($infoTire);
                    
                    $history=new tireInformationHistory();
                    $history->mant_tire_informations_id=$TireInformations['id'];
                    $history->users_id=$user->id;
                    $history->user_name=$user->name;
                    $history->action="Crear";
                    $history->descripcion="Se crea el registro";
                    $history->assignment_type = $TireInformations['assignment_type'];
                    $history->status = "Activo";
                    
                    $history->save();

                    $TireInformations->ResumeMachineryVehiclesYellow;
                    $TireInformations["tire_brand_name"] = TireBrand::select("brand_name")->where("id",$tire["tire_brand"])->first()->brand_name;
        
                    // Efectua los cambios realizados
                    DB::commit();
        
                    return $this->sendResponse($TireInformations->toArray(), trans('msg_success_save'));
                } catch (\Illuminate\Database\QueryException $error) {
                    // Devuelve los cambios realizados
                    DB::rollback();
                    // Inserta el error en el registro de log
                    $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireInformationsController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
                    // Retorna mensaje de error de base de datos
                    return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
                } catch (\Exception $e) {
                    // Devuelve los cambios realizados
                    DB::rollback();
                    // Inserta el error en el registro de log
                    $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireInformationsController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
                    // Retorna error de tipo logico
                    return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
                }
        
        //Valida si la llanta es de tipo activo
        }else{

            $tireReference = TireReferences::where('id',$tire['mant_set_tires_id'])->get()->first()->toArray();

            //Asigna el nombre de la referencia al campó para almacenarlo
            $tire['tire_reference'] = $tireReference['name'];

            //Consulta el codigo de la llanta
            $codeExists = TireInformations::where('code_tire', $tire['code_tire'])->get()->first();

            $machinery = ResumeMachineryVehiclesYellow::where('id', '=', $tire['vehiculo'] ? $tire['vehiculo']['mant_resume_machinery_vehicles_yellow_id'] : $tire['mant_resume_machinery_vehicles_yellow_id'])->get()->first()->toArray();
        
            //Verifica si al consulta viene vacia o con data
            if(empty($codeExists)){
                
                //Consulta la refencia de la llanta
                // $tireReference = SetTire::where('id',$tire['mant_set_tires_id'])->get()->first();

                //Consulta la dependencia
                $dependency = Dependency::where('id',$tire['vehiculo'] ? $tire['vehiculo']['dependencias_id'] : $tire['dependencias_id'])->get()->first();

                //Asigna los valores a la variable
                $input = ['mant_resume_machinery_vehicles_yellow_id'=>$tire['vehiculo'] ? $tire['vehiculo']['mant_resume_machinery_vehicles_yellow_id'] : $tire['mant_resume_machinery_vehicles_yellow_id'],'name_machinery'=>$machinery['name_vehicle_machinery'] ,'name_dependencias' => $dependency['nombre'], 'dependencias_id'=>$tire['vehiculo'] ? $tire['vehiculo']['dependencias_id'] : $tire['dependencias_id'], 'assignment_type'=>$tire['assignment_type'], 'plaque'=>$machinery['plaque'], 'date_register'=>$tire['date_register'],'date_assignment' => $tire['date_assignment'], 'position_tire'=>$tire['position_tire'] ?? null, 'type_tire'=>$tire['type_tire'], 'cost_tire'=>$tire['cost_tire'], 'depth_tire'=>$tire['depth_tire'], 'mileage_initial'=>$tire['mileage_initial'], 'available_depth'=>$tire['available_depth'], 'general_cost_mm'=>$tire['general_cost_mm'], 'location_tire'=>$tire['location_tire'], 'code_tire'=>$tire['code_tire'],'reference_name' => $tire["reference_name"], 'tire_brand'=>$tire['tire_brand'],'inflation_pressure' => $tire["inflation_pressure"],'max_wear_for_retorquing' => $tire["max_wear_for_retorquing"], 'observation_information'=>$tire['observation_information'], 'state'=>$tire['state'], 'tire_reference'=>$tire['tire_reference'], 'mant_vehicle_fuels_id' => $tire['mant_vehicle_fuels_id'] ?? null, 'mant_set_tires_id'=>$tire['mant_set_tires_id'] ?? null];
                // Inicia la transaccion
                DB::beginTransaction();
                try {
                    // Inserta el registro en la base de datos
                    $TireInformations = $this->TireInformationsRepository->create($input);

                    $tireData = TireInformations::where('mant_resume_machinery_vehicles_yellow_id', $tire['vehiculo'] ? $tire['vehiculo']['mant_resume_machinery_vehicles_yellow_id'] : $tire['mant_resume_machinery_vehicles_yellow_id'])->count();
                    

                    $ResumeMachineryVehiclesYellow = ResumeMachineryVehiclesYellow::find($tire['vehiculo'] ? $tire['vehiculo']['mant_resume_machinery_vehicles_yellow_id'] : $tire['mant_resume_machinery_vehicles_yellow_id'])->update([
                        'number_tires' => $tireData
                    ]);

                    //Almacena un nuevo dato en el historial
                    $history=new tireInformationHistory();
                    $history->mant_tire_informations_id=$TireInformations['id'];
                    $history->mant_resume_machinery_vehicles_yellow_id=$tire['vehiculo'] ? $tire['vehiculo']['mant_resume_machinery_vehicles_yellow_id'] : $tire['mant_resume_machinery_vehicles_yellow_id'];
                    $history->users_id=$user->id;
                    $history->user_name=$user->name;
                    $history->action="Crear";
                    $history->plaque=$input['plaque'];
                    $history->dependencia=$input['name_dependencias'];
                    $history->descripcion= 'Se crea el registro';
                    $history->assignment_type = $tire['assignment_type'];
                    $history->code = $input['code_tire'];
                    $history->position = $input['position_tire'];
                    $history->status = "Activo";
                    
                    $history->save();


                    $TireInformations->ResumeMachineryVehiclesYellow;
        
                    // Efectua los cambios realizados
                    DB::commit();
        
                    return $this->sendResponse($TireInformations->toArray(), trans('msg_success_save'));
                } catch (\Illuminate\Database\QueryException $error) {
                    // Devuelve los cambios realizados
                    DB::rollback();
                    // Inserta el error en el registro de log
                    $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireInformationsController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
                    // Retorna mensaje de error de base de datos
                    return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
                } catch (\Exception $e) {
                    // Devuelve los cambios realizados
                    DB::rollback();
                    // Inserta el error en el registro de log
                    $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireInformationsController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
                    // Retorna error de tipo logico
                    return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
                }
            }else{
                return $this->sendSuccess('Ya hay una llanta registrada con el mismo codigo.', 'error');
            }
        }
       


    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTireInformationsRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        //Recupera el usuario en sesion
        $user=Auth::user();

        //Asigna lo traido por el Request a la variable input
        $tire = $request->all();

        //Valida si la llanta estan en estado activo o en el almacen
        if ($tire['assignment_type'] == 'Almacén') {

            //Consultaa la configuracion de llanta
                $tireReference = TireReferences::where('id',$tire['mant_set_tires_id'])->get()->first()->toArray();

                //Asigna el nombre de la referencia al campó para almacenarlo
                $tire['tire_reference'] = $tireReference['name'];

                $infoTire = ['assignment_type'=>$tire['assignment_type'],'mant_set_tires_id'=>$tire['mant_set_tires_id'],'tire_reference'=>$tire['tire_reference'], 'date_register'=>$tire['date_register'],'depth_tire'=>$tire['depth_tire'],'reference_name' => $tire["reference_name"],'tire_brand'  =>$tire["tire_brand"],'mileage_initial'=>$tire['mileage_initial'], 'available_depth'=>'', 'plaque'=>' ', 'cost_tire'=>$tire['cost_tire'],'position_tire'=>$tire['position_tire'] ?? null, 'tire_reference'=>$tireReference['name']];
            
                /** @var TireInformations $TireInformations */
                $TireInformations = $this->TireInformationsRepository->find($id);            
                if (empty($TireInformations)) {
                    return $this->sendError(trans('not_found_element'), 200);
                }
        
                // Inicia la transaccion
                DB::beginTransaction();
                try {

                    if (array_key_exists('mant_resume_machinery_vehicles_yellow_id',$tire)) {
                        $tireData = TireInformations::where('mant_resume_machinery_vehicles_yellow_id', $tire['vehiculo'] ? $tire['vehiculo']['mant_resume_machinery_vehicles_yellow_id'] : $tire['mant_resume_machinery_vehicles_yellow_id'])->count();
                                 
                        $tiresTotal = $tireData - 1;

                        $ResumeMachineryVehiclesYellow = ResumeMachineryVehiclesYellow::find($tire['vehiculo'] ? $tire['vehiculo']['mant_resume_machinery_vehicles_yellow_id'] : $tire['mant_resume_machinery_vehicles_yellow_id'])->update([
                            'number_tires' => $tiresTotal
                        ]);
                    }

                    
                    $infoTire['plaque'] = '';

                    $infoTire['name_machinery'] = '';

                    $infoTire['mant_resume_machinery_vehicles_yellow_id'] = Null;

                    $infoTire['dependencias_id'] = '';

                    // Actualiza el registro
                    $TireInformations = $this->TireInformationsRepository->update($infoTire, $id);
                    
                    //Valida si el campo descriptionDelete viene en el array
                    if($tire['descriptionDelete']){
                        //Almacena un nuevo dato en el historial
                        $history=new tireInformationHistory();
                        $history->mant_tire_informations_id=$id;
                        $history->users_id=$user->id;
                        $history->user_name=$user->name;
                        $history->action="Editar";
                        $history->descripcion= 'Se Actualiza el registro';
                        $history->observation= $tire['descriptionDelete'];
                        $history->assignment_type = $tire['assignment_type'];
                        $history->status = "Activo";
                        
                        $history->save();

                    }

                    $TireInformations->SetTire;
 
                    // Efectua los cambios realizados
                    DB::commit();
                    // $TireInformations->SetTire
                    // dd($TireInformations->toArray());
                    return $this->sendResponse($TireInformations->toArray(), trans('msg_success_update'));
                } catch (\Illuminate\Database\QueryException $error) {
                    // Devuelve los cambios realizados
                    DB::rollback();
                    // Inserta el error en el registro de log
                    $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireInformationsController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
                    // Retorna mensaje de error de base de datos
                    return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
                } catch (\Exception $e) {
                    // Devuelve los cambios realizados
                    DB::rollback();
                    // Inserta el error en el registro de log
                    $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireInformationsController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
                    // Retorna error de tipo logico
                    return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
                }
        //Si el llanta esta en estado activo
        }else{

            $machinery = ResumeMachineryVehiclesYellow::where('id', '=', $tire['vehiculo'] ? $tire['vehiculo']['mant_resume_machinery_vehicles_yellow_id'] : $tire['mant_resume_machinery_vehicles_yellow_id'])->get()->first()->toArray();

            $tireReference = TireReferences::where('id',$tire['mant_set_tires_id'])->get()->first()->toArray();
            
            //Consulta el registro actual
            $currentTire = TireInformations::where('id',$tire['id'])->first()->toArray();

            $kilometrajeRodamiento = 0;

            //Calcula el kilometraje de rodamiento 
             //Calcula el kilometraje de rodamiento 
             if (array_key_exists('kilometraje_rodamiento',$currentTire)) {
                if ($currentTire['kilometraje_rodamiento'] != 0) {
                    $newDate = new DateTime($tire['date_assignment']);

                    $kilometrajeRodamiento = $currentTire['kilometraje_rodamiento'];
    
                    $historyMilage = new TireHistoryMileage();
                    $historyMilage->mant_tire_informations_id = $tire['id'] ?? null;
                    $historyMilage->mant_vehicle_fuels_id = $tire['mant_vehicle_fuels_id'] ?? null;
                    $historyMilage->mant_tire_wears_id = null;
                    $historyMilage->date_assignment = $newDate->format('Y-m-d')  ?? null;
                    $historyMilage->plaque = $machinery['plaque'] ?? null;
                    $historyMilage->mileage_initial = $tire['mileage_initial'] ?? null;
                    $historyMilage->revision_date = null;
                    $historyMilage->revision_mileage = null;
                    $historyMilage->route_total = null;
                    $historyMilage->kilometraje_rodamiento = $kilometrajeRodamiento ?? 0;
                    $historyMilage->save();
    
                }



            }

            //Valida si la consulta ya tiene un vehiculo asociado
            if ($currentTire['mant_resume_machinery_vehicles_yellow_id']) {
                
                //Valida si el vehiculo asociado es el mismo o cambio
                if ($currentTire['mant_resume_machinery_vehicles_yellow_id'] != ($tire['vehiculo'] ? $tire['vehiculo']['mant_resume_machinery_vehicles_yellow_id'] : $tire['mant_resume_machinery_vehicles_yellow_id']) || $tire['state'] == 'Dada de baja') {
                    
                
                    //Consulta la cantidad de llantas que tiene asociada este vehiculo
                    $tireData = TireInformations::where('mant_resume_machinery_vehicles_yellow_id', $currentTire['mant_resume_machinery_vehicles_yellow_id'])->count();
                    
                    //Le resta una llanta
                    $tiresTotal = $tireData - 1;

                    //Actualiza la cantidad de llantas del vehiculo
                    $ResumeMachineryVehiclesYellow = ResumeMachineryVehiclesYellow::where('id',$currentTire['mant_resume_machinery_vehicles_yellow_id'])->update([
                        'number_tires' => $tiresTotal
                    ]);

                }
            }



            //Consulta la dependencia
            $dependency = Dependency::where('id',$tire['vehiculo'] ? $tire['vehiculo']['dependencias_id'] : $tire['dependencias_id'])->get()->first();

            //Consulta si existe el codigo de la llanta
            $codeExists = TireInformations::where('code_tire', $tire['code_tire'])->where('id','!=',$id)->get()->first();

            //Valida si la consutla anterior viene vacia o con data
            if(empty($codeExists)){

                //Asigna el nombre de la referencia al campó para almacenarlo
                $tire['tire_reference'] = $tireReference['name'];
            
                /** @var TireInformations $TireInformations */
                $TireInformations = $this->TireInformationsRepository->find($id);            
                if (empty($TireInformations)) {
                    return $this->sendError(trans('not_found_element'), 200);
                }

                $infoTire = ['mant_resume_machinery_vehicles_yellow_id'=>$tire['vehiculo'] ? $tire['vehiculo']['mant_resume_machinery_vehicles_yellow_id'] : $tire['mant_resume_machinery_vehicles_yellow_id'],'name_machinery'=>$machinery['name_vehicle_machinery'] ?? null ,'name_dependencias' => $dependency['nombre'], 'dependencias_id'=>$tire['vehiculo'] ? $tire['vehiculo']['dependencias_id'] : $tire['dependencias_id'], 'assignment_type'=>$tire['assignment_type'], 'plaque'=>$machinery['plaque'], 'date_register'=>$tire['date_register'],'date_assignment' => $tire['date_assignment'], 'position_tire'=>$tire['position_tire'], 'type_tire'=>$tire['type_tire'], 'cost_tire'=>$tire['cost_tire'], 'depth_tire'=>$tire['depth_tire'], 'mileage_initial'=>$tire['mileage_initial'],'kilometraje_rodamiento' => $kilometrajeRodamiento, 'available_depth'=>$tire['available_depth'], 'general_cost_mm'=>$tire['general_cost_mm'], 'location_tire'=>$tire['location_tire'], 'code_tire'=>$tire['code_tire'],'reference_name' => $tire["reference_name"], 'tire_brand'=>$tire['tire_brand'],'inflation_pressure' => $tire["inflation_pressure"],'max_wear_for_retorquing' => $tire["max_wear_for_retorquing"], 'observation_information'=>$tire['observation_information'], 'state'=>$tire['state'], 'tire_reference'=>$tire['tire_reference'], 'mant_vehicle_fuels_id' => $tire['mant_vehicle_fuels_id'] ?? null, 'mant_set_tires_id'=>$tire['mant_set_tires_id'] ?? null];

                // Inicia la transaccion
                DB::beginTransaction();
                try {
                    

                       // Actualiza el registro
                        $TireInformations = $this->TireInformationsRepository->update($infoTire, $id);

                        //Consulta la cantidad de llantas que tiene asociada este vehiculo
                        $tireData = TireInformations::where('mant_resume_machinery_vehicles_yellow_id', $tire['vehiculo'] ? $tire['vehiculo']['mant_resume_machinery_vehicles_yellow_id'] : $tire['mant_resume_machinery_vehicles_yellow_id'])->where('state','!=','Dada de baja')->count();
                                     
                        //Actualiza la cantidad de llantas del vehiculo
                        $ResumeMachineryVehiclesYellow = ResumeMachineryVehiclesYellow::find($tire['vehiculo'] ? $tire['vehiculo']['mant_resume_machinery_vehicles_yellow_id'] : $tire['mant_resume_machinery_vehicles_yellow_id'])->update([
                            'number_tires' => $tireData
                    ]); 
                
                    

                    
                    if($tire['descriptionDelete']){
                            //Almacena un nuevo dato en el historial
                            $history=new tireInformationHistory();
                            $history->mant_tire_informations_id=$TireInformations['id'];
                            $history->mant_resume_machinery_vehicles_yellow_id=$tire['vehiculo'] ? $tire['vehiculo']['mant_resume_machinery_vehicles_yellow_id'] : $tire['mant_resume_machinery_vehicles_yellow_id'];
                            $history->users_id=$user->id;
                            $history->user_name=$user->name;
                            $history->action="Editar";
                            $history->plaque=$tire['plaque'];
                            $history->dependencia=$infoTire['name_dependencias'];
                            $history->assignment_type = $tire['assignment_type'];
                            $history->code = $tire['code_tire'];
                            $history->position = $tire['position_tire'];
                            $history->descripcion= 'Se Actualiza el registro';
                            $history->observation = $tire['descriptionDelete'];
                            $history->status = "Activo";

                            $history->save();
                    }
                    

                    $TireInformations->SetTire;

                    $TireInformations->ResumeMachineryVehiclesYellow;
 
                    // Efectua los cambios realizados
                    DB::commit();
        
                    return $this->sendResponse($TireInformations->toArray(), trans('msg_success_update'));
                } catch (\Illuminate\Database\QueryException $error) {
                    // Devuelve los cambios realizados
                    DB::rollback();
                    // Inserta el error en el registro de log
                    $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireInformationsController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
                    // Retorna mensaje de error de base de datos
                    return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
                } catch (\Exception $e) {
                    // Devuelve los cambios realizados
                    DB::rollback();
                    // Inserta el error en el registro de log
                    $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireInformationsController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
                    // Retorna error de tipo logico
                    return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
                }
            }else{
                return $this->sendSuccess('Ya hay una llanta registrada con el mismo codigo.', 'error');
            }
        }
    }

    /**
     * Elimina un TireInformations del almacenamiento
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
    public function destroy(Request $request)
    {
                $user=Auth::user();
                
        /** @var TireInformations $TireInformations */
        $tire_informations = TireInformations::with(['TireWears'])->where('id',$request['id'])->get();

        if (empty($tire_informations[0])) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $tire = '';

        if (count($tire_informations[0]->TireWears) == 0) {


            if ($tire_informations[0]['mant_resume_machinery_vehicles_yellow_id']) {
                //Consulta la cantidad de llantas que tiene asociada este vehiculo
                $tireData = TireInformations::where('mant_resume_machinery_vehicles_yellow_id', $tire_informations[0]['mant_resume_machinery_vehicles_yellow_id'])->count();

                //Le resta una llanta
                $tiresTotal = $tireData - 1;

                //Actualiza la cantidad de llantas del vehiculo
                $ResumeMachineryVehiclesYellow = ResumeMachineryVehiclesYellow::find($tire_informations[0]['mant_resume_machinery_vehicles_yellow_id'])->update([
                    'number_tires' => $tiresTotal
                ]);

                $tire = $tire_informations[0]['mant_resume_machinery_vehicles_yellow_id'];
            }

            $history=new tireInformationHistory();
            $history->mant_tire_informations_id=$tire_informations[0]['id'];
            $history->mant_resume_machinery_vehicles_yellow_id= $tire;
            $history->users_id=$user->id;
            $history->user_name=$user->name;
            $history->action="Eliminar";
            $history->plaque=$tire_informations[0]['plaque'];
            $history->dependencia=$tire_informations[0]['name_dependencias'];
            $history->assignment_type = $tire_informations[0]['assignment_type'];
            $history->code = $tire_informations[0]['code_tire'];
            $history->position = $tire_informations[0]['position_tire'];
            $history->descripcion= 'Se elimino el registro';
            $history->observation = $request['observationDelete'];
            $history->status = "Activo";

            $history->save();


            // Elimina el registro
            $tire_informations[0]->delete();
        }else{

            return $this->sendSuccess('Esta llanta tiene desgastes registrados, si desea eliminarla por favor diríjase a los desgastes y elimine los registros existentes.', 'error');

        }  

            // Efectua los cambios realizados

            return $this->sendResponse($tire_informations[0]->toArray(), trans('msg_success_update'));

    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Johan David Velasco Rios. - septiembre. 14 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request)
    {
        $input = $request->toArray();

        $filtros_new = $this->removeExistsAfterCondition($input['filtros']);

        if(array_key_exists("filtros", $input)) {

            $input["data"] = TireInformations::with(['ResumeMachineryVehiclesYellow','TireWears', 'excel', 'TireHistories','TireHistoryMileage','VehiclesFuels'])->where('assignment_type', $input['filter'])->whereRaw($filtros_new)->latest()->get()->toArray();
            
        }else{
            $input["data"] = TireInformations::with(['ResumeMachineryVehiclesYellow','TireWears', 'excel', 'TireHistories','TireHistoryMileage','VehiclesFuels'])->where('assignment_type', $input['filter'])->latest()->get()->toArray();
        }

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // $fileName = date('Y-m-d H:i:s').'-'.trans('tireBrand').'.'.$fileType;
        $fileName = 'excel.' . $fileType;


        if ($request['filter'] == 'Activo') {
            return Excel::download(new ResquestExportTires('maintenance::tire_informations.report_excel_activo', JwtController::generateToken($input['data']), 'z'), $fileName);
        }else{
            return Excel::download(new ResquestExportTires('maintenance::tire_informations.report_excel_almacen', JwtController::generateToken($input['data']), 'f'), $fileName);
        }
        
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
        // Expresión regular para encontrar las condiciones `exists_after LIKE '%TRUE%'`, `exists_after LIKE '%FALSE%'`, `total_consume_fuel LIKE '%[cualquier número]%'`, `assignment_type LIKE '%ACTIVO%'` y `assignment_type LIKE '%ALMACEN%'`
        $pattern = "/\b(exists_after\s+LIKE\s+'%(TRUE|FALSE)%'|total_consume_fuel\s+LIKE\s+'%[^']+%'|assignment_type\s+LIKE\s+'%(ACTIVO|ALMACEN)%')\s*(AND\s*)?/i";
    
        // Reemplaza las condiciones encontradas por una cadena vacía
        $cleanedQuery = preg_replace($pattern, '', $query);
    
        // Elimina cualquier "AND" colgante al final de la consulta
        $cleanedQuery = preg_replace("/\s+AND\s*$/i", '', $cleanedQuery);
    
        if (empty(trim($cleanedQuery))) {
            $cleanedQuery = "1=1";
        }
    
        return $cleanedQuery;
    }

/**
 * Modifica una cláusula de consulta que contiene una condición `LIKE` en la cadena de filtro, 
 * reemplazándola por una comparación utilizando el operador especificado.
 *
 * @param string $filtro  La cadena de filtro que contiene la consulta a modificar. 
 *                        Debe incluir una cláusula que utilice el operador `LIKE` y el campo a modificar.
 * @param string $campo   El campo de la base de datos sobre el cual se realiza la búsqueda con `LIKE`. 
 *                        Este será el campo que se reemplazará en la consulta.
 * @param string $simbolo El nuevo operador que se utilizará para reemplazar `LIKE`. 
 *                        Puede ser un operador como '=', '!=', '>', etc.
 *
 * @return string         Devuelve la cadena de filtro modificada con la nueva condición.
 *
 */
    public function changeQueryOperator(string $filtro ,string $campo, string $simbolo): string {
        if (strpos($filtro, $campo ) !== false) {
        
            // Utilizamos una expresión regular para encontrar la parte que necesitas
            $pattern = "/".$campo." LIKE '%(\d+)%'/";
            
            // Reemplazamos el patrón encontrado por la nueva estructura
            $replacement = $campo ." ". $simbolo ." '$1'";
            $filtro = preg_replace($pattern, $replacement, $filtro);
            
            return $filtro;
            
        }else {
            return $filtro;
        }
    }
}
