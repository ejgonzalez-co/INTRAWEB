<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\HelpTable\RequestExport;
use Modules\HelpTable\Http\Requests\CreateEquipmentResumeRequest;
use Modules\HelpTable\Http\Requests\UpdateEquipmentResumeRequest;
use Modules\HelpTable\Repositories\EquipmentResumeRepository;
use Modules\HelpTable\Models\EquipmentResume;
use Modules\HelpTable\Models\EquipmentResumeHistory;
use Modules\HelpTable\Models\EquipmentResumeBackup;
use Modules\HelpTable\Models\EquipmentPurchaseDetailsBackup;
use Modules\HelpTable\Models\EquipmentResumeOtherEquipmentBackup;
use Modules\HelpTable\Models\ConfigOtherEquipment;
use Modules\HelpTable\Models\EquipmentPurchaseDetail;
use App\Http\Controllers\AppBaseController;
use Modules\Intranet\Models\Dependency;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Cookie;
use Request as CookieRequest;
use Carbon\Carbon;

/**
 * Descripcion de la clase
 *
 * @author Kleverman Salazar Florez. - Ene. 24 - 2023
 * @version 1.0.0
 */
class EquipmentResumeController extends AppBaseController {

    /** @var  EquipmentResumeRepository */
    private $equipmentResumeRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     */
    public function __construct(EquipmentResumeRepository $equipmentResumeRepo) {
        $this->equipmentResumeRepository = $equipmentResumeRepo;
    }

