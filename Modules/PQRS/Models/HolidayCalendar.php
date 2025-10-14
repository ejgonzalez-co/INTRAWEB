<?php

namespace Modules\PQRS\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HolidayCalendar
 * @package Modules\PQRS\Models
 * @version October 29, 2020, 7:26 pm -05
 *
 */
class HolidayCalendar extends Model
{
    public $table = 'holiday_calendar';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'date' => 'datetime:Y-m-d',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
}
