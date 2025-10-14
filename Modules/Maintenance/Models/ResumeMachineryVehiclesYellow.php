<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Maintenance\Models\AssetManagement;
/**
 * Class ResumeMachineryVehiclesYellow
 * @package Modules\Maintenance\Models
 * @version March 15, 2021, 4:43 pm -05
 *
 * @property Modules\Maintenance\Models\Dependencia $dependencias
 * @property Modules\Maintenance\Models\MantCategory $mantCategory
 * @property string $name_vehicle_machinery
 * @property string $no_inventory
 * @property integer $purchase_price
 * @property string|\Carbon\Carbon $sheet_elaboration_date
 * @property integer $mileage_start_activities
 * @property string $mark
 * @property string $model
 * @property string $no_motor
 * @property string $invoice_number
 * @property string|\Carbon\Carbon $date_put_into_service
 * @property string|\Carbon\Carbon $warranty_date
 * * @property string|\Carbon\Carbon $warranty_description
 * @property string|\Carbon\Carbon $service_retirement_date
 * @property string $plaque
 * @property string $color
 * @property string $chassis_number
 * @property string $service_class
 * @property string $body_type
 * @property string $transit_license_number
 * @property integer $number_passengers
 * @property string $fuel_type
 * @property integer $number_tires
 * @property string $front_tire_reference
 * @property string $rear_tire_reference
 * @property integer $number_batteries
 * @property string $battery_reference
 * @property integer $gallon_tank_capacity
 * @property integer $tank_capacity_tons
 * @property integer $tons_capacity_load
 * @property string $cylinder_capacity
 * @property string|\Carbon\Carbon $expiration_date_soat
 * @property string|\Carbon\Carbon $expiration_date_tecnomecanica
 * @property integer $dependencias_id
 * @property integer $mant_category_id
 */
class ResumeMachineryVehiclesYellow extends Model
{
    use SoftDeletes;

    public $table = 'mant_resume_machinery_vehicles_yellow';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name_vehicle_machinery',
        'no_inventory',
        'purchase_price',
        'sheet_elaboration_date',
        'mileage_start_activities',
        'mark',
        'model',
        'no_motor',
        'invoice_number',
        'date_put_into_service',
        'warranty_date',
        'warranty_description',
        'service_retirement_date',
        'line',

        'warehouse_entry_number',
        'warehouse_exit_number',
        'delivery_date_vehicle_by_provider',

        'plaque',
        'color',
        'chassis_number',
        'service_class',
        'body_type',
        'transit_license_number',
        'number_passengers',
        'fuel_type',
        'number_tires',
        'front_tire_reference',
        'rear_tire_reference',

        'mant_tire_all_front',
        'mant_tire_all_rear',

        'number_batteries',
        'battery_reference',
        'gallon_tank_capacity',
        'tons_capacity_load',
        'cylinder_capacity',
        'expiration_date_soat',
        'expiration_date_tecnomecanica',

        'person_prepares_resume',
        'person_reviewed_approved',

        'observation',
        'status',
        'dependencias_id',
        'mant_category_id',
        'responsable',
        'mant_providers_id',
        'mant_asset_type_id'
    ];
    protected $appends = [
        'name_general','rubros_asignados','mantenances_actives'
    ];

    public function getNameGeneralAttribute(){
        return $this->name_vehicle_machinery;
    }

    public function getMantenancesActivesAttribute()
    {
        $currentYear = Carbon::now()->year;

        if (empty($this->plaque)) {
            return [];
        }else{
            return AssetManagement::where('nombre_activo', 'like', '%' . $this->plaque . '%')
                ->whereYear('created_at', $currentYear)
                ->get()
                ->map(function ($item) {
                    $item->id_encripted = base64_encode($item->id);
                    return $item;
                });

        }
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name_vehicle_machinery' => 'string',
        'no_inventory' => 'string',
        'purchase_price' => 'float',
        'mark' => 'string',
        'model' => 'string',
        'no_motor' => 'string',
        'invoice_number' => 'string',
        'plaque' => 'string',
        'color' => 'string',
        'line'=> 'string',
        'chassis_number' => 'string',
        'service_class' => 'string',
        'body_type' => 'string',
        'transit_license_number' => 'string',
        'number_passengers' => 'integer',
        'fuel_type' => 'string',
        'number_tires' => 'integer',
        'front_tire_reference' => 'string',
        'rear_tire_reference' => 'string',
        'number_batteries' => 'integer',
        'battery_reference' => 'string',
        'tons_capacity_load' => 'string',
        'cylinder_capacity' => 'string',
        'observation' => 'string',
        'status' => 'string',
        'dependencias_id' => 'integer',
        'mant_category_id' => 'integer',
        'responsable' => 'integer',
        'mant_providers_id' => 'integer',
        'mant_asset_type_id' => 'integer'
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
        return $this->hasMany(\Modules\Maintenance\Models\DocumentsAssets::class, 'mant_resume_machinery_vehicles_yellow_id');
        // return $this->hasMany(\Modules\Maintenance\Models\DocumentsAssets::class, 'mant_resume_machinery_vehicles_yellow_id')->where('form_type', '=', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function assetType()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\AssetType::class, 'mant_asset_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function TireInformations() {
        return $this->hasMany(\Modules\Maintenance\Models\TireInformations::class, 'mant_resume_machinery_vehicles_yellow_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany rubros_asignados
     **/
    public function getRubrosAsignadosAttribute()
    {
        return \Modules\Maintenance\Models\SnActivesHeading::where("activo_id",$this->id)->get()->toArray();
    }
}
