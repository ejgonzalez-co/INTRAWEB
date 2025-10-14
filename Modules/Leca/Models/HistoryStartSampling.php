<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HistoryStartSampling
 * @package Modules\Leca\Models
 * @version January 19, 2022, 9:45 pm -05
 *
 * @property Modules\Leca\Models\LcStartSampling $lcStartSampling
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_start_sampling_id
 * @property integer $users_id
 * @property string $user_name
 * @property string $date
 * @property string $action
 * @property string $observation
 */
class HistoryStartSampling extends Model {
    use SoftDeletes;

    public $table = 'lc_history_start_sampling';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_start_sampling_id',
        'users_id',
        'user_name',
        'date',
        'action',
        'observation'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_start_sampling_id' => 'integer',
        'users_id' => 'integer',
        'user_name' => 'string',
        'date' => 'string',
        'action' => 'string',
        'observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_start_sampling_id' => 'required',
        'users_id' => 'required',
        'user_name' => 'nullable|string|max:255',
        'date' => 'nullable|string|max:255',
        'action' => 'nullable|string|max:255',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcStartSampling() {
        return $this->belongsTo(\Modules\Leca\Models\LcStartSampling::class, 'lc_start_sampling_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }
}
