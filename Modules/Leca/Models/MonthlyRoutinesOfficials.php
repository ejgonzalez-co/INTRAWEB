<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MonthlyRoutinesOfficials
 * @package Modules\Leca\Models
 * @version December 13, 2021, 11:12 am -05
 *
 * @property Modules\Leca\Models\LcMonthlyRoutine $lcMonthlyRoutines
 * @property Modules\Leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcListTrials
 * @property integer $lc_monthly_routines_id
 * @property integer $users_id
 * @property string $user_name
 */
class MonthlyRoutinesOfficials extends Model
{
    use SoftDeletes;

    public $table = 'lc_monthly_routines_officials';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'lc_monthly_routines_id',
        'users_id',
        'user_name',
        'fecha_inicio',
        'fecha_fin',
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
        'user_name' => 'string',
        'fecha_inicio' => 'string',
        'fecha_fin' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'lc_monthly_routines_id' => 'required',
        // 'users_id' => 'required',
        'user_name' => 'nullable|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
    ];

    // protected $appends = [
    //     'centinela_valor',
    // ];

    // public function getCentinelaValorAttribute()
    // {
    //     $centinela = false;
    //     $fecha_actual = strtotime(date("d-m-Y H:i:00", time()));
    //     $fecha_inicio = strtotime($this->lcMonthlyRoutines->routine_end_date);
    //     $fecha_Final = strtotime($this->lcMonthlyRoutines->routine_start_date);
        
    //     if ($fecha_inicio > $fecha_Final && $fecha_actual < $fecha_Final) {
    //         $centinela = true;
    //     } else {
    //         $centinela = false;

    //     }
    //     return $centinela;
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcMonthlyRoutines()
    {
        return $this->belongsTo(\Modules\Leca\Models\MonthlyRoutines::class, 'lc_monthly_routines_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function lcListTrials()
    {
        return $this->belongsToMany(\Modules\Leca\Models\ListTrials::class, 'lc_list_trials_has_lc_monthly_routines_officials', 'lc_monthly_routines_officials_id', 'lc_list_trials_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function historiales()
    {
        return $this->hasMany(\Modules\Leca\Models\HistorialFuncionarioRutina::class, 'lc_monthly_routines_officials_id')->latest();
    }
}
