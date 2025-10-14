<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TypeImprovementOpportunity
 * @package Modules\ImprovementPlans\Models
 * @version August 26, 2023, 2:49 pm -05
 *
 * @property \Modules\ImprovementPlans\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $pmImprovementOpportunities
 * @property integer $users_id
 * @property string $name
 * @property string $status
 */
class TypeImprovementOpportunity extends Model
{
        use SoftDeletes;

    public $table = 'pm_type_oportunity_improvements';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
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
        'users_id' => 'integer',
        'name' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'name' => 'nullable|string|max:45',
        'status' => 'nullable|string|max:8',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pmImprovementOpportunities() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\PmImprovementOpportunity::class, 'type_oportunity_improvements_id');
    }
}
