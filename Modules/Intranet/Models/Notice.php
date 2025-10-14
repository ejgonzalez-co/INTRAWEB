<?php

namespace Modules\Intranet\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Notice
 * @package Modules\Intranet\Models
 * @version August 31, 2021, 1:30 pm -05
 *
 * @property \Modules\Intranet\Models\IntranetNewsCategory $intranetNewsCategory
 * @property \Modules\Intranet\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $intranetNewsHistories
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
 */
class Notice extends Model
{
        use SoftDeletes;

    public $table = 'intranet_news';

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
        'annexes',
        'keywords',
        'featured',
        'users_id',
        'intranet_news_category_id'
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
        'state' => 'integer',

        'views' => 'integer',
        'image_banner' => 'string',
        'image_presentation' => 'string',
        'keywords' => 'string',
        'featured' => 'string',
        'users_id' => 'integer',
        'intranet_news_category_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'nullable|string|max:255',
        'content' => 'nullable|string',
        'state' => 'nullable|boolean',
        'date_start' => 'nullable',
        'date_end' => 'nullable',

    ];

    protected $appends = [
        'state_name',
        'state_color'

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
    public function category() {
        return $this->belongsTo(\Modules\Intranet\Models\Category::class, 'intranet_news_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function noticeMonitoringControl()
    {
        return $this->hasMany(\Modules\Intranet\Models\NoticeMonitoringControl::class, 'intranet_news_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function intranetNewsHistories() {
        return $this->hasMany(\Modules\Intranet\Models\IntranetNewsHistory::class, 'intranet_news_id');
    }
       /**
     * obtiene y valida si una encuesta esta entre los rangos de fecha
     **/
    public function getStateNameAttribute()
    {
        if($this->state==1){
            return "Publicado";

        }else{
            return "Despublicado";
        }
    }
    public function getStateColorAttribute()
    {
        if($this->state==1){
            return "text-white text-center p-2 bg-green";

        }else{
            return "text-white text-center p-2 bg-warning";
        }
    }
}
