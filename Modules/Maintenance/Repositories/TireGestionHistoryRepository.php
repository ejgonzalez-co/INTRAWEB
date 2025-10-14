<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\TireGestionHistory;
use App\Repositories\BaseRepository;

/**
 * Class TireGestionHistoryRepository
 * @package Modules\maintenance\Repositories
 * @version February 15, 2022, 2:35 pm -05
*/

class TireGestionHistoryRepository extends BaseRepository
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
        'equipment'
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
        return TireGestionHistory::class;
    }
}
