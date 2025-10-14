<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class TicTypeTicCategory
 * @package Modules\HelpTable\Models
 * @version June 4, 2021, 2:53 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $htTicRequestHistories
 * @property \Illuminate\Database\Eloquent\Collection $htTicRequests
 * @property \Illuminate\Database\Eloquent\Collection $htTicTypeAssets
 * @property string $name
 * @property string $description
 */
class TicTypeTicCategory extends Model
{
        use SoftDeletes;

    public $table = 'ht_tic_type_tic_categories';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'description',
        'estado'
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
        'estado' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:80',
        'description' => 'string'
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticRequestHistories() {
        return $this->hasMany(\Modules\HelpTable\Models\TicRequestHistory::class, 'ht_tic_type_tic_categories_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticRequests() {
        return $this->hasMany(\Modules\HelpTable\Models\TicRequest::class, 'ht_tic_type_tic_categories_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticTypeAssets() {
        return $this->hasMany(\Modules\HelpTable\Models\TicTypeAsset::class, 'ht_tic_type_tic_categories_id');
    }

    public function ticTypeCategoryHistory() {
        return $this->hasMany(\Modules\HelpTable\Models\TicTypeTicCategoryHistory::class, 'id_categories');
    }

}
