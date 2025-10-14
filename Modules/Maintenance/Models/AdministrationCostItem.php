<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Maintenance\Models\HistoryCostItem;
/**
 * Class AdministrationCostItem
 * @package Modules\Maintenance\Models
 * @version August 12, 2021, 3:45 pm -05
 *
 * @property Modules\Maintenance\Models\MantBudgetAssignation $mantBudgetAssignation
 * @property Modules\Maintenance\Models\MantCenterCost $mantCenterCost
 * @property Modules\Maintenance\Models\MantHeading $mantHeading
 * @property \Illuminate\Database\Eloquent\Collection $mantBudgetExecutions
 * @property string $name
 * @property string $code_cost
 * @property string $cost_center_name
 * @property string $cost_center
 * @property number $value_item
 * @property string $observation
 * @property integer $mant_heading_id
 * @property integer $mant_center_cost_id
 * @property integer $mant_budget_assignation_id
 */
class AdministrationCostItem extends Model
{
        use SoftDeletes;

    public $table = 'mant_administration_cost_items';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'code_cost',
        'cost_center_name',
        'cost_center',
        'value_item',
        'observation',
        'mant_heading_id',
        'mant_center_cost_id',
        'mant_budget_assignation_id',
        'name_user',
        'users_id',
        'value_eddition',
        'value_item_old'
    ];

    protected $appends = [
        'total_value_executed',
        'total_percentage_executed',
        'last_executed_value',
        'value_avaible',
        'history_item'
    ];


    public function getHistoryItemAttribute(){
        $total=[];
        $total=HistoryCostItem::where('code_cost', $this->code_cost)->where('mant_budget_assignation_id',  $this->mant_budget_assignation_id)->limit(5)->latest()->get();       
        return $total;
    }
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'code_cost' => 'string',
        'cost_center_name' => 'string',
        'cost_center' => 'string',
        'value_item' => 'float',
        'observation' => 'string',
        'mant_heading_id' => 'integer',
        'mant_center_cost_id' => 'integer',
        'mant_budget_assignation_id' => 'integer',        
        'users_id' => 'integer',
        'name_user' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'code_cost' => 'nullable|string|max:255',
        'cost_center_name' => 'nullable|string|max:255',
        'cost_center' => 'nullable|string|max:255',
        'value_item' => 'nullable|numeric',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        
        
    ];

    
        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantBudgetAssignation() {
        return $this->belongsTo(\Modules\Maintenance\Models\BudgetAssignation::class, 'mant_budget_assignation_id')->with(["mantProviderContract"]);
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantCenterCost() {
        return $this->belongsTo(\Modules\Maintenance\Models\CenterCost::class, 'mant_center_cost_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantHeading() {
        return $this->belongsTo(\Modules\Maintenance\Models\Heading::class, 'mant_heading_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantBudgetExecutions() {
        return $this->hasMany(\Modules\Maintenance\Models\ButgetExecution::class, 'mant_administration_cost_items_id')->latest();
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }

    /**
     * Obtiene el valor total ejecutado
     * @return 
     */
    public function getTotalValueExecutedAttribute() {
        $total=0;
        if ($this->mantBudgetExecutions) {  
        foreach ($this->mantBudgetExecutions as $budgetExecution) {
            if($budgetExecution){
                $total+=$budgetExecution->executed_value;
            }
           
        }
    }
        return $total;
    }

      
    //Envia el valor disponible

    public function getValueAvaibleAttribute(){
        $total=$this->value_item-$this->total_value_executed;
        
        return $total;
    }

    /**
     * Obtiene el valor total del porcentaje
     * @return 
     */
    public function gettotalPercentageExecutedAttribute() {
        $total=0;
        $totalPercentage=0;
        if ($this->mantBudgetExecutions) {  
        foreach ($this->mantBudgetExecutions as $budgetExecution) {
            $total+=$budgetExecution->executed_value;
        }
        if($total != 0 && $this->value_item !=0){
            $totalPercentage=($total/$this->value_item)*100;
        }
        
        }
        
        return $totalPercentage;
    }

     /**
     * Obtiene el valor total del porcentaje
     * @return 
     */
    public function getLastExecutedValueAttribute() {
        $total=0;
        //Se le asigna  item el ultimo objeto de la relacion
        $item=$this->mantBudgetExecutions->first();
        //Valida que si tenga datos la relacion
        if ($item) {  
            //Se le asigna al total el valor que del ultimo item  
        $total =$this->value_item - $this->total_value_executed ;

            // $total=$item->new_value_available;
        }
        else{
            $total = $this->value_item;
        }
        return $total;
    }
}
