<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\TireInformations;
use App\Repositories\BaseRepository;

/**
 * Class TireInformationsRepository
 * @package Modules\Maintenance\Repositories
 * @version September 7, 2021, 2:45 pm -05
*/

class TireInformationsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mant_tire_quantities_id',
        'mant_resume_machinery_vehicles_yellow_id',
        'mant_vehicle_fuels_id',
        'dependencias_id',
        'name_dependencias',
        'name_machinery',
        'plaque',
        'assignment_type',
        'date_assignment',
        'mant_set_tires_id',
        'date_register',
        'tire_reference',
        'position_tire',
        'type_tire',
        'cost_tire',
        'depth_tire',
        'mileage_initial',
        'available_depth',
        'general_cost_mm',
        'location_tire',
        'code_tire',
        'tire_brand',
        'observation_information',
        'state',
        'tire_wear',
        'tire_pressure',
        'max_wear',
        'inflation_pressure',
        'max_wear_for_retorquing',
        'reference_name'

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
        return TireInformations::class;
    }
}
