<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\EquipmentMinorFuelConsumption;
use App\Repositories\BaseRepository;

/**
 * Class EquipmentMinorFuelConsumptionRepository
 * @package Modules\Maintenance\Repositories
 * @version August 27, 2021, 2:31 pm -05
*/

class EquipmentMinorFuelConsumptionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mant_minor_equipment_fuel_id',
        'supply_date',
        'process',
        'equipment_description',
        'gallons_supplied',
        'name_receives_equipment'
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
        return EquipmentMinorFuelConsumption::class;
    }
}
