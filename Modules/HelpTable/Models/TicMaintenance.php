<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;
use DateTimeInterface;

/**
 * Class TicMaintenance
 * @package Modules\HelpTable\Models
 * @version June 5, 2021, 10:15 am -05
 *
 * @property \Modules\HelpTable\Models\Dependencia $dependencias
 * @property \Modules\HelpTable\Models\TicAsset $htTicAssets
 * @property \Modules\HelpTable\Models\TicProvider $htTicProvider
 * @property \Modules\HelpTable\Models\TicRequest $htTicRequests
 * @property integer $ht_tic_assets_id
 * @property integer $ht_tic_provider_id
 * @property integer $ht_tic_requests_id
 * @property integer $dependencias_id
 * @property integer $type_maintenance
 * @property string $fault_description
 * @property string $service_start_date
 * @property string $end_date_service
 * @property string $maintenance_description
 * @property string $contract_number
 * @property number $cost
 * @property string $warranty_start_date
 * @property string $warranty_end_date
 * @property integer $maintenance_status
 * @property string $provider_name
 */
class TicMaintenance extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_maintenances';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
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
        'id_tower_inventory'
    ];

    protected $appends = [
        'type_maintenance_name',
        'maintenance_status_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ht_tic_assets_id' => 'integer',
        'ht_tic_provider_id' => 'integer',
        'ht_tic_requests_id' => 'integer',
        'dependencias_id' => 'integer',
        'type_maintenance' => 'integer',
        'fault_description' => 'string',
        'service_start_date' => 'date',
        'end_date_service' => 'date',
        'maintenance_description' => 'string',
        'contract_number' => 'string',
        'cost' => 'float',
        'warranty_start_date' => 'date',
        'warranty_end_date' => 'date',
        'maintenance_status' => 'integer',
        'provider_name' => 'string',
        'id_tower_inventory' =>'integer',
        'asset_type' => 'string',
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
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ht_tic_assets_id' => 'nullable',
        'ht_tic_provider_id' => 'nullable',
        'ht_tic_requests_id' => 'nullable',
        'dependencias_id' => 'nullable',
        'type_maintenance' => 'required|integer',
        'fault_description' => 'nullable|string',
        'service_start_date' => 'nullable',
        'end_date_service' => 'nullable',
        'maintenance_description' => 'nullable|string',
        'contract_number' => 'nullable|string|max:100',
        'cost' => 'nullable|numeric',
        'warranty_start_date' => 'nullable',
        'warranty_end_date' => 'nullable',
        'maintenance_status' => 'required|integer',
        'provider_name' => 'nullable|string|max:80',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ticAssets() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicAsset::class, 'ht_tic_assets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ticProvider() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicProvider::class, 'ht_tic_provider_id')->with('users');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ticRequests() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicRequest::class, 'ht_tic_requests_id');
    }

    /**
     * Obtiene el nombre del tipo de mantenimiento
     * @return 
     */
    public function getTypeMaintenanceNameAttribute() {
        return AppBaseController::getObjectOfList(config('help_table.type_maintenance_tic'), 'id', $this->type_maintenance)->name;
    }

    /**
     * Obtiene el nombre del estado del mantenimiento
     * @return 
     */
    public function getMaintenanceStatusNameAttribute() {
        return AppBaseController::getObjectOfList(config('help_table.maintenance_status_tic'), 'id', $this->maintenance_status)->name;
    }
    
}
