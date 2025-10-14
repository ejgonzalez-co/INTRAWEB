<?php

namespace Modules\ImprovementPlans\Repositories;

use Modules\ImprovementPlans\Models\Evaluation;
use App\Repositories\BaseRepository;

/**
 * Class EvaluationRepository
 * @package Modules\ImprovementPlans\Repositories
 * @version August 30, 2023, 3:22 pm -05
*/

class EvaluationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'evaluator_id',
        'evaluated_id',
        'type_evaluation',
        'evaluation_name',
        'objective_evaluation',
        'evaluation_scope',
        'evaluation_site',
        'evaluation_start_date',
        'evaluation_start_time',
        'evaluation_end_date',
        'evaluation_end_time',
        'unit_responsible_for_evaluation',
        'evaluation_officer',
        'process',
        'attached',
        'status',
        'evaluation_process_attachment',
        'general_description_evaluation_results',
        'name_improvement_plan',
        'type_name_improvement_plan',
        'is_accordance',
        'execution_percentage',
        'no_improvement_plan',
        'status_improvement_plan'
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
        return Evaluation::class;
    }
}
