<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\CharacteristicsEquipment;
use App\Repositories\BaseRepository;

/**
 * Class CharacteristicsEquipmentRepository
 * @package Modules\Maintenance\Repositories
 * @version April 23, 2021, 8:41 am -05
*/

class CharacteristicsEquipmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'accessory_parts',
        'amount',
        'reference_part_number',
        'mant_resume_equipment_machinery_id'
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
        return CharacteristicsEquipment::class;
    }
}
