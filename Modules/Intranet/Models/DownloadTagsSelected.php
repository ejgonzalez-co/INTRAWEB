<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DownloadTagsSelected
 * @package Modules\Intranet\Models
 * @version June 28, 2021, 9:49 pm -05
 *
 * @property \Modules\Intranet\Models\IntranetDownload $intranetDownloads
 * @property \Modules\Intranet\Models\IntranetDownloadsTag $intranetDownloadsTags
 * @property integer $intranet_downloads_tags_id
 * @property integer $intranet_downloads_id
 */
class DownloadTagsSelected extends Model
{
        use SoftDeletes;

    public $table = 'intranet_downloads_tags_selected';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'intranet_downloads_tags_id',
        'intranet_downloads_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'intranet_downloads_tags_id' => 'integer',
        'intranet_downloads_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'intranet_downloads_tags_id' => 'required|integer',
        'intranet_downloads_id' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function intranetDownloads() {
        return $this->belongsTo(\Modules\Intranet\Models\IntranetDownload::class, 'intranet_downloads_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function intranetDownloadsTags() {
        return $this->belongsTo(\Modules\Intranet\Models\IntranetDownloadsTag::class, 'intranet_downloads_tags_id');
    }
}
