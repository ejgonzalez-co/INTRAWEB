<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GoalActivity
 * @package Modules\ImprovementPlans\Models
 * @version September 27, 2023, 11:15 am -05
 *
 * @property \Modules\ImprovementPlans\Models\PmGoal $pmGoals
 * @property \Illuminate\Database\Eloquent\Collection $pmGoalProgresses
 * @property integer $pm_goals_id
 * @property string $activity_name
 * @property string $activity_quantity
 * @property string $activity_weigth
 * @property string $baseline_for_goal
 * @property string $gap_meet_goal
 */
class GoalActivity extends Model
{
        use SoftDeletes;

    public $table = 'pm_goal_activities';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'pm_goals_id',
        'activity_name',
        'activity_quantity',
        'activity_weigth',
        'status_modification',
        'baseline_for_goal',
        'gap_meet_goal',
        'goal_type',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pm_goals_id' => 'integer',
        'activity_name' => 'string',
        'activity_quantity' => 'string',
        'activity_weigth' => 'string',
        'status_modification' => 'string',
        'baseline_for_goal' => 'string',
        'gap_meet_goal' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pm_goals_id' => 'required',
        'activity_name' => 'nullable|string|max:50',
        'activity_quantity' => 'nullable|string|max:5',
        'activity_weigth' => 'nullable|string|max:4',
        'status_modification' => 'nullable|string',
        'baseline_for_goal' => 'nullable|string|max:40',
        'gap_meet_goal' => 'nullable|string|max:40',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function goal() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\Goal::class, 'pm_goals_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function GoalProgresses() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\GoalProgress::class, 'pm_goal_activities_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function GoalResponsibles() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\GoalResponsible::class, 'activity');
    }
}
