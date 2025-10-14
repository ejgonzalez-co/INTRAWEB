<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tag
 * @package Modules\Intranet\Models
 * @version July 20, 2021, 1:23 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $intranetPosts
 * @property string $name
 * @property string $slug
 */
class Tag extends Model
{
        use SoftDeletes;

    public $table = 'intranet_tags';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'slug'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'slug' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function intranetPosts() {
        return $this->belongsToMany(\Modules\Intranet\Models\IntranetPost::class, 'intranet_post_tag');
    }
}
