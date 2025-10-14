<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FuelDocument
 * @package Modules\Maintenance\Models
 * @version August 20, 2021, 8:50 am -05
 *
 * @property Modules\Maintenance\Models\MantVehicleFuel $mantVehicleFuels
 * @property integer $mant_vehicle_fuels_id
 * @property string $name
 * @property string $description
 * @property string $url_document_fuel
 */
class FuelDocument extends Model {
    use SoftDeletes;

    public $table = 'mant_fuel_documents';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_vehicle_fuels_id',
        'name',
        'description',
        'url_document_fuel'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_vehicle_fuels_id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'url_document_fuel' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        // 'url_document_fuel' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function vehicleFuels() {
        return $this->belongsTo(\Modules\Maintenance\Models\VehicleFuel::class, 'mant_vehicle_fuels_id');
    }
}
