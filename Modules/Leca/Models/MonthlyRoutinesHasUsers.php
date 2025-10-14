<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MonthlyRoutinesHasUsers
 * @package Modules\Leca\Models
 * @version December 6, 2021, 4:04 pm -05
 *
 * @property Modules\Leca\Models\LcListTrial $lcListTrials
 * @property Modules\Leca\Models\LcMonthlyRoutine $lcMonthlyRoutines
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_monthly_routines_id
 * @property integer $users_id
 * @property integer $lc_list_trials_id
 */
class MonthlyRoutinesHasUsers extends Model {
    use SoftDeletes;

    public $table = 'lc_monthly_routines_has_users';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_monthly_routines_id',
        'users_id',
        'lc_list_trials_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_monthly_routines_id' => 'integer',
        'users_id' => 'integer',
        'lc_list_trials_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'lc_monthly_routines_id' => 'required',
        'users_id' => 'required',
        'lc_list_trials_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcListTrials() {
        return $this->belongsTo(\Modules\Leca\Models\ListTrials::class, 'lc_list_trials_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcMonthlyRoutines() {
        return $this->belongsTo(\Modules\Leca\Models\MonthlyRoutines::class, 'lc_monthly_routines_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }
}
