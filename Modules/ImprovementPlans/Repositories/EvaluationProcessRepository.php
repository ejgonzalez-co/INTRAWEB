<?php

namespace Modules\ImprovementPlans\Repositories;

use Modules\ImprovementPlans\Models\EvaluationProcess;
use App\Repositories\BaseRepository;

/**
 * Class EvaluationProcessRepository
 * @package Modules\ImprovementPlans\Repositories
 * @version August 25, 2023, 2:41 pm -05
*/

class EvaluationProcessRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'state',
        'id_user'
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
        return EvaluationProcess::class;
    }
}
