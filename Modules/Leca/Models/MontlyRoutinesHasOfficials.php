<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Leca\Models\MontlyRoutinesHasOfficials;

/**
 * Class MontlyRoutinesHasOfficials
 * @package Modules\Leca\Models
 * @version November 26, 2021, 11:43 am -05
 *
 * @property Modules\Leca\Models\LcListTrial $lcListTrials
 * @property Modules\Leca\Models\LcMonthlyRoutine $lcMonthlyRoutines
 * @property Modules\Leca\Models\LcOfficial $lcOfficials
 * @property integer $lc_monthly_routines_id
 * @property integer $lc_officials_id
 * @property integer $lc_list_trials_id
 * @property string $official_show
 */
class MontlyRoutinesHasOfficials extends Model {
    use SoftDeletes;

    public $table = 'lc_monthly_routines_has_lc_officials';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_monthly_routines_id',
        'lc_officials_id',
        'lc_list_trials_id',
        'official_show'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_monthly_routines_id' => 'integer',
        'lc_officials_id' => 'integer',
        'lc_list_trials_id' => 'integer',
        'official_show' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_monthly_routines_id' => 'required',
        'lc_officials_id' => 'required',
        'lc_list_trials_id' => 'required',
        'official_show' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = [
        'multiselect',
        'name'
    ];

    public function getMultiselectAttribute(){
        
        return  $this->lcListTrials->name;

    }

    public function getNameAttribute(){
        return  $this->lcOfficials->name;

    }


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
        return $this->belongsTo(\Modules\Leca\Models\MonthlyRoutine::class, 'lc_monthly_routines_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcOfficials() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'lc_officials_id');
    }
}
