<?php

namespace Modules\ImprovementPlans\Repositories;

use Modules\ImprovementPlans\Models\TypeEvaluation;
use App\Repositories\BaseRepository;

/**
 * Class TypeEvaluationRepository
 * @package Modules\ImprovementPlans\Repositories
 * @version August 25, 2023, 4:35 pm -05
*/

class TypeEvaluationRepository extends BaseRepository
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
        return TypeEvaluation::class;
    }
}
