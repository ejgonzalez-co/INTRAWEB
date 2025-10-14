<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use DateTimeInterface;


/**
 * Class ResumeEquipmentLeca
 * @package Modules\Maintenance\Models
 * @version May 3, 2021, 5:48 pm -05
 *
 * @property Modules\Maintenance\Models\MantCategory $mantCategory
 * @property \Illuminate\Database\Eloquent\Collection $mantTechnicalSpecificationsEquipmentLecas
 * @property string $name_equipment
 * @property string $internal_code_leca
 * @property string $inventory_no
 * @property string $mark
 * @property string $serie
 * @property string $model
 * @property string $location
 * @property string $software
 * @property string $purchase_date
 * @property string $commissioning_date
 * @property string $date_withdrawal_service
 * @property string $maker
 * @property string $provider
 * @property string $catalogue
 * @property string $idiom
 * @property string $instructive
 * @property string $instructional_location
 * @property string $magnitude_control
 * @property string $consumables
 * @property string $resolution
 * @property string $accessories
 * @property string $operation_range
 * @property string $voltage
 * @property string $use
 * @property string $use_range
 * @property string $allowable_error
 * @property string $minimum_permissible_error
 * @property string $environmental_operating_conditions
 * @property integer $dependencias_id
 * @property integer $mant_category_id
 * @property integer $responsable
 */
class ResumeEquipmentLeca extends Model
{
    use SoftDeletes;

    public $table = 'mant_resume_equipment_leca';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name_equipment',
        'internal_code_leca',
        'inventory_no',
        'mark',
        'serie',
        'model',
        'location',
        'software',
        'purchase_date',
        'commissioning_date',
        'date_withdrawal_service',
        'maker',
        'provider',
        'catalogue',
        'catalogue_location',
        'idiom',
        'instructive',
        'instructional_location',
        'magnitude_control',
        'consumables',
        'resolution',
        'accessories',
        'operation_range',
        'voltage',
        'use',
        'use_range',
        'allowable_error',
        'minimum_permissible_error',
        'environmental_operating_conditions',
        'dependencias_id',
        'mant_category_id',
        'responsable',
        'mant_providers_id',
        'mant_asset_type_id',
        'observation',
        'status',
        'purchase_price'
    ];

    protected $appends = [
        'name_general',
        'name_vehicle_machinery',
        'no_inventory',
        'mantenances_actives'
    ];

    public function getMantenancesActivesAttribute()
    {
        $currentYear = Carbon::now()->year;

        if (empty($this->inventory_no)) {
            return [];
        }else{
            return AssetManagement::where('nombre_activo', 'like', '%' . $this->inventory_no . '%')
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

    public function getNoInventoryAttribute(){
        return $this->inventory_no;
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
        'internal_code_leca' => 'string',
        'inventory_no' => 'string',
        'mark' => 'string',
        'serie' => 'string',
        'model' => 'string',
        'location' => 'string',
        'software' => 'string',
        'maker' => 'string',
        'provider' => 'string',
        'catalogue' => 'string',
        'catalogue_location' => 'string',
        'idiom' => 'string',
        'instructive' => 'string',
        'instructional_location' => 'string',
        'magnitude_control' => 'string',
        'consumables' => 'string',
        'resolution' => 'string',
        'accessories' => 'string',
        'operation_range' => 'string',
        'voltage' => 'string',
        'use' => 'string',
        'use_range' => 'string',
        'allowable_error' => 'string',
        'minimum_permissible_error' => 'string',
        'environmental_operating_conditions' => 'string',
        'dependencias_id' => 'integer',
        'mant_category_id' => 'integer',
        'responsable' => 'integer',
        'mant_asset_type_id' => 'integer',
        'observation' => 'string',
        'status' => 'string',
        'purchase_price' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
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
        return $this->hasMany(\Modules\Maintenance\Models\DocumentsAssets::class, 'mant_resume_equipment_leca_id');
        // return $this->hasMany(\Modules\Maintenance\Models\DocumentsAssets::class, 'mant_resume_machinery_vehicles_yellow_id')->where('form_type', '=', 4);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function specificationsEquipmentLeca()
    {
        return $this->hasMany(\Modules\Maintenance\Models\SpecificationsEquipmentLeca::class, 'mant_resume_equipment_leca_id');
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
