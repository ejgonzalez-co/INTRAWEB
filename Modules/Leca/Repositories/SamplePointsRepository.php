<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\SamplePoints;
use App\Repositories\BaseRepository;

/**
 * Class SamplePointsRepository
 * @package Modules\Leca\Repositories
 * @version November 9, 2021, 9:55 am -05
*/

class SamplePointsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'point_location',
        'no_samples_taken',
        'sector',
        'tank_feeding'
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
        return SamplePoints::class;
    }
}
