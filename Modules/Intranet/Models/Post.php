<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Post
 * @package Modules\Intranet\Models
 * @version July 20, 2021, 1:24 am -05
 *
 * @property \Modules\Intranet\Models\IntranetCategory $category
 * @property \Modules\Intranet\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $intranetComments
 * @property \Illuminate\Database\Eloquent\Collection $intranetTags
 * @property integer $category_id
 * @property string $title
 * @property string $slug
 * @property string $cover_path
 * @property string $content
 * @property boolean $online
 * @property integer $user_id
 * @property integer $visits
 */
class Post extends Model
{
        use SoftDeletes;

    public $table = 'intranet_posts';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'category_id',
        'title',
        'slug',
        'cover_path',
        'content',
        'online',
        'user_id',
        'visits'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'cover_path' => 'string',
        'content' => 'string',
        'online' => 'boolean',
        'user_id' => 'integer',
        'visits' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'category_id' => 'required',
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255',
        'cover_path' => 'required|string|max:255',
        'content' => 'required|string',
        'online' => 'required|boolean',
        'user_id' => 'required',
        'visits' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category() {
        return $this->belongsTo(\Modules\Intranet\Models\IntranetCategory::class, 'category_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'user_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function intranetComments() {
        return $this->hasMany(\Modules\Intranet\Models\IntranetComment::class, 'post_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function intranetTags() {
        return $this->belongsToMany(\Modules\Intranet\Models\IntranetTag::class, 'intranet_post_tag');
    }
}
