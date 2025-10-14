<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HistoryAttachmentReport
 * @package Modules\leca\Models
 * @version December 15, 2022, 9:40 am -05
 *
 * @property Modules\leca\Models\LcRmAttachment $lcRmAttachment
 * @property Modules\leca\Models\User $users
 * @property integer $users_id
 * @property integer $lc_rm_attachment_id
 * @property string $user_name
 * @property string $status
 * @property string|\Carbon\Carbon $update_at
 */
class HistoryAttachmentReport extends Model {
    use SoftDeletes;

    public $table = 'lc_rm_history_attachment';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'lc_rm_attachment_id',
        'user_name',
        'status',
        'updated_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'lc_rm_attachment_id' => 'integer',
        'user_name' => 'string',
        'status' => 'string',
        'updated_at' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'lc_rm_attachment_id' => 'required|integer',
        'user_name' => 'nullable|string|max:255',
        'status' => 'nullable|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcRmAttachment() {
        return $this->belongsTo(\Modules\leca\Models\ReporManagementAttachment::class, 'lc_rm_attachment_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\leca\Models\User::class, 'users_id');
    }
}
