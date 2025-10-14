<?php

namespace Modules\Configuracion\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Variables
 * @package Modules\Configuracion\Models
 * @version December 22, 2021, 10:33 am -05
 *
 * @property string $name
 * @property string $value
 * @property string $type
 * @property string $description
 */
class Variables extends Model
{
        use SoftDeletes;

    public $table = 'intranet_variables';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'value',
        'type',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'value' => 'string',
        'type' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'value' => 'nullable|string|max:255',
        'type' => 'nullable|string|max:255',
        'description' => 'nullable|string'
    ];

    
}
