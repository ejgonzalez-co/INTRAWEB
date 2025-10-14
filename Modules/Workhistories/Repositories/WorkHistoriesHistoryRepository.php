<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\WorkHistoriesHistory;
use App\Repositories\BaseRepository;

/**
 * Class WorkHistoriesHistoryRepository
 * @package Modules\Workhistories\Repositories
 * @version October 23, 2020, 5:46 pm -05
*/

class WorkHistoriesHistoryRepository extends BaseRepository
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
        'gender',
        'group_ethnic',
        'birth_date',
        'notification',
        'level_study',
        'phone_event',
        'name_event',
        'state',
        'work_histories_id'
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
        return WorkHistoriesHistory::class;
    }
}
