<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class TicAssetsHistory
 * @package Modules\HelpTable\Models
 * @version June 8, 2021, 11:47 am -05
 *
 * @property \Modules\HelpTable\Models\Dependencia $dependencias
 * @property \Modules\HelpTable\Models\TicAsset $htTicAssets
 * @property \Modules\HelpTable\Models\TicPeriodValidity $htTicPeriodValidity
 * @property \Modules\HelpTable\Models\TicProvider $htTicProvider
 * @property \Modules\HelpTable\Models\TicTypeAsset $htTicTypeAssets
 * @property \Modules\HelpTable\Models\User $users
 * @property integer $ht_tic_period_validity_id
 * @property integer $ht_tic_type_assets_id
 * @property integer $ht_tic_provider_id
 * @property integer $users_id
 * @property integer $dependencias_id
 * @property integer $ht_tic_assets_id
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
 */
class TicAssetsHistory extends Model
{
        use SoftDeletes;

    public $table = 'ht_tic_assets_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'ht_tic_period_validity_id',
        'ht_tic_type_assets_id',
        'ht_tic_provider_id',
        'users_id',
        'dependencias_id',
        'ht_tic_assets_id',
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
        'provider_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ht_tic_period_validity_id' => 'integer',
        'ht_tic_type_assets_id' => 'integer',
        'ht_tic_provider_id' => 'integer',
        'users_id' => 'integer',
        'dependencias_id' => 'integer',
        'ht_tic_assets_id' => 'integer',
        'consecutive' => 'string',
        'name' => 'string',
        'brand' => 'string',
        'serial' => 'string',
        'model' => 'string',
        'inventory_plate' => 'string',
        'description' => 'string',
        'general_description' => 'string',
        'purchase_date' => 'date',
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
        'provider_name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ht_tic_period_validity_id' => 'nullable',
        'ht_tic_type_assets_id' => 'nullable',
        'ht_tic_provider_id' => 'nullable',
        'users_id' => 'nullable',
        'dependencias_id' => 'nullable',
        'ht_tic_assets_id' => 'required',
        'consecutive' => 'nullable|string|max:50',
        'name' => 'nullable|string|max:100',
        'brand' => 'nullable|string|max:80',
        'serial' => 'nullable|string|max:200',
        'model' => 'nullable|string|max:191',
        'inventory_plate' => 'nullable|string|max:191',
        'description' => 'nullable|string',
        'general_description' => 'nullable|string',
        'purchase_date' => 'nullable',
        'license_validity' => 'string|max:80',
        'invoice_attachment' => 'string|max:120',
        'location_address' => 'nullable|string|max:100',
        'state' => 'nullable|integer',
        'monitor_id' => 'nullable',
        'keyboard_id' => 'nullable',
        'mouse_id' => 'nullable',
        'operating_system' => 'nullable|integer',
        'operating_system_version' => 'nullable|integer',
        'operating_system_serial' => 'nullable|string|max:191',
        'license_microsoft_office' => 'nullable|integer',
        'serial_licencia_microsoft_office' => 'nullable|string|max:191',
        'processor' => 'nullable|string|max:50',
        'ram' => 'nullable|string|max:20',
        'hdd' => 'nullable|string|max:20',
        'name_user' => 'nullable|string|max:80',
        'provider_name' => 'nullable|string|max:80',
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
    public function dependencias() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ticAssets() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicAsset::class, 'ht_tic_assets_id');
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
}
