<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;
use App\Repositories\BaseRepository;

/**
 * Class ResumeMachineryVehiclesYellowRepository
 * @package Modules\Maintenance\Repositories
 * @version March 15, 2021, 4:43 pm -05
*/

class ResumeMachineryVehiclesYellowRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name_vehicle_machinery',
        'no_inventory',
        'purchase_price',
        'sheet_elaboration_date',
        'mileage_start_activities',
        'mark',
        'model',
        'no_motor',
        'invoice_number',
        'date_put_into_service',
        'warranty_date',
        'service_retirement_date',

        'warehouse_entry_number',
        'warehouse_exit_number',
        'delivery_date_vehicle_by_provider',

        'plaque',
        'color',
        'chassis_number',
        'service_class',
        'body_type',
        'transit_license_number',
        'number_passengers',
        'fuel_type',
        'number_tires',
        'front_tire_reference',
        'rear_tire_reference',
        'number_batteries',
        'battery_reference',
        'gallon_tank_capacity',
        'tons_capacity_load',
        'cylinder_capacity',
        'expiration_date_soat',
        'expiration_date_tecnomecanica',

        'person_prepares_resume',
        'person_reviewed_approved',

        'observation',
        'status',
        'dependencias_id',
        'mant_category_id',
        'responsable',
        'mant_providers_id',
        'mant_asset_type_id'
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
        return ResumeMachineryVehiclesYellow::class;
    }
}
