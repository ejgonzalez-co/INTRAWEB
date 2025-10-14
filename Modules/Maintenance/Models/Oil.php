<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class Oil
 * @package Modules\Maintenance\Models
 * @version December 17, 2021, 11:17 am -05
 *
 * @property Modules\Maintenance\Models\MantResumeMachineryVehiclesYellow $mantResumeMachineryVehiclesYellow
 * @property \Illuminate\Database\Eloquent\Collection $mantOilControlLaboratories
 * @property \Illuminate\Database\Eloquent\Collection $mantOilDocuments
 * @property \Illuminate\Database\Eloquent\Collection $mantOilElementWears
 * @property integer $mant_resume_machinery_vehicles_yellow_id
 * @property integer $mant_oil_element_wear_configurations_id
 * @property string|\Carbon\Carbon $register_date
 * @property string $show_type
 * @property string $component
 * @property integer $serial_number
 * @property string $brand
 * @property integer $model
 * @property string $job_place
 * @property integer $number_warranty_extended
 * @property string $work_order
 * @property string $serial_component
 * @property string $model_component
 * @property string $maker_component
 * @property string $number_control_lab
 * @property string $grade_oil
 * @property string $type_fluid
 * @property string $date_finished
 */
class Oil extends Model {
    use SoftDeletes;

    public $table = 'mant_oils';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    public $fillable = [
        'mant_resume_machinery_vehicles_yellow_id',
        'mant_vehicle_fuels_id',
        'register_date',
        'show_type',
        'component',
        'serial_number',
        'brand',
        'model',
        'job_place',
        'number_warranty_extended',
        'work_order',
        'serial_component',
        'model_component',
        'maker_component',
        'number_control_lab',
        'grade_oil',
        'type_fluid',
        'date_finished_warranty_extended',
        'dependencies_id',
        'mant_asset_type_id',
        'asset_name',
        'equipment_number',
        'component_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_resume_machinery_vehicles_yellow_id' => 'integer',
        'mant_vehicle_fuels_id' => 'integer',
        'register_date' => 'datetime',
        'show_type' => 'string',
        'component' => 'string',
        'serial_number' => 'string',
        'brand' => 'string',
        'model' => 'integer',
        'job_place' => 'string',
        'number_warranty_extended' => 'string',
        'work_order' => 'string',
        'serial_component' => 'string',
        'model_component' => 'string',
        'maker_component' => 'string',
        'number_control_lab' => 'string',
        'grade_oil' => 'string',
        'type_fluid' => 'string',
        'date_finished_warranty_extended' => 'datetime',
        'dependencies_id' => 'integer',
        'mant_asset_type_id' => 'integer',
        'asset_name' => 'string',
        'equipment_number' => 'string',
        'component_name' => 'string'

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_resume_machinery_vehicles_yellow_id' => 'required|integer',
        'register_date' => 'nullable',
        'show_type' => 'nullable|string|max:100',
        'component' => 'nullable|string|max:100',
        'serial_number' => 'nullable|string|max:100',
        'brand' => 'nullable|string|max:100',
        'job_place' => 'nullable|string|max:100',
        'number_warranty_extended' => 'nullable|string',
        'work_order' => 'nullable|string|max:100',
        'serial_component' => 'nullable|string|max:100',
        'model_component' => 'nullable|string|max:100',
        'maker_component' => 'nullable|string|max:100',
        'number_control_lab' => 'nullable|string|max:100',
        'grade_oil' => 'nullable|string|max:100',
        'type_fluid' => 'nullable|string|max:100',
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
    public function oilElementWearConfigurations() {
        return $this->belongsTo(\Modules\Maintenance\Models\OilElementWearConfiguration::class, 'mant_oil_element_wear_configurations_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function assetType() {
        return $this->belongsTo(\Modules\Maintenance\Models\AssetType::class, 'mant_asset_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function resumeMachineryVehiclesYellow() {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeMachineryVehiclesYellow::class, 'mant_resume_machinery_vehicles_yellow_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function oilControlLaboratories() {
        return $this->hasMany(\Modules\Maintenance\Models\OilControlLaboratory::class, 'mant_oils_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function oilDocuments() {
        return $this->hasMany(\Modules\Maintenance\Models\OilDocument::class, 'mant_oils_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function oilElementWears() {
        return $this->hasMany(\Modules\Maintenance\Models\OilElementWear::class, 'mant_oils_id')->with(['oilElementWearConfigurations']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias()
    {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencies_id');
    }
}
