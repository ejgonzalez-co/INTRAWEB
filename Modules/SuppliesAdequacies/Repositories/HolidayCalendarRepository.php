<?php

namespace Modules\SuppliesAdequacies\Repositories;

use Modules\SuppliesAdequacies\Models\HolidayCalendar;
use App\Repositories\BaseRepository;

/**
 * Class HolidayCalendarRepository
 * @package Modules\SuppliesAdequacies\Repositories
 * @version October 29, 2020, 7:26 pm -05
*/

class HolidayCalendarRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'date'
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
        return HolidayCalendar::class;
    }
}
