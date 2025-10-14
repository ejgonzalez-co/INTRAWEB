<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class FunctioningNeed
 * @package Modules\ContractualProcess\Models
 * @version June 18, 2021, 9:11 am -05
 *
 * @property Modules\ContractualProcess\Models\PcNeed $pcNeeds
 * @property integer $pc_needs_id
 * @property string $description
 * @property string|\Carbon\Carbon $estimated_start_date
 * @property string $selection_mode
 * @property number $estimated_total_value
 * @property number $estimated_value_current_validity
 * @property string $additions
 * @property number $total_value
 * @property integer $future_validity_status
 * @property string $observation
 */
class FunctioningNeed extends Model
{
        use SoftDeletes;

    public $table = 'pc_functioning_needs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'pc_needs_id',
        'description',
        'estimated_start_date',
        'selection_mode',
        'estimated_total_value',
        'estimated_value_current_validity',
        'additions',
        'total_value',
        'future_validity_status',
        'observation',
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
        'pc_needs_id' => 'integer',
        'description' => 'string',
        'estimated_start_date' => 'datetime',
        'selection_mode' => 'string',
        'estimated_total_value' => 'float',
        'estimated_value_current_validity' => 'float',
        'additions' => 'string',
        'total_value' => 'float',
        'future_validity_status' => 'integer',
        'observation' => 'string',
        'state' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'description' => 'nullable|string',
        'estimated_start_date' => 'nullable',
        'selection_mode' => 'nullable|string|max:45',
        'estimated_total_value' => 'nullable|numeric',
        'estimated_value_current_validity' => 'nullable|numeric',
        'additions' => 'nullable|string|max:45',
        'total_value' => 'nullable|numeric',
        'future_validity_status' => 'nullable|integer',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function needs() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\Need::class, 'pc_needs_id');
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
