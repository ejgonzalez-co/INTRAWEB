<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SourceInformation
 * @package Modules\ImprovementPlans\Models
 * @version August 25, 2023, 5:08 pm -05
 *
 * @property \Modules\ImprovementPlans\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $pmImprovementOpportunities
 * @property integer $user_id
 * @property string $name
 * @property string $status
 */
class SourceInformation extends Model
{
        use SoftDeletes;

    public $table = 'pm_source_information';
    
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pmImprovementOpportunities() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\PmImprovementOpportunity::class, 'source_information_id');
    }
}
