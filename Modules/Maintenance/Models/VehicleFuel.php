<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class VehicleFuel
 * @package Modules\Maintenance\Models
 * @version August 13, 2021, 5:22 pm -05
 *
 * @property Modules\Maintenance\Models\MantAssetType $mantAssetType
 * @property Modules\Maintenance\Models\MantResumeMachineryVehiclesYellow $mantResumeMachineryVehiclesYellow
 * @property integer $dependencies_id
 * @property integer $mant_resume_machinery_vehicles_yellow_id
 * @property integer $mant_asset_type_id
 * @property string $asset_name
 * @property string $invoice_number
 * @property string $invoice_date
 * @property time $tanking_hour
 * @property string $driver_name
 * @property string $fuel_type
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
class VehicleFuel extends Model {
    use SoftDeletes;

    public $table = 'mant_vehicle_fuels';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'dependencies_id',
        'mant_resume_machinery_vehicles_yellow_id',
        'mant_asset_type_id',
        'asset_name',
        'created_migration',
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
        'document_name',
        'document_description',
        'attached_invoce',
        'photo_tachometer_hourmeter',
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
        'invoice_date' => 'date',
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
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'mant_resume_machinery_vehicles_yellow_id' => 'required',
        'asset_name' => 'nullable|string|max:255',
        'invoice_number' => 'nullable|string|max:100',
        'invoice_date' => 'required',
        'tanking_hour' => 'nullable|string',
        'driver_name' => 'nullable|string|max:255',
        'fuel_quantity' => 'required|numeric',
        'gallon_price' => 'required|numeric',
        'total_price' => 'nullable|numeric',
        'variation_tanking_hour' => 'nullable|numeric',
        'variation_route_hour' => 'nullable|numeric',
        'performance_by_gallon' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = [
        'date_register_name'
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
    public function assetType() {
        return $this->belongsTo(\Modules\Maintenance\Models\AssetType::class, 'mant_asset_type_id')->select(['id','name']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function resumeMachineryVehiclesYellow() {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeMachineryVehiclesYellow::class, 'mant_resume_machinery_vehicles_yellow_id')->select(['id','plaque', 'fuel_type', 'name_vehicle_machinery']);
    }

    public function getDateRegisterNameAttribute(){
        
        if($this->created_migration!=null){
            $register_date = $this->created_migration;
        }else{
            $register_date = $this->created_at;
        }
        return $register_date;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias()
    {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencies_id')->select(['id','nombre']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function FuelDocuments() {
        return $this->hasMany(\Modules\Maintenance\Models\FuelDocument::class, 'mant_vehicle_fuels_id');
    }
}
