<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DownloadAccessGroup
 * @package Modules\Intranet\Models
 * @version June 21, 2021, 7:28 pm -05
 *
 * @property \Modules\Intranet\Models\IntranetDownloadsCategory $intranetDownloadsCategories
 * @property integer $group_id
 * @property integer $intranet_downloads_categories_id
 */
class DownloadAccessGroup extends Model
{
        use SoftDeletes;

    public $table = 'intranet_downloads_access_group';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'group_id',
        'intranet_downloads_categories_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'group_id' => 'integer',
        'intranet_downloads_categories_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'group_id' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'intranet_downloads_categories_id' => 'required|integer'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workGroup()
    {
        return $this->belongsTo(\Modules\Intranet\Models\WorkGroup::class, 'group_id');
    }

}
