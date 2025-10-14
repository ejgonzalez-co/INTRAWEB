<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\ScheduleInventoryLeca;
use App\Repositories\BaseRepository;

/**
 * Class ScheduleInventoryLecaRepository
 * @package Modules\Maintenance\Repositories
 * @version May 6, 2021, 5:23 pm -05
*/

class ScheduleInventoryLecaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'month',
        'metrological_activity',
        'description',
        'mant_inventory_metrological_schedule_leca_id'
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
        return ScheduleInventoryLeca::class;
    }
}
