<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PatronGeneral
 * @package Modules\Leca\Models
 * @version May 3, 2022, 9:50 am -05
 *
 * @property string $tipo
 * @property integer $periodicidad
 * @property time $hora_inicio
 * @property time $hora_final
 */
class PatronGeneral extends Model {
    use SoftDeletes;

    public $table = 'lc_patron_general';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'tipo',
        'periodicidad',
        'hora_inicio',
        'hora_final'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tipo' => 'string',
        'periodicidad' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tipo' => 'nullable|string|max:120',
        'periodicidad' => 'nullable|integer',
        'hora_inicio' => 'nullable',
        'hora_final' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
