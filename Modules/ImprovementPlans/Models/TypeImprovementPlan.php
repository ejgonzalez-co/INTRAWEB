<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TypeImprovementPlan
 * @package Modules\ImprovementPlans\Models
 * @version August 31, 2023, 8:48 am -05
 *
 * @property \Modules\ImprovementPlans\Models\User $user
 * @property integer $user_id
 * @property string $code
 * @property string $name
 * @property boolean $state
 * @property integer $days_anticipated
 * @property string $message
 */
class TypeImprovementPlan extends Model
{
        use SoftDeletes;

    public $table = 'pm_type_improvement_plan';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'user_id',
        'code',
        'name',
        'status',
        'days_anticipated',
        'message'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'code' => 'string',
        'name' => 'string',
        'days_anticipated' => 'integer',
        'message' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'code' => 'nullable|string|max:30',
        'name' => 'nullable|string|max:45',
        'days_anticipated' => 'nullable|integer',
        'message' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\User::class, 'user_id');
    }
}
