<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Download
 * @package Modules\Intranet\Models
 * @version June 28, 2021, 7:42 am -05
 *
 * @property \Modules\Intranet\Models\IntranetDownloadsCategory $intranetDownloadsCategories
 * @property \Modules\Intranet\Models\IntranetDownloadsLicense $intranetDownloadsLicenses
 * @property \Modules\Intranet\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $intranetDownloadsFileVotes
 * @property \Illuminate\Database\Eloquent\Collection $intranetDownloadsFileVotesStatistics
 * @property \Illuminate\Database\Eloquent\Collection $intranetDownloadsTagsSelecteds
 * @property \Illuminate\Database\Eloquent\Collection $intranetDownloadsUserStats
 * @property integer $owner_id
 * @property string $title
 * @property string $alias
 * @property string $filename
 * @property integer $filesize
 * @property string $filename_play
 * @property string $filename_preview
 * @property string $author
 * @property string $author_email
 * @property string $author_url
 * @property string $license
 * @property string $license_url
 * @property string $video_filename
 * @property string $image_filename
 * @property string $image_filename_spec1
 * @property string $image_filename_spec2
 * @property string $image_download
 * @property string $link_external
 * @property string $mirror1link
 * @property string $mirror1title
 * @property string $mirror1target
 * @property string $mirror2link
 * @property string $mirror2title
 * @property string $mirror2target
 * @property string $description
 * @property string $features
 * @property string $changelog
 * @property string $notes
 * @property string $version
 * @property string $directlink
 * @property string|\Carbon\Carbon $date
 * @property string|\Carbon\Carbon $publish_up
 * @property string|\Carbon\Carbon $publish_down
 * @property integer $hits
 * @property string $published
 * @property string $approved
 * @property integer $checked_out
 * @property string|\Carbon\Carbon $checked_out_time
 * @property integer $ordering
 * @property integer $unaccessible_file
 * @property string $metakey
 * @property string $metadesc
 * @property integer $intranet_downloads_categories_id
 * @property integer $intranet_downloads_licenses_id
 * @property integer $users_id
 */
class Download extends Model
{
        use SoftDeletes;

    public $table = 'intranet_downloads';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'owner_id',
        'title',
        'filename',
        'author',
        'author_email',
        'author_url',
        'image_filename',
        'image_download',
        'description',
        'features',
        'changelog',
        'notes',
        'version',
        'publish_up',
        'publish_down',
        'hits',
        'published',
        'checked_out',
        'checked_out_time',
        'ordering',
        'metakey',
        'intranet_downloads_categories_id',
        'intranet_downloads_licenses_id',
        'users_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'owner_id' => 'integer',
        'title' => 'string',
        'author' => 'string',
        'author_email' => 'string',
        'author_url' => 'string',
        'description' => 'string',
        'features' => 'string',
        'changelog' => 'string',
        'notes' => 'string',
        'version' => 'string',
        'publish_up' => 'datetime',
        'publish_down' => 'datetime',
        'hits' => 'integer',
        'published' => 'string',
        'approved' => 'string',
        'checked_out' => 'integer',
        'checked_out_time' => 'datetime',
        'ordering' => 'integer',
        'metakey' => 'string',
        'intranet_downloads_categories_id' => 'integer',
        'intranet_downloads_licenses_id' => 'integer',
        'users_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'filename' => 'required',
        'description' => 'nullable|string',
        'features' => 'nullable|string',
        'changelog' => 'nullable|string',
        'metakey' => 'nullable|string',
        'intranet_downloads_categories_id' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function downloadLicense() {
        return $this->belongsTo(\Modules\Intranet\Models\License::class, 'intranet_downloads_licenses_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function owner() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function intranetDownloadsFileVotes() {
        return $this->hasMany(\Modules\Intranet\Models\IntranetDownloadsFileVote::class, 'intranet_downloads_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function intranetDownloadsFileVotesStatistics() {
        return $this->hasMany(\Modules\Intranet\Models\IntranetDownloadsFileVotesStatistic::class, 'intranet_downloads_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function downloadTagsSelected() {
        return $this->hasMany(\Modules\Intranet\Models\DownloadTagsSelected::class, 'intranet_downloads_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function intranetDownloadsUserStats() {
        return $this->hasMany(\Modules\Intranet\Models\IntranetDownloadsUserStat::class, 'intranet_downloads_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function categorie()
    {
        return $this->belongsTo(\Modules\Intranet\Models\DownloadCategorie::class, 'intranet_downloads_categories_id')->with(["downloadAccessUsers", "downloadAccessUsers"]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function fileVote() {
        return $this->belongsTo(\Modules\Intranet\Models\DownloadFileVote::class, 'id', 'intranet_downloads_id');
    }

}
