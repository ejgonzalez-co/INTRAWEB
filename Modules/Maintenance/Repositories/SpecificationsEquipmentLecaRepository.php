<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\SpecificationsEquipmentLeca;
use App\Repositories\BaseRepository;

/**
 * Class SpecificationsEquipmentLecaRepository
 * @package Modules\Maintenance\Repositories
 * @version May 3, 2021, 11:38 am -05
*/

class SpecificationsEquipmentLecaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'calibration_verification_points',
        'reference_standard_calibration_verification',
        'acceptance_requirements',
        'mant_resume_equipment_leca_id'
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
        return SpecificationsEquipmentLeca::class;
    }
}
