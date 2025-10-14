<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PaaModificationRequestEvaluation
 * @package Modules\ContractualProcess\Models
 * @version July 14, 2021, 11:36 am -05
 *
 * @property Modules\ContractualProcess\Models\User $users
 * @property Modules\ContractualProcess\Models\PcPaaModificationRequest $pcPaaModificationRequests
 * @property integer $pc_paa_modification_requests_id
 * @property integer $users_id
 * @property string $user_name
 * @property string $description
 * @property string $attached
 * @property integer $state
 */
class PaaModificationRequestEvaluation extends Model
{
        use SoftDeletes;

    public $table = 'pc_paa_modification_request_evaluations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'pc_paa_modification_requests_id',
        'users_id',
        'user_name',
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
        'pc_paa_modification_requests_id' => 'integer',
        'users_id' => 'integer',
        'user_name' => 'string',
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
        'pc_paa_modification_requests_id' => 'required',
        'users_id' => 'required',
        'user_name' => 'nullable|string|max:120',
        'description' => 'nullable|string',
        'attached' => 'nullable|string',
        'state' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function paaModificationRequests() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PaaModificationRequest::class, 'pc_paa_modification_requests_id');
    }
}
