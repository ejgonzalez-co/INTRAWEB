<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NonConformingCriteria
 * @package Modules\ImprovementPlans\Models
 * @version September 26, 2023, 10:20 am -05
 *
 * @property \Modules\ImprovementPlans\Models\PmEvaluation $evaluations
 * @property integer $evaluations_id
 * @property string $criteria_name
 * @property string $status
 * @property string $observations
 * @property string $description_cause_analysis
 * @property string $weight
 * @property string $possible_causes
 * @property string $execution_percentage
 */
class NonConformingCriteria extends Model
{
        use SoftDeletes;

    public $table = 'pm_evaluation_criteria';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    protected $appends = ['percentage_execution','sum_weigth_goals','can_edit'];
    
    public $fillable = [
        'id',
        'evaluations_id',
        'criteria_name',
        'status',
        'observations',
        'description_cause_analysis',
        'weight',
        'possible_causes',
        'execution_percentage'
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
        'observations' => 'string',
        'description_cause_analysis' => 'string',
        'weight' => 'string',
        'possible_causes' => 'string',
        'execution_percentage' => 'string'
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
        'description_cause_analysis' => 'nullable|string',
        'weight' => 'nullable|string|max:3',
        'possible_causes' => 'nullable|string',
        'execution_percentage' => 'nullable|string|max:3',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function evaluation() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\Evaluation::class, 'evaluations_id');
    }

    public function goals()
    {
        return $this->hasMany(\Modules\ImprovementPlans\Models\Goal::class, 'pm_evaluation_criteria_id');
    }

    public function opportunities()
    {
        return $this->hasMany(\Modules\ImprovementPlans\Models\ImprovementOpportunity::class, 'evaluation_criteria_id');
    }
    public function getPercentageExecutionAttribute(){
        $goals = Goal::where("pm_evaluation_criteria_id",$this->id)->get()->toArray();
        $executionPercentaje = 0;
        foreach ($goals as $goal) {
            $executionPercentaje += $goal["goal_weight"] * ($goal["percentage_execution"] / 100);
         }
        return $executionPercentaje;
    }

    public function getSumWeigthGoalsAttribute(){
        return $this->goals->sum('goal_weight');
    }
    public function getCanEditAttribute(){
        return isset($this->evaluation->status_improvement_plan) && ($this->evaluation->status_improvement_plan == 'Pendiente' || $this->evaluation->status_improvement_plan == 'Devuelto');
    }
}
