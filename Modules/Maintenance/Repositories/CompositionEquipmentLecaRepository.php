<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\CompositionEquipmentLeca;
use App\Repositories\BaseRepository;

/**
 * Class CompositionEquipmentLecaRepository
 * @package Modules\Maintenance\Repositories
 * @version April 29, 2021, 11:46 am -05
*/

class CompositionEquipmentLecaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'accessory_parts',
        'reference',
        'observation',
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
        return CompositionEquipmentLeca::class;
    }
}
