<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Goal
 * @package Modules\ImprovementPlans\Models
 * @version September 26, 2023, 3:52 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $pmGoalActivities
 * @property \Illuminate\Database\Eloquent\Collection $pmGoalDependencies
 * @property \Illuminate\Database\Eloquent\Collection $pmGoalHistories
 * @property \Illuminate\Database\Eloquent\Collection $pmGoalProgresses
 * @property string $goal_type
 * @property string $goal_name
 * @property string $goal_weight
 * @property string $indicator_description
 */
class Goal extends Model
{
        use SoftDeletes;

    public $table = 'pm_goals';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    protected $appends = ["percentage_execution","sum_weigth_activities","can_edit_goal","quantity_progress_pending"];

    
    public $fillable = [
        'pm_evaluation_criteria_id',
        'goal_type',
        'goal_name',
        'goal_weight',
        'indicator_description',
        'execution_percentage',
        'status_modification',
        'commitment_date',
        'pm_improvement_opportunity_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'goal_type' => 'string',
        'goal_name' => 'string',
        'goal_weight' => 'string',
        'indicator_description' => 'string',
        'status_modification' => 'string',
        'commitment_date' => 'date:Y-m-d', // castear como fecha sin hora
        
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'goal_type' => 'nullable|string|max:12',
        'goal_name' => 'nullable|string|max:50',
        'goal_weight' => 'nullable|string|max:4',
        'indicator_description' => 'nullable|string',
        'status_modification' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function getQuantityProgressPendingAttribute(){
        $quantityProgressPending = GoalProgress::where("pm_goals_id",$this->id)->where("status","Revisión")->count();
        return $quantityProgressPending;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function criterio() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\NonConformingCriteria::class, 'pm_evaluation_criteria_id');
    }

    public function opportunity() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\ImprovementOpportunity::class, 'pm_improvement_opportunity_id')->with("evaluation");
    }

    // En el modelo Goal
    public function GoalActivities() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\GoalActivity::class, 'pm_goals_id')->with("GoalProgresses");
    }

    public function GoalResponsibles() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\GoalResponsible::class, 'pm_goals_id');
    }

    
    public function GoalActivitiesCuantitativas() {
        return $this->GoalActivities()->where(function ($query) {
            $query->where('goal_type', 'Cuantitativa')->where('status_modification', 'Aprobado')->orwhere('status_modification', 'Creado');
            return $query;
        });
    }
    public function GoalActivitiesCualitativas() {
        return $this->GoalActivities()->where(function ($query) {
            $query->where('goal_type', 'Cualitativa')->where('status_modification', 'Aprobado')->orwhere('status_modification', 'Creado');
        });
    }

    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function GoalDependencies() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\GoalDependencies::class, 'pm_goals_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pmGoalHistories() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\PmGoalHistory::class, 'pm_goals_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pmGoalProgresses() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\PmGoalProgress::class, 'pm_goals_id');
    }

    public function getPercentageExecutionAttribute(){
        $goal = Goal::select("goal_weight","goal_type")->where("id",$this->id)->first();
        $goalProgress = GoalProgress::with("GoalActivities")->where("status","Aprobado")->where("pm_goals_id",$this->id)->get()->toArray();

        $executionPercentage = 0;
        if (isset($goal) && $goal != null) {

            if($goal->goal_type == "Cuantitativa"){
                $executionPercentage = 0;
                foreach ($goalProgress as $progress) {
                    if(isset($progress["goal_activities"]["gap_meet_goal"])){
                        $activityExecutionPercentage = ($progress["progress_weigth"] * 100) / $progress["goal_activities"]["gap_meet_goal"];
                        $executionPercentage+= $progress["activity_weigth"] * ($activityExecutionPercentage / 100);
                    }
                   
                }
            } else {

                $executionPercentage = 0;
                foreach ($goalProgress as $progress) {
                    // $activityExecutionPercentage = ($progress["progress_weigth"] * 100) / $progress["activity_weigth"];
                    // $executionPercentage+= $progress["activity_weigth"] * ($activityExecutionPercentage / 100);
                    $executionPercentage += $progress["activity_weigth"];
                }
            }
        }
      
        return $executionPercentage;
    }

    public function getSumWeigthActivitiesAttribute(){
        return $this->GoalActivities->sum('activity_weigth');
    }

    public function getCanEditGoalAttribute() {
        // return $this->criterio->can_edit ?? true;

        $evaluations_id = ImprovementOpportunity::where("id", $this->pm_improvement_opportunity_id)->value("evaluations_id");

        $status = Evaluation::where("id", $evaluations_id )->value("status_improvement_plan");
    
        if($status){
            return $status;
        }else{
            return "Sin estado";

        }
        
    }
    

    
}
