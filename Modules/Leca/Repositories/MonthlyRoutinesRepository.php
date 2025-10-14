<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\MonthlyRoutines;
use App\Repositories\BaseRepository;

/**
 * Class MonthlyRoutinesRepository
 * @package Modules\Leca\Repositories
 * @version November 20, 2021, 10:51 am -05
*/

class MonthlyRoutinesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'routine_start_date',
        'routine_end_date',
        'state_routine'
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
        return MonthlyRoutines::class;
    }
}
