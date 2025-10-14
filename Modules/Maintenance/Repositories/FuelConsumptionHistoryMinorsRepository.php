<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\FuelConsumptionHistoryMinors;
use App\Repositories\BaseRepository;

/**
 * Class FuelConsumptionHistoryMinorsRepository
 * @package Modules\maintenance\Repositories
 * @version February 15, 2022, 9:42 am -05
*/

class FuelConsumptionHistoryMinorsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'action',
        'description',
        'name_user',
        'dependencia',
        'id_equipment_minor',
        'fuel_equipment_consumption'
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
        return FuelConsumptionHistoryMinors::class;
    }
}
