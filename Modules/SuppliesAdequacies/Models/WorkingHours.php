<?php

namespace Modules\SuppliesAdequacies\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WorkingHours
 * @package Modules\SuppliesAdequacies\Models
 * @version November 13, 2024, 3:11 pm -05
 *
 * @property string $star_time_am
 * @property string $time_end_am
 * @property string $star_time_pm
 * @property string $time_end_pm
 */
class WorkingHours extends Model {
    use SoftDeletes;

    public $table = 'requests_supplies_adjustements_working_hours';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'star_time_am',
        'time_end_am',
        'star_time_pm',
        'time_end_pm'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'star_time_am' => 'string',
        'time_end_am' => 'string',
        'star_time_pm' => 'string',
        'time_end_pm' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'star_time_am' => 'nullable|string|max:20',
        'time_end_am' => 'nullable|string|max:20',
        'star_time_pm' => 'nullable|string|max:20',
        'time_end_pm' => 'nullable|string|max:20',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
