<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PozosMicro
 * @package Modules\leca\Models
 * @version November 30, 2022, 5:32 pm -05
 *
 * @property integer $p_grandes
 * @property integer $p_pequeños
 * @property number $resultado
 */
class PozosMicro extends Model {
    use SoftDeletes;

    public $table = 'lc_po_grandes_po_peque';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'p_grandes',
        'p_pequeños',
        'resultado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'p_grandes' => 'integer',
        'p_pequeños' => 'integer',
        'resultado' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'p_grandes' => 'nullable|integer',
        'p_pequeños' => 'nullable|integer',
        'resultado' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
