<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class PcPreviousStudies
 * @package Modules\ContractualProcess\Models
 * @version January 9, 2021, 11:47 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $pcPreviousStudiesTipifications
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
 * @property string $type
 */
class PcPreviousStudies extends Model
{
    use SoftDeletes;

    public $table = 'pc_previous_studies';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'approval_leader',
        'approval_legal',
        'approval_financial',
        'approval_counsel',
        'approval_treasurer',
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
        'type',
        'date_project',
        'revisor_juridic',
        'leaders_name',
        'leaders_id',
        'users_id',
        'boss_process_id',
        'leader_process_id',
        'subgerente_process_id',
        'lawyer',
        'process',
        'pc_functioning_needs_id',
        'invitation_type',
        'account_approval',
        'proposed_status',
        'project_or_needs',
        
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
        'users_id' => 'integer',
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
        'determination_value' => 'integer',
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
        'type' => 'string',
        'revisor_juridic'=> 'integer',
        'invitation_type' => 'string',
        'proposed_status' => 'string',
        'project_or_needs' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function usersBoss()
    {
        return $this->belongsTo(\App\User::class, 'boss_process_id');
    }

    
        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function usersLeader()
    {
        return $this->belongsTo(\App\User::class, 'leader_process_id');
    }

            /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function usersSubgerente()
    {
        return $this->belongsTo(\App\User::class, 'subgerente_process_id');
    }

    
            /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function usersLawyer()
    {
        return $this->belongsTo(\App\User::class, 'lawyer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcPreviousStudiesTipifications()
    {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcPreviousStudiesTipification::class, 'pc_previous_studies_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcPreviousStudiesDocuments()
    {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcPreviousStudiesDocuments::class, 'pc_previous_studies_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcPreviousStudiesNews()
    {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcPreviousStudiesNews::class, 'pc_previous_studies_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcPreviousStudiesHistory()
    {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcPreviousStudiesHistory::class, 'pc_previous_studies_id');
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

    public function InvestmentSheets() {


             return $this->hasMany(\Modules\ContractualProcess\Models\PreviousStudiesInvestmentSheets::class, 'pc_previous_studies_id')->with(['pcInvestmentTechnicalSheets']);

        //return $this->belongsToMany('Modules\ContractualProcess\Models\PreviousStudiesInvestmentSheets');
    }

    
    public function functioningNeeds() {

        return $this->belongsTo(\Modules\ContractualProcess\Models\FunctioningNeed::class, 'pc_functioning_needs_id');

    }

    
}
