<?php

namespace Modules\ImprovementPlans\Repositories;

use Modules\ImprovementPlans\Models\GoalProgress;
use App\Repositories\BaseRepository;

/**
 * Class GoalProgressRepository
 * @package Modules\ImprovementPlans\Repositories
 * @version October 30, 2023, 10:09 pm -05
*/

class GoalProgressRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pm_goals_id',
        'pm_goal_activities_id',
        'goal_name',
        'activity_name',
        'activity_weigth',
        'progress_weigth',
        'evidence_description',
        'url_progress_evidence',
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
        return GoalProgress::class;
    }
}
