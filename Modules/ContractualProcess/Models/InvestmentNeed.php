<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class InvestmentNeed
 * @package Modules\ContractualProcess\Models
 * @version June 25, 2021, 3:16 pm -05
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
 * @property \Illuminate\Database\Eloquent\Collection $pcProjectAreaInfluences
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
 * @property boolean $municipal_development_plan
 * @property boolean $period
 * @property boolean $strategic_line
 * @property boolean $sector
 * @property boolean $program
 * @property boolean $subprogram
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
 * @property boolean $state
 */
class InvestmentNeed extends Model
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
        'state'
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
        'municipal_development_plan' => 'boolean',
        'period' => 'boolean',
        'strategic_line' => 'boolean',
        'sector' => 'boolean',
        'program' => 'boolean',
        'subprogram' => 'boolean',
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
        'state' => 'integer'
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
        'users_id' => 'required',
        'pc_project_lines_id' => 'required',
        'pc_poir_id' => 'required',
        'code_bppiepa' => 'nullable|string|max:100',
        'date_presentation' => 'nullable',
        'update_date' => 'nullable',
        'responsible_user' => 'nullable|string|max:45',
        'municipal_development_plan' => 'nullable|boolean',
        'period' => 'nullable|boolean',
        'strategic_line' => 'nullable|boolean',
        'sector' => 'nullable|boolean',
        'program' => 'nullable|boolean',
        'subprogram' => 'nullable|boolean',
        'other_planning_documents' => 'nullable|string',
        'which_other_document' => 'nullable|string',
        'description_problem_need' => 'nullable|string',
        'project_description' => 'nullable|string',
        'justification' => 'nullable|string',
        'background' => 'nullable|string',
        'general_objective' => 'nullable|string',
        'overall_goal' => 'nullable|string',
        'general_baseline' => 'nullable|string',
        'cost_units' => 'nullable|string|max:45',
        'replacement' => 'nullable|boolean',
        'expansion' => 'nullable|boolean',
        'rehabilitation' => 'nullable|boolean',
        'coverage' => 'nullable|boolean',
        'continuity' => 'nullable|boolean',
        'irca_water_quality_risk_index' => 'nullable|boolean',
        'micrometer' => 'nullable|boolean',
        'ianc_unaccounted_water_index' => 'nullable|boolean',
        'ipufi_loss_index_billed_user' => 'nullable|boolean',
        'icufi_index_water_consumed_user' => 'nullable|boolean',
        'isufi_supply_index_billed_user' => 'nullable|boolean',
        'ccpi_consumption_corrected_losses' => 'nullable|boolean',
        'pressure' => 'nullable|boolean',
        'discharge_treatment_index' => 'nullable|boolean',
        'tons_bbo_removed' => 'nullable|boolean',
        'tons_sst_removed' => 'nullable|boolean',
        'operational_claim_index' => 'nullable|boolean',
        'commercial_claim_index' => 'nullable|boolean',
        'efficiency_collection' => 'nullable|boolean',
        'via_aqueduct_sewerage_rates' => 'nullable|boolean',
        'cleaning_fee_resources' => 'nullable|boolean',
        'regalias' => 'nullable|boolean',
        'general_participation_system' => 'nullable|boolean',
        'decentralized_entity' => 'nullable|boolean',
        'capital_contributed' => 'nullable|boolean',
        'contributed_capital_official' => 'nullable|boolean',
        'capital_contributions' => 'nullable|boolean',
        'third_party_contributions' => 'nullable|boolean',
        'national_debt' => 'nullable|boolean',
        'foreign_debt' => 'nullable|boolean',
        'social' => 'nullable|string',
        'environmental' => 'nullable|string',
        'economical' => 'nullable|string',
        'jobs_to_generate' => 'nullable|string',
        'requires_environmental_license' => 'nullable|string|max:4',
        'license_number' => 'nullable|string|max:191',
        'expedition_date' => 'nullable',
        'state' => 'nullable|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcNeeds() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcNeed::class, 'pc_needs_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcPoir() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcPoir::class, 'pc_poir_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\Dependencia::class, 'dependencias_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcManagementUnit() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcManagementUnit::class, 'pc_management_unit_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcNameProjects() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcNameProject::class, 'pc_name_projects_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcProjectLines() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcProjectLine::class, 'pc_project_lines_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcValidities() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcValidity::class, 'pc_validities_id');
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
    public function pcAlternativeBudgets() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcAlternativeBudget::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcDirectCausesProblems() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcDirectCausesProblem::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcDirectEffectsProblems() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcDirectEffectsProblem::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcEnvironmentalImpacts() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcEnvironmentalImpact::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcIndirectCausesProblems() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcIndirectCausesProblem::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcIndirectEffectsProblems() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcIndirectEffectsProblem::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcInformationTariffHarmonizations() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcInformationTariffHarmonization::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcInvestmentTechnicalSheetsHistories() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcInvestmentTechnicalSheetsHistory::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcMonitoringIndicators() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcMonitoringIndicator::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcOtherPlanningDocuments() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcOtherPlanningDocument::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcProjectAreaInfluences() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcProjectAreaInfluence::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcResourceScheduleCurrentTerms() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcResourceScheduleCurrentTerm::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcScheduleResourcesPreviousPeriods() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcScheduleResourcesPreviousPeriod::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcSelectionAlternatives() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcSelectionAlternative::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcSpecificObjectives() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcSpecificObjective::class, 'pc_investment_technical_sheets_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcSupportingStudyData() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcSupportingStudyDatum::class, 'pc_investment_technical_sheets_id');
    }

    /**
     * Obtiene el nombre del estado
     * @return 
     */
    public function getStateNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.paa_needs_status'), 'id', $this->state)->name;
        
    }
}
