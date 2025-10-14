<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HistoryCostItem
 * @package Modules\Maintenance\Models
 * @version September 13, 2021, 11:52 am -05
 *
 * @property Modules\Maintenance\Models\MantBudgetAssignation $mantBudgetAssignation
 * @property Modules\Maintenance\Models\User $users
 * @property string $name
 * @property string $name_cost
 * @property string $observation
 * @property string $code_cost
 * @property string $cost_center
 * @property string $cost_center_name
 * @property number $value_item
 * @property string $name_user
 * @property integer $users_id
 * @property integer $mant_budget_assignation_id
 */
class HistoryCostItem extends Model {
    use SoftDeletes;

    public $table = 'mant_history_cost_items';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'name_cost',
        'observation',
        'code_cost',
        'cost_center',
        'cost_center_name',
        'value_item',
        'name_user',
        'users_id',
        'mant_budget_assignation_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'name_cost' => 'string',
        'observation' => 'string',
        'code_cost' => 'string',
        'cost_center' => 'string',
        'cost_center_name' => 'string',
        'value_item' => 'float',
        'name_user' => 'string',
        'users_id' => 'integer',
        'mant_budget_assignation_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'name_cost' => 'nullable|string|max:255',
        'observation' => 'nullable|string',
        'code_cost' => 'nullable|string|max:255',
        'cost_center' => 'nullable|string|max:255',
        'cost_center_name' => 'nullable|string|max:255',
        'value_item' => 'nullable|numeric',
        'name_user' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'users_id' => 'required',
        'mant_budget_assignation_id' => 'required'
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
