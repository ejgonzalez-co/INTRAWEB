<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ResidualChlorineList
 * @package Modules\Leca\Models
 * @version January 5, 2022, 11:34 am -05
 *
 * @property Modules\Leca\Models\LcSampleTaking $lcSampleTaking
 * @property integer $lc_sample_taking_id
 * @property string $v_sample
 * @property string $chlorine_residual_test
 * @property string $mg_cl2
 * @property string $turbidity
 */
class ResidualChlorineList extends Model {
    use SoftDeletes;

    public $table = 'lc_residual_chlorine_list';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_sample_taking_id',
        'v_sample',
        'chlorine_residual_test',
        'mg_cl2',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'lc_sample_taking_id' => 'integer',
        'v_sample' => 'string',
        'chlorine_residual_test' => 'string',
        'mg_cl2' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_sample_taking_id' => 'required',
        'v_sample' => 'nullable|string|max:255',
        'chlorine_residual_test' => 'nullable|string|max:255',
        'mg_cl2' => 'nullable|string|max:255',
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
