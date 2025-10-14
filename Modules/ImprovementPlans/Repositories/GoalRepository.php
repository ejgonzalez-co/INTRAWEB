<?php

namespace Modules\ImprovementPlans\Repositories;

use Modules\ImprovementPlans\Models\Goal;
use App\Repositories\BaseRepository;

/**
 * Class GoalRepository
 * @package Modules\ImprovementPlans\Repositories
 * @version September 26, 2023, 3:52 pm -05
*/

class GoalRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'goal_type',
        'goal_name',
        'goal_weight',
        'indicator_description',
        'execution_percentage',
        'commitment_date'
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
        return Goal::class;
    }
}
