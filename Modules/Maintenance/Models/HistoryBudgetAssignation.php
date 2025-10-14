<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HistoryBudgetAssignation
 * @package Modules\Maintenance\Models
 * @version September 13, 2021, 4:47 pm -05
 *
 * @property Modules\Maintenance\Models\MantProviderContract $mantProviderContract
 * @property Modules\Maintenance\Models\User $users
 * @property string $name
 * @property string $observation
 * @property string $name_user
 * @property string $value_cdp
 * @property string $value_contract
 * @property string $cdp_avaible
 * @property integer $users_id
 * @property integer $mant_provider_contract_id
 */
class HistoryBudgetAssignation extends Model {
    use SoftDeletes;

    public $table = 'mant_history_budget_assignation';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'id',
        'mant_provider_contract_id',
        'users_id',
        'name',
        'name_user',
        'observation',
        'consecutive',
        'consecutive_cdp',
        'value_cdp',
        'value_contract',
        'cdp_avaible',
        'novelty_type',
        'name',
        'observation',
        'name_user',
        'value_cdp',
        'value_contract',
        'cdp_avaible',
        'mant_budget_assignation_id',
        'users_id',
        'date_modification',
        'time_extension',
        'date_contract_term',
        'type_suspension',
        'date_start_suspension',
        'time_suspension',
        'date_act_suspension',
        'date_end_suspension',
        'date_last_suspension',
        'number_cdp'
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'observation' => 'string',
        'name_user' => 'string',
        'value_cdp' => 'string',
        'value_contract' => 'string',
        'cdp_avaible' => 'string',
        'users_id' => 'integer',
        'mant_provider_contract_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'observation' => 'nullable|string',
        'name_user' => 'nullable|string|max:255',
        'value_cdp' => 'nullable|string|max:255',
        'value_contract' => 'nullable|string|max:255',
        'cdp_avaible' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'users_id' => 'required',
        'mant_provider_contract_id' => 'required|integer'
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
        return $this->belongsTo(\Modules\Maintenance\Models\User::class, 'users_id');
    }
}
