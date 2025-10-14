<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * Class OilElementWear
 * @package Modules\Maintenance\Models
 * @version December 19, 2021, 6:05 pm -05
 *
 * @property Modules\Maintenance\Models\MantOilElementWearConfiguration $mantOilElementWearConfigurations
 * @property Modules\Maintenance\Models\MantOil $mantOils
 * @property integer $mant_oils_id
 * @property integer $mant_oil_element_wear_configurations_id
 * @property string $detected_value
 * @property string $number_control_laboratory
 * @property string $range
 */
class OilElementWear extends Model {
    use SoftDeletes;

    public $table = 'mant_oil_element_wear';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_oils_id',
        'mant_oil_element_wear_configurations_id',
        'detected_value',
        'number_control_laboratory',
        'range',
        'group'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_oils_id' => 'integer',
        'mant_oil_element_wear_configurations_id' => 'integer',
        'detected_value' => 'string',
        'number_control_laboratory' => 'string',
        'range' => 'string',
        'group' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_oils_id' => 'required',
        'mant_oil_element_wear_configurations_id' => 'required',
        'detected_value' => 'nullable|string|max:100',
        'number_control_laboratory' => 'nullable|string|max:100',
        'range' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = [
        'name_oil_element_wear'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function oilElementWearConfigurations() {
        return $this->belongsTo(\Modules\Maintenance\Models\OilElementWearConfiguration::class, 'mant_oil_element_wear_configurations_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function oils() {
        return $this->belongsTo(\Modules\Maintenance\Models\Oil::class, 'mant_oils_id');
    }

    public function getNameOilElementWearAttribute(){
        $nameMantElement = DB::table('mant_oil_element_wear_configurations')->where('id', $this->mant_oil_element_wear_configurations_id)->first();
        return $nameMantElement;
    }
}
