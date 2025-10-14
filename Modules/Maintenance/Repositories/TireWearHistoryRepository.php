<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\TireWearHistory;
use App\Repositories\BaseRepository;

/**
 * Class TireWearHistoryRepository
 * @package Modules\Maintenance\Repositories
 * @version June 10, 2022, 2:43 pm -05
*/

class TireWearHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mant_tire_wears_id',
        'users_id',
        'user_name',
        'action',
        'plaque',
        'position',
        'revision_pressure',
        'revision_mileage',
        'wear_total',
        'status',
        'observation',
        'descripcion'
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
        return TireWearHistory::class;
    }
}
