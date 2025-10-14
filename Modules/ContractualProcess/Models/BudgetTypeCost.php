<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class BudgetTypeCost
 * @package Modules\ContractualProcess\Models
 * @version March 5, 2021, 9:16 am -05
 *
 * @property Modules\ContractualProcess\Models\PcAlternativeBudget $pcAlternativeBudget
 * @property integer $pc_alternative_budget_id
 * @property number $cost_type
 * @property number $total_value
 * @property number $aqueduct
 * @property number $percentage_aqueduct
 * @property number $sewerage
 * @property number $percentage_sewerage
 * @property number $cleanliness
 * @property number $percentage_cleanliness
 */
class BudgetTypeCost extends Model
{
    use SoftDeletes;

    public $table = 'pc_budget_types_costs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'pc_alternative_budget_id',
        'cost_type',
        'total_value',
        'aqueduct',
        'percentage_aqueduct',
        'sewerage',
        'percentage_sewerage',
        'cleanliness',
        'percentage_cleanliness'
    ];

    
    protected $appends = [
        'cost_type_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_alternative_budget_id' => 'integer',
        'cost_type' => 'integer',
        'total_value' => 'float',
        'aqueduct' => 'float',
        'percentage_aqueduct' => 'float',
        'sewerage' => 'float',
        'percentage_sewerage' => 'float',
        'cleanliness' => 'float',
        'percentage_cleanliness' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pc_alternative_budget_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function alternativeBudgets()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\alternativeBudget::class, 'pc_alternative_budget_id');
    }

    /**
     * Obtiene el nombre del tipo de costo
     * @return 
     */
    public function getCostTypeNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.cost_type'), 'id', $this->cost_type)->name;   
    }
}
