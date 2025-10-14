<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PaaModificationRequest
 * @package Modules\ContractualProcess\Models
 * @version July 14, 2021, 11:29 am -05
 *
 * @property Modules\ContractualProcess\Models\PcNeed $pcNeeds
 * @property \Illuminate\Database\Eloquent\Collection $pcPaaModificationRequestEvaluations
 * @property integer $pc_needs_id
 * @property string $description
 * @property string $attached
 * @property integer $state
 */
class PaaModificationRequest extends Model
{
        use SoftDeletes;

    public $table = 'pc_paa_modification_requests';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'pc_needs_id',
        'description',
        'attached',
        'state'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_needs_id' => 'integer',
        'description' => 'string',
        'attached' => 'string',
        'state' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pc_needs_id' => 'required',
        'description' => 'nullable|string',
        'attached' => 'nullable|string',
        'state' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function needs() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\Need::class, 'pc_needs_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function paaModificationRequestEvaluations() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PaaModificationRequestEvaluation::class, 'pc_paa_modification_requests_id');
    }
}
