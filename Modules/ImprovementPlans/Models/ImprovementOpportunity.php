<?php

namespace Modules\ImprovementPlans\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ImprovementOpportunity
 * @package Modules\ImprovementPlans\Models
 * @version August 30, 2023, 10:55 pm -05
 *
 * @property \Modules\ImprovementPlans\Models\PmEvaluation $evaluations
 * @property \Modules\ImprovementPlans\Models\PmSourceInformation $sourceInformation
 * @property \Modules\ImprovementPlans\Models\PmTypeOportunityImprovement $typeOportunityImprovements
 * @property \Illuminate\Database\Eloquent\Collection $pmImprovementPlans
 * @property integer $evaluations_id
 * @property integer $source_information_id
 * @property integer $type_oportunity_improvements_id
 * @property string $name_opportunity_improvement
 * @property string $description_opportunity_improvement
 * @property string $unit_responsible_improvement_opportunity
 * @property string $official_responsible
 * @property string $deadline_submission
 * @property string $evidence
 * @property string $evaluation_criteria
 */
class ImprovementOpportunity extends Model
{
        use SoftDeletes;

    public $table = 'pm_improvement_opportunities';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    protected $appends = ['percentage_execution','sum_weigth_goals','can_edit'];

    
    public $fillable = [
        'evaluations_id',
        'source_information_id',
        'type_oportunity_improvements_id',
        'name_opportunity_improvement',
        'description_opportunity_improvement',
        'unit_responsible_improvement_opportunity',
        'official_responsible',
        'deadline_submission',
        'evidence',
        'evaluation_criteria',
        'evaluation_criteria_id',
        'weight',
        'dependencia_id',
        'official_responsible_id',
        'description_cause_analysis',
        'possible_causes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'evaluations_id' => 'integer',
        'source_information_id' => 'integer',
        'type_oportunity_improvements_id' => 'integer',
        'name_opportunity_improvement' => 'string',
        'description_opportunity_improvement' => 'string',
        'unit_responsible_improvement_opportunity' => 'string',
        'official_responsible' => 'string',
        'deadline_submission' => 'date',
        'evidence' => 'string',
        'evaluation_criteria' => 'string',
        'evaluation_criteria_id' => 'integer',
        'dependencia_id' => 'integer',
        'official_responsible_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'evaluations_id' => 'required',
        'source_information_id' => 'required|integer',
        'type_oportunity_improvements_id' => 'required|integer',
        // 'name_opportunity_improvement' => 'nullable|string|max:45',
        'description_opportunity_improvement' => 'nullable|string',
        'unit_responsible_improvement_opportunity' => 'nullable|string|max:100',
        'official_responsible' => 'nullable|string|max:255',
        'deadline_submission' => 'nullable',
        'evidence' => 'nullable|string|max:500',
        'evaluation_criteria' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function evaluation() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\Evaluation::class, 'evaluations_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function sourceInformation() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\SourceInformation::class, 'source_information_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function typeOportunityImprovements() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\TypeImprovementOpportunity::class, 'type_oportunity_improvements_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pmImprovementPlans() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\PmImprovementPlan::class, 'pm_improvement_opportunities_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function nonConformingCriterias() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\NonConformingCriteria::class, 'evaluation_criteria_id');
    }


    public function goals()
    {
        return $this->hasMany(\Modules\ImprovementPlans\Models\Goal::class, 'pm_improvement_opportunity_id')->with(["GoalActivities" => function ($query) {
            $query->with('GoalResponsibles');
        }]);
    }

    public function getPercentageExecutionAttribute(){
        $goals = Goal::where("pm_improvement_opportunity_id",$this->id)->get()->toArray();
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
