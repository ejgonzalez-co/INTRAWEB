<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PatronNtu
 * @package Modules\Leca\Models
 * @version July 7, 2022, 3:22 pm -05
 *
 * @property Modules\Leca\Models\LcStartSampling $lcStartSampling
 * @property integer $lc_start_sampling_id
 * @property string $patron
 */
class PatronNtu extends Model {
    use SoftDeletes;

    public $table = 'lc_patron_ntu';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_start_sampling_id',
        'patron'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_start_sampling_id' => 'integer',
        'patron' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_start_sampling_id' => 'required',
        'patron' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcStartSampling() {
        return $this->belongsTo(\Modules\Leca\Models\StartSampling::class, 'lc_start_sampling_id');
    }
}
