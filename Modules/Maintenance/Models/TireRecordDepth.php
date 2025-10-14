<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TireRecordDepth
 * @package Modules\Maintenance\Models
 * @version September 20, 2021, 5:35 pm -05
 *
 * @property Modules\Maintenance\Models\MantTireWear $mantTireWears
 * @property integer $mant_tire_wears_id
 * @property number $data_record_depth
 */
class TireRecordDepth extends Model {
    use SoftDeletes;

    public $table = 'mant_tire_record_depth';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_tire_wears_id',
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_tire_wears_id' => 'integer',
        'name' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_tire_wears_id' => 'required',
        'name' => 'required|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function TireWears() {
        return $this->belongsTo(\Modules\Maintenance\Models\TireWear::class, 'mant_tire_wears_id');
    }
}
