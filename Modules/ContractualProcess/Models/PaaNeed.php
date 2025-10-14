<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;


/**
 * Class PaaNeed
 * @package Modules\ContractualProcess\Models
 * @version August 4, 2021, 3:01 pm -05
 *
 * @property Modules\ContractualProcess\Models\PcPaaCall $pcPaaCalls
 * @property Modules\ContractualProcess\Models\PcProcessLeader $pcProcessLeaders
 * @property \Illuminate\Database\Eloquent\Collection $pcFunctioningNeeds
 * @property \Illuminate\Database\Eloquent\Collection $pcInvestmentNeeds
 * @property \Illuminate\Database\Eloquent\Collection $pcInvestmentTechnicalSheets
 * @property \Illuminate\Database\Eloquent\Collection $pcInvestmentTechnicalSheetsHistories
 * @property \Illuminate\Database\Eloquent\Collection $pcNoveltiesPaas
 * @property \Illuminate\Database\Eloquent\Collection $pcPaaModificationRequests
 * @property integer $pc_paa_calls_id
 * @property integer $pc_process_leaders_id
 * @property string $name_process
 * @property boolean $state
 * @property number $total_value_paa
 * @property number $total_operating_value
 * @property boolean $future_validity_status
 * @property number $total_investment_value
 */
class PaaNeed extends Model {
    use SoftDeletes;

    public $table = 'pc_needs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $dates = ['deleted_at'];
    
    public $fillable = [
        'pc_paa_calls_id',
        'pc_process_leaders_id',
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
        'state' => 'boolean',
        'total_value_paa' => 'float',
        'total_operating_value' => 'float',
        'future_validity_status' => 'boolean',
        'total_investment_value' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name_process' => 'nullable|string|max:80',
        'state' => 'nullable|boolean',
        'total_value_paa' => 'nullable|numeric',
        'total_operating_value' => 'nullable|numeric',
        'future_validity_status' => 'nullable|boolean',
        'total_investment_value' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function paaCalls() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcPaaCall::class, 'pc_paa_calls_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function processLeaders() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcProcessLeader::class, 'pc_process_leaders_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function functioningNeeds() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcFunctioningNeed::class, 'pc_needs_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function investmentNeeds() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcInvestmentNeed::class, 'pc_needs_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function investmentTechnicalSheets() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet::class, 'pc_needs_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function investmentTechnicalSheetsHistories() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcInvestmentTechnicalSheetsHistory::class, 'pc_needs_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function paaNovelties() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcNoveltiesPaa::class, 'pc_needs_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function paaModificationRequests() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcPaaModificationRequest::class, 'pc_needs_id');
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
}
