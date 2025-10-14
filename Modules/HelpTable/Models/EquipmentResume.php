<?php

namespace Modules\HelpTable\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use function PHPSTORM_META\map;

/**
 * Class EquipmentResume
 * @package Modules\HelpTable\Models
 * @version January 23, 2023, 4:33 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $htTicEquipmentPurchaseDetails
 * @property \Illuminate\Database\Eloquent\Collection $htTicEquipmentResumeHistories
 * @property \Illuminate\Database\Eloquent\Collection $htTicHardwareConfigurationOtherEquipments
 * @property string $mouse
 * @property string $tower
 * @property string $keyboard
 * @property string $monitor
 * @property string $asset_type
 * @property string $domain_user
 * @property string $officer
 * @property string $contract_type
 * @property string $charge
 * @property string $dependence
 * @property string $area
 * @property string $site
 * @property string $service_manager
 * @property string $maintenance_type
 * @property string $cycle
 * @property string $contract_number
 * @property string $contract_date
 * @property string $maintenance_date
 * @property string $provider
 * @property string $contract_value
 * @property string $has_internal_and_external_hardware_cleaning
 * @property string $observation_internal_and_external_hardware_cleaning
 * @property string $has_ram_cleaning
 * @property string $observation_ram_cleaning
 * @property string $has_board_memory_cleaning
 * @property string $observation_board_memory_cleaning
 * @property string $has_power_supply_cleaning
 * @property string $observation_power_supply_cleaning
 * @property string $has_dvd_drive_cleaning
 * @property string $observation_dvd_drive_cleaning
 * @property string $has_monitor_cleaning
 * @property string $observation_monitor_cleaning
 * @property string $has_keyboard_cleaning
 * @property string $observation_keyboard_cleaning
 * @property string $has_mouse_cleaning
 * @property string $observation_mouse_cleaning
 * @property string $has_thermal_paste_change
 * @property string $observation_thermal_paste_change
 * @property string $has_heatsink_cleaning
 * @property string $observation_heatsink_cleaning
 * @property string $technical_report
 * @property string $observation
 * @property string $tower_inventory_number
 * @property string $tower_model
 * @property string $tower_series
 * @property string $tower_processor
 * @property string $tower_host
 * @property string $tower_ram_gb
 * @property string $tower_ram_gb_mark
 * @property string $tower_number_ram_modules
 * @property string $tower_mac_address
 * @property string $tower_mainboard
 * @property string $tower_mainboard_mark
 * @property string $tower_ipv4_address
 * @property string $tower_ipv6_address
 * @property string $tower_ddh_capacity_gb
 * @property string $tower_ddh_capacity_gb_mark
 * @property string $tower_ssd_capacity_gb
 * @property string $tower_ssd_capacity_gb_mark
 * @property string $tower_video_card
 * @property string $tower_video_card_mark
 * @property string $tower_sound_card
 * @property string $tower_sound_card_mark
 * @property string $tower_network_card
 * @property string $tower_network_card_mark
 * @property string $faceplate
 * @property string $faceplate_patch_panel
 * @property string $tower_value
 * @property string $tower_contract_number
 * @property string $monitor_number_inventory
 * @property string $monitor_model
 * @property string $monitor_serial
 * @property string $monitor_value
 * @property string $monitor_contract_number
 * @property string $mouse_number_inventory
 * @property string $mouse_model
 * @property string $mouse_serial
 * @property string $mouse_value
 * @property string $mouse_contract_number
 * @property string $operating_system
 * @property string $operating_system_version
 * @property string $operating_system_license
 * @property string $office_automation
 * @property string $office_automation_version
 * @property string $office_automation_license
 * @property string $antivirus
 * @property string $installed_product
 * @property string $installed_product_version
 * @property string $browser
 * @property string $browser_version
 * @property string $teamviewer
 * @property string $other
 */
class EquipmentResume extends Model {
    use SoftDeletes;

