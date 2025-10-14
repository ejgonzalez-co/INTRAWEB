<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GoalResponsible
 * @package Modules\ImprovementPlans\Models
 * @version August 28, 2024, 4:55 pm -05
 *
 * @property \Modules\ImprovementPlans\Models\PmGoal $pmGoals
 * @property integer $pm_goals_id
 * @property integer $activity
 * @property string $responsibles_names
 * @property string $responsibles_id
 */
class GoalResponsible extends Model
{
        use SoftDeletes;

    public $table = 'pm_goal_responsibles';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    protected $appends = ["activity_object","responsibles"];
    
    public $fillable = [
        'pm_goals_id',
        'activity',
        'responsibles_names',
        'responsibles_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pm_goals_id' => 'integer',
        'activity' => 'integer',
        'responsibles_names' => 'string',
        'responsibles_id' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pm_goals_id' => 'required',
        'activity' => 'nullable',
        'responsibles_names' => 'nullable|string',
        'responsibles_id' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pmGoals() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\PmGoal::class, 'pm_goals_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function GoalActivity() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\GoalActivity::class, 'activity');
    }

    public function getActivityObjectAttribute(){
        $goalActivities = GoalActivity::select(["pm_goals_id","id","activity_name","activity_weigth","start_date","end_date"])->where("pm_goals_id",$this->pm_goals_id)->first();
        return $goalActivities;
    }

    public function getResponsiblesAttribute(){
        $responsibles = User::whereRaw('FIND_IN_SET("' . $this->responsibles_id .'", id)')->first()->toArray();
        return $responsibles;
    }
}
