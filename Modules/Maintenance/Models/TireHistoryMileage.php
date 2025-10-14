<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class TireHistoryMileage
 * @package Modules\Maintenance\Models
 * @version November 7, 2024, 11:38 pm -05
 *
 * @property Modules\Maintenance\Models\MantTireInformation $mantTireInformations
 * @property Modules\Maintenance\Models\MantTireWear $mantTireWears
 * @property Modules\Maintenance\Models\MantVehicleFuel $mantVehicleFuels
 * @property integer $mant_tire_informations_id
 * @property integer $mant_vehicle_fuels_id
 * @property integer $mant_tire_wears_id
 * @property string $date_assignment
 * @property string $plaque
 * @property integer $mileage_initial
 * @property string $revision_date
 * @property number $revision_mileage
 * @property number $route_total
 * @property number $kilometraje_rodamiento
 */
class TireHistoryMileage extends Model {
    use SoftDeletes;

    public $table = 'mant_tire_history_mileage';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_tire_informations_id',
        'mant_vehicle_fuels_id',
        'mant_tire_wears_id',
        'date_assignment',
        'plaque',
        'mileage_initial',
        'revision_date',
        'revision_mileage',
        'route_total',
        'kilometraje_rodamiento'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_tire_informations_id' => 'integer',
        'mant_vehicle_fuels_id' => 'integer',
        'mant_tire_wears_id' => 'integer',
        'date_assignment' => 'date',
        'plaque' => 'string',
        'mileage_initial' => 'integer',
        'revision_date' => 'date',
        'revision_mileage' => 'float',
        'route_total' => 'float',
        'kilometraje_rodamiento' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_tire_informations_id' => 'nullable',
        'mant_vehicle_fuels_id' => 'nullable',
        'mant_tire_wears_id' => 'nullable',
        'date_assignment' => 'nullable',
        'plaque' => 'nullable|string|max:45',
        'mileage_initial' => 'nullable',
        'revision_date' => 'nullable',
        'revision_mileage' => 'nullable|numeric',
        'route_total' => 'nullable|numeric',
        'kilometraje_rodamiento' => 'nullable|numeric',
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
    public function TireInformations() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantTireInformation::class, 'mant_tire_informations_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function TireWears() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantTireWear::class, 'mant_tire_wears_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function VehicleFuels() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantVehicleFuel::class, 'mant_vehicle_fuels_id');
    }
}
