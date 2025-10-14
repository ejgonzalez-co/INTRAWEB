<?php

namespace Modules\PQRS\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WorkingHours
 * @package Modules\PQRS\Models
 * @version October 29, 2020, 7:26 pm -05
 *
 * @property string $day
 * @property string $star_time_am
 * @property string $time_end_am
 * @property string $star_time_pm
 * @property string $time_end_pm
 */
class WorkingHours extends Model
{

    public $table = 'working_hours';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'day',
        'star_time_am',
        'time_end_am',
        'star_time_pm',
        'time_end_pm',

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'day' => 'string',
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
        
    ];

    
}
