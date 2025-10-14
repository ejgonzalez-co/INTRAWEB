<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PaaVersion
 * @package Modules\ContractualProcess\Models
 * @version July 22, 2021, 9:54 am -05
 *
 * @property Modules\ContractualProcess\Models\Dependencia $dependencias
 * @property Modules\ContractualProcess\Models\PcPaaCall $pcPaaCalls
 * @property integer $pc_paa_calls_id
 * @property integer $dependencias_id
 * @property number $version_number
 * @property string $type_need
 * @property string $description
 * @property number $total_value
 */
class PaaVersion extends Model
{
        use SoftDeletes;

    public $table = 'pc_paa_versions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'pc_paa_calls_id',
        'dependencias_id',
        'version_number',
        'type_need',
        'description',
        'total_value'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_paa_calls_id' => 'integer',
        'dependencias_id' => 'integer',
        'version_number' => 'float',
        'type_need' => 'string',
        'description' => 'string',
        'total_value' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pc_paa_calls_id' => 'required',
        'dependencias_id' => 'required',
        'version_number' => 'nullable|numeric',
        'type_need' => 'nullable|string|max:120',
        'description' => 'nullable|string',
        'total_value' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\Dependencia::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcPaaCalls() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcPaaCall::class, 'pc_paa_calls_id');
    }
}
