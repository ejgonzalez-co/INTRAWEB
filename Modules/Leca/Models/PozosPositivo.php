<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PozosPositivo
 * @package Modules\leca\Models
 * @version December 5, 2022, 2:25 pm -05
 *
 * @property integer $pozos
 * @property string $resultado_mpn
 */
class PozosPositivo extends Model {
    use SoftDeletes;

    public $table = 'lc_pozos_positivos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'pozos',
        'resultado_mpn'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pozos' => 'integer',
        'resultado_mpn' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pozos' => 'nullable|integer',
        'resultado_mpn' => 'nullable|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
