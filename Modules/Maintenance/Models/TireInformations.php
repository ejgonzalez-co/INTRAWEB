<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TireInformation
 * @package Modules\Maintenance\Models
 * @version September 7, 2021, 2:45 pm -05
 *
 * @property Modules\Maintenance\Models\MantTireQuantity $mantTireQuantities
 * @property \Illuminate\Database\Eloquent\Collection $mantTireWears
 * @property integer $mant_tire_quantities_id
 * @property string $date_register
 * @property integer $position_tire
 * @property string $type_tire
 * @property integer $cost_tire
 * @property number $depth_tire
 * @property integer $mileage_initial
 * @property number $available_depth
 * @property number $general_cost_mm
 * @property string $location_tire
 * @property string $code_tire
 * @property string $observation_information
 * @property string $state
 */
class TireInformations extends Model {
    use SoftDeletes;

    public $table = 'mant_tire_informations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_tire_quantities_id',
        'mant_resume_machinery_vehicles_yellow_id',
        'mant_vehicle_fuels_id',
        'dependencias_id',
        'name_dependencias',
        'name_machinery',
        'plaque',
        'assignment_type',
        'date_assignment',
        'mant_set_tires_id',
        'date_register',
        'tire_reference',
        'position_tire',
        'type_tire',
        'cost_tire',
        'depth_tire',
        'mileage_initial',
        'available_depth',
        'general_cost_mm',
        'location_tire',
        'code_tire',
        'tire_brand',
        'observation_information',
        'state',
        'tire_wear',
        'tire_pressure',
        'max_wear',
        'inflation_pressure',
        'max_wear_for_retorquing',
        'reference_name',
        'kilometraje_rodamiento'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_tire_quantities_id' => 'integer',
        'mant_resume_machinery_vehicles_yellow_id' => 'integer',
        'mant_vehicle_fuels_id' => 'integer',
        'dependencias_id' => 'integer',
        'name_dependencias'=>'string',
        'name_machinery'=>'string',
        'plaque' => 'string',
        'date_assignment' => 'string',
        'assignment_type' => 'string',
        'mant_set_tires_id' => 'integer',
        'date_register' => 'string',
        'tire_reference' => 'string',
        'position_tire' => 'integer',
        'type_tire' => 'string',
        'cost_tire' => 'string',
        'depth_tire' => 'float',
        'mileage_initial' => 'integer',
        'available_depth' => 'float',
        'general_cost_mm' => 'string',
        'location_tire' => 'string',
        'code_tire' => 'string',
        'tire_brand' => 'string',
        'observation_information' => 'string',
        'state' => 'string',
        'tire_wear' => 'float',
        'tire_pressure' => 'string',
        'max_wear'=>'string',
        'kilometraje_rodamiento'=> 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'mant_tire_quantities_id' => 'required',
        // 'mant_set_tires_id'=>'required',
        // 'dependencias_id' => 'required',
        'name_dependencias' => 'nullable|string|max:80',
        'name_machinery'=>'nullable|string|max:60',
        'plaque' => 'nullable|string|max:255',
        'assignment_type' => 'nullable|string|max:60',
        'date_assignment' => 'nullable',
        'date_register' => 'nullable',
        'tire_reference' => 'nullable|string|max:150',
        'position_tire' => 'nullable|integer',
        'type_tire' => 'nullable|string|max:20',
        'cost_tire' => 'nullable|string|max:80',
        'depth_tire' => 'nullable|numeric',
        'mileage_initial' => 'nullable',
        'available_depth' => 'nullable|numeric',
        'general_cost_mm' => 'nullable|string|max:80',
        'location_tire' => 'nullable|string|max:20',
        'code_tire' => 'nullable|string|max:255',
        'tire_brand' => 'nullable|string|max:150',
        'observation_information' => 'nullable|string',
        'state' => 'nullable|string|max:80',
        'tire_wear' => 'nullable|numeric',
        'tire_pressure' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'max_wear'=> 'nullable|string|max:300',
    ];

    protected $appends = [
        'general_cost_mm_name',
        'tire_brand_name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function TireWears() {
        return $this->hasMany(\Modules\Maintenance\Models\TireWears::class, 'mant_tire_informations_id')->limit('3')->latest();
    }

    public function VehiclesFuels() {
        return $this->belongsTo(\Modules\Maintenance\Models\VehicleFuel::class, 'mant_vehicle_fuels_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function TireHistoryMileage() {
        return $this->hasMany(\Modules\Maintenance\Models\TireHistoryMileage::class, 'mant_tire_informations_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function TireHistories() {
        return $this->hasMany(\Modules\Maintenance\Models\TireHistory::class, 'mant_tire_informations_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function excel() {
        return $this->hasMany(\Modules\Maintenance\Models\TireWears::class, 'mant_tire_informations_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function SetTire() {
        return $this->belongsTo(\Modules\Maintenance\Models\SetTire::class, 'mant_set_tires_id');
    }

    //Pasa el valor inicial de general_cost_mm para poder editarlo
    public function getGeneralCostMmNameAttribute(){
        $tireInformations = 0;
        $tireInformations = TireInformations::find('general_cost_mm');
        
        return $tireInformations;
    }

    public function getTireBrandNameAttribute(){
        $tireBrand = TireBrand::select("brand_name")->where("id",$this->tire_brand)->first();
        if($tireBrand != null){
            return $tireBrand->brand_name;
        }
        return "";
    }

            /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ResumeMachineryVehiclesYellow() {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeMachineryVehiclesYellow::class, 'mant_resume_machinery_vehicles_yellow_id');
    }

}
