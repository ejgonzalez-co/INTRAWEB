<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GoalDependencies
 * @package Modules\ImprovementPlans\Models
 * @version September 27, 2023, 11:19 am -05
 *
 * @property \Modules\ImprovementPlans\Models\PmGoal $pmGoals
 * @property integer $pm_goals_id
 * @property string $dependence_name
 * @property string $responsible
 */
class GoalDependencies extends Model
{
        use SoftDeletes;

    public $table = 'pm_goal_dependencies';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'pm_goals_id',
        'dependence_name',
        'responsible'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pm_goals_id' => 'integer',
        'dependence_name' => 'string',
        'responsible' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pm_goals_id' => 'required',
        'dependence_name' => 'nullable|string|max:80',
        'responsible' => 'nullable|string|max:80',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pmGoals() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\PmGoal::class, 'pm_goals_id');
    }
}
