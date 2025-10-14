<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class TicRequestHistory
 * @package Modules\HelpTable\Models
 * @version June 5, 2021, 11:38 am -05
 *
 * @property \Modules\HelpTable\Models\TicRequest $htTicRequests
 * @property \Modules\HelpTable\Models\TicRequestStatus $htTicRequestStatus
 * @property \Modules\HelpTable\Models\TicTypeRequest $htTicTypeRequest
 * @property \Modules\HelpTable\Models\TicTypeTicCategory $htTicTypeTicCategories
 * @property \Modules\HelpTable\Models\User $assignedBy
 * @property \Modules\HelpTable\Models\User $users
 * @property \Modules\HelpTable\Models\User $assignedUser
 * @property integer $ht_tic_requests_id
 * @property integer $ht_tic_type_request_id
 * @property integer $ht_tic_request_status_id
 * @property integer $assigned_by_id
 * @property string $assigned_by_name
 * @property integer $users_id
 * @property string $users_name
 * @property string $assigned_user_name
 * @property integer $assigned_user_id
 * @property integer $ht_tic_type_tic_categories_id
 * @property integer $priority_request
 * @property string $affair
 * @property string $floor
 * @property string|\Carbon\Carbon $assignment_date
 * @property string|\Carbon\Carbon $prox_date_to_expire
 * @property string|\Carbon\Carbon $expiration_date
 * @property string|\Carbon\Carbon $date_attention
 * @property string|\Carbon\Carbon $closing_date
 * @property string|\Carbon\Carbon $reshipment_date
 * @property string $next_hour_to_expire
 * @property string $hours
 * @property string $description
 * @property string $tracing
 * @property string $request_status
 * @property integer $survey_status
 * @property string $time_line
 * @property integer $support_type
 * @property string $username_requesting_requirement
 * @property string $location
 */
class TicRequestHistory extends Model
{
        use SoftDeletes;

    public $table = 'ht_tic_request_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'ht_tic_requests_id',
        'ht_tic_type_request_id',
        'ht_tic_request_status_id',
        'assigned_by_id',
        'assigned_by_name',
        'users_id',
        'users_name',
        'assigned_user_name',
        'assigned_user_id',
        'ht_tic_type_tic_categories_id',
        'priority_request',
        'affair',
        'floor',
        'assignment_date',
        'prox_date_to_expire',
        'expiration_date',
        'date_attention',
        'closing_date',
        'reshipment_date',
        'next_hour_to_expire',
        'hours',
        'description',
        'tracing',
        'request_status',
        'survey_status',
        'time_line',
        'support_type',
        'username_requesting_requirement',
        'location',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ht_tic_requests_id' => 'integer',
        'ht_tic_type_request_id' => 'integer',
        'ht_tic_request_status_id' => 'integer',
        'assigned_by_id' => 'integer',
        'assigned_by_name' => 'string',
        'users_id' => 'integer',
        'users_name' => 'string',
        'assigned_user_name' => 'string',
        'assigned_user_id' => 'integer',
        'ht_tic_type_tic_categories_id' => 'integer',
        'priority_request' => 'integer',
        'affair' => 'string',
        'floor' => 'string',
        'assignment_date' => 'datetime',
        'prox_date_to_expire' => 'datetime',
        'expiration_date' => 'datetime',
        'date_attention' => 'datetime',
        'closing_date' => 'datetime',
        'reshipment_date' => 'datetime',
        'next_hour_to_expire' => 'string',
        'hours' => 'string',
        'description' => 'string',
        'tracing' => 'string',
        'request_status' => 'string',
        'survey_status' => 'integer',
        'time_line' => 'string',
        'support_type' => 'integer',
        'username_requesting_requirement' => 'string',
        'location' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ht_tic_type_request_id' => 'nullable',
        'ht_tic_request_status_id' => 'nullable',
        'assigned_by_id' => 'nullable',
        'assigned_by_name' => 'nullable|string|max:150',
        'users_id' => 'nullable',
        'users_name' => 'nullable|string|max:150',
        'assigned_user_name' => 'nullable|string|max:150',
        'assigned_user_id' => 'nullable',
        'ht_tic_type_tic_categories_id' => 'nullable',
        'priority_request' => 'nullable|boolean',
        'affair' => 'string',
        'floor' => 'nullable|string|max:40',
        'assignment_date' => 'nullable',
        'prox_date_to_expire' => 'nullable',
        'expiration_date' => 'nullable',
        'date_attention' => 'nullable',
        'closing_date' => 'nullable',
        'reshipment_date' => 'nullable',
        'next_hour_to_expire' => 'nullable|string',
        'hours' => 'nullable|string',
        'description' => 'nullable|string',
        'tracing' => 'nullable|string',
        'request_status' => 'string|max:80',
        'survey_status' => 'nullable|boolean',
        'time_line' => 'nullable|string|max:191',
        'support_type' => 'nullable|boolean',
        'username_requesting_requirement' => 'string|max:50',
        'location' => 'string|max:70',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ticRequests() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicRequest::class, 'ht_tic_requests_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ticRequestStatus() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicRequestStatus::class, 'ht_tic_request_status_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ticTypeRequest() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicTypeRequest::class, 'ht_tic_type_request_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ticTypeTicCategories() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicTypeTicCategory::class, 'ht_tic_type_tic_categories_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function assignedBy() {
        return $this->belongsTo(\App\User::class, 'assigned_by_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function assignedUser() {
        return $this->belongsTo(\App\User::class, 'assigned_user_id');
    }
}
