<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\MonthlyRoutinesHasUsers;
use App\Repositories\BaseRepository;

/**
 * Class MonthlyRoutinesHasUsersRepository
 * @package Modules\Leca\Repositories
 * @version December 6, 2021, 4:04 pm -05
*/

class MonthlyRoutinesHasUsersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_monthly_routines_id',
        'users_id',
        'lc_list_trials_id'
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
        return MonthlyRoutinesHasUsers::class;
    }
}
