<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TicAssetsComponent
 * @package Modules\HelpTable\Models
 * @version June 8, 2021, 2:59 pm -05
 *
 * @property \Modules\HelpTable\Models\HtTicAsset $htTicAssets
 * @property integer $ht_tic_assets_id
 * @property string $name
 * @property string|\Carbon\Carbon $date_purchase
 * @property string $provider_name
 * @property string $serial
 * @property string $description
 * @property boolean $state
 */
class TicAssetsComponent extends Model
{
        use SoftDeletes;

    public $table = 'ht_tic_assets_components';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'ht_tic_assets_id',
        'name',
        'date_purchase',
        'provider_name',
        'serial',
        'description',
        'state'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ht_tic_assets_id' => 'integer',
        'name' => 'string',
        'date_purchase' => 'datetime',
        'provider_name' => 'string',
        'serial' => 'string',
        'description' => 'string',
        'state' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ht_tic_assets_id' => 'nullable',
        'name' => 'nullable|string|max:120',
        'date_purchase' => 'nullable',
        'provider_name' => 'nullable|string|max:120',
        'serial' => 'nullable|string|max:120',
        'description' => 'nullable|string',
        'state' => 'nullable|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ticAssets() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicAsset::class, 'ht_tic_assets_id');
    }
}
