<?php

namespace Modules\ImprovementPlans\Repositories;

use Modules\ImprovementPlans\Models\ImprovementOpportunity;
use App\Repositories\BaseRepository;

/**
 * Class ImprovementOpportunityRepository
 * @package Modules\ImprovementPlans\Repositories
 * @version August 30, 2023, 10:55 pm -05
*/

class ImprovementOpportunityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'evaluations_id',
        'source_information_id',
        'type_oportunity_improvements_id',
        'name_opportunity_improvement',
        'description_opportunity_improvement',
        'unit_responsible_improvement_opportunity',
        'official_responsible',
        'deadline_submission',
        'evidence',
        'evaluation_criteria',
        'evaluation_criteria_id',
        'weight',
        'dependencia_id',
        'official_responsible_id',
        'description_cause_analysis',
        'possible_causes'
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
        return ImprovementOpportunity::class;
    }
}
