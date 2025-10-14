<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Modules\Intranet\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Leca\Models\MonthlyRoutinesHasUsers;
use DB;
/**
 * Class MonthlyRoutines
 * @package Modules\Leca\Models
 * @version November 20, 2021, 10:51 am -05
 *
 * @property Modules\Leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcOfficials
 * @property \Illuminate\Database\Eloquent\Collection $lcWeeklyRoutines
 * @property integer $users_id
 * @property string $routine_start_date
 * @property string $routine_end_date
 * @property string $state_routine
 */
class MonthlyRoutines extends Model {
    use SoftDeletes;

    public $table = 'lc_monthly_routines';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'routine_start_date',
        'routine_end_date',
        'state_routine'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'state_routine' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'routine_start_date' => 'nullable|string|max:255',
        'routine_end_date' => 'nullable|string|max:255',
        'state_routine' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function lcOfficials() {
        return $this->belongsToMany(\Modules\Leca\Models\User::class,'lc_monthly_routines_officials','lc_monthly_routines_id','users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcListTrials() {
        return $this->hasMany(\Modules\Leca\Models\MonthlyRoutinesHasUsers::class, 'lc_monthly_routines_id');
        // return $this->hasMany(\Modules\Leca\Models\MonthlyRoutinesHasUsers::class, 'lc_monthly_routines_id')->with(["lcListTrials","lcOfficials"]);
        // return Modules\Leca\Models\MontlyRoutinesHasOfficials::join('lc_list_trials', 'lc_list_trials.id', '=','lc_monthly_routines_has_lc_officials.lc_list_trials_id')
        // // ->select('lc_monthly_routines_has_lc_officials.*')
        // ->selectRaw('GROUP_CONCAT(lc_list_trials.id) as holidays')
        // ->groupBy('lc_monthly_routines_has_lc_officials.id')
        // ->get();
    }


    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  **/
    // public function lcPrueba() {
    //     DB::enableQueryLog();
    //     $Resultado = $this->hasMany(\Modules\Leca\Models\MonthlyRoutinesHasUsers::class, 'lc_monthly_routines_id')->with(['lcOfficials','buena'])
    //     // ->join("lc_list_trials", "lc_list_trials.id", "=","lc_monthly_routines_has_lc_officials.lc_list_trials_id")
    //     ->select("id","lc_monthly_routines_id","users_id")
    //     // ->get();
    //     // dd($Resultado->toArray());
    //     // ->where('lc_monthly_routines_has_lc_officials.id')
    //     ->selectRaw('GROUP_CONCAT(lc_list_trials_id) as lc_list_trials_id')
    //     ->groupBy('users_id')->get();

        
    //     return $Resultado;

    //     return MontlyRoutinesHasOfficials::join('lc_list_trials', 'lc_list_trials.id', '=','lc_monthly_routines_has_lc_officials.lc_list_trials_id')
        
    //     ->select('lc_monthly_routines_has_lc_officials.*')
    //     ->selectRaw('GROUP_CONCAT(lc_list_trials.id) as holidays')
    //     ->groupBy('lc_monthly_routines_has_lc_officials.id')
    //     ->get();
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcWeeklyRoutines() {
        return $this->hasMany(\Modules\Leca\Models\WeeklyRoutine::class, 'lc_monthly_routines_id');
    }
}