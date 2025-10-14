<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OilControlLaboratory
 * @package Modules\Maintenance\Models
 * @version December 19, 2021, 6:04 pm -05
 *
 * @property Modules\Maintenance\Models\MantOil $mantOils
 * @property integer $mant_oils_id
 * @property string $number_control_laboratory
 * @property string|\Carbon\Carbon $date_sampling
 * @property string|\Carbon\Carbon $date_process
 * @property integer $hourmeter
 * @property integer $kilometer
 * @property integer $oil_hours
 * @property string $filling
 * @property string $change_oil
 * @property integer $filling_units
 * @property string $change_filter
 * @property string $result
 * @property string $type_result
 */
class OilControlLaboratory extends Model {
    use SoftDeletes;

    public $table = 'mant_oil_control_laboratories';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_oils_id',
        'number_control_laboratory',
        'date_sampling',
        'date_process',
        'hourmeter',
        'kilometer',
        'oil_hours',
        'filling',
        'change_oil',
        'filling_units',
        'change_filter',
        'result',
        'type_result',
        'observation'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_oils_id' => 'integer',
        'number_control_laboratory' => 'string',
        'hourmeter' => 'integer',
        'kilometer' => 'integer',
        'oil_hours' => 'integer',
        'filling' => 'string',
        'change_oil' => 'string',
        'filling_units' => 'integer',
        'change_filter' => 'string',
        'result' => 'string',
        'type_result' => 'string',
        'observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_oils_id' => 'required',
        'number_control_laboratory' => 'nullable|string|max:100',
        'hourmeter' => 'nullable|integer',
        'kilometer' => 'nullable|integer',
        'oil_hours' => 'nullable|integer',
        'filling' => 'nullable|string|max:100',
        'change_oil' => 'nullable|string|max:10',
        'filling_units' => 'nullable|integer',
        'change_filter' => 'nullable|string|max:10',
        'result' => 'nullable|string|max:100',
        'type_result' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantOils() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantOil::class, 'mant_oils_id');
    }
}
