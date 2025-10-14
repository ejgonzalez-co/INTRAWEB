<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\FuelHistory;
use App\Repositories\BaseRepository;

/**
 * Class FuelHistoryRepository
 * @package Modules\maintenance\Repositories
 * @version February 8, 2022, 3:33 pm -05
*/

class FuelHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'description',
        'plaque',
        'user_name',
        'id_fuel',
        'action'
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
        return FuelHistory::class;
    }
}
