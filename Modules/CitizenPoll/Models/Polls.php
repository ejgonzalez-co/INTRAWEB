<?php

namespace Modules\CitizenPoll\Models;

use App\Http\Controllers\AppBaseController;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class Polls
 * @package Modules\CitizenPoll\Models
 * @version April 30, 2021, 4:07 pm -05
 *
 * @property string $name
 * @property string $gender
 * @property string $email
 * @property string $direction_state
 * @property string $phone
 * @property string $suscriber_quality
 * @property string $aqueduct
 * @property string $sewerage
 * @property string $cleanliness
 * @property integer $attention_qualification_respect
 * @property integer $attention_solve_problems
 * @property integer $qualification_waiting_time
 * @property integer $time_solution_petition
 * @property integer $chance
 * @property integer $reports_effectiveness
 * @property integer $aqueduct_benefit_service
 * @property integer $aqueduct_continuity_service
 * @property integer $sewerage_benefit_service
 * @property integer $cleanliness_benefit_service
 * @property integer $cleanliness_qualification_service
 * @property string $number_account
 */
class Polls extends Model
{
    use SoftDeletes;

    public $table = 'cp_polls';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'gender',
        'email',
        'direction_state',
        'phone',
        'damage_report',
        'fixed_damage',
        'suscriber_quality',
        'aqueduct',
        'they_arrived_time',
        'sewerage',
        'cleanliness',
        // 'attention_qualification_respect',
        // 'attention_solve_problems',
        // 'qualification_waiting_time',
        // 'time_solution_petition',
        'chance',
        'reports_effectiveness',
        'aqueduct_benefit_service',
        'aqueduct_continuity_service',
        // 'sewerage_benefit_service',
        // 'cleanliness_benefit_service',
        // 'cleanliness_qualification_service',
        'number_account'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'gender' => 'string',
        'damage_report' => 'string',
        'email' => 'string',
        'fixed_damage' => 'string',
        'direction_state' => 'string',
        'phone' => 'string',
        'they_arrived_time' => 'string',
        'suscriber_quality' => 'string',
        'aqueduct' => 'string',
        'sewerage' => 'string',
        'cleanliness' => 'string',
        // 'attention_qualification_respect' => 'integer',
        // 'attention_solve_problems' => 'integer',
        // 'qualification_waiting_time' => 'integer',
        // 'time_solution_petition' => 'integer',
        'chance' => 'integer',
        'reports_effectiveness' => 'integer',
        'aqueduct_benefit_service' => 'integer',
        'aqueduct_continuity_service' => 'integer',
        // 'sewerage_benefit_service' => 'integer',
        // 'cleanliness_benefit_service' => 'integer',
        // 'cleanliness_qualification_service' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:256',
        'gender' => 'nullable|string|max:20',
        'email' => 'nullable|string|max:256',
        'direction_state' => 'nullable|string|max:256',
        'phone' => 'nullable|string|max:20',
        'suscriber_quality' => 'nullable|string|max:30',
        'aqueduct' => 'nullable|string|max:3',
        'sewerage' => 'nullable|string|max:3',
        'cleanliness' => 'nullable|string|max:3',
        'chance' => 'nullable',
        'reports_effectiveness' => 'nullable',
        'aqueduct_benefit_service' => 'nullable',
        'aqueduct_continuity_service' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'number_account' => 'nullable|string|max:10'
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

    protected $appends = [
        'aqueduct_benefit_service_name','aqueduct_continuity_service_name','chance_name','reports_effectiveness_name','arrived_on_time_name','damage_well_fixed_name'
        
    ];

    /**
     * Obtiene la calificacion de reporte de daños si llegaron a tiempo
     * @return
     */
    public function getDamageWellFixedNameAttribute() {
        if (!empty($this->fixed_damage)) {
            return AppBaseController::getObjectOfList(config('citizen_poll.star_calification'), 'id', $this->fixed_damage)->name;
        }
    }


    /**
     * Obtiene la calificacion de reporte de daños si llegaron a tiempo
     * @return
     */
    public function getArrivedOnTimeNameAttribute() {
        if (!empty($this->they_arrived_time)) {
            return AppBaseController::getObjectOfList(config('citizen_poll.star_calification'), 'id', $this->they_arrived_time)->name;
        }
    }

    /**
     * Obtiene la calificacion sobre la prestacion del servicio de acueducto
     * @return
     */
    public function getAqueductBenefitServiceNameAttribute() {
        if (!empty($this->aqueduct_benefit_service)) {
            return AppBaseController::getObjectOfList(config('citizen_poll.star_calification'), 'id', $this->aqueduct_benefit_service)->name;
        }
    }

    /**
     * Obtiene la calificacion sobre la continuidad en la prestacion del servicio de acueducto
     * @return
     */
    public function getAqueductContinuityServiceNameAttribute() {
        if (!empty($this->aqueduct_continuity_service)) {
            return AppBaseController::getObjectOfList(config('citizen_poll.star_calification'), 'id', $this->aqueduct_continuity_service)->name;
        }
    }
    

    /**
     * Obtiene la calificacion sobre si el personal llego a tiempo para atender la consulta
     * @return
     */
    public function getChanceNameAttribute() {
        if (!empty($this->chance)) {
            return AppBaseController::getObjectOfList(config('citizen_poll.star_calification'), 'id', $this->chance)->name;
        }
    }

    /**
     * Obtiene la calificacion sobre la efectividad del personal a la hora de arreglar el daño
     * @return
     */
    public function getReportsEffectivenessNameAttribute() {
        if (!empty($this->reports_effectiveness)) {
            return AppBaseController::getObjectOfList(config('citizen_poll.star_calification'), 'id', $this->reports_effectiveness)->name;
        }
    }

}