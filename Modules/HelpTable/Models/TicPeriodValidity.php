<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TicPeriodValidity
 * @package Modules\HelpTable\Models
 * @version June 8, 2021, 11:52 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $htTicAssets
 * @property \Illuminate\Database\Eloquent\Collection $htTicAssetsHistories
 * @property string $name
 */
class TicPeriodValidity extends Model
{
        use SoftDeletes;

    public $table = 'ht_tic_period_validity';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:5',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticAssets() {
        return $this->hasMany(\Modules\HelpTable\Models\TicAsset::class, 'ht_tic_period_validity_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticAssetsHistories() {
        return $this->hasMany(\Modules\HelpTable\Models\TicAssetsHistory::class, 'ht_tic_period_validity_id');
    }
}
