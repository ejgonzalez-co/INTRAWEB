<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;
use DateTimeInterface;

/**
 * Class TicAsset
 * @package Modules\HelpTable\Models
 * @version June 4, 2021, 10:38 am -05
 *
 * @property \Modules\HelpTable\Models\Dependencia $dependencias
 * @property \Modules\HelpTable\Models\HtTicPeriodValidity $htTicPeriodValidity
 * @property \Modules\HelpTable\Models\HtTicProvider $htTicProvider
 * @property \Modules\HelpTable\Models\HtTicTypeAsset $htTicTypeAssets
 * @property \Modules\HelpTable\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $htTicAssetsComponents
 * @property \Illuminate\Database\Eloquent\Collection $htTicAssetsHistories
 * @property \Illuminate\Database\Eloquent\Collection $htTicMaintenances
 * @property string $consecutive
 * @property string $name
 * @property string $brand
 * @property string $serial
 * @property string $model
 * @property string $inventory_plate
 * @property string $description
 * @property string $general_description
 * @property string $purchase_date
 * @property string $license_validity
 * @property string $invoice_attachment
 * @property string $location_address
 * @property integer $state
 * @property integer $monitor_id
 * @property integer $keyboard_id
 * @property integer $mouse_id
 * @property integer $operating_system
 * @property integer $operating_system_version
 * @property string $operating_system_serial
 * @property integer $license_microsoft_office
 * @property string $serial_licencia_microsoft_office
 * @property string $processor
 * @property string $ram
 * @property string $hdd
 * @property string $name_user
 * @property string $provider_name
 * @property integer $ht_tic_period_validity_id
 * @property integer $ht_tic_type_assets_id
 * @property integer $ht_tic_provider_id
 * @property integer $users_id
 * @property integer $dependencias_id
 */
class TicAsset extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_assets';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'consecutive',
        'name',
        'brand',
        'serial',
        'model',
        'inventory_plate',
        'description',
        'general_description',
        'purchase_date',
        'license_validity',
        'invoice_attachment',
        'location_address',
        'state',
        'monitor_id',
        'keyboard_id',
        'mouse_id',
        'operating_system',
        'operating_system_version',
        'operating_system_serial',
        'license_microsoft_office',
        'serial_licencia_microsoft_office',
        'processor',
        'ram',
        'hdd',
        'name_user',
        'provider_name',
        'ht_tic_period_validity_id',
        'ht_tic_type_assets_id',
        'ht_tic_provider_id',
        'users_id',
        'dependencias_id'
    ];

    protected $appends = [
        'state_name',
        'operating_systems_info',
        'office_automation_versions_info'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'consecutive' => 'string',
        'name' => 'string',
        'brand' => 'string',
        'serial' => 'string',
        'model' => 'string',
        'inventory_plate' => 'string',
        'description' => 'string',
        'general_description' => 'string',
        'purchase_date' => 'datetime:Y-m-d',
        'license_validity' => 'string',
        'invoice_attachment' => 'string',
        'location_address' => 'string',
        'state' => 'integer',
        'monitor_id' => 'integer',
        'keyboard_id' => 'integer',
        'mouse_id' => 'integer',
        'operating_system' => 'integer',
        'operating_system_version' => 'integer',
        'operating_system_serial' => 'string',
        'license_microsoft_office' => 'integer',
        'serial_licencia_microsoft_office' => 'string',
        'processor' => 'string',
        'ram' => 'string',
        'hdd' => 'string',
        'name_user' => 'string',
        'provider_name' => 'string',
        'ht_tic_period_validity_id' => 'integer',
        'ht_tic_type_assets_id' => 'integer',
        'ht_tic_provider_id' => 'integer',
        'users_id' => 'integer',
        'dependencias_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'consecutive' => 'string|max:50',
        'name' => 'string|max:100',
        'brand' => 'string|max:80',
        'serial' => 'string|max:200',
        'model' => 'string|max:191',
        'inventory_plate' => 'string|max:191',
        'description' => 'string',
        'general_description' => 'string',
        'license_validity' => 'string|max:80',
        'invoice_attachment' => 'string|max:120',
        'location_address' => 'string|max:100',
        'state' => 'integer',
        'operating_system' => 'integer',
        'operating_system_version' => 'integer',
        'operating_system_serial' => 'string|max:191',
        'license_microsoft_office' => 'integer',
        'serial_licencia_microsoft_office' => 'string|max:191',
        'processor' => 'string|max:50',
        'ram' => 'string|max:20',
        'hdd' => 'string|max:20',
        'name_user' => 'string|max:80',
        'provider_name' => 'string|max:80',
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
    public function dependencias() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ticPeriodValidity() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicPeriodValidity::class, 'ht_tic_period_validity_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ticProvider() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicProvider::class, 'ht_tic_provider_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ticTypeAssets() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicTypeAsset::class, 'ht_tic_type_assets_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticAssetsComponents() {
        return $this->hasMany(\Modules\HelpTable\Models\TicAssetsComponent::class, 'ht_tic_assets_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticAssetsHistories() {
        return $this->hasMany(\Modules\HelpTable\Models\TicAssetsHistory::class, 'ht_tic_assets_id')->with(["ticTypeAssets","dependencias"]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticMaintenances() {
        return $this->hasMany(\Modules\HelpTable\Models\TicMaintenance::class, 'ht_tic_assets_id');
    }

    /**
     * Obtiene el nombre del estado del activo tic
     * @return 
     */
    public function getStateNameAttribute() {
        return AppBaseController::getObjectOfList(config('help_table.state_assets_tic'), 'id', $this->state)->name;
    }

    /**
     * Obtiene el nombre del estado del activo tic
     * @return 
     */
    public function getOperatingSystemsInfoAttribute() {
        return AppBaseController::getObjectOfList(config('help_table.operating_systems'), 'id', $this->operating_system);
    }

    /**
     * Obtiene el nombre del estado del activo tic
     * @return 
     */
    public function getOfficeAutomationVersionsInfoAttribute() {
        return AppBaseController::getObjectOfList(config('help_table.office_automation_versions'), 'id', $this->license_microsoft_office);
    }
}
