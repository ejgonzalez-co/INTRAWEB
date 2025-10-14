<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DownloadsTags
 * @package Modules\Intranet\Models
 * @version June 27, 2021, 9:55 am -05
 *
 * @property \Modules\Intranet\Models\IntranetDownloadsCategory $intranetDownloadsCategories
 * @property \Illuminate\Database\Eloquent\Collection $intranetDownloadsTagsSelecteds
 * @property string $title
 * @property string $alias
 * @property string $link_ext
 * @property string $description
 * @property string $published
 * @property integer $checked_out
 * @property string|\Carbon\Carbon $checked_out_time
 * @property integer $ordering
 * @property integer $intranet_downloads_categories_id
 */
class DownloadsTags extends Model
{
        use SoftDeletes;

    public $table = 'intranet_downloads_tags';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'title',
        'alias',
        'link_ext',
        'description',
        'published',
        'checked_out',
        'checked_out_time',
        'ordering',
        'intranet_downloads_categories_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'alias' => 'string',
        'link_ext' => 'string',
        'description' => 'string',
        'published' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'published' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function downloadCategories() {
        return $this->belongsTo(\Modules\Intranet\Models\DownloadCategorie::class, 'intranet_downloads_categories_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function intranetDownloadsTagsSelecteds() {
        return $this->hasMany(\Modules\Intranet\Models\IntranetDownloadsTagsSelected::class, 'intranet_downloads_tags_id');
    }
}
