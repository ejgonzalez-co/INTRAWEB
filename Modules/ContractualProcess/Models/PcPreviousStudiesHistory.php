<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class PcPreviousStudiesHistory
 * @package Modules\ContractualProcess\Models
 * @version January 20, 2021, 9:57 pm -05
 *
 * @property Modules\ContractualProcess\Models\PcPreviousStudy $pcPreviousStudies
 * @property string $organizational_unit
 * @property string $program
 * @property string $subprogram
 * @property string $project
 * @property string $lineproject
 * @property string $justification_tecnic_description
 * @property string $justification_tecnic_approach
 * @property string $justification_tecnic_modality
 * @property string $fundaments_juridics
 * @property string $imputation_budget_rubro
 * @property string $imputation_budget_interventor
 * @property string $determination_object
 * @property string $determination_value
 * @property string $determination_time_limit
 * @property string $determination_form_pay
 * @property string $obligation_principal
 * @property boolean $obligation_principal_documentation
 * @property boolean $situation_estates_public
 * @property string $situation_estates_public_observation
 * @property boolean $situation_estates_private
 * @property string $situation_estates_private_observation
 * @property boolean $solution_servitude
 * @property string $solution_servitude_observation
 * @property boolean $solution_owner
 * @property string $solution_owner_observation
 * @property string $process_concilation
 * @property boolean $process_licenses_environment
 * @property boolean $process_licenses_beach
 * @property boolean $process_licenses_forestal
 * @property boolean $process_licenses_guadua
 * @property boolean $process_licenses_tree
 * @property boolean $process_licenses_road
 * @property boolean $process_licenses_demolition
 * @property boolean $process_licenses_tree_urban
 * @property string $tipification_danger
 * @property string $indication_danger_precontractual
 * @property string $indication_danger_ejecution
 * @property boolean $state
 * @property string $date_project
 * @property string $type
 * @property integer $pc_previous_studies_id
 */
class PcPreviousStudiesHistory extends Model
{
    use SoftDeletes;

    public $table = 'pc_previous_studies_h';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'organizational_unit',
        'program',
        'subprogram',
        'project',
        'lineproject',
        'justification_tecnic_description',
        'justification_tecnic_approach',
        'justification_tecnic_modality',
        'fundaments_juridics',
        'imputation_budget_rubro',
        'imputation_budget_interventor',
        'determination_object',
        'determination_value',
        'determination_time_limit',
        'determination_form_pay',
        'obligation_principal',
        'obligation_principal_documentation',
        'situation_estates_public',
        'situation_estates_public_observation',
        'situation_estates_private',
        'situation_estates_private_observation',
        'solution_servitude',
        'solution_servitude_observation',
        'solution_owner',
        'solution_owner_observation',
        'process_concilation',
        'process_licenses_environment',
        'process_licenses_beach',
        'process_licenses_forestal',
        'process_licenses_guadua',
        'process_licenses_tree',
        'process_licenses_road',
        'process_licenses_demolition',
        'process_licenses_tree_urban',
        'tipification_danger',
        'indication_danger_precontractual',
        'indication_danger_ejecution',
        'state',
        'date_project',
        'type',
        'pc_previous_studies_id',
        'users_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'organizational_unit' => 'string',
        'program' => 'string',
        'subprogram' => 'string',
        'project' => 'string',
        'lineproject' => 'string',
        'justification_tecnic_description' => 'string',
        'justification_tecnic_approach' => 'string',
        'justification_tecnic_modality' => 'string',
        'fundaments_juridics' => 'string',
        'imputation_budget_rubro' => 'string',
        'imputation_budget_interventor' => 'string',
        'determination_object' => 'string',
        'determination_value' => 'string',
        'determination_time_limit' => 'string',
        'determination_form_pay' => 'string',
        'obligation_principal' => 'string',
        'obligation_principal_documentation' => 'boolean',
        'situation_estates_public' => 'boolean',
        'situation_estates_public_observation' => 'string',
        'situation_estates_private' => 'boolean',
        'situation_estates_private_observation' => 'string',
        'solution_servitude' => 'boolean',
        'solution_servitude_observation' => 'string',
        'solution_owner' => 'boolean',
        'solution_owner_observation' => 'string',
        'process_concilation' => 'string',
        'process_licenses_environment' => 'boolean',
        'process_licenses_beach' => 'boolean',
        'process_licenses_forestal' => 'boolean',
        'process_licenses_guadua' => 'boolean',
        'process_licenses_tree' => 'boolean',
        'process_licenses_road' => 'boolean',
        'process_licenses_demolition' => 'boolean',
        'process_licenses_tree_urban' => 'boolean',
        'tipification_danger' => 'string',
        'indication_danger_precontractual' => 'string',
        'indication_danger_ejecution' => 'string',
        'state' => 'integer',
        'date_project' => 'date',
        'type' => 'string',
        'pc_previous_studies_id' => 'integer'
    ];

    protected $appends = [
        'state_name',
        'state_colour',
    ];
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pc_previous_studies_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcPreviousStudies()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcPreviousStudy::class, 'pc_previous_studies_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcPreviousStudiesTipificationsH()
    {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcPreviousStudiesTipificationHistory::class, 'pc_previous_studies_h_id');
    }

        /**
     * Obtiene el nombre del estado
     * @return
     */
    public function getStateNameAttribute() {
        if (!empty($this->state)) {
            return AppBaseController::getObjectOfList(config('contractual_process.pc_studies_previous'), 'id', $this->state)->name;
        }
    }

    /**
     * Obtiene la clase del estado
     * @return
     */
    public function getStateColourAttribute() {
        if (!empty($this->state)) {
            return AppBaseController::getObjectOfList(config('contractual_process.pc_studies_previous'), 'id', $this->state)->colour;
        }
    }
}
