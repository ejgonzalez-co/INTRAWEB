<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\ResumeInventoryLeca;
use App\Repositories\BaseRepository;

/**
 * Class ResumeInventoryLecaRepository
 * @package Modules\Maintenance\Repositories
 * @version May 6, 2021, 5:24 pm -05
*/

class ResumeInventoryLecaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'no_inventory_epa_esp',
        'leca_code',
        'description_equipment_name',
        'maker',
        'serial_number',
        'model',
        'location',
        'measured_used',
        'unit_measurement',
        'resolution',
        'manufacturer_error',
        'operation_range',
        'range_use',
        'operating_conditions_temperature',
        'condition_oper_elative_humidity_hr',
        'condition_oper_voltage_range',
        'maintenance_metrological_operation_frequency',
        'calibration_metrological_operating_frequency',
        'qualification_metrological_operating_frequency',
        'intermediate_verification_metrological_operating_frequency',
        'total_interventions',
        'name_elaborated',
        'cargo_role_elaborated',
        'name_updated',
        'cargo_role_updated',
        'technical_director',
        'mant_category_id',
        'responsable',
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
        return ResumeInventoryLeca::class;
    }
}
