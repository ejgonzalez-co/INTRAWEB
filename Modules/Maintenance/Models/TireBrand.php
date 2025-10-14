<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TireBrand
 * @package Modules\Maintenance\Models
 * @version August 27, 2021, 2:55 pm -05
 *
 * @property string $brand_name
 * @property string $descripction
 */
class TireBrand extends Model
{
    use SoftDeletes;

    public $table = 'mant_tire_brand';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'brand_name',
        'descripction'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'brand_name' => 'string',
        'descripction' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'brand_name' => 'required|string|max:120',
        'descripction' => 'string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function TireReferences() {
        return $this->hasMany(\Modules\Maintenance\Models\TireReference::class, 'mant_tire_brand_id');
    }
}
