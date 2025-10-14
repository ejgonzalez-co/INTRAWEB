<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicMaintenance;
use App\Repositories\BaseRepository;

/**
 * Class TicMaintenanceRepository
 * @package Modules\HelpTable\Repositories
 * @version June 5, 2021, 10:15 am -05
*/

class TicMaintenanceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ht_tic_assets_id',
        'ht_tic_provider_id',
        'ht_tic_requests_id',
        'dependencias_id',
        'type_maintenance',
        'fault_description',
        'service_start_date',
        'end_date_service',
        'maintenance_description',
        'contract_number',
        'cost',
        'warranty_start_date',
        'warranty_end_date',
        'maintenance_status',
        'provider_name',
        'user_id',
        'user_name',
        'id_tower_inven,tory',
        'asset_type',
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
        return TicMaintenance::class;
    }
}
