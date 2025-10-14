<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\MinorEquipmentFuel;
use App\Repositories\BaseRepository;

/**
 * Class MinorEquipmentFuelRepository
 * @package Modules\Maintenance\Repositories
 * @version August 27, 2021, 2:29 pm -05
*/

class MinorEquipmentFuelRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'dependencias_id',
        'users_id',
        'responsible_process',
        'supply_date',
        'supply_hour',
        'start_date_fortnight',
        'end_date_fortnight',
        'initial_fuel_balance',
        'more_buy_fortnight',
        'less_fuel_deliveries',
        'final_fuel_balance',
        'bill_number',
        'gallon_value',
        'checked_fuel',
        'cost_in_pesos',
        'name',
        'position',
        'approved_process',
        'process_leader_name'
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
        return MinorEquipmentFuel::class;
    }
}
