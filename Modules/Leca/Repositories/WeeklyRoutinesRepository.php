<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\WeeklyRoutines;
use App\Repositories\BaseRepository;

/**
 * Class WeeklyRoutinesRepository
 * @package Modules\Leca\Repositories
 * @version November 22, 2021, 10:36 am -05
*/

class WeeklyRoutinesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'lc_monthly_routines_id'
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
        return WeeklyRoutines::class;
    }
}
