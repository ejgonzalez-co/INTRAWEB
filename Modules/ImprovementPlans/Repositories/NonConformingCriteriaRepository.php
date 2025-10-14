<?php

namespace Modules\ImprovementPlans\Repositories;

use Modules\ImprovementPlans\Models\NonConformingCriteria;
use App\Repositories\BaseRepository;

/**
 * Class NonConformingCriteriaRepository
 * @package Modules\ImprovementPlans\Repositories
 * @version September 26, 2023, 10:20 am -05
*/

class NonConformingCriteriaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'evaluations_id',
        'criteria_name',
        'status',
        'observations',
        'description_cause_analysis',
        'weight',
        'possible_causes',
        'execution_percentage'
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
        return NonConformingCriteria::class;
    }
}
