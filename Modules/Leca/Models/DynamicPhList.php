<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DynamicPhList
 * @package Modules\Leca\Models
 * @version January 5, 2022, 10:24 am -05
 *
 * @property Modules\Leca\Models\LcSampleTaking $lcSampleTaking
 * @property integer $lc_sample_taking_id
 * @property string $ph_unit
 * @property string $temperature_range
 */
class DynamicPhList extends Model {
    use SoftDeletes;

    public $table = 'lc_dynamic_ph_list';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_sample_taking_id',
        'ph_unit',
        'temperature_range'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'lc_sample_taking_id' => 'integer',
        'ph_unit' => 'string',
        'temperature_range' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_sample_taking_id' => 'required',
        'ph_unit' => 'nullable|string|max:255',
        'temperature_range' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcSampleTaking() {
        return $this->belongsTo(\Modules\Leca\Models\LcSampleTaking::class, 'lc_sample_taking_id');
    }
}
