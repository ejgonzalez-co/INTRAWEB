<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 * @package Modules\Intranet\Models
 * @version August 31, 2021, 2:56 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $intranetNews
 * @property \Illuminate\Database\Eloquent\Collection $intranetNewsHistories
 * @property string $name
 */
class Category extends Model
{
        use SoftDeletes;

    public $table = 'intranet_news_category';
    
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
        'name' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function intranetNews() {
        return $this->hasMany(\Modules\Intranet\Models\Notice::class, 'intranet_news_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function intranetNewsHistories() {
        return $this->hasMany(\Modules\Intranet\Models\IntranetNewsHistory::class, 'intranet_news_category_id');
    }
}
