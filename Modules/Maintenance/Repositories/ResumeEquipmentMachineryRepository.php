<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\ResumeEquipmentMachinery;
use App\Repositories\BaseRepository;

/**
 * Class ResumeEquipmentMachineryRepository
 * @package Modules\Maintenance\Repositories
 * @version March 23, 2021, 4:59 pm -05
*/

class ResumeEquipmentMachineryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

        'warehouse_entry_number',
        'type_number_the_acquisition_contract',
        'requirement_for_operation',
        'description_for_operation',

        'catalog_specifications',
        'location',
        'language',
        'version',
        'technical_verification',
        'technical_verification_frequency',
        'preventive_maintenance',
        'preventive_maintenance_frequency',
        'person_responsible_team',
        'person_prepares_resume_equipment_machinery',

        'purchase_date',

        'name_equipment',
        'no_identification',
        'no_inventory',
        'mark',
        'model',
        'serie',
        'ubication',
        'no_invoice',
        'purchase_price',
        'equipment_warranty',
        'service_start_date',
        'retirement_date',
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
        return ResumeEquipmentMachinery::class;
    }
}
