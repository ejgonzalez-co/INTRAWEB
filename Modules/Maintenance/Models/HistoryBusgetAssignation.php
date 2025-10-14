<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HistoryBusgetAssignation
 * @package Modules\Maintenance\Models
 * @version September 9, 2021, 3:18 pm -05
 *
 * @property Modules\Maintenance\Models\MantBudgetAssignation $mantBudgetAssignation
 * @property Modules\Maintenance\Models\User $users
 * @property string $name
 * @property string $observation
 * @property string $name_user
 * @property string $value_cdp
 * @property string $value_contract
 * @property string $cdp_avaible
 * @property integer $mant_budget_assignation_id
 * @property integer $users_id
 */
class HistoryBusgetAssignation extends Model {
    use SoftDeletes;

    public $table = 'mant_history_budget_assignation';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
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
        'date_last_suspension'


        
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
        'mant_budget_assignation_id' => 'integer',
        'users_id' => 'integer'
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
        'mant_budget_assignation_id' => 'required',
        'users_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantBudgetAssignation() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantBudgetAssignation::class, 'mant_budget_assignation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Maintenance\Models\User::class, 'users_id');
    }
}
