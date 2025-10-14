<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\tireInformationHistory;
use App\Repositories\BaseRepository;

/**
 * Class tireInformationHistoryRepository
 * @package Modules\Maintenance\Repositories
 * @version March 28, 2022, 11:21 am -05
*/

class tireInformationHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'mant_tire_quantities_id',
        'user_name',
        'action',
        'description',
        'plaque',
        'dependencia',
        'number',
        'position',
        'brand'
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
        return tireInformationHistory::class;
    }
}
