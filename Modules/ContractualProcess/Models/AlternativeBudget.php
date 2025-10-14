<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AlternativeBudget
 * @package Modules\ContractualProcess\Models
 * @version March 5, 2021, 9:13 am -05
 *
 * @property Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet $pcInvestmentTechnicalSheets
 * @property \Illuminate\Database\Eloquent\Collection $pcBudgets
 * @property \Illuminate\Database\Eloquent\Collection $pcBudgetTypesCosts
 * @property integer $pc_investment_technical_sheets_id
 * @property number $total_direct_cost
 * @property number $total_direct_aqueduct
 * @property number $total_direct_percentage_aqueduct
 * @property number $total_direct_sewerage
 * @property number $total_direct_percentage_sewerage
 * @property number $total_direct_cleanliness
 * @property number $total_direct_percentage_cleanliness
 * @property number $total_project_cost
 * @property number $total_project_aqueduct
 * @property number $total_project_percentage_aqueduct
 * @property number $total_project_sewerage
 * @property number $total_project_percentage_sewerage
 * @property number $total_project_cleanliness
 * @property number $total_project_percentage_cleanliness
 */
class AlternativeBudget extends Model
{
    use SoftDeletes;

    public $table = 'pc_alternative_budget';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'pc_investment_technical_sheets_id',
        'total_direct_cost',
        'total_direct_aqueduct',
        'total_direct_percentage_aqueduct',
        'total_direct_sewerage',
        'total_direct_percentage_sewerage',
        'total_direct_cleanliness',
        'total_direct_percentage_cleanliness',
        'total_project_cost',
        'total_project_aqueduct',
        'total_project_percentage_aqueduct',
        'total_project_sewerage',
        'total_project_percentage_sewerage',
        'total_project_cleanliness',
        'total_project_percentage_cleanliness'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_investment_technical_sheets_id' => 'integer',
        'total_direct_cost' => 'float',
        'total_direct_aqueduct' => 'float',
        'total_direct_percentage_aqueduct' => 'float',
        'total_direct_sewerage' => 'float',
        'total_direct_percentage_sewerage' => 'float',
        'total_direct_cleanliness' => 'float',
        'total_direct_percentage_cleanliness' => 'float',
        'total_project_cost' => 'float',
        'total_project_aqueduct' => 'float',
        'total_project_percentage_aqueduct' => 'float',
        'total_project_sewerage' => 'float',
        'total_project_percentage_sewerage' => 'float',
        'total_project_cleanliness' => 'float',
        'total_project_percentage_cleanliness' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pc_investment_technical_sheets_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function investmentTechnicalSheets() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\InvestmentTechnicalSheet::class, 'pc_investment_technical_sheets_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function budgets() {
        return $this->hasMany(\Modules\ContractualProcess\Models\Budget::class, 'pc_alternative_budget_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function budgetTypesCosts() {
        return $this->hasMany(\Modules\ContractualProcess\Models\BudgetTypeCost::class, 'pc_alternative_budget_id');
    }
}
