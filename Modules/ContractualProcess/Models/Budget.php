<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class Budget
 * @package Modules\ContractualProcess\Models
 * @version March 5, 2021, 9:15 am -05
 *
 * @property Modules\ContractualProcess\Models\PcAlternativeBudget $pcAlternativeBudget
 * @property integer $pc_alternative_budget_id
 * @property string $description
 * @property string $unit
 * @property number $quantity
 * @property number $unit_value
 * @property number $total_value
 * @property number $aqueduct
 * @property number $percentage_aqueduct
 * @property number $sewerage
 * @property number $percentage_sewerage
 * @property number $cleanliness
 * @property number $percentage_cleanliness
 */
class Budget extends Model
{
    use SoftDeletes;

    public $table = 'pc_budget';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'pc_alternative_budget_id',
        'description',
        'unit',
        'quantity',
        'unit_value',
        'total_value',
        'aqueduct',
        'percentage_aqueduct',
        'sewerage',
        'percentage_sewerage',
        'cleanliness',
        'percentage_cleanliness',
        'state'
    ];

    protected $appends = [
        'state_name',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_alternative_budget_id' => 'integer',
        'description' => 'string',
        'unit' => 'string',
        'quantity' => 'float',
        'unit_value' => 'float',
        'total_value' => 'float',
        'aqueduct' => 'float',
        'percentage_aqueduct' => 'float',
        'sewerage' => 'float',
        'percentage_sewerage' => 'float',
        'cleanliness' => 'float',
        'percentage_cleanliness' => 'float',
        'state' => 'integer'
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
    public function alternativeBudgets() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\AlternativeBudget::class, 'pc_alternative_budget_id');
    }

    /**
     * Obtiene el nombre del estado
     * @return 
     */
    public function getStateNameAttribute() {
        if ($this->state) {
            return AppBaseController::getObjectOfList(config('contractual_process.paa_needs_status'), 'id', $this->state)->name;
        }
    }
}
