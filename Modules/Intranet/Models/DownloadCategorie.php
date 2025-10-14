<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DownloadCategorie
 * @package Modules\Intranet\Models
 * @version June 21, 2021, 7:28 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $DownloadsAccessGroups
 * @property \Illuminate\Database\Eloquent\Collection $DownloadsAccessUsers
 * @property \Illuminate\Database\Eloquent\Collection $DownloadsDeleteGroups
 * @property \Illuminate\Database\Eloquent\Collection $DownloadsDeleteUsers
 * @property \Illuminate\Database\Eloquent\Collection $DownloadsUploadGroups
 * @property \Illuminate\Database\Eloquent\Collection $DownloadsUploadUsers
 * @property integer $parent_id
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property boolean $published
 * @property integer $checked_out
 * @property string|\Carbon\Carbon $checked_out_time
 * @property integer $ordering
 * @property string $uploaduserid
 * @property string $accessuserid
 * @property string $deleteuserid
 * @property string $uploadgroupid
 * @property string $accessgroupid
 * @property string $deletegroupid
 * @property string|\Carbon\Carbon $date
 * @property string $metakey
 * @property string $metadesc
 */
class DownloadCategorie extends Model
{
        use SoftDeletes;

    public $table = 'intranet_downloads_categories';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'parent_id',
        'title',
        'alias',
        'description',
        'published',
        'checked_out',
        'checked_out_time',
        'ordering',
        'date',
        'metakey',
        'metadesc',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'parent_id' => 'integer',
        'title' => 'string',
        'alias' => 'string',
        'description' => 'string',
        'checked_out' => 'integer',
        'checked_out_time' => 'datetime',
        'ordering' => 'integer',
        'metakey' => 'string',
        'metadesc' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'metakey' => 'nullable|string',
        'metadesc' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function parentid()
    {
        return $this->belongsTo(\Modules\Intranet\Models\DownloadCategorie::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function downloadAccessGroups() {
        return $this->hasMany(\Modules\Intranet\Models\DownloadAccessGroup::class, 'intranet_downloads_categories_id')->with(["workGroup"]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function downloadAccessUsers() {
        return $this->hasMany(\Modules\Intranet\Models\DownloadAccessUser::class, 'intranet_downloads_categories_id')->with(["user"]);
    }
}
