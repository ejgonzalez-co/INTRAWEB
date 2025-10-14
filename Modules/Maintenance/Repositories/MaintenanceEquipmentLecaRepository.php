<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\MaintenanceEquipmentLeca;
use App\Repositories\BaseRepository;

/**
 * Class MaintenanceEquipmentLecaRepository
 * @package Modules\Maintenance\Repositories
 * @version April 29, 2021, 11:46 am -05
*/

class MaintenanceEquipmentLecaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'acceptance_requirements',
        'mant_resume_equipment_machinery_leca_id'
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
        return MaintenanceEquipmentLeca::class;
    }
}
