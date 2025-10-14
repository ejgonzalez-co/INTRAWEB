<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class TicTypeAsset
 * @package Modules\HelpTable\Models
 * @version June 4, 2021, 2:17 pm -05
 *
 * @property \Modules\HelpTable\Models\TicTypeTicCategory $htTicTypeTicCategories
 * @property \Illuminate\Database\Eloquent\Collection $htTicAssets
 * @property \Illuminate\Database\Eloquent\Collection $htTicAssetsHistories
 * @property string $name
 * @property string $description
 * @property integer $ht_tic_type_tic_categories_id
 */
class TicTypeAsset extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_type_assets';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'description',
        'ht_tic_type_tic_categories_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'ht_tic_type_tic_categories_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'name' => 'required|string|max:191',
        'description' => 'nullable|string',
        'ht_tic_type_tic_categories_id' => 'required'
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
    public function ticTypeTicCategories() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicTypeTicCategory::class, 'ht_tic_type_tic_categories_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticAssets() {
        return $this->hasMany(\Modules\HelpTable\Models\TicAsset::class, 'ht_tic_type_assets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticAssetsHistories() {
        return $this->hasMany(\Modules\HelpTable\Models\TicAssetsHistory::class, 'ht_tic_type_assets_id');
    }
}
