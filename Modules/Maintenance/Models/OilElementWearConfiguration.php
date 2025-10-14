<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class OilElementWearConfiguration
 * @package Modules\Maintenance\Models
 * @version December 17, 2021, 8:40 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $mantOilElementWears
 * @property \Illuminate\Database\Eloquent\Collection $mantOils
 * @property string|\Carbon\Carbon $register_date
 * @property string $element_name
 * @property integer $rank_higher
 * @property integer $rank_lower
 * @property string $observation
 */
class OilElementWearConfiguration extends Model {
    use SoftDeletes;

    public $table = 'mant_oil_element_wear_configurations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'register_date',
        'element_name',
        'rank_higher',
        'rank_lower',
        'observation',
        'group',
        'component'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'register_date' => 'datetime',
        'element_name' => 'string',
        'rank_higher' => 'string',
        'rank_lower' => 'string',
        'observation' => 'string',
        'group' => 'string',
        'component' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'register_date' => 'nullable',
        'element_name' => 'nullable|string|max:100',
        'rank_higher' => 'nullable|string',
        'rank_lower' => 'nullable|string',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantOilElementWears() {
        return $this->hasMany(\Modules\Maintenance\Models\OilElementWear::class, 'mant_oil_element_wear_configurations_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantOils() {
        return $this->hasMany(\Modules\Maintenance\Models\Oil::class, 'mant_oil_element_wear_configurations_id');
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
