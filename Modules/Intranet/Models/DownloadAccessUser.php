<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DownloadAccessUser
 * @package Modules\Intranet\Models
 * @version June 24, 2021, 8:18 pm -05
 *
 * @property \Modules\Intranet\Models\IntranetDownloadsCategory $intranetDownloadsCategories
 * @property integer $user_id
 * @property integer $intranet_downloads_categories_id
 */
class DownloadAccessUser extends Model
{
        use SoftDeletes;

    public $table = 'intranet_downloads_access_user';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'user_id',
        'intranet_downloads_categories_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'intranet_downloads_categories_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'intranet_downloads_categories_id' => 'required|integer'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function intranetDownloadsCategories() {
        return $this->belongsTo(\Modules\Intranet\Models\IntranetDownloadsCategory::class, 'intranet_downloads_categories_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'user_id');
    }
}
