<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ConfigurationMessageTime
 * @package Modules\ImprovementPlans\Models
 * @version September 6, 2023, 8:51 am -05
 *
 * @property integer $anticipated_days
 * @property string $message
 */
class ConfigurationMessageTime extends Model
{
        use SoftDeletes;

    public $table = 'pm_configuration_message_times';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'anticipated_days',
        'message'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'anticipated_days' => 'integer',
        'message' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'anticipated_days' => 'nullable|integer',
        'message' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
