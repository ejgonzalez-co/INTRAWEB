<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\MonthlyRoutinesOfficials;
use App\Repositories\BaseRepository;

/**
 * Class MonthlyRoutinesOfficialsRepository
 * @package Modules\Leca\Repositories
 * @version December 13, 2021, 11:12 am -05
*/

class MonthlyRoutinesOfficialsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_monthly_routines_id',
        'users_id',
        'user_name'
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
        return MonthlyRoutinesOfficials::class;
    }
}