    /**
     * Muestra la vista para el CRUD de EquipmentResume.
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $cookieProviderName = CookieRequest::cookie("provider_name");
        if(Auth::user() != null){
            if(Auth::user()->hasRole("Administrador TIC")){
                return view('help_table::equipment_resumes.index');
            }
            return redirect('/login');
        }
        if($request->session()->get("is_provider")){
            $providerFullname = $request->session()->get("fullname");
            return response(view('help_table::equipment_resumes.index'))->cookie("provider_name",$providerFullname,60);
        }
        if(isset($cookieProviderName)){
            return response(view('help_table::equipment_resumes.index'))->cookie("provider_name",$cookieProviderName,60);
        }
        return redirect('/login/help-table/providers');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        $status_equipment = [];
        $status_asset_type = [];
        $status_data = EquipmentResume::select('status_equipment', DB::raw('COUNT(*) as total'))
        ->groupBy('status_equipment')
        ->get()->toArray();;


        $asset_data = EquipmentResume::select('asset_type', DB::raw('COUNT(*) as total'))
        ->groupBy('asset_type')
        ->get()->toArray();;
        // dd($status_data->toArray(),$asset_data->toArray());
        $status_equipment = ["Activo","Desactivado","En reparación","Dado de Baja"];
        $status_equipment_to_remplace = ["activo","desactivado","en_reparacion","dado_de_baja"];

        $asset_types = ["Computador","Portátil","Servidor"];

        foreach ($status_data as $key => $status) {
            $modified_status_data[str_replace($status_equipment, $status_equipment_to_remplace, $status["status_equipment"])] = $status["total"];
        }
        $status_equipment = $modified_status_data;

        foreach ($asset_data as $key => $asset) {
            $modified_asset_data[$asset["asset_type"]] = $asset["total"];
        }

        $status_asset_type = $modified_asset_data;
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {
            $equipment_resumes = EquipmentResume::with(['EquipmentPurchaseDetails','ConfigurationOtherEquipments','EquipmentResumeHistories','ConfigurationTowerReferences','ConfigurationTowerSize','ConfigurationTowerProcessor','ConfigurationTowerRam','ConfigurationTowerSsd','ConfigurationTowerHdd','ConfigurationTowerVideoCard','ConfigurationTowerSharedFolder','ConfigurationNetworkCard','ConfigurationTowerSharedFolder','ConfigurationVersionOffice','ConfigurationStorageStatus','ConfigurationUnnecessaryApps','ConfigOperationSystem','dependencias','sedes','contractData'])
            
            ->whereRaw(base64_decode($request["f"]))->latest()
            ->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))
            ->take(base64_decode($request["pi"]))->get();

            $count_resumes = EquipmentResume::with(['EquipmentPurchaseDetails','ConfigurationOtherEquipments','EquipmentResumeHistories','ConfigurationTowerReferences','ConfigurationTowerSize','ConfigurationTowerProcessor','ConfigurationTowerRam','ConfigurationTowerSsd','ConfigurationTowerHdd','ConfigurationTowerVideoCard','ConfigurationTowerSharedFolder','ConfigurationNetworkCard','ConfigurationTowerSharedFolder','ConfigurationVersionOffice','ConfigurationStorageStatus','ConfigurationUnnecessaryApps','ConfigOperationSystem','dependencias','sedes','contractData'])
            ->whereRaw(base64_decode($request["f"]))->latest()
            ->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))
            ->take(base64_decode($request["pi"]))->count();



        }
        else if(isset($request["cp"]) && isset($request["pi"])) {
            $equipment_resumes = EquipmentResume::with(['EquipmentPurchaseDetails','ConfigurationOtherEquipments','EquipmentResumeHistories','ConfigurationTowerReferences','ConfigurationTowerSize','ConfigurationTowerProcessor','ConfigurationTowerRam','ConfigurationTowerSsd','ConfigurationTowerHdd','ConfigurationTowerVideoCard','ConfigurationTowerSharedFolder','ConfigurationNetworkCard','ConfigurationTowerSharedFolder','ConfigurationVersionOffice','ConfigurationStorageStatus','ConfigurationUnnecessaryApps','ConfigOperationSystem','dependencias','sedes','contractData'])
            ->latest()
            ->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get();

            $count_resumes= EquipmentResume::with(['EquipmentPurchaseDetails','ConfigurationOtherEquipments','EquipmentResumeHistories','ConfigurationTowerReferences','ConfigurationTowerSize','ConfigurationTowerProcessor','ConfigurationTowerRam','ConfigurationTowerSsd','ConfigurationTowerHdd','ConfigurationTowerVideoCard','ConfigurationTowerSharedFolder','ConfigurationNetworkCard','ConfigurationTowerSharedFolder','ConfigurationVersionOffice','ConfigurationStorageStatus','ConfigurationUnnecessaryApps','ConfigOperationSystem','dependencias','sedes','contractData'])
            ->latest()
            ->count();


        }
        $total_all_count = EquipmentResume::count();
        // $equipment_resumes = EquipmentResume::with(['EquipmentPurchaseDetails','ConfigurationOtherEquipments','EquipmentResumeHistories'])->latest()->get();
        return $this->sendResponseAvanzado($equipment_resumes->toArray(), trans('data_obtained_successfully'),null,["status_equipment" => $status_equipment,"status_asset_type"=>$status_asset_type , "total_registros" =>$count_resumes,"total_equipos" => $total_all_count]);
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param CreateEquipmentResumeRequest $request
     *
     * @return Response
     */
    public function store(CreateEquipmentResumeRequest $request) {

        $userLogged = Auth::user();
        $input = $request->all();

        $cookieProviderName = CookieRequest::cookie("provider_name");

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $mainData = [
                "tower" => $input['tower'] ?? null,
                "monitor" => $input['monitor'] ?? null,
                "asset_type" => $input['asset_type'] ?? null,
                "domain_user" => $input['domain_user'] ?? null,
                "officer" => $input['officer'] ?? null,
                "contract_type" => $input['contract_type'] ?? null,
                "charge" => $input['charge'] ?? null,
                "dependence" => $input['dependence'] ?? null,
                "area" => $input['area'] ?? null,
                "site" => $input['site'] ?? null,
                "service_manager" => $input['service_manager'] ?? null,
                "contract_number" => $input['contract_number'] ?? null,
                "provider" => $input['provider'] ?? null,
                "tower_inventory_number" => $input['tower_inventory_number'] ?? null,
                "tower_model" => $input['tower_model'] ?? null,
                "tower_series" => $input['tower_series'] ?? null,
                "tower_host" => $input['tower_host'] ?? null,
                "tower_mac_address" => $input['tower_mac_address'] ?? null,
                "tower_ipv4_address" => $input['tower_ipv4_address'] ?? null,
                "tower_ipv6_address" => $input['tower_ipv6_address'] ?? null,
                "faceplate_patch_panel" => $input['faceplate_patch_panel'] ?? null,
                "monitor_number_inventory" => $input['monitor_number_inventory'] ?? null,
                "monitor_model" => $input['monitor_model'] ?? null,
                "monitor_serial" => $input['monitor_serial'] ?? null,
                "operating_system" => $input['operating_system'] ?? null,
                "operating_system_version" => $input['operating_system_version'] ?? null,
                "operating_system_license" => $input['operating_system_license'] ?? null,
                "office_automation_license" => $input['office_automation_license'] ?? null,
                "installed_product" => $input['installed_product'] ?? null,
                "installed_product_version" => $input['installed_product_version'] ?? null,
                "teamviewer" => $input['teamviewer'] ?? null,
                "status_equipment" => $input['status_equipment'] ?? null,
                "name_user_domain" => $input['name_user_domain'] ?? null,
                // "date" => $input['date'] ?? null,
                // "warranty_in_years" => $input['warranty_in_years'] ?? null,
                // "contract_total_value" => $input['contract_total_value'] ?? null,
                // "warranty_termination_date" => $input['warranty_termination_date'] ?? null,
                "tower_equipment_year" => $input['tower_equipment_year'] ?? null,
                "tower_domain" => $input['tower_domain'] ?? null,
                "tower_network_point" => $input['tower_network_point'] ?? null,
                "tower_directory_active" => $input['tower_directory_active'] ?? null,
                "antivirus_version" => $input['antivirus_version'] ?? null,
                "antivirus_agent_version" => $input['antivirus_agent_version'] ?? null,
                "tower_dhcp" => $input['tower_dhcp'] ?? null,
                "id_tower" => $input['id_tower'] ?? null,
                "id_tower_processor" => $input['id_tower_processor'] ?? null,
                "id_tower_ram" => $input['id_tower_ram'] ?? null,
                "id_tower_network_card" => $input['id_tower_network_card'] ?? null,
                "id_tower_shared_folder" => $input['id_tower_shared_folder'] ?? null,
                "id_tower_ssd_capacity" => $input['id_tower_ssd_capacity'] ?? null,
                "id_tower_reference" => $input['id_tower_reference'] ?? null,
                "id_tower_video_card" => $input['id_tower_video_card'] ?? null,
                "id_tower_hdd_capacity" => $input['id_tower_hdd_capacity'] ?? null,
                "id_office_version" => $input['id_office_version'] ?? null,
                "id_storage_status" => $input['id_storage_status'] ?? null,
                "id_unnecessary_app" => $input['id_unnecessary_app'] ?? null,
                "office_license_inventory" => $input['office_license_inventory'] ?? null,
                "ht_sedes_id" => $input['ht_sedes_id'] ?? null,
                'tower_warranty_end_date' => $input['tower_warranty_end_date'] ?? null,
                'id_tower_size' => $input['id_tower_size'] ?? null,
                'observation' => $input['observation'] ?? null,


            ];
            // dd($mainData);
            // Inserta el registro en la base de datos
            $equipmentResume = EquipmentResume::create($mainData);

            if (!$equipmentResume) {
                return null;
            }
            $equipmentResume = $this->equipmentResumeRepository->find($equipmentResume->id);
            // dd($equipmentResume);
            // if(isset($input["configuration_other_equipments"])){
            //     foreach ($input["configuration_other_equipments"] as $otherEquipment) {
            //         $decodedConfigurationOtherEquipments = json_decode($otherEquipment);
            //         $this->_createOtherEquipment($equipmentResume['id'],$decodedConfigurationOtherEquipments->inventory_number,$decodedConfigurationOtherEquipments->mark,$decodedConfigurationOtherEquipments->model,$decodedConfigurationOtherEquipments->serial,$decodedConfigurationOtherEquipments->monitor_value,$decodedConfigurationOtherEquipments->contract_number);
            //     }
            // }

            // if(isset($input["equipment_purchase_details"])){
            //     foreach ($input["equipment_purchase_details"] as $EquipmentPurchaseDetail) {
            //         $decodedConfigurationEquipmentPurchaseDetails = json_decode($EquipmentPurchaseDetail);
            //         $this->_createEquipmentPurchaseDetail($equipmentResume['id'],$decodedConfigurationEquipmentPurchaseDetails->contract_number,$decodedConfigurationEquipmentPurchaseDetails->date,$decodedConfigurationEquipmentPurchaseDetails->provider,$decodedConfigurationEquipmentPurchaseDetails->warranty_in_years,$decodedConfigurationEquipmentPurchaseDetails->contract_total_value,$decodedConfigurationEquipmentPurchaseDetails->status,$decodedConfigurationEquipmentPurchaseDetails->warranty_termination_date);
            //     }
            // }

            if(isset($cookieProviderName)){
                $this->_createEquipmentResumeHistoryForProvider($equipmentResume['id'],$cookieProviderName,"Creación de la hoja de vida del equipo");
            }
            else{
                $this->_createEquipmentResumeHistory($equipmentResume['id'],$userLogged->id,$userLogged->name,"Creación de la hoja de vida del equipo");
            }

            $equipmentResume->EquipmentResumeHistories;
            $equipmentResume->EquipmentPurchaseDetails;
            $equipmentResume->ConfigurationOtherEquipments;
            $equipmentResume->ConfigurationTowerReferences;
            $equipmentResume->ConfigurationTowerSize;
            $equipmentResume->ConfigurationTowerProcessor;
            $equipmentResume->ConfigurationTowerRam;
            $equipmentResume->ConfigurationTowerSsd;
            $equipmentResume->ConfigurationTowerHdd;
            $equipmentResume->ConfigurationTowerVideoCard;
            $equipmentResume->ConfigurationTowerSharedFolder;
            $equipmentResume->ConfigurationNetworkCard;
            $equipmentResume->ConfigurationVersionOffice;
            $equipmentResume->ConfigurationStorageStatus;
            $equipmentResume->ConfigurationUnnecessaryApps;
            $equipmentResume->ConfigOperationSystem;
            $equipmentResume->dependencias;
            $equipmentResume->sedes;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($equipmentResume->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateEquipmentResumeRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        $userLogged = Auth::user();
        $input = $request->all();
        $cookieProviderName = CookieRequest::cookie("provider_name");

        /** @var EquipmentResume $equipmentResume */
        $equipmentResume = $this->equipmentResumeRepository->find($id);

        if (empty($equipmentResume)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // $quantityEquipmentResumeBackup = EquipmentResumeBackup::where('ht_tic_equipment_resume_id',$equipmentResume['id'])->count();

        // $equipmentResume['consecutive'] = $quantityEquipmentResumeBackup > 0 ? $quantityEquipmentResumeBackup + 1 : 1;

        // $equipmentResumeBackup = $this->_createEquipmentResumeBackup($equipmentResume);

        // if(isset($input["configuration_other_equipments"])){
        //     foreach ($input["configuration_other_equipments"] as $otherEquipment) {
        //         $decodedConfigurationOtherEquipments = json_decode($otherEquipment);
        //         if(isset($decodedConfigurationOtherEquipments->id)){
        //             $otherEquipment = ConfigOtherEquipment::where('id',$decodedConfigurationOtherEquipments->id)->get()->first();
        //             EquipmentResumeOtherEquipmentBackup::create(['ht_tic_equipment_resume_backups_id' => $equipmentResumeBackup[0]['id'],'inventory_number' => $otherEquipment['inventory_number'],'mark' => $otherEquipment['mark'],'model' => $otherEquipment['model'],'serial' => $otherEquipment['serial'],'monitor_value' => $otherEquipment['monitor_value'],'contract_number' => $otherEquipment['contract_number']]);
        //             ConfigOtherEquipment::where('id',$decodedConfigurationOtherEquipments->id)->delete();
        //         }
        //         $this->_createOtherEquipment($equipmentResume['id'],$decodedConfigurationOtherEquipments->inventory_number,$decodedConfigurationOtherEquipments->mark,$decodedConfigurationOtherEquipments->model,$decodedConfigurationOtherEquipments->serial,$decodedConfigurationOtherEquipments->monitor_value,$decodedConfigurationOtherEquipments->contract_number);
        //     }
        // }
        // if(isset($input["equipment_purchase_details"])){
        //     foreach ($input["equipment_purchase_details"] as $equipmentPurchaseDetail) {
        //         $decodedConfigurationEquipmentPurchaseDetails = json_decode($equipmentPurchaseDetail);
        //         if(isset($decodedConfigurationEquipmentPurchaseDetails->id)){
        //             $equipmentPurchaseDetail = EquipmentPurchaseDetail::where('id',$decodedConfigurationEquipmentPurchaseDetails->id)->get()->first();
        //             EquipmentPurchaseDetailsBackup::create(['ht_tic_equipment_resume_backups_id' => $equipmentResumeBackup[0]['id'],'contract_number' => $equipmentPurchaseDetail['contract_number'],'date' => $equipmentPurchaseDetail['date'],'provider' => $equipmentPurchaseDetail['provider'],'warranty_in_years' => $equipmentPurchaseDetail['warranty_in_years'],'contract_total_value' => $equipmentPurchaseDetail['contract_total_value'],'status' => $equipmentPurchaseDetail['status'],'warranty_termination_date' => $equipmentPurchaseDetail['warranty_termination_date']]);
        //             EquipmentPurchaseDetail::where('id',$decodedConfigurationEquipmentPurchaseDetails->id)->delete();
        //         }
        //         $this->_createEquipmentPurchaseDetail($equipmentResume['id'],$decodedConfigurationEquipmentPurchaseDetails->contract_number,$decodedConfigurationEquipmentPurchaseDetails->date,$decodedConfigurationEquipmentPurchaseDetails->provider,$decodedConfigurationEquipmentPurchaseDetails->warranty_in_years,$decodedConfigurationEquipmentPurchaseDetails->contract_total_value,$decodedConfigurationEquipmentPurchaseDetails->status,$decodedConfigurationEquipmentPurchaseDetails->warranty_termination_date);
        //     }
        // }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
           $mainData = [
                "tower" => $input['tower'] ?? null,
                "monitor" => $input['monitor'] ?? null,
                "asset_type" => $input['asset_type'] ?? null,
                "domain_user" => $input['domain_user'] ?? null,
                "officer" => $input['officer'] ?? null,
                "contract_type" => $input['contract_type'] ?? null,
                "charge" => $input['charge'] ?? null,
                "dependence" => $input['dependence'] ?? null,
                "area" => $input['area'] ?? null,
                "site" => $input['site'] ?? null,
                "service_manager" => $input['service_manager'] ?? null,
                "contract_number" => $input['contract_number'] ?? null,
                "provider" => $input['provider'] ?? null,
                "tower_inventory_number" => $input['tower_inventory_number'] ?? null,
                "tower_model" => $input['tower_model'] ?? null,
                "tower_series" => $input['tower_series'] ?? null,
                "tower_host" => $input['tower_host'] ?? null,
                "tower_mac_address" => $input['tower_mac_address'] ?? null,
                "tower_ipv4_address" => $input['tower_ipv4_address'] ?? null,
                "tower_ipv6_address" => $input['tower_ipv6_address'] ?? null,
                "faceplate_patch_panel" => $input['faceplate_patch_panel'] ?? null,
                "monitor_number_inventory" => $input['monitor_number_inventory'] ?? null,
                "monitor_model" => $input['monitor_model'] ?? null,
                "monitor_serial" => $input['monitor_serial'] ?? null,
                "operating_system" => $input['operating_system'] ?? null,
                "operating_system_version" => $input['operating_system_version'] ?? null,
                "operating_system_license" => $input['operating_system_license'] ?? null,
                "office_automation_license" => $input['office_automation_license'] ?? null,
                "installed_product" => $input['installed_product'] ?? null,
                "installed_product_version" => $input['installed_product_version'] ?? null,
                "teamviewer" => $input['teamviewer'] ?? null,
                "status_equipment" => $input['status_equipment'] ?? null,
                "name_user_domain" => $input['name_user_domain'] ?? null,
                "date" => $input['date'] ?? null,
                "warranty_in_years" => $input['warranty_in_years'] ?? null,
                "contract_total_value" => $input['contract_total_value'] ?? null,
                "warranty_termination_date" => $input['warranty_termination_date'] ?? null,
                "tower_equipment_year" => $input['tower_equipment_year'] ?? null,
                "tower_domain" => $input['tower_domain'] ?? null,
                "tower_network_point" => $input['tower_network_point'] ?? null,
                "tower_directory_active" => $input['tower_directory_active'] ?? null,
                "antivirus_version" => $input['antivirus_version'] ?? null,
                "antivirus_agent_version" => $input['antivirus_agent_version'] ?? null,
                "tower_dhcp" => $input['tower_dhcp'] ?? null,
                "id_tower" => $input['id_tower'] ?? null,
                "id_tower_processor" => $input['id_tower_processor'] ?? null,
                "id_tower_ram" => $input['id_tower_ram'] ?? null,
                "id_tower_network_card" => $input['id_tower_network_card'] ?? null,
                "id_tower_shared_folder" => $input['id_tower_shared_folder'] ?? null,
                "id_tower_ssd_capacity" => $input['id_tower_ssd_capacity'] ?? null,
                "id_tower_reference" => $input['id_tower_reference'] ?? null,
                "id_tower_video_card" => $input['id_tower_video_card'] ?? null,
                "id_tower_hdd_capacity" => $input['id_tower_hdd_capacity'] ?? null,
                "id_office_version" => $input['id_office_version'] ?? null,
                "id_storage_status" => $input['id_storage_status'] ?? null,
                "id_unnecessary_app" => $input['id_unnecessary_app'] ?? null,
                "office_license_inventory" => $input['office_license_inventory'] ?? null,
                "ht_sedes_id" => $input['ht_sedes_id'] ?? null,
                'tower_warranty_end_date' => $input['tower_warranty_end_date'] ?? null,
                'id_tower_size' => $input['id_tower_size'] ?? null,
                'observation' => $input['observation'] ?? null,

            ];
            // dd($mainData);
            // Actualiza el registro
            $equipmentResume = EquipmentResume::where('id', $id)->update($mainData);
            
            /** @var EquipmentResume $equipmentResume */
            $equipmentResume = $this->equipmentResumeRepository->find($id);
            
            if(isset($cookieProviderName)){
                $this->_createEquipmentResumeHistoryForProvider($equipmentResume['id'],$cookieProviderName,"Modificación de la información de la hoja de vida del equipo");
            }
            else{
                $this->_createEquipmentResumeHistory($equipmentResume['id'],$userLogged->id,$userLogged->name,"Modificación de la información de la hoja de vida del equipo");
            }

            $equipmentResume->EquipmentResumeHistories;
            $equipmentResume->EquipmentPurchaseDetails;
            $equipmentResume->ConfigurationOtherEquipments;
            $equipmentResume->ConfigurationTowerReferences;
            $equipmentResume->ConfigurationTowerSize;
            $equipmentResume->ConfigurationTowerProcessor;
            $equipmentResume->ConfigurationTowerRam;
            $equipmentResume->ConfigurationTowerSsd;
            $equipmentResume->ConfigurationTowerHdd;
            $equipmentResume->ConfigurationTowerVideoCard;
            $equipmentResume->ConfigurationTowerSharedFolder;
            $equipmentResume->ConfigurationNetworkCard;
            $equipmentResume->ConfigurationVersionOffice;
            $equipmentResume->ConfigurationStorageStatus;
            $equipmentResume->ConfigurationUnnecessaryApps;
            $equipmentResume->ConfigOperationSystem;
            $equipmentResume->dependencias;
            $equipmentResume->sedes;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($equipmentResume, trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeController - '. Auth::user()->name.' -  Error: '.$e->getMessage() . 'Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un EquipmentResume del almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var EquipmentResume $equipmentResume */
        $equipmentResume = $this->equipmentResumeRepository->find($id);

        if (empty($equipmentResume)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $equipmentResume->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('equipment_resumes').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) {

            if (!empty($request['filtros'])) {
                $equipmentResume = EquipmentResume::with('ConfigurationTowerRam','contractData','ConfigurationTowerProcessor','ConfigurationTowerHdd','ConfigurationTowerSsd','ConfigurationTowerVideoCard','ConfigurationNetworkCard','ConfigOperationSystem','ConfigurationVersionOffice','ConfigurationUnnecessaryApps','ConfigurationTowerReferences','ConfigurationTowerSize','ConfigurationTowerSharedFolder','dependencias')->whereRaw($request['filtros'])->get();
            }else{
                $equipmentResume = EquipmentResume::with('ConfigurationTowerRam','contractData','ConfigurationTowerProcessor','ConfigurationTowerHdd','ConfigurationTowerSsd','ConfigurationTowerVideoCard','ConfigurationNetworkCard','ConfigOperationSystem','ConfigurationVersionOffice','ConfigurationUnnecessaryApps','ConfigurationTowerReferences','ConfigurationTowerSize','ConfigurationTowerSharedFolder','dependencias')->get();

            }
        
            return Excel::download(new RequestExport('help_table::equipment_resumes.exports.xlsx',$equipmentResume,'AZ'), 'Listado de la hoja de vida del equipo.xlsx');
        }
    }

    /**
     * Importa los registros de un excel para registrarlos en el sistema
     *
     * @author Kleverman Salazar Florez. - Ene. 25 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     *
     * @return Response
     */
    public function importDataOfEquipmentResumes(Request $request){
        $userLogged = Auth::user();

        $equipmentResumesData = Excel::toArray(new EquipmentResume, $request["import_file"]);
        $data = [];

        if($request->hasFile("import_file")){
            $quantityColumns = count($equipmentResumesData[0][0]);

            if($quantityColumns != 87){
                return $this->sendSuccess('Error,por favor verifique que el número de columnas con datos en el excel coincida con el número de columnas del formulario de importación', 'info');
            }

            unset($equipmentResumesData[0][0]); // Elimina la posicion 0 ya que es donde se encuentra los titulos

            $quantityEquipmentResumesData = count($equipmentResumesData[0]);

            foreach ($equipmentResumesData[0] as $equipmentResume) {
                if(!isset($equipmentResume[0])){
                    break;
                }

                // Inserta el registro en la base de datos
                $newEquipmentResume = EquipmentResume::create([
                    'asset_type' => $equipmentResume[0],
                    'domain_user' => $equipmentResume[1],
                    'officer' => $equipmentResume[2],
                    'contract_type' => $equipmentResume[3],
                    'charge' => $equipmentResume[4],
                    'dependence' => $equipmentResume[5],
                    'area' => $equipmentResume[6],
                    'site' => $equipmentResume[7],
                    'service_manager' => $equipmentResume[8],
                    'maintenance_type' => $equipmentResume[9],
                    'cycle' => $equipmentResume[10],
                    'contract_number' => $equipmentResume[11],
                    'contract_date' => $this->formatDate($equipmentResume[12]),
                    'maintenance_date' => $this->formatDate($equipmentResume[13]),
                    'provider' => $equipmentResume[14],
                    'contract_value' => $equipmentResume[15],
                    'has_internal_and_external_hardware_cleaning' => $equipmentResume[16],
                    'has_ram_cleaning' => $equipmentResume[17],
                    'has_board_memory_cleaning' => $equipmentResume[18],
                    'has_power_supply_cleaning' => $equipmentResume[19],
                    'has_dvd_drive_cleaning' => $equipmentResume[20],
                    'has_monitor_cleaning' => $equipmentResume[21],
                    'has_keyboard_cleaning' => $equipmentResume[22],
                    'has_mouse_cleaning' => $equipmentResume[23],
                    'has_thermal_paste_change' => $equipmentResume[24],
                    'has_heatsink_cleaning' => $equipmentResume[25],
                    'technical_report' => $equipmentResume[26],
                    'observation' => $equipmentResume[27],
                    'tower_inventory_number' => $equipmentResume[28],
                    'tower' => $equipmentResume[29] ?? null,
                    'tower_model' => $equipmentResume[30] ?? null,
                    'tower_series' => $equipmentResume[31] ?? null,
                    'tower_processor' => $equipmentResume[32] ?? null,
                    'tower_host' => $equipmentResume[33] ?? null,
                    'tower_ram_gb' => $equipmentResume[34] ?? null,
                    'tower_ram_gb_mark' => $equipmentResume[35] ?? null,
                    'tower_number_ram_modules' => $equipmentResume[36] ?? null,
                    'tower_mac_address' => $equipmentResume[37] ?? null,
                    'tower_mainboard' => $equipmentResume[38] ?? null,
                    'tower_mainboard_mark' => $equipmentResume[39] ?? null,
                    'tower_ipv4_address' => $equipmentResume[40] ?? null,
                    'tower_ipv6_address' => $equipmentResume[41] ?? null,
                    'tower_ddh_capacity_gb' => $equipmentResume[42] ?? null,
                    'tower_ddh_capacity_gb_mark' => $equipmentResume[43] ?? null,
                    'tower_ssd_capacity_gb' => $equipmentResume[44] ?? null,
                    'tower_ssd_capacity_gb_mark' => $equipmentResume[45] ?? null,
                    'tower_video_card' => $equipmentResume[46] ?? null,
                    'tower_video_card_mark' => $equipmentResume[47] ?? null,
                    'tower_sound_card' => $equipmentResume[48] ?? null,
                    'tower_sound_card_mark' => $equipmentResume[49] ?? null,
                    'tower_network_card' => $equipmentResume[50] ?? null,
                    'tower_network_card_mark' => $equipmentResume[51] ?? null,
                    'faceplate' => $equipmentResume[52] ?? null,
                    'faceplate_patch_panel' => $equipmentResume[53] ?? null,
                    'tower_value' => $equipmentResume[54] ?? null,
                    'tower_contract_number' => $equipmentResume[55] ?? null,
                    'monitor_number_inventory' => $equipmentResume[56] ?? null,
                    'monitor' => $equipmentResume[57] ?? null,
                    'monitor_model' => $equipmentResume[58] ?? null,
                    'monitor_serial' => $equipmentResume[59] ?? null,
                    'monitor_value' => $equipmentResume[60] ?? null,
                    'monitor_contract_number' => $equipmentResume[61] ?? null,
                    'keyboard_number_inventory' => $equipmentResume[62] ?? null,
                    'keyboard' => $equipmentResume[63] ?? null,
                    'keyboard_model' => $equipmentResume[64] ?? null,
                    'keyboard_serial' => $equipmentResume[65] ?? null,
                    'keyboard_value' => $equipmentResume[66] ?? null,
                    'keyboard_contract_number' => $equipmentResume[67] ?? null,
                    'mouse_number_inventory' => $equipmentResume[68] ?? null,
                    'mouse' => $equipmentResume[69] ?? null,
                    'mouse_model' => $equipmentResume[70] ?? null,
                    'mouse_serial' => $equipmentResume[71] ?? null,
                    'mouse_value' => $equipmentResume[72] ?? null,
                    'mouse_contract_number' => $equipmentResume[73] ?? null,
                    'operating_system' => $equipmentResume[74] ?? null,
                    'operating_system_version' => $equipmentResume[75] ?? null,
                    'operating_system_license' => $equipmentResume[76] ?? null,
                    'office_automation' => $equipmentResume[77] ?? null,
                    'office_automation_version' => $equipmentResume[78] ?? null,
                    'office_automation_license' => $equipmentResume[79] ?? null,
                    'antivirus' => $equipmentResume[80] ?? null,
                    'installed_product' => $equipmentResume[81] ?? null,
                    'installed_product_version' => $equipmentResume[82] ?? null,
                    'browser' => $equipmentResume[83] ?? null,
                    'browser_version' => $equipmentResume[84] ?? null,
                    'teamviewer' => $equipmentResume[85] ?? null,
                    'other' =>$equipmentResume[86] ?? null,
                ]);
                $this->_createEquipmentResumeHistory($newEquipmentResume['id'],$userLogged->id,$userLogged->name,"Creación de la hoja de vida del equipo");
            }

            return $this->sendResponse($newEquipmentResume, trans('msg_success_save'));
        }
    }

    public function formatDate($date){
        if (!is_null($date)) {
            // Obtiene el sistema operativo para formatear la fecha segun este
            $userAgent = $this->getOperatingSystem();

            $year = $userAgent === "Windows" ? 1900 : 1904;
            $base_date = Carbon::createFromDate($year, 1, 2);
            $date = $base_date->addDays($date - 2)->format('Y-m-d');
        }
        return $date;
    }

    public function getOperatingSystem() : string {
        $userAgent = request()->header('User-Agent');
    
        if (strpos($userAgent, 'Windows NT') !== false) {
            return 'Windows';
        } elseif (strpos($userAgent, 'Macintosh') !== false && strpos($userAgent, 'Intel Mac') !== false) {
            return 'Mac';
        } else {
            return 'Desconocido';
        }
    }

    /**
     * Crea un registro de la tabla de otros dispositivos
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param int $equipmentResumeId
     * @param string $inventoryNumber
     * @param string $mark
     * @param string $model
     * @param string $serial
     * @param string $monitorValue
     * @param string $contractNumber
     */
    private function _createOtherEquipment(int $equipmentResumeId,string $inventoryNumber,string $mark,string $model,string $serial,string $monitorValue,string $contractNumber): void {
        ConfigOtherEquipment::create(['ht_tic_equipment_resume_id' => $equipmentResumeId, 'inventory_number' => $inventoryNumber, 'mark' => $mark, 'model' => $model, 'serial' => $serial, 'monitor_value' => $monitorValue, 'contract_number' => $contractNumber]);
    }

    /**
     * Crea un registro de la tabla de otros dispositivos
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param int $equipmentResumeId
     * @param string $inventoryNumber
     * @param string $mark
     * @param string $model
     * @param string $serial
     * @param string $monitorValue
     * @param string $contractNumber
     */
    private function _createEquipmentPurchaseDetail(int $equipmentResumeId,string $contractNumber,string $date,string $provider,string $warrantyInYears,string $contractTotalValue,string $status,string $warrantyTerminationDate): void {
        EquipmentPurchaseDetail::create(['ht_tic_equipment_resume_id' => $equipmentResumeId, 'contract_number' => $contractNumber, 'date' => $date, 'provider' => $provider, 'warranty_in_years' => $warrantyTerminationDate, 'contract_total_value' => $contractTotalValue, 'status' => $status, 'warranty_termination_date' => $warrantyTerminationDate]);
    }

    /**
     * Crea un registro de historial para una hoja de vida de un equipo
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param int $equipmentResumeId
     * @param int $userId
     * @param string $userName
     * @param string $action
     *
     */
    private function _createEquipmentResumeHistory(int $equipmentResumeId, int $userId, string $userName,string $action):void{
        EquipmentResumeHistory::create(['ht_tic_equipment_resume_id' => $equipmentResumeId, 'user_id' => $userId, 'user_name' => $userName, 'action' => $action]);
    }

    /**
     * Crea un registro de historial para una hoja de vida de un equipo
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param int $equipmentResumeId
     * @param string $userName
     * @param string $action
     *
     */
    private function _createEquipmentResumeHistoryForProvider(int $equipmentResumeId, string $userName,string $action):void{
        EquipmentResumeHistory::create(['ht_tic_equipment_resume_id' => $equipmentResumeId, 'user_name' => $userName, 'action' => $action]);
    }

    private function _createEquipmentResumeBackup(object $equipmentResumeData):array{
        return [
            EquipmentResumeBackup::create([
                'ht_tic_equipment_resume_id' => $equipmentResumeData["id"],
                'consecutive' => $equipmentResumeData["consecutive"],
                'asset_type' => isset($equipmentResumeData["asset_type"]) ? $equipmentResumeData["asset_type"] : null,
                'domain_user' => isset($equipmentResumeData["domain_user"]) ? $equipmentResumeData["domain_user"] : null,
                'officer' => isset($equipmentResumeData["officer"]) ? $equipmentResumeData["officer"] : null,
                'contract_type' => isset($equipmentResumeData["contract_type"]) ? $equipmentResumeData["contract_type"] : null,
                'charge' => isset($equipmentResumeData["charge"]) ? $equipmentResumeData["charge"] : null,
                'dependence' => isset($equipmentResumeData["dependence"]) ? $equipmentResumeData["dependence"] : null,
                'area' => isset($equipmentResumeData["area"]) ? $equipmentResumeData["area"] : null,
                'site' => isset($equipmentResumeData["site"]) ? $equipmentResumeData["site"] : null,
                'service_manager' => isset($equipmentResumeData["service_manager"]) ? $equipmentResumeData["service_manager"] : null,
                'maintenance_type' => isset($equipmentResumeData["maintenance_type"]) ? $equipmentResumeData["maintenance_type"] : null,
                'cycle' => isset($equipmentResumeData["cycle"]) ? $equipmentResumeData["cycle"] : null,
                'contract_number' => isset($equipmentResumeData["contract_number"]) ? $equipmentResumeData["contract_number"] : null,
                'contract_date' => isset($equipmentResumeData["contract_date"]) ? $equipmentResumeData["contract_date"] : null,
                'maintenance_date' => isset($equipmentResumeData["maintenance_date"]) ? $equipmentResumeData["maintenance_date"] : null,
                'provider' => isset($equipmentResumeData["provider"]) ? $equipmentResumeData["provider"] : null,
                'contract_value' => isset($equipmentResumeData["contract_value"]) ? $equipmentResumeData["contract_value"] : null,
                'has_internal_and_external_hardware_cleaning' => isset($equipmentResumeData["has_internal_and_external_hardware_cleaning"]) ? $equipmentResumeData["has_internal_and_external_hardware_cleaning"] : null,
                'observation_internal_and_external_hardware_cleaning' => isset($equipmentResumeData["observation_internal_and_external_hardware_cleaning"]) ? $equipmentResumeData["observation_internal_and_external_hardware_cleaning"] : null,
                'has_ram_cleaning' => isset($equipmentResumeData["has_ram_cleaning"]) ? $equipmentResumeData["has_ram_cleaning"] : null,
                'observation_ram_cleaning' => isset($equipmentResumeData["observation_ram_cleaning"]) ? $equipmentResumeData["observation_ram_cleaning"] : null,
                'has_board_memory_cleaning' => isset($equipmentResumeData["has_board_memory_cleaning"]) ? $equipmentResumeData["has_board_memory_cleaning"] : null,
                'observation_board_memory_cleaning' => isset($equipmentResumeData["observation_board_memory_cleaning"]) ? $equipmentResumeData["observation_board_memory_cleaning"] : null,
                'has_power_supply_cleaning' => isset($equipmentResumeData["has_power_supply_cleaning"]) ? $equipmentResumeData["has_power_supply_cleaning"] : null,
                'observation_power_supply_cleaning' => isset($equipmentResumeData["observation_power_supply_cleaning"]) ? $equipmentResumeData["observation_power_supply_cleaning"] : null,
                'has_dvd_drive_cleaning' => isset($equipmentResumeData["has_dvd_drive_cleaning"]) ? $equipmentResumeData["has_dvd_drive_cleaning"] : null,
                'observation_dvd_drive_cleaning' => isset($equipmentResumeData["observation_dvd_drive_cleaning"]) ? $equipmentResumeData["observation_dvd_drive_cleaning"] : null,
                'has_monitor_cleaning' => isset($equipmentResumeData["has_monitor_cleaning"]) ? $equipmentResumeData["has_monitor_cleaning"] : null,
                'observation_monitor_cleaning' => isset($equipmentResumeData["observation_monitor_cleaning"]) ? $equipmentResumeData["observation_monitor_cleaning"] : null,
                'has_keyboard_cleaning' => isset($equipmentResumeData["has_keyboard_cleaning"]) ? $equipmentResumeData["has_keyboard_cleaning"] : null,
                'observation_keyboard_cleaning' => isset($equipmentResumeData["observation_keyboard_cleaning"]) ? $equipmentResumeData["observation_keyboard_cleaning"] : null,
                'has_mouse_cleaning' => isset($equipmentResumeData["has_mouse_cleaning"]) ? $equipmentResumeData["has_mouse_cleaning"] : null,
                'observation_mouse_cleaning' => isset($equipmentResumeData["observation_mouse_cleaning"]) ? $equipmentResumeData["observation_mouse_cleaning"] : null,
                'has_thermal_paste_change' => isset($equipmentResumeData["has_thermal_paste_change"]) ? $equipmentResumeData["has_thermal_paste_change"] : null,
                'observation_thermal_paste_change' => isset($equipmentResumeData["observation_thermal_paste_change"]) ? $equipmentResumeData["observation_thermal_paste_change"] : null,
                'has_heatsink_cleaning' => isset($equipmentResumeData["has_heatsink_cleaning"]) ? $equipmentResumeData["has_heatsink_cleaning"] : null,
                'observation_heatsink_cleaning' => isset($equipmentResumeData["observation_heatsink_cleaning"]) ? $equipmentResumeData["observation_heatsink_cleaning"] : null,
                'technical_report' => isset($equipmentResumeData["technical_report"]) ? $equipmentResumeData["technical_report"] : null,
                'observation' => isset($equipmentResumeData["observation"]) ? $equipmentResumeData["observation"] : null,
                'tower_inventory_number' => isset($equipmentResumeData["tower_inventory_number"]) ? $equipmentResumeData["tower_inventory_number"] : null,
                'tower' => isset($equipmentResumeData["tower"]) ? $equipmentResumeData["tower"] : null,
                'tower_model' => isset($equipmentResumeData["tower_model"]) ? $equipmentResumeData["tower_model"] : null,
                'tower_series' => isset($equipmentResumeData["tower_series"]) ? $equipmentResumeData["tower_series"] : null,
                'tower_processor' => isset($equipmentResumeData["tower_processor"]) ? $equipmentResumeData["tower_processor"] : null,
                'tower_host' => isset($equipmentResumeData["tower_host"]) ? $equipmentResumeData["tower_host"] : null,
                'tower_ram_gb' => isset($equipmentResumeData["tower_ram_gb"]) ? $equipmentResumeData["tower_ram_gb"] : null,
                'tower_ram_gb_mark' => isset($equipmentResumeData["tower_ram_gb_mark"]) ? $equipmentResumeData["tower_ram_gb_mark"] : null,
                'tower_number_ram_modules' => isset($equipmentResumeData["tower_number_ram_modules"]) ? $equipmentResumeData["tower_number_ram_modules"] : null,
                'tower_mac_address' => isset($equipmentResumeData["tower_mac_address"]) ? $equipmentResumeData["tower_mac_address"] : null,
                'tower_mainboard' => isset($equipmentResumeData["tower_mainboard"]) ? $equipmentResumeData["tower_mainboard"] : null,
                'tower_mainboard_mark' => isset($equipmentResumeData["tower_mainboard_mark"]) ? $equipmentResumeData["tower_mainboard_mark"] : null,
                'tower_ipv4_address' => isset($equipmentResumeData["tower_ipv4_address"]) ? $equipmentResumeData["tower_ipv4_address"] : null,
                'tower_ipv6_address' => isset($equipmentResumeData["tower_ipv6_address"]) ? $equipmentResumeData["tower_ipv6_address"] : null,
                'tower_ddh_capacity_gb' => isset($equipmentResumeData["tower_ddh_capacity_gb"]) ? $equipmentResumeData["tower_ddh_capacity_gb"] : null,
                'tower_ddh_capacity_gb_mark' => isset($equipmentResumeData["tower_ddh_capacity_gb_mark"]) ? $equipmentResumeData["tower_ddh_capacity_gb_mark"] : null,
                'tower_ssd_capacity_gb' => isset($equipmentResumeData["tower_ssd_capacity_gb"]) ? $equipmentResumeData["tower_ssd_capacity_gb"] : null,
                'tower_ssd_capacity_gb_mark' => isset($equipmentResumeData["tower_ssd_capacity_gb_mark"]) ? $equipmentResumeData["tower_ssd_capacity_gb_mark"] : null,
                'tower_video_card' => isset($equipmentResumeData["tower_video_card"]) ? $equipmentResumeData["tower_video_card"] : null,
                'tower_video_card_mark' => isset($equipmentResumeData["tower_video_card_mark"]) ? $equipmentResumeData["tower_video_card_mark"] : null,
                'tower_sound_card' => isset($equipmentResumeData["tower_sound_card"]) ? $equipmentResumeData["tower_sound_card"] : null,
                'tower_sound_card_mark' => isset($equipmentResumeData["tower_sound_card_mark"]) ? $equipmentResumeData["tower_sound_card_mark"] : null,
                'tower_network_card' => isset($equipmentResumeData["tower_network_card"]) ? $equipmentResumeData["tower_network_card"] : null,
                'tower_network_card_mark' => isset($equipmentResumeData["tower_network_card_mark"]) ? $equipmentResumeData["tower_network_card_mark"] : null,
                'faceplate' => isset($equipmentResumeData["faceplate"]) ? $equipmentResumeData["faceplate"] : null,
                'faceplate_patch_panel' => isset($equipmentResumeData["faceplate_patch_panel"]) ? $equipmentResumeData["faceplate_patch_panel"] : null,
                'tower_value' => isset($equipmentResumeData["tower_value"]) ? $equipmentResumeData["tower_value"] : null,
                'tower_contract_number' => isset($equipmentResumeData["tower_contract_number"]) ? $equipmentResumeData["tower_contract_number"] : null,
                'monitor_number_inventory' => isset($equipmentResumeData["monitor_number_inventory"]) ? $equipmentResumeData["monitor_number_inventory"] : null,
                'monitor' => isset($equipmentResumeData["monitor"]) ? $equipmentResumeData["monitor"] : null,
                'monitor_model' => isset($equipmentResumeData["monitor_model"]) ? $equipmentResumeData["monitor_model"] : null,
                'monitor_serial' => isset($equipmentResumeData["monitor_serial"]) ? $equipmentResumeData["monitor_serial"] : null,
                'monitor_value' => isset($equipmentResumeData["monitor_value"]) ? $equipmentResumeData["monitor_value"] : null,
                'monitor_contract_number' => isset($equipmentResumeData["monitor_contract_number"]) ? $equipmentResumeData["monitor_contract_number"] : null,
                'keyboard_number_inventory' => isset($equipmentResumeData["keyboard_number_inventory"]) ? $equipmentResumeData["keyboard_number_inventory"] : null,
                'keyboard' => isset($equipmentResumeData["keyboard"]) ? $equipmentResumeData["keyboard"] : null,
                'keyboard_model' => isset($equipmentResumeData["keyboard_model"]) ? $equipmentResumeData["keyboard_model"] : null,
                'keyboard_serial' => isset($equipmentResumeData["keyboard_serial"]) ? $equipmentResumeData["keyboard_serial"] : null,
                'keyboard_value' => isset($equipmentResumeData["keyboard_value"]) ? $equipmentResumeData["keyboard_value"] : null,
                'keyboard_contract_number' => isset($equipmentResumeData["keyboard_contract_number"]) ? $equipmentResumeData["keyboard_contract_number"] : null,
                'mouse_number_inventory' => isset($equipmentResumeData["mouse_number_inventory"]) ? $equipmentResumeData["mouse_number_inventory"] : null,
                'mouse' => isset($equipmentResumeData["mouse"]) ? $equipmentResumeData["mouse"] : null,
                'mouse_model' => isset($equipmentResumeData["mouse_model"]) ? $equipmentResumeData["mouse_model"] : null,
                'mouse_serial' => isset($equipmentResumeData["mouse_serial"]) ? $equipmentResumeData["mouse_serial"] : null,
                'mouse_value' => isset($equipmentResumeData["mouse_value"]) ? $equipmentResumeData["mouse_value"] : null,
                'mouse_contract_number' => isset($equipmentResumeData["mouse_contract_number"]) ? $equipmentResumeData["mouse_contract_number"] : null,
                'operating_system' => isset($equipmentResumeData["operating_system"]) ? $equipmentResumeData["operating_system"] : null,
                'operating_system_version' => isset($equipmentResumeData["operating_system_version"]) ? $equipmentResumeData["operating_system_version"] : null,
                'operating_system_license' => isset($equipmentResumeData["operating_system_license"]) ? $equipmentResumeData["operating_system_license"] : null,
                'office_automation' => isset($equipmentResumeData["office_automation"]) ? $equipmentResumeData["office_automation"] : null,
                'office_automation_version' => isset($equipmentResumeData["office_automation_version"]) ? $equipmentResumeData["office_automation_version"] : null,
                'office_automation_license' => isset($equipmentResumeData["office_automation_license"]) ? $equipmentResumeData["office_automation_license"] : null,
                'antivirus' => isset($equipmentResumeData["antivirus"]) ? $equipmentResumeData["antivirus"] : null,
                'installed_product' => isset($equipmentResumeData["installed_product"]) ? $equipmentResumeData["installed_product"] : null,
                'installed_product_version' => isset($equipmentResumeData["installed_product_version"]) ? $equipmentResumeData["installed_product_version"] : null,
                'browser' => isset($equipmentResumeData["browser"]) ? $equipmentResumeData["browser"] : null,
                'browser_version' => isset($equipmentResumeData["browser_version"]) ? $equipmentResumeData["browser_version"] : null,
                'teamviewer' => isset($equipmentResumeData["teamviewer"]) ? $equipmentResumeData["teamviewer"] : null,
                'other' => isset($equipmentResumeData["other"]) ? $equipmentResumeData["other"] : null,
            ])
        ];
    }


        /**
     * Obtiene todos los elementos existentes
     *
     * @author Seven Soluciones Informáticas S.A.S - May. 03 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function obtener_dependencias() {
        // Obtiene las dependencias con sus sedes
        $dependencies = Dependency::all();
        return $this->sendResponse($dependencies->toArray(), trans('data_obtained_successfully'));
    }



        /**
     * Obtiene todos los elementos existentes
     *
     * @author Seven Soluciones Informáticas S.A.S - May. 03 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getEquipmentResume() {
        // Obtiene las dependencias con sus sedes
        $equipmentResume = EquipmentResume::all();
        return $this->sendResponse($equipmentResume->toArray(), trans('data_obtained_successfully'));
    }
}
