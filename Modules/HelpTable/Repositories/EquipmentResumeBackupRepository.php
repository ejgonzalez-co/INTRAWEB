<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\EquipmentResumeBackup;
use App\Repositories\BaseRepository;

/**
 * Class EquipmentResumeBackupRepository
 * @package Modules\HelpTable\Repositories
 * @version January 25, 2023, 2:13 pm -05
*/

class EquipmentResumeBackupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ht_tic_equipment_resume_id',
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
        'other'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return EquipmentResumeBackup::class;
    }
}
