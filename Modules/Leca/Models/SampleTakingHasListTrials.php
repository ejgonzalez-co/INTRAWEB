<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SampleTakingHasListTrials
 * @package Modules\Leca\Models
 * @version January 13, 2022, 2:36 pm -05
 *
 * @property Modules\Leca\Models\LcListTrial $lcListTrials
 * @property Modules\Leca\Models\LcSampleTaking $lcSampleTaking
 * @property integer $lc_list_trials_id
 */
class SampleTakingHasListTrials extends Model {
    use SoftDeletes;

    public $table = 'lc_sample_taking_has_lc_list_trials';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_sample_taking_id',
        'lc_list_trials_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'lc_sample_taking_id' => 'integer',
        'lc_list_trials_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_sample_taking_id' => 'required',
        'lc_list_trials_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcListTrials() {
        return $this->belongsTo(\Modules\Leca\Models\LcListTrial::class, 'lc_list_trials_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcSampleTaking() {
        return $this->belongsTo(\Modules\Leca\Models\LcSampleTaking::class, 'lc_sample_taking_id');
    }
}
