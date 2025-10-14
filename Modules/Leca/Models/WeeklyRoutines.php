<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WeeklyRoutines
 * @package Modules\Leca\Models
 * @version November 22, 2021, 10:36 am -05
 *
 * @property Modules\Leca\Models\LcMonthlyRoutine $lcMonthlyRoutines
 * @property Modules\Leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcOfficials
 * @property integer $users_id
 * @property integer $lc_monthly_routines_id
 */
class WeeklyRoutines extends Model {
    use SoftDeletes;

    public $table = 'lc_weekly_routines';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'lc_monthly_routines_id',
        'non_business_days',
        'contractor_id',
        'user_name_officials'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'lc_monthly_routines_id' => 'integer',
        'non_business_days' => 'string',
        'contractor_id' => 'string',
        'user_name_officials'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'lc_monthly_routines_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function usersContract() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'contractor_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function lcOfficials() {
        return $this->belongsToMany(\Modules\Leca\Models\LcOfficial::class, 'lc_weekly_routines_has_lc_officials');
    }
}
