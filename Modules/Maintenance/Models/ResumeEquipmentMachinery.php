<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use DateTimeInterface;


/**
 * Class ResumeEquipmentMachinery
 * @package Modules\Maintenance\Models
 * @version March 23, 2021, 4:59 pm -05
 *
 * @property Modules\Maintenance\Models\Dependencia $dependencias
 * @property Modules\Maintenance\Models\MantCategory $mantCategory
 * @property Modules\Maintenance\Models\MantProvider $mantProviders
 * @property string $name_equipment
 * @property string $no_identification
 * @property string $no_inventory
 * @property string $mark
 * @property string $model
 * @property string $no_motor
 * @property string $serie
 * @property string $ubication
 * @property string $acquisition_contract
 * @property string $no_invoice
 * @property integer $purchase_price
 * @property string $equipment_warranty
 * @property string $equipment_operation
 * @property integer $no_batteries
 * @property string $batteries_reference
 * @property integer $no_tires
 * @property string $tire_reference
 * @property string $fuel_type
 * @property string $fuel_capacity
 * @property string $use_oil
 * @property string $oil_capacity
 * @property string $requires_calibration
 * @property string $periodicity_calibration
 * @property string $oil_change
 * @property string $periodicity_oil_change
 * @property string $fluid_change
 * @property string $periodicity_fluid_change
 * @property string $preventive_maintenance
 * @property string $periodicity_preventive_maintenance
 * @property string $electrical_checks
 * @property string $periodicity_electrical_checks
 * @property string $mechanical_checks
 * @property string $periodicity_mechanical_checks
 * @property string $general_cleaning
 * @property string $periodicity_general_cleaning
 * @property string $license_expiration
 * @property string $periodicity_license_expiration
 * @property string $warranty_expiration
 * @property string $periodicity_warranty_expiration
 * @property string $insured_asset
 * @property string $periodicity_insured_asset
 * @property string $rated_current
 * @property string $periodicity_rated_current
 * @property string $rated_power
 * @property string $periodicity_rated_power
 * @property string $maximum_voltage
 * @property string $minimun_voltage
 * @property string $revolutions
 * @property string $useful_life
 * @property string $transportation_precaution
 * @property string $capacity_force_hp
 * @property string $protection_type
 * @property string $minimum_permissible_error
 * @property string $maximun_permissible_error
 * @property string $calibration_point
 * @property string $certified_calibration
 * @property integer $no_entry_warehouse
 * @property integer $no_exit_warehouse
 * @property string $warehouse_entry_date
 * @property string $warehouse_exit_date
 * @property string $service_start_date
 * @property string $retirement_date
 * @property string $frecuency_use_month
 * @property string $frecuency_use_hours
 * @property string $operates_equipment
 * @property string $observation
 * @property string $status
 * @property integer $dependencias_id
 * @property integer $mant_category_id
 * @property integer $responsable
 * @property integer $mant_providers_id
 */
class ResumeEquipmentMachinery extends Model
{
    use SoftDeletes;

    public $table = 'mant_resume_equipment_machinery';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [

        'warehouse_entry_number',
        'type_number_the_acquisition_contract',
        'requirement_for_operation',
        'description_for_operation',
        'consecutive',
        'catalog_specifications',
        'location',
        'language',
        'version',
        'technical_verification',
        'technical_verification_frequency',
        'preventive_maintenance',
        'preventive_maintenance_frequency',
        'person_responsible_team',
        'person_prepares_resume_equipment_machinery',
        'purchase_date',
        'name_equipment',
        'no_identification',
        'no_inventory',
        'mark',
        'model',
        'no_motor',
        'serie',
        'ubication',
        'no_invoice',
        'purchase_price',
        'equipment_warranty',
        'service_start_date',
        'retirement_date',
        'observation',
        'status',
        'dependencias_id',
        'mant_category_id',
        'responsable',
        'mant_providers_id',
        'mant_asset_type_id',
        'observation',
        'status',
        'fuel_type'
    ];

    protected $appends = [
        'name_general',
        'name_vehicle_machinery',
        'rubros_asignados',
        'mantenances_actives'
    ];

    public function getMantenancesActivesAttribute()
    {
        $currentYear = Carbon::now()->year;


        if (empty($this->no_inventory)) {
            return [];
        }else{
            return AssetManagement::where('nombre_activo', 'like', '%' . $this->no_inventory . '%')
                ->whereYear('created_at', $currentYear)
                ->get()
                ->map(function ($item) {
                    $item->id_encripted = base64_encode($item->id);
                    return $item;
                });

        }
    }

    public function getNameGeneralAttribute(){
        return $this->name_equipment;
    }

    public function getNameVehicleMachineryAttribute(){
        return $this->name_equipment;
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
        'name_equipment' => 'string',
        'no_identification' => 'string',
        'no_inventory' => 'string',
        'mark' => 'string',
        'model' => 'string',
        'no_motor' => 'string',
        'serie' => 'string',
        'ubication' => 'string',
        'type_number_the_acquisition_contract' => 'string',
        'no_invoice' => 'string',
        'purchase_price' => 'float',
        'equipment_warranty' => 'string',
        'consecutive' => 'string',
        'observation' => 'string',
        'status' => 'string',
        'dependencias_id' => 'integer',
        'mant_category_id' => 'integer',
        'responsable' => 'integer',
        'mant_providers_id' => 'integer',
        'mant_asset_type_id' => 'integer',
        'observation' => 'string',
        'fuel_type' => 'string'
        
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function provider()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\Providers::class, 'mant_providers_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantDocumentsAsset()
    {
        return $this->hasMany(\Modules\Maintenance\Models\DocumentsAssets::class, 'mant_resume_equipment_machinery_id');
        // return $this->hasMany(\Modules\Maintenance\Models\DocumentsAssets::class, 'mant_resume_machinery_vehicles_yellow_id')->where('form_type', '=', 2);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function characteristicsEquipment()
    {
        return $this->hasMany(\Modules\Maintenance\Models\CharacteristicsEquipment::class, 'mant_resume_equipment_machinery_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function assetType()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\AssetType::class, 'mant_asset_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany rubros_asignados
     **/
    public function rubrosAsignados()
    {
        return $this->hasMany(\Modules\Maintenance\Models\SnActivesHeading::class, 'activo_id')
        // ->where('activo_tipo', 'ResumeEquipmentMachinery')->with('rubro','centrocosto');
                    ->where('activo_tipo', 'ResumeEquipmentMachinery');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany rubros_asignados
     **/
    public function getRubrosAsignadosAttribute()
    {
        return \Modules\Maintenance\Models\SnActivesHeading::where("activo_id",$this->id)->where('activo_tipo', 'ResumeEquipmentMachinery')->get()->toArray();
    }


}
