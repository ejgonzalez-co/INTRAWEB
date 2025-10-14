<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class InvestmentTechnicalSheet
 * @package Modules\ContractualProcess\Models
 * @version June 21, 2021, 8:57 am -05
 *
 * @property Modules\ContractualProcess\Models\PcNeed $pcNeeds
 * @property Modules\ContractualProcess\Models\PcPoir $pcPoir
 * @property Modules\ContractualProcess\Models\Dependencia $dependencias
 * @property Modules\ContractualProcess\Models\PcManagementUnit $pcManagementUnit
 * @property Modules\ContractualProcess\Models\PcNameProject $pcNameProjects
 * @property Modules\ContractualProcess\Models\PcProjectLine $pcProjectLines
 * @property Modules\ContractualProcess\Models\PcValidity $pcValidities
 * @property Modules\ContractualProcess\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $pcAlternativeBudgets
 * @property \Illuminate\Database\Eloquent\Collection $pcDirectCausesProblems
 * @property \Illuminate\Database\Eloquent\Collection $pcDirectEffectsProblems
 * @property \Illuminate\Database\Eloquent\Collection $pcEnvironmentalImpacts
 * @property \Illuminate\Database\Eloquent\Collection $pcIndirectCausesProblems
 * @property \Illuminate\Database\Eloquent\Collection $pcIndirectEffectsProblems
 * @property \Illuminate\Database\Eloquent\Collection $pcInformationTariffHarmonizations
 * @property \Illuminate\Database\Eloquent\Collection $pcInvestmentTechnicalSheetsHistories
 * @property \Illuminate\Database\Eloquent\Collection $pcMonitoringIndicators
 * @property \Illuminate\Database\Eloquent\Collection $pcOtherPlanningDocuments
 * @property \Illuminate\Database\Eloquent\Collection $pcProjectAreasInfluences
 * @property \Illuminate\Database\Eloquent\Collection $pcResourceScheduleCurrentTerms
 * @property \Illuminate\Database\Eloquent\Collection $pcScheduleResourcesPreviousPeriods
 * @property \Illuminate\Database\Eloquent\Collection $pcSelectionAlternatives
 * @property \Illuminate\Database\Eloquent\Collection $pcSpecificObjectives
 * @property \Illuminate\Database\Eloquent\Collection $pcSupportingStudyData
 * @property integer $pc_needs_id
 * @property integer $pc_validities_id
 * @property integer $pc_name_projects_id
 * @property integer $dependencias_id
 * @property integer $pc_management_unit_id
 * @property integer $users_id
 * @property integer $pc_project_lines_id
 * @property integer $pc_poir_id
 * @property string $code_bppiepa
 * @property string|\Carbon\Carbon $date_presentation
 * @property string|\Carbon\Carbon $update_date
 * @property string $responsible_user
 * @property integer $municipal_development_plan
 * @property integer $period
 * @property integer $strategic_line
 * @property integer $sector
 * @property integer $program
 * @property integer $subprogram
 * @property string $other_planning_documents
 * @property string $which_other_document
 * @property string $description_problem_need
 * @property string $project_description
 * @property string $justification
 * @property string $background
 * @property string $general_objective
 * @property string $overall_goal
 * @property string $general_baseline
 * @property string $cost_units
 * @property boolean $replacement
 * @property boolean $expansion
 * @property boolean $rehabilitation
 * @property boolean $coverage
 * @property boolean $continuity
 * @property boolean $irca_water_quality_risk_index
 * @property boolean $micrometer
 * @property boolean $ianc_unaccounted_water_index
 * @property boolean $ipufi_loss_index_billed_user
 * @property boolean $icufi_index_water_consumed_user
 * @property boolean $isufi_supply_index_billed_user
 * @property boolean $ccpi_consumption_corrected_losses
 * @property boolean $pressure
 * @property boolean $discharge_treatment_index
 * @property boolean $tons_bbo_removed
 * @property boolean $tons_sst_removed
 * @property boolean $operational_claim_index
 * @property boolean $commercial_claim_index
 * @property boolean $efficiency_collection
 * @property boolean $via_aqueduct_sewerage_rates
 * @property boolean $cleaning_fee_resources
 * @property boolean $regalias
 * @property boolean $general_participation_system
 * @property boolean $decentralized_entity
 * @property boolean $capital_contributed
 * @property boolean $contributed_capital_official
 * @property boolean $capital_contributions
 * @property boolean $third_party_contributions
 * @property boolean $national_debt
 * @property boolean $foreign_debt
 * @property string $social
 * @property string $environmental
 * @property string $economical
 * @property string $jobs_to_generate
 * @property string $requires_environmental_license
 * @property string $license_number
 * @property string|\Carbon\Carbon $expedition_date
 * @property integer $state
 */
