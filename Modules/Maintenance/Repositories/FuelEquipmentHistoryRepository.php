<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\FuelEquipmentHistory;
use App\Repositories\BaseRepository;

/**
 * Class FuelEquipmentHistoryRepository
 * @package Modules\maintenance\Repositories
 * @version February 14, 2022, 8:29 am -05
*/

class FuelEquipmentHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'date_register',
        'description',
        'action',
        'name_user',
        'dependencia',
        'id_equipment_fuel'
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
        return FuelEquipmentHistory::class;
    }
}
