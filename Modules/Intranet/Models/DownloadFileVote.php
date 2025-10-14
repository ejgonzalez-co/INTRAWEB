<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DownloadFileVote
 * @package Modules\Intranet\Models
 * @version July 2, 2021, 11:35 am -05
 *
 * @property \Modules\Intranet\Models\IntranetDownload $intranetDownloads
 * @property \Modules\Intranet\Models\User $users
 * @property boolean $rating
 * @property integer $ordering
 * @property integer $intranet_downloads_id
 * @property integer $users_id
 */
class DownloadFileVote extends Model
{
        use SoftDeletes;

    public $table = 'intranet_downloads_file_votes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'rating',
        'ordering',
        'intranet_downloads_id',
        'users_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ordering' => 'integer',
        'intranet_downloads_id' => 'integer',
        'users_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function download() {
        return $this->belongsTo(\Modules\Intranet\Models\Download::class, 'intranet_downloads_id')->with(["categorie"]);
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
