<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Maintenance\Models\ProviderContract;
use Modules\Maintenance\Models\AdministrationCostItem;
use Modules\Maintenance\Models\HistoryBudgetAssignation;
use Modules\Maintenance\Models\HistoryCostItem;
use Modules\Maintenance\Models\ContractNews;
use DateTimeInterface;

/**
 * Class BudgetAssignation
 * @package Modules\Maintenance\Models
 * @version August 12, 2021, 3:45 pm -05
 *
 * @property Modules\Maintenance\Models\MantProviderContract $mantProviderContract
 * @property \Illuminate\Database\Eloquent\Collection $mantAdministrationCostItems
 * @property integer $mant_provider_contract_id
 * @property number $value_cdp
 * @property string $consecutive_cdp
 * @property number $value_contract
 * @property number $cdp_available
 * @property string $observation
 */
class BudgetAssignation extends Model
{
        use SoftDeletes;

    public $table = 'mant_budget_assignation';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_provider_contract_id',
        'value_cdp',
        'consecutive_cdp',
        'value_contract',
        'cdp_available',
        'observation',
        'name_user',
        'users_id',
        'attachment',
        'old_value'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_provider_contract_id' => 'integer',
        'value_cdp' => 'float',
        'consecutive_cdp' => 'string',
        'value_contract' => 'float',
        'cdp_available' => 'float',
        'observation' => 'string',        
        'users_id' => 'integer',
        'name_user' => 'string',
        'attachment' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'value_cdp' => 'required|numeric',
        'consecutive_cdp' => 'required|string|max:255',
        'value_contract' => 'required|numeric',
        'cdp_available' => 'required|numeric',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
       
    ];


    protected $appends = [
        'history_assignation'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    //Retorna el historial de cada asignacion presupuestal
    public function getHistoryAssignationAttribute(){
        $total=[];
        $total=HistoryBudgetAssignation::where('mant_provider_contract_id', $this->mant_provider_contract_id)->limit(5)->latest()->get();       
        return $total;
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantProviderContract() {
        return $this->belongsTo(ProviderContract::class, 'mant_provider_contract_id')->with(["mantContractNew"]);
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantAdministrationCostItems() {
        return $this->hasMany(AdministrationCostItem::class, 'mant_budget_assignation_id')->with(["mantBudgetExecutions"]);
    }
    
        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantHistoryAdministrationCostItems() {
        return $this->hasMany(HistoryCostItem::class, 'mant_budget_assignation_id');
    }



            /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
