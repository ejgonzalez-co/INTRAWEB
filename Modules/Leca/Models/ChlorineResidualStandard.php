<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ChlorineResidualStandard
 * @package Modules\Leca\Models
 * @version January 16, 2022, 9:05 pm -05
 *
 * @property Modules\Leca\Models\LcStartSampling $lcStartSampling
 * @property integer $lc_start_sampling_id
 * @property string $chlorine_residual
 */
class ChlorineResidualStandard extends Model {
    use SoftDeletes;

    public $table = 'lc_chlorine_residual_standard';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_start_sampling_id',
        'chlorine_residual'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'lc_start_sampling_id' => 'integer',
        'chlorine_residual' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_start_sampling_id' => 'required',
        'chlorine_residual' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcStartSampling() {
        return $this->belongsTo(\Modules\Leca\Models\LcStartSampling::class, 'lc_start_sampling_id');
    }
}