    public $table = 'ht_tic_equipment_resume';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'mouse',
        'tower',
        'keyboard',
        'monitor',
        'asset_type',
        'domain_user',
        'officer',
        'contract_type',
        'charge',
        'dependence',
        'area',
        'site',
        'service_manager',
        'maintenance_type',
        'cycle',
        'contract_number',
        'contract_date',
        'maintenance_date',
        'provider',
        'contract_value',
        'has_internal_and_external_hardware_cleaning',
        'observation_internal_and_external_hardware_cleaning',
        'has_ram_cleaning',
        'observation_ram_cleaning',
        'has_board_memory_cleaning',
        'observation_board_memory_cleaning',
        'has_power_supply_cleaning',
        'observation_power_supply_cleaning',
        'has_dvd_drive_cleaning',
        'observation_dvd_drive_cleaning',
        'has_monitor_cleaning',
        'observation_monitor_cleaning',
        'has_keyboard_cleaning',
        'observation_keyboard_cleaning',
        'has_mouse_cleaning',
        'observation_mouse_cleaning',
        'has_thermal_paste_change',
        'observation_thermal_paste_change',
        'has_heatsink_cleaning',
        'observation_heatsink_cleaning',
        'technical_report',
        'observation',
        'tower_inventory_number',
        'tower_model',
        'tower_series',
        'tower_processor',
        'tower_host',
        'tower_ram_gb',
        'tower_ram_gb_mark',
        'tower_number_ram_modules',
        'tower_mac_address',
        'tower_mainboard',
        'tower_mainboard_mark',
        'tower_ipv4_address',
        'tower_ipv6_address',
        'tower_ddh_capacity_gb',
        'tower_ddh_capacity_gb_mark',
        'tower_ssd_capacity_gb',
        'tower_ssd_capacity_gb_mark',
        'tower_video_card',
        'tower_video_card_mark',
        'tower_sound_card',
        'tower_sound_card_mark',
        'tower_network_card',
        'tower_network_card_mark',
        'faceplate',
        'faceplate_patch_panel',
        'tower_value',
        'tower_contract_number',
        'monitor_number_inventory',
        'monitor_model',
        'monitor_serial',
        'monitor_value',
        'monitor_contract_number',
        'keyboard_number_inventory',
        'keyboard_model',
        'keyboard_serial',
        'keyboard_value',
        'keyboard_contract_number',
        'mouse_number_inventory',
        'mouse_model',
        'mouse_serial',
        'mouse_value',
        'mouse_contract_number',
        'operating_system',
        'operating_system_version',
        'operating_system_license',
        'office_automation',
        'office_automation_version',
        'office_automation_license',
        'antivirus',
        'installed_product',
        'installed_product_version',
        'browser',
        'browser_version',
        'teamviewer',
        'antivirus_version',
        'antivirus_agent_version',
        'other',
        'tower_dhcp',
        'status_equipment',
        'name_user_domain',
        'tower_warranty_end_date',
        'tower_equipment_year',
        'tower_domain',
        'tower_directory_active',
        'tower_network_point',
        'id_tower_network_card',
        'id_tower_shared_folder',
        'id_tower_ssd_capacity',
        'id_tower_processor',
        'id_tower_reference',
        'id_tower_size',
        'id_tower_video_card',
        'id_tower_hdd_capacity',
        'id_tower_ram',
        'id_office_version',
        'id_storage_status',
        'id_unnecessary_app',
        'office_license_inventory',
        'date',
        'warranty_in_years',
        'contract_total_value',
        'warranty_termination_date',
        'ht_sedes_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mouse' => 'string',
        'tower' => 'string',
        'keyboard' => 'string',
        'monitor' => 'string',
        'asset_type' => 'string',
        'domain_user' => 'string',
        'officer' => 'string',
        'contract_type' => 'string',
        'charge' => 'string',
        'dependence' => 'integer',
        'area' => 'string',
        'site' => 'string',
        'service_manager' => 'string',
        'cycle' => 'string',
        'contract_number' => 'integer',
        'contract_date' => 'date',
        'maintenance_date' => 'date',
        'provider' => 'string',
        'contract_value' => 'string',
        'has_internal_and_external_hardware_cleaning' => 'string',
        'observation_internal_and_external_hardware_cleaning' => 'string',
        'has_ram_cleaning' => 'string',
        'observation_ram_cleaning' => 'string',
        'has_board_memory_cleaning' => 'string',
        'observation_board_memory_cleaning' => 'string',
        'has_power_supply_cleaning' => 'string',
        'observation_power_supply_cleaning' => 'string',
        'has_dvd_drive_cleaning' => 'string',
        'observation_dvd_drive_cleaning' => 'string',
        'has_monitor_cleaning' => 'string',
        'observation_monitor_cleaning' => 'string',
        'has_keyboard_cleaning' => 'string',
        'observation_keyboard_cleaning' => 'string',
        'has_mouse_cleaning' => 'string',
        'observation_mouse_cleaning' => 'string',
        'has_thermal_paste_change' => 'string',
        'observation_thermal_paste_change' => 'string',
        'has_heatsink_cleaning' => 'string',
        'observation_heatsink_cleaning' => 'string',
        'technical_report' => 'string',
        'observation' => 'string',
        'tower_inventory_number' => 'string',
        'tower_model' => 'string',
        'tower_series' => 'string',
        'tower_processor' => 'string',
        'tower_host' => 'string',
        'tower_ram_gb' => 'string',
        'tower_ram_gb_mark' => 'string',
        'tower_number_ram_modules' => 'string',
        'tower_mac_address' => 'string',
        'tower_mainboard' => 'string',
        'tower_mainboard_mark' => 'string',
        'tower_ipv4_address' => 'string',
        'tower_ipv6_address' => 'string',
        'tower_ddh_capacity_gb' => 'string',
        'tower_ddh_capacity_gb_mark' => 'string',
        'tower_ssd_capacity_gb' => 'string',
        'tower_ssd_capacity_gb_mark' => 'string',
        'tower_video_card' => 'string',
        'tower_video_card_mark' => 'string',
        'tower_sound_card' => 'string',
        'tower_sound_card_mark' => 'string',
        'tower_network_card' => 'string',
        'tower_network_card_mark' => 'string',
        'faceplate' => 'string',
        'faceplate_patch_panel' => 'string',
        'tower_value' => 'string',
        'tower_contract_number' => 'integer',
        'monitor_number_inventory' => 'string',
        'monitor_model' => 'string',
        'monitor_serial' => 'string',
        'monitor_value' => 'string',
        'monitor_contract_number' => 'string',
        'mouse_number_inventory' => 'string',
        'mouse_model' => 'string',
        'mouse_serial' => 'string',
        'mouse_value' => 'string',
        'mouse_contract_number' => 'string',
        'operating_system' => 'integer',
        'operating_system_version' => 'string',
        'operating_system_license' => 'string',
        'office_automation' => 'string',
        'office_automation_version' => 'string',
        'office_automation_license' => 'string',
        'antivirus' => 'string',
        'installed_product' => 'string',
        'installed_product_version' => 'string',
        'browser' => 'string',
        'browser_version' => 'string',
        'teamviewer' => 'string',
        'antivirus_version' => 'string',
        'antivirus_agent_version' => 'string',
        'other' => 'string',
        'tower_dhcp' => 'string',
        'tower_domain' => 'string',
        'tower_directory_active' => 'string',
        'tower_network_point' => 'string',
        'status_equipment'=>'string',
        'name_user_domain' =>'string',
        'warranty_end_date' => 'date',
        'equipment_year' => 'date',
        'tower_equipment_year' => 'string',
        'date'=> 'date',
        'warranty_in_years' => 'string',
        'contract_total_value' =>'string',
        'warranty_termination_date'=> 'date',
        'id_tower_network_card'=>'integer',
        'id_tower_shared_folder' => 'integer',
        'id_tower_ssd_capacity' => 'integer',
        'id_tower_processor' => 'integer',
        'id_tower_reference' => 'integer',
        'id_tower_video_card'=> 'integer',
        'id_tower_hdd_capacity'=> 'integer',
        'id_tower_ram' => 'integer',
        'id_tower_size' => 'integer',
        'id_office_version' => 'integer',
        'id_storage_status' => 'integer',
        'id_unnecessary_app' => 'integer',
        'ht_sedes_id' => 'integer',
        'office_license_inventory' => 'string',
        'tower_warranty_end_date' => 'date',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mouse' => 'nullable|string|max:50',
        'tower' => 'nullable|string|max:50',
        'keyboard' => 'nullable|string|max:50',
        'monitor' => 'nullable|string|max:50',
        'asset_type' => 'nullable|string|max:10',
        'domain_user' => 'nullable|string|max:50',
        'officer' => 'nullable|string|max:80',
        'contract_type' => 'nullable|string|max:18',
        'charge' => 'nullable|string|max:100',
        'dependence' => 'nullable|string|max:100',
        'area' => 'nullable|string|max:100',
        'site' => 'nullable|string|max:24',
        'service_manager' => 'nullable|string|max:100',
        'cycle' => 'nullable|string|max:50',
        'contract_number' => 'nullable|string',
        'contract_date' => 'nullable',
        'maintenance_date' => 'nullable',
        'provider' => 'nullable|string|max:100',
        'contract_value' => 'nullable|string|max:30',
        'has_internal_and_external_hardware_cleaning' => 'nullable|string|max:2',
        'observation_internal_and_external_hardware_cleaning' => 'nullable|string',
        'has_ram_cleaning' => 'nullable|string|max:2',
        'observation_ram_cleaning' => 'nullable|string',
        'has_board_memory_cleaning' => 'nullable|string|max:2',
        'observation_board_memory_cleaning' => 'nullable|string',
        'has_power_supply_cleaning' => 'nullable|string|max:2',
        'observation_power_supply_cleaning' => 'nullable|string|max:2000',
        'has_dvd_drive_cleaning' => 'nullable|string|max:2',
        'observation_dvd_drive_cleaning' => 'nullable|string|max:200',
        'has_monitor_cleaning' => 'nullable|string|max:2',
        'observation_monitor_cleaning' => 'nullable|string',
        'has_keyboard_cleaning' => 'nullable|string|max:2',
        'observation_keyboard_cleaning' => 'nullable|string',
        'has_mouse_cleaning' => 'nullable|string|max:2',
        'observation_mouse_cleaning' => 'nullable|string',
        'has_thermal_paste_change' => 'nullable|string|max:2',
        'observation_thermal_paste_change' => 'nullable|string',
        'has_heatsink_cleaning' => 'nullable|string|max:2',
        'observation_heatsink_cleaning' => 'nullable|string',
        'technical_report' => 'nullable|string',
        'observation' => 'nullable|string',
        'tower_inventory_number' => 'nullable|string|max:50',
        'tower_model' => 'nullable|string|max:50',
        'tower_series' => 'nullable|string|max:50',
        'tower_processor' => 'nullable|string|max:50',
        'tower_host' => 'nullable|string|max:50',
        'tower_ram_gb' => 'nullable|string|max:15',
        'tower_ram_gb_mark' => 'nullable|string|max:50',
        'tower_number_ram_modules' => 'nullable|string|max:15',
        'tower_mac_address' => 'nullable|string|max:50',
        'tower_mainboard' => 'nullable|string|max:50',
        'tower_mainboard_mark' => 'nullable|string|max:50',
        'tower_ipv4_address' => 'nullable|string|max:50',
        'tower_ipv6_address' => 'nullable|string|max:50',
        'tower_ddh_capacity_gb' => 'nullable|string|max:15',
        'tower_ddh_capacity_gb_mark' => 'nullable|string|max:50',
        'tower_ssd_capacity_gb' => 'nullable|string|max:15',
        'tower_ssd_capacity_gb_mark' => 'nullable|string|max:50',
        'tower_video_card' => 'nullable|string|max:50',
        'tower_video_card_mark' => 'nullable|string|max:50',
        'tower_sound_card' => 'nullable|string|max:50',
        'tower_sound_card_mark' => 'nullable|string|max:50',
        'tower_network_card' => 'nullable|string|max:50',
        'tower_network_card_mark' => 'nullable|string|max:50',
        'faceplate' => 'nullable|string|max:50',
        'faceplate_patch_panel' => 'nullable|string|max:50',
        'tower_value' => 'nullable|string|max:20',
        'tower_contract_number' => 'nullable|string|max:50',
        'monitor_number_inventory' => 'nullable|string|max:50',
        'monitor_model' => 'nullable|string|max:50',
        'monitor_serial' => 'nullable|string|max:50',
        'monitor_value' => 'nullable|string|max:20',
        'monitor_contract_number' => 'nullable|string|max:50',
        'mouse_number_inventory' => 'nullable|string|max:50',
        'mouse_model' => 'nullable|string|max:50',
        'mouse_serial' => 'nullable|string|max:50',
        'mouse_value' => 'nullable|string|max:20',
        'mouse_contract_number' => 'nullable|string|max:50',
        'operating_system' => 'nullable|integer',
        'operating_system_version' => 'nullable|string|max:20',
        'operating_system_license' => 'nullable|string|max:50',
        'office_automation' => 'nullable|string|max:50',
        'office_automation_version' => 'nullable|string|max:20',
        'office_automation_license' => 'nullable|string|max:50',
        'antivirus' => 'nullable|string|max:40',
        'installed_product' => 'nullable|string|max:50',
        'installed_product_version' => 'nullable|string|max:20',
        'browser' => 'nullable|string|max:35',
        'browser_version' => 'nullable|string|max:20',
        'teamviewer' => 'nullable|string|max:40',
        'other' => 'nullable|string|max:50'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function EquipmentPurchaseDetails() {
        return $this->hasMany(\Modules\HelpTable\Models\EquipmentPurchaseDetail::class, 'ht_tic_equipment_resume_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function EquipmentResumeHistories() {
        return $this->hasMany(\Modules\HelpTable\Models\EquipmentResumeHistory::class, 'ht_tic_equipment_resume_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ConfigurationOtherEquipments() {
        return $this->hasMany(\Modules\HelpTable\Models\ConfigOtherEquipment::class, 'ht_tic_equipment_resume_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ConfigurationTowerReferences() {
        return $this->belongsTo(\Modules\HelpTable\Models\ConfigTowerReference::class, 'id_tower_reference');
    }
    
     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ConfigurationTowerSize() {
        return $this->belongsTo(\Modules\HelpTable\Models\ConfigTowerSize::class, 'id_tower_size');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ConfigurationTowerProcessor() {
        return $this->belongsTo(\Modules\HelpTable\Models\ConfigTowerProcessor::class, 'id_tower_processor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ConfigurationTowerRam() {
        return $this->belongsTo(\Modules\HelpTable\Models\ConfigTowerMemoryRam::class, 'id_tower_ram');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ConfigurationTowerSsd() {
        return $this->belongsTo(\Modules\HelpTable\Models\ConfigTowerSsdCapacity::class, 'id_tower_ssd_capacity');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ConfigurationTowerHdd() {
        return $this->belongsTo(\Modules\HelpTable\Models\ConfigTowerHddCapacity::class, 'id_tower_hdd_capacity');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ConfigurationTowerVideoCard() {
        return $this->belongsTo(\Modules\HelpTable\Models\ConfigTowerVideoCard::class, 'id_tower_video_card');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ConfigurationTowerSharedFolder() {
        return $this->belongsTo(\Modules\HelpTable\Models\ConfigSharedFolder::class, 'id_tower_shared_folder');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ConfigurationNetworkCard() {
        return $this->belongsTo(\Modules\HelpTable\Models\ConfigNetworkCard::class, 'id_tower_network_card');
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ConfigurationVersionOffice() {
        return $this->belongsTo(\Modules\HelpTable\Models\ConfigOfficeVersion::class, 'id_office_version');
    }
     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ConfigurationStorageStatus() {
        return $this->belongsTo(\Modules\HelpTable\Models\ConfigStorageStatus::class, 'id_storage_status');
    }
         /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ConfigurationUnnecessaryApps() {
        return $this->belongsTo(\Modules\HelpTable\Models\ConfigUnnecessaryApps::class, 'id_unnecessary_app');
    }           /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ConfigOperationSystem() {
        return $this->belongsTo(\Modules\HelpTable\Models\ConfigOperationSystem::class, 'operating_system');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependence');
    }

    public function sedes() {
        return $this->belongsTo(\Modules\HelpTable\Models\SedeTicRequest::class, 'ht_sedes_id');
    }

    public function contractData(){
          return $this->belongsTo(
        \Modules\HelpTable\Models\EquipmentPurchaseDetail::class,
        'contract_number',  
    );

    }
}
