<?php

namespace Modules\ImprovementPlans\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Evaluation
 * @package Modules\ImprovementPlan\Models
 * @version August 4, 2023, 11:29 am -05
 *
 * @property \Modules\ImprovementPlan\Models\User $evaluator
 * @property \Modules\ImprovementPlan\Models\User $evaluated
 * @property \Illuminate\Database\Eloquent\Collection $pmEvaluationCriteria
 * @property \Illuminate\Database\Eloquent\Collection $pmEvaluationDependences
 * @property integer $evaluator_id
 * @property integer $evaluated_id
 * @property string $type_evaluation
 * @property string $evaluation_name
 * @property string $objective_evaluation
 * @property string $evaluation_scope
 * @property string $evaluation_site
 * @property string $evaluation_start_date
 * @property string $evaluation_start_time
 * @property string $evaluation_end_date
 * @property string $evaluation_end_time
 * @property string $unit_responsible_for_evaluation
 * @property string $evaluation_officer
 * @property string $process
 * @property string $attached
 * @property string $status
 * @property string $evaluation_process_attachment
 * @property string $general_description_evaluation_results
 * @property string $name_improvement_plan
 */
class Evaluation extends Model
{
    use SoftDeletes;

    public $table = 'pm_evaluations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'evaluator_id',
        'evaluated_id',
        'type_evaluation',
        'evaluation_name',
        'objective_evaluation',
        'evaluation_scope',
        'evaluation_site',
        'evaluation_start_date',
        'evaluation_start_time',
        'evaluation_end_date',
        'evaluation_end_time',
        'unit_responsible_for_evaluation',
        'evaluation_officer',
        'process',
        'attached',
        'status',
        'evaluation_process_attachment',
        'general_description_evaluation_results',
        'type_name_improvement_plan',
        'name_improvement_plan',
        'is_accordance',
        'execution_percentage',
        'no_improvement_plan',
        'status_improvement_plan'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'evaluator_id' => 'integer',
        'evaluated_id' => 'integer',
        'type_evaluation' => 'string',
        'evaluation_name' => 'string',
        'objective_evaluation' => 'string',
        'evaluation_scope' => 'string',
        'evaluation_site' => 'string',
        'evaluation_start_date' => 'date',
        'evaluation_start_time' => 'string',
        'evaluation_end_date' => 'date',
        'evaluation_end_time' => 'string',
        'unit_responsible_for_evaluation' => 'string',
        'evaluation_officer' => 'string',
        'process' => 'string',
        'attached' => 'string',
        'status' => 'string',
        'evaluation_process_attachment' => 'string',
        'general_description_evaluation_results' => 'string',
        'name_improvement_plan' => 'string',
        'type_name_improvement_plan' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'evaluator_id' => 'required',
        // 'evaluated_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function evaluator()
    {
        return $this->belongsTo(\App\User::class, 'evaluator_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function evaluated()
    {
        return $this->belongsTo(\App\User::class, 'evaluated_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function EvaluationCriteria()
    {
        return $this->hasMany(\Modules\ImprovementPlans\Models\EvaluationCriterion::class, 'evaluations_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function EvaluationDependences()
    {
        return $this->hasMany(\Modules\ImprovementPlans\Models\EvaluationDependence::class, 'evaluations_id');
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
