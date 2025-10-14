<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Maintenance\Models\mant_history_provider_contract;
use Modules\Maintenance\Models\HistoryBudgetAssignation;
use Modules\Maintenance\Models\AdministrationCostItem;


/**
 * Class ProviderContract
 * @package Modules\Maintenance\Models
 * @version May 28, 2021, 3:44 pm -05
 *
 * @property Modules\Maintenance\Models\MantProvider $mantProviders
 * @property \Illuminate\Database\Eloquent\Collection $mantBudgetAllocationProviders
 * @property \Illuminate\Database\Eloquent\Collection $mantDocumentsProviderContracts
 * @property \Illuminate\Database\Eloquent\Collection $mantImportActivitiesProviderContracts
 * @property \Illuminate\Database\Eloquent\Collection $mantImportSparePartsProviderContracts
 * @property string $object
 * @property string $type_contract
 * @property string $contract_number
 * @property string $start_date
 * @property string $execution_time
 * @property string $closing_date
 * @property integer $mant_providers_id
 */
class ProviderContract extends Model
{
    use SoftDeletes;

    public $table = 'mant_provider_contract';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'object',
        'type_contract',
        'contract_number',
        'start_date',
        'closing_date',
        'mant_providers_id',
        'execution_time',
        'condition',
        'dependencias_id',
        'users_id',
        'manager_dependencia',
        'advance_payment',
        'future_validity',
        'advance_value',
        'vigencia'

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'object' => 'string',
        'type_contract' => 'string',
        'contract_number' => 'string',
        'execution_time' => 'string',
        'mant_providers_id' => 'integer',
        'dependencias_id' => 'integer',
        'users_id ' => 'integer',
        'condition' => 'string',
        'manager_dependencia' => 'string'
    ];

    

    
    protected $appends = [
        'total_value_cdp',
        'total_value_contract',
        'total_value_avaible_cdp',
        'total_percentage',
        'total_executed',
        'history_contract',
        'value_avaible'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * retorna el valor disponible
     * @return 
     */
    public function getValueAvaibleAttribute() {
        $total=0;
        $total=$this->total_value_contract-$this->total_executed;
        return $total;
    }


    /**
     * retorna el historial de contrato los ultimos 5 registros
     * @return 
     */
    public function getHistoryContractAttribute() {
        $total=0;
        $total=HistoryProviderContract::where('contract_number', $this->contract_number)->limit(5)->latest()->get();
        
        return $total;
    }

    /**
     * retorna el total de los cdp disponbibles
     * @return 
     */
    public function getTotalValueAvaibleCdpAttribute() {
        $total=0;
        foreach ($this->mantBudgetAssignation as $budgetAsignation) {
            $total+=$budgetAsignation->cdp_available;
        }        
        return $total;
    }

     /**
     * retorna el total ejecutado
     * @return 
     */
    public function getTotalExecutedAttribute() {
        $total=0;
        $totalItems=0;
        $items=0;
        //Verifica que exista la relacion
        if($this->mantBudgetAssignation){
            //Va recorrer la relacion
            foreach ($this->mantBudgetAssignation as $budgetAsignation) {
                //Verifica que exista relacion de administrar rubros
                if($budgetAsignation->mantAdministrationCostItems){
                    $items=$budgetAsignation->mantAdministrationCostItems;
                    //Se le asigna la variable de todos los rubros y se verifica que exista
                    if( $items){
                        //Se va recorrer cada item de cada rubro
                        foreach ($items as  $item) {
                            if($item){
                                //Se suman las variables en una varible para saber cuanto es el total de lo que se ha gastado
                                $execution=$item->mantBudgetExecutions;
                                foreach ($execution as $exec) {
                                    # code...
                                    $totalItems+=$exec->executed_value;
                                }
                            }
                        }
                    }
                }
            }              
        }
        //Se retorna el valor de lo gastado
        return $totalItems;
    }

    
    /**
     * retorna el porcentaje de los valores gastados
     * @return 
     */
    public function getTotalPercentageAttribute() {
        $total=0;
        $totalItems=0;
        $items=0;
        //Se verifica que existan datos en la relacion
        if($this->mantBudgetAssignation){
            //Se recorre la relacion
            foreach ($this->mantBudgetAssignation as $budgetAsignation) {
                //Se verifica que existan rubros
                if($budgetAsignation->mantAdministrationCostItems){
                    //Se le asignan todos los rubros que existan
                    $items=$budgetAsignation->mantAdministrationCostItems;
                    //Se verifica que existan rubros
                    if( $items){
                        //Se recorre cada rubro
                        foreach ($items as  $item) {
                            //Se verifica que exista cada rubro
                            if($item){
                                //Se recorre la relacion de ejecucion
                                foreach ($item->mantBudgetExecutions as $budgetExecution) {
                                    //Se suman todos los valores ejecutados a una variable
                                    $totalItems+=$budgetExecution->executed_value;
                                }
                            }
                        }
                    }
                }
            }              
        }
        //Se verifica que total item sea diferente de 0 para poder hacer la operacion y guardar todo en total
        if($this->total_value_contract && $totalItems!=0){
            $total=($totalItems/$this->total_value_contract)*100;
        }
        
        return $total;
    }
    
     /**
     * retorna el total de los cdp
     * @return 
     */
    public function getTotalValueCdpAttribute() {
        $total=0;
        foreach ($this->mantBudgetAssignation as $budgetAsignation) {
            $total+=$budgetAsignation->value_cdp;
        }        
        return $total;
    }

         /**
     * retorna el total de los contratos
     * @return 
     */
    public function getTotalValueContractAttribute() {
        $total=0;
        foreach ($this->mantBudgetAssignation as $budgetAsignation) {
            $total+=$budgetAsignation->value_contract;
        }        
        return $total;
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

       /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function providers()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\Providers::class, 'mant_providers_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function budgetAllocationProviders()
    {
        return $this->hasMany(\Modules\Maintenance\Models\BudgetAllocationProvider::class, 'mant_provider_contract_id')->with(["dependencias"]);
    }

         /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantBudgetAssignation() {
        return $this->hasMany(\Modules\Maintenance\Models\BudgetAssignation::class, 'mant_provider_contract_id')->with(["mantAdministrationCostItems", "mantHistoryAdministrationCostItems"]);
    }

          /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantContractNew() {
        return $this->hasMany(\Modules\Maintenance\Models\ContractNew::class, 'mant_provider_contract_id');
    }

             /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantHistoryBudgetAssignation() {
        return $this->hasMany(\Modules\Maintenance\Models\HistoryBudgetAssignation::class, 'mant_provider_contract_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function documentsProviderContracts()
    {
        return $this->hasMany(\Modules\Maintenance\Models\DocumentsProviderContract::class, 'mant_provider_contract_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function importActivitiesProviderContracts()
    {
        return $this->hasMany(\Modules\Maintenance\Models\ImportActivitiesProviderContract::class, 'mant_provider_contract_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function importSparePartsProviderContracts()
    {
        return $this->hasMany(\Modules\Maintenance\Models\ImportSparePartsProviderContract::class, 'mant_provider_contract_id');
    }
}
