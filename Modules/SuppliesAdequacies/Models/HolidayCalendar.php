<?php

namespace Modules\SuppliesAdequacies\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HolidayCalendar
 * @package Modules\SuppliesAdequacies\Models
 * @version November 13, 2024, 3:17 pm -05
 *
 * @property integer $users_id
 * @property string|\Carbon\Carbon $date
 */
class HolidayCalendar extends Model {
    use SoftDeletes;

    public $table = 'requests_supplies_adjustements_holiday_calendar';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'date' => 'datetime:Y-m-d'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'date' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
