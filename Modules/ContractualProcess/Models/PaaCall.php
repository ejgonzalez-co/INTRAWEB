<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class PaaCall
 * @package Modules\ContractualProcess\Models
 * @version July 6, 2021, 9:18 am -05
 *
 * @property Modules\ContractualProcess\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $pcNeeds
 * @property integer $users_id
 * @property string $validity
 * @property string $name
 * @property string|\Carbon\Carbon $start_date
 * @property string|\Carbon\Carbon $closing_alert_date
 * @property string|\Carbon\Carbon $closing_date
 * @property string $observation_message
 * @property integer $state
 */
class PaaCall extends Model
{
        use SoftDeletes;

    public $table = 'pc_paa_calls';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'validity',
        'name',
        'start_date',
        'closing_alert_date',
        'closing_date',
        'attached',
        'observation_message',
        'state'
    ];

    protected $appends = [
        'state_name',
        'state_colour',
        'in_range_date',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'validity' => 'string',
        'name' => 'string',
        'start_date' => 'datetime',
        'closing_alert_date' => 'datetime',
        'closing_date' => 'datetime',
        'observation_message' => 'string',
        'state' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'validity' => 'required|string|max:4',
        'name' => 'required|string|max:100',
        'start_date' => 'nullable',
        'closing_alert_date' => 'nullable',
        'closing_date' => 'nullable',
        'observation_message' => 'nullable|string',
        'state' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function needs() {
        return $this->hasMany(\Modules\ContractualProcess\Models\Need::class, 'pc_paa_calls_id');
    }

    /**
     * Obtiene el nombre del estado de la convocatoria
     * @return 
     */
    public function getStateNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.pc_paa_calls_status'), 'id', $this->state)->name;
        
    }

    /**
     * Obtiene el color del estado de la convocatoria
     * @return 
     */
    public function getStateColourAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.pc_paa_calls_status'), 'id', $this->state)->colour;
        
    }

    /**
     * Valida si la fecha esta en el rango de la convocatoria
     * @return 
     */
    public function getInRangeDateAttribute() {

        //obtiene fechas de modelo
        $startDate = strtotime(date("Y-m-d", strtotime($this->start_date)));
        $closingDate = strtotime(date("Y-m-d", strtotime($this->closing_date)));

        $dateNow = strtotime(date("Y-m-d"));
            
        $inRange = false;
        //valida con la fecha de hoy
        if ($dateNow >= $startDate && $dateNow <= $closingDate) {
            $inRange = true;
        }
        return $inRange;        
    }
}