class InvestmentTechnicalSheet extends Model
{
        use SoftDeletes;

    public $table = 'pc_investment_technical_sheets';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'pc_needs_id',
        'pc_validities_id',
        'pc_name_projects_id',
        'dependencias_id',
        'pc_management_unit_id',
        'users_id',
        'pc_project_lines_id',
        'pc_poir_id',
        'code_bppiepa',
        'date_presentation',
        'update_date',
        'responsible_user',
        'municipal_development_plan',
        'period',
        'strategic_line',
        'sector',
        'program',
        'subprogram',
        'other_planning_documents',
        'which_other_document',
        'description_problem_need',
        'project_description',
        'justification',
        'background',
        'general_objective',
        'overall_goal',
        'general_baseline',
        'cost_units',
        'replacement',
        'expansion',
        'rehabilitation',
        'coverage',
        'continuity',
        'irca_water_quality_risk_index',
        'micrometer',
        'ianc_unaccounted_water_index',
        'ipufi_loss_index_billed_user',
        'icufi_index_water_consumed_user',
        'isufi_supply_index_billed_user',
        'ccpi_consumption_corrected_losses',
        'pressure',
        'discharge_treatment_index',
        'tons_bbo_removed',
        'tons_sst_removed',
        'operational_claim_index',
        'commercial_claim_index',
        'efficiency_collection',
        'another_goal',
        'via_aqueduct_sewerage_rates',
        'cleaning_fee_resources',
        'regalias',
        'general_participation_system',
        'decentralized_entity',
        'capital_contributed',
        'contributed_capital_official',
        'capital_contributions',
        'third_party_contributions',
        'national_debt',
        'foreign_debt',
        'social',
        'environmental',
        'economical',
        'jobs_to_generate',
        'requires_environmental_license',
        'license_number',
        'expedition_date',
        'state',
    ];

    protected $appends = [
        'municipal_development_plan_name',
        'period_name',
        'strategic_line_name',
        'sector_name',
        'program_name',
        'subprogram_name',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_needs_id' => 'integer',
        'pc_validities_id' => 'integer',
        'pc_name_projects_id' => 'integer',
        'dependencias_id' => 'integer',
        'pc_management_unit_id' => 'integer',
        'users_id' => 'integer',
        'pc_project_lines_id' => 'integer',
        'pc_poir_id' => 'integer',
        'code_bppiepa' => 'string',
        'date_presentation' => 'datetime',
        'update_date' => 'datetime',
        'responsible_user' => 'string',
        'municipal_development_plan' => 'integer',
        'period' => 'integer',
        'strategic_line' => 'integer',
        'sector' => 'integer',
        'program' => 'integer',
        'subprogram' => 'integer',
        'other_planning_documents' => 'string',
        'which_other_document' => 'string',
        'description_problem_need' => 'string',
        'project_description' => 'string',
        'justification' => 'string',
        'background' => 'string',
        'general_objective' => 'string',
        'overall_goal' => 'string',
        'general_baseline' => 'string',
        'cost_units' => 'string',
        'replacement' => 'boolean',
        'expansion' => 'boolean',
        'rehabilitation' => 'boolean',
        'coverage' => 'boolean',
        'continuity' => 'boolean',
        'irca_water_quality_risk_index' => 'boolean',
        'micrometer' => 'boolean',
        'ianc_unaccounted_water_index' => 'boolean',
        'ipufi_loss_index_billed_user' => 'boolean',
        'icufi_index_water_consumed_user' => 'boolean',
        'isufi_supply_index_billed_user' => 'boolean',
        'ccpi_consumption_corrected_losses' => 'boolean',
        'pressure' => 'boolean',
        'discharge_treatment_index' => 'boolean',
        'tons_bbo_removed' => 'boolean',
        'tons_sst_removed' => 'boolean',
        'operational_claim_index' => 'boolean',
        'commercial_claim_index' => 'boolean',
        'efficiency_collection' => 'boolean',
        'via_aqueduct_sewerage_rates' => 'boolean',
        'cleaning_fee_resources' => 'boolean',
        'regalias' => 'boolean',
        'general_participation_system' => 'boolean',
        'decentralized_entity' => 'boolean',
        'capital_contributed' => 'boolean',
        'contributed_capital_official' => 'boolean',
        'capital_contributions' => 'boolean',
        'third_party_contributions' => 'boolean',
        'national_debt' => 'boolean',
        'foreign_debt' => 'boolean',
        'social' => 'string',
        'environmental' => 'string',
        'economical' => 'string',
        'jobs_to_generate' => 'string',
        'requires_environmental_license' => 'string',
        'license_number' => 'string',
        'expedition_date' => 'datetime',
        'state' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pc_needs_id' => 'required',
        'pc_validities_id' => 'required',
        'pc_name_projects_id' => 'required',
        'dependencias_id' => 'required',
        'pc_management_unit_id' => 'required',
        'pc_project_lines_id' => 'required'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function needs() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\Need::class, 'pc_needs_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function poir() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\Poir::class, 'pc_poir_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencies() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function managementUnit() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\ManagementUnit::class, 'pc_management_unit_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function nameProjects() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\NameProject::class, 'pc_name_projects_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function projectLines() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\ProjectLine::class, 'pc_project_lines_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function validities() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\Validity::class, 'pc_validities_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\User::class, 'users_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function alternativeBudgets() {
        return $this->hasMany(\Modules\ContractualProcess\Models\AlternativeBudget::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function directCausesProblems() {
        return $this->hasMany(\Modules\ContractualProcess\Models\DirectCausesProblem::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function directEffectsProblems() {
        return $this->hasMany(\Modules\ContractualProcess\Models\DirectEffectsProblem::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function environmentalImpacts() {
        return $this->hasMany(\Modules\ContractualProcess\Models\EnvironmentalImpact::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function indirectCausesProblems() {
        return $this->hasMany(\Modules\ContractualProcess\Models\IndirectCausesProblem::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function indirectEffectsProblems() {
        return $this->hasMany(\Modules\ContractualProcess\Models\IndirectEffectsProblem::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function informationTariffHarmonizations() {
        return $this->hasMany(\Modules\ContractualProcess\Models\InformationTariffHarmonization::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function investmentTechnicalSheetsHistories() {
        return $this->hasMany(\Modules\ContractualProcess\Models\InvestmentTechnicalSheetsHistory::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function monitoringIndicators() {
        return $this->hasMany(\Modules\ContractualProcess\Models\MonitoringIndicator::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function otherPlanningDocuments() {
        return $this->hasMany(\Modules\ContractualProcess\Models\OtherPlanningDocument::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function projectAreasInfluences() {
        return $this->hasMany(\Modules\ContractualProcess\Models\ProjectAreaInfluence::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function resourceScheduleCurrentTerms() {
        return $this->hasMany(\Modules\ContractualProcess\Models\ResourceScheduleCurrentTerm::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function scheduleResourcesPreviousPeriods() {
        return $this->hasMany(\Modules\ContractualProcess\Models\ScheduleResourcesPreviousPeriod::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function selectionAlternatives() {
        return $this->hasMany(\Modules\ContractualProcess\Models\SelectionAlternative::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function specificObjectives() {
        return $this->hasMany(\Modules\ContractualProcess\Models\SpecificObjective::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function supportingStudyData() {
        return $this->hasMany(\Modules\ContractualProcess\Models\SupportingStudyData::class, 'pc_investment_technical_sheets_id');
    }

    /**
     * Obtiene el nombre del Plan de Desarrollo Municipal
     * @return 
     */
    public function getMunicipalDevelopmentPlanNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.investment_technical_municipal_development_plan'), 'id', $this->municipal_development_plan)->name;   
    }

    /**
     * Obtiene el nombre del perido
     * @return 
     */
    public function getPeriodNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.investment_technical_period'), 'id', $this->period)->name;   
    }

    /**
     * Obtiene el nombre de la linea estrategica
     * @return 
     */
    public function getStrategicLineNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.investment_technical_strategic_line'), 'id', $this->strategic_line)->name;   
    }

    /**
     * Obtiene el nombre del sector
     * @return 
     */
    public function getSectorNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.investment_technical_sector'), 'id', $this->sector)->name;   
    }

    /**
     * Obtiene el nombre del programa
     * @return 
     */
    public function getProgramNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.investment_technical_program'), 'id', $this->program)->name;   
    }

    /**
     * Obtiene el nombre del subprograma
     * @return 
     */
    public function getSubprogramNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.investment_technical_subprogram'), 'id', $this->subprogram)->name;   
    }
}
