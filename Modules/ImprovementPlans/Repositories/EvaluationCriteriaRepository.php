<?php

namespace Modules\ImprovementPlans\Repositories;

use Modules\ImprovementPlans\Models\EvaluationCriteria;
use App\Repositories\BaseRepository;

/**
 * Class EvaluationCriteriaRepository
 * @package Modules\ImprovementPlans\Repositories
 * @version August 26, 2023, 3:04 pm -05
*/

class EvaluationCriteriaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'name',
        'status'
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
        return EvaluationCriteria::class;
    }
}
