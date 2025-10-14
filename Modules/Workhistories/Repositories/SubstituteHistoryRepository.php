<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\SubstituteHistory;
use App\Repositories\BaseRepository;

/**
 * Class SubstituteHistoryRepository
 * @package Modules\Workhistories\Repositories
 * @version December 7, 2020, 2:35 pm -05
*/

class SubstituteHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type_document',
        'number_document',
        'name',
        'surname',
        'address',
        'phone',
        'email',
        'depto',
        'city',
        'type_substitute',
        'date_document',
        'birth_date',
        'notification',
        'users_name',
        'work_histories_p_substitute_id',
        'users_id'
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
        return SubstituteHistory::class;
    }
}
