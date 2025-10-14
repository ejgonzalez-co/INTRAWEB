<?php

namespace Modules\ImprovementPlans\Repositories;

use Modules\ImprovementPlans\Models\AnnotationEvaluation;
use App\Repositories\BaseRepository;

/**
 * Class AnnotationEvaluationRepository
 * @package Modules\ImprovementPlans\Repositories
 * @version November 24, 2023, 10:17 pm -05
*/

class AnnotationEvaluationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pm_evaluations_id',
        'users_id',
        'user_name',
        'observation'
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
        return AnnotationEvaluation::class;
    }
}
