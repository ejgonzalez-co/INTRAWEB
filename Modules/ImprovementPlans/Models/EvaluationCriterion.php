<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EvaluationCriterion
 * @package Modules\ImprovementPlans\Models
 * @version August 30, 2023, 3:42 pm -05
 *
 * @property \Modules\ImprovementPlans\Models\PmEvaluation $evaluations
 * @property integer $evaluations_id
 * @property string $criteria_name
 * @property string $status
 * @property string $observations
 */
class EvaluationCriterion extends Model
{
        use SoftDeletes;

    public $table = 'pm_evaluation_criteria';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'evaluations_id',
        'criteria_name',
        'status',
        'observations'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'evaluations_id' => 'integer',
        'criteria_name' => 'string',
        'status' => 'string',
        'observations' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'evaluations_id' => 'required',
        'criteria_name' => 'nullable|string|max:45',
        'status' => 'nullable|string|max:45',
        'observations' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function evaluations() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\PmEvaluation::class, 'evaluations_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function Goals() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\Goal::class, 'pm_evaluation_criteria_id')->with("GoalActivities");
    }
}
