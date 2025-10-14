<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use DateTimeInterface;

/**
 * Class ResumeInventoryLeca
 * @package Modules\Maintenance\Models
 * @version May 6, 2021, 5:24 pm -05
 *
 * @property Modules\Maintenance\Models\MantCategory $mantCategory
 * @property \Illuminate\Database\Eloquent\Collection $mantScheduleInventoryLecas
 * @property string $no_inventory_epa_esp
 * @property string $leca_code
 * @property string $description_equipment_name
 * @property string $maker
 * @property string $serial_number
 * @property string $model
 * @property string $location
 * @property string $measured_used
 * @property string $unit_measurement
 * @property string $resolution
 * @property string $manufacturer_error
 * @property string $operation_range
 * @property string $range_use
 * @property string $operating_conditions_temperature
 * @property string $condition_oper_elative_humidity_hr
 * @property string $condition_oper_voltage_range
 * @property string $maintenance_metrological_operation_frequency
 * @property string $calibration_metrological_operating_frequency
 * @property string $qualification_metrological_operating_frequency
 * @property string $intermediate_verification_metrological_operating_frequency
 * @property string $total_interventions
 * @property string $name_elaborated
 * @property string $cargo_role_elaborated
 * @property string $name_updated
 * @property string $cargo_role_updated
 * @property string $technical_director
 * @property integer $mant_category_id
 * @property integer $responsable
 */
class ResumeInventoryLeca extends Model
{
    use SoftDeletes;

    public $table = 'mant_inventory_metrological_schedule_leca';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'no_inventory_epa_esp',
        'leca_code',
        'description_equipment_name',
        'maker',
        'serial_number',
        'model',
        'location',
        'measured_used',
        'unit_measurement',
        'resolution',
        'manufacturer_error',
        'operation_range',
        'range_use',
        'operating_conditions_temperature',
        'condition_oper_elative_humidity_hr',
        'condition_oper_voltage_range',
        'maintenance_metrological_operation_frequency',
        'calibration_metrological_operating_frequency',
        'qualification_metrological_operating_frequency',
        'intermediate_verification_metrological_operating_frequency',
        'total_interventions',
        'name_elaborated',
        'cargo_role_elaborated',
        'name_updated',
        'cargo_role_updated',
        'technical_director',
        'mant_category_id',
        'dependencias_id',
        'responsable',
        'mant_providers_id',
        'mant_asset_type_id',
        'observation',
        'status'
    ];

    protected $appends = [
        'name_general',
        'name_vehicle_machinery',
        'no_inventory',
        'mantenances_actives'
    ];

    public function getMantenancesActivesAttribute()
    {
        $currentYear = Carbon::now()->year;

        if (empty($this->no_inventory_epa_esp)) {
            return [];
        }else{
            return AssetManagement::where('nombre_activo', 'like', '%' . $this->no_inventory_epa_esp . '%')
                ->whereYear('created_at', $currentYear)
                ->get()
                ->map(function ($item) {
                    $item->id_encripted = base64_encode($item->id);
                    return $item;
                });

        }
    }


    public function getNameGeneralAttribute(){
        return $this->description_equipment_name;
    }

    public function getNameVehicleMachineryAttribute(){
        return $this->description_equipment_name;
    }

    public function getNoInventoryAttribute(){
        return $this->no_inventory_epa_esp;
    }

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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'no_inventory_epa_esp' => 'string',
        'leca_code' => 'string',
        'description_equipment_name' => 'string',
        'maker' => 'string',
        'serial_number' => 'string',
        'model' => 'string',
        'location' => 'string',
        'measured_used' => 'string',
        'unit_measurement' => 'string',
        'resolution' => 'string',
        'manufacturer_error' => 'string',
        'operation_range' => 'string',
        'range_use' => 'string',
        'operating_conditions_temperature' => 'string',
        'condition_oper_elative_humidity_hr' => 'string',
        'condition_oper_voltage_range' => 'string',
        'maintenance_metrological_operation_frequency' => 'string',
        'calibration_metrological_operating_frequency' => 'string',
        'qualification_metrological_operating_frequency' => 'string',
        'intermediate_verification_metrological_operating_frequency' => 'string',
        'total_interventions' => 'string',
        'name_elaborated' => 'string',
        'cargo_role_elaborated' => 'string',
        'name_updated' => 'string',
        'cargo_role_updated' => 'string',
        'technical_director' => 'string',
        'mant_category_id' => 'integer',
        'dependencias_id' => 'integer',
        'responsable' => 'integer',
        'mant_asset_type_id' => 'integer',
        'observation' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_category_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantCategory()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\Category::class, 'mant_category_id')->withTrashed()->with(["mantAssetType"]);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantDocumentsAsset()
    {
        return $this->hasMany(\Modules\Maintenance\Models\DocumentsAssets::class, 'mant_inventory_metrological_schedule_leca_id');
        // return $this->hasMany(\Modules\Maintenance\Models\DocumentsAssets::class, 'mant_resume_machinery_vehicles_yellow_id')->where('form_type', '=', 5);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function scheduleInventoryLeca()
    {
        return $this->hasMany(\Modules\Maintenance\Models\ScheduleInventoryLeca::class, 'mant_inventory_metrological_schedule_leca_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function provider()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\Providers::class, 'mant_providers_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function assetType()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\AssetType::class, 'mant_asset_type_id');
    }
}
