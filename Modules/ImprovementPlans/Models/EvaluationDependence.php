<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EvaluationDependence
 * @package Modules\ImprovementPlans\Models
 * @version August 30, 2023, 3:40 pm -05
 *
 * @property \Modules\ImprovementPlans\Models\PmEvaluation $evaluations
 * @property integer $evaluations_id
 * @property string $dependence_name
 */
class EvaluationDependence extends Model
{
        use SoftDeletes;

    public $table = 'pm_evaluation_dependences';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'evaluations_id',
        'dependence_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'evaluations_id' => 'integer',
        'dependence_name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'evaluations_id' => 'required',
        'dependence_name' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function evaluations() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\PmEvaluation::class, 'evaluations_id');
    }
}
