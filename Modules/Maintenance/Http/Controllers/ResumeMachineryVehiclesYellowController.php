<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateResumeMachineryVehiclesYellowRequest;
use Modules\Maintenance\Http\Requests\UpdateResumeMachineryVehiclesYellowRequest;
use Modules\Maintenance\Repositories\ResumeMachineryVehiclesYellowRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\Maintenance\RequestExport;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use App\User;
use Auth;
use App\Http\Controllers\JwtController;
use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;
use Modules\Maintenance\Models\TireReferences;
use Modules\Maintenance\Models\ResumeEquipmentMachinery;
use Modules\Maintenance\Models\ResumeEquipmentMachineryLeca;
use Modules\Maintenance\Models\ResumeEquipmentLeca;
use Modules\Maintenance\Models\ResumeInventoryLeca;
use Modules\Maintenance\Models\AssetType;
use Modules\Maintenance\Models\TireQuantitites;
use Modules\Maintenance\Models\TireInformations;
use Modules\Maintenance\Models\SnActivesHeading;
use Illuminate\Support\Facades\DB;
use Modules\Maintenance\Models\RequestNeed;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\Crypt;
use Modules\Maintenance\Models\AssetManagement;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Modules\Maintenance\Models\RequestNeedItem;
use Illuminate\Support\Facades\Storage;
/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class ResumeMachineryVehiclesYellowController extends AppBaseController {

    /** @var  ResumeMachineryVehiclesYellowRepository */
    private $resumeMachineryVehiclesYellowRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(ResumeMachineryVehiclesYellowRepository $resumeMachineryVehiclesYellowRepo) {
        $this->resumeMachineryVehiclesYellowRepository = $resumeMachineryVehiclesYellowRepo;
    }

    /**
     * Muestra la vista para el CRUD de ResumeMachineryVehiclesYellow.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $resume_machinery_vehicles_yellows2 = AssetType::latest()->get()->toArray();

        $data = [];
        $user=Auth::user();
        if(Auth::user()->hasRole(['mant Consulta proceso','mant Operador líder'])){
            foreach ($resume_machinery_vehicles_yellows2 as $key => $value) {
                $count1 = ResumeMachineryVehiclesYellow::where('mant_asset_type_id',$value['id'])->where('dependencias_id', $user->id_dependencia)->count();
                $count2 = ResumeEquipmentMachinery::where('mant_asset_type_id',$value['id'])->where('dependencias_id', $user->id_dependencia)->count();
                $count3 = ResumeEquipmentMachineryLeca::where('mant_asset_type_id',$value['id'])->where('dependencias_id', $user->id_dependencia)->count();
                $count4 = ResumeEquipmentLeca::where('mant_asset_type_id',$value['id'])->where('dependencias_id', $user->id_dependencia)->count();
                $count5 = ResumeInventoryLeca::where('mant_asset_type_id',$value['id'])->where('dependencias_id', $user->id_dependencia)->count();
    
                $total = $count1+$count2+$count3+$count4+$count5;
    
                $resume_machinery_vehicles_yellows2[$key]['total_registro'] = $total;
    
            }
        }else {
            foreach ($resume_machinery_vehicles_yellows2 as $key => $value) {
                $count1 = ResumeMachineryVehiclesYellow::where('mant_asset_type_id',$value['id'])->count();
                $count2 = ResumeEquipmentMachinery::where('mant_asset_type_id',$value['id'])->count();
                $count3 = ResumeEquipmentMachineryLeca::where('mant_asset_type_id',$value['id'])->count();
                $count4 = ResumeEquipmentLeca::where('mant_asset_type_id',$value['id'])->count();
                $count5 = ResumeInventoryLeca::where('mant_asset_type_id',$value['id'])->count();
    
                $total = $count1+$count2+$count3+$count4+$count5;
    
                $resume_machinery_vehicles_yellows2[$key]['total_registro'] = $total;
    
            }
        }
        
        

        // dd($resume_machinery_vehicles_yellows2->toArray());
        return view('maintenance::resume_machinery_vehicles_yellows.index')->with('consolidatedRequestBoard', $resume_machinery_vehicles_yellows2);
    }

    

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        // dependencias_id

        $activosCount = 0;
        $activos = collect();
        //Recupera el usuario en sesion
        $user=Auth::user();

        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {

            //Envia los filtros a la funcion para tratar los datos
            $filtros = $this->removeExistsAfterCondition(base64_decode($request['f']));

            //Valida si el usuario tiene el rol de mant Consulta proceso
            if(Auth::user()->hasRole(['mant Consulta proceso','mant Operador líder'])){
                    
                //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
                if ($filtros['ResumeMachineryVehiclesYellow'] != 'NA') {
                    // Obtén los datos de ResumeMachineryVehiclesYellow como objeto y asigna a $activos
                    $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::with([
                        "provider", 
                        "mantCategory",
                        "assetType", 
                        "dependencies", 
                        "mantDocumentsAsset",
                        "TireInformations"
                    ])->where('dependencias_id', $user->id_dependencia)->whereRaw($filtros['ResumeMachineryVehiclesYellow'])->latest()->get();

                    //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                    if(empty($activos)){
                        $activos = $resume_machinery_vehicles_yellows;
                    //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                    }else{
                        $activos = $activos->concat($resume_machinery_vehicles_yellows);
                    }
                }

                //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
                if ($filtros['ResumeEquipmentMachinery'] != 'NA') {
                    // Obtén los datos de ResumeEquipmentMachinery y agrégalos a $activos
                    $resume_equipment_machinery = ResumeEquipmentMachinery::with([
                        "provider", 
                        "mantCategory", 
                        "dependencies", 
                        "characteristicsEquipment",
                        "assetType", 
                        "mantDocumentsAsset"
                    ])->where('dependencias_id', $user->id_dependencia)->whereRaw($filtros['ResumeEquipmentMachinery'])->latest()->get();

                    //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                    if(empty($activos)){
                        $activos = $resume_equipment_machinery;
                    //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                    }else{
                        $activos = $activos->concat($resume_equipment_machinery);
                    }
                }

                //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
                if ($filtros['ResumeEquipmentMachineryLeca'] != 'NA') {
                    // Obtén los datos de ResumeEquipmentMachineryLeca y agrégalos a $activos
                    $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::with([
                        "provider", 
                        "mantCategory", 
                        "dependencies", 
                        "compositionEquipmentLeca",
                        "assetType", 
                        "maintenanceEquipmentLeca"
                    ])->where('dependencias_id', $user->id_dependencia)->whereRaw($filtros['ResumeEquipmentMachineryLeca'])->latest()->get();

                    //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                    if(empty($activos)){
                        $activos = $resume_equipment_machinery_lecas;
                    //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                    }else{
                        $activos = $activos->concat($resume_equipment_machinery_lecas);
                    }
                }

                //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
                if ($filtros['ResumeEquipmentLeca'] != 'NA') {
                    // Obtén los datos de ResumeEquipmentLeca y agrégalos a $activos
                    $resume_equipment_lecas = ResumeEquipmentLeca::with([
                        "provider", 
                        "mantCategory", 
                        "dependencies", 
                        "specificationsEquipmentLeca", 
                        "mantDocumentsAsset",
                        "assetType"
                    ])->where('dependencias_id', $user->id_dependencia)->whereRaw($filtros['ResumeEquipmentLeca'])->latest()->get();

                    //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                    if(empty($activos)){
                        $activos = $resume_equipment_lecas;
                    //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                    }else{
                        $activos = $activos->concat($resume_equipment_lecas);
                    }
                }

                //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
                if ($filtros['ResumeInventoryLeca'] != 'NA') {
                    // Obtén los datos de ResumeInventoryLeca y agrégalos a $activos
                    $resume_inventory_lecas = ResumeInventoryLeca::with([
                        "provider", 
                        "mantCategory", 
                        "scheduleInventoryLeca", 
                        "mantDocumentsAsset",
                        "assetType"
                    ])->where('dependencias_id', $user->id_dependencia)->whereRaw($filtros['ResumeInventoryLeca'])->latest()->get();

                    //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                    if(empty($activos)){
                        $activos = $resume_inventory_lecas;
                    //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                    }else{
                        $activos = $activos->concat($resume_inventory_lecas);
                    }
                }

                $activosRetorno = $activos->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->values()->toArray();

                $activosCount = $activos->count();

            }else{

                //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
                if ($filtros['ResumeMachineryVehiclesYellow'] != 'NA') {
                    $filtros['ResumeMachineryVehiclesYellow'] = preg_replace("/AND serie LIKE '%[^']*%'/i", "", $filtros['ResumeMachineryVehiclesYellow']);
                    // Obtén los datos de ResumeMachineryVehiclesYellow como objeto y asigna a $activos
                    $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::with([
                        "provider", 
                        "mantCategory",
                        "assetType", 
                        "dependencies", 
                        "mantDocumentsAsset",
                        "TireInformations"
                    ])->whereRaw($filtros['ResumeMachineryVehiclesYellow'])->latest()->get();

                    //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                    if(empty($activos)){
                        $activos = $resume_machinery_vehicles_yellows;
                    //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                    }else{
                        $activos = $activos->concat($resume_machinery_vehicles_yellows);
                    }
                }

                //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
                if ($filtros['ResumeEquipmentMachinery'] != 'NA') {
                    // Obtén los datos de ResumeEquipmentMachinery y agrégalos a $activos
                    $resume_equipment_machinery = ResumeEquipmentMachinery::with([
                        "provider", 
                        "mantCategory", 
                        "dependencies", 
                        "characteristicsEquipment",
                        "assetType", 
                        "mantDocumentsAsset"
                    ])->whereRaw($filtros['ResumeEquipmentMachinery'])->latest()->get();

                    //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                    if(empty($activos)){
                        $activos = $resume_equipment_machinery;
                    //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                    }else{
                        $activos = $activos->concat($resume_equipment_machinery);
                    }
                }

                //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
                if ($filtros['ResumeEquipmentMachineryLeca'] != 'NA') {
                    // Obtén los datos de ResumeEquipmentMachineryLeca y agrégalos a $activos
                    $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::with([
                        "provider", 
                        "mantCategory", 
                        "dependencies", 
                        "compositionEquipmentLeca",
                        "assetType", 
                        "maintenanceEquipmentLeca", 
                        "mantDocumentsAsset"
                    ])->whereRaw($filtros['ResumeEquipmentMachineryLeca'])->latest()->get();

                    //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                    if(empty($activos)){
                        $activos = $resume_equipment_machinery_lecas;
                    //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                    }else{
                        $activos = $activos->concat($resume_equipment_machinery_lecas);
                    }
                }

                //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
                if ($filtros['ResumeEquipmentLeca'] != 'NA') {
                    // Obtén los datos de ResumeEquipmentLeca y agrégalos a $activos
                    $resume_equipment_lecas = ResumeEquipmentLeca::with([
                        "provider", 
                        "mantCategory", 
                        "dependencies", 
                        "specificationsEquipmentLeca", 
                        "mantDocumentsAsset",
                        "assetType"
                    ])->whereRaw($filtros['ResumeEquipmentLeca'])->latest()->get();

                    //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                    if(empty($activos)){
                        $activos = $resume_equipment_lecas;
                    //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                    }else{
                        $activos = $activos->concat($resume_equipment_lecas);
                    }
                }

                //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
                if ($filtros['ResumeInventoryLeca'] != 'NA') {
                    $filtros['ResumeInventoryLeca'] = preg_replace("/AND serie LIKE '%[^']*%'/i", "", $filtros['ResumeInventoryLeca']);
                    // Obtén los datos de ResumeInventoryLeca y agrégalos a $activos
                    $resume_inventory_lecas = ResumeInventoryLeca::with([
                        "provider", 
                        "mantCategory", 
                        "scheduleInventoryLeca", 
                        "mantDocumentsAsset",
                        "assetType"
                    ])->whereRaw($filtros['ResumeInventoryLeca'])->latest()->get();

                    //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                    if(empty($activos)){
                        $activos = $resume_inventory_lecas;
                    //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                    }else{
                        $activos = $activos->concat($resume_inventory_lecas);
                    }
                }

                // dd($activos);
                $activosRetorno = $activos->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->values()->toArray();

                $activosCount = $activos->count();
            }
        } else if(isset($request["cp"]) && isset($request["pi"])) {

            if(Auth::user()->hasRole(['mant Consulta proceso','mant Operador líder'])){
                    
                // Obtén los datos de ResumeMachineryVehiclesYellow como objeto y asigna a $activos
                $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::with([
                    "provider", 
                    "mantCategory",
                    "assetType", 
                    "dependencies", 
                    "mantDocumentsAsset",
                    "TireInformations"
                ])->where('dependencias_id', $user->id_dependencia)->latest()->get();

                $activos = $resume_machinery_vehicles_yellows;

                // Obtén los datos de ResumeEquipmentMachinery y agrégalos a $activos
                $resume_equipment_machinery = ResumeEquipmentMachinery::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "characteristicsEquipment",
                    "assetType", 
                    "mantDocumentsAsset"
                ])->where('dependencias_id', $user->id_dependencia)->latest()->get();

                $activos = $activos->concat($resume_equipment_machinery);

                // Obtén los datos de ResumeEquipmentMachineryLeca y agrégalos a $activos
                $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "compositionEquipmentLeca",
                    "assetType", 
                    "maintenanceEquipmentLeca", 
                    "mantDocumentsAsset"
                ])->where('dependencias_id', $user->id_dependencia)->latest()->get();

                $activos = $activos->concat($resume_equipment_machinery_lecas);

                // Obtén los datos de ResumeEquipmentLeca y agrégalos a $activos
                $resume_equipment_lecas = ResumeEquipmentLeca::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "specificationsEquipmentLeca", 
                    "mantDocumentsAsset",
                    "assetType"
                ])->where('dependencias_id', $user->id_dependencia)->latest()->get();

                $activos = $activos->concat($resume_equipment_lecas);

                // Obtén los datos de ResumeInventoryLeca y agrégalos a $activos
                $resume_inventory_lecas = ResumeInventoryLeca::with([
                    "provider", 
                    "mantCategory", 
                    "scheduleInventoryLeca", 
                    "mantDocumentsAsset",
                    "assetType"
                ])->where('dependencias_id', $user->id_dependencia)->latest()->get();

                $activos = $activos->concat($resume_inventory_lecas);

                $activosRetorno = $activos->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->values()->toArray();

                $activosCount = $activos->count();

            }else{

                // Obtén los datos de ResumeMachineryVehiclesYellow como objeto y asigna a $activos
                $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::with([
                    "provider", 
                    "mantCategory",
                    "assetType", 
                    "dependencies", 
                    "mantDocumentsAsset",
                    "TireInformations"
                ])->latest()->get();

                $activos = $resume_machinery_vehicles_yellows;

                // Obtén los datos de ResumeEquipmentMachinery y agrégalos a $activos
                $resume_equipment_machinery = ResumeEquipmentMachinery::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "characteristicsEquipment",
                    "assetType", 
                    "mantDocumentsAsset"
                ])->latest()->get();

                $activos = $activos->concat($resume_equipment_machinery);

                // Obtén los datos de ResumeEquipmentMachineryLeca y agrégalos a $activos
                $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "compositionEquipmentLeca",
                    "assetType", 
                    "maintenanceEquipmentLeca", 
                    "mantDocumentsAsset"
                ])->latest()->get();

                $activos = $activos->concat($resume_equipment_machinery_lecas);

                // Obtén los datos de ResumeEquipmentLeca y agrégalos a $activos
                $resume_equipment_lecas = ResumeEquipmentLeca::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "specificationsEquipmentLeca", 
                    "mantDocumentsAsset",
                    "assetType"
                ])->latest()->get();

                $activos = $activos->concat($resume_equipment_lecas);

                // Obtén los datos de ResumeInventoryLeca y agrégalos a $activos
                $resume_inventory_lecas = ResumeInventoryLeca::with([
                    "provider", 
                    "mantCategory", 
                    "scheduleInventoryLeca", 
                    "mantDocumentsAsset",
                    "assetType"
                ])->latest()->get();

                $activos = $activos->concat($resume_inventory_lecas);

                $activosRetorno = $activos->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->values()->toArray();

                $activosCount = $activos->count();
            }

        }else{
            if(Auth::user()->hasRole('mant Consulta proceso')){
                    
                // Obtén los datos de ResumeMachineryVehiclesYellow como objeto y asigna a $activos
                $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::with([
                    "provider", 
                    "mantCategory",
                    "assetType", 
                    "dependencies", 
                    "mantDocumentsAsset",
                    "TireInformations"
                ])->where('dependencias_id', $user->id_dependencia)->latest()->get();

                $activos = $resume_machinery_vehicles_yellows;

                // Obtén los datos de ResumeEquipmentMachinery y agrégalos a $activos
                $resume_equipment_machinery = ResumeEquipmentMachinery::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "characteristicsEquipment",
                    "assetType", 
                    "mantDocumentsAsset"
                ])->where('dependencias_id', $user->id_dependencia)->latest()->get();

                $activos = $activos->concat($resume_equipment_machinery);

                // Obtén los datos de ResumeEquipmentMachineryLeca y agrégalos a $activos
                $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "compositionEquipmentLeca",
                    "assetType", 
                    "maintenanceEquipmentLeca", 
                    "mantDocumentsAsset"
                ])->where('dependencias_id', $user->id_dependencia)->latest()->get();

                $activos = $activos->concat($resume_equipment_machinery_lecas);

                // Obtén los datos de ResumeEquipmentLeca y agrégalos a $activos
                $resume_equipment_lecas = ResumeEquipmentLeca::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "specificationsEquipmentLeca", 
                    "mantDocumentsAsset",
                    "assetType"
                ])->where('dependencias_id', $user->id_dependencia)->latest()->get();

                $activos = $activos->concat($resume_equipment_lecas);

                // Obtén los datos de ResumeInventoryLeca y agrégalos a $activos
                $resume_inventory_lecas = ResumeInventoryLeca::with([
                    "provider", 
                    "mantCategory", 
                    "scheduleInventoryLeca", 
                    "mantDocumentsAsset",
                    "assetType"
                ])->where('dependencias_id', $user->id_dependencia)->latest()->get();

                $activos = $activos->concat($resume_inventory_lecas);

                $activosRetorno = $activos->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->values()->toArray();

                $activosCount = $activos->count();

            }else{

                // Obtén los datos de ResumeMachineryVehiclesYellow como objeto y asigna a $activos
                $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::with([
                    "provider", 
                    "mantCategory",
                    "assetType", 
                    "dependencies", 
                    "mantDocumentsAsset",
                    "TireInformations"
                ])->latest()->get();

                $activos = $resume_machinery_vehicles_yellows;

                // Obtén los datos de ResumeEquipmentMachinery y agrégalos a $activos
                $resume_equipment_machinery = ResumeEquipmentMachinery::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "characteristicsEquipment",
                    "assetType", 
                    "mantDocumentsAsset"
                ])->latest()->get();

                $activos = $activos->concat($resume_equipment_machinery);

                // Obtén los datos de ResumeEquipmentMachineryLeca y agrégalos a $activos
                $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "compositionEquipmentLeca",
                    "assetType", 
                    "maintenanceEquipmentLeca", 
                    "mantDocumentsAsset"
                ])->latest()->get();

                $activos = $activos->concat($resume_equipment_machinery_lecas);

                // Obtén los datos de ResumeEquipmentLeca y agrégalos a $activos
                $resume_equipment_lecas = ResumeEquipmentLeca::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "specificationsEquipmentLeca", 
                    "mantDocumentsAsset",
                    "assetType"
                ])->latest()->get();

                $activos = $activos->concat($resume_equipment_lecas);

                // Obtén los datos de ResumeInventoryLeca y agrégalos a $activos
                $resume_inventory_lecas = ResumeInventoryLeca::with([
                    "provider", 
                    "mantCategory", 
                    "scheduleInventoryLeca", 
                    "mantDocumentsAsset",
                    "assetType"
                ])->latest()->get();

                $activos = $activos->concat($resume_inventory_lecas);

                $activosRetorno = $activos->toArray();

                $activosCount = $activos->count();
            }

        }

        
        return $this->sendResponseAvanzado($activosRetorno, trans('data_obtained_successfully'), null, ["total_registros" => $activosCount]);

    }

        /**
         * Limpia el query de los filtros no deseados en la variable
         *
         * @param string $query La consulta a limpiar.
         * @return string La consulta limpia.
         */
        public function removeExistsAfterCondition($query) {
            // Expresión regular para encontrar la condición `typeQuery LIKE '%DEPENDENCIAS_ID,MANT_ASSET_TYPE_ID,MANT_CATEGORY_ID%'`
            $pattern1 = "/\b(typeQuery\s+LIKE\s+'%DEPENDENCIAS_ID,MANT_ASSET_TYPE_ID,MANT_CATEGORY_ID%')\s*(AND\s*)?/i";

            // Reemplaza las condiciones encontradas por una cadena vacía
            $cleanedQuery = preg_replace($pattern1, '', $query);
            
            // Elimina cualquier "AND" colgante al final de la consulta
            $cleanedQuery = preg_replace("/\s+AND\s*$/i", '', $cleanedQuery);

            // Si la consulta está vacía después de limpiar, devuelve "1=1"
            if (empty(trim($cleanedQuery))) {
                $cleanedQuery = "1=1";
            }

            if (str_contains($cleanedQuery, 'mant_asset_type_id')) {
                $cleanedQuery = preg_replace("/mant_asset_type_id LIKE '%(\d+)%'/", "mant_asset_type_id = '$1'", $cleanedQuery);
            }

            $filtros;

            //se crea una variable por cada tabla a la cual se cosnsulta
            $ResumeMachineryVehiclesYellow = $cleanedQuery;
            $ResumeEquipmentMachinery = $cleanedQuery;
            $ResumeEquipmentMachineryLeca = $cleanedQuery;
            $ResumeEquipmentLeca = $cleanedQuery;
            $ResumeInventoryLeca = $cleanedQuery;

            //Valida si en los filtros viene el filtro de 'name_vehicle_machinery'
            if (str_contains($cleanedQuery, 'name_vehicle_machinery')) {
                $ResumeEquipmentMachinery = str_replace('name_vehicle_machinery', 'name_equipment', $ResumeEquipmentMachinery);
                $ResumeEquipmentMachineryLeca = str_replace('name_vehicle_machinery', 'name_equipment_machinery', $ResumeEquipmentMachineryLeca);
                $ResumeEquipmentLeca = str_replace('name_vehicle_machinery', 'name_equipment', $ResumeEquipmentLeca);
                $ResumeInventoryLeca = str_replace('name_vehicle_machinery', 'description_equipment_name', $ResumeInventoryLeca);
            }

            //Valida si en los filtros viene el filtro de 'consecutive', ya que algunas tablas no tienen este campo entonces no se necesita filtrar por estas
            if (str_contains($cleanedQuery, 'consecutive')) {
                $ResumeMachineryVehiclesYellow = "NA";
                $ResumeEquipmentMachineryLeca = "NA";
                $ResumeEquipmentLeca = "NA";
                $ResumeInventoryLeca = "NA";
            }

            //Valida si en los filtros viene el filtro de 'no_inventory', 
            if (str_contains($cleanedQuery, 'no_inventory')) {
                $ResumeEquipmentMachineryLeca = str_replace('no_inventory', 'no_inventory_epa_esp', $ResumeEquipmentMachineryLeca);
                $ResumeEquipmentLeca = str_replace('no_inventory', 'inventory_no', $ResumeEquipmentLeca);
                $ResumeInventoryLeca = str_replace('no_inventory', 'no_inventory_epa_esp', $ResumeInventoryLeca);
            }

            //Valida si en los filtros viene el filtro de 'mark', ya que algunas tablas no tienen este campo entonces no se necesita filtrar por estas
            if (str_contains($cleanedQuery, 'mark')) {
                $ResumeInventoryLeca = "NA";
            }

            //Valida si en los filtros viene el filtro de 'plaque', ya que algunas tablas no tienen este campo entonces no se necesita filtrar por estas
            if (str_contains($cleanedQuery, 'plaque')) {
                $ResumeEquipmentMachinery = "NA";
                $ResumeEquipmentMachineryLeca = "NA";
                $ResumeEquipmentLeca = "NA";
                $ResumeInventoryLeca = "NA";
            }

            //Valida si en los filtros viene el filtro de 'fuel_type' , ya que algunas tablas no tienen este campo entonces no se necesita filtrar por estas
            if (str_contains($cleanedQuery, 'fuel_type')) {
                $ResumeEquipmentMachineryLeca = "NA";
                $ResumeEquipmentLeca = "NA";
                $ResumeInventoryLeca = "NA";
            }


            
            $filtros = [
                'ResumeMachineryVehiclesYellow' => $ResumeMachineryVehiclesYellow,
                'ResumeEquipmentMachinery' => $ResumeEquipmentMachinery,
                'ResumeEquipmentMachineryLeca' => $ResumeEquipmentMachineryLeca,
                'ResumeEquipmentLeca' => $ResumeEquipmentLeca,
                'ResumeInventoryLeca' => $ResumeInventoryLeca
            ];
        


            return $filtros;
        }


    
    /**
     * Valida si un rubro puede ser eliminado verificando si está relacionado con una solicitud de necesidad.
     *
     * Este método recibe una solicitud HTTP con un `rubro_id` y consulta si existe una relación con la tabla
     * `RequestNeed`. Si encuentra una solicitud relacionada, retorna un mensaje indicando que no se puede eliminar
     * el rubro. En caso contrario, retorna `null` como mensaje.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud HTTP que debe contener el campo `rubro_id`.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que incluye un mensaje de validación y una descripción.
     */
    public function deleteValidateRubro(Request $request)
    {
        $input = $request->toArray();

        // Verifica si existe alguna solicitud de necesidad asociada al rubro
        $tiene_necesidad = RequestNeed::where("rubro_id", $input['rubro_id'])->first();

        if ($tiene_necesidad) {
            $mensaje = "No se puede eliminar el rubro ya que está relacionado a la solicitud de necesidad: <br><strong>" . $tiene_necesidad['consecutivo'] . "</strong>";
        } else {
            $mensaje = null;
        }

        return $this->sendResponse($mensaje, "Consulta del uso del rubro");
    }
    

    /**
     * Inserta una imagen en un rango combinado de celdas con padding.
     *
     * @param string $imagePath Ruta completa de la imagen.
     * @param object $sheet Hoja de cálculo.
     * @param string $startCol Columna inicial (letra).
     * @param int $startRow Fila inicial.
     * @param string $endCol Columna final (letra).
     * @param int $endRow Fila final.
     * @param int $padding Padding en píxeles (opcional).
     */
    private function insertarImagenEnRango($imagePath, $sheet, $startCol, $startRow, $endCol, $endRow, $padding = 5)
    {

        // Verificar si la imagen existe en el storage
        if (!Storage::disk('public')->exists($imagePath)) {
            return; // No hacer nada si no existe la imagen
        }

        $startColIndex = Coordinate::columnIndexFromString($startCol);
        $endColIndex = Coordinate::columnIndexFromString($endCol);

        // Calcular ancho total
        $totalWidth = 0;
        for ($colIndex = $startColIndex; $colIndex <= $endColIndex; $colIndex++) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex);
            $colWidth = $sheet->getColumnDimension($colLetter)->getWidth();
            $totalWidth += $colWidth * 7; // Aproximado en píxeles
        }

        // Calcular altura total
        $totalHeight = 0;
        for ($row = $startRow; $row <= $endRow; $row++) {
            $rowHeight = $sheet->getRowDimension($row)->getRowHeight();
            if ($rowHeight === -1) {
                $rowHeight = 15; // Por defecto
            }
            $totalHeight += $rowHeight * 1.33; // Convertir puntos a píxeles
        }

        // Insertar imagen redimensionada
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath(storage_path('app/public'.'/'. $imagePath));
        $drawing->setCoordinates($startCol . $startRow);
        $drawing->setWorksheet($sheet);
        $drawing->setResizeProportional(false);
        $drawing->setWidth($totalWidth - ($padding * 2));
        $drawing->setHeight($totalHeight - ($padding * 2));
        $drawing->setOffsetX($padding);
        $drawing->setOffsetY($padding);
    }

    /**
     * Exporta la hoja de vida del activo maquinaria amarilla a un archivo Excel.
     *
     * @param request 
     */
    public function exportHojaDeVidaActivoMachinery(Request $request) {

        $input = $request->all();
        $fileType = 'Xlsx';
        $storagePath = public_path('assets/formatos/VIG-GR-R-001-maquinaria-amarilla.xlsx');
        $hoja1 = "GR-R-001";
        $hoja2 = "GR-R-001 - ANEXO";
        
        $resumeMachineryVehiclesYellows = ResumeMachineryVehiclesYellow::with([
            "provider",
            "assetType",
            "dependencies",
            "TireInformations" => function ($query) {
                $query->where('state', 'Instalada');
            },
            "mantDocumentsAsset"
        ])
        ->where('id', $input['datos'])
        ->first()
        ->toArray();

        $AssetManagement = AssetManagement::where('nombre_activo', 'LIKE', '%' . $resumeMachineryVehiclesYellows['plaque'] . '%')->get()->toArray();

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
        $reader->setIncludeCharts(true);
        $spreadsheet = $reader->load($storagePath);
        // Obtener la hoja 1 por nombre
        $sheetHoja1 = $spreadsheet->getSheetByName($hoja1);

        //Obtener la hoja 2 por nombre
        $sheetHoja2 = $spreadsheet->getSheetByName($hoja2);

        $documentos = $this->processDocumentsYellow($resumeMachineryVehiclesYellows, $sheetHoja1);
        $dataObservation = $this->processObservations($documentos);
        $this->setCellValueSafe($sheetHoja1,"!A28", implode("\n\n", $dataObservation));

        $this->fillSheetDataYellow($sheetHoja1, $resumeMachineryVehiclesYellows);

        // Llenar datos en la 2
        $this->fillSheetData2Menor($sheetHoja2, $hoja2, $resumeMachineryVehiclesYellows, $AssetManagement);
        

        // Exportar el archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="VIG-GR-R-001.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $fileType);
        $writer->setIncludeCharts(true);
        $writer->save('php://output');

    }

    protected function fillSheetDataYellow($sheet, $resumeMachinery){
        
        $this->setCellValueSafe($sheet,"!P24", $resumeMachinery['warranty_description'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!N28", $resumeMachinery['person_prepares_resume'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!N30", $resumeMachinery['person_reviewed_approved'] ?? 'N/A');

        // Estado del equipo
        $statusCell = [
            'Activo' => 'E31',
            'Inactivo' => 'H31',
            'Dado de baja' => 'K31'
        ];
        $cell = $statusCell[$resumeMachinery['status']] ?? 'E31';
        $this->setCellValueSafe($sheet,$cell, 'X');

                        // Procesar documentos e imágenes
        $this->setCellValueSafe($sheet,"!D7", $resumeMachinery['dependencies']['nombre'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!K7", $resumeMachinery['name_vehicle_machinery'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!P7", $resumeMachinery['plaque'] ?? 'N/A');

        list($year, $month, $day) = explode('-', $resumeMachinery['sheet_elaboration_date'] ?? '0000-00-00');
        $this->setCellValueSafe($sheet,"!U8", $year);
        $this->setCellValueSafe($sheet,"!V8", $month);
        $this->setCellValueSafe($sheet,"!W8", $day);

        $this->setCellValueSafe($sheet,"!D11", $resumeMachinery['model'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!G11", $resumeMachinery['plaque'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!J11", $resumeMachinery['color'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!M11", $resumeMachinery['mark'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!P11", $resumeMachinery['line'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!U11", $resumeMachinery['service_class'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!D12", $resumeMachinery['transit_license_number'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!G12", $resumeMachinery['body_type'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!M12", $resumeMachinery['number_passengers'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!U12", $resumeMachinery['fuel_type'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!D13", $resumeMachinery['no_motor'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!D13", $resumeMachinery['no_motor'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!D14", $resumeMachinery['chassis_number'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!M13", $resumeMachinery['number_batteries'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!M14", $resumeMachinery['battery_reference'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!U13", $resumeMachinery['gallon_tank_capacity'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!U15", $resumeMachinery['tons_capacity_load'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!D15", $resumeMachinery['cylinder_capacity'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!G15", "$".$resumeMachinery['purchase_price'] ?? 'N/A');

        $this->setCellValueSafe($sheet,"!D16", $resumeMachinery['provider']['name'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!H16", $resumeMachinery['provider']['type_person'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!H18", $resumeMachinery['provider']['identification'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!D19", $resumeMachinery['provider']['address'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!H19", $resumeMachinery['provider']['regime'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!D20", $resumeMachinery['provider']['municipality'] .'/'. $resumeMachinery['provider']['department'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!D21", $resumeMachinery['provider']['phone'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!D22", $resumeMachinery['provider']['mail'] ?? 'N/A');

        $this->setCellValueSafe($sheet,"!M16", $resumeMachinery['warehouse_entry_number'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!M18", $resumeMachinery['warehouse_exit_number'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!M19", $resumeMachinery['invoice_number'] ?? 'N/A');
        $this->setCellValueSafe($sheet,"!M20", $resumeMachinery['mileage_start_activities'] ?? 'N/A');

        list($yearSoat, $monthSoat, $daySoat) = explode('-', $resumeMachinery['expiration_date_soat'] ?? '0000-00-00');
        $this->setCellValueSafe($sheet,"!U17", $yearSoat);
        $this->setCellValueSafe($sheet,"!V17", $monthSoat);
        $this->setCellValueSafe($sheet,"!W17", $daySoat);

        list($yearTecno, $monthTecno, $dayTecno) = explode('-', $resumeMachinery['expiration_date_tecnomecanica'] ?? '0000-00-00');
        $this->setCellValueSafe($sheet,"!U18", $yearTecno);
        $this->setCellValueSafe($sheet,"!V18", $monthTecno);
        $this->setCellValueSafe($sheet,"!W18", $dayTecno);

        list($yearGaran, $monthGaran, $dayGaran) = explode('-', $resumeMachinery['warranty_date'] ?? '0000-00-00');
        $this->setCellValueSafe($sheet,"!U19", $yearGaran);
        $this->setCellValueSafe($sheet,"!V19", $monthGaran);
        $this->setCellValueSafe($sheet,"!W19", $dayGaran);

        list($yearEntre, $monthEntre, $dayEntre) = explode('-', $resumeMachinery['delivery_date_vehicle_by_provider'] ?? '0000-00-00');
        $this->setCellValueSafe($sheet,"!U20", $yearEntre);
        $this->setCellValueSafe($sheet,"!V20", $monthEntre);
        $this->setCellValueSafe($sheet,"!W20", $dayEntre);

        list($yearStart, $monthStart, $dayStart) = explode('-', $resumeMachinery['date_put_into_service'] ?? '0000-00-00');
        $this->setCellValueSafe($sheet,"!U21", $yearStart);
        $this->setCellValueSafe($sheet,"!V21", $monthStart);
        $this->setCellValueSafe($sheet,"!W21", $dayStart);

        list($yearExit, $monthExit, $dayExit) = explode('-', $resumeMachinery['service_retirement_date'] ?? '0000-00-00');
        $this->setCellValueSafe($sheet,"!U22", $yearExit);
        $this->setCellValueSafe($sheet,"!V22", $monthExit);
        $this->setCellValueSafe($sheet,"!W22", $dayExit);

        
        $startRow = 26; // Fila donde comienza la tabla "Llantas del vehículo"
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];

        // Insertar filas
        $numRowsToInsert = count($resumeMachinery['tire_informations']) - 2;

        for ($i = 0; $i < $numRowsToInsert; $i++) {
            $sheet->insertNewRowBefore($startRow + 1, 1);
        }

        $total = $startRow+$numRowsToInsert;

        // Aplicar estilos y combinar celdas para cada fila nueva
        for ($row = $startRow + 1; $row < $startRow + 1 + $numRowsToInsert; $row++) {
            // Combinar celdas como en la plantilla (ejemplo: A23:C23 combinadas)
            $sheet->mergeCells('A' . $row . ':C' . $row);
            $sheet->mergeCells('D' . $row . ':F' . $row);
            $sheet->mergeCells('G' . $row . ':I' . $row);
            $sheet->mergeCells('J' . $row . ':L' . $row);
            $sheet->mergeCells('M' . $row . ':O' . $row);
            
            // ... otras combinaciones necesarias

            // Aplicar estilo centrado y bordes
            foreach ($columns as $col) {
                $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($col . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            }
        }

        $sheet->mergeCells('P24:W'.$total);

        
        $starRowLlantas = 25;
        foreach ($resumeMachinery['tire_informations'] as $key => $value) {
            $this->setCellValueSafe($sheet,"!A".$starRowLlantas, $value['code_tire'] ?? 'N/A');
            $this->setCellValueSafe($sheet,"!D".$starRowLlantas, $value['tire_reference'] ?? 'N/A');            
            $this->setCellValueSafe($sheet,"!G".$starRowLlantas, $value['position_tire'] ?? 'N/A');
            $this->setCellValueSafe($sheet,"!J".$starRowLlantas, $value['location_tire'] ?? 'N/A');
            $this->setCellValueSafe($sheet,"!M".$starRowLlantas, $value['state'] ?? 'N/A');

            $starRowLlantas++;
        }     
        



    }

        /**
     * Funcion encargada de procesar las observaciones y documentos del equipo.
     *
     * @param Request $request Ruta completa de la imagen.
     */
    protected function processDocumentsYellow($equipment, $sheet)
    {
        $documentos = [];

        
        if (!empty($equipment['observation'])) {
            $documentos[] = [$equipment['observation']];
        }

        if (!empty($equipment['mant_documents_asset'])) {
            foreach ($equipment['mant_documents_asset'] as $value) {
                $images = explode(',', $value['url_document']);
                
                if ($value['images_equipo']) {
                    $this->insertarImagenEnRango($images[0], $sheet, 'A', 34, 'H', 45, 5);
                    if (count($images) > 1) {
                        $this->insertarImagenEnRango($images[1], $sheet, 'I', 34, 'O', 45, 5);
                    }
                    if (count($images) > 2) {
                        $this->insertarImagenEnRango($images[2], $sheet, 'P', 34, 'W', 45, 5);
                    }
                }
                $documentos[] = $images;
            }
        }
        
        return array_merge(...$documentos);
    }

    /**
     * Inserta una imagen en un rango combinado de celdas con padding.
     *
     * @param Request $request Ruta completa de la imagen.
     */
    public function exportHojaDeVidaActivo(Request $request)
    {
        $input = $request->all();
        $fileType = 'Xlsx';
        $storagePath = public_path('assets/formatos/VIG-GR-R-046-equipos-menores.xlsx');
        $hoja1 = "GR-R-046 Hoja 1";
        $hoja2 = "GR-R-046 Hoja 2";

        // Obtener datos del equipo
        $resumeEquipmentMachinery = ResumeEquipmentMachinery::with([
                'dependencies',
                'provider',
                'characteristicsEquipment' => function ($query) {
                    $query->orderBy('created_at', 'desc')->limit(3);
                },
                'mantDocumentsAsset'
            ])->where('id', $input['datos'])->first()->toArray();

            // dd($resumeEquipmentMachinery);

        if (!empty($resumeEquipmentMachinery['no_inventory'])) {
            $AssetManagement = AssetManagement::where('nombre_activo', 'LIKE', '%' . $resumeEquipmentMachinery['no_inventory'] . '%')->get()->toArray();
        }

        // Cargar el archivo Excel
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
        $reader->setIncludeCharts(true);
        $spreadsheet = $reader->load($storagePath);
        // Obtener la hoja 1 por nombre
        $sheetHoja1 = $spreadsheet->getSheetByName($hoja1);

        //Obtener la hoja 2 por nombre
        $sheetHoja2 = $spreadsheet->getSheetByName($hoja2);

        // Procesar documentos e imágenes
        $documentos = $this->processDocuments($resumeEquipmentMachinery, $sheetHoja1);
        
        // Llenar datos en la hoja
        $this->fillSheetDataMenor($sheetHoja1, $hoja1, $resumeEquipmentMachinery);
        
        $this->fillSheetData2Menor($sheetHoja2, $hoja2, $resumeEquipmentMachinery, $AssetManagement ?? null);
        // Llenar datos en la 2

        // Procesar observaciones
        $dataObservation = $this->processObservations($documentos);
        $sheetHoja1->setCellValue($hoja1."!A45", implode("\n\n", $dataObservation));

        // Exportar el archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="VIG-GR-R-046-equipos-menores.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $fileType);
        $writer->setIncludeCharts(true);
        $writer->save('php://output');
    }

    /**
     * Funcion encargada de procesar las observaciones y documentos del equipo.
     *
     * @param Request $request Ruta completa de la imagen.
     */
    protected function processDocuments($equipment, $sheet)
    {
        $documentos = [];
        
        if (!empty($equipment['observation'])) {
            $documentos[] = [$equipment['observation']];
        }

        if (!empty($equipment['mant_documents_asset'])) {
            foreach ($equipment['mant_documents_asset'] as $value) {
                $images = explode(',', $value['url_document']);
                
                if ($value['images_equipo']) {
                    $this->insertarImagenEnRango($images[0], $sheet, 'O', 9, 'AG', 20, 5);
                    if (count($images) > 1) {
                        $this->insertarImagenEnRango($images[1], $sheet, 'O', 21, 'AG', 32, 5);
                    }
                }
                $documentos[] = $images;
            }
        }
        
        return array_merge(...$documentos);
    }

    /**
     * Funcion encargada de llenar los de la hoja 2 del equipo.
     *
     * @param Request $request Ruta completa de la imagen.
     */
    protected function fillSheetData2Menor($sheet, $hoja, $equipment, $AssetManagement)
    {
        // Datos básicos
        $this->setCellValueSafe($sheet, $hoja."!D7", $equipment['dependencies']['nombre'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!K7", $equipment['name_equipment'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!P7", $equipment['no_inventory'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!U7", 'N/A');

        $celdaIncial = 10;
        
        if (!empty($AssetManagement)) {

            foreach ($AssetManagement as $key => $value) {
    
                $explodeData =  explode(" - ", $value['nombre_proveedor'] ?? 'N/A');
    
                $nombreProvedor = count($explodeData) > 2 ? $explodeData[2] : 'No aplica';
    
                if ($value['actividad'] !== null || $value['repuesto'] !== null) {
                    if ($value['actividad'] !== null) {
                        $mantenimiento = json_decode($value['actividad'], true);
                        $mant = RequestNeedItem::where('id', $mantenimiento['id'])->get()->first();
        
                        $this->setCellValueSafe($sheet, $hoja."!E".$celdaIncial, $value['nombre_activo'] ?? 'N/A');
                        $this->setCellValueSafe($sheet, $hoja."!I".$celdaIncial, 'N/A');
        
                    }else{
                        $mantenimiento = json_decode($value['repuesto'], true);
        
                        $mant = RequestNeedItem::where('id', $mantenimiento['id'])->get()->first();
        
                        $this->setCellValueSafe($sheet, $hoja."!E".$celdaIncial,'N/A');
                        $this->setCellValueSafe($sheet, $hoja."!I".$celdaIncial, $value['nombre_activo'] ?? 'N/A');
                    }
        
                    $requestNeed = RequestNeed::where('id', $mant['mant_sn_request_id'])->with('contratoDatos')->get()->first();
        
        
                    if ($requestNeed !== null) {
                        $requestNeed = $requestNeed->toArray();
        
                        $numeroContrato = $requestNeed['contratoDatos']['numero_contrato'] ?? 'N/A';
        
        
                        $this->setCellValueSafe($sheet, $hoja."!S".$celdaIncial, $nombreProvedor." - ". $numeroContrato);
                    }
                }
    
    
                $this->setCellValueSafe($sheet, $hoja."!A".$celdaIncial, $value['created_at'] ?? 'N/A');
                $this->setCellValueSafe($sheet, $hoja."!C".$celdaIncial, $value['tipo_mantenimiento'] ?? 'N/A');
                $this->setCellValueSafe($sheet, $hoja."!L".$celdaIncial, $mant['unidad_medida'] ?? 'N/A');
                $this->setCellValueSafe($sheet, $hoja."!M".$celdaIncial, $mant['cantidad_solicitada'] ?? 'N/A');
                $this->setCellValueSafe($sheet, $hoja."!N".$celdaIncial, $mant['valor_unitario'] ?? 'N/A');
                $this->setCellValueSafe($sheet, $hoja."!O".$celdaIncial, $mant['IVA'] ?? 'N/A');
                $this->setCellValueSafe($sheet, $hoja."!P".$celdaIncial, $mant['valor_total'] ?? 'N/A');
                $this->setCellValueSafe($sheet, $hoja."!R".$celdaIncial, $value['no_solicitud'] ?? 'N/A');
    
                $celdaIncial++; 
            }  
        }
        
            
    }

    /**
     * Funcion encargada de llenar los datos en la hoja 1.
     *
     * @param Request $request Ruta completa de la imagen.
     */
    protected function fillSheetDataMenor($sheet, $hoja, $equipment)
    {
        // Datos básicos
        $this->setCellValueSafe($sheet, $hoja."!E8", $equipment['dependencies']['nombre'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!H9", $equipment['name_equipment'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!F10", $equipment['no_identification'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!M10", $equipment['no_inventory'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!C11", $equipment['mark'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!M11", $equipment['serie'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!C12", $equipment['model'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!I12", $equipment['ubication'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!N12", $equipment['consecutive'] ?? 'N/A');
        
        // Datos financieros/proveedor
        $this->setCellValueSafe($sheet, $hoja."!F14", $equipment['purchase_price'] ? "$".$equipment['purchase_price'] : 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!F15", $equipment['no_invoice'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!F16", $equipment['warehouse_entry_number'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!F17", $equipment['type_number_the_acquisition_contract'] ?? 'N/A');
        
        // Datos del proveedor
        if (isset($equipment['provider'])) {
            $provider = $equipment['provider'];
            $this->setCellValueSafe($sheet, $hoja."!F19", $provider['name'] ?? 'N/A');
            $this->setCellValueSafe($sheet, $hoja."!L19", $provider['identification'] ?? 'N/A');
            $this->setCellValueSafe($sheet, $hoja."!F20", $provider['address'] ?? 'N/A');
            $this->setCellValueSafe($sheet, $hoja."!L20", ($provider['municipality'] ?? '')."/".($provider['department'] ?? ''));
            $this->setCellValueSafe($sheet, $hoja."!F21", $provider['mail'] ?? 'N/A');
            $this->setCellValueSafe($sheet, $hoja."!F22", $provider['phone'] ?? 'N/A');
            $this->setCellValueSafe($sheet, $hoja."!L22", $provider['phone'] ?? 'N/A');
        }
        
        // Características del equipo
        if (!empty($equipment['characteristics_equipment'])) {
            $row = 25;
            foreach ($equipment['characteristics_equipment'] as $value) {
                $this->setCellValueSafe($sheet, $hoja."!A".$row, $value['accessory_parts'] ?? 'N/A');
                $this->setCellValueSafe($sheet, $hoja."!G".$row, $value['amount'] ?? 'N/A');
                $this->setCellValueSafe($sheet, $hoja."!K".$row, $value['reference_part_number'] ?? 'N/A');
                $row++;
            }
        }
        
        // Requerimientos de operación
        if (!empty($equipment['requirement_for_operation'])) {
            $cellMap = [
                'Neumático' => 'C29',
                'Energía eléctrica' => 'F35',
                'Combustible' => 'I35',
                'Electrónico' => 'L35'
            ];
            
            $cell = $cellMap[$equipment['requirement_for_operation']] ?? 'N35';
            $this->setCellValueSafe($sheet, $hoja."!".$cell, 'X');
        }
        
        // Especificaciones de catálogo
        if ($equipment['catalog_specifications'] == 'Si') {
            $this->setCellValueSafe($sheet, $hoja."!D35", 'X');
            $this->setCellValueSafe($sheet, $hoja."!J35", $equipment['location'] ?? 'N/A');
        } else {
            $this->setCellValueSafe($sheet, $hoja."!F35", 'X');
        }
        
        // Fechas importantes
        $this->fillDateData($sheet, $hoja, $equipment);
        
        // Datos adicionales
        $this->setCellValueSafe($sheet, $hoja."!P38", $equipment['person_responsible_team'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!Y38", $equipment['person_prepares_resume_equipment_machinery'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!G44", $equipment['technical_verification_frequency'] ?? 'N/A');
        $this->setCellValueSafe($sheet, $hoja."!M44", $equipment['preventive_maintenance_frequency'] ?? 'N/A');


        
        // Estado del equipo
        $statusCell = [
            'Activo' => 'AB43',
            'Inactivo' => 'AB44',
            'Obsoleto o depreciado' => 'AG43'
        ];
        $cell = $statusCell[$equipment['status']] ?? 'AG44';
        $this->setCellValueSafe($sheet, $hoja."!".$cell, 'X');
    }

    /**
     * funcion encargada de llenar los datos de fecha en la hoja 1.
     *
     * @param Request $request Ruta completa de la imagen.
     */
    protected function fillDateData($sheet, $hoja, $equipment)
    {
        // Fecha de compra
        $this->fillSingleDate($sheet, $hoja, 'A40', 'B40', 'C40', $equipment['purchase_date'] ?? null);
        
        // Fecha de inicio de servicio
        $this->fillSingleDate($sheet, $hoja, 'D40', 'F40', 'H40', $equipment['service_start_date'] ?? null);
        
        // Fecha de retiro
        $this->fillSingleDate($sheet, $hoja, 'J40', 'L40', 'N40', $equipment['retirement_date'] ?? null);
    }


    /**
     * Funcion encargada de llenar una fecha en celdas separadas para año, mes y día.
     *
     * @param Request $request Ruta completa de la imagen.
     */
    protected function fillSingleDate($sheet, $hoja, $yearCell, $monthCell, $dayCell, $date)
    {
        if ($date) {
            list($year, $month, $day) = explode('-', $date);
            $this->setCellValueSafe($sheet, $hoja."!".$yearCell, $year);
            $this->setCellValueSafe($sheet, $hoja."!".$monthCell, $month);
            $this->setCellValueSafe($sheet, $hoja."!".$dayCell, $day);
        } else {
            $this->setCellValueSafe($sheet, $hoja."!".$yearCell, 'N/A');
            $this->setCellValueSafe($sheet, $hoja."!".$monthCell, 'N/A');
            $this->setCellValueSafe($sheet, $hoja."!".$dayCell, 'N/A');
        }
    }

    /**
     * funcion encargada de retornar los links de accesos a los documentos encriptados
     *
     * @param Request $request Ruta completa de la imagen.
     */
    protected function processObservations($documentos)
    {
        $dataObservation = [];
        
        foreach ($documentos as $key => $value) {
            if ($key != 0) {
                $dataObservation[] = config('app.url').'maintenance/watch-archives?document='.urlencode(Crypt::encryptString(trim($value)));
            } else {
                $dataObservation[] = $value;
            }
        }
        
        return $dataObservation;
    }

    /**
     * funcion encargada de establecer un valor en una celda de forma segura.
     *
     * @param Request $request Ruta completa de la imagen.
     */
    protected function setCellValueSafe($sheet, $cell, $value)
    {
        $sheet->setCellValue($cell, $value ?? 'N/A');
    }
   

         /**
     * Generar una URL para visualizar un documento en una nueva pestaña.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

     public function watchDocument(Request $request)
     {
         try {
             // Obtener parámetros de la solicitud
             $documentoEncriptado = $request->query('document');
             if (!empty($documentoEncriptado)) {
                $documento = Crypt::decryptString($documentoEncriptado);
                 return view('correspondence::view_document_public.index', compact(['documento']));
             }

         } catch (\Exception $e) {
             $this->generateSevenLog('correspondence', 'Modules\Maintenance\Http\Controllers\ResumeMachineryVehiclesYellowController - ' . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

         }
     }


    /**
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateResumeMachineryVehiclesYellowRequest $request
     *
     * @return Response
     */
    public function tireInformation($id)
    {
        $tireInformation = TireInformations::where('mant_resume_machinery_vehicles_yellow_id', $id)->where('state','!=','Dada de baja')->get();

        return $this->sendResponse($tireInformation->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateResumeMachineryVehiclesYellowRequest $request
     *
     * @return Response
     */
    public function store(CreateResumeMachineryVehiclesYellowRequest $request) {
        
        $input = $request->all();
        $code = 0;
        $code1 = 0;
        $code2 = 0;
        $code3 = 0;
        $code4 = 0;

        if ($request['no_inventory']) {
            $code = ResumeMachineryVehiclesYellow::where('no_inventory',$request['no_inventory'])->get()->count();
            $code1 = ResumeEquipmentLeca::where('inventory_no',$request['no_inventory'])->get()->count();
            $code2 = ResumeEquipmentMachinery::where('no_inventory',$request['no_inventory'])->get()->count();
            $code3 = ResumeInventoryLeca::where('no_inventory_epa_esp',$request['no_inventory'])->get()->count();
            $code4 = ResumeEquipmentMachineryLeca::where('no_inventory_epa_esp',$request['no_inventory'])->get()->count();
        }
        

        // $font=TireReferences::find($input['mant_tire_all_front']);
        // $rear=TireReferences::find($input['mant_tire_all_rear']);
        // $input["front_tire_reference"]=$font->name;
        // $input["rear_tire_reference"]=$rear->name;

        if ($code > 0 || $code1 > 0 || $code2 > 0 || $code3 > 0 || $code4 > 0) {
            
            return $this->sendSuccess('El número de inventario ingresado ya esta en uso', 'error');

        }else{
                    unset($input["type_person"]);
                    unset($input["document_type"]);
                    unset($input["name"]);
                    unset($input["mail"]);
                    unset($input["transit_license_number"]);
                    unset($input["phone"]);
                    unset($input["address"]);

            $resumeMachineryVehiclesYellow = $this->resumeMachineryVehiclesYellowRepository->create($input);

            // Asigna los rubros al activo
            if (array_key_exists("rubros_asignados",$input)) {
                foreach ($input["rubros_asignados"] as $json_rubro_asignado) {
                    $rubro_asignado = json_decode($json_rubro_asignado);
                    DB::table('mant_sn_actives_heading')->insert(["activo_id" => $resumeMachineryVehiclesYellow["id"], "rubro_id" => $rubro_asignado->rubro_id,"rubro_codigo" => $rubro_asignado->rubro_codigo,"centro_costo_id" => $rubro_asignado->centro_costo_id,"centro_costo_codigo" => $rubro_asignado->centro_costo_codigo,"created_at" => date("Y-m-d H:i:s"),"updated_at" => date("Y-m-d H:i:s")]);
                }
            }


            // Ejecuta el modelo de proveedor
            $resumeMachineryVehiclesYellow->provider;
            // Ejecuta el modelo de categoría
            $resumeMachineryVehiclesYellow->mantCategory;
            // Ejecuta el modelo de dependencia
            $resumeMachineryVehiclesYellow->dependencies;
            // Ejecuta el modelo de documentos adjuntos relacionados
            $resumeMachineryVehiclesYellow->mantDocumentsAsset;
    
            $resumeMachineryVehiclesYellow->assetType;
    
            return $this->sendResponse($resumeMachineryVehiclesYellow->toArray(), trans('msg_success_save'));
        }

     
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateResumeMachineryVehiclesYellowRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateResumeMachineryVehiclesYellowRequest $request) {

        $input = $request->all();
        
        $code = 0;
        $code1 = 0;
        $code2 = 0;
        $code3 = 0;
        $code4 = 0;

        if (array_key_exists("provider", $input) && ($input['mant_providers_id'] === null)) {
            $input['mant_providers_id'] = $input['provider']['id'] ?? null;
        }

        if ($request['no_inventory']) {
            $code = ResumeMachineryVehiclesYellow::where('no_inventory',$request['no_inventory'])->where('id','!=',$id)->get()->count();
            $code1 = ResumeEquipmentLeca::where('inventory_no',$request['no_inventory'])->get()->count();
            $code2 = ResumeEquipmentMachinery::where('no_inventory',$request['no_inventory'])->get()->count();
            $code3 = ResumeInventoryLeca::where('no_inventory_epa_esp',$request['no_inventory'])->get()->count();
            $code4 = ResumeEquipmentMachineryLeca::where('no_inventory_epa_esp',$request['no_inventory'])->get()->count();
        }

            /** @var ResumeMachineryVehiclesYellow $resumeMachineryVehiclesYellow */
            $resumeMachineryVehiclesYellow = $this->resumeMachineryVehiclesYellowRepository->find($id);

            if (empty($resumeMachineryVehiclesYellow)) {
                return $this->sendError(trans('not_found_element'), 200);
            }
            // $font=TireReferences::find($input['mant_tire_all_front']);
            // $rear=TireReferences::find($input['mant_tire_all_rear']);
            // $input["front_tire_reference"]=$font->name;
            // $input["rear_tire_reference"]=$rear->name;

            if ($code > 0 || $code1 > 0 || $code2 > 0 || $code3 > 0 || $code4 > 0) {

                return $this->sendSuccess('El número de inventario ingresado ya esta en uso', 'error');

            }else{
                $resumeMachineryVehiclesYellow = $this->resumeMachineryVehiclesYellowRepository->update($input, $id);
                // Ejecuta el modelo de proveedor
                $resumeMachineryVehiclesYellow->provider;
                // Ejecuta el modelo de categoría
                $resumeMachineryVehiclesYellow->mantCategory;
                // Ejecuta el modelo de dependencia
                $resumeMachineryVehiclesYellow->dependencies;
                // Ejecuta el modelo de documentos adjuntos relacionados
                $resumeMachineryVehiclesYellow->mantDocumentsAsset;

            if (array_key_exists("rubros_asignados", $input) && is_array($input["rubros_asignados"])) {
                // Obtén todos los rubros existentes para este activo
                $rubros_existentes = DB::table('mant_sn_actives_heading')
                    ->where('activo_id', $resumeMachineryVehiclesYellow["id"])
                    ->whereNull('deleted_at')
                    ->get();
                
                // Crea un array con los IDs de los rubros que vienen en el input
                $ids_en_input = [];
                foreach ($input["rubros_asignados"] as $json_rubro_asignado) {
                    $rubro_asignado = json_decode($json_rubro_asignado);
                    if ($rubro_asignado === null) {
                        $this->generateSevenLog('resume_machinary', 'Modules\Maintenance\Http\Controllers\ResumeMachineryVehiclesYellowController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: $rubro_asignado llego en null');
                        continue;
                    }

                    // Si tiene ID, añádelo al array de IDs en input
                    if(isset($rubro_asignado->id) && $rubro_asignado->id !== null && $rubro_asignado->id !== '') {
                        $ids_en_input[] = $rubro_asignado->id;
                    }

                    if(empty($rubro_asignado->centro_costo_codigo)){
                        return $this->sendSuccess(
                            '<strong>Centro de Costo Requerido</strong>' . '<br>' . 
                            'Por favor, ingrese nuevamente la asignación de los valores que no cuentan con un centro de costo asociado.',
                            'error'
                        );
                    }

                    // Procesa cada rubro como lo hacías antes
                    if(isset($rubro_asignado->id) && $rubro_asignado->id !== null && $rubro_asignado->id !== '') {
                    } else {
                        // Verificar que existan todas las propiedades necesarias
                        if (isset($rubro_asignado->rubro_id) && isset($rubro_asignado->rubro_codigo) &&
                            isset($rubro_asignado->centro_costo_codigo) && isset($rubro_asignado->centro_costo_id)) {
                            try {
                                DB::table('mant_sn_actives_heading')->insert([
                                    "activo_id" => $resumeMachineryVehiclesYellow["id"],
                                    "rubro_id" => $rubro_asignado->rubro_id,
                                    "rubro_codigo" => $rubro_asignado->rubro_codigo,
                                    "centro_costo_codigo" => $rubro_asignado->centro_costo_codigo,
                                    "centro_costo_id" => $rubro_asignado->centro_costo_id,
                                    "created_at" => date("Y-m-d H:i:s"),
                                    "updated_at" => date("Y-m-d H:i:s")
                                ]);
                            } catch (\Exception $e) {
                                $this->generateSevenLog('resume_machinary', 'Modules\Maintenance\Http\Controllers\ResumeMachineryVehiclesYellowController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). '. Linea: ' . $e->getLine());
                            }
                        }
                    }
                }

                // Ahora marca como eliminados los rubros que existen en la BD pero no en el input
                foreach ($rubros_existentes as $rubro_existente) {
                    if (!in_array($rubro_existente->id, $ids_en_input)) {
                        // Este rubro existe en la BD pero no en el input, verifica si tiene necesidad
                        $tiene_necesidad = RequestNeed::where("rubro_id", $rubro_existente->id)->count();
                        if($tiene_necesidad == 0) {
                            try {
                                DB::table('mant_sn_actives_heading')
                                    ->where('id', $rubro_existente->id)
                                    ->update(['deleted_at' => now()]);
                            } catch (\Exception $e) {
                                $this->generateSevenLog('resume_machinary', 'Modules\Maintenance\Http\Controllers\ResumeMachineryVehiclesYellowController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). '. Linea: ' . $e->getLine());
                            }
                        }
                    }
                }
            }
    
                return $this->sendResponse($resumeMachineryVehiclesYellow->toArray(), trans('msg_success_update'));
            }


    }

    /**
     * Elimina un ResumeMachineryVehiclesYellow del almacenamiento
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

        $id = explode("_", $id);

        $form_type = $id[1];
        $id = $id[0];
        
        if($form_type == 1) {
            /** @var ResumeMachineryVehiclesYellow $resumeMachineryVehiclesYellow */
            $resumeMachineryVehiclesYellow = $this->resumeMachineryVehiclesYellowRepository->find($id);

            if (empty($resumeMachineryVehiclesYellow)) {
                return $this->sendError(trans('not_found_element'), 200);
            }

            $resumeMachineryVehiclesYellow->delete();
        } else if($form_type == 2) {
            /** @var ResumeEquipmentMachinery $resumeEquipmentMachinery */
            $resumeEquipmentMachinery = ResumeEquipmentMachinery::find($id);

            if (empty($resumeEquipmentMachinery)) {
                return $this->sendError(trans('not_found_element'), 200);
            }

            $resumeEquipmentMachinery->delete();
        } else if($form_type == 3) {
            /** @var ResumeEquipmentMachineryLeca $resumeEquipmentMachineryLeca */
            $resumeEquipmentMachineryLeca = ResumeEquipmentMachineryLeca::find($id);

            if (empty($resumeEquipmentMachineryLeca)) {
                return $this->sendError(trans('not_found_element'), 200);
            }

            $resumeEquipmentMachineryLeca->delete();
        } else if($form_type == 4) {
            /** @var ResumeEquipmentLeca $resumeEquipmentLeca */
            $resumeEquipmentLeca = ResumeEquipmentLeca::find($id);

            if (empty($resumeEquipmentLeca)) {
                return $this->sendError(trans('not_found_element'), 200);
            }

            $resumeEquipmentLeca->delete();
        } else if($form_type == 5) {
            /** @var ResumeInventoryLeca $resumeInventoryLeca */
            $resumeInventoryLeca = ResumeInventoryLeca::find($id);

            if (empty($resumeInventoryLeca)) {
                return $this->sendError(trans('not_found_element'), 200);
            }

            $resumeInventoryLeca->delete();
        }

        return $this->sendSuccess(trans('msg_success_drop'));

    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    /*public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('resume_machinery_vehicles_yellows').'.'.$fileType;

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

    }*/

    /**
     * Genera el reporte de activos en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - jun. 04 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        $filtros = $this->removeExistsAfterCondition($input['filtros']);

        //Valida si el usuario tiene el rol de mant Consulta proceso
        if(Auth::user()->hasRole('mant Consulta proceso')){
                    
            //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
            if ($filtros['ResumeMachineryVehiclesYellow'] != 'NA') {
                // Obtén los datos de ResumeMachineryVehiclesYellow como objeto y asigna a $activos
                $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::with([
                    "provider", 
                    "mantCategory",
                    "assetType", 
                    "dependencies", 
                    "mantDocumentsAsset",
                    "TireInformations"
                ])->where('dependencias_id', $user->id_dependencia)->whereRaw($filtros['ResumeMachineryVehiclesYellow'])->latest()->get();

                //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                if(empty($activos)){
                    $activos = $resume_machinery_vehicles_yellows;
                //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                }else{
                    $activos = $activos->concat($resume_machinery_vehicles_yellows);
                }
            }

            //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
            if ($filtros['ResumeEquipmentMachinery'] != 'NA') {
                // Obtén los datos de ResumeEquipmentMachinery y agrégalos a $activos
                $resume_equipment_machinery = ResumeEquipmentMachinery::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "characteristicsEquipment",
                    "assetType", 
                    "mantDocumentsAsset"
                ])->where('dependencias_id', $user->id_dependencia)->whereRaw($filtros['ResumeEquipmentMachinery'])->latest()->get();

                //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                if(empty($activos)){
                    $activos = $resume_equipment_machinery;
                //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                }else{
                    $activos = $activos->concat($resume_equipment_machinery);
                }
            }

            //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
            if ($filtros['ResumeEquipmentMachineryLeca'] != 'NA') {
                // Obtén los datos de ResumeEquipmentMachineryLeca y agrégalos a $activos
                $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "compositionEquipmentLeca",
                    "assetType", 
                    "maintenanceEquipmentLeca", 
                    "mantDocumentsAsset"
                ])->where('dependencias_id', $user->id_dependencia)->whereRaw($filtros['ResumeEquipmentMachineryLeca'])->latest()->get();

                //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                if(empty($activos)){
                    $activos = $resume_equipment_machinery_lecas;
                //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                }else{
                    $activos = $activos->concat($resume_equipment_machinery_lecas);
                }
            }

            //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
            if ($filtros['ResumeEquipmentLeca'] != 'NA') {
                // Obtén los datos de ResumeEquipmentLeca y agrégalos a $activos
                $resume_equipment_lecas = ResumeEquipmentLeca::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "specificationsEquipmentLeca", 
                    "mantDocumentsAsset",
                    "assetType"
                ])->where('dependencias_id', $user->id_dependencia)->whereRaw($filtros['ResumeEquipmentLeca'])->latest()->get();

                //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                if(empty($activos)){
                    $activos = $resume_equipment_lecas;
                //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                }else{
                    $activos = $activos->concat($resume_equipment_lecas);
                }
            }

            //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
            if ($filtros['ResumeInventoryLeca'] != 'NA') {
                // Obtén los datos de ResumeInventoryLeca y agrégalos a $activos
                $resume_inventory_lecas = ResumeInventoryLeca::with([
                    "provider", 
                    "mantCategory", 
                    "scheduleInventoryLeca", 
                    "mantDocumentsAsset",
                    "assetType"
                ])->where('dependencias_id', $user->id_dependencia)->whereRaw($filtros['ResumeInventoryLeca'])->latest()->get();

                //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                if(empty($activos)){
                    $activos = $resume_inventory_lecas;
                //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                }else{
                    $activos = $activos->concat($resume_inventory_lecas);
                }
            }

            $input['data'] = $activos->toArray();

        }else{

            //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
            if ($filtros['ResumeMachineryVehiclesYellow'] != 'NA') {
                // Obtén los datos de ResumeMachineryVehiclesYellow como objeto y asigna a $activos
                $resume_machinery_vehicles_yellows = ResumeMachineryVehiclesYellow::with([
                    "provider", 
                    "mantCategory",
                    "assetType", 
                    "dependencies", 
                    "mantDocumentsAsset",
                    "TireInformations"
                ])->whereRaw($filtros['ResumeMachineryVehiclesYellow'])->latest()->get();

                //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                if(empty($activos)){
                    $activos = $resume_machinery_vehicles_yellows;
                //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                }else{
                    $activos = $activos->concat($resume_machinery_vehicles_yellows);
                }
            }

            //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
            if ($filtros['ResumeEquipmentMachinery'] != 'NA') {
                // Obtén los datos de ResumeEquipmentMachinery y agrégalos a $activos
                $resume_equipment_machinery = ResumeEquipmentMachinery::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "characteristicsEquipment",
                    "assetType", 
                    "mantDocumentsAsset"
                ])->whereRaw($filtros['ResumeEquipmentMachinery'])->latest()->get();

                //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                if(empty($activos)){
                    $activos = $resume_equipment_machinery;
                //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                }else{
                    $activos = $activos->concat($resume_equipment_machinery);
                }
            }

            //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
            if ($filtros['ResumeEquipmentMachineryLeca'] != 'NA') {
                // Obtén los datos de ResumeEquipmentMachineryLeca y agrégalos a $activos
                $resume_equipment_machinery_lecas = ResumeEquipmentMachineryLeca::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "compositionEquipmentLeca",
                    "assetType", 
                    "maintenanceEquipmentLeca", 
                    "mantDocumentsAsset"
                ])->whereRaw($filtros['ResumeEquipmentMachineryLeca'])->latest()->get();

                //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                if(empty($activos)){
                    $activos = $resume_equipment_machinery_lecas;
                //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                }else{
                    $activos = $activos->concat($resume_equipment_machinery_lecas);
                }
            }

            //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
            if ($filtros['ResumeEquipmentLeca'] != 'NA') {
                // Obtén los datos de ResumeEquipmentLeca y agrégalos a $activos
                $resume_equipment_lecas = ResumeEquipmentLeca::with([
                    "provider", 
                    "mantCategory", 
                    "dependencies", 
                    "specificationsEquipmentLeca", 
                    "mantDocumentsAsset",
                    "assetType"
                ])->whereRaw($filtros['ResumeEquipmentLeca'])->latest()->get();

                //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                if(empty($activos)){
                    $activos = $resume_equipment_lecas;
                //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                }else{
                    $activos = $activos->concat($resume_equipment_lecas);
                }
            }

            //Valida si el valor de los filtros es difrente de 'NA' para entrar a la consulta
            if ($filtros['ResumeInventoryLeca'] != 'NA') {
                // Obtén los datos de ResumeInventoryLeca y agrégalos a $activos
                $resume_inventory_lecas = ResumeInventoryLeca::with([
                    "provider", 
                    "mantCategory", 
                    "scheduleInventoryLeca", 
                    "mantDocumentsAsset",
                    "assetType"
                ])->whereRaw($filtros['ResumeInventoryLeca'])->latest()->get();

                //Valida si activos viene vacio, si es el caso asigna el valor de la consulta
                if(empty($activos)){
                    $activos = $resume_inventory_lecas;
                //De lo contrario que venga vacio, concatena lo obtenido en la consulta
                }else{
                    $activos = $activos->concat($resume_inventory_lecas);
                }
            }

            $input['data'] = $activos->toArray();
        }
        
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('actives').'.'.$fileType;

        return Excel::download(new RequestExport('maintenance::resume_machinery_vehicles_yellows.report_excel', JwtController::generateToken($input['data']), 'p'), $fileName);
    }

    /**
     * Obtiene todos los usuarios del sistema, incluyendo los eliminados
     *
     * @author German Gonzalez V. - Mar. 17 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getAllUsers($asset, $category) {
        $users = User::withTrashed()->select('users.id', 'users.name')
                        ->join('mant_asset_create_authorization', 'users.id', 'mant_asset_create_authorization.responsable')
                        ->join('mant_authorized_categories', 'mant_asset_create_authorization.id', 'mant_authorized_categories.asset_authorization_id')
                        ->where('mant_authorized_categories.mant_asset_type_id', $asset)
                        ->where('mant_authorized_categories.mant_category_id', $category)
                        ->distinct()
                        ->get();

        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    public function prueba(Request $request) {

        $path = storage_path('app/public/maintenance/documents_assets');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }


    public function sendTireReference() {

        $references=TireReferences::all();

        return $this->sendResponse($references, trans('msg_success_update'));
    }
}
