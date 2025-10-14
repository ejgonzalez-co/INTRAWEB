<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SetTire
 * @package Modules\Maintenance\Models
 * @version August 28, 2021, 10:38 am -05
 *
 * @property Modules\Maintenance\Models\MantTireBrand $mantTireBrand
 * @property integer $mant_tire_brand_id
 * @property string $registration_date
 * @property string $tire_reference
 * @property number $maximum_wear
 * @property string $observation
 */
class SetTire extends Model {
    use SoftDeletes;

    public $table = 'mant_set_tires';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_tire_brand_id',
        'mant_tire_all_id',
        'registration_date',
        'tire_reference',
        'maximum_wear',
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
        'mant_tire_brand_id' => 'integer',
        'registration_date' => 'string',
        'tire_reference' => 'string',
        'maximum_wear' => 'float',
        'observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_tire_brand_id' => 'required',
        'mant_tire_all_id' => 'required',
        'registration_date' => 'nullable|string|max:20',
        'tire_reference' => 'nullable|string|max:255',
        'maximum_wear' => 'nullable|numeric',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tireBrand() {
        return $this->belongsTo(\Modules\Maintenance\Models\TireBrand::class, 'mant_tire_brand_id');
    }


        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tireAll() {
        return $this->belongsTo(\Modules\Maintenance\Models\TireReferences::class, 'mant_tire_all_id');
    }

    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function TireInformations() {
        return $this->hasMany(\Modules\Maintenance\Models\TireInformations::class, 'mant_set_tires_id');
    }
}
