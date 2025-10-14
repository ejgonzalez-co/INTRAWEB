<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\InflationPressure;
use App\Repositories\BaseRepository;

/**
 * Class InflationPressureRepository
 * @package Modules\Maintenance\Repositories
 * @version August 30, 2021, 3:10 pm -05
*/

class InflationPressureRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'registration_date',
        'tire_reference',
        'inflation_pressure',
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
        return InflationPressure::class;
    }
}
