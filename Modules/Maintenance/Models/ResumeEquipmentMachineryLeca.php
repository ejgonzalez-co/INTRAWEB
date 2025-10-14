<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use DateTimeInterface;

/**
 * Class ResumeEquipmentMachineryLeca
 * @package Modules\Maintenance\Models
 * @version April 29, 2021, 11:42 am -05
 *
 * @property Modules\Maintenance\Models\MantCategory $mantCategory
 * @property string $name_equipment_machinery
 * @property string $no_identification
 * @property string $no_inventory_epa_esp
 * @property string $mark
 * @property string $model
 * @property string $serie
 * @property string $location
 * @property string $path_information
 * @property string $acquisition_contract
 * @property string $provider_data
 * @property string $apply
 * @property string $location_specification
 * @property string $language
 * @property string $version
 * @property string $purchase_date
 * @property string $commissioning_date
 * @property string $date_withdrawal_service
 * @property string $observations
 * @property string $vo_bo_name
 * @property string $vo_bo_cargo
 * @property string $magnitude
 * @property string $unit_measurement
 * @property string $scale_division
 * @property string $manufacturer_specification_max_permissible_error
 * @property string $max_permissible_error_technical_standard_process
 * @property string $measurement_range
 * @property string $operation_range
 * @property string $use_parameter
 * @property string $use_recommendations
 * @property string $resolution
 * @property string $analog_indication
 * @property string $digital_indication
 * @property string $wavelength_indication
 * @property string $adsorption_indication
 * @property string $feeding
 * @property string $voltage
 * @property string $RH
 * @property string $power
 * @property string $temperature
 * @property string $frequency
 * @property string $revolutions_per_minute
 * @property string $type_protection
 * @property string $rated_current
 * @property string $rated_power
 * @property string $operating_conditions
 * @property string $calibration_validation_external_verification
 * @property string $calibration_frequency
 * @property string $preventive_maintenance
 * @property string $maintenance_frequency
 * @property string $verification_internal_verification
 * @property string $verification_frequency
 * @property string $procedure_code
 * @property string $calibration_points
 * @property string $calibration_under_accreditation
 * @property string $reference_norm
 * @property string $measure_pattern
 * @property string $criteria_acceptance
 * @property string $calibration_test
 * @property integer $dependencias_id
 * @property integer $mant_category_id
 * @property integer $responsable
 */
class ResumeEquipmentMachineryLeca extends Model
{
    use SoftDeletes;

    public $table = 'mant_resume_equipment_machinery_leca';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name_equipment_machinery',
        'no_identification',
        'no_inventory_epa_esp',
        'mark',
        'model',
        'serie',
        'purchase_price',
        'location',
        'path_information',
        'acquisition_contract',
        'provider_data',
        'apply',
        'location_specification',
        'language',
        'version',
        'purchase_date',
        'commissioning_date',
        'date_withdrawal_service',
        'observations',
        'observation_composition',
        'vo_bo_name',
        'vo_bo_cargo',
        'magnitude',
        'unit_measurement',
        'scale_division',
        'manufacturer_specification_max_permissible_error',
        'max_permissible_error_technical_standard_process',
        'measurement_range',
        'operation_range',
        'use_parameter',
        'use_recommendations',
        'resolution',
        'analog_indication',
        'digital_indication',
        'wavelength_indication',
        'adsorption_indication',
        'feeding',
        'voltage',
        'RH',
        'power',
        'temperature',
        'frequency',
        'revolutions_per_minute',
        'type_protection',
        'rated_current',
        'rated_power',
        'operating_conditions',
        // 'calibration_validation_external_verification',
        'calibration_frequency',
        // 'preventive_maintenance',
        'maintenance_frequency',
        // 'verification_internal_verification',
        'verification_frequency',
        'procedure_code',
        // 'calibration_points',
        // 'calibration_under_accreditation',
        // 'reference_norm',
        // 'measure_pattern',
        // 'criteria_acceptance',
        // 'calibration_test',
        'dependencias_id',
        'mant_category_id',
        'responsable',
        'mant_providers_id',
        'mant_asset_type_id',
        'observation',
        'status'
    ];

    protected $appends = [
        'name_general',
        'no_inventory',
        'name_vehicle_machinery',
        'mantenances_actives'
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
        return $this->name_equipment_machinery;
    }

    /**
     * Castea el número de inventario a la variable no_inventory
     * @return  el número de inventario
     */
    public function getNoInventoryAttribute() {
        return $this->no_inventory_epa_esp;
    }

    public function getNameVehicleMachineryAttribute(){
        return $this->name_equipment_machinery;
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name_equipment_machinery' => 'string',
        'no_identification' => 'string',
        'no_inventory_epa_esp' => 'string',
        'mark' => 'string',
        'model' => 'string',
        'serie' => 'string',
        'purchase_price' => 'float',
        'location' => 'string',
        'path_information' => 'string',
        'acquisition_contract' => 'string',
        'provider_data' => 'string',
        'apply' => 'string',
        'location_specification' => 'string',
        'language' => 'string',
        'version' => 'string',
        'purchase_date' => 'string',
        'commissioning_date' => 'string',
        'date_withdrawal_service' => 'string',
        'observations' => 'string',
        'observation_composition' => 'string',
        'vo_bo_name' => 'string',
        'vo_bo_cargo' => 'string',
        'magnitude' => 'string',
        'unit_measurement' => 'string',
        'scale_division' => 'string',
        'manufacturer_specification_max_permissible_error' => 'string',
        'max_permissible_error_technical_standard_process' => 'string',
        'measurement_range' => 'string',
        'operation_range' => 'string',
        'use_parameter' => 'string',
        'use_recommendations' => 'string',
        'resolution' => 'string',
        'analog_indication' => 'string',
        'digital_indication' => 'string',
        'wavelength_indication' => 'string',
        'adsorption_indication' => 'string',
        'feeding' => 'string',
        'voltage' => 'string',
        'RH' => 'string',
        'power' => 'string',
        'temperature' => 'string',
        'frequency' => 'string',
        'revolutions_per_minute' => 'string',
        'type_protection' => 'string',
        'rated_current' => 'string',
        'rated_power' => 'string',
        'operating_conditions' => 'string',
        // 'calibration_validation_external_verification' => 'string',
        'calibration_frequency' => 'string',
        // 'preventive_maintenance' => 'string',
        'maintenance_frequency' => 'string',
        // 'verification_internal_verification' => 'string',
        'verification_frequency' => 'string',
        'procedure_code' => 'string',
        'calibration_points' => 'string',
        'calibration_under_accreditation' => 'string',
        'reference_norm' => 'string',
        'measure_pattern' => 'string',
        'criteria_acceptance' => 'string',
        'calibration_test' => 'string',
        'dependencias_id' => 'integer',
        'mant_category_id' => 'integer',
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
        'dependencias_id' => 'required',
        'mant_category_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencies()
    {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id')->withTrashed();
    }

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
        return $this->hasMany(\Modules\Maintenance\Models\DocumentsAssets::class, 'mant_resume_equipment_machinery_leca_id');
        // return $this->hasMany(\Modules\Maintenance\Models\DocumentsAssets::class, 'mant_resume_machinery_vehicles_yellow_id')->where('form_type', '=', 3);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function compositionEquipmentLeca()
    {
        return $this->hasMany(\Modules\Maintenance\Models\CompositionEquipmentLeca::class, 'mant_resume_equipment_machinery_leca_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function maintenanceEquipmentLeca()
    {
        return $this->hasMany(\Modules\Maintenance\Models\MaintenanceEquipmentLeca::class, 'mant_resume_equipment_machinery_leca_id');
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