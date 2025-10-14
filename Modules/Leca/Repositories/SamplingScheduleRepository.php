<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\SamplingSchedule;
use App\Repositories\BaseRepository;

/**
 * Class SamplingScheduleRepository
 * @package Modules\Leca\Repositories
 * @version November 24, 2021, 5:32 pm -05
*/

class SamplingScheduleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_sample_points_id',
        'lc_officials_id',
        'users_id',
        'sampling_date',
        'direction',
        'observation'
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
        return SamplingSchedule::class;
    }
}
