<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WorkRequestHistory
 * @package Modules\Workhistories\Models
 * @version October 23, 2021, 9:21 am -05
 *
 * @property Modules\Workhistories\Models\WorkHistory $workHistories
 * @property Modules\Workhistories\Models\WorkHistoriesP $workHistoriesP
 * @property Modules\Workhistories\Models\WorkRequestCv $workRequestCv
 * @property string $user_aprobed
 * @property integer $work_request_cv_id
 * @property integer $work_histories_id
 * @property integer $work_histories_p_id
 * @property integer $work_histories_p_users_id
 * @property string $observation
 * @property string $condition
 */
class WorkRequestHistory extends Model
{
        use SoftDeletes;

    public $table = 'work_history_cv';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'user_aprobed',
        'work_request_cv_id',
        'work_histories_id',
        'work_histories_p_id',
        'work_histories_p_users_id',
        'observation',
        'condition'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_aprobed' => 'string',
        'work_request_cv_id' => 'integer',
        'work_histories_id' => 'integer',
        'work_histories_p_id' => 'integer',
        'work_histories_p_users_id' => 'integer',
        'observation' => 'string',
        'condition' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_aprobed' => 'nullable|string|max:255',
        'work_request_cv_id' => 'required',
        'work_histories_id' => 'nullable',
        'work_histories_p_id' => 'nullable',
        'work_histories_p_users_id' => 'nullable',
        'observation' => 'nullable|string',
        'condition' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistories() {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesActive::class, 'work_histories_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistoriesP() {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesP::class, 'work_histories_p_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workRequestCv() {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkRequestCv::class, 'work_request_cv_id');
    }
}
