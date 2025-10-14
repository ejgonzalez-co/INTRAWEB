<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TypeEvaluation
 * @package Modules\ImprovementPlans\Models
 * @version August 25, 2023, 4:35 pm -05
 *
 * @property \Modules\ImprovementPlans\Models\User $user
 * @property integer $user_id
 * @property string $name
 * @property string $status
 */
class TypeEvaluation extends Model
{
        use SoftDeletes;

    public $table = 'pm_type_evaluation';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'user_id',
        'name',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'name' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'name' => 'nullable|string|max:45',
        'status' => 'nullable|string|max:8',
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
