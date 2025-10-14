<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TireReference
 * @package Modules\Maintenance\Models
 * @version July 28, 2023, 3:53 pm -05
 *
 * @property Modules\Maintenance\Models\MantTireBrand $mantTireBrand
 * @property integer $mant_tire_brand_id
 * @property string $reference_name
 */
class TireReference extends Model {
    use SoftDeletes;

    public $table = 'mant_tire_references';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_tire_brand_id',
        'reference_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_tire_brand_id' => 'integer',
        'reference_name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_tire_brand_id' => 'required',
        'reference_name' => 'nullable|string|max:80',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantTireBrand() {
        return $this->belongsTo(\Modules\Maintenance\Models\TireBrand::class, 'mant_tire_brand_id');
    }
}
