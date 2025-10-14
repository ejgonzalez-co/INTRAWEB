<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\InvestmentNeed;
use App\Repositories\BaseRepository;

/**
 * Class InvestmentNeedRepository
 * @package Modules\ContractualProcess\Repositories
 * @version June 25, 2021, 3:16 pm -05
*/

class InvestmentNeedRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return InvestmentNeed::class;
    }
}
