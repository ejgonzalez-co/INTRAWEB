<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AnnotationEvaluation
 * @package Modules\ImprovementPlans\Models
 * @version November 24, 2023, 10:17 pm -05
 *
 * @property \Modules\ImprovementPlans\Models\PmEvaluation $pmEvaluations
 * @property \Modules\ImprovementPlans\Models\User $users
 * @property integer $pm_evaluations_id
 * @property integer $users_id
 * @property string $user_name
 * @property string $observation
 */
class AnnotationEvaluation extends Model
{
        use SoftDeletes;

    public $table = 'pm_annotations_evaluations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'pm_evaluations_id',
        'users_id',
        'user_name',
        'observation'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pm_evaluations_id' => 'integer',
        // 'users_id' => 'integer',
        'user_name' => 'string',
        'observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pm_evaluations_id' => 'required',
        // 'users_id' => 'required',
        'user_name' => 'nullable|string|max:120',
        'observation' => 'nullable|string|max:120',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pmEvaluations() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\PmEvaluation::class, 'pm_evaluations_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\User::class, 'users_id');
    }
}
