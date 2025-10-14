<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\TireWears;
use App\Repositories\BaseRepository;

/**
 * Class TireWearsRepository
 * @package Modules\Maintenance\Repositories
 * @version September 8, 2021, 5:33 pm -05
*/

class TireWearsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mant_tire_informations_id',
        'registration_depth',
        'revision_date',
        'wear_total',
        'revision_mileage',
        'route_total',
        'wear_cost_mm',
        'cost_km',
        'revision_pressure',
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
        return TireWears::class;
    }
}
