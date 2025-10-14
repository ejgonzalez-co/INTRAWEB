<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\PcPreviousStudiesHistory;
use App\Repositories\BaseRepository;

/**
 * Class PcPreviousStudiesHistoryRepository
 * @package Modules\ContractualProcess\Repositories
 * @version March 1, 2021, 10:26 am -05
*/

class PcPreviousStudiesHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
        'users_name',
        'pc_previous_studies_id'
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
        return PcPreviousStudiesHistory::class;
    }
}
