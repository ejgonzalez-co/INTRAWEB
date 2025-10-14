<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ButgetExecution
 * @package Modules\Maintenance\Models
 * @version August 12, 2021, 3:45 pm -05
 *
 * @property Modules\Maintenance\Models\MantAdministrationCostItem $mantAdministrationCostItems
 * @property integer $mant_administration_cost_items_id
 * @property string $minutes
 * @property string $date
 * @property number $executed_value
 * @property number $new_value_available
 * @property number $percentage_execution_item
 * @property string $observation
 */
class ButgetExecution extends Model
{
        use SoftDeletes;

    public $table = 'mant_budget_execution';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_administration_cost_items_id',
        'minutes',
        'date',
        'executed_value',
        'new_value_available',
        'percentage_execution_item',
        'observation',
        'name_user',
        'users_id',
        'old_value_available'
    ];

    

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_administration_cost_items_id' => 'integer',
        'minutes' => 'string',
        'date' => 'string',
        'executed_value' => 'float',
        'new_value_available' => 'float',
        'percentage_execution_item' => 'float',
        'observation' => 'string',        
        'users_id' => 'integer',
        'name_user' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'minutes' => 'required|string|max:255',
        'date' => 'required',
        'executed_value' => 'required|numeric',
        'new_value_available' => 'required|numeric',
        'percentage_execution_item' => 'required|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'observation' => 'nullable|string',
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantAdministrationCostItems() {
        return $this->belongsTo(\Modules\Maintenance\Models\AdministrationCostItem::class, 'mant_administration_cost_items_id')->with(["mantBudgetAssignation"]);
    }
            /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
