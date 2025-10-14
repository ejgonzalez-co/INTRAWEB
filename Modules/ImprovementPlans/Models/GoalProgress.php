<?php

namespace Modules\ImprovementPlans\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GoalProgress
 * @package Modules\ImprovementPlans\Models
 * @version October 30, 2023, 10:09 pm -05
 *
 * @property \Modules\ImprovementPlans\Models\PmGoalActivity $pmGoalActivities
 * @property \Modules\ImprovementPlans\Models\PmGoal $pmGoals
 * @property integer $pm_goals_id
 * @property integer $pm_goal_activities_id
 * @property string $goal_name
 * @property string $activity_name
 * @property string $activity_weigth
 * @property string $progress_weigth
 * @property string $evidence_description
 * @property string $url_progress_evidence
 */
class GoalProgress extends Model
{
        use SoftDeletes;

    public $table = 'pm_goal_progress';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    protected $appends = ["percentage_execution","evaluator_name"];

    public $fillable = [
        'user_id',
        'pm_goals_id',
        'pm_goal_activities_id',
        'goal_name',
        'activity_name',
        'activity_weigth',
        'progress_weigth',
        'evidence_description',
        'observation',
        'url_progress_evidence',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pm_goals_id' => 'integer',
        'pm_goal_activities_id' => 'integer',
        'goal_name' => 'string',
        'activity_name' => 'string',
        'activity_weigth' => 'string',
        'progress_weigth' => 'string',
        'evidence_description' => 'string',
        'observation' => 'string',
        'url_progress_evidence' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pm_goals_id' => 'required',
        'pm_goal_activities_id' => 'required',
        'goal_name' => 'nullable|string|max:100',
        'activity_name' => 'nullable|string|max:100',
        'activity_weigth' => 'nullable|string|max:4',
        'progress_weigth' => 'nullable|string|max:4',
        'evidence_description' => 'nullable|string',
        'url_progress_evidence' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function CreatorUser()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function GoalActivities() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\GoalActivity::class, 'pm_goal_activities_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function Goals() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\Goal::class, 'pm_goals_id');
    }

    public function getPercentageExecutionAttribute(){

        $actividad = $this->GoalActivities;
        // dd($actividad);
        $executionPercentage = 0;

        if (isset($actividad) && $actividad != null) {
            if($actividad->goal_type == "Cuantitativa"){
                $executionPercentage = 0;
                $executionPercentage = ($this->progress_weigth * 100) / $actividad->gap_meet_goal;            
            }else{

                $executionPercentage = 100;
            }
        
        }
        return $executionPercentage;
    }

    public function getEvaluatorNameAttribute(){
        $goalProgress = GoalProgress::with([
            'goals' => function ($query) {
                return $query->with([
                    'opportunity' => function ($query) {
                        return $query->with([
                            'evaluation' => function ($query) {
                                return $query->with('evaluator');
                            }
                        ]);
                    }
                ]);
            }
        ])
        ->where('id', $this->id)
        ->first();
        $evaluatorName = $goalProgress->goals->opportunity->evaluation->evaluator["name"];
        return $evaluatorName; 
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
