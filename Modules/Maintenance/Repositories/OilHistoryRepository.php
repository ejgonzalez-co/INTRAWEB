<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\OilHistory;
use App\Repositories\BaseRepository;

/**
 * Class OilHistoryRepository
 * @package Modules\maintenance\Repositories
 * @version February 15, 2022, 5:48 pm -05
*/

class OilHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'action',
        'description',
        'name_user',
        'plaque',
        'dependencia',
        'consecutive'
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
        return OilHistory::class;
    }
}
