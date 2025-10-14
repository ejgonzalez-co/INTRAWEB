<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VehicleFuelMigrationOld
 * @package Modules\Maintenance\Models
 * @version October 15, 2021, 2:50 pm -05
 *
 * @property Modules\Maintenance\Models\MantAssetType $mantAssetType
 * @property Modules\Maintenance\Models\MantResumeMachineryVehiclesYellow $mantResumeMachineryVehiclesYellow
 * @property integer $dependencies_id
 * @property integer $mant_resume_machinery_vehicles_yellow_id
 * @property integer $mant_asset_type_id
 * @property string $asset_name
 * @property string $invoice_number
 * @property string $invoice_date
 * @property string $tanking_hour
 * @property string $driver_name
 * @property number $current_mileage
 * @property number $fuel_quantity
 * @property number $gallon_price
 * @property number $total_price
 * @property number $current_hourmeter
 * @property number $previous_hourmeter
 * @property number $variation_tanking_hour
 * @property number $previous_mileage
 * @property number $variation_route_hour
 * @property number $performance_by_gallon
 */
class VehicleFuelMigrationOld extends Model {
    use SoftDeletes;

    public $table = 'mant_vehicle_fuels_migration_old';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'dependencies_id',
        'mant_resume_machinery_vehicles_yellow_id',
        'mant_asset_type_id',
        'asset_name',
        'invoice_number',
        'invoice_date',
        'tanking_hour',
        'driver_name',
        'current_mileage',
        'fuel_quantity',
        'gallon_price',
        'total_price',
        'current_hourmeter',
        'previous_hourmeter',
        'variation_tanking_hour',
        'previous_mileage',
        'variation_route_hour',
        'performance_by_gallon',
        'created_migration'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'dependencies_id' => 'integer',
        'mant_resume_machinery_vehicles_yellow_id' => 'integer',
        'mant_asset_type_id' => 'integer',
        'asset_name' => 'string',
        'invoice_number' => 'string',
        'invoice_date' => 'string',
        'tanking_hour' => 'string',
        'driver_name' => 'string',
        'current_mileage' => 'float',
        'fuel_quantity' => 'float',
        'gallon_price' => 'float',
        'total_price' => 'float',
        'current_hourmeter' => 'float',
        'previous_hourmeter' => 'float',
        'variation_tanking_hour' => 'float',
        'previous_mileage' => 'float',
        'variation_route_hour' => 'float',
        'performance_by_gallon' => 'float'
    ];

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
}
