<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NoticeHistory
 * @package Modules\Intranet\Models
 * @version August 31, 2021, 1:30 pm -05
 *
 * @property \Modules\Intranet\Models\IntranetNews $intranetNews
 * @property \Modules\Intranet\Models\IntranetNewsCategory $intranetNewsCategory
 * @property \Modules\Intranet\Models\User $users
 * @property string $title
 * @property string $content
 * @property string $users_name
 * @property boolean $state
 * @property string|\Carbon\Carbon $date_start
 * @property string|\Carbon\Carbon $date_end
 * @property integer $views
 * @property string $image_banner
 * @property string $image_presentation
 * @property string $keywords
 * @property string $featured
 * @property integer $users_id
 * @property integer $intranet_news_category_id
 * @property integer $intranet_news_id
 */
class NoticeHistory extends Model
{
        use SoftDeletes;

    public $table = 'intranet_news_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'title',
        'content',
        'users_name',
        'state',
        'date_start',
        'date_end',
        'views',
        'image_banner',
        'image_presentation',
        'keywords',
        'featured',
        'users_id',
        'intranet_news_category_id',
        'intranet_news_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'content' => 'string',
        'users_name' => 'string',
        'state' => 'boolean',
        'date_start' => 'datetime',
        'date_end' => 'datetime',
        'views' => 'integer',
        'image_banner' => 'string',
        'image_presentation' => 'string',
        'keywords' => 'string',
        'featured' => 'string',
        'users_id' => 'integer',
        'intranet_news_category_id' => 'integer',
        'intranet_news_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'nullable|string|max:255',
        'content' => 'nullable|string',
        'users_name' => 'nullable|string|max:255',
        'state' => 'nullable|boolean',
        'date_start' => 'nullable',
        'date_end' => 'nullable',
        'views' => 'nullable|integer',
        'image_banner' => 'nullable|string',
        'image_presentation' => 'nullable|string',
        'keywords' => 'nullable|string',
        'featured' => 'nullable|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'users_id' => 'required',
        'intranet_news_category_id' => 'required',
        'intranet_news_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function intranetNews() {
        return $this->belongsTo(\Modules\Intranet\Models\IntranetNews::class, 'intranet_news_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function intranetNewsCategory() {
        return $this->belongsTo(\Modules\Intranet\Models\IntranetNewsCategory::class, 'intranet_news_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
