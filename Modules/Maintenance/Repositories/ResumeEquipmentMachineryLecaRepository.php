<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\ResumeEquipmentMachineryLeca;
use App\Repositories\BaseRepository;

/**
 * Class ResumeEquipmentMachineryLecaRepository
 * @package Modules\Maintenance\Repositories
 * @version April 29, 2021, 11:42 am -05
*/

class ResumeEquipmentMachineryLecaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name_equipment_machinery',
        'no_identification',
        'no_inventory_epa_esp',
        'mark',
        'model',
        'serie',
        'location',
        'path_information',
        'acquisition_contract',
        'provider_data',
        'apply',
        'location_specification',
        'language',
        'version',
        'purchase_date',
        'commissioning_date',
        'date_withdrawal_service',
        'observations',
        'vo_bo_name',
        'vo_bo_cargo',
        'magnitude',
        'unit_measurement',
        'scale_division',
        'manufacturer_specification_max_permissible_error',
        'max_permissible_error_technical_standard_process',
        'measurement_range',
        'operation_range',
        'use_parameter',
        'use_recommendations',
        'resolution',
        'analog_indication',
        'digital_indication',
        'wavelength_indication',
        'adsorption_indication',
        'feeding',
        'voltage',
        'RH',
        'power',
        'temperature',
        'frequency',
        'revolutions_per_minute',
        'type_protection',
        'rated_current',
        'rated_power',
        'operating_conditions',
        'calibration_validation_external_verification',
        'calibration_frequency',
        'preventive_maintenance',
        'maintenance_frequency',
        'verification_internal_verification',
        'verification_frequency',
        'procedure_code',
        'calibration_points',
        'calibration_under_accreditation',
        'reference_norm',
        'measure_pattern',
        'criteria_acceptance',
        'calibration_test',
        'dependencias_id',
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
        return ResumeEquipmentMachineryLeca::class;
    }
}
