<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TireWears
 * @package Modules\Maintenance\Models
 * @version September 8, 2021, 5:33 pm -05
 *
 * @property Modules\Maintenance\Models\ManttireInformations $manttireInformations
 * @property integer $mant_tire_informations_id
 * @property number $registration_depth
 * @property string $revision_date
 * @property number $wear_total
 * @property number $revision_mileage
 * @property number $route_total
 * @property number $wear_cost_mm
 * @property number $cost_km
 * @property integer $revision_pressure
 * @property string $observation
 */
class TireWears extends Model {
    use SoftDeletes;

    public $table = 'mant_tire_wears';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_tire_informations_id',
        'mant_resume_machinery_vehicles_yellow_id',
        'data_log_depth',
        'registration_depth',
        'revision_date',
        'wear_total',
        'revision_mileage',
        'route_total',
        'wear_cost_mm',
        'cost_km',
        'revision_pressure',
        'observation',
        'max_wear'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_resume_machinery_vehicles_yellow_id' => 'integer',
        'mant_tire_informations_id' => 'integer',
        'data_log_depth'=>'string',
        'registration_depth' => 'float',
        'revision_date' => 'string',
        'wear_total' => 'float',
        'revision_mileage' => 'float',
        'route_total' => 'float',
        'wear_cost_mm' => 'float',
        'cost_km' => 'float',
        'revision_pressure' => 'float',
        'observation' => 'string',
        'max_wear' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'mant_tire_informations_id' => 'required',
        'data_log_depth'=>'nullable|string|max:1000',
        'registration_depth' => 'nullable|numeric',
        'revision_date' => 'nullable',
        'wear_total' => 'nullable|numeric',
        'revision_mileage' => 'nullable|numeric',
        'route_total' => 'nullable|numeric',
        'wear_cost_mm' => 'nullable|numeric',
        'cost_km' => 'nullable|numeric',
        'revision_pressure' => 'nullable|numeric',
        'observation' => 'nullable|string',
        'max_wear'=>'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = [
        'mileage_initial',
        'depth_tire',
        'general_cost_mm'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tireInformations() {
        return $this->belongsTo(\Modules\Maintenance\Models\TireInformations::class, 'mant_tire_informations_id')->with('VehiclesFuels');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ResumeMachineryVehiclesYellow() {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeMachineryVehiclesYellow::class, 'mant_resume_machinery_vehicles_yellow_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function RecordDepth() {
        return $this->hasMany(\Modules\Maintenance\Models\TireRecordDepth::class, 'mant_tire_wears_id');
    }

    
    public function getMileageInitialAttribute(){
        $tireInformations = 0;
        $tireInformations = $this->tireInformations->mileage_initial;
        
        return $tireInformations;
    }

    public function getDepthTireAttribute(){
        $tireInformations = 0;
        $tireInformations = $this->tireInformations->depth_tire;
        
        return $tireInformations;
    }

    public function getGeneralCostMmAttribute(){
        $tireInformations = 0;
        $tireInformations = $this->tireInformations->general_cost_mm;
        
        return $tireInformations;
    }



}
