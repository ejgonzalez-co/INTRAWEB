<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class Need
 * @package Modules\ContractualProcess\Models
 * @version June 18, 2021, 8:50 am -05
 *
 * @property Modules\ContractualProcess\Models\PcPaaCall $pcPaaCalls
 * @property Modules\ContractualProcess\Models\PcProcessLeader $pcProcessLeaders
 * @property \Illuminate\Database\Eloquent\Collection $pcFunctioningNeeds
 * @property \Illuminate\Database\Eloquent\Collection $pcInvestmentTechnicalSheets
 * @property integer $pc_paa_calls_id
 * @property integer $pc_process_leaders_id
 * @property string $name_process
 * @property integer $state
 * @property number $total_value_paa
 * @property number $total_operating_value
 * @property integer $future_validity_status
 * @property number $total_investment_value
 */
class Need extends Model
{
    use SoftDeletes;

    public $table = 'pc_needs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];
    
    public $fillable = [
        'pc_paa_calls_id',
        'pc_process_leaders_id',
        'assigned_user_id',
        'name_process',
        'state',
        'total_value_paa',
        'total_operating_value',
        'future_validity_status',
        'total_investment_value'
    ];

    protected $appends = [
        'state_name',
        'state_colour',
        'in_range_date',
        // 'total_value'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_paa_calls_id' => 'integer',
        'pc_process_leaders_id' => 'integer',
        'name_process' => 'string',
        'state' => 'integer',
        'total_value_paa' => 'float',
        'total_operating_value' => 'float',
        'future_validity_status' => 'integer',
        'total_investment_value' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name_process' => 'nullable|string|max:80',
        'state' => 'nullable|integer',
        'total_value_paa' => 'nullable|numeric',
        'total_operating_value' => 'nullable|numeric',
        'future_validity_status' => 'nullable|integer',
        'total_investment_value' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function paaCalls() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PaaCall::class, 'pc_paa_calls_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function processLeaders() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\ProcessLeaders::class, 'pc_process_leaders_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function functioningNeeds() {
        return $this->hasMany(\Modules\ContractualProcess\Models\FunctioningNeed::class, 'pc_needs_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function investmentTechnicalSheets() {
        return $this->hasMany(\Modules\ContractualProcess\Models\InvestmentTechnicalSheet::class, 'pc_needs_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function paaModificationRequest() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PaaModificationRequest::class, 'pc_needs_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function noveltiesPaa() {
        return $this->hasMany(\Modules\ContractualProcess\Models\NoveltiesPaa::class, 'pc_needs_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function paaProcessAttachments() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PaaProcessAttachment::class, 'pc_needs_id');
    }

    /**
     * Obtiene el nombre del estado de la necesidad
     * @return 
     */
    public function getStateNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.pc_needs_status'), 'id', $this->state)->name;
    }

    /**
     * Obtiene el color del estado de la convocanecesidadtoria
     * @return 
     */
    public function getStateColourAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.pc_needs_status'), 'id', $this->state)->colour;
        
    }

    /**
     * Valida si la fecha esta en el rango de la convocatoria
     * @return 
     */
    public function getInRangeDateAttribute() {

        $inRange = false;

        //obtiene fechas de modelo
        if($this->paaCalls){
            $startDate = strtotime(date("Y-m-d", strtotime($this->paaCalls->start_date)));

            $closingDate = strtotime(date("Y-m-d", strtotime($this->paaCalls->closing_date)));

            $dateNow = strtotime(date("Y-m-d"));
            
            //valida con la fecha de hoy
            if ($dateNow >= $startDate && $dateNow <= $closingDate) {
                $inRange = true;
            }
        }
        return $inRange;        
    }

    /**
     * Obtiene valor total del plan
     * @return 
     */
    // public function getTotalValueAttribute() {

    //     if ($this->investmentTechnicalSheets) {

    //         foreach ($this->investmentTechnicalSheets as $investmentTechnical) {
    //             $item = $investmentTechnical->alternativeBudgets()->budgets;
    //         }
    //     } else {
    //         $item = 0;
    //     }


    //     // return 0;
    //     return $item;
        
    // }
}
