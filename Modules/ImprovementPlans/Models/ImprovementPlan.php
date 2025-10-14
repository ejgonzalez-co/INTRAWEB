<?php

namespace Modules\ImprovementPlans\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ImprovementPlan
 * @package Modules\ImprovementPlans\Models
 * @version September 26, 2023, 8:20 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $pmEvaluationCriteria
 * @property \Illuminate\Database\Eloquent\Collection $pmEvaluationDependences
 * @property \Illuminate\Database\Eloquent\Collection $pmImprovementOpportunities
 * @property \Illuminate\Database\Eloquent\Collection $pmImprovementPlans
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
 * @property string $is_accordance
 * @property string $execution_percentage
 */
class ImprovementPlan extends Model
{
        use SoftDeletes;

    public $table = 'pm_evaluations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    protected $appends = ["percentage_execution","quantity_goal","activities_plans"];
    
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
        'is_accordance' => 'string',
        'execution_percentage' => 'string',
        'no_improvement_plan' => 'integer',
        'status_improvement_plan' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'evaluator_id' => 'required',
        'evaluated_id' => 'required',
        'type_evaluation' => 'nullable|string|max:45',
        'evaluation_name' => 'nullable|string|max:45',
        'objective_evaluation' => 'nullable|string',
        'evaluation_scope' => 'nullable|string',
        'evaluation_site' => 'nullable|string|max:45',
        'evaluation_start_date' => 'nullable',
        'evaluation_start_time' => 'nullable|string|max:10',
        'evaluation_end_date' => 'nullable',
        'evaluation_end_time' => 'nullable|string|max:10',
        'unit_responsible_for_evaluation' => 'nullable|string|max:45',
        'evaluation_officer' => 'nullable|string|max:45',
        'process' => 'nullable|string',
        'attached' => 'nullable|string',
        'status' => 'nullable|string|max:45',
        'evaluation_process_attachment' => 'nullable|string|max:500',
        'general_description_evaluation_results' => 'nullable|string',
        'name_improvement_plan' => 'nullable|string|max:100',
        'is_accordance' => 'nullable|string|max:2',
        'execution_percentage' => 'nullable|string|max:3',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
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
    public function pmEvaluationCriteria() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\PmEvaluationCriterion::class, 'evaluations_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pmEvaluationDependences() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\PmEvaluationDependence::class, 'evaluations_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pmImprovementOpportunities() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\PmImprovementOpportunity::class, 'evaluations_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pmImprovementPlans() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\PmImprovementPlan::class, 'pm_evaluations_id');
    }

    public function getPercentageExecutionAttribute(){
        $criterias = ImprovementOpportunity::where("evaluations_id",$this->id)->get()->toArray();
        $executionPercentaje = 0;
        foreach ($criterias as $criteria) {
            $executionPercentaje += $criteria["weight"] * ($criteria["percentage_execution"] / 100);
         }
        return $executionPercentaje;
    }

    public function EvaluationDependences()
    {
        return $this->hasMany(\Modules\ImprovementPlans\Models\EvaluationDependence::class, 'evaluations_id');
    }
       /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function EvaluationCriteria()
    {
        return $this->hasMany(\Modules\ImprovementPlans\Models\NonConformingCriteria::class, 'evaluations_id')->with("opportunities")->where("status", "No conforme");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
        public function HistoryEvaluation()
    {
        // El order by sirve para ordenar los objetos creados, en este caso de una manera descendiente
        return $this->hasMany(\Modules\ImprovementPlans\Models\EvaluationHistory::class, 'pm_evaluations_id')->orderBy('created_at', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function EvaluationImprovementOpportunities() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\ImprovementOpportunity::class, 'evaluations_id')->with(["sourceInformation","typeOportunityImprovements"]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function getActivitiesPlansAttribute() {
        $opportunities = ImprovementOpportunity::where("evaluations_id", $this->id)->get();
        $goals = Goal::whereIn("pm_improvement_opportunity_id", $opportunities->pluck("id"))->get();
        $activity = GoalActivity::whereIn("pm_goals_id", $goals->pluck("id"))->get();
        return $activity;
    }

    public function getQuantityGoalAttribute(){
        $criterias = NonConformingCriteria::select("id")->where("evaluations_id",$this->id)->get()->toArray();
        $criteriasId = array_column($criterias,"id");
        $goals = Goal::select("id")->where("pm_evaluation_criteria_id",$criteriasId)->where("commitment_date", "<", date("Y-m-d"))->get()->count();
        return $goals;
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
