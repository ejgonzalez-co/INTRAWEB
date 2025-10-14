<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class InflationPressure
 * @package Modules\Maintenance\Models
 * @version August 30, 2021, 3:10 pm -05
 *
 * @property string $registration_date
 * @property string $tire_reference
 * @property string $inflation_pressure
 * @property string $observation
 */
class InflationPressure extends Model {
    use SoftDeletes;

    public $table = 'mant_inflation_pressure';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'registration_date',
        'mant_tire_all_id',
        'tire_reference',
        'inflation_pressure',
        'observation'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_tire_all_id'=> 'integer',
        'registration_date' => 'date',
        'tire_reference' => 'string',
        'inflation_pressure' => 'float',
        'observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'registration_date' => 'required',
        'inflation_pressure' => 'nullable|numeric',
        'mant_tire_all_id' => 'nullable|numeric',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

            /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tireAll() {
        return $this->belongsTo(\Modules\Maintenance\Models\TireReferences::class, 'mant_tire_all_id');
    }
    
}
