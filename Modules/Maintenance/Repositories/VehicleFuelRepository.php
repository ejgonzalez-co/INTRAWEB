<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\VehicleFuel;
use App\Repositories\BaseRepository;

/**
 * Class VehicleFuelRepository
 * @package Modules\Maintenance\Repositories
 * @version August 13, 2021, 5:22 pm -05
*/

class VehicleFuelRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'dependencies_id',
        'mant_resume_machinery_vehicles_yellow_id',
        'mant_asset_type_id',
        'asset_name',
        'invoice_number',
        'invoice_date',
        'tanking_hour',
        'driver_name',
        'fuel_type',
        'current_mileage',
        'fuel_quantity',
        'gallon_price',
        'total_price',
        'current_hourmeter',
        'previous_hourmeter',
        'variation_tanking_hour',
        'previous_mileage',
        'variation_route_hour',
        'performance_by_gallon'
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
        return VehicleFuel::class;
    }
}
