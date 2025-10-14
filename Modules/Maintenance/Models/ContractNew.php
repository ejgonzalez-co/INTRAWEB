<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ContractNew
 * @package Modules\Maintenance\Models
 * @version August 11, 2021, 3:42 pm -05
 *
 * @property Modules\Maintenance\Models\MantProviderContract $mantProviderContract
 * @property integer $mant_provider_contract_id
 * @property string $type_new
 * @property string $date_new
 * @property string $observation
 * @property number $cdp_modify
 */
class ContractNew extends Model
{
        use SoftDeletes;

    public $table = 'mant_contract_news';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_provider_contract_id',
        'type_new',
        'date_new',
        'observation',
        'cdp_modify',
        'contract_modify',
        'name_user',
        'users_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_provider_contract_id' => 'integer',
        'type_new' => 'string',
        'date_new' => 'date',
        'observation' => 'string',
        'cdp_modify' => 'float',       
        'contract_modify' => 'float',   
        'users_id' => 'integer',
        'name_user' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_provider_contract_id' => 'required|integer',
        'type_new' => 'nullable|string|max:255',
        'date_new' => 'nullable',
        'observation' => 'nullable|string',
        'cdp_modify' => 'nullable|numeric',
        'contract_modify' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantProviderContract() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantProviderContract::class, 'mant_provider_contract_id');
    }
            /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
