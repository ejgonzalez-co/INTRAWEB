<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class TicRequestStatus
 * @package Modules\HelpTable\Models
 * @version June 5, 2021, 10:24 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $htTicRequestHistories
 * @property \Illuminate\Database\Eloquent\Collection $htTicRequests
 * @property string $name
 * @property string $slug
 * @property string $status_color
 */
class TicRequestStatus extends Model
{
        use SoftDeletes;

    public $table = 'ht_tic_request_status';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'slug',
        'status_color'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'slug' => 'string',
        'status_color' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:80',
        'slug' => 'nullable|string|max:80',
        'status_color' => 'nullable|string|max:80',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticRequestHistories() {
        return $this->hasMany(\Modules\HelpTable\Models\TicRequestHistory::class, 'ht_tic_request_status_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticRequests() {
        return $this->hasMany(\Modules\HelpTable\Models\TicRequest::class, 'ht_tic_request_status_id');
    }
}
