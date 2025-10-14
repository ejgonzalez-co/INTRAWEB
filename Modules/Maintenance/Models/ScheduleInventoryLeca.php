<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ScheduleInventoryLeca
 * @package Modules\Maintenance\Models
 * @version May 6, 2021, 5:23 pm -05
 *
 * @property Modules\Maintenance\Models\MantInventoryMetrologicalScheduleLeca $mantInventoryMetrologicalScheduleLeca
 * @property string $month
 * @property string $metrological_activity
 * @property string $description
 * @property integer $mant_inventory_metrological_schedule_leca_id
 */
class ScheduleInventoryLeca extends Model
{
    use SoftDeletes;

    public $table = 'mant_schedule_inventory_leca';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'month',
        'metrological_activity',
        'description',
        'mant_inventory_metrological_schedule_leca_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'month' => 'string',
        'metrological_activity' => 'string',
        'description' => 'string',
        'mant_inventory_metrological_schedule_leca_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_inventory_metrological_schedule_leca_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantInventoryMetrologicalScheduleLeca()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\MantInventoryMetrologicalScheduleLeca::class, 'mant_inventory_metrological_schedule_leca_id');
    }
}
