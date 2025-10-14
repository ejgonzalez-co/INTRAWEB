<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class InformCustomerAtteched
 * @package Modules\leca\Models
 * @version March 3, 2023, 9:03 am -05
 *
 * @property Modules\leca\Models\LcRmReportManagement $lcRmReportManagement
 * @property Modules\leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcRmHistoryAttachments
 * @property integer $lc_rm_report_management_id
 * @property integer $users_id
 * @property string $name
 * @property string $user_name
 * @property string $attachment
 * @property string $status
 * @property string $comments
 */
class InformCustomerAtteched extends Model {
    use SoftDeletes;

    public $table = 'lc_rm_attachment';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_rm_report_management_id',
        'users_id',
        'name',
        'user_name',
        'attachment',
        'status',
        'comments'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_rm_report_management_id' => 'integer',
        'users_id' => 'integer',
        'name' => 'string',
        'user_name' => 'string',
        'attachment' => 'string',
        'status' => 'string',
        'comments' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_rm_report_management_id' => 'required|integer',
        'users_id' => 'required',
        'name' => 'nullable|string|max:255',
        'user_name' => 'nullable|string|max:255',
        'attachment' => 'nullable|string',
        'status' => 'nullable|string|max:9',
        'comments' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function reportManagement() {
        return $this->belongsTo(\Modules\leca\Models\ReportManagement::class, 'lc_rm_report_management_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\leca\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcRmHistoryAttachments() {
        return $this->hasMany(\Modules\leca\Models\RmHistoryAttachment::class, 'lc_rm_attachment_id');
    }
}
