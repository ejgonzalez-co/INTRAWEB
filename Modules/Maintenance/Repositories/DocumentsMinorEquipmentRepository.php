<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\DocumentsMinorEquipment;
use App\Repositories\BaseRepository;

/**
 * Class DocumentsMinorEquipmentRepository
 * @package Modules\Maintenance\Repositories
 * @version August 27, 2021, 2:33 pm -05
*/

class DocumentsMinorEquipmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mant_minor_equipment_fuel_id',
        'name',
        'url'
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
        return DocumentsMinorEquipment::class;
    }
}
