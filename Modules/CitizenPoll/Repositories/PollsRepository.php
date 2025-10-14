<?php

namespace Modules\CitizenPoll\Repositories;

use Modules\CitizenPoll\Models\Polls;
use App\Repositories\BaseRepository;

/**
 * Class PollsRepository
 * @package Modules\CitizenPoll\Repositories
 * @version April 30, 2021, 4:07 pm -05
*/

class PollsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'gender',
        'email',
        'direction_state',
        'phone',
        'suscriber_quaity',
        'aqueduct',
        'sewerage',
        'cleanliness',
        'attention_qualification_respect',
        'attention_solve_problems',
        'qualification_waiting_time',
        'time_solution_petition',
        'chance',
        'reports_effectiveness',
        'aqueduct_benefit_service',
        'aqueduct_continuity_service',
        'sewerage_benefit_service',
        'cleanliness_benefit_service',
        'cleanliness_qualification_service'
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
        return Polls::class;
    }
}
